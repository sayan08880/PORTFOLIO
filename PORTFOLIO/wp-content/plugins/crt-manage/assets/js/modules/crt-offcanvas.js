(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-offcanvas.default',function($scope) {

            let animationDuration;

            if ( $scope.hasClass('crt-offcanvas-entrance-animation-pro-sl') ) {
                $scope.removeClass('crt-offcanvas-entrance-animation-pro-sl').addClass('crt-offcanvas-entrance-animation-fade');
            } else if ( $scope.hasClass('crt-offcanvas-entrance-animation-pro-gr') ) {
                $scope.removeClass('crt-offcanvas-entrance-animation-pro-gr').addClass('crt-offcanvas-entrance-animation-fade');
            }

            if ( $scope.hasClass('crt-offcanvas-entrance-type-pro-ps') ) {
                $scope.removeClass('crt-offcanvas-entrance-type-pro-ps').addClass('crt-offcanvas-entrance-type-cover');
            }

            function openOffcanvas(offcanvasSelector) {
                if ( !$scope.hasClass('crt-offcanvas-entrance-type-push') && !$scope.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-relative') ) {
                    $('body').addClass('crt-offcanvas-body-overflow');
                }
                animationDuration = +offcanvasSelector.find('.crt-offcanvas-content').css('animation-duration').replace('s', '') * 1000;
                offcanvasSelector.fadeIn(animationDuration);
                offcanvasSelector.addClass('crt-offcanvas-wrap-active');
                if ( $scope.hasClass('crt-offcanvas-entrance-animation-slide') ) {
                    if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-slide-in') ) {
                        offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-slide-in').addClass('crt-offcanvas-slide-out');
                    } else {
                        offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-slide-out').addClass('crt-offcanvas-slide-in');
                    }
                } else if ( $scope.hasClass('crt-offcanvas-entrance-animation-grow') ) {
                    if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-grow-in') ) {
                        offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-grow-in').addClass('crt-offcanvas-grow-out');
                    } else {
                        offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-grow-out').addClass('crt-offcanvas-grow-in');
                    }
                } else if ( $scope.hasClass('crt-offcanvas-entrance-animation-fade') ) {
                    if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-fade-in') ) {
                        offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-fade-in').addClass('crt-offcanvas-fade-out');
                    } else {
                        offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-fade-out').addClass('crt-offcanvas-fade-in');
                    }
                }

                $(window).trigger('resize');
            }

            function closeOffcanvas(offcanvasSelector) {
                if ( !$scope.hasClass('crt-offcanvas-entrance-type-push') && !$scope.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-relative') ) {
                    $('body').removeClass('crt-offcanvas-body-overflow');
                }
                if ( $scope.hasClass('crt-offcanvas-entrance-animation-slide') ) {
                    offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-slide-in').addClass('crt-offcanvas-slide-out');
                } else if ( $scope.hasClass('crt-offcanvas-entrance-animation-grow') ) {
                    offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-grow-in').addClass('crt-offcanvas-grow-out');
                } else if ( $scope.hasClass('crt-offcanvas-entrance-animation-fade') ) {
                    offcanvasSelector.find('.crt-offcanvas-content').removeClass('crt-offcanvas-fade-in').addClass('crt-offcanvas-fade-out');
                }

                offcanvasSelector.fadeOut(animationDuration);
                offcanvasSelector.removeClass('crt-offcanvas-wrap-active');
                // setTimeout(function() {
                // }, 600);
            }

            if ( $scope.hasClass('crt-offcanvas-entrance-type-push') ) {

                function growBodyWidth() {

                    if ($('.crt-offcanvas-body-inner-wrap-'+ $scope.data('id')).length < 1 ) {
                        var offcanvasWrap = $('.crt-offcanvas-wrap-'+ $scope.data('id')).clone();
                        $('.crt-offcanvas-wrap-'+ $scope.data('id')).remove();

                        if ( !($('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')).length > 0) ) {
                            $("body").wrapInner('<div class="crt-offcanvas-body-inner-wrap-' + $scope.data('id') + '" />');
                        }

                        bodyInnerWrap = $('.crt-offcanvas-body-inner-wrap-' + $scope.data('id'));

                        bodyInnerWrap.css('position', 'relative');

                        if ( !(bodyInnerWrap.prev('.crt-offcanvas-wrap').length > 0) ) {
                            document.querySelector('body').insertBefore(offcanvasWrap[0], document.querySelector('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')));
                        }

                        offcanvasSelector = $('.crt-offcanvas-wrap-'+ $scope.data('id'));
                    }

                    openOffcanvas(offcanvasSelector);

                    $('body').addClass('crt-offcanvas-body-overflow');

                    if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-left') ) {
                        // bodyInnerWrap.animate({'margin-left': offcanvasSelector.find('.crt-offcanvas-content').width() + 'px'}, 'slow');
                        bodyInnerWrap.css({
                            'transition-duration': offcanvasSelector.find('.crt-offcanvas-content').css('animation-duration'),
                            'transform': 'translateX('+ offcanvasSelector.find('.crt-offcanvas-content').outerWidth() +'px)',
                        });
                    } else if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-right') ) {
                        // bodyInnerWrap.animate({'margin-right': offcanvasSelector.find('.crt-offcanvas-content').width() + 'px'}, 'slow');
                        bodyInnerWrap.css({
                            'transition-duration': offcanvasSelector.find('.crt-offcanvas-content').css('animation-duration'),
                            'transform': 'translateX(-'+ offcanvasSelector.find('.crt-offcanvas-content').outerWidth() +'px)',
                        });
                    } else if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-top') ) {
                        // bodyInnerWrap.animate({'margin-top': offcanvasSelector.find('.crt-offcanvas-content').outerHeight() + 'px'}, 'slow');
                        bodyInnerWrap.css({
                            'transition-duration': offcanvasSelector.find('.crt-offcanvas-content').css('animation-duration'),
                            'margin-top': offcanvasSelector.find('.crt-offcanvas-content').outerHeight() + 'px',
                        });
                    }
                }

                function reduceBodyWidth() {

                    if ( !bodyInnerWrap && !offcanvasSelector )  {
                        bodyInnerWrap = $('.crt-offcanvas-body-inner-wrap-' + $scope.data('id'));
                        offcanvasSelector = $('.crt-offcanvas-wrap-'+ $scope.data('id'));
                    }

                    closeOffcanvas(offcanvasSelector);

                    if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-left') ) {
                        // bodyInnerWrap.animate({'margin-left': 0}, 'slow');
                        bodyInnerWrap.css({'transform': 'translateX(0px)'});
                    } else if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-right') ) {
                        // bodyInnerWrap.animate({'margin-right': 0}, 'slow');
                        bodyInnerWrap.css({'transform': 'translateX(0px)'});
                    } else if ( offcanvasSelector.find('.crt-offcanvas-content').hasClass('crt-offcanvas-content-top') ) {
                        // bodyInnerWrap.animate({'margin-top': 0}, 'slow');
                        bodyInnerWrap.css({'margin-top': 0});
                    }

                    $('body').removeClass('crt-offcanvas-body-overflow');
                    setTimeout(function() {
                        var cnt = $('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')).contents();
                        $('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')).replaceWith(cnt);
                    }, 1000);
                }

                function closeTriggers() {
                    offcanvasSelector.on('click', function(e){
                        if ( !e.target.classList.value.includes('crt-offcanvas-content') && !e.target.closest('.crt-offcanvas-content') ) {
                            reduceBodyWidth();
                        }
                    });

                    $(document).on('keyup', function(event) {
                        if (event.key == "Escape") {
                            reduceBodyWidth();
                        }
                    });

                    offcanvasSelector.find('.crt-close-offcanvas').on('click', function() {
                        reduceBodyWidth();
                    });
                }

                if ( !($('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')).length > 0) ) {
                    $("body").wrapInner('<div class="crt-offcanvas-body-inner-wrap-' + $scope.data('id') + '" />');
                }

                var bodyInnerWrap = $('.crt-offcanvas-body-inner-wrap-' + $scope.data('id'));

                bodyInnerWrap.css('position', 'relative');

                if ( !(bodyInnerWrap.prev('.crt-offcanvas-wrap').length > 0) ) {
                    $scope.find('.crt-offcanvas-wrap').addClass('crt-offcanvas-wrap-'+ $scope.data('id'));

                    document.querySelector('body').insertBefore($scope.find('.crt-offcanvas-wrap')[0], document.querySelector('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')));
                }

                var offcanvasSelector = $('.crt-offcanvas-wrap-'+ $scope.data('id'));

                $scope.find('.crt-offcanvas-trigger').on('click', function() {
                    if ( $('.crt-offcanvas-wrap-'+ $scope.data('id')).length > 0 && $scope.find('.crt-offcanvas-wrap').length > 0 ) {
                        $('.crt-offcanvas-wrap-'+ $scope.data('id')).remove();
                        $scope.find('.crt-offcanvas-wrap').addClass('crt-offcanvas-wrap-'+ $scope.data('id'));
                        document.querySelector('body').insertBefore($scope.find('.crt-offcanvas-wrap')[0], document.querySelector('.crt-offcanvas-body-inner-wrap-' + $scope.data('id')));
                        offcanvasSelector = $('.crt-offcanvas-wrap-'+ $scope.data('id'));
                    }

                    if (offcanvasSelector.hasClass('crt-offcanvas-wrap-active')) {
                        reduceBodyWidth();
                    } else {
                        growBodyWidth();
                    }
                });

                if ( 'yes' === $scope.find('.crt-offcanvas-container').data('offcanvas-open') ) {
                    $scope.find('.crt-offcanvas-trigger').trigger('click');
                }

                closeTriggers();

                $('body').on('click', function() {
                    closeTriggers();
                });

                var mutationObserver = new MutationObserver(function(mutations) {
                    closeTriggers();
                });

                mutationObserver.observe($scope[0], {
                    childList: true,
                    subtree: true,
                });
            } else {

                $scope.find('.crt-offcanvas-trigger').on('click', function() {
                    if ( !$scope.find('.crt-offcanvas-wrap').hasClass('crt-offcanvas-wrap-active') ) {
                        openOffcanvas($scope.find('.crt-offcanvas-wrap'));
                    } else if ( $scope.find('.crt-offcanvas-wrap').hasClass('crt-offcanvas-wrap-active') && $scope.find('.crt-offcanvas-wrap').hasClass('crt-offcanvas-wrap-relative') ) {
                        closeOffcanvas($scope.find('.crt-offcanvas-wrap'));
                    }
                });

                $scope.find('.crt-offcanvas-wrap').on('click', function(e){
                    if ( !e.target.classList.value.includes('crt-offcanvas-content') && !e.target.closest('.crt-offcanvas-content') ) {
                        closeOffcanvas($scope.find('.crt-offcanvas-wrap'));
                    }
                });

                if ( 'yes' === $scope.find('.crt-offcanvas-container').data('offcanvas-open') ) {
                    $scope.find('.crt-offcanvas-trigger').trigger('click');
                }

                $(document).on('keyup', function(event) {
                    if (event.key == "Escape") {
                        closeOffcanvas($scope.find('.crt-offcanvas-wrap'));
                    }
                });

                $scope.find('.crt-close-offcanvas').on('click', function() {
                    closeOffcanvas($scope.find('.crt-offcanvas-wrap'));
                });

            }

        });
    });
})(jQuery);