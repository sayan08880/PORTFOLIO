(function($) {
    "use strict";

    var WidgetScrollMarqueeHandler = function($scope, $) {
        var $marqueeContainer = $scope.find('.crt-marquee-container');
        if (!$marqueeContainer.length) {
            return;
        }

        var $track = $marqueeContainer.find('.crt-marquee-track');
        var speed = parseFloat($marqueeContainer.data('speed')) || 50; 
        var direction = $marqueeContainer.data('direction') || 'left';
        var pauseOnHover = $marqueeContainer.data('pause-on-hover') === true;
        var scrollEffect = $marqueeContainer.data('scroll-effect') === true;

        if (typeof gsap === 'undefined') {
            return;
        }

        // To create a seamless infinite scroll, we need enough cloned items to cover the screen
        // But since we use JS to animate, it's easier to duplicate the content once
        // and tween the translation.
        
        // Clone the items and append them to the track
        var html = $track.html();
        
        // Ensure track is fully populated to scroll seamlessly
        // In some cases we might need more clones if the items are few.
        var repeatCount = 1;
        
        // If track width is smaller than container width, clone multiple times until it fills
        var trackWidth = $track.outerWidth();
        var containerWidth = $marqueeContainer.outerWidth();
        
        if (trackWidth > 0 && trackWidth < containerWidth * 2) {
            repeatCount = Math.ceil((containerWidth * 2) / trackWidth);
        }
        
        for (var i = 0; i < repeatCount; i++) {
            $track.append(html);
        }

        // We re-measure the original group width to know how far to animate
        // But since CSS gap is applied, we must measure the full track and divide
        var totalWidth = $track[0].scrollWidth;
        // The width of one original set of items including gaps
        var singleSetWidth = totalWidth / (repeatCount + 1);

        var duration = singleSetWidth / speed;

        // Set up the animation
        var distance = (direction === 'left') ? -singleSetWidth : singleSetWidth;

        // If moving right, we start at -singleSetWidth and animate to 0
        if (direction === 'right') {
            gsap.set($track, { x: -singleSetWidth });
            distance = 0;
        }

        var tween = gsap.to($track, {
            x: distance,
            ease: "none",
            duration: duration,
            repeat: -1,
            // If direction is right, we reset to -singleSetWidth on restart
            onRepeat: function() {
                if(direction === 'right') {
                   gsap.set($track, { x: -singleSetWidth });
                }
            }
        });

        if (pauseOnHover) {
            $marqueeContainer.on('mouseenter', function() {
                tween.pause();
            }).on('mouseleave', function() {
                tween.play();
            });
        }

        if (scrollEffect && typeof ScrollTrigger !== 'undefined') {
            var scrollTimeout;
            ScrollTrigger.create({
                start: 0,
                end: "max",
                onUpdate: function(self) {
                    var velocity = Math.abs(self.getVelocity() || 0);
                    // Add velocity to scale (e.g., speed up when scrolling fast)
                    var scale = 1 + velocity / 500; 
                    scale = Math.min(scale, 5); // cap speed

                    // self.direction is 1 (down) or -1 (up)
                    var dir = self.direction; 
                    
                    gsap.to(tween, {
                        timeScale: dir * scale,
                        duration: 0.2,
                        overwrite: true
                    });
                    
                    clearTimeout(scrollTimeout);
                    scrollTimeout = setTimeout(function() {
                        gsap.to(tween, {
                            timeScale: dir, // maintain direction after scrolling stops
                            duration: 0.5,
                            overwrite: true
                        });
                    }, 150);
                }
            });
        }
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-scroll-marquee.default', WidgetScrollMarqueeHandler);
    });

})(jQuery);
