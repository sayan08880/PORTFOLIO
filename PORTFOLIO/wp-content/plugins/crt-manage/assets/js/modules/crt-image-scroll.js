(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-image-scroll.default',function($scope) {

            var $container = $scope.find('.crt-image-scroll-wrap');
            var $img = $container.find('img');
            var settings = $container.data();
            var scrollSpeed = settings.speed || 1;
            var containerHeight = $container.height();
            var containerWidth = $container.width();
            var imgHeight = $img.height();
            var imgWidth = $img.width();
            var isVertical = $container.hasClass('crt-scroll-vertical');
            var isReverse = $container.hasClass('crt-direction-reverse');
            var maxScroll = isVertical ? imgHeight - containerHeight : imgWidth - containerWidth;
            var $link = $scope.find('.crt-image-scroll-link');

            $img.on('load', function() {
                imgHeight = $img.height();
                imgWidth = $img.width();
                maxScroll = isVertical ? imgHeight - containerHeight : imgWidth - containerWidth;
                updateTransitionSpeed();
                init();
            });

            init();

            // Add transition style with dynamic speed
            function updateTransitionSpeed() {
                var transitionSpeed = scrollSpeed; // Adjust this ratio as needed
                $img.css('transition', `transform ${transitionSpeed}s ease-out`);
            }

            function init() {
                updateTransitionSpeed();
                // Set initial position if reverse is enabled
                if (isReverse) {
                    if (isVertical) {
                        $img.css('transform', `translateY(-${maxScroll}px)`);
                    } else {
                        $img.css('transform', `translateX(-${maxScroll}px)`);
                    }
                }

                $container.on('mouseenter', function() {
                    $scope.find('.crt-image-scroll-icon').addClass('crt-image-scroll-icon-hidden');
                });

                $container.on('mouseleave', function() {
                    $scope.find('.crt-image-scroll-icon').removeClass('crt-image-scroll-icon-hidden');
                });

                if ($container.hasClass('crt-trigger-hover')) {
                    initHoverScroll();
                } else if ($container.hasClass('crt-trigger-scroll')) {
                    initMouseScroll();
                }
            }

            // Hover Scroll
            function initHoverScroll() {
                var $hoverElement = $link.length ? $link : $container;

                $hoverElement.on('mouseenter', function() {
                    if (isReverse) {
                        // If reverse, go from bottom/right to top/left
                        if (isVertical) {
                            $img.css('transform', 'translateY(0)');
                        } else {
                            $img.css('transform', 'translateX(0)');
                        }
                    } else {
                        // If not reverse, go from top/left to bottom/right
                        if (isVertical) {
                            $img.css('transform', `translateY(-${maxScroll}px)`);
                        } else {
                            $img.css('transform', `translateX(-${maxScroll}px)`);
                        }
                    }
                });

                $container.on('mouseleave', function() {
                    if (isReverse) {
                        // If reverse, return to bottom/right
                        if (isVertical) {
                            $img.css('transform', `translateY(-${maxScroll}px)`);
                        } else {
                            $img.css('transform', `translateX(-${maxScroll}px)`);
                        }
                    } else {
                        // If not reverse, return to top/left
                        $img.css('transform', 'translate(0, 0)');
                    }
                });
            }

            // Mouse Scroll
            function initMouseScroll() {
                var scrollPosition = isReverse ? maxScroll : 0;
                var isScrolling = false;

                $container.on('wheel', function(e) {
                    e.preventDefault();
                    if (!isScrolling) {
                        requestAnimationFrame(updateScroll);
                    }
                    isScrolling = true;

                    var delta = e.originalEvent.deltaY;
                    if (isReverse) {
                        delta = -delta;
                    }
                    scrollPosition = Math.max(Math.min(scrollPosition + delta * scrollSpeed, maxScroll), 0);
                });

                function updateScroll() {
                    if (isVertical) {
                        $img.css('transform', 'translateY(-' + scrollPosition + 'px)');
                    } else {
                        $img.css('transform', 'translateX(-' + scrollPosition + 'px)');
                    }
                    isScrolling = false;
                }
            }
        });
    });
})(jQuery);