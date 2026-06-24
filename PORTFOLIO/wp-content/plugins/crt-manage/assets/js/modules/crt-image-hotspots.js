(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-image-hotspots.default',function($scope) {
            var $imgHotspots = $scope.find( '.crt-image-hotspots' ),
                hotspotsOptions = $imgHotspots.data('options'),
                $hotspotItem = $imgHotspots.find('.crt-hotspot-item'),
                tooltipTrigger = hotspotsOptions.tooltipTrigger;

            if ( 'click' === tooltipTrigger ) {
                $hotspotItem.on( 'click', function() {
                    if ( $(this).hasClass('crt-tooltip-active') ) {
                        $(this).removeClass('crt-tooltip-active');
                    } else {
                        $hotspotItem.removeClass('crt-tooltip-active');
                        $(this).addClass('crt-tooltip-active');
                    }
                    event.stopPropagation();
                });

                $(window).on( 'click', function () {
                    $hotspotItem.removeClass('crt-tooltip-active');
                });

            } else if ( 'hover' === tooltipTrigger ) {
                $hotspotItem.on( 'mouseenter', function () {
                    $(this).addClass('crt-tooltip-active');
                });

                $hotspotItem.on( 'mouseleave', function () {
                    $(this).removeClass('crt-tooltip-active');
                });

            } else {
                $hotspotItem.addClass('crt-tooltip-active');
            }
        });
    });
})(jQuery);