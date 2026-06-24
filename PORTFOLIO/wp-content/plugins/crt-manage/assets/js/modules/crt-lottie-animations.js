(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-lottie-animations.default',function($scope) {

            var lottieAnimations = $scope.find('.crt-lottie-animations'),
                lottieAnimationsWrap = $scope.find('.crt-lottie-animations-wrapper'),
                lottieJSON = JSON.parse(lottieAnimations.attr('data-settings'));

            var animation = lottie.loadAnimation({
                container: lottieAnimations[0], // Required
                path: lottieAnimations.attr('data-json-url'), // Required
                renderer: lottieJSON.lottie_renderer, // Required
                loop: 'yes' === lottieJSON.loop ? true : false, // Optional
                autoplay: 'yes' === lottieJSON.autoplay ? true : false
            });

            animation.setSpeed(lottieJSON.speed);

            if( lottieJSON.reverse ) {
                animation.setDirection(-1);
            }

            animation.addEventListener('DOMLoaded', function () {

                if ( 'hover' !== lottieJSON.trigger && 'none' !== lottieJSON.trigger ) {

                    // if ( 'viewport' === lottieJSON.trigger ) {
                    initLottie('load');
                    $(window).on('scroll', initLottie);
                }

                if ( 'hover' === lottieJSON.trigger ) {
                    animation.pause();
                    lottieAnimations.hover(function () {
                        animation.play();
                    }, function () {
                        animation.pause();
                    });
                }

                function initLottie(event) {
                    animation.pause();

                    if (typeof lottieAnimations[0].getBoundingClientRect === "function") {

                        var height = document.documentElement.clientHeight;
                        var scrollTop = (lottieAnimations[0].getBoundingClientRect().top)/height * 100;
                        var scrollBottom = (lottieAnimations[0].getBoundingClientRect().bottom)/height * 100;
                        var scrollEnd = scrollTop < lottieJSON.scroll_end;
                        var scrollStart = scrollBottom > lottieJSON.scroll_start;

                        if ( 'viewport' === lottieJSON.trigger ) {
                            scrollStart && scrollEnd ? animation.play() : animation.pause();
                        }

                        if ( 'scroll' === lottieJSON.trigger ) {
                            if( scrollStart && scrollEnd) {
                                animation.pause();

                                // $(window).scroll(function() {
                                // calculate the percentage the user has scrolled down the page
                                var scrollPercent = 100 * $(window).scrollTop() / ($(document).height() - $(window).height());

                                var scrollPercentRounded = Math.round(scrollPercent);

                                animation.goToAndStop( (scrollPercentRounded / 100) * 4000); // why 4000
                                // });
                            }
                        }
                    }
                }
            });
        });
    });
})(jQuery);