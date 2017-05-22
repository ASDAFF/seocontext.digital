;(function() {

"use strict";

BX.namespace("BX.Kanban");

/**
 *
 * @param {object} options
 * @param {Element} options.renderTo
 * @param {BX.Kanban.Column[]} [options.columns]
 * @param {BX.Kanban.Item[]} [options.items]
 * @param {BX.Kanban.DropZone[]} [options.dropzones]
 * @param {object} [options.events]
 * @param {string} [options.itemType]
 * @param {string} [options.columnType]
 * @param {bool} [options.canAddColumn]
 * @param {bool} [options.canEditColumn]
 * @param {bool} [options.canRemoveColumn]
 * @param {bool} [options.canAddItem]
 * @param {string} [options.dropZoneType]
 * @param {string} [options.bgColor]
 * @param {object} [options.data] Custom Data For Grid
 * @constructor
 */
BX.Kanban.Grid = function(options)
{
	if (!BX.type.isPlainObject(options))
	{
		throw new Error("BX.Kanban.Grid: 'options' is not an object.");
	}

	this.options = options;

	if (!BX.type.isDomNode(options.renderTo))
	{
		throw new Error("BX.Kanban.Grid: 'renderTo' is not a DOMNode.");
	}

	this.renderTo = options.renderTo;
	this.rendered = false;

	this.layout = {
		outerContainer: null,
		innerContainer: null,
		gridContainer: null,
		earLeft: null,
		earRight: null,
		dropzone: null,
		emptyStub: null
	};

	this.itemType = this.getItemType(options.itemType);
	this.columnType = this.getColumnType(options.columnType);
	this.dropZoneType = this.getDropZoneType(options.dropZoneType);

	this.columns = Object.create(null);
	this.columnsOrder = [];
	this.items = Object.create(null);
	this.dropzones = Object.create(null);

	this.data = BX.type.isPlainObject(options.data) ? options.data : Object.create(null);
	this.bgColor = BX.Kanban.Utils.isValidColor(options.bgColor) ? options.bgColor : "ffffff";

	this.earTimer = null;
	this.dragMode = BX.Kanban.DragMode.NONE;

	/** @private **/
	this.canAddColumn = BX.type.isBoolean(options.canAddColumn) ? options.canAddColumn : false;
	/** @private **/
	this.canEditColumn = BX.type.isBoolean(options.canEditColumn) ? options.canEditColumn : false;
	/** @private **/
	this.canRemoveColumn = BX.type.isBoolean(options.canRemoveColumn) ? options.canRemoveColumn : false;
	/** @private **/
	this.canAddItem = BX.type.isBoolean(options.canAddItem) ? options.canAddItem : false;

	this.data = Object.create(null);
	this.setData(options.data);

	this.loadData(options);

	if (options.events)
	{
		for (var eventName in options.events)
		{
			if (options.events.hasOwnProperty(eventName))
			{
				BX.addCustomEvent(this, "Kanban.Grid:" + eventName, options.events[eventName]);
			}
		}
	}

	BX.addCustomEvent(this, "Kanban.Grid:onItemDragStart", BX.delegate(this.onItemDragStart, this));
	BX.addCustomEvent(this, "Kanban.Grid:onItemDragStop", BX.delegate(this.onItemDragStop, this));

	BX.addCustomEvent(this, "Kanban.Grid:onColumnDragStart", BX.delegate(this.onColumnDragStart, this));
	BX.addCustomEvent(this, "Kanban.Grid:onColumnDragStop", BX.delegate(this.onColumnDragStop, this));
};

/**
 *
 * @enum {number}
 */
BX.Kanban.DragMode = {
	NONE: 0,
	ITEM: 1,
	COLUMN: 2
};

BX.Kanban.Grid.prototype =
{
	/**
	 *
	 * @param {object} options
	 * @returns {BX.Kanban.Column|null}
	 */
	addColumn: function(options)
	{
		options = options || {};

		if (this.getColumn(options.id) !== null)
		{
			return null;
		}

		var columnType = this.getColumnType(options.type);
		var column = new columnType(options);
		if (! column instanceof BX.Kanban.Column)
		{
			throw new Error("Column type must be an instance of BX.Kanban.Column");
		}

		column.setGrid(this);
		this.columns[column.getId()] = column;

		var targetColumn = this.getColumn(options.targetId);
		var targetIndex = BX.util.array_search(targetColumn, this.columnsOrder);
		if (targetIndex >= 0)
		{
			this.columnsOrder.splice(targetIndex, 0, column);
		}
		else
		{
			this.columnsOrder.push(column);
		}

		if (this.isRendered())
		{
			if (targetColumn)
			{
				this.getGridContainer().insertBefore(column.render(), targetColumn.getContainer());
			}
			else
			{
				this.getGridContainer().appendChild(column.render());
			}
		}

		return column;
	},

	/**
	 *
	 * @param {BX.Kanban.Column|string|number} column
	 * @returns {boolean}
	 */
	removeColumn: function(column)
	{
		column = this.getColumn(column);
		if (!column)
		{
			return false;
		}

		this.removeColumnItems(column);

		this.columnsOrder = this.columnsOrder.filter(function(element) {
			return column !== element;
		});

		delete this.columns[column.getId()];

		BX.remove(column.getContainer());

		return true;
	},

	updateColumn: function(column, options)
	{
		column = this.getColumn(column);
		if (!column)
		{
			return false;
		}

		column.setOptions(options);
		column.render();

		return true;
	},

	/**
	 *
	 * @param {BX.Kanban.Column} currentColumn
	 * @returns {BX.Kanban.Column}
	 */
	getNextColumnSibling: function(currentColumn)
	{
		var columnIndex = this.getColumnIndex(currentColumn);
		var columns = this.getColumns();

		return columnIndex !== -1 && columns[columnIndex + 1] ? columns[columnIndex + 1] : null;
	},

	/**
	 *
	 * @param {BX.Kanban.Column} currentColumn
	 * @returns {BX.Kanban.Column}
	 */
	getPreviousColumnSibling: function(currentColumn)
	{
		var columnIndex = this.getColumnIndex(currentColumn);
		var columns = this.getColumns();

		return columnIndex > 0 && columns[columnIndex - 1] ? columns[columnIndex - 1] : null;
	},

	/**
	 *
	 * @param {object} options
	 * @param {string|number} options.id
	 * @param {string|number} options.columnId
	 * @param {string} [options.type]
	 * @param {string|number} [options.targetId]
	 * @returns {BX.Kanban.Item|null}
	 */
	addItem: function(options)
	{
		options = options || {};
		var column = this.getColumn(options.columnId);
		if (!column)
		{
			return null;
		}

		var itemType = this.getItemType(options.type);
		var item = new itemType(options);
		if (! item instanceof BX.Kanban.Item)
		{
			throw new Error("Item type must be an instance of BX.Kanban.Item");
		}

		if (this.items[item.getId()])
		{
			return null;
		}

		item.setGrid(this);
		this.items[item.getId()] = item;

		var targetItem = this.getItem(options.targetId);
		column.addItem(item, targetItem);

		return item;
	},

	/**
	 *
	 * @param {BX.Kanban.Item|string|number} itemId
	 * @returns {BX.Kanban.Item}
	 */
	removeItem: function(itemId)
	{
		var item = this.getItem(itemId);
		if (item)
		{
			var column = item.getColumn();
			delete this.items[item.getId()];
			column.removeItem(item);
		}

		return item;
	},

	removeColumnItems: function(column)
	{
		column = this.getColumn(column);

		var items = column.getItems();
		column.removeItems();

		items.forEach(function(item) {
			this.removeItem(item);
		}, this);
	},

	removeItems: function()
	{
		this.getColumns().forEach(function(column) {
			this.removeColumnItems(column);
		}, this);
	},

	updateItem: function(item, options)
	{
		item = this.getItem(item);
		if (!item)
		{
			return false;
		}

		if (BX.Kanban.Utils.isValidId(options.columnId) && options.columnId !== item.getColumn().getId())
		{
			this.moveItem(item, this.getColumn(options.columnId), this.getItem(options.targetId));
		}

		item.setOptions(options);
		item.render();

		return true;
	},

	/**
	 *
	 * @param {object} options
	 * @returns {BX.Kanban.DropZone|null}
	 */
	addDropZone: function(options)
	{
		options = options || {};

		if (this.getDropZone(options.id) !== null)
		{
			return null;
		}

		var dropZoneType = this.getDropZoneType(options.type);
		var dropZone = new dropZoneType(options);
		if (! dropZone instanceof BX.Kanban.DropZone)
		{
			throw new Error("DropZone type must be an instance of BX.Kanban.DropZone");
		}

		dropZone.setGrid(this);
		this.dropzones[dropZone.getId()] = dropZone;

		return dropZone;
	},

	updateDropZone: function(dropZone, options)
	{

	},

	/**
	 *
	 * @param {BX.Kanban.Column|string|number} column
	 * @returns {BX.Kanban.Column}
	 */
	getColumn: function(column)
	{
		var columnId = column instanceof BX.Kanban.Column ? column.getId() : column;

		return this.columns[columnId] ? this.columns[columnId] : null;
	},

	/**
	 *
	 * @returns {BX.Kanban.Column[]}
	 */
	getColumns: function()
	{
		return this.columnsOrder;
	},

	/**
	 * @returns {number}
	 */
	getColumnsCount: function()
	{
		return this.columnsOrder.length;
	},

	/**
	 *
	 * @param column
	 * @returns {number}
	 */
	getColumnIndex: function(column)
	{
		column = this.getColumn(column);

		return BX.util.array_search(column, this.getColumns());
	},

	/**
	 *
	 * @param {string|number} item
	 * @returns {BX.Kanban.Item}
	 */
	getItem: function(item)
	{
		var itemId = item instanceof BX.Kanban.Item ? item.getId() : item;

		return this.items[itemId] ? this.items[itemId] : null;
	},

	/**
	 *
	 * @param {Element} itemNode
	 * @returns {BX.Kanban.Item|null}
	 */
	getItemByElement: function(itemNode)
	{
		if (BX.type.isDomNode(itemNode) && itemNode.dataset.id && itemNode.dataset.type === "item")
		{
			return this.getItem(itemNode.dataset.id);
		}

		return null;
	},

	/**
	 *
	 * @returns {BX.Kanban.Item[]}
	 */
	getItems: function()
	{
		return this.items;
	},

	/**
	 *
	 * @param {string|number} dropId
	 * @returns {BX.Kanban.DropZone}
	 */
	getDropZone: function(dropId)
	{
		return this.dropzones[dropId] ? this.dropzones[dropId] : null;
	},

	getDropZones: function()
	{
		return this.dropzones;
	},

	/**
	 *
	 * @param {string} [className]
	 * @returns {BX.Kanban.Item}
	 */
	getItemType: function(className)
	{
		var classFn = BX.Kanban.Utils.getClass(className);
		if (BX.type.isFunction(classFn))
		{
			return classFn;
		}

		return this.itemType || BX.Kanban.Item;
	},

	/**
	 *
	 * @param {string} [className]
	 * @returns {BX.Kanban.Column}
	 */
	getColumnType: function(className)
	{
		var classFn = BX.Kanban.Utils.getClass(className);
		if (BX.type.isFunction(classFn))
		{
			return classFn;
		}

		return this.columnType || BX.Kanban.Column;
	},

	/**
	 *
	 * @param {string} [className]
	 * @returns {BX.Kanban.DropZone}
	 */
	getDropZoneType: function(className)
	{
		var classFn = BX.Kanban.Utils.getClass(className);
		if (BX.type.isFunction(classFn) && classFn instanceof BX.Kanban.DropZone)
		{
			return classFn;
		}

		return this.columnType || BX.Kanban.DropZone;
	},

	/**
	 *
	 * @returns {object}
	 */
	getData: function()
	{
		return this.data;
	},

	setData: function(data)
	{
		if (BX.type.isPlainObject(data))
		{
			this.data = data;
		}
	},

	getBgColor: function()
	{
		return this.bgColor;
	},

	/**
	 *
	 * @returns {object}
	 */
	getOptions: function()
	{
		return this.options;
	},

	/**
	 *
	 * @param {object} json
	 * @param {BX.Kanban.Column[]} [json.columns]
	 * @param {BX.Kanban.Item[]} [json.items]
	 * @param {BX.Kanban.DropZone[]} [json.dropzones]
	 * @param {object} [json.events]
	 */
	loadData: function(json)
	{
		var needToDraw = this.isRendered();
		this.setRenderStatus(false);

		if (BX.type.isArray(json.columns))
		{
			json.columns.forEach(function(column) {

				if (column && BX.Kanban.Utils.isValidId(column.id) && this.getColumn(column.id))
				{
					this.updateColumn(column.id, column);
				}
				else
				{
					this.addColumn(column);
				}

			}, this);
		}

		if (BX.type.isArray(json.items))
		{
			json.items.forEach(function(item) {

				if (item && BX.Kanban.Utils.isValidId(item.id) && this.getItem(item.id))
				{
					this.updateItem(item.id, item);
				}
				else
				{
					this.addItem(item);
				}

			}, this);
		}

		if (BX.type.isArray(json.dropzones))
		{
			json.dropzones.forEach(function(dropzone) {

				if (dropzone && BX.Kanban.Utils.isValidId(dropzone.id) && this.getDropZone(dropzone.id))
				{
					this.updateDropZone(dropzone.id.id, dropzone.id);
				}
				else
				{
					this.addDropZone(dropzone);
				}


			}, this);
		}

		if (needToDraw)
		{
			this.draw();
		}
	},

	/**
	 * Draws Kanban on the page
	 *
	 */
	draw: function()
	{
		var docFragment = document.createDocumentFragment();
		var columns = this.getColumns();
		for (var i = 0; i < columns.length; i++)
		{
			var column = columns[i];
			docFragment.appendChild(column.render());
		}

		BX.cleanNode(this.getGridContainer());
		this.getGridContainer().appendChild(docFragment);

		var dropZoneItems = document.createDocumentFragment();
		for (var dropId in this.dropzones)
		{
			dropZoneItems.appendChild(this.dropzones[dropId].render());
		}

		BX.cleanNode(this.getDropZoneContainer());
		this.getDropZoneContainer().appendChild(dropZoneItems);

		if (!this.isRendered())
		{
			this.renderLayout();
			this.adjustLayout();
			this.setRenderStatus(true);
			BX.onCustomEvent(this, "Kanban.Grid:onFirstRender");
		}
		else
		{
			this.adjustLayout();
		}

		this.adjustEmptyStub();
	},

	renderLayout: function()
	{
		if (this.getOuterContainer().parentNode)
		{
			return;
		}

		var innerContainer = this.getInnerContainer();
		innerContainer.appendChild(this.getEmptyStub());
		innerContainer.appendChild(this.getLeftEar());
		innerContainer.appendChild(this.getRightEar());
		innerContainer.appendChild(this.getGridContainer());

		var outerContainer = this.getOuterContainer();
		outerContainer.appendChild(innerContainer);

		this.renderTo.appendChild(this.getOuterContainer());
		document.body.appendChild(this.getDropZoneContainer());

		BX.bind(window, "resize", this.adjustLayout.bind(this));
		BX.bind(window, "scroll", this.adjustHeight.bind(this));
	},

	isRendered: function()
	{
		return this.rendered;
	},

	setRenderStatus: function(status)
	{
		if (BX.type.isBoolean(status))
		{
			this.rendered = status;
		}
	},

	/**
	 *
	 * @returns {Element}
	 */
	getLeftEar: function()
	{
		if (this.layout.earLeft)
		{
			return this.layout.earLeft;
		}

		this.layout.earLeft = BX.create("div", {
			attrs: {
				className: "main-kanban-ear-left"
			},
			events: {
				mouseenter: this.scrollToLeft.bind(this),
				mouseleave: this.stopAutoScroll.bind(this)
			}
		});

		return this.layout.earLeft;
	},

	/**
	 *
	 * @returns {Element}
	 */
	getRightEar: function()
	{
		if (this.layout.earRight)
		{
			return this.layout.earRight;
		}

		this.layout.earRight = BX.create("div", {
			attrs: {
				className: "main-kanban-ear-right"
			},
			events: {
				mouseenter: this.scrollToRight.bind(this),
				mouseleave: this.stopAutoScroll.bind(this)
			}
		});

		return this.layout.earRight;
	},

	/**
	 *
	 * @returns {Element}
	 */
	getOuterContainer: function()
	{
		if (this.layout.outerContainer)
		{
			return this.layout.outerContainer;
		}

		this.layout.outerContainer = BX.create("div", {
			props: {
				className: "main-kanban"
			},
			style: {
				backgroundColor: "#" + this.getBgColor()
			}
		});

		return this.layout.outerContainer;
	},

	/**
	 *
	 * @returns {Element}
	 */
	getInnerContainer: function()
	{
		if (this.layout.innerContainer)
		{
			return this.layout.innerContainer;
		}

		this.layout.innerContainer = BX.create("div", {
			props: {
				className: "main-kanban-inner"
			},
			style: {
				backgroundColor: "#" + this.getBgColor()
			}
		});

		return this.layout.innerContainer;
	},

	/**
	 *
	 * @returns {Element}
	 */
	getGridContainer: function()
	{
		if (this.layout.gridContainer)
		{
			return this.layout.gridContainer;
		}

		this.layout.gridContainer = BX.create("div", {
			props: {
				className: "main-kanban-grid"
			},
			events: {
				scroll: this.adjustEars.bind(this)
			}
		});
		return this.layout.gridContainer;
	},

	/**
	 *
	 * @returns {Element}
	 */
	getDropZoneContainer: function()
	{
		if (this.layout.dropzone)
		{
			return this.layout.dropzone;
		}

		this.layout.dropzone = BX.create("div", {
			attrs: {
				className: "main-kanban-dropzone-area"
			}
		});

		return this.layout.dropzone;
	},

	/**
	 *
	 * @returns {Element}
	 */
	getEmptyStub: function()
	{
		if (this.layout.emptyStub)
		{
			return this.layout.emptyStub;
		}

		this.layout.emptyStub = BX.create("div", {
			attrs: {
				className: "main-kanban-no-data"
			},
			children: [
				BX.create("div", {
					attrs: {
						className: "main-kanban-no-data-inner"
					},
					children: [
						BX.create("div", {
							attrs: {
								className: "main-kanban-no-data-image"
							}
						}),
						BX.create("div", {
							attrs: {
								className: "main-kanban-no-data-text"
							},
							text: BX.message("MAIN_KANBAN_NO_DATA")
						})
					]
				})
			]
		});

		return this.layout.emptyStub;
	},

	adjustLayout: function()
	{
		this.adjustWidth();
		this.adjustHeight();
		this.adjustEars();
		this.adjustDropZoneArea();
	},

	adjustEars: function()
	{
		var grid = this.getGridContainer();
		var scroll = grid.scrollLeft;

		var isLeftVisible = scroll > 0;
		var isRightVisible = grid.scrollWidth > (scroll + grid.offsetWidth);

		this.getOuterContainer().classList[isLeftVisible ? "add" : "remove"]("main-kanban-left-ear-shown");
		this.getOuterContainer().classList[isRightVisible ? "add" : "remove"]("main-kanban-right-ear-shown");
	},

	adjustWidth: function()
	{
		this.getOuterContainer().style.width = this.renderTo.offsetWidth + "px";
	},

	adjustHeight: function()
	{
		var outerContainer = this.getOuterContainer();
		var innerContainer = this.getInnerContainer();

		if (outerContainer.getBoundingClientRect().top >= 15) //@see .main-kanban-fixed:top
		{
			var height = document.documentElement.clientHeight - innerContainer.getBoundingClientRect().top;
			innerContainer.style.height = height + "px";

			outerContainer.style.minHeight = document.documentElement.clientHeight + "px";
			innerContainer.style.removeProperty("top");
			innerContainer.style.removeProperty("left");
			innerContainer.style.removeProperty("width");
			innerContainer.classList.remove("main-kanban-fixed");
		}
		else
		{
			var rectArea = this.renderTo.getBoundingClientRect();

			innerContainer.classList.contains("main-kanban-fixed");
			innerContainer.style.left = rectArea.left + "px";
			innerContainer.style.width = rectArea.width + "px";
			innerContainer.style.removeProperty("height");
			innerContainer.classList.add("main-kanban-fixed");
		}
	},

	adjustDropZoneArea: function()
	{
		var rectArea = BX.pos(this.getOuterContainer());
		this.getDropZoneContainer().style.width = rectArea.width + "px";
		this.getDropZoneContainer().style.left = rectArea.left + "px";
	},

	adjustEmptyStub: function()
	{
		var isVisible = BX.Kanban.Utils.isEmptyObject(this.getItems());
		this.getInnerContainer().classList[isVisible ? "add" : "remove"]("main-kanban-no-data-mode");
	},

	moveItem: function(item, targetColumn, beforeItem)
	{
		item = this.getItem(item);
		targetColumn = this.getColumn(targetColumn);
		beforeItem = this.getItem(beforeItem);

		if (!item || !targetColumn || item === beforeItem)
		{
			return false;
		}

		var currentColumn = item.getColumn();
		currentColumn.removeItem(item);
		targetColumn.addItem(item, beforeItem);

		return true;
	},

	/**
	 *
	 * @param {BX.Kanban.Column|string|number} column
	 * @param {BX.Kanban.Column|string|number} [targetColumn]
	 * @returns {boolean}
	 */
	moveColumn: function(column, targetColumn)
	{
		column = this.getColumn(column);
		targetColumn = this.getColumn(targetColumn);
		if (!column || column === targetColumn)
		{
			return false;
		}

		var columnIndex = BX.util.array_search(column, this.columnsOrder);
		this.columnsOrder.splice(columnIndex, 1);

		var targetIndex = BX.util.array_search(targetColumn, this.columnsOrder);
		if (targetIndex >= 0)
		{
			this.columnsOrder.splice(targetIndex, 0, column);
			if (this.isRendered())
			{
				column.getContainer().parentNode.insertBefore(column.getContainer(), targetColumn.getContainer());
			}
		}
		else
		{
			this.columnsOrder.push(column);
			if (this.isRendered())
			{
				column.getContainer().parentNode.appendChild(column.getContainer());
			}
		}

		return true;
	},

	/**
	 *
	 * @returns {bool}
	 */
	canAddColumns: function()
	{
		return this.canAddColumn;
	},

	/**
	 *
	 * @returns {bool}
	 */
	canEditColumns: function()
	{
		return this.canEditColumn;
	},

	/**
	 *
	 * @returns {bool}
	 */
	canRemoveColumns: function()
	{
		return this.canRemoveColumn;
	},

	/**
	 *
	 * @returns {bool}
	 */
	canAddItems: function()
	{
		return this.canAddItem;
	},

	scrollToRight: function()
	{
		this.earTimer = setInterval(function() {
			this.getGridContainer().scrollLeft += 10;
		}.bind(this), 20)
	},

	scrollToLeft: function()
	{
		this.earTimer = setInterval(function() {
			this.getGridContainer().scrollLeft -= 10;
		}.bind(this), 20)
	},

	stopAutoScroll: function()
	{
		clearInterval(this.earTimer);

		//?
		jsDD.refreshDestArea();
	},

	/**
	 *
	 * @returns {BX.Kanban.DragMode}
	 */
	getDragMode: function()
	{
		return this.dragMode;
	},

	getDragModeCode: function(mode)
	{
		for (var code in BX.Kanban.DragMode)
		{
			if (BX.Kanban.DragMode[code] === mode)
			{
				return code;
			}
		}

		return null;
	},

	/**
	 *
	 * @param {BX.Kanban.DragMode} mode
	 */
	setDragMode: function(mode)
	{
		var code = this.getDragModeCode(mode);
		if (code !== null)
		{
			this.getOuterContainer().classList.add("main-kanban-drag-mode-" + code.toLowerCase());
			this.dragMode = mode;
		}
	},

	resetDragMode: function()
	{
		var code = this.getDragModeCode(this.getDragMode());
		if (code !== null)
		{
			this.getOuterContainer().classList.remove("main-kanban-drag-mode-" + code.toLowerCase());
		}

		this.dragMode = BX.Kanban.DragMode.NONE;
	},

	onItemDragStart: function(item)
	{
		this.setDragMode(BX.Kanban.DragMode.ITEM);

		var items = this.getItems();
		for (var itemId in items)
		{
			items[itemId].enableDropping();
		}

		this.getColumns().forEach(function(/*BX.Kanban.Column*/column) {
			column.enableDropping();
		});
	},

	onItemDragStop: function(item)
	{
		this.resetDragMode();

		// var items = this.getItems();
		// for (var itemId in items)
		// {
		// 	items[itemId].disableDropping();
		// }
		//
		// this.getColumns().forEach(function(/*BX.Kanban.Column*/column) {
		// 	column.disableDropping();
		// });
	},

	onColumnDragStart: function(column)
	{
		this.setDragMode(BX.Kanban.DragMode.COLUMN);
	},

	onColumnDragStop: function(column)
	{
		this.resetDragMode();
	},

	/**
	 *
	 * @param {string} eventName
	 * @param {array} eventArgs
	 * @param {function} onFulfilled
	 * @param {function} onRejected
	 */
	getEventPromise: function(eventName, eventArgs, onFulfilled, onRejected)
	{
		var promises = [];

		eventArgs = BX.type.isArray(eventArgs) ? eventArgs : [];
		BX.onCustomEvent(this, eventName, [promises].concat(eventArgs));

		var promise = new BX.Promise();
		var firstPromise = promise;

		for (var i = 0; i < promises.length; i++)
		{
			promise = promise.then(promises[i]);
		}

		promise.then(
			BX.type.isFunction(onFulfilled) ? onFulfilled : null,
			BX.type.isFunction(onRejected) ? onRejected : null
		);

		return firstPromise;
	},

	fadeOut: function()
	{
		this.getOuterContainer().classList.add("main-kanban-faded");
	},


	fadeIn: function()
	{
		this.getOuterContainer().classList.remove("main-kanban-faded");
	}
};


})();