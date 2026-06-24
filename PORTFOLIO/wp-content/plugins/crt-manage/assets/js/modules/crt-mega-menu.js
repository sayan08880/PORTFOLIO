(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-mega-menu.default',function($scope) {


            var $navMenu = $scope.find( '.crt-nav-menu-container' ),
                $mobileNavMenu = $scope.find( '.crt-mobile-nav-menu-container' );

            // Menu
            var subMenuFirst = $navMenu.find( '.crt-nav-menu > li.menu-item-has-children' ),
                subMenuDeep = $navMenu.find( '.crt-sub-menu li.menu-item-has-children' );

            if ( $scope.find('.crt-mobile-toggle').length ) {
                $scope.find('a').on('click', function() { // GOGA - sub mega menu condition needs testing
                    if ( this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)  && !($(this).closest('.crt-sub-mega-menu').length > 0) ) {
                        $scope.find('.crt-mobile-toggle').trigger('click');
                    }
                });
            }

            // Click
            if ( $navMenu.attr('data-trigger') === 'click' ) {

                // First Sub
                subMenuFirst.children('a').on( 'click', function(e) {
                    var currentItem = $(this).parent(),
                        childrenSub = currentItem.children('.crt-sub-menu, .crt-sub-mega-menu');

                    // Reset
                    subMenuFirst.not(currentItem).removeClass('crt-sub-open');
                    if ( $navMenu.hasClass('crt-nav-menu-horizontal') || ( $navMenu.hasClass('crt-nav-menu-vertical') ) ) {
                        subMenuAnimation( subMenuFirst.children('.crt-sub-menu, .crt-sub-mega-menu'), false );
                    }

                    if ( ! currentItem.hasClass( 'crt-sub-open' ) ) {
                        e.preventDefault();
                        currentItem.addClass('crt-sub-open');
                        subMenuAnimation( childrenSub, true );
                    } else {
                        currentItem.removeClass('crt-sub-open');
                        subMenuAnimation( childrenSub, false );
                    }
                });

                // Deep Subs
                subMenuDeep.on( 'click', function(e) {
                    var currentItem = $(this),
                        childrenSub = currentItem.children('.crt-sub-menu');

                    // Reset
                    if ( $navMenu.hasClass('crt-nav-menu-horizontal') ) {
                        subMenuAnimation( subMenuDeep.find('.crt-sub-menu'), false );
                    }

                    if ( ! currentItem.hasClass( 'crt-sub-open' ) ) {
                        e.preventDefault();
                        currentItem.addClass('crt-sub-open');
                        subMenuAnimation( childrenSub, true );

                    } else {
                        currentItem.removeClass('crt-sub-open');
                        subMenuAnimation( childrenSub, false );
                    }
                });

                // Reset Subs on Document click
                $( document ).mouseup(function (e) {
                    if ( ! subMenuFirst.is(e.target) && subMenuFirst.has(e.target).length === 0 ) {
                        subMenuFirst.not().removeClass('crt-sub-open');
                        subMenuAnimation( subMenuFirst.children('.crt-sub-menu, .crt-sub-mega-menu'), false );
                    }
                    if ( ! subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0 ) {
                        subMenuDeep.removeClass('crt-sub-open');
                        subMenuAnimation( subMenuDeep.children('.crt-sub-menu'), false );
                    }
                });

                // Hover
            } else {
                // Mouse Over
                subMenuFirst.on( 'mouseenter', function() {
                    subMenuAnimation( $(this).children('.crt-sub-menu, .crt-sub-mega-menu'), true );
                });

                subMenuDeep.on( 'mouseenter', function() {
                    subMenuAnimation( $(this).children('.crt-sub-menu'), true );
                });

                // Mouse Leave
                subMenuFirst.on( 'mouseleave', function() {
                    subMenuAnimation( $(this).children('.crt-sub-menu, .crt-sub-mega-menu'), false );
                });

                subMenuDeep.on( 'mouseleave', function() {
                    subMenuAnimation( $(this).children('.crt-sub-menu'), false );
                });
            }

            // Mobile Menu
            var mobileMenu = $mobileNavMenu.find( '.crt-mobile-nav-menu' );

            // Toggle Button
            $mobileNavMenu.find( '.crt-mobile-toggle' ).on( 'click', function(e) {

                if ( window.getComputedStyle($mobileNavMenu[0])['pointer-events'] === 'none' ) {
                    return;
                }

                // Change Toggle Text
                if ( ! $(this).hasClass('crt-mobile-toggle-open') ) {
                    $(this).addClass('crt-mobile-toggle-open');

                    if ( $(this).find('.crt-mobile-toggle-text').length ) {
                        $(this).children().eq(0).hide();
                        $(this).children().eq(1).show();
                    }
                } else {
                    $(this).removeClass('crt-mobile-toggle-open');
                    $(this).trigger('focusout');

                    if ( $(this).find('.crt-mobile-toggle-text').length ) {
                        $(this).children().eq(1).hide();
                        $(this).children().eq(0).show();
                    }
                }

                // Show Menu
                if ( $scope.hasClass('crt-mobile-menu-display-offcanvas') ) {
                    $(this).closest('.elementor-top-section').addClass('crt-section-full-height');
                    $('body').css('overflow', 'hidden');
                    $(this).parent().siblings('.crt-mobile-mega-menu-wrap').toggleClass('crt-mobile-mega-menu-open');
                } else {
                    $(this).parent().siblings('.crt-mobile-mega-menu-wrap').stop().slideToggle();
                }

                // Hide Off-Canvas Menu
                $scope.find('.mobile-mega-menu-close').on('click', function() {
                    $(this).closest('.crt-mobile-mega-menu-wrap').removeClass('crt-mobile-mega-menu-open');
                    $('body').css('overflow', 'visible');
                    $(this).closest('.elementor-top-section').removeClass('crt-section-full-height');
                });
                $scope.find('.crt-mobile-mega-menu-overlay').on('click', function() {
                    $(this).siblings('.crt-mobile-mega-menu-wrap').removeClass('crt-mobile-mega-menu-open');
                    $('body').css('overflow', 'visible');
                    $(this).closest('.elementor-top-section').removeClass('crt-section-full-height');
                });

                // Fix Width
                fullWidthMobileDropdown();
            });

            // Sub Menu Class
            mobileMenu.find('.sub-menu').removeClass('crt-sub-menu').addClass('crt-mobile-sub-menu');

            // Add Submenu Icon
            let mobileSubIcon = mobileMenu.find('.crt-mobile-sub-icon'),
                mobileSubIconClass = 'fas ';

            if ( $scope.hasClass('crt-sub-icon-caret-down') ) {
                mobileSubIconClass += 'fa-caret-down';
            } else if ( $scope.hasClass('crt-sub-icon-angle-down') ) {
                mobileSubIconClass += 'fa-angle-down';
            } else if ( $scope.hasClass('crt-sub-icon-chevron-down') ) {
                mobileSubIconClass += 'fa-chevron-down';
            } else if ( $scope.hasClass('crt-sub-icon-plus') ) {
                mobileSubIconClass += 'fa-plus';
            }

            mobileSubIcon.addClass(mobileSubIconClass);

            // Sub Menu Dropdown
            mobileMenu.find('.menu-item-has-children > a .crt-mobile-sub-icon, .menu-item-has-children > a[href="#"]').on( 'click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                var parentItem = $(this).closest('li.menu-item');

                // Toggle
                if ( ! parentItem.hasClass('crt-mobile-sub-open') ) {
                    e.preventDefault();
                    parentItem.addClass('crt-mobile-sub-open');

                    if ( ! $scope.hasClass('crt-mobile-menu-display-offcanvas') ) {
                        $(window).trigger('resize');
                        parentItem.children('.crt-mobile-sub-menu').first().stop().slideDown();
                    }

                    // Mega Menu
                    if ( parentItem.hasClass('crt-mega-menu-true') ) {
                        if ( parentItem.hasClass('crt-mega-menu-ajax') && ! parentItem.find('.crt-mobile-sub-mega-menu').find('.elementor').length  ) {
                            let subIcon = parentItem.find('.crt-mobile-sub-icon');

                            $.ajax({
                                type: 'GET',
                                url: CRTConfig.resturl + '/crtmegamenu/',
                                data: {
                                    item_id: parentItem.data('id')
                                },
                                beforeSend:function() {
                                    subIcon.removeClass(mobileSubIconClass).addClass('fas fa-circle-notch fa-spin');
                                },
                                success: function( response ) {
                                    subIcon.removeClass('fas fa-circle-notch fa-spin').addClass(mobileSubIconClass);

                                    if ( $scope.hasClass('crt-mobile-menu-display-offcanvas') ) {
                                        parentItem.find('.crt-menu-offcanvas-back').after(response);
                                        offCanvasSubMenuAnimation( parentItem );
                                    } else {
                                        parentItem.find('.crt-mobile-sub-mega-menu').html(response);
                                        parentItem.children('.crt-mobile-sub-mega-menu').slideDown();
                                    }

                                    parentItem.find('.crt-mobile-sub-mega-menu').find('.elementor-element').each(function() {
                                        elementorFrontend.elementsHandler.runReadyTrigger($(this));
                                    });
                                }
                            });
                        } else {
                            if ( $scope.hasClass('crt-mobile-menu-display-offcanvas') ) {
                                offCanvasSubMenuAnimation( parentItem );
                            } else {
                                parentItem.children('.crt-mobile-sub-mega-menu').slideDown();
                            }
                        }
                    } else {
                        if (  $scope.hasClass('crt-mobile-menu-display-offcanvas') ) {
                            offCanvasSubMenuAnimation( parentItem );
                        }
                    }

                } else {
                    // SlideUp
                    parentItem.removeClass('crt-mobile-sub-open');

                    if ( ! $scope.hasClass('crt-mobile-menu-display-offcanvas') ) {
                        parentItem.children('.crt-mobile-sub-menu').slideUp();
                        parentItem.children('.crt-mobile-sub-mega-menu').slideUp();
                    }
                }
            });

            // Off-Canvas Back Button
            $scope.find('.crt-menu-offcanvas-back').on('click', function() {
                $(this).closest('.crt-mobile-mega-menu').removeClass('crt-mobile-sub-offcanvas-open');
                $(this).closest('.menu-item').removeClass('crt-mobile-sub-open');
                $scope.find('.crt-mobile-mega-menu-wrap').removeAttr('style');
                $scope.find('.crt-mobile-sub-mega-menu').removeAttr('style');
            });

            // Run Functions
            MegaMenuCustomWidth();
            fullWidthMobileDropdown();

            // Run Functions on Resize
            $(window).smartresize(function() {
                MegaMenuCustomWidth();
                fullWidthMobileDropdown();
            });

            // Mega Menu Full or Custom Width
            function MegaMenuCustomWidth() {
                let megaItem = $scope.find('.crt-mega-menu-true');

                megaItem.each(function() {
                    let megaSubMenu = $(this).find('.crt-sub-mega-menu')

                    if ( $(this).hasClass('crt-mega-menu-width-full') ) {
                        megaSubMenu.css({
                            'max-width' : $(window).width() +'px',
                            'left' : - $scope.find('.crt-nav-menu-container').offset().left +'px'
                        });	// conditions for sticky replace needed
                    } else if ( $(this).hasClass('crt-mega-menu-width-stretch') ) {
                        // Sections (Old)
                        if ( 0 === $(this).closest('.e-con').length ) {
                            var elContainer = $(this).closest('.elementor-section');
                            elContainer = elContainer.hasClass('elementor-inner-section') ? elContainer : elContainer.children('.elementor-container');

                            var elWidgetGap = !elContainer.hasClass('elementor-inner-section') ? elContainer.find('.elementor-element-populated').css('padding') : '0';
                            elWidgetGap = parseInt(elWidgetGap.replace('px', ''), 10);

                            // Container (New)
                        } else {
                            var elContainer = $(this).closest('.e-con-inner');

                            var elWidgetGap = elContainer.find('.elementor-element.e-con').css('padding'),
                                elWidgetGap = parseInt(elWidgetGap, 10);
                        }

                        if ( elContainer.length === 0 ) {
                            return;
                        }

                        var elContainerWidth = elContainer.outerWidth() - (elWidgetGap * 2),
                            offsetLeft = -($scope.offset().left - elContainer.offset().left) + elWidgetGap;

                        megaSubMenu.css({
                            'width' : elContainerWidth +'px',
                            'left' : offsetLeft +'px'
                        });
                    } else if ( $(this).hasClass('crt-mega-menu-width-custom') ) {
                        megaSubMenu.css({
                            'width' : $(this).data('custom-width') +'px',
                        });
                    } else if ( $(this).hasClass('crt-mega-menu-width-default') && $(this).hasClass('crt-mega-menu-pos-relative') ) {
                        megaSubMenu.css({
                            'width' : $(this).closest('.elementor-column').outerWidth() +'px',
                        });
                    }
                });
            }

            // Full Width Dropdown
            function fullWidthMobileDropdown() {
                if ( ! $scope.hasClass( 'crt-mobile-menu-full-width' ) || (! $scope.closest('.elementor-column').length && ! $scope.closest('.e-con').length) ) {
                    return;
                }

                var topSection = $scope.closest('.elementor-top-section').length ? $scope.closest('.elementor-top-section') : $scope.closest('.e-con-inner');

                var eColumn   =$scope.closest('.elementor-column').length ? $scope.closest('.elementor-column') : $scope.closest('.elementor-element'),
                    mWidth 	  = topSection.outerWidth() - 2 * mobileMenu.offset().left,
                    mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

                if ( topSection.hasClass('e-con-inner') ) {
                    mPosition = eColumn.offset().left - 2 * parseInt(topSection.parent().css('padding-left'), 10);
                    mWidth = topSection.outerWidth() - 2 * parseInt(topSection.parent().css('padding-left'), 10);
                }

                mobileMenu.parent('div').css({
                    'width' : mWidth +'px',
                    'left' : - mPosition +'px'
                });
            }

            // Sub Menu Animation
            function subMenuAnimation( selector, show ) {
                if ( show === true ) {
                    selector.stop().addClass('crt-animate-sub');
                } else {
                    selector.stop().removeClass('crt-animate-sub');
                }
            }

            // Off-Canvas Sub Menu Animation
            function offCanvasSubMenuAnimation( selector ) {
                let title = selector.children('a').clone().children().remove().end().text();

                selector.closest('.crt-mobile-mega-menu').addClass('crt-mobile-sub-offcanvas-open');

                if (selector.find('.crt-menu-offcanvas-back').find('h3').length > 0) {
                    selector.find('.crt-menu-offcanvas-back').find('h3').text(title);
                } else {
                    selector.find('.crt-menu-offcanvas-back').append('<h3></h3>').find('h3').text(title);
                }

                let parentItem = $scope.find('.crt-mobile-mega-menu').children('.crt-mobile-sub-open'),
                    subSelector = parentItem.children('ul').length ? parentItem.children('ul') : parentItem.children('.crt-mobile-sub-mega-menu'),
                    subHeight = subSelector.outerHeight();

                if ( subHeight > $(window).height() ) {
                    $scope.find('.crt-mobile-sub-mega-menu').not(selector.find('.crt-mobile-sub-mega-menu')).hide();
                    $scope.find('.crt-mobile-mega-menu-wrap').css('overflow-y', 'scroll');
                }
            }
        });
    });
})(jQuery);


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