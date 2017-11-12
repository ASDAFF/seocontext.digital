requirejs(['/bitrix/js/seocontext.locations/main.js'], function(){
    requirejs(['jquery', 'location', 'autocomplete', 'popup', 'scroll'], function ($, location, a, p) {
        $(function () {

            $('.seocontext-locations .selected-locations').slimScroll({
                size: '8px',
                height: '100px',
                width: '100%'
            });


            const progressStatus = {
                LocationDetecting: 0,
                LocationDetected: 1,
                Loading: 2
            };

            var selectedLocation;
            var searchLocationsInput = $('.seocontext-locations input[name="location"]');

            $(document).on('location:change', function () {
                // check if need to reload
                if ($('input#seocontext_locations_reload').length > 0)
                    document.location.reload(true);
                processLocation();
            });


            processLocation();
            // setting autocomplete
            var options = {
                serviceUrl: '/bitrix/components/seocontext/locations/templates/.default/search-ajax.php',
                dataType: 'json',
                showNoSuggestionNotice: true,
                minChars: 2,
                onSelect: function (suggestion) {
                    console.log(suggestion);
                    var name = suggestion.value.split(',')[0];
                    selectedLocation = {'code': suggestion.data, 'name': name};
                },
            };
            searchLocationsInput.autocomplete(options);

            // setting popup
            $('.seocontext-locations-show').magnificPopup({
                type: 'inline',
                midClick: true, // Allow opening popup on middle mouse click. Always set it to true if you don't provide alternative source in href.
                //closeBtnInside: true
                callbacks: {
                    open: function () {
                        selectedLocation = location.get();
                        if (selectedLocation) {
                            searchLocationsInput.val(selectedLocation.name);
                        } else {
                            searchLocationsInput.val("");
                        }
                    },
                }
            });

            $(".seocontext-locations button").on('click', function () {
                $.magnificPopup.close();
                var btn = $(this);
                if (!btn.hasClass('save-location')) return;
                if (selectedLocation)
                    location.set(selectedLocation.code, selectedLocation.name);
                else
                    location.reset();
            });

            $('.seocontext-locations .reset-location').on('click', function() {
                searchLocationsInput.val('');
                searchLocationsInput.autocomplete().hide();
                selectedLocation = '';
            });

            $('ul.selected-locations li').on('click', function () {
                $li = $(this);
                console.log('setting location');
                var code = $li.attr('data-code');
                var name = $li.text().trim();
                //location = {'code': code, 'name': name};
                location.set(code, name);
                $.magnificPopup.close();
            });

            function processLocation() {
                // check cache

                var currLocation = location.get();
                console.log('location was set: ', currLocation);
                if (currLocation) {
                    $(".seocontext-selected-location").text(currLocation.name);
                    displayDetectingBlock(progressStatus.Detected);
                }
                else {
                    displayDetectingBlock(progressStatus.Loading);
                    // detect current position
                    requirejs(['ymaps'], function(ymaps){
                        ymaps.ready(init);
                    });
                }
            }

            function init() {
                var addressLine;
                var country;
                var administrativeArea;
                var subAdministrativeArea;
                var locality;
                var CityAndCode;


                var geolocation = ymaps.geolocation;
                // position by ip
                console.log("init");
                var promise = geolocation.get({
                    provider: 'yandex'
                }).then(function (result) {
                    console.log("then");
                    var res = result.geoObjects.get(0).properties._data.metaDataProperty.GeocoderMetaData.AddressDetails.Country;
                    addressLine = res['AddressLine'];
                    country = res['CountryName'];
                    administrativeArea = res['AdministrativeArea']['AdministrativeAreaName'];
                    subAdministrativeArea = res['AdministrativeArea']['SubAdministrativeArea']['SubAdministrativeAreaName'];
                    locality = res['AdministrativeArea']['SubAdministrativeArea']['Locality']['LocalityName'];

                    //console.log(result.geoObjects.get(0).properties._data.metaDataProperty);
                    getBitrixLocationCodeAndName(locality, subAdministrativeArea, administrativeArea);
                },
                function (result) {
                    $('.seocontext-selected-location').text($('.seocontext-selected-location').attr('data-choose-message'));
                    displayDetectingBlock(progressStatus.Detected);

                    console.log("rejected", result);
                    console.log(promise);
                    console.log(promise.valueOf());
                });

            }

            /**
             *
             * @param status integer
             */
            function displayDetectingBlock(status) {
                switch (status) {
                    case progressStatus.LocationDetecting:
                        $(".seocontext-detecting-location").show();
                        $(".seocontext-selected-location").hide();
                        $(".seocontext-detecting-progress").hide();
                        break;
                    case progressStatus.Detected:
                        $(".seocontext-detecting-location").hide();
                        $(".seocontext-selected-location").show();
                        $(".seocontext-detecting-progress").hide();
                        break;
                    case progressStatus.Loading:
                        $(".seocontext-detecting-location").hide();
                        $(".seocontext-selected-location").hide();
                        $(".seocontext-detecting-progress").show();
                        break;
                }
            }

            var currPositionName;

            function getBitrixLocationCodeAndName(locality, subAdministrativeArea, administrativeArea) {
                console.log(locality);
                $.post("/bitrix/components/seocontext/locations/templates/.default/getLocation.php", {
                        Locality: locality,
                        SubAdministrativeArea: subAdministrativeArea,
                        AdministrativeArea: administrativeArea
                    },
                    function (data) {
                    })
                    .done(function (data) {
                        var post_res = $.parseJSON(data);
                        console.log(post_res[1]);
                        currPositionName = post_res[1];
                        if (currPositionName == null) {
                            displayDetectingBlock(progressStatus.Detected);
                        } else {
                            $(".seocontext-detected-location").text(currPositionName);
                            displayDetectingBlock(progressStatus.LocationDetecting);

                            $(".seocontext-detected-location-yes").on("click", function (e) {
                                location.set(post_res[0], post_res[1]);
                                displayDetectingBlock(progressStatus.Detected);
                                e.preventDefault();
                            });
                        }
                    });
            }
        });

    });
});



