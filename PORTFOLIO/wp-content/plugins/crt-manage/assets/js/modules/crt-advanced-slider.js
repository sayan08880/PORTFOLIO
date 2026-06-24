(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-advanced-slider.default',function($scope) {

            var $advancedSlider = $scope.find( '.crt-advanced-slider' ),
                sliderData = $advancedSlider.data('slick'),
                videoBtnSize = $advancedSlider.data('video-btn-size'),
                videoPlays = 'false';

            // customPaging: function(slider, i) {
            // 	return '<span class="crt-slider-dot" style="background-image:url('+ $(slider.$slides[i]).find('.crt-slider-item-bg').css('background-image').replace('url(','').replace(')','').replace(/\"/gi, "") +')"></span>';
            // },

            // Slider Columns
            var sliderClass = $scope.attr('class'),
                sliderColumnsDesktop = sliderClass.match(/crt-adv-slider-columns-\d/) ? +sliderClass.match(/crt-adv-slider-columns-\d/).join().slice(-1) : 2,
                sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? +sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
                sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? +sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
                sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? +sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
                sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? +sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
                sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? +sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
                sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? +sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
                sliderSlidesToScroll = +(sliderClass.match(/crt-adv-slides-to-scroll-\d/).join().slice(-1)),
                dataSlideEffect = $advancedSlider.attr('data-slide-effect');

            $advancedSlider.slick({
                appendArrows :  $scope.find('.crt-slider-controls'),
                appendDots :  $scope.find('.crt-slider-dots'),
                customPaging : function (slider, i) {
                    var slideNumber = (i + 1),
                        totalSlides = slider.slideCount;
                    return '<span class="crt-slider-dot"></span>';
                },
                slidesToShow: sliderColumnsDesktop,
                responsive: [
                    {
                        breakpoint: 10000,
                        settings: {
                            slidesToShow: sliderColumnsWideScreen,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsWideScreen && 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 2399,
                        settings: {
                            slidesToShow: sliderColumnsDesktop,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsDesktop && 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 1221,
                        settings: {
                            slidesToShow: sliderColumnsLaptop,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsLaptop && 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 1200,
                        settings: {
                            slidesToShow: sliderColumnsTabletExtra,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsTabletExtra && 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 1024,
                        settings: {
                            slidesToShow: sliderColumnsTablet,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsTablet && 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 880,
                        settings: {
                            slidesToShow: sliderColumnsMobileExtra,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsMobileExtra && 'fade' === dataSlideEffect) ? true : false
                        }
                    },
                    {
                        breakpoint: 768,
                        settings: {
                            slidesToShow: sliderColumnsMobile,
                            slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll,
                            fade: (1 == sliderColumnsMobile && 'fade' === dataSlideEffect) ? true : false
                        }
                    }
                ],
            });

            $(document).ready(function() {

                $scope.find('.slick-current').addClass('crt-slick-visible');

                var maxHeight = -1;
                // $scope.find('.slick-slide').each(function() {
                // if ($(this).height() > maxHeight) {
                // 	maxHeight = $(this).height();
                // }
                // });
                // $scope.find('.slick-slide').each(function() {
                // if ($(this).height() < maxHeight) {
                // 	console.log(Math.ceil((maxHeight-$(this).height())/2) + 'px 0');
                // 	$(this).css('margin', Math.ceil((maxHeight-$(this).height())/2) + 'px 0');
                // 	// $(this).css('transform', 'translateY(-50%)');
                // }
                // });

                // GOGA - needs condition check if there are any images
                if ( $scope.find('.crt-slider-img').length !== 0 ) {
                    $scope.find('.crt-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());

                    $scope.find('.crt-slider-arrow').on('click', function() {
                        $scope.find('.crt-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
                    });

                    $(window).smartresize(function() {
                        $scope.find('.crt-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
                    });
                }
            });

            function sliderVideoSize(){

                // var sliderWidth = $advancedSlider.find('.crt-slider-item').outerWidth(),
                // 	sliderHeight = $advancedSlider.find('.crt-slider-item').outerHeight(),
                // 	sliderRatio = sliderWidth / sliderHeight,
                // 	iframeRatio = (16/9),
                // 	iframeHeight,
                // 	iframeWidth,
                // 	iframeTopDistance = 0,
                // 	iframeLeftDistance = 0;

                // if ( sliderRatio > iframeRatio ) {
                // 	iframeWidth = sliderWidth;
                // 	iframeHeight = iframeWidth / iframeRatio;
                // 	iframeTopDistance = '-'+ ( iframeHeight - sliderHeight ) / 2 +'px';
                // } else {
                // 	iframeHeight = sliderHeight;
                // 	iframeWidth = iframeHeight * iframeRatio;
                // 	iframeLeftDistance = '-'+ ( iframeWidth - sliderWidth ) / 2 +'px';
                // }

                // $advancedSlider.find('iframe').css({
                // 	'display': 'block',
                // 	'width': iframeWidth +'px',
                // 	'height': iframeHeight +'px',
                // 	'max-width': 'none',
                // 	'position': 'absolute',
                // 	'left': iframeLeftDistance +'',
                // 	'top': iframeTopDistance +'',
                // 	'text-align': 'inherit',
                // 	'line-height':'0px',
                // 	'border-width': '0px',
                // 	'margin': '0px',
                // 	'padding': '0px',
                // });

                $advancedSlider.find('iframe').attr('width', $scope.find('.crt-slider-item').width());
                $advancedSlider.find('iframe').attr('height', $scope.find('.crt-slider-item').height());

                var viewportWidth = $(window).outerWidth();

                var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
                var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
                var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
                var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
                var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
                var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

                var activeBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

                [...$scope[0].classList].forEach(className => {
                    if (className.startsWith('crt-slider-video-icon-size-')) {
                        $scope[0].classList.remove(className);
                    }
                });

                // Mobile
                if ( MobileResp >= viewportWidth && activeBreakpoints.mobile != null ) {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.mobile);
                    // Mobile Extra
                } else if ( MobileExtraResp >= viewportWidth && activeBreakpoints.mobile_extra != null ) {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.mobile_extra);
                    // Tablet
                } else if ( TabletResp >= viewportWidth && activeBreakpoints.tablet != null ) {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.tablet);
                    // Tablet Extra
                } else if ( TabletExtraResp >= viewportWidth && activeBreakpoints.tablet_extra != null ) {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.tablet_extra);
                    // Laptop
                } else if ( LaptopResp >= viewportWidth && activeBreakpoints.laptop != null ) {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.laptop);
                    // Desktop
                } else if ( wideScreenResp > viewportWidth ) {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.desktop);
                }  else {
                    $scope.addClass('crt-slider-video-icon-size-'+videoBtnSize.widescreen);
                }
                // crt-slider-video-icon-size-
            }

            $(window).on('load resize', function(){
                sliderVideoSize();
            });

            $(document).ready(function () {
                // Handler when all assets (including images) are loaded
                if ( $scope.find('.crt-advanced-slider').length ) {
                    $scope.find('.crt-advanced-slider').css('opacity', 1);
                    autoplayVideo();
                }
            });

            function autoplayVideo() {
                $advancedSlider.find('.slick-current').each(function() {

                    var videoSrc = sanitizeURL($(this).find('.crt-slider-item').attr('data-video-src')) || '',
                        videoAutoplay = $(this).find('.crt-slider-item').attr('data-video-autoplay');

                    if ( $(this).find( '.crt-slider-video' ).length !== 1 && videoAutoplay === 'yes' ) {
                        if ( videoSrc.includes('vimeo') || videoSrc.includes('youtube') ) {
                            if ( sliderColumnsDesktop == 1 ) {
                                // $(this).find('.crt-cv-inner').prepend('<div class="crt-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');
                                $(this).find('.crt-cv-inner').prepend('<div class="crt-slider-video"><iframe src="'+ videoSrc +'"  frameborder="0" allow="autoplay" allowfullscreen></iframe></div>');
                            } else {
                                $(this).find('.crt-cv-container').prepend('<div class="crt-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe></div>');
                            }
                            sliderVideoSize();
                        } else {
                            var videoMute = CrtElements.sanitizeDataAttr($(this).find('.crt-slider-item').attr('data-video-mute'));
                            var videoControls = CrtElements.sanitizeDataAttr($(this).find('.crt-slider-item').attr('data-video-controls'));
                            var videoLoop = CrtElements.sanitizeDataAttr($(this).find('.crt-slider-item').attr('data-video-loop'));

                            $(this).find('.crt-cv-inner').prepend('<div class="crt-slider-video crt-custom-video"><video autoplay '+ videoLoop + ' ' + videoMute + ' ' + videoControls + ' ' +  'src="'+ videoSrc +'" width="100%" height="100%"></video></div>');

                            $advancedSlider.find('video').attr('width', $scope.find('.crt-slider-item').width());
                            $advancedSlider.find('video').attr('height', $scope.find('.crt-slider-item').height());
                        }

                        // GOGA - remove condition if not necessary
                        if ( $(this).find('.crt-slider-content') && $advancedSlider.data('hide-video-content') === 'yes' ) {
                            $(this).find('.crt-slider-content').fadeOut(300);
                        }
                    }
                });
            }

            function slideAnimationOff() {
                if ( sliderColumnsDesktop == 1 ) {
                    $advancedSlider.find('.crt-slider-item').not('.slick-active').find('.crt-slider-animation').removeClass( 'crt-animation-enter' );
                }
            }

            function slideAnimationOn() {
                $advancedSlider.find('.slick-active').find('.crt-slider-content').fadeIn(0);
                $advancedSlider.find('.slick-cloned').find('.crt-slider-content').fadeIn(0);
                $advancedSlider.find('.slick-current').find('.crt-slider-content').fadeIn(0);
                if ( sliderColumnsDesktop == 1 ) {
                    $advancedSlider.find('.slick-active').find('.crt-slider-animation').addClass( 'crt-animation-enter' );
                }
            }

            slideAnimationOn();

            $advancedSlider.on( 'click', '.crt-slider-video-btn', function() {
                var currentSlide = $(this).closest('.slick-slide'),
                    videoSrc = sanitizeURL(currentSlide.find('.crt-slider-item').attr('data-video-src')) || '',
                    videoButton = $(this),
                    allowFullScreen = '';

                if ( videoPlays == 'true' ) {
                    videoPlays = 'false';
                } else {
                    videoPlays = 'true';
                }

                if ( videoSrc.includes('youtube') ) {
                    videoSrc += "&autoplay=1"; // Tell YouTube to autoplay
                    allowFullScreen = 'allowfullscreen="allowfullscreen"';
                } else if ( videoSrc.includes('vimeo') ) {
                    allowFullScreen = 'allowfullscreen';
                } else {
                    var videoMute = CrtElements.sanitizeDataAttr(currentSlide.find('.crt-slider-item').attr('data-video-mute'));
                    var videoControls = CrtElements.sanitizeDataAttr(currentSlide.find('.crt-slider-item').attr('data-video-controls'));
                    var videoLoop = CrtElements.sanitizeDataAttr(currentSlide.find('.crt-slider-item').attr('data-video-loop'));

                    if ( currentSlide.find( '.crt-slider-video' ).length !== 1 ) {
                        currentSlide.find('.crt-cv-container').prepend('<div class="crt-slider-video crt-custom-video"><video '+ videoLoop + ' ' + videoMute + ' ' + videoControls + ' ' + 'src="'+ videoSrc +'" width="100%" height="100%"></video></div>');

                        $advancedSlider.find('video').attr('width', $scope.find('.crt-slider-item').width());
                        $advancedSlider.find('video').attr('height', $scope.find('.crt-slider-item').height());

                        if ( $advancedSlider.data('hide-video-content') === 'yes' ) {
                            currentSlide.find('.crt-slider-content').fadeOut(300);
                        } else {
                            if ( videoPlays == 'true' ) {
                                videoButton.find('i').removeClass('fa-play').addClass('fa-pause');
                            } else {
                                videoButton.find('i').removeClass('fa-pause').addClass('fa-play');
                            }
                        }

                        currentSlide.find('video')[0].play();
                    } else {
                        if ( videoPlays == 'true' ) {
                            currentSlide.find('video')[0].play();
                            videoButton.find('i').removeClass('fa-play').addClass('fa-pause');
                        } else {
                            currentSlide.find('video')[0].pause();
                            videoButton.find('i').removeClass('fa-pause').addClass('fa-play');
                        }
                    }
                    return;
                }

                if ( currentSlide.find( '.crt-slider-video' ).length !== 1 ) {
                    // currentSlide.find('.crt-cv-container').prepend('<div class="crt-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture;"></iframe></div>');
                    currentSlide.find('.crt-cv-container').prepend('<div class="crt-slider-video"><iframe src="'+ videoSrc +'" width="100%" height="100%"  frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture;"'+ allowFullScreen +'></iframe></div>');

                    sliderVideoSize();

                    if ( $advancedSlider.data('hide-video-content') === 'yes' ) {
                        currentSlide.find('.crt-slider-content').fadeOut(300);
                    }
                }

            });

            $advancedSlider.on( {
                beforeChange: function() {
                    $advancedSlider.find('.crt-slider-item').not('.slick-active').find('.crt-slider-video').remove();
                    $advancedSlider.find('.crt-animation-enter').find('.crt-slider-content').fadeOut(300);
                    if ( $advancedSlider.data('hide-video-content') !== 'yes' ) {
                        $advancedSlider.find('.crt-slider-video-btn').find('i').removeClass('fa-pause').addClass('fa-play');
                        videoPlays = 'false';
                    }
                    slideAnimationOff();
                },
                afterChange: function( event, slick, currentSlide ) {
                    slideAnimationOn();
                    autoplayVideo();
                    $scope.find('.slick-slide').removeClass('crt-slick-visible');
                    $scope.find('.slick-current').addClass('crt-slick-visible');
                    $scope.find('.slick-current').nextAll().slice(0, sliderColumnsDesktop - 1).addClass('crt-slick-visible');
                    $scope.find('.crt-advanced-slider').css('height', $scope.find('.slick-current').outerHeight());
                }
            });

            // Adjust Horizontal Pagination
            if ( $scope.find( '.slick-dots' ).length && $scope.hasClass( 'crt-slider-dots-horizontal') ) {
                // Calculate Width
                var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

                // on Load
                if ( $scope.find( '.slick-dots' ).length ) {
                    $scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
                }

                // on Resize
                $(window).smartresize(function() {
                    setTimeout(function() {
                        // Calculate Width
                        var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

                        // Set Width
                        $scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
                    }, 300 );
                });
            }
        });
    });

    function sanitizeURL(dirtyURL) {
        if (!dirtyURL || typeof dirtyURL !== 'string') return null;

        // Sanitize the raw string (strip tags, attrs)
        const cleaned = DOMPurify.sanitize(dirtyURL, { ALLOWED_TAGS: [], ALLOWED_ATTR: [] }).trim();
        const lower = cleaned.toLowerCase();

        // Block dangerous schemes
        if (/^(javascript|data|vbscript):/.test(lower)) return null;

        // Parse URL (supports protocol-relative with base)
        let url;
        try {
            url = new URL(cleaned, window.location.origin);
        } catch {
            return null;
        }

        // Only http/https
        if (!/^https?:$/.test(url.protocol)) return null;

        // Whitelist domains (allow subdomains)
        const allowedDomains = ['youtube.com', 'youtu.be', 'vimeo.com', 'yourdomain.com'];
        const host = url.hostname.toLowerCase();
        const hostAllowed = allowedDomains.some(d => host === d || host.endsWith('.' + d));

        // Also allow direct video files anywhere
        const isDirectVideo = /\.(mp4|webm|ogg)(?:$|\?)/i.test(url.pathname);

        if (!hostAllowed && !isDirectVideo) return null;

        return url.toString();
    }
})(jQuery);

// Resize Function - Debounce
(function($,sr){

    var debounce = function (func, threshold, execAsap) {
        var timeout;

        return function debounced () {
            var obj = this, args = arguments;
            function delayed () {
                if (!execAsap)
                    func.apply(obj, args);
                timeout = null;
            };

            if (timeout)
                clearTimeout(timeout);
            else if (execAsap)
                func.apply(obj, args);

            timeout = setTimeout(delayed, threshold || 100);
        };
    }
    // smartresize
    jQuery.fn[sr] = function(fn){  return fn ? this.bind('resize', debounce(fn)) : this.trigger(sr); };

})(jQuery,'smartresize');