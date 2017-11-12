requirejs.config({
    baseUrl: '/bitrix/js/seocontext.locations',
    paths: {
        'jquery': 'jquery/jquery',
        'jquery-private': 'jquery/jquery-private',
        'autocomplete': 'devbridge/jquery.autocomplete.min',
        'popup': 'popup/jquery.magnific-popup.min',
        'ymaps': '//api-maps.yandex.ru/2.1/?lang=ru_RU',
        'store': 'store/store.min',
        'editor': '//cdn.ckeditor.com/4.5.7/standard/ckeditor.js',
        'location': 'lib/location',
        'manager': 'lib/manager',
        'settings': 'lib/settings',
        'validator': 'validator/jquery.validate',
        'scroll': 'scroll/jquery.slimscroll.amd'
    },

    shim: {
        'ymaps': {
            exports: 'ymaps'
        },
        'editor': {
            exports: 'CKEDITOR'
        }
    },

    // Add this map config in addition to any baseUrl or
    // paths config you may already have in the project.
    map: {
        // '*' means all modules will get 'jquery-private'
        // for their 'jquery' dependency.
        '*': { 'jquery': 'jquery-private' },

        // 'jquery-private' wants the real jQuery module
        // though. If this line was not here, there would
        // be an unresolvable cyclic dependency.
        'jquery-private': { 'jquery': 'jquery' }
    }
});