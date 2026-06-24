(function($) {
    "use strict";

    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/global',function($scope) {
            if ( $scope.hasClass('crt-sticky-section-yes') && typeof CRTConfig !== 'undefined' && CRTConfig.sticky_section === 'on'  ) {
                $(document).ready(function() {
                    stickySection();
                });
            }

            function stickySection() {
                var positionType = !CrtElements.editorCheck() ? $scope.attr('data-crt-position-type') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-position-type'),
                    positionLocation = !CrtElements.editorCheck() ? $scope.attr('data-crt-position-location') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-position-location'),
                    positionOffset = !CrtElements.editorCheck() ? $scope.attr('data-crt-position-offset') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-position-offset'),
                    viewportWidth = $('body').prop('clientWidth') + 17,
                    availableDevices = !CrtElements.editorCheck() ? $scope.attr('data-crt-sticky-devices') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-sticky-devices'),
                    activeDevices = !CrtElements.editorCheck() ? $scope.attr('data-crt-active-breakpoints') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-active-breakpoints'),
                    stickySectionExists = $scope.hasClass('crt-sticky-section-yes') || $scope.find('.crt-sticky-section-yes-editor') ? true : false,
                    positionStyle,
                    adminBarHeight,
                    stickyEffectsOffset = $(window).scrollTop(),
                    stickyHideDistance = 0,
                    $window = $(window),
                    prevScrollPos = $window.scrollTop(),
                    stickyHeaderFooter = '',
                    stickyAnimation = 'none',
                    stickyAnimationHide = '',
                    headerFooterZIndex = !CrtElements.editorCheck() ? $scope.attr('data-crt-z-index') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-z-index'),
                    stickType = !CrtElements.editorCheck() ? $scope.attr('data-crt-sticky-type') : $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-sticky-type'),
                    hiddenHeaderClass = $scope.next().hasClass('e-con') ? 'crt-hidden-header-flex' : 'crt-hidden-header',
                    distanceFromTop = $scope.offset().top,
                    windowHeight = $(window).height(),
                    elementHeight = $scope.outerHeight(true),
                    distanceFromBottom = $(document).height() - (distanceFromTop + elementHeight),
                    offsetTop;


                if ( $scope.data('settings') && $scope.data('settings').sticky_animation ) {
                    stickyAnimation = $scope.data('settings').sticky_animation;
                } else {
                    stickyAnimation = $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-sticky-animation');
                }

                var stickyAnimDuration = $scope.attr('data-crt-animation-duration') ? $scope.attr('data-crt-animation-duration') + 's' : 500 + 's';

                if ( $scope.closest('div[data-elementor-type="wp-post"]').length > 0 ) {
                    stickyHeaderFooter = $scope.closest('div[data-elementor-type="wp-post"]');
                } else if ( $scope.closest('div[data-elementor-type="header"]').length > 0 ) {
                    stickyHeaderFooter = $scope.closest('div[data-elementor-type="header"]');
                } else if ( $scope.closest('div[data-elementor-type="footer"]').length > 0 ) {
                    stickyHeaderFooter = $scope.closest('div[data-elementor-type="footer"]');
                }

                if ( !$scope.find('.crt-sticky-section-yes-editor').length) {
                    positionType = $scope.attr('data-crt-position-type');
                    positionLocation = $scope.attr('data-crt-position-location');
                    positionOffset = $scope.attr('data-crt-position-offset');
                    availableDevices = $scope.attr('data-crt-sticky-devices');
                    activeDevices = $scope.attr('data-crt-active-breakpoints');
                    headerFooterZIndex = $scope.attr('data-crt-z-index');
                }

                if ( 'top' === positionLocation && 'auto' === $scope.css('top') ) {
                    var offsetTop = $scope.data('crt-position-offset');
                    $scope.css('top', offsetTop);
                } else {
                    var offsetTop = $scope.data('crt-position-offset');
                }

                if ( 0 == availableDevices.length ) {
                    positionType = 'relative';
                }

                if ( CrtElements.editorCheck() && availableDevices ) {
                    var attributes = $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-sticky-devices');
                    $scope.attr('data-crt-sticky-devices', attributes);
                    availableDevices = $scope.attr('data-crt-sticky-devices');
                }

                changePositionType();
                changeAdminBarOffset();

                $(window).smartresize(function() {
                    recalculateVariables();
                });

                // Debounce function
                function debounce(func, wait) {
                    let timeout;
                    return function(...args) {
                        const context = this;
                        clearTimeout(timeout);
                        timeout = setTimeout(() => func.apply(context, args), wait);
                    };
                }// Adjust the debounce delay as needed

                // Create a MutationObserver instance
                if ( !CrtElements.editorCheck() ) {
                    if ( 'yes' !== $scope.data('crt-replace-header') && 'yes' !== $scope.data('crt-sticky-hide') ) {
                        // Function to be called when mutations are observed
                        const handleMutations = debounce(function(mutationsList) {
                            // if (!$scope[0] || !document.body.contains($scope[0])) return;
                            // var isGTranslate = function(node) {
                            // 	if (!node || !node.getAttribute) return false;
                            // 	var id = node.getAttribute('id') || '';
                            // 	if (id.indexOf('google_translate') !== -1 || id.indexOf('gtranslate') !== -1) return true;
                            // 	return node.closest && (node.closest('#google_translate_element') || node.closest('.gtranslate'));
                            // };
                            for (let mutation of mutationsList) {
                                // if (mutation.type === 'childList' && !isGTranslate(mutation.target)) {
                                if (mutation.type === 'childList') {
                                    $(window).trigger('scroll');
                                    recalculateVariables();
                                    break;
                                }
                            }
                        }, 100);

                        const observer = new MutationObserver(handleMutations);
                        observer.observe(document.body, { childList: true, subtree: true });
                    }
                }

                $(window).scroll(function() {
                    if ($scope && $scope.css('position') === 'relative') {
                        recalculateVariables();
                    }
                });

                if (!stickySectionExists) {
                    positionStyle = 'relative';
                }

                function recalculateVariables() {
                    distanceFromTop = $scope.offset().top;
                    windowHeight = $(window).height(),
                        elementHeight = $scope.outerHeight(true),
                        distanceFromBottom = $(document).height() - (distanceFromTop + elementHeight);

                    viewportWidth = $('body').prop('clientWidth') + 17;

                    changePositionType();
                }

                function changePositionType() {
                    if ( !$scope.hasClass('crt-sticky-section-yes') && !$scope.find('.crt-sticky-section-yes-editor') ) {
                        positionStyle = 'relative';
                        return;
                    }

                    var desktopDimension = activeDevices.includes('widescreen_sticky') ? 2400 : 4000
                    var checkDevices = [['mobile_sticky', 768], ['mobile_extra_sticky', 881], ['tablet_sticky', 1025], ['tablet_extra_sticky', 1201], ['laptop_sticky', 1216],  ['desktop_sticky', 4000], ['widescreen_sticky', 4000]];
                    var emptyVariables = [];

                    var checkedDevices = checkDevices.filter((item, index) => {
                        return activeDevices.indexOf(item[0]) != -1;
                    }).reverse();

                    checkedDevices.forEach((device, index) => {
                        if ( device[1] > viewportWidth ) {
                            var deviceName = device[0].replace("_sticky", "");

                            if ( 'desktop' == deviceName ) {
                                if ( $scope.data('settings') ) {
                                    stickyEffectsOffset = distanceFromTop + $scope.data('settings').crt_sticky_effects_offset;
                                } else {
                                    stickyEffectsOffset = distanceFromTop + $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-offset-settings');
                                }
                            } else {
                                if ( $scope.data('settings') ) {
                                    stickyEffectsOffset = distanceFromTop + $scope.data('settings')['crt_sticky_effects_offset_' + deviceName];
                                } else {
                                    stickyEffectsOffset = distanceFromTop + $scope.find('.crt-sticky-section-yes-editor').attr('data-crt-offset-settings');
                                }
                            }

                            if ( availableDevices.indexOf(device[0]) === -1 ) {
                                positionStyle = activeDevices?.indexOf(device[0]) !== -1 ? 'relative' : (emptyVariables[index - 1] ? emptyVariables[index - 1] : positionType);
                                // positionStyle = activeDevices && activeDevices.indexOf(device[0]) !== -1 ? 'static' : (emptyVariables[index - 1] ? emptyVariables[index - 1] : positionType);
                                emptyVariables[index] = positionStyle;
                            } else if ( availableDevices.indexOf(device[0]) !== -1 ) {
                                positionStyle = positionType;
                            }
                        }
                    });

                    var handleScroll = function() {
                        let scrollPos = $window.scrollTop();

                        if ( 'fixed' != positionStyle ) {
                            if ( 'top' === positionLocation ) {
                                if ( scrollPos > distanceFromTop) {
                                    applyPosition();
                                } else if ( scrollPos <= distanceFromTop ) {
                                    $scope.css({'position': 'relative' });
                                }
                            }

                            if ( 'bottom' === positionLocation ) {
                                if ( scrollPos + windowHeight <= $(document).height() - distanceFromBottom ) {
                                    applyPosition();
                                } else {
                                    $scope.css({'position': 'relative' });
                                }
                            }
                        }

                        if ( 'relative' !== positionStyle ) {
                            if ( scrollPos > stickyEffectsOffset ) {
                                if ( 'yes' == $scope.data('crt-replace-header') ) {

                                    if ( 'yes' === $scope.data('crt-sticky-hide') ) {

                                        if ( scrollPos >= distanceFromTop ) {
                                            $scope.addClass('crt-visibility-hidden');
                                        }

                                        if ( scrollPos < prevScrollPos) {
                                            $scope.next().addClass(hiddenHeaderClass).addClass('crt-' + stickyAnimation + '-in');
                                        }
                                    } else {
                                        $scope.addClass('crt-visibility-hidden');
                                        $scope.next().addClass(hiddenHeaderClass).addClass('crt-' + stickyAnimation + '-in');
                                    }
                                } else {
                                    $scope.addClass('crt-sticky-header');
                                }
                            } else if ( scrollPos <= stickyEffectsOffset ) {
                                if ( 'yes' == $scope.data('crt-replace-header') ) {
                                    $scope.next().removeClass(hiddenHeaderClass);
                                    $scope.removeClass('crt-visibility-hidden');
                                    $scope.next().removeClass('crt-' + stickyAnimation + '-in');
                                } else {
                                    $scope.removeClass('crt-sticky-header');
                                }
                            }
                        }

                        if ( 'yes' === $scope.data('crt-sticky-hide') ) {
                            if ( scrollPos >= distanceFromTop ) {
                                if ( scrollPos < prevScrollPos ) {
                                    // Scrolling up
                                    if ( 'yes' === $scope.data('crt-replace-header') ) {
                                        $scope.next().removeClass('crt-' + stickyAnimation + '-out');
                                        $scope.next().addClass('crt-' + stickyAnimation + '-in');
                                    } else {
                                        $scope.removeClass('crt-' + stickyAnimation + '-out');
                                        $scope.addClass('crt-' + stickyAnimation + '-in');
                                    }
                                } else {
                                    // Scrolling down or no direction change
                                    if ( 'yes' === $scope.data('crt-replace-header') ) {
                                        $scope.next().removeClass('crt-' + stickyAnimation + '-in');
                                        $scope.next().addClass('crt-' + stickyAnimation + '-out');
                                    } else {
                                        $scope.removeClass('crt-' + stickyAnimation + '-in');
                                        $scope.addClass('crt-' + stickyAnimation + '-out');
                                    }
                                }
                            }

                            if ( 0 === $(window).scrollTop() && 'sticky' === positionType ) {
                                $scope.css('position', 'relative');
                            }
                        }

                        // Clear any previous timeout
                        clearTimeout(scrollEndTimeout);

                        // Set a new timeout to update prevScrollPos after 150 milliseconds (adjust the delay as needed)
                        scrollEndTimeout = setTimeout(() => {
                            prevScrollPos = scrollPos;
                        }, 10);
                    }

                    if ( 'sticky' == positionStyle ) {
                        $(window).scroll(handleScroll);
                    } else if ( 'fixed' == positionStyle ) {
                        applyPosition();
                        $(window).scroll(handleScroll);
                    }

                    if ( 'yes' == $scope.data('crt-replace-header') ) {
                        if ( 0 != $scope.next().length ) {
                            $scope.next().get(0).style.setProperty('--crt-animation-duration', stickyAnimDuration);
                        }
                    }

                    let scrollEndTimeout;
                }

                function applyPosition() {
                    var bottom = +window.innerHeight - (+$scope.css('top').slice(0, -2) + $scope.height());
                    var top = +window.innerHeight - (+$scope.css('bottom').slice(0, -2) + $scope.height());

                    if ( 'yes' === $scope.data('crt-sticky-hide') && prevScrollPos < $window.scrollTop() ) {
                        return;
                    }

                    if ( '' == stickType ) {
                        stickType = 'fixed';
                    }

                    $scope.css({'position': stickType });

                    if ( $('.crt-close-cart span').length && !CrtElements.editorCheck() ) {
                        CrtElements.closeSideBarOnReplace($scope, 200);
                    }
                }

                function changeAdminBarOffset() {
                    if ( $('#wpadminbar').length ) {
                        adminBarHeight = $('#wpadminbar').css('height').slice(0, $('#wpadminbar').css('height').length - 2);

                        if ( 'top'  ===  positionLocation && ( 'fixed' == $scope.css('position') ) ) {
                            $scope.css('top', +adminBarHeight + offsetTop + 'px');
                            $scope.css('bottom', 'auto');
                        }
                    }
                }
            }

            var CrtElements = {
                editorCheck: function() {
                    return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
                },
            }
        });
    });
})(jQuery);