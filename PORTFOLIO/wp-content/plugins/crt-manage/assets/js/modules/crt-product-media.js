(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-product-media.default',function($scope) {

            // Fix Main Slider Distortion
            $(document).ready(function($) {
                $(window).trigger('resize');
                setTimeout(function() {
                    $(window).trigger('resize');
                    $scope.find('.crt-product-media-wrap').removeClass('crt-zero-opacity');
                }, 1000);
            });

            var sliderIcons = $scope.find('.crt-gallery-slider-arrows-wrap');
            sliderIcons.remove();

            if ( $scope.find('.woocommerce-product-gallery__trigger').length ) {
                $scope.find('.woocommerce-product-gallery__trigger').remove();
            }

            $scope.find('.flex-viewport').append(sliderIcons);

            $scope.find('.crt-gallery-slider-arrow').on('click', function() {
                if ($(this).hasClass('crt-gallery-slider-prev-arrow')) {
                    $scope.find('a.flex-prev').trigger('click');
                } else if ($(this).hasClass('crt-gallery-slider-next-arrow')) {
                    $scope.find('a.flex-next').trigger('click');
                }
            });

            // Lightbox
            var lightboxSettings = $( '.crt-product-media-wrap' ).attr( 'data-lightbox' );

            if ( typeof lightboxSettings !== typeof undefined && lightboxSettings !== false ) {
                console.log('loaded');
                $scope.find('.woocommerce-product-gallery__image').each(function() {
                    $(this).attr('data-lightbox', lightboxSettings);
                    $(this).attr('data-src', $(this).find('a').attr('href'));
                });


                $scope.find('.woocommerce-product-gallery__image').on('click', function(e) {
                    e.stopPropagation();
                });

                $scope.find('.crt-product-media-lightbox').on('click', function() {
                    $scope.find('.woocommerce-product-gallery__image').trigger('click');
                });

                var MediaWrap = $scope.find( '.woocommerce-product-gallery__wrapper' );
                lightboxSettings = JSON.parse( lightboxSettings );

                // Init Lightbox
                MediaWrap.lightGallery( lightboxSettings );

                // Show/Hide Controls
                MediaWrap.on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
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
                    if ( '' === lightboxSettings.autoplay ) {
                        $( '.lg-autoplay-button' ).css({
                            'width' : '0',
                            'height' : '0',
                            'overflow' : 'hidden'
                        });
                    }
                });
            }

            if ( $scope.hasClass('crt-product-media-thumbs-slider') && $scope.hasClass('crt-product-media-thumbs-vertical') ) {

                var thumbsToShow = $scope.find('.crt-product-media-wrap').data('slidestoshow');
                var thumbsToScroll = +$scope.find('.crt-product-media-wrap').data('slidestoscroll');

                $scope.find('.flex-control-nav').css('height', ((100/thumbsToShow) * $scope.find('.flex-control-nav li').length) + '%');

                $scope.find('.flex-control-nav').wrap('<div class="crt-fcn-wrap"></div>');

                var thumbIcon1 = $scope.find('.crt-thumbnail-slider-prev-arrow');
                var thumbIcon2 = $scope.find('.crt-thumbnail-slider-next-arrow');

                thumbIcon1.remove();
                thumbIcon2.remove();

                if ( $scope.find('.crt-product-media-wrap').data('slidestoshow') < $scope.find('.flex-control-nav li').length ) {
                    $scope.find('.crt-fcn-wrap').prepend(thumbIcon1);
                    $scope.find('.crt-fcn-wrap').append(thumbIcon2);
                }

                var posy = 0;
                var slideCount = 0;

                $scope.find('.crt-thumbnail-slider-next-arrow').on('click', function() {
                    // var currTrans =  $scope.find('.flex-control-nav').css('transform') != 'none' ? $scope.find('.flex-control-nav').css('transform').split(/[()]/)[1] : 0;
                    // posx = currTrans ? currTrans.split(',')[4] : 0;
                    if ( (slideCount + thumbsToScroll) < $scope.find('.flex-control-nav li').length - 1 ) {
                        posy++;
                        slideCount = slideCount + thumbsToScroll;
                        $scope.find('.flex-control-nav').css('transform', 'translateY('+ (parseInt(-posy) * (parseInt($scope.find('.flex-control-nav li:last-child').css('height').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li').css('margin-bottom'))) * thumbsToScroll) +'px)');
                        if ( posy >= 1 ) {
                            $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', false);
                        } else {
                            $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', true);
                        }
                    } else {
                        posy = 0;
                        slideCount = 0;
                        $scope.find('.flex-control-nav').css('transform', `translateY(0)`);
                        $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', true);
                    }
                });

                $scope.find('.crt-thumbnail-slider-prev-arrow').on('click', function() {
                    if ( posy >= 1 ) {
                        posy--;
                        if ( posy == 0 ) {
                            $(this).attr('disabled', true);
                        }
                        slideCount = slideCount - thumbsToScroll;
                        $scope.find('.flex-control-nav').css('transform', 'translateY('+ parseInt(-posy) * (parseInt($scope.find('.flex-control-nav li').css('height').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li:last-child').css('margin-top'))) * thumbsToScroll +'px)');
                        if ( slideCount < $scope.find('.flex-control-nav li').length - 1 ) {
                            $scope.find('.crt-thumbnail-slider-next-arrow').attr('disabled', false);
                        } else {
                            $scope.find('.crt-thumbnail-slider-next-arrow').attr('disabled', true);
                        }
                    } else {
                        // slideCount = $scope.find('.flex-control-nav li').length - 1;
                        // $scope.find('.flex-control-nav').css('transform', `translateX(0)`);
                        $(this).attr('disabled', true);
                    }
                });
            }

            if ( $scope.hasClass('crt-product-media-thumbs-slider') && $scope.find('.crt-product-media-wrap').hasClass('crt-product-media-thumbs-horizontal') ) {

                var thumbsToShow = $scope.find('.crt-product-media-wrap').data('slidestoshow');
                var thumbsToScroll = +$scope.find('.crt-product-media-wrap').data('slidestoscroll');

                $scope.find('.flex-control-nav').css('width', ((100/thumbsToShow) * $scope.find('.flex-control-nav li').length) +'%');

                $scope.find('.flex-control-nav').wrap('<div class="crt-fcn-wrap"></div>');

                var thumbIcon1 = $scope.find('.crt-thumbnail-slider-prev-arrow');
                var thumbIcon2 = $scope.find('.crt-thumbnail-slider-next-arrow');

                thumbIcon1.remove();
                thumbIcon2.remove();

                if ( $scope.find('.crt-product-media-wrap').data('slidestoshow') < $scope.find('.flex-control-nav li').length ) {
                    $scope.find('.crt-fcn-wrap').prepend(thumbIcon1);
                    $scope.find('.crt-fcn-wrap').append(thumbIcon2);
                    $scope.find('.crt-thumbnail-slider-arrow').removeClass('crt-tsa-hidden');
                }

                var posx = 0;
                var slideCount = 0;
                $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', true);

                $scope.find('.crt-thumbnail-slider-next-arrow').on('click', function() {
                    // var currTrans =  $scope.find('.flex-control-nav').css('transform') != 'none' ? $scope.find('.flex-control-nav').css('transform').split(/[()]/)[1] : 0;
                    // posx = currTrans ? currTrans.split(',')[4] : 0;
                    if ( (slideCount + thumbsToScroll) < $scope.find('.flex-control-nav li').length - 1 ) {
                        posx++;
                        console.log('next_' + posx);
                        slideCount = slideCount + thumbsToScroll;
                        $scope.find('.flex-control-nav').css('transform', 'translateX('+ (parseInt(-posx) * (parseInt($scope.find('.flex-control-nav li:last-child').css('width').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li').css('margin-right'))) * thumbsToScroll) +'px)');
                        if ( posx >= 1 ) {
                            $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', false);
                        } else {
                            $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', true);
                        }
                    } else {
                        posx = 0;
                        console.log('next*' + posx);
                        slideCount = 0;
                        $scope.find('.flex-control-nav').css('transform', `translateX(0)`);
                        $scope.find('.crt-thumbnail-slider-prev-arrow').attr('disabled', true);
                    }
                });

                $scope.find('.crt-thumbnail-slider-prev-arrow').on('click', function() {
                    if ( posx >= 1 ) {
                        posx--;
                        console.log(posx);
                        if ( posx == 0 ) {
                            $(this).attr('disabled', true);
                        }
                        slideCount = slideCount - thumbsToScroll;
                        $scope.find('.flex-control-nav').css('transform', 'translateX('+ parseInt(-posx) * (parseInt($scope.find('.flex-control-nav li').css('width').slice(0, -2)) + parseInt($scope.find('.flex-control-nav li').css('margin-right'))) * thumbsToScroll +'px)');
                        if ( slideCount < $scope.find('.flex-control-nav li').length - 1 ) {
                            $scope.find('.crt-thumbnail-slider-next-arrow').attr('disabled', false);
                        } else {
                            $scope.find('.crt-thumbnail-slider-next-arrow').attr('disabled', true);
                        }
                    } else {
                        // slideCount = $scope.find('.flex-control-nav li').length - 1;
                        // $scope.find('.flex-control-nav').css('transform', `translateX(0)`);
                        $(this).attr('disabled', true);
                    }
                });

            }

        });
    });
})(jQuery);