seodefine(['jquery','store'], function ($, store) {
    // some config
    $(function(){
        configureLocations();
        function configureLocations()
        {
            // when on one page it was placed many location components
            // repeated popup div blocks should be deleted
            $(".seocontext-locations.mfp-hide:gt(0)").remove();
            //popups
            //for(var i = 1; i < popups.length; i++)
            //{
            //    var popup = popups.get(i);
            //    popup
            //}
        }
    });


    // working with localStorage and cookies
    const key = "SEOCONTEXT_LOCATION";
    return {
        get: function () {
            return store.get(key);
        },
        set: function (code, name) {
            var oldLocation = this.get();
            store.set(key, {'code': code, 'name': name});

            // setting cookie
            var date = new Date(new Date().getTime() + 31104000 * 1000);
            document.cookie = key + "_COOKIE=" + code + "; path=/; expires=" + date.toUTCString();

            if (!oldLocation || oldLocation.code != code)
                $(document).trigger('location:change');
        },
        reset: function() {
            var oldLocation = this.get();
            store.remove(key);
            document.cookie = key + "_COOKIE=; path=/; expires=Thu, 01 Jan 1970 00:00:01 GMT";
            if (oldLocation)
                $(document).trigger('location:change');
        }

    }
});
