;(function() {
	'use strict';

	BX.namespace('BX.Grid');


	BX.Grid.RowsSortable = function(parent)
	{
		this.parent = null;
		this.list = null;
		this.setDefaultProps();
		this.init(parent);
	};

	BX.Grid.RowsSortable.prototype = {
		init: function(parent)
		{
			this.parent = parent;
			this.list = this.getList();
			this.prepareListItems();
			jsDD.Enable();

			if (!this.inited)
			{
				this.inited = true;
				this.onscrollDebounceHandler = BX.debounce(this._onWindowScroll, 300, this);
				BX.addCustomEvent('Grid::thereEditedRows', BX.proxy(this.disable, this));
				BX.addCustomEvent('Grid::noEditedRows', BX.proxy(this.enable, this));
				BX.bind(window, 'scroll', this.onscrollDebounceHandler);
			}
		},

		destroy: function()
		{
			BX.removeCustomEvent('Grid::thereEditedRows', BX.proxy(this.disable, this));
			BX.removeCustomEvent('Grid::noEditedRows', BX.proxy(this.enable, this));
			BX.unbind(window, 'scroll', this.onscrollDebounceHandler);
			this.unregisterObjects();
		},

		_onWindowScroll: function()
		{
			this.windowScrollTop = BX.scrollTop(window);
		},

		disable: function()
		{
			this.unregisterObjects();
		},

		enable: function()
		{
			this.reinit();
		},

		reinit: function()
		{
			this.unregisterObjects();
			this.setDefaultProps();
			this.init(this.parent);
		},

		getList: function()
		{
			return this.parent.getRows().getSourceBodyChild();
		},

		unregisterObjects: function()
		{
			this.list = this.list.map(function(current) {
				jsDD.unregisterObject(current);
				return current;
			});
		},

		prepareListItems: function()
		{
			var self = this;
			this.list = this.list.map(function(current) {
				current.onbxdragstart = BX.delegate(self._onDragStart, self);
				current.onbxdrag = BX.delegate(self._onDrag, self);
				current.onbxdragstop = BX.delegate(self._onDragEnd, self);
				jsDD.registerObject(current);
				return current;
			});
		},

		getIndex: function(item)
		{
			return BX.Grid.Utils.getIndex(this.list, item);
		},

		_onDragStart: function()
		{
			this.dragItem = jsDD.current_node;
			this.dragIndex = this.getIndex(this.dragItem);
			this.dragRect = this.getRowRect(this.dragItem, this.dragIndex);
			this.offset = this.dragRect.height;
			this.dragStartOffset = (jsDD.start_y - (this.dragRect.top + this.getWindowScrollTop()));

			BX.Grid.Utils.styleForEach(this.list, {'transition': +this.parent.settings.get('animationDuration') + 'ms'});
			BX.bind(document, 'mousemove', BX.delegate(this._onMouseMove, this));
			BX.addClass(this.parent.getContainer(), this.parent.settings.get('classOnDrag'));
			BX.addClass(this.dragItem, this.parent.settings.get('classDragActive'));
		},

		_onMouseMove: function(event)
		{
			this.realX = event.clientX;
			this.realY = event.clientY;
		},


		/**
		 * @param {?HTMLElement} row
		 * @param {int} offset
		 * @param {?int} [transition] css transition-duration in ms
		 */
		moveRow: function(row, offset, transition)
		{
			if (!!row)
			{
				var transitionDuration = BX.type.isNumber(transition) ? transition : 300;
				row.style.transition = transitionDuration + 'ms';
				row.style.transform = 'translate3d(0px, '+offset+'px, 0px)';
			}
		},

		getDragOffset: function()
		{
			return this.realY - this.dragRect.top - this.dragStartOffset;
		},

		getWindowScrollTop: function()
		{
			if (this.windowScrollTop === null)
			{
				this.windowScrollTop = BX.scrollTop(window);
			}

			return this.windowScrollTop;
		},

		getSortOffset: function()
		{
			return this.realY + this.getWindowScrollTop();
		},

		getRowRect: function(row, index)
		{
			if (!this.rowsRectList)
			{
				this.rowsRectList = {};

				this.list.forEach(function(current, i) {
					return this.rowsRectList[i] = current.getBoundingClientRect();
				}, this);
			}

			return this.rowsRectList[index];
		},

		getRowCenter: function(row, index)
		{
			var rect = this.getRowRect(row, index);
			return rect.top + this.getWindowScrollTop() + (rect.height / 2);
		},

		isDragToBottom: function(row, index)
		{
			var rowCenter = this.getRowCenter(row, index);
			var sortOffset = this.getSortOffset();
			return index > this.dragIndex && rowCenter < sortOffset;
		},

		isMovedToBottom: function(row)
		{
			return row.style.transform === 'translate3d(0px, '+(-this.offset)+'px, 0px)';
		},

		isDragToTop: function(row, index)
		{
			var rowCenter = this.getRowCenter(row, index);
			var sortOffset = this.getSortOffset();
			return index < this.dragIndex && rowCenter > sortOffset;
		},

		isMovedToTop: function(row)
		{
			return row.style.transform === 'translate3d(0px, '+this.offset+'px, 0px)';
		},

		isDragToBack: function(row, index)
		{
			var rowCenter = this.getRowCenter(row, index);
			var dragIndex = this.dragIndex;
			var y = jsDD.y;

			return (index > dragIndex && y < rowCenter) || (index < dragIndex && y > rowCenter);
		},

		isMoved: function(row)
		{
			return (row.style.transform !== 'translate3d(0px, 0px, 0px)' && row.style.transform !== '');
		},

		_onDrag: function()
		{
			var dragTransitionDuration = 0;
			var defaultOffset = 0;

			this.moveRow(this.dragItem, this.getDragOffset(), dragTransitionDuration);

			this.list.forEach(function(current, index) {
				if (!!current)
				{
					if (this.isDragToTop(current, index) && !this.isMovedToTop(current))
					{
						this.targetItem = current;
						this.moveRow(current, this.offset);
					}

					if (this.isDragToBottom(current, index) && !this.isMovedToBottom(current))
					{
						this.targetItem = current;
						this.moveRow(current, -this.offset);
					}

					if (this.isDragToBack(current, index) && this.isMoved(current))
					{
						this.targetItem = current;
						this.moveRow(current, defaultOffset);
					}
				}
			}, this);
		},

		_onDragOver: function() {},

		_onDragLeave: function() {},

		_onDragEnd: function()
		{
			BX.unbind(document, 'mousemove', BX.delegate(this._onMouseMove, this));
			BX.removeClass(this.parent.getContainer(), this.parent.settings.get('classOnDrag'));
			BX.removeClass(this.dragItem, this.parent.settings.get('classDragActive'));

			BX.Grid.Utils.styleForEach(this.list, {'transition': '', 'transform': ''});
			BX.Grid.Utils.collectionSort(this.dragItem, this.targetItem);

			this.list = this.getList();
			this.parent.getRows().reset();

			var dragItem = this.parent.getRows().get(this.dragItem);
			var ids = this.parent.getRows().getBodyChild().map(function(row) {
				return row.getId();
			});

			this.saveRowsSort(ids);
			BX.onCustomEvent(window, 'Grid::rowMoved', [ids, dragItem, this.parent]);
			this.setDefaultProps();
		},

		saveRowsSort: function(rows)
		{
			var data = {
				ids: rows,
				action: this.parent.getUserOptions().getAction('GRID_SAVE_ROWS_SORT')
			};

			this.parent.getData().request(null, 'POST', data);
		},

		setDefaultProps: function()
		{
			this.dragItem = null;
			this.targetItem = null;
			this.dragRect = null;
			this.dragIndex = null;
			this.offset = null;
			this.realX = null;
			this.realY = null;
			this.dragStartOffset = null;
			this.windowScrollTop = null;
			this.rowsRectList = null;
		}
	};
})();