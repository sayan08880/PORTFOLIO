(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-content-ticker.default',function($scope) {
            var $contentTickerSlider = $scope.find( '.crt-ticker-slider' ),
                $contentTickerMarquee = $scope.find( '.crt-ticker-marquee' ),
                marqueeData = $contentTickerMarquee.data('options');
            // Slider Columns
            var sliderClass = $scope.attr('class'),
                sliderColumnsDesktop = sliderClass.match(/crt-ticker-slider-columns-\d/) ? +sliderClass.match(/crt-ticker-slider-columns-\d/).join().slice(-1) : 2,
                sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? +sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
                sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? +sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
                sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? +sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
                sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? +sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
                sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? +sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
                sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? +sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
                dataSlideEffect = $contentTickerSlider.attr('data-slide-effect'),
                sliderSlidesToScroll = 'hr-slide' === dataSlideEffect && sliderClass.match(/crt-ticker-slides-to-scroll-\d/) ? +(sliderClass.match(/crt-ticker-slides-to-scroll-\d/).join().slice(-1)) : 1;

            $contentTickerSlider.slick({
                appendArrows : $scope.find('.crt-ticker-slider-controls'),
                slidesToShow: sliderColumnsDesktop,
                responsive: [
                    {
                        breakpoint: 10000,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsWideScreen,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 2399,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsDesktop,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 1221,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsLaptop,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsTabletExtra,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsTablet,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 880,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsMobileExtra,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect ) ? 1 : sliderColumnsMobile,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
                            fade: ('typing' === dataSlideEffect || 'fade' === dataSlideEffect) ? true : false
                        }
                    }
                ],
            });

            $contentTickerMarquee.marquee(marqueeData);
            if ( $scope.find('.crt-marquee-hidden').length > 0 ) {
                $scope.find('.crt-ticker-marquee').removeClass('crt-marquee-hidden');
            }

        });
    });
})(jQuery);