;(function() {
	'use strict';

	BX.namespace('BX.Grid');

	/**
	 * BX.Grid.ColsSortable
	 * @param {BX.Main.grid} parent
	 * @constructor
	 */
	BX.Grid.ColsSortable = function(parent)
	{
		this.parent = null;
		this.dragItem = null;
		this.targetItem = null;
		this.rowsList = null;
		this.colsList = null;
		this.dragRect = null;
		this.offset = null;
		this.startDragOffset = null;
		this.dragColumn = null;
		this.targetColumn = null;
		this.isDrag = null;
		this.init(parent);
	};

	BX.Grid.ColsSortable.prototype = {
		init: function(parent)
		{
			var fixedTable, rows;
			var self = this;

			this.parent = parent;
			this.colsList = this.getColsList();
			this.rowsList = this.parent.getRows().getSourceRows();

			if (this.isPinned && this.parent.getParam('ALLOW_PIN_HEADER'))
			{
				fixedTable = this.parent.getPinHeader().getFixedTable();
				rows = BX.Grid.Utils.getByTag(fixedTable, 'tr');

				(rows || []).forEach(function(current) {
					self.rowsList.push(current);
				});
			}

			this.registerObjects();

			if (!this.inited)
			{
				this.inited = true;
				BX.addCustomEvent('Grid::headerPinned', BX.proxy(this._onPin, this));
				BX.addCustomEvent('Grid::headerUnpinned', BX.proxy(this._onUnpin, this));
			}
		},

		destroy: function()
		{
			BX.removeCustomEvent('Grid::headerPinned', BX.proxy(this._onPin, this));
			BX.removeCustomEvent('Grid::headerUnpinned', BX.proxy(this._onUnpin, this));
			this.unregisterObjects();
		},

		_onPin: function()
		{
			this.isPinned = true;
			this.reinit();
		},

		_onUnpin: function()
		{
			this.isPinned = false;
			this.reinit();
		},

		reinit: function()
		{
			this.unregisterObjects();
			this.reset();
			this.init(this.parent);
		},

		reset: function()
		{
			this.dragItem = null;
			this.targetItem = null;
			this.rowsList = null;
			this.colsList = null;
			this.dragRect = null;
			this.offset = null;
			this.startDragOffset = null;
			this.dragColumn = null;
			this.targetColumn = null;
			this.isDrag = null;
		},

		isActive: function()
		{
			return this.isDrag;
		},

		registerObjects: function(objects)
		{
			var self = this;

			[].forEach.call((objects || this.colsList), function(current) {
				current.onbxdragstart = BX.delegate(self._onDragStart, self);
				current.onbxdrag = BX.delegate(self._onDrag, self);
				current.onbxdragstop = BX.delegate(self._onDragEnd, self);
				jsDD.registerObject(current);
				jsDD.registerDest(current);
			});
		},

		unregisterObjects: function()
		{
			[].forEach.call(this.colsList, function(current) {
				jsDD.unregisterObject(current);
				jsDD.unregisterDest(current);
			});
		},

		getColsList: function()
		{
			var self = this;
			var list = [];
			var table;

			if (this.isPinned && this.parent.getParam('ALLOW_PIN_HEADER'))
			{
				table = this.parent.getPinHeader().getFixedTable();
				list = BX.Grid.Utils.getByTag(table, 'th');
			}
			else
			{
				list = this.parent.getRows().getHeadFirstChild().getCells();
			}

			list = [].filter.call(list, function(current) {
				return !self.isStatic(current);
			});

			return list;
		},

		isStatic: function(item)
		{
			return BX.hasClass(item, this.parent.settings.get('classCellStatic'));
		},

		getDragOffset: function()
		{
			return (jsDD.x - this.startDragOffset - this.dragRect.left);
		},

		getColumn: function(item)
		{
			var column = BX.Grid.Utils.getColumn(this.parent.getTable(), item);

			if (column.indexOf(item) === -1)
			{
				column.push(item);
			}

			return column;
		},

		_onDragStart: function()
		{
			this.isDrag = true;

			this.dragItem = jsDD.current_node;
			this.dragRect = this.dragItem.getBoundingClientRect();
			this.offset = Math.ceil(this.dragRect.width);
			this.startDragOffset = jsDD.start_x - this.dragRect.left;
			this.dragColumn = this.getColumn(this.dragItem);
			this.dragIndex = BX.Grid.Utils.getIndex(this.colsList, this.dragItem);
		},

		isDragToRight: function(node, index)
		{
			var nodeClientRect = node.getBoundingClientRect();
			var nodeCenter = Math.ceil(nodeClientRect.left + (nodeClientRect.width / 2) + BX.scrollLeft(window));
			var dragIndex = this.dragIndex;
			var x = jsDD.x;

			return index > dragIndex && x > nodeCenter;
		},

		isDragToLeft: function(node, index)
		{
			var nodeClientRect = node.getBoundingClientRect();
			var nodeCenter = Math.ceil(nodeClientRect.left + (nodeClientRect.width / 2) + BX.scrollLeft(window));
			var dragIndex = this.dragIndex;
			var x = jsDD.x;

			return index < dragIndex && x < nodeCenter;
		},

		isDragToBack: function(node, index)
		{
			var nodeClientRect = node.getBoundingClientRect();
			var nodeCenter = Math.ceil(nodeClientRect.left + (nodeClientRect.width / 2) + BX.scrollLeft(window));
			var dragIndex = this.dragIndex;
			var x = jsDD.x;

			return (index > dragIndex && x < nodeCenter) || (index < dragIndex && x > nodeCenter);
		},


		isMovedToRight: function(node)
		{
			return node.style.transform === 'translate3d('+(-this.offset)+'px, 0px, 0px)';
		},

		isMovedToLeft: function(node)
		{
			return (
				node.style.transform === 'translate3d('+(this.offset)+'px, 0px, 0px)'
			);
		},

		isMoved: function(node)
		{
			return (node.style.transform !== 'translate3d(0px, 0px, 0px)' && node.style.transform !== '');
		},

		/**
		 * Moves grid column by offset
		 * @param {array} column - Array cells of column
		 * @param {int} offset - Pixels offset
		 * @param {int} [transition = 300] - Transition duration in milliseconds
		 */
		moveColumn: function(column, offset, transition)
		{
			transition = BX.type.isNumber(transition) ? transition : 300;
			BX.Grid.Utils.styleForEach(column, {
				'transition': transition+'ms',
				'transform': 'translate3d('+offset+'px, 0px, 0px)'
			});
		},

		_onDrag: function()
		{
			this.dragOffset = this.getDragOffset();
			this.targetItem = this.targetItem || this.dragItem;
			this.targetColumn = this.targetColumn || this.dragColumn;

			var leftOffset = -this.offset;
			var rightOffset = this.offset;
			var defaultOffset = 0;
			var dragTransitionDuration = 0;

			this.moveColumn(this.dragColumn, this.dragOffset, dragTransitionDuration);

			[].forEach.call(this.colsList, function(current, index) {
				if (current)
				{
					if (this.isDragToRight(current, index) && !this.isMovedToRight(current))
					{
						this.targetColumn = this.getColumn(current);
						this.moveColumn(this.targetColumn, leftOffset);
					}

					if (this.isDragToLeft(current, index) && !this.isMovedToLeft(current))
					{
						this.targetColumn = this.getColumn(current);
						this.moveColumn(this.targetColumn, rightOffset);
					}

					if (this.isDragToBack(current, index) && this.isMoved(current))
					{
						this.targetColumn = this.getColumn(current);
						this.moveColumn(this.targetColumn, defaultOffset);
					}
				}
			}, this);
		},

		_onDragEnd: function()
		{
			var self = this;
			var columns = [];

			[].forEach.call(this.dragColumn, function(current, index) {
				BX.Grid.Utils.collectionSort(current, self.targetColumn[index]);
			});

			[].forEach.call(this.rowsList, function(current) {
				BX.Grid.Utils.styleForEach(current.cells, {
					transition: '',
					transform: ''
				});
			});

			this.reinit();

			[].forEach.call(this.colsList, function(current) {
				columns.push(BX.data(current, 'name'));
			});

			this.parent.getUserOptions().setColumns(columns);
			BX.onCustomEvent(this.parent.getContainer(), 'Grid::columnMoved', [this.parent]);
		}
	};
})();