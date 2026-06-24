(function($) {
    "use strict";

    var WidgetBackgroundSwitcherHandler = function($scope, $) {
        var $container = $scope.find('.crt-bgs-container');
        if (!$container.length) {
            return;
        }

        var effect = $container.data('effect') || 'slip';
        var speed = parseInt($container.data('speed'), 10) / 1000 || 0.6; // convert to seconds for GSAP
        
        var $navItems = $container.find('.crt-bgs-nav-item');
        var $bgItems = $container.find('.crt-bgs-bg-item');

        if (typeof gsap === 'undefined') {
            return; // GSAP is required
        }

        // Initialize backgrounds
        $bgItems.each(function(index) {
            if ($(this).hasClass('active')) {
                gsap.set(this, { opacity: 1, autoAlpha: 1 });
            } else {
                gsap.set(this, { opacity: 0, autoAlpha: 0 });
            }
        });

        var currentIndex = 0;

        $navItems.on('mouseenter', function() {
            var $this = $(this);
            var index = $this.data('index');

            if (index === currentIndex) return;

            // Remove active classes
            $navItems.removeClass('active');
            $this.addClass('active');

            var $outgoingBg = $bgItems.eq(currentIndex);
            var $incomingBg = $bgItems.eq(index);

            currentIndex = index;

            $bgItems.removeClass('active');
            $incomingBg.addClass('active');

            // GSAP Animations based on effect
            if (effect === 'fade') {
                // Crossfade
                gsap.to($outgoingBg, { autoAlpha: 0, duration: speed, ease: "power2.inOut" });
                gsap.fromTo($incomingBg, { autoAlpha: 0 }, { autoAlpha: 1, duration: speed, ease: "power2.inOut" });
                
            } else if (effect === 'slip') {
                // Slide up reveal (slip)
                // Ensure incoming is above outgoing temporarily simply by z-index or rendering order.
                // Or just slide it up while fading out the old one
                gsap.to($outgoingBg, { autoAlpha: 0, y: -50, duration: speed, ease: "power3.inOut" });
                gsap.fromTo($incomingBg, { y: 100, autoAlpha: 0 }, { y: 0, autoAlpha: 1, duration: speed, ease: "power3.inOut" });
                
            } else if (effect === 'zoom') {
                // Zoom in crossfade
                gsap.to($outgoingBg, { autoAlpha: 0, scale: 1.1, duration: speed, ease: "power2.inOut" });
                gsap.fromTo($incomingBg, { scale: 1.05, autoAlpha: 0 }, { scale: 1, autoAlpha: 1, duration: speed, ease: "power2.inOut" });
            }
        });
    };

    $(window).on('elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-background-switcher.default', WidgetBackgroundSwitcherHandler);
    });

})(jQuery);
