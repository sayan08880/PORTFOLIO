(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-posts-timeline.default',function($scope) {

            var iScrollTarget = $scope.find( '.crt-timeline-centered' ).length > 0 ? $scope.find( '.crt-timeline-centered' ) : '',
                element = $scope.find('.crt-timeline-centered').length > 0 ? $scope.find('.crt-timeline-centered') : '',
                pagination = $scope.find( '.crt-grid-pagination' ).length > 0 ? $scope.find( '.crt-grid-pagination' ) : '',
                middleLine = $scope.find('.crt-middle-line').length > 0 ? $scope.find('.crt-middle-line') : '',
                timelineFill = $scope.find(".crt-timeline-fill").length > 0 ? $scope.find(".crt-timeline-fill") : '',
                lastIcon = $scope.find('.crt-main-line-icon.crt-icon:last').length > 0 ? $scope.find('.crt-main-line-icon.crt-icon:last') : '',
                firstIcon = $scope.find('.crt-main-line-icon.crt-icon').length > 0 ? $scope.find('.crt-main-line-icon.crt-icon').first() : '',
                scopeClass = '.elementor-element-'+ $scope.attr( 'data-id' ),
                aosOffset = $scope.find('.crt-story-info-vertical').attr('data-animation-offset') ? +$scope.find('.crt-story-info-vertical').attr('data-animation-offset') : '',
                aosDuration = $scope.find('.crt-story-info-vertical').attr('data-animation-duration') ? +$scope.find('.crt-story-info-vertical').attr('data-animation-duration') : '';


            if ( $scope.find('.crt-timeline-centered').length > 0 ) {

                $(window).resize(function() {
                    removeLeftAlignedClass();
                });

                $(window).smartresize(function() {
                    removeLeftAlignedClass();
                });

                setTimeout(function() {
                    removeLeftAlignedClass();
                    $(window).trigger('resize');
                }, 500);

                adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);

                setTimeout(function() {
                    adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                    $(window).trigger('resize');
                }, 500);

                $(window).smartresize(function() {
                    adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                });

                $(window).resize(function() {
                    adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                });

                if ( 'load-more' !== iScrollTarget.attr('data-pagination') ) {
                    $scope.find('.crt-grid-pagination').css('visibility', 'hidden');
                }

                AOS.init({
                    offset: parseInt(aosOffset),
                    duration: aosDuration,
                    once: true,
                });

                postsTimelineFill(lastIcon, firstIcon);

                $(window).on('scroll',  function() {
                    postsTimelineFill(lastIcon, firstIcon);
                });

                // init Infinite Scroll
                if ( !$scope.find('.elementor-repeater-items').length && !CrtElements.editorCheck() && ('load-more' === $scope.find('.crt-timeline-centered').data('pagination') || 'infinite-scroll' === $scope.find('.crt-timeline-centered').data('pagination')) ) {
                    var threshold = iScrollTarget !== undefined && 'load-more' === iScrollTarget.attr('data-pagination') ? false : 10;
                    // var navClass = scopeClass +' .crt-load-more-btn';

                    iScrollTarget.infiniteScroll({
                        path: scopeClass +' .crt-grid-pagination a',
                        hideNav: false,
                        append:  scopeClass +'.crt-timeline-entry',
                        history: false,
                        scrollThreshold: threshold,
                        status: scopeClass + ' .page-load-status',
                    });
                    // Request
                    iScrollTarget.on( 'request.infiniteScroll', function( event, path ) {
                        $scope.find( '.crt-load-more-btn' ).hide();
                        $scope.find( '.crt-pagination-loading' ).css( 'display', 'inline-block' );
                    });

                    var pagesLoaded = 0;

                    iScrollTarget.on( 'load.infiniteScroll', function( event, response ) {
                        pagesLoaded++;

                        // get posts from response
                        var items = $( response ).find(scopeClass).find( '.crt-timeline-entry' );
                        iScrollTarget.infiniteScroll( 'appendItems', items );

                        if ( !$scope.find('.crt-one-sided-timeline').length && !$scope.find('.crt-one-sided-timeline-left').length ) {
                            $scope.find('.crt-timeline-entry').each(function(index, value){
                                $(this).removeClass('crt-right-aligned crt-left-aligned');
                                if ( 0 == index % 2 ) {
                                    $(this).addClass('crt-left-aligned');
                                    $(this).find('.crt-story-info-vertical').attr('data-aos', $(this).find('.crt-story-info-vertical').attr('data-aos-left'));
                                } else {
                                    $(this).addClass('crt-right-aligned');
                                    $(this).find('.crt-story-info-vertical').attr('data-aos', $(this).find('.crt-story-info-vertical').attr('data-aos-right'));
                                }
                            });
                        }

                        AOS.init({
                            offset: parseInt(aosOffset),
                            duration: aosDuration,
                            once: true,
                        });

                        $(window).scroll();

                        $scope.find( '.crt-pagination-loading' ).hide();
                        // $scope.find( '.crt-load-more-btn' ).fadeIn();
                        if ( iScrollTarget.data('max-pages') - 1 !== pagesLoaded ) { // $pagination_max_pages
                            if ( 'load-more' === iScrollTarget.attr('data-pagination') ) {
                                $scope.find( '.crt-load-more-btn' ).fadeIn();
                            }
                        } else {
                            $scope.find( '.crt-pagination-finish' ).fadeIn( 1000 );
                            pagination.delay( 2000 ).fadeOut( 1000 );
                        }

                        middleLine = $scope.find('.crt-middle-line');
                        timelineFill = $scope.find(".crt-timeline-fill");
                        lastIcon = $scope.find('.crt-main-line-icon.crt-icon:last');
                        firstIcon = $scope.find('.crt-main-line-icon.crt-icon').first();
                        element = $scope.find('.crt-timeline-centered');

                        adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element);
                        $(window).trigger('resize');
                        postsTimelineFill(lastIcon, firstIcon);
                    });

                    if ( !CrtElements.editorCheck() ) {
                        $scope.find( '.crt-load-more-btn' ).on( 'click', function() {
                            iScrollTarget.infiniteScroll( 'loadNextPage' );
                            return false;
                        });

                        if ( 'infinite-scroll' == iScrollTarget.attr('data-pagination') ) {
                            iScrollTarget.infiniteScroll('loadNextPage');
                        }
                    }
                }
            }

            if ( $scope.find('.swiper-wrapper').length ) {

                var swiperLoader = function swiperLoader(swiperElement, swiperConfig) {
                    // if ('undefined' === typeof Swiper) {
                    // 	var asyncSwiper = elementorFrontend.utils.swiper;
                    // 	return new asyncSwiper(swiperElement, swiperConfig).then( function (newSwiperInstance) {
                    // 		return newSwiperInstance;
                    // 	});
                    //  } else {
                    // 	return swiperPromise(swiperElement, swiperConfig);
                    // }

                    // Check if swiperPromise is necessary
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

                var horizontal = $scope.find('.crt-horizontal-bottom').length ? '.crt-horizontal-bottom' : '.crt-horizontal';
                var swiperSlider = $scope.find(horizontal +".swiper");

                var slidestoshow = swiperSlider.data("slidestoshow");

                swiperLoader(swiperSlider, {
                    spaceBetween: +swiperSlider.data('swiper-space-between'),
                    loop: swiperSlider.data('loop') === 'yes' ? true : false,
                    autoplay: swiperSlider.data("autoplay") !== 'yes' ? false : {
                        delay: +swiperSlider.attr('data-swiper-delay'),
                        disableOnInteraction: false,
                        pauseOnMouseEnter: swiperSlider.data('swiper-poh') === 'yes' ? true : false,
                    },
                    on: {
                        init: function () {
                            if ( $scope.find('.crt-timeline-outer-container').length > 0 ) {
                                $scope.find('.crt-timeline-outer-container').css('opacity', 1);
                            }
                        },
                    },
                    speed: +swiperSlider.attr('data-swiper-speed'),
                    slidesPerView: swiperSlider.data("slidestoshow"),
                    direction: 'horizontal',
                    pagination: {
                        el: '.crt-swiper-pagination',
                        type: 'progressbar',
                    },
                    navigation: {
                        nextEl: '.crt-button-next',
                        prevEl: '.crt-button-prev',
                    },
                    // Responsive breakpoints
                    breakpoints: {
                        // when window width is >= 320px
                        320: {
                            slidesPerView: 1,
                        },
                        // when window width is >= 480px
                        480: {
                            slidesPerView: 2,
                        },
                        // when window width is >= 640px
                        769: { // 640
                            slidesPerView: slidestoshow,
                        }
                    },
                });

                //   swiperSlider.data('pause-on-hover') === 'yes' && swiperSlider.hover(function() {
                // 	  (this).swiper.autoplay.stop();
                //   }, function() {
                // 	  (this).swiper.autoplay.start();
                //   });

            } else {
                $(document).ready(function() {
                    // Handler when all assets (including images) are loaded
                    if ( $scope.find('.crt-timeline-outer-container').length ) {
                        $scope.find('.crt-timeline-outer-container').css('opacity', 1);
                    }
                });
            }

            function removeLeftAlignedClass() {
                if ( $scope.find('.crt-centered').length ) {
                    if ( window.innerWidth <= 767 ) {
                        $scope.find('.crt-wrapper .crt-timeline-centered').removeClass('crt-both-sided-timeline').addClass('crt-one-sided-timeline').addClass('crt-remove-one-sided-later');
                        $scope.find('.crt-wrapper .crt-left-aligned').removeClass('crt-left-aligned').addClass('crt-right-aligned').addClass('crt-remove-right-aligned-later');
                    } else {
                        $scope.find('.crt-wrapper .crt-timeline-centered.crt-remove-one-sided-later').removeClass('crt-one-sided-timeline').addClass('crt-both-sided-timeline').removeClass('crt-remove-one-sided-later');
                        $scope.find('.crt-wrapper .crt-remove-right-aligned-later').removeClass('crt-right-aligned').addClass('crt-left-aligned').removeClass('crt-remove-right-aligned-later');
                    }
                }
            }

            function postsTimelineFill(lastIcon, firstIcon) {
                if ( !$scope.find('.crt-timeline-fill').length ) {
                    return;
                }

                if ( $scope.find('.crt-timeline-entry:eq(0)').prev('.crt-year-wrap').length > 0 ) {
                    firstIcon = $scope.find('.crt-year-label').eq(0);
                }

                if ( timelineFill.length ) {
                    var fillHeight = timelineFill.css('height').slice(0, -2),
                        docScrollTop = document.documentElement.scrollTop,
                        clientHeight = document.documentElement.clientHeight/2;

                    if ( !((docScrollTop + clientHeight - (firstIcon.offset().top)) > lastIcon.offset().top - firstIcon.offset().top + parseInt(lastIcon.css('height').slice(0, -2))) ) {
                        timelineFill.css('height', (docScrollTop  + clientHeight - (firstIcon.offset().top)) + 'px');
                    }

                    $scope.find('.crt-main-line-icon.crt-icon').each(function () {
                        if ( $(this).offset().top < parseInt( firstIcon.offset().top + parseInt(fillHeight) ) ) {
                            $(this).addClass('crt-change-border-color');
                        } else {
                            $(this).removeClass('crt-change-border-color');
                        }
                    });
                }
            }

            function adjustMiddleLineHeight(middleLine, timelineFill, lastIcon, firstIcon, element) {
                element = $scope.find('.crt-timeline-centered');
                if ( !$scope.find('.crt-both-sided-timeline').length && !$scope.find('.crt-one-sided-timeline').length && !$scope.find('.crt-one-sided-timeline-left').length ) {
                    return;
                }

                if ( $scope.find('.crt-timeline-entry:eq(0)').prev('.crt-year-wrap').length > 0 ) {
                    firstIcon = $scope.find('.crt-year-label').eq(0);
                }

                var firstIconOffset = firstIcon.offset().top;
                var lastIconOffset = lastIcon.offset().top;
                var middleLineTop = (firstIconOffset - element.offset().top) + 'px';
                // var middleLineHeight = (lastIconOffset - (lastIcon.css('height').slice(0, -2)/2 + (firstIconOffset - firstIcon.css('height').slice(0, -2)))) + 'px';
                var middleLineHeight = lastIconOffset - firstIconOffset + parseInt(lastIcon.css('height').slice(0, -2));
                var middleLineMaxHeight = firstIconOffset - lastIconOffset + 'px !important';

                middleLine.css('top', middleLineTop);
                middleLine.css('height', middleLineHeight);
                // middleLine.css('maxHeight', middleLineMaxHeight);
                timelineFill !== '' ? timelineFill.css('top', middleLineTop) : '';
            }
        });

        var CrtElements = {
            editorCheck: function() {
                return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
            },
        }
    });
})(jQuery);