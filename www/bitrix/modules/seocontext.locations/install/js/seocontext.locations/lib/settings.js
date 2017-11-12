seodefine(['jquery', 'autocomplete'], function ($, a) {
    var includeIdContainer;
    const contentManagerUrl = '/bitrix/components/seocontext/cond.include/settings/content.php';
    var locationInParams;
    var selectedLocationCode;

    // Private functions
    function addLocationToTable(code) {

        var isUnique = true;
        // Check select list for adding code
        $('#select-location option').each(function () {
            if (code == $(this).val()) {
                isUnique = false;
                return false;
            }
        });

        if (isUnique) {
            $.post(contentManagerUrl, {Code: code, action: "addloc"},
                function (data) {
                    var result = $.parseJSON(data);
                    $('#select-location')
                        .append($('<option>', {value: result['code'], deletable: 'Y'})
                            .text(result['name']));
                    $('button#add-loc').prop("disabled", true);
                });
        }
    }

    function deleteLocationFromTable(code, selectedDeletable) {
        console.log(code, selectedDeletable);
        if (code == 'default' || selectedDeletable == 'N')
            return false;
        else {
            $.post(contentManagerUrl, {Code: code, action: "delloc"},
                function (data) {
                    $("#select-location option[value='" + code + "']").remove();
                });
        }
    }

    function initEditor() {
        if (typeof CKEDITOR.instances['seocontext_content_editor'] !== 'undefined') {
            CKEDITOR.instances['seocontext_content_editor'].destroy();
        }
        CKEDITOR.replace('seocontext_content_editor');
    }

    function getIncludeId() {
        var input = includeIdContainer.find('input');
        if (!input.is(':disabled')) {
            return input.val();
        }

        var select = includeIdContainer.find('select');
        return select.val();
    }

    function validate(input) {
        if ($(input).is(":disabled"))
            return {valid: true};
        var val = $(input).val();
        if (val == '')
            return {valid: false, message: "Field can't be empty"};
        if (/[^a-zA-Z0-9a-яА-ЯёЁ]/i.test(val))
            return {valid: false, message: "Identifier can't contain non alphanumeric symbols"};
        return {valid: true};
    }

    var settings = {
        onSettingsCreate: function (arParams) {
            //console.log(arParams.getElements());
            console.log($('head'));
            $('<link rel="stylesheet" data-template-style="true" type="text/css" href="/bitrix/components/seocontext/cond.include/settings/settings.css" />')
                .appendTo('head');
            var container = $(arParams.oCont);
            includeIdContainer = $(arParams.getElements()["INCLUDE_ID"]).parent();
            var inputIncludeId = includeIdContainer.find('input');
            inputIncludeId.after("<div></div>");

            //==== set validation: includeId must be nonempty ====

            includeIdContainer.on('change keyup','select, input', function(){
                console.log("changed");
                var validResult = validate(inputIncludeId);
                if (validResult.valid) {
                    $(inputIncludeId).removeClass('seocontext-error');
                    $(inputIncludeId).next('div').html("");
                    container.show();
                } else {
                    $(inputIncludeId).addClass('seocontext-error');
                    $(inputIncludeId).next('div').html(validResult.message);
                    container.hide();
                }
            });
            //=====


            // todo: replace with relative path
            $.get('/bitrix/components/seocontext/cond.include/settings/settings.php').done(function (html) {
                var $html = $($.parseHTML(html));
                $html.find('#save-btn').on('click', function () {
                    //var hidden = $(this).parent().find('input[type="hidden"]');
                    //hidden.val(textarea.html());
                    //console.log(hidden);
                    settings.showLoading();
                    var content = CKEDITOR.instances['seocontext_content_editor'].getData();
                    settings.saveLocationSpecificContent(locationInParams, content).done(function () {
                        settings.hideLoading();
                    });
                    return false;
                });

                $html.find('#del-loc').on('click', function () {
                    var selectedOptionCode = $("#select-location option:selected").val();
                    var selectedDeletable = $("#select-location option:selected").attr("deletable");
                    deleteLocationFromTable(selectedOptionCode, selectedDeletable);
                    return false;
                });

                $html.find('#add-loc').on('click', function () {
                    addLocationToTable(selectedLocationCode);
                    console.log(selectedLocationCode);
                    $html.find('input').val("");
                    return false;
                });

                $html.find('select').on('change', function () {
                    var locCode = $(this).val();
                    settings.locationChanged(locCode);
                    var deletableOption = $("#select-location option:selected").attr("deletable");
                    console.log(deletableOption);
                    if (deletableOption == 'N')
                        $html.find('button#del-loc').prop("disabled", true);
                    else
                        $html.find('button#del-loc').prop("disabled", false);
                });

                $html.find('select').on('onblur', function () {
                    $html.find('button#del-loc').prop("disabled", true);
                });

                $html.find('button#add-loc').prop("disabled", true);
                $html.find('button#del-loc').prop("disabled", true);

                // REMOVED FOR NOW
                // location.js is not loaded when cond.include component is just added
                // in page editing form so we must check it
                //if (typeof seocontext.location !== "undefined")
                //{
                //    var currentLocation = seocontext.location.get();
                //    if (currentLocation) {
                //        var name = currentLocation.name;
                //        $html.find('input').val(name);
                //        that.locationChanged(currentLocation.code);
                //    }
                //}

                settings.setAutocomplete($html.find('input'));

                container.append($html);
                if (typeof CKEDITOR === 'undefined') {
                    settings.loadScript("//cdn.ckeditor.com/4.5.8/standard/ckeditor.js").done(function () {
                        initEditor();
                    });
                } else {
                    initEditor();
                }

            });

            //$.cachedScript('http://cdn.tinymce.com/4/tinymce.min.js').done(function(){
            //    tinymce.init({
            //        selector: 'textarea'
            //    });
            //    console.log("done");
            //});
        },

        setAutocomplete: function (input) {
            // setting autocomplete
            var options = {
                // todo: make relative or automatic
                serviceUrl: '/bitrix/components/seocontext/cond.include/settings/search-ajax.php',
                dataType: 'json',
                showNoSuggestionNotice: true,
                minChars: 2,
                onSelect: function (suggestion) {
                    console.log(suggestion);
                    settings.locationChanged(suggestion.data);
                    selectedLocationCode = suggestion.data;
                    $('button#add-loc').prop("disabled", false);
                    //locationManager.setCurrentLocation(suggestion.data, suggestion.value);
                }
            };
            $(input).autocomplete(options);
        },

        locationChanged: function (locCode) {
            this.showLoading();
            console.log(locCode);
            locationInParams = locCode;
            this.getLocationSpecificContent(locCode).done(function (html) {
                CKEDITOR.instances['seocontext_content_editor'].setData(html);
                settings.hideLoading();
            });
        },

        showLoading: function () {

        },

        hideLoading: function () {

        },

        getLocationSpecificContent: function (locationCode) {
            console.log(getIncludeId());
            return $.get(contentManagerUrl, {code: locationCode, includeId: getIncludeId()});
        },

        saveLocationSpecificContent: function (locationCode, content) {
            return $.post(contentManagerUrl, {code: locationCode, content: content, action: 'save', includeId: getIncludeId()});
        },


        loadScript: function (url, options) {
            // Allow user to set any option except for dataType, cache, and url
            options = $.extend(options || {}, {
                dataType: "script",
                cache: true,
                url: url
            });

            // Use $.ajax() since it is more flexible than $.getScript
            // Return the jqXHR object so we can chain callbacks
            return $.ajax(options);
        }
    };

    return settings;
});
