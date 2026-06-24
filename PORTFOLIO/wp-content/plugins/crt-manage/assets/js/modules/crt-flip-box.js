(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-flip-box.default',function($scope) {

            var $flipBox = $scope.find('.crt-flip-box'),
                flipBoxTrigger = $flipBox.data('trigger');

            // Listen for the pageshow event to prevent undesired cache
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    if ( $flipBox.hasClass('crt-flip-box-active') ) {
                        $flipBox.removeClass('crt-flip-box-active');
                    }
                }
            });

            // Listen for the popstate event same purpose
            window.addEventListener('popstate', function(event) {
                if ( $flipBox.hasClass('crt-flip-box-active') ) {
                    $flipBox.removeClass('crt-flip-box-active');
                }
            });

            if ( 'box' === flipBoxTrigger ) {

                $flipBox.find('.crt-flip-box-front').on( 'click', function() {
                    $(this).closest('.crt-flip-box').addClass('crt-flip-box-active');
                });

                $(window).on( 'click', function () {
                    if( $(event.target).closest('.crt-flip-box').length === 0 ) {
                        $flipBox.removeClass('crt-flip-box-active');
                    }
                });

            } else if ( 'btn' == flipBoxTrigger ) {

                $flipBox.find('.crt-flip-box-btn').on( 'click', function() {
                    $(this).closest('.crt-flip-box').addClass('crt-flip-box-active');
                });

                $(window).on( 'click', function (event) {
                    if( $(event.target).closest('.crt-flip-box').length === 0 ) {
                        $flipBox.removeClass('crt-flip-box-active');
                    }
                });


            } else if ( 'hover' == flipBoxTrigger ) {

                $flipBox.hover(function () {
                    $(this).toggleClass('crt-flip-box-active');
                });

            }

        });
    });
})(jQuery);