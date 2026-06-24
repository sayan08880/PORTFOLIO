(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-instagram-feed.default',function($scope) {


            if ( !($scope.find('.crt-insta-feed-content-wrap').length  > 0) ) {
                return;
            }

            let instaFeed = $scope.find('.crt-instagram-feed');

            if ( instaFeed.attr( 'data-settings' ) ) {
                var settings = JSON.parse( instaFeed.attr( 'data-settings' ) );
                var loadMoreSettings = settings.insta_load_more_settings;
            }

            var widgetID = $scope.attr('data-id');

            var nextPostsIndex = loadMoreSettings.limit;
            var pagination = $scope.find( '.crt-grid-pagination' ); // Isotope Layout

            if ( $scope.hasClass('crt-insta-feed-layout-full-width') ) {
                if ( loadMoreSettings.limit > $scope.find('.crt-insta-feed-content-wrap').length ) {
                    $scope.find('.crt-layout-full-width').css('grid-template-columns', "repeat("+ $scope.find('.crt-insta-feed-content-wrap').length +", minmax(0, 1fr))");
                }
            }

            if ( $scope.hasClass('crt-insta-feed-masonry') ) {
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

            if ( $scope.hasClass('crt-insta-feed-layout-list') ) {
                var mediaAlign = settings.media_align,
                    mediaWidth = settings.media_width,
                    mediaDistance = settings.media_distance;
                $scope.find( '.crt-insta-feed-item-below-content' ).css({
                    'float' : mediaAlign,
                    'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
                });

                $(window).smartresize(function() {
                    mediaAlign = settings.media_align,
                        mediaWidth = settings.media_width,
                        mediaDistance = settings.media_distance;
                    $scope.find( '.crt-insta-feed-item-below-content' ).css({
                        'float' : mediaAlign,
                        'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
                    });
                });
            }

            function isotopeLayout( settings ) {
                var instaFeed = $scope.find( '.crt-instagram-feed' ),
                    item = instaFeed.find( '.crt-insta-feed-content-wrap' ),
                    layout = settings.insta_layout_select,
                    columns = 3,
                    gutterHr = settings.gutter_hr,
                    gutterVr = settings.gutter_vr,
                    contWidth = instaFeed.width() + gutterHr - 0.3,
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

                // Run Isotope
                var instagramFeed = instaFeed.isotopecrt({
                    layoutMode: layout,
                    masonry: {
                        comlumnWidth: contWidth / columns,
                        gutter: gutterHr
                    },
                    transitionDuration: transDuration,
                    percentPosition: true
                });
                // return instagramFeed;//tmp
            }

            if ( !CrtElements.editorCheck() ) {
                $scope.find('.crt-load-more-insta-posts').on('click', function() {
                    pagination.find( '.crt-load-more-btn' ).hide();
                    pagination.find( '.crt-pagination-loading' ).css( 'display', 'inline-block' );
                    // pagination.find( '.crt-pagination-finish' ).fadeIn(  );
                    // pagination.delay( 2000 ).fadeOut( 1000 );
                    // setTimeout(function() {
                    $.ajax({
                        type: 'POST',
                        url: CRTConfig.ajaxurl,
                        data: {
                            action: 'crt_load_more_instagram_posts',
                            nonce: CRTConfig.nonce,
                            crt_load_more_settings: loadMoreSettings,
                            crt_insta_feed_widget_id: widgetID,
                            next_post_index: nextPostsIndex,
                        },
                        success: function(data) {
                            var $data = $(data);

                            $data.each(function() {
                                $(this).addClass('crt-instagram-hidden-item');
                            });

                            $scope.find('.crt-instagram-feed').append( $data );


                            if ( $scope.hasClass('crt-insta-feed-layout-list') ) {
                                mediaAlign = settings.media_align,
                                    mediaWidth = settings.media_width,
                                    mediaDistance = settings.media_distance;
                                $scope.find( '.crt-insta-feed-item-below-content' ).css({
                                    'float' : mediaAlign,
                                    'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
                                });
                            }

                            if ( $scope.hasClass('crt-insta-feed-masonry') ) {
                                instaFeed.isotopecrt( 'appended', $data );

                                instaFeed.isotopecrt( 'reloadItems' ); // https://isotope.metafizzy.co/methods.html#reloaditems

                                instaFeed.isotopecrt('layout'); // https://isotope.metafizzy.co/methods.html#layout

                                $(window).trigger('resize');
                            }

                            setTimeout(function() {

                                $data.each(function(index) {
                                    var item = $(this);
                                    setTimeout(function() {
                                        item.removeClass('crt-instagram-hidden-item');
                                    }, 100);
                                });

                                // Loading
                                pagination.find( '.crt-pagination-loading' ).hide();

                                if (data.includes('crt-insta-feed-content-wrap')) {
                                    setTimeout(function() {
                                        pagination.find( '.crt-load-more-btn' ).fadeIn();
                                    }, 400);
                                } else {
                                    pagination.find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                    pagination.delay( 2000 ).fadeOut( 1000 );
                                    setTimeout(function() {
                                        pagination.find( '.crt-pagination-loading' ).hide();
                                    }, 500 );
                                }

                            }, 400);

                            // if ( loadMoreSettings.is_mobile === 'mobile' ) {
                            // 	nextPostsIndex =  nextPostsIndex + loadMoreSettings.limit_mobile;
                            // } else {
                            // 	nextPostsIndex =  nextPostsIndex + loadMoreSettings.limit;
                            // }
                            nextPostsIndex =  nextPostsIndex + loadMoreSettings.limit;

                            if ( instaFeed.data('lightGallery') ) {
                                // Fix Lightbox
                                instaFeed.data( 'lightGallery' ).destroy( true );
                            }

                            mediaHoverLink();
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                    // }, 1000);
                });
            }

            if ( $scope.find('.crt-layout-carousel') ) {
                instaFeedCarousel();
            }

            $(document).ready(function() {
                $scope.find('.crt-grid-pagination').removeClass('crt-pagination-hidden');
            });

            $(document).ready(function() {
                // Handler when all assets (including images) are loaded
                if ( instaFeed.length ) {
                    instaFeed.css('opacity', 1);
                }
            });

            if ( CrtElements.editorCheck() ) {
                // Handler when all assets (including images) are loaded
                if ( instaFeed.length ) {
                    instaFeed.css('opacity', 1);
                }
            }

            // Init Media Hover Link
            mediaHoverLink();

            // Init Lightbox
            lightboxPopup( settings );

            // Init Post Sharing
            postSharing();

            var mutationObserver = new MutationObserver(function(mutations) {
                // Init Media Hover Link
                mediaHoverLink();

                lightboxPopup( settings );
            });

            mutationObserver.observe($scope[0], {
                childList: true,
                subtree: true,
            });

            // Post Sharing
            function postSharing() {
                if ( $scope.find( '.crt-sharing-trigger' ).length ) {
                    var sharingTrigger = $scope.find( '.crt-sharing-trigger' ),
                        sharingInner = $scope.find( '.crt-post-sharing-inner' ),
                        sharingWidth = 5;

                    // Calculate Width
                    sharingInner.first().find( 'a' ).each(function() {
                        sharingWidth += $(this).outerWidth() + parseInt( $(this).css('margin-right'), 10 );
                    });

                    // Calculate Margin
                    var sharingMargin = parseInt( sharingInner.find( 'a' ).css('margin-right'), 10 );

                    // Set Positions
                    if ( 'left' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Width
                        sharingInner.css( 'width', sharingWidth +'px' );

                        // Set Position
                        sharingInner.css( 'left', - ( sharingMargin + sharingWidth ) +'px' );
                    } else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Width
                        sharingInner.css( 'width', sharingWidth +'px' );

                        // Set Position
                        sharingInner.css( 'right', - ( sharingMargin + sharingWidth ) +'px' );
                    } else if ( 'top' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Margins
                        sharingInner.find( 'a' ).css({
                            'margin-right' : '0',
                            'margin-top' : sharingMargin +'px'
                        });

                        // Set Position
                        sharingInner.css({
                            'top' : -sharingMargin +'px',
                            'left' : '50%',
                            '-webkit-transform' : 'translate(-50%, -100%)',
                            'transform' : 'translate(-50%, -100%)'
                        });
                    } else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Width
                        sharingInner.css( 'width', sharingWidth +'px' );

                        // Set Position
                        sharingInner.css({
                            'left' : sharingMargin +'px',
                            // 'bottom' : - ( sharingInner.outerHeight() + sharingTrigger.outerHeight() ) +'px',
                        });
                    } else if ( 'bottom' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Margins
                        sharingInner.find( 'a' ).css({
                            'margin-right' : '0',
                            'margin-bottom' : sharingMargin +'px'
                        });

                        // Set Position
                        sharingInner.css({
                            'bottom' : -sharingMargin +'px',
                            'left' : '50%',
                            '-webkit-transform' : 'translate(-50%, 100%)',
                            'transform' : 'translate(-50%, 100%)'
                        });
                    }

                    if ( 'click' === sharingTrigger.attr( 'data-action' ) ) {
                        sharingTrigger.on( 'click', function() {
                            var sharingInner = $(this).next();

                            if ( 'hidden' === sharingInner.css( 'visibility' ) ) {
                                sharingInner.css( 'visibility', 'visible' );
                                sharingInner.find( 'a' ).css({
                                    'opacity' : '1',
                                    'top' : '0'
                                });

                                setTimeout( function() {
                                    sharingInner.find( 'a' ).addClass( 'crt-no-transition-delay' );
                                }, sharingInner.find( 'a' ).length * 100 );
                            } else {
                                sharingInner.find( 'a' ).removeClass( 'crt-no-transition-delay' );

                                sharingInner.find( 'a' ).css({
                                    'opacity' : '0',
                                    'top' : '-5px'
                                });
                                setTimeout( function() {
                                    sharingInner.css( 'visibility', 'hidden' );
                                }, sharingInner.find( 'a' ).length * 100 );
                            }
                        });
                    } else {
                        sharingTrigger.on( 'mouseenter', function() {
                            var sharingInner = $(this).next();

                            sharingInner.css( 'visibility', 'visible' );
                            sharingInner.find( 'a' ).css({
                                'opacity' : '1',
                                'top' : '0',
                            });

                            setTimeout( function() {
                                sharingInner.find( 'a' ).addClass( 'crt-no-transition-delay' );
                            }, sharingInner.find( 'a' ).length * 100 );
                        });
                        $scope.find( '.crt-insta-feed-item-sharing' ).on( 'mouseleave', function() {
                            var sharingInner = $(this).find( '.crt-post-sharing-inner' );

                            sharingInner.find( 'a' ).removeClass( 'crt-no-transition-delay' );

                            sharingInner.find( 'a' ).css({
                                'opacity' : '0',
                                'top' : '-5px'
                            });
                            setTimeout( function() {
                                sharingInner.css( 'visibility', 'hidden' );
                            }, sharingInner.find( 'a' ).length * 100 );
                        });
                    }
                }
            }

            // Remove if not necessary - GOGA
            $scope.find('.elementor-widget-wrap').removeClass('e-swiper-container');

            function instaFeedCarousel() {
                if ( $scope.hasClass('crt-insta-feed-layout-carousel') ) {
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

                    $scope.find('.crt-instagram-feed').css('flexWrap', 'nowrap');

                    var sliderSettings = settings.carousel;

                    $scope.find('.crt-instagram-feed-cont').addClass('swiper');
                    $scope.find('.crt-instagram-feed').addClass('swiper-wrapper');
                    $scope.find('.crt-insta-feed-content-wrap').addClass('swiper-slide');
                    $scope.find('.crt-instagram-feed-cont').css('overflow', 'hidden');
                    // $scope.find('.elementor-container').css('margin', '0');
                    var swiperSlider = $scope.find('.crt-instagram-feed-cont');

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
                            clickable: 'bullets' === sliderSettings.crt_cs_pagination_type ? true : false,
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

            function lightboxPopup( settings ) {
                if ( -1 === $scope.find( '.crt-insta-feed-item-lightbox' ).length ) {
                    return;
                }

                var lightbox = $scope.find( '.crt-insta-feed-item-lightbox' ),
                    lightboxOverlay = lightbox.find( '.crt-insta-feed-lightbox-overlay' );

                // Set Src Attributes
                lightbox.each(function() {
                    var source = $(this).find('.inner-block > span').attr( 'data-src' ),
                        instaFeedItem = $(this).closest( '.crt-insta-feed-content-wrap' );

                    instaFeedItem.find('img').attr('alt', DOMPurify.sanitize(instaFeedItem.find('img').attr('alt')) );

                    instaFeedItem.find( '.crt-insta-feed-image-wrap' ).attr( 'data-src', source );

                    var dataSource = instaFeedItem.find( '.crt-insta-feed-image-wrap' ).attr( 'data-src' );
                });

                // Init Lightbox
                instaFeed.lightGallery( settings.lightbox );

                // Fix LightGallery Thumbnails
                instaFeed.on('onAfterOpen.lg', function() {
                    if ( $('.lg-outer').find('.lg-thumb-item').length ) {
                        $('.lg-outer').find('.lg-thumb-item').each(function() {
                            var imgSrc = $(this).find('img').attr('src'),
                                newImgSrc = imgSrc,
                                extIndex = imgSrc.lastIndexOf('.'),
                                imgExt = imgSrc.slice(extIndex),
                                cropIndex = imgSrc.lastIndexOf('-'),
                                cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;

                            if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
                                cropSize = '';
                            }

                            if ( cropSize !== '' ) {
                                if ( false !== cropSize ) {
                                    newImgSrc = imgSrc.replace(cropSize, '-150x150');
                                } else {
                                    newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
                                }
                            }

                            // Change SRC
                            $(this).find('img').attr('src', newImgSrc);

                            if ( false == cropSize ) {
                                $(this).find('img').attr('src', imgSrc);
                            }
                        });
                    }
                });

                // Show/Hide Controls
                $scope.find( '.crt-insta-feed' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
                    var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
                        lightboxDownload = $( '#lg-download' ).attr( 'href' );

                    if ( $( '#lg-download' ).length ) {
                        if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
                            lightboxControls.addClass( 'crt-hidden-element' );
                        } else {
                            lightboxControls.removeClass( 'crt-hidden-element' );
                        }
                    }

                    // Autoplay Button
                    if ( '' === settings.lightbox.autoplay ) {
                        $( '.lg-autoplay-button' ).css({
                            'width' : '0',
                            'height' : '0',
                            'overflow' : 'hidden'
                        });
                    }
                });

                // Overlay
                if ( lightboxOverlay.length ) {
                    $scope.find( '.crt-insta-feed-media-hover-bg' ).after( lightboxOverlay.remove() );

                    $scope.find( '.crt-insta-feed-lightbox-overlay' ).on( 'click', function() {
                        if ( ! CrtElements.editorCheck() ) {
                            $(this).closest( '.crt-insta-feed-content-wrap' ).find( '.crt-insta-feed-image-wrap' ).trigger( 'click' );
                        } else {
                            alert( 'Lightbox is Disabled in the Editor! Please Preview this Page to see it in action.' );
                        }
                    });
                } else {
                    lightbox.find( '.inner-block > span' ).on( 'click', function() {
                        if ( ! CrtElements.editorCheck() ) {
                            var imageWrap = $(this).closest( '.crt-insta-feed-content-wrap' ).find( '.crt-insta-feed-image-wrap' );
                            imageWrap.trigger( 'click' );
                        } else {
                            alert( 'Lightbox is Disabled in the Editor! Please Preview this Page to see it in action.' );
                        }
                    });
                }
            }

            // Media Hover Link
            function mediaHoverLink() {
                if ( 'yes' === instaFeed.find( '.crt-insta-feed-media-wrap' ).attr( 'data-overlay-link' ) && ! CrtElements.editorCheck() ) {
                    instaFeed.find( '.crt-insta-feed-media-wrap' ).css('cursor', 'pointer');

                    instaFeed.find( '.crt-insta-feed-media-wrap' ).on( 'click', function( event ) {

                        var targetClass = event.target.className;

                        if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'crt-cv-inner' ) ||
                            -1 !== targetClass.indexOf( 'crt-insta-feed-media-hover' ) ) {
                            event.preventDefault();

                            var itemUrl = $(this).find( '.crt-insta-feed-media-hover-bg' ).attr( 'data-url' ),
                                itemUrl = itemUrl.replace('#new_tab', '');

                            if (itemUrl) {
                                try {
                                    // Create a URL object to validate the URL
                                    var url = new URL(itemUrl);

                                    // Define a list of allowed protocols
                                    var allowedProtocols = ['http:', 'https:'];

                                    // Check if the URL's protocol is allowed
                                    if (allowedProtocols.includes(url.protocol)) {
                                        // Safe to use the URL
                                        var safeUrl = url.href;

                                        if ('_blank' === iGrid.find('.crt-grid-item-title a').attr('target')) {
                                            window.open(safeUrl, '_blank').focus();
                                        } else {
                                            window.location.href = safeUrl;
                                        }
                                    } else {
                                        console.error('Invalid URL scheme:', url.protocol);
                                    }
                                } catch (e) {
                                    console.error('Invalid URL:', itemUrl);
                                }
                            }
                        }
                    });
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