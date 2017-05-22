;(function() {
	'use strict';

	BX.namespace('BX.Grid');

	BX.Grid.SettingsWindow = function(parent)
	{
		this.parent = null;
		this.settingsButton = null;
		this.applyBottom = null;
		this.items = null;
		this.popup = null;
		this.sourceContent = null;
		this.lastColumns = null;
		this.init(parent);
	};

	BX.Grid.SettingsWindow.prototype = {
		init: function(parent)
		{
			this.parent = parent;
			BX.bind(this.parent.getContainer(), 'click', BX.proxy(this._onContainerClick, this));
			BX.addCustomEvent(window, 'Grid::columnMoved', BX.proxy(this._onColumnMoved, this));
		},

		reset: function()
		{
			this.items = null;
			this.allColumns = null;
			this.showedColumns = null;
		},

		destroy: function()
		{
			BX.unbind(this.parent.getContainer(), 'click', BX.proxy(this._onContainerClick, this));
			BX.removeCustomEvent(window, 'Grid::columnMoved', BX.proxy(this._onColumnMoved, this));
			this.getPopup().close();
		},

		_onContainerClick: function(event)
		{
			if (BX.hasClass(event.target, this.parent.settings.get('classSettingsButton')))
			{
				this._onSettingsButtonClick(event);
			}
		},

		_onSettingsButtonClick: function(event)
		{
			this.getPopup().show();
		},

		getSourceContent: function()
		{
			if (!this.sourceContent)
			{
				this.sourceContent = BX.Grid.Utils.getByClass(this.parent.getContainer(), this.parent.settings.get('classSettingsWindow'), true);
			}

			return this.sourceContent;
		},

		getPopupItems: function()
		{
			var popupContainer;

			if (!this.items)
			{
				popupContainer = this.getPopup().contentContainer;
				this.items = BX.Grid.Utils.getByClass(popupContainer, this.parent.settings.get('classSettingsWindowColumn'));
			}

			return this.items;
		},

		getColumns: function()
		{
			var items = this.getPopupItems();
			var columns = [];
			var checkbox;

			items.forEach(function(current) {
				checkbox = this.findColumnCheckbox(current);
				if (checkbox && checkbox.checked)
				{
					columns.push(BX.data(current, 'name'));
				}
			}, this);

			return columns;
		},

		restoreColumns: function()
		{
			var columns = this.parent.getParam('DEFAULT_COLUMNS');
			this.getPopupItems().forEach(function(current) {
				var name = BX.data(current, 'name');
				var checkbox = this.findColumnCheckbox(current);
				var label = this.findColumnLabel(current);
				var defaultColumn = columns[name];

				checkbox.checked = defaultColumn.default ? true : null;
				BX.html(label, BX.util.htmlspecialchars(defaultColumn.name));
			}, this);

			this.sortItems();
			this.reset();
		},

		restoreLastColumns: function()
		{
			this.getPopupItems().forEach(function(current) {
				if (this.lastColumns.indexOf(BX.data(current, 'name')) === -1) {
					var checkbox = this.findColumnCheckbox(current);

					if (checkbox)
					{
						checkbox.checked = null;
					}
				}
			}, this);
		},

		saveColumns: function(columns, callback)
		{
			var parent = this.parent;
			var options = parent.getUserOptions();
			var columnNames = this.getColumnNames();
			var forAll = this.isForAll();

			parent.tableFade();

			options.setColumns(columns, function() {
				options.setColumnsNames(columnNames, function() {
					if (forAll)
					{
						options.saveForAll(function() {
							parent.reloadTable(null, null, callback);
						});
					}
					else
					{
						parent.reloadTable(null, null, callback);
					}
				});
			});
		},

		resetSettings: function(button)
		{
			var forAll = this.isForAll();
			var confirmOptions = {
				CONFIRM: true,
				CONFIRM_MESSAGE: this.parent.arParams.CONFIRM_RESET_MESSAGE
			};

			this.parent.getActionsPanel().confirmDialog(
				confirmOptions,
				BX.delegate(function() {
					this.parent.tableFade();
					BX.addClass(button.buttonNode, 'webform-small-button-wait');
					BX.removeClass(button.buttonNode, 'popup-window-button');
					this.parent.getUserOptions().reset(forAll, BX.delegate(function() {
						this.parent.reloadTable(null, null, BX.delegate(function() {
							this.restoreColumns();
							this.lastColumns = this.getColumns();
							BX.removeClass(button.buttonNode, 'webform-small-button-wait');
							BX.addClass(button.buttonNode, 'popup-window-button');
							button.popupWindow.close();
						}, this));
					}, this));
				}, this)
			);
		},

		/**
		 * Enable edit mode of column label
		 * @param {HTMLElement} label
		 */
		enableColumnLabelEdit: function(label)
		{
			label && (label.contentEditable = true) && this.adjustCaret(label) && label.focus();
		},

		/**
		 * Disable edit mode of column label
		 * @param {HTMLElement} label
		 */
		disableColumnLabelEdit: function(label)
		{
			label && (label.contentEditable = false);
		},

		disableAllColumnslabelEdit: function()
		{
			this.getPopupItems().forEach(function(column) {
				var label = this.findColumnLabel(column);
				var checkbox = this.findColumnCheckbox(column);
				this.disableColumnLabelEdit(label);
				this.enableCheckbox(checkbox);
			}, this);
		},

		/**
		 * Checks is enable editable mode of column label
		 * @param {HTMLElement} label
		 * @returns {boolean}
		 */
		isColumnLabelEditEnabled: function(label)
		{
			return label && label.isContentEditable;
		},

		/**
		 * Checks is edit button
		 * @param {HTMLElement} element
		 * @returns {boolean}
		 */
		isEditButton: function(element)
		{
			return !!element && BX.hasClass(element, this.parent.settings.get('classSettingsWindowColumnEditButton'));
		},

		/**
		 * Finds column label node
		 * @param {HTMLElement} column
		 * @returns {?HTMLElement}
		 */
		findColumnLabel: function(column)
		{
			return BX.Grid.Utils.getByTag(column, 'label', true);
		},

		/**
		 * Finds column checkbox input
		 * @param {HTMLElement} column
		 * @returns {?HTMLInputElement}
		 */
		findColumnCheckbox: function(column)
		{
			return BX.Grid.Utils.getBySelector(column, 'input[type="checkbox"]', true);
		},

		/**
		 * Disabled checkbox
		 * @param {HTMLInputElement} checkbox
		 */
		disableCheckbox: function(checkbox)
		{
			checkbox && (checkbox.disabled = true);
		},

		/**
		 * Enabled checkbox
		 * @param {HTMLInputElement} checkbox
		 */
		enableCheckbox: function(checkbox)
		{
			checkbox && (checkbox.disabled = false);
		},

		adjustCaret: function(element)
		{
			if (element && element.childNodes.length)
			{
				var range = document.createRange();
				var selection = window.getSelection();
				var elementTextLength = element.innerText.length;
				var textNodes = element.childNodes;
				var lastTextNode = textNodes[textNodes.length - 1];

				range.setStart(lastTextNode, elementTextLength);
				range.setEnd(lastTextNode, elementTextLength);
				range.collapse(true);

				selection.removeAllRanges();
				selection.addRange(range);
			}
		},

		_onColumnClick: function(event)
		{
			var column = event.currentTarget;
			var target = event.target;

			if (this.isEditButton(target))
			{
				var label = this.findColumnLabel(column);
				var checkbox = this.findColumnCheckbox(column);

				if (!this.isColumnLabelEditEnabled(label))
				{
					this.enableColumnLabelEdit(label);
					this.disableCheckbox(checkbox);
				}
				else
				{
					this.disableColumnLabelEdit(label);
					this.enableCheckbox(checkbox);
				}
			}
		},

		_onColumnKeydown: function(event)
		{
			if (event.code === 'Enter')
			{
				var column = event.currentTarget;
				BX.removeClass(column, this.parent.settings.get('classSettingsWindowColumnEditState'));
				var input = BX.Grid.Utils.getByClass(column, this.parent.settings.get('classSettingsWindowColumnEditInput'), true);
				var label = BX.Grid.Utils.getByClass(column, this.parent.settings.get('classSettingsWindowColumnLabel'), true);

				if (label)
				{
					BX.html(label, BX.util.htmlspecialchars(input.value));
				}
			}
		},

		getAllColumns: function()
		{
			if (!this.allColumns)
			{
				this.allColumns = this.getPopupItems().map(function(item) {
					return BX.data(item, 'name');
				});
			}

			return this.allColumns;
		},

		getShowedColumns: function()
		{
			if (!this.showedColumns)
			{
				this.showedColumns = [];
				[].forEach.call(this.parent.getRows().getHeadFirstChild().getCells(), function(item) {
					var name = BX.data(item, 'name');
					name && this.showedColumns.push(name);
				}, this);
			}

			return this.showedColumns;
		},

		isShowedColumn: function(columnName)
		{
			return this.getShowedColumns().some(function(name) {
				return name === columnName;
			});
		},

		sortItems: function()
		{
			var showedColumns = this.getShowedColumns();
			var allColumns = {};

			this.getAllColumns().forEach(function(name) {
				allColumns[name] = name;
			}, this);

			var counter = 0;
			Object.keys(allColumns).forEach(function(name) {
				if (this.isShowedColumn(name))
				{
					allColumns[name] = showedColumns[counter];
					counter++;
				}

				var current = this.getColumnByName(allColumns[name]);
				current && current.parentNode.appendChild(current);
			}, this);
		},

		getColumnNames: function()
		{
			var items = this.getPopupItems();
			var columns = {};

			items.forEach(function(current) {
				var name = BX.data(current, 'name');
				var label = this.findColumnLabel(current);
				columns[name] = BX.util.htmlspecialchars(BX.util.htmlspecialcharsback(label.innerText.trim()));
			}, this);

			return columns;
		},

		getColumnByName: function(name)
		{
			return BX.Grid.Utils.getBySelector(
				this.getPopup().popupContainer,
				'.' + this.parent.settings.get('classSettingsWindowColumn') + '[data-name="'+name+'"]',
				true
			);
		},

		_onColumnMoved: function()
		{
			this.sortItems();
			this.reset();
		},

		getPopup: function()
		{
			var self = this;
			if (!this.popup)
			{
				var tmpDiv = BX.create('div');
				tmpDiv.innerHTML = '<span>' + this.parent.getParam('SETTINGS_TITLE') + ' &laquo;'+BX('pagetitle').innerText+'&raquo;</span>';
				var titleBar = tmpDiv.firstChild;

				this.popup = new BX.PopupWindow(
					this.parent.getContainerId() + '-grid-settings-window',
					null,
					{
						titleBar: titleBar.innerText,
						autoHide: false,
						overlay: 0.6,
						width: 800,
						closeIcon: true,
						closeByEsc: true,
						contentNoPaddings: true,
						events: {
							onPopupClose: BX.delegate(function() {
								this.restoreLastColumns();
								this.disableAllColumnslabelEdit();
							}, this)
						},
						buttons: [
							new BX.PopupWindowButtonLink({
								text: this.parent.getParam('RESET_DEFAULT'),
								id: this.parent.getContainerId() + '-grid-settings-reset-button',
								className: 'main-grid-settings-window-actions-item-reset',
								events: {
									click: function()
									{
										self.resetSettings(this.popupWindow.buttons[1]);
									}
								}
							}),
							new BX.PopupWindowButton({
								text: this.parent.getParam('APPLY_SETTINGS'),
								id: this.parent.getContainerId() + '-grid-settings-apply-button',
								className: 'webform-small-button-blue webform-small-button',
								events: {
									click: function()
									{
										self.parent.getActionsPanel().confirmDialog(
											{
												CONFIRM: self.isForAll(),
												CONFIRM_MESSAGE: self.parent.getParam('SETTINGS_FOR_ALL_CONFIRM_MESSAGE')
											},
											BX.delegate(function() {
												BX.addClass(this.buttonNode, 'webform-small-button-wait');
												BX.removeClass(this.buttonNode, 'popup-window-button');
												self.lastColumns = self.getColumns();

												self.saveColumns(self.lastColumns, BX.delegate(function() {
													this.popupWindow.close();
													BX.removeClass(this.buttonNode, 'webform-small-button-wait');
													BX.addClass(this.buttonNode, 'popup-window-button');
													var checkbox = self.getForAllCheckbox();
													checkbox && (checkbox.checked = null);
												}, this));
											}, this),
											BX.delegate(function() {
												var checkbox = self.getForAllCheckbox();
												checkbox && (checkbox.checked = null);
											}, this)
										);
									}
								}
							}),
							new BX.PopupWindowButtonLink({
								text: this.parent.getParam('CANCEL_SETTINGS'),
								id: this.parent.getContainerId() + '-grid-settings-cancel-button',
								events: {
									click: function()
									{
										this.popupWindow.close();
										self.restoreLastColumns();
									}
								}
							})
						]
					}
				);

				if (this.parent.getParam('IS_ADMIN'))
				{
					var checkbox = this.createCheckbox();
					var resetButton = this.getResetButton();
					BX.insertAfter(checkbox, resetButton);
				}

				this.popup.setContent(this.getSourceContent());
				this.lastColumns = this.getColumns();
				this.getPopupItems().forEach(function(current) {
					BX.bind(current, 'click', BX.delegate(this._onColumnClick, this));
					BX.bind(current, 'keydown', BX.delegate(this._onColumnKeydown, this));
				}, this);
			}

			return this.popup;
		},

		isForAll: function()
		{
			var checkbox = this.getForAllCheckbox();
			return checkbox && !!checkbox.checked;
		},

		getForAllCheckbox: function()
		{
			return BX.Grid.Utils.getByClass(this.getPopup().popupContainer, 'main-grid-settings-window-for-all-checkbox', true);
		},

		getResetButton: function()
		{
			return BX.Grid.Utils.getByClass(this.getPopup().popupContainer, 'main-grid-settings-window-actions-item-reset', true);
		},

		createCheckbox: function()
		{
			var id = 'main-grid-settings-window-for-all-checkbox_' + this.parent.getParam('GRID_ID');

			return BX.decl({
				block: 'popup-window-button',
				mix: ['popup-window-button-link', 'main-grid-settings-window-for-all'],
				tag: 'span',
				content: [
					{
						block: 'main-grid-settings-window-for-all-checkbox',
						tag: 'input',
						attrs: {
							name: 'grid-settings-window-for-all',
							type: 'checkbox',
							id: id
						}
					},
					{
						block: 'main-grid-settings-window-for-all-label',
						tag: 'label',
						attrs: {
							for: id
						},
						content: this.parent.getParam('SETTINGS_FOR_ALL_LABEL')
					}
				]
			})
		}
	};

})();