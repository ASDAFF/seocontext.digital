seodefine(['jquery', 'location'], function ($, location) {
    // todo: DEFAULT_CODE value must be taken from Seocontext\Locations\Content::DEFAULT_CONTENT_FILENAME
    const DEFAULT_CODE = 'default';
    const CONTENT_URL = '/bitrix/components/seocontext/cond.include/settings/content.php';

    var manager = {};
    manager.options = [];
    manager.includeId = [];

    var defaults = {
        type: 'ajax'
    };

    /**
     * Options is object of the type:
     * {id: 'blockId' [, includeId: 'id'] [, type: 'ajax'|'js']}.
     * If type is 'ajax' then includeId param become compulsory.
     * When type is 'js', manager will not try to ajax-load content.
     * In this case all content blocks must be already presented on page.
     * @param options
     */
    manager.init = function (options) {
        options = $.extend({}, defaults, options);
        manager.options.push(options);
        manager.showContent(options.id);
    };

    manager.getLocationCode = function () {
        var code = DEFAULT_CODE;
        var currLocation = location.get();
        if (currLocation)
            code = currLocation.code;
        return code;
    }

    /**
     * Shows specific for given location code content in all conditional include blocks.
     * If locationCode is omitted then current location code is used.
     * @param locationCode
     */
    manager.showContentForAll = function (locationCode) {

        if (typeof locationCode === "undefined")
            locationCode = manager.getLocationCode();

        for (var i = 0; i < manager.options.length; i++) {
            var id = manager.options[i].id;
            manager.showContent(id, locationCode);
        }
    };

    /**
     * Shows specific for given locationCode content in conditional include blocks with given blockId.
     * If locationCode is omitted then current location code is used.
     * @param blockId
     * @param locationCode
     */
    manager.showContent = function (blockId, locationCode) {
        if (typeof locationCode === "undefined")
            locationCode = manager.getLocationCode();

        var options = getOptionsById(blockId);
        if (options.type == 'js')
            showContentJs(blockId, locationCode);
        else
            showContentAjax(locationCode, options.includeId).done(function (html) {
                // when type = ajax it is supposed that
                // there is only one block with given blockId
                var block = $('[data-cond-id=' + blockId + ']');
                block.html(html);
                block.attr('data-cond-code', locationCode);
                block.show();
            });
    };

    function getOptionsById(id) {
        for (var i = 0; i < manager.options.length; i++) {
            if (manager.options[i].id == id)
                return manager.options[i];
        }

        throw "Not found options with id = " + id;
    }

    function showContentJs(blockId, locationCode) {
        var incBlocks = $('[data-cond-id=' + blockId + ']');
        incBlocks.hide();
        var showBlock = incBlocks.filter('[data-cond-code=' + locationCode + ']');
        if (showBlock.length > 0)
            showBlock.show();
        else
            incBlocks.filter('[data-cond-code=' + DEFAULT_CODE + ']').show();
    }

    function showContentAjax(locationCode, includeId) {
        return $.get(CONTENT_URL, {code: locationCode, includeId: includeId, useDefault: true});
    }

    manager.locationChanged = function () {
        manager.showContentForAll();
    };

    $(document).on('location:change', manager.locationChanged);

    return manager;
});

