(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-twitter-feed.default',function($scope) {


            if ($scope.find('.crt-twitter-feed').attr( 'data-settings' )) {
                var settings = JSON.parse( $scope.find('.crt-twitter-feed').attr( 'data-settings' ) );
            } else {
                return;
            }

            let twitterFeed = $scope.find('.crt-twitter-feed');

            var settings = JSON.parse( twitterFeed.attr( 'data-settings' ) );
            var loadMoreSettings = settings.twitter_load_more_settings;

            var nextPostsIndex = loadMoreSettings.number_of_posts;
            var pagination = $scope.find( '.crt-grid-pagination' );

            if ( $scope.hasClass('crt-twitter-feed-masonry') ) {
                // Init Functions
                isotopeLayout( settings );
                setTimeout(function() {
                    isotopeLayout( settings );
                }, 100 );

                if ( CrtElements.editorCheck() ) {
                    setTimeout(function() {
                        isotopeLayout( settings );
                    }, 500 );
                    setTimeout(function() {
                        isotopeLayout( settings );
                    }, 1000 );
                }

                $( window ).on( 'load', function() {
                    setTimeout(function() {
                        isotopeLayout( settings );
                    }, 100 );
                });

                $(window).smartresize(function(){
                    setTimeout(function() {
                        isotopeLayout( settings );
                    }, 200 );
                });
            }

            function isotopeLayout( settings ) {
                var twitterFeed = $scope.find( '.crt-twitter-feed' ),
                    item = twitterFeed.find( '.crt-tweet' ),
                    layout = settings.layout_select,
                    columns = 3,
                    gutterHr = settings.gutter_hr,
                    gutterVr = settings.gutter_vr,
                    contWidth = twitterFeed.width() + gutterHr - 0.3,
                    viewportWidth = $(window).outerWidth(),
                    transDuration = 400;

                var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
                var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
                var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
                var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
                var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
                var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

                // Mobile
                if (MobileResp >= viewportWidth ) {
                    columns = (settings.columns_mobile) ? (settings.columns_mobile) : 1;
                    // Mobile Extra
                } else if ( MobileExtraResp >= viewportWidth ) {
                    columns = (settings.columns_mobile_extra) ? settings.columns_mobile_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;
                    // Tablet
                } else if ( TabletResp >= viewportWidth ) {
                    columns = (settings.columns_tablet) ? settings.columns_tablet : 2;
                    // Tablet Extra
                } else if ( TabletExtraResp >= viewportWidth ) {
                    columns = (settings.columns_tablet_extra) ? settings.columns_tablet_extra : settings.columns_tablet ? settings.columns_tablet : settings.columns;

                    // Laptop
                } else if (  LaptopResp >= viewportWidth ) {
                    columns = (settings.columns_laptop) ? settings.columns_laptop : settings.columns;

                    // Desktop
                } else if ( wideScreenResp - 1 >= viewportWidth ) {
                    columns = settings.columns;

                    // Larger Screens
                } else if ( wideScreenResp <= viewportWidth ) {
                    columns = (settings.columns_widescreen) ? settings.columns_widescreen : settings.columns;
                } else {
                    columns = settings.columns
                }

                // Limit Columns for Higher Screens
                if ( columns > 8 ) {
                    columns = 8;
                }

                columns = parseInt(columns);

                if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
                    columns = 3;
                }

                // Calculate Item Width
                item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );

                // Set Vertical Gutter
                item.css( 'margin-bottom', gutterVr +'px' );

                // Reset Vertical Gutter for 1 Column Layout
                if ( 1 === columns ) {
                    item.last().css( 'margin-bottom', '0' );
                }

                // add last row & make all post equal height
                var maxTop = -1;

                // Run Isotope
                var twitterFeedMasonry = twitterFeed.isotopecrt({
                    layoutMode: layout,
                    masonry: {
                        comlumnWidth: contWidth / columns,
                        gutter: gutterHr
                    },
                    transitionDuration: transDuration,
                    percentPosition: true
                });

                if ( '1' !== twitterFeed.css( 'opacity' ) ) {
                    twitterFeed.css( 'opacity', '1' );
                }

                // return instagramFeed;//tmp
            }

            if ( !CrtElements.editorCheck() ) {
                $scope.find('.crt-load-more-twitter-posts').on('click', function() {
                    pagination.find( '.crt-load-more-btn' ).hide();
                    pagination.find( '.crt-pagination-loading' ).css( 'display', 'inline-block' );
                    // pagination.find( '.crt-pagination-finish' ).fadeIn(  );
                    // pagination.delay( 2000 ).fadeOut( 1000 );
                    // setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: WprConfig.ajaxurl,
                        data: {
                            action: 'crt_load_more_tweets',
                            nonce: WprConfig.nonce,
                            crt_load_more_settings: loadMoreSettings,
                            next_post_index: nextPostsIndex,
                        },
                        success: function(data) {
                            var $data = $(data);

                            $data.each(function() {
                                $(this).addClass('crt-twitter-hidden-item');
                            });


                            $scope.find('.crt-twitter-feed').append( $data );

                            setTimeout(function() {

                                if ( $scope.hasClass('crt-twitter-feed-masonry') ) {
                                    twitterFeed.isotopecrt( 'appended', $data );

                                    twitterFeed.isotopecrt( 'reloadItems' ); // https://isotope.metafizzy.co/methods.html#reloaditems

                                    twitterFeed.isotopecrt('layout'); // https://isotope.metafizzy.co/methods.html#layout

                                    $(window).trigger('resize');
                                }

                                $data.each(function(index) {
                                    var item = $(this);
                                    setTimeout(function() {
                                        item.removeClass('crt-twitter-hidden-item');
                                    }, 300);
                                });

                                // Loading
                                pagination.find( '.crt-pagination-loading' ).hide();

                                if (data.includes('crt-tweet')) { // replaceclassname
                                    pagination.find( '.crt-load-more-btn' ).fadeIn();
                                } else {
                                    pagination.find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                }
                            }, 400);

                            nextPostsIndex =  nextPostsIndex + loadMoreSettings.number_of_posts;
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });

                    // }, 1000);
                });
            }

            twitterFeedCarousel();

            $scope.find('.crt-grid').css('opacity', 1);

            function twitterFeedCarousel() {
                if ( $scope.hasClass('crt-twitter-feed-carousel') ) {
                    var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
                        // if ('undefined' === typeof Swiper) {
                        // 	var asyncSwiper = elementorFrontend.utils.swiper;
                        // 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
                        // 		return newSwiperInstance;
                        // 	});
                        // } else {
                        // 	return swiperPromise(swiperElement, swiperConfig);
                        // }

                        var asyncSwiper = elementorFrontend.utils.swiper;
                        return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
                            return newSwiperInstance;
                        });
                    };

                    var swiperPromise = function swiperPromise(swiperElement, swiperConfig) {
                        return new Promise(function (resolve, reject) {
                            var swiperInstance = new Swiper(swiperElement, swiperConfig);
                            resolve(swiperInstance);
                        });
                    };

                    $scope.find('.crt-twitter-feed').css('flexWrap', 'nowrap');

                    var sliderSettings = settings.carousel;

                    $scope.find('.crt-twitter-feed-cont').addClass('swiper');
                    $scope.find('.crt-twitter-feed').addClass('swiper-wrapper');
                    $scope.find('.crt-tweet').addClass('swiper-slide');
                    $scope.find('.crt-twitter-feed-cont').css('overflow', 'hidden');
                    // $scope.find('.elementor-container').css('margin', '0');
                    var swiperSlider = $scope.find('.crt-twitter-feed-cont');

                    var aboveMobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value + 1;
                    var aboveMobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value + 1;
                    var aboveTabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value + 1;
                    var aboveTabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value + 1;
                    var aboveLaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value + 1;
                    var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

                    swiperLoader(swiperSlider, {
                        autoplay: sliderSettings.crt_cs_autoplay === 'yes' ? {
                            delay: +sliderSettings.crt_cs_delay,
                        } : false,
                        loop: sliderSettings.crt_cs_loop === 'yes' ? true : false,
                        slidesPerView: +sliderSettings.crt_cs_slides_to_show,
                        spaceBetween: +sliderSettings.crt_cs_space_between,
                        speed: +sliderSettings.crt_cs_speed,
                        pagination: sliderSettings.crt_cs_pagination === 'yes' ? {
                            el: '.swiper-pagination',
                            type: sliderSettings.crt_cs_pagination_type,
                        } : false,
                        navigation: {
                            prevEl: '.crt-swiper-button-prev',
                            nextEl: '.crt-swiper-button-next',
                        },
                        // Responsive breakpoints - direction min
                        breakpoints: {
                            320: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show_mobile,
                                // spaceBetween: +sliderSettings.crt_cs_space_between_mobile,
                            },
                            [aboveMobileResp]: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show_mobile_extra,
                                // spaceBetween: +sliderSettings.crt_cs_space_between_mobile_extra,
                            },
                            [aboveMobileExtraResp]: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show_tablet,
                                spaceBetween: +sliderSettings.crt_cs_space_between_tablet,
                            },
                            [aboveTabletResp]: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show_tablet_extra,
                                spaceBetween: +sliderSettings.crt_cs_space_between_tablet_extra,
                            },
                            [aboveTabletExtraResp]: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show_laptop,
                                spaceBetween: +sliderSettings.crt_cs_space_between_laptop,
                            },
                            [aboveLaptopResp]: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show,
                                spaceBetween: +sliderSettings.crt_cs_space_between,
                            },
                            [wideScreenResp]: {
                                slidesPerView: +sliderSettings.crt_cs_slides_to_show_widescreen,
                                spaceBetween: +sliderSettings.crt_cs_space_between_widescreen,
                            }
                        },

                    });

                    $scope.css('opacity', 1);
                }
            }
        });

        var CrtElements = {
            editorCheck: function() {
                return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
            },
        }
        
    });
})(jQuery);