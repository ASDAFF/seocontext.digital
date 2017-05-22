;(function() {

"use strict";

BX.namespace("BX.Kanban");

BX.Kanban.DropZone = function(options)
{
	options = options || {};
	if (!BX.Kanban.Utils.isValidId(options.id))
	{
		throw new Error("BX.Kanban.DropZone: 'id' parameter is not valid.")
	}

	this.id = options.id;
	this.name = BX.type.isNotEmptyString(options.name) ? options.name : null;
	this.color = options.color;
	this.grid = null;
	this.layout = {
		container: null
	};
};

BX.Kanban.DropZone.prototype =
{
	/**
	 *
	 * @returns {number|string}
	 */
	getId: function()
	{
		return this.id;
	},

	/**
	 * @param {BX.Kanban.Grid} grid
	 */
	setGrid: function(grid)
	{
		this.grid = grid;
	},

	/**
	 * @returns {BX.Kanban.Grid}
	 */
	getGrid: function()
	{
		return this.grid;
	},

	render: function()
	{
		var dropContainer = BX.create("div", {
			attrs: {
				className: "main-kanban-dropzone",
				"data-id": this.getId()
			},
			children: [
				BX.create("div", {
					attrs: {
						className: "main-kanban-dropzone-title"
					},
					html: this.name
				}),
				BX.create("div", {
					attrs: {
						className: "main-kanban-dropzone-bg",
						style: "background: #" + this.color
					}
				})
			]
		});

		return dropContainer;
	}
};

})();
