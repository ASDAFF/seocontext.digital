function onCondIncludeSettingsCreate(arParams) {
    // check if requirejs is loaded
    console.log('onSettingsCreate');
    if (typeof requirejs === 'function') {
        createSettings();
    } else {
        var requirejsScript = document.createElement('script');
        requirejsScript.type = 'text/javascript';
        requirejsScript.src = '/bitrix/js/seocontext.locations/require.js';
        requirejsScript.onload = function (s) {
            console.log("requirejs loaded");
            createSettings();
        }
        document.getElementsByTagName('head')[0].appendChild(requirejsScript);
    }

    function createSettings()
    {
        requirejs(['/bitrix/js/seocontext.locations/main.js'], function () {
            requirejs(['settings'], function (settings) {
                settings.onSettingsCreate(arParams);
            });
        });
    }
}

