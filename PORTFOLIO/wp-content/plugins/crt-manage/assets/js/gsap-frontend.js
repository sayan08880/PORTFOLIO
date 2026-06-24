jQuery(document).ready(function ($) {
    /**
     * GFEA Frontend Handler
     */
    var CRTGSFrontend = {
        init: function () {
            elementorFrontend.hooks.addAction('frontend/element_ready/widget', CRTGSFrontend.initGSAP);
            elementorFrontend.hooks.addAction('frontend/element_ready/section', CRTGSFrontend.initGSAP);
            elementorFrontend.hooks.addAction('frontend/element_ready/column', CRTGSFrontend.initGSAP);
            elementorFrontend.hooks.addAction('frontend/element_ready/container', CRTGSFrontend.initGSAP);
        },

        splitText: function ($element, mode) {
            // Target common text wrappers inside the element
            var $targetNodes = $element.find('h1, h2, h3, h4, h5, h6, p, span, div').filter(function () {
                // Filter elements that have direct text content and no children (leaf nodes mainly)
                return $(this).children().length === 0 && $(this).text().trim().length > 0;
            });

            // If no specific text children found, try the element itself
            if ($targetNodes.length === 0 && $element.text().trim().length > 0) {
                $targetNodes = $element;
            }

            $targetNodes.each(function () {
                var $node = $(this);
                var text = $node.text();
                var chars = text.split('');
                var words = text.split(' ');
                var html = '';

                if (mode === 'chars') {
                    $.each(chars, function (i, char) {
                        if (char === ' ') {
                            html += '&nbsp;';
                        } else {
                            html += '<span style="display:inline-block; opacity: 1;">' + char + '</span>';
                        }
                    });
                } else if (mode === 'words') {
                    $.each(words, function (i, word) {
                        html += '<span style="display:inline-block; opacity: 1;">' + word + '</span> ';
                    });
                }

                $node.html(html);
            });

            return $element.find('span'); // Return the created spans
        },

        initGSAP: function ($scope) {
            var $target = $scope;
            var settings = $target.data('gfea-settings');

            if (!settings) {
                return;
            }

            // GSAP Animation Logic
            var animationType = settings.type;
            var duration = parseFloat(settings.duration) || 1;
            var delay = parseFloat(settings.delay) || 0;
            var ease = settings.ease || 'power2.out';
            var $animTarget = $target[0];

            var animProps = {
                duration: duration,
                delay: delay,
                ease: ease
            };

            // Text Animation Handling
            if (animationType.indexOf('text-') === 0) {
                var stagger = 0.05;
                var mode = 'chars';
                if (animationType.indexOf('words') !== -1) {
                    mode = 'words';
                    stagger = 0.1;
                }

                var $spans = CRTGSFrontend.splitText($target, mode);
                if ($spans.length > 0) {
                    $animTarget = $spans;
                    animProps.stagger = stagger;
                }
            }

            // Define Animations
            switch (animationType) {
                // Standard Animations
                case 'fadeIn':
                    animProps.opacity = 0;
                    break;
                case 'fadeInUp':
                    animProps.y = 50;
                    animProps.opacity = 0;
                    break;
                case 'fadeInDown':
                    animProps.y = -50;
                    animProps.opacity = 0;
                    break;
                case 'fadeInLeft':
                    animProps.x = -50;
                    animProps.opacity = 0;
                    break;
                case 'fadeInRight':
                    animProps.x = 50;
                    animProps.opacity = 0;
                    break;
                case 'zoomIn':
                    animProps.scale = 0.5;
                    animProps.opacity = 0;
                    break;
                case 'zoomOut':
                    animProps.scale = 1.5;
                    animProps.opacity = 0;
                    break;
                case 'rotateIn':
                    animProps.rotation = -180;
                    animProps.opacity = 0;
                    break;
                case 'fromLeft':
                    animProps.x = '-100%';
                    animProps.opacity = 0;
                    break;
                case 'fromRight':
                    animProps.x = '100%';
                    animProps.opacity = 0;
                    break;

                // Text Animations
                case 'text-chars-fadeInUp':
                    animProps.y = 30;
                    animProps.opacity = 0;
                    break;
                case 'text-chars-typewriter':
                    animProps.opacity = 0;
                    animProps.duration = 0.01; // Almost instant per char
                    animProps.stagger = duration / $animTarget.length; // Spread duration
                    break;
                case 'text-words-fadeIn':
                    animProps.y = 20;
                    animProps.opacity = 0;
                    break;

                default:
                    animProps.opacity = 0;
            }

            // ScrollTrigger
            if (settings.scrollTrigger && settings.scrollTrigger.enable === 'yes') {
                animProps.scrollTrigger = {
                    trigger: $target[0],
                    start: settings.scrollTrigger.start || 'top 80%',
                    end: settings.scrollTrigger.end || 'bottom 20%',
                    scrub: settings.scrollTrigger.scrub === 'yes' ? true : false,
                    markers: settings.scrollTrigger.markers === 'yes' ? true : false,
                };
            }

            // Apply Animation
            gsap.from($animTarget, animProps);
        }
    };

    $(window).on('elementor/frontend/init', CRTGSFrontend.init);
});
