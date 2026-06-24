'use strict';
(function($) {

    setTimeout(function(){
        $.getScript( CRT_MANAGE.URI + "assets/js/frontend/theia-sticky-sidebar.min.js").done(function () {
            $.getScript( CRT_MANAGE.URI + "assets/js/frontend/setting-sticky.js");
        });
        $.getScript( CRT_MANAGE.URI + "assets/js/frontend/resize-sensor.min.js");
    }, 1000);

})(jQuery);