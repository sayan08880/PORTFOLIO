(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-back-to-top.default',function($scope) {

            var sttBtn = $scope.find( '.crt-stt-btn' ),
                settings = sttBtn.attr('data-settings');

            // Get Settings
            settings = JSON.parse(settings);

            if ( settings.fixed === 'fixed' ) {

                if ( 'none' !== settings.animation ) {
                    sttBtn.css({
                        'opacity' : '0'
                    });

                    if ( settings.animation ==='slide' ) {
                        sttBtn.css({
                            'margin-bottom': '-100px',
                        });
                    }
                }

                // Run on Load
                scrollToTop($(window).scrollTop(), sttBtn, settings);

                // Run on Scroll
                $(window).scroll(function() {
                    scrollToTop($(this).scrollTop(), sttBtn, settings);
                });
            } // end fixed check

            // Click to Scroll Top
            sttBtn.on('click', function() {
                $('html, body').animate({ scrollTop : 0}, settings.scrolAnim );
                return false;
            });

            function scrollToTop( scrollTop, button, settings ) {
                // Show
                if ( scrollTop > settings.animationOffset ) {

                    if ( 'fade' === settings.animation ) {
                        sttBtn.stop().css('visibility', 'visible').animate({
                            'opacity' : '1'
                        }, settings.animationDuration);
                    } else if ( 'slide' === settings.animation ){
                        sttBtn.stop().css('visibility', 'visible').animate({
                            'opacity' : '1',
                            'margin-bottom' : 0
                        }, settings.animationDuration);
                    } else {
                        sttBtn.css('visibility', 'visible');
                    }

                    // Hide
                } else {

                    if ( 'fade' === settings.animation ) {
                        sttBtn.stop().animate({'opacity': '0'}, settings.animationDuration);
                    } else if (settings.animation === 'slide') {
                        sttBtn.stop().animate({
                            'margin-bottom' : '-100px',
                            'opacity' : '0'
                        }, settings.animationDuration);
                    } else {
                        sttBtn.css('visibility', 'hidden');
                    }

                }
            }
        });
    });
})(jQuery);