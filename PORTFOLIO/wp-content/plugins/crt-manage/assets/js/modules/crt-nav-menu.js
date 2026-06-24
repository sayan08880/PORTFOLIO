(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-nav-menu.default',function($scope) {

            var $navMenu = $scope.find( '.crt-nav-menu-container' ),
                $mobileNavMenu = $scope.find( '.crt-mobile-nav-menu-container' );

            // Menu
            var subMenuFirst = $navMenu.find( '.crt-nav-menu > li.menu-item-has-children' ),
                subMenuDeep = $navMenu.find( '.crt-sub-menu li.menu-item-has-children' );

            if ( $scope.find('.crt-mobile-toggle').length ) {
                $scope.find('a').on('click', function() {
                    if (this.pathname == window.location.pathname && !($(this).parent('li').children().length > 1)) {
                        $scope.find('.crt-mobile-toggle').trigger('click');
                    }
                });
            }

            if ( $navMenu.attr('data-trigger') === 'click' ) {
                // First Sub
                subMenuFirst.children('a').on( 'click', function(e) {
                    var currentItem = $(this).parent(),
                        childrenSub = currentItem.children('.crt-sub-menu');

                    // Reset
                    subMenuFirst.not(currentItem).removeClass('crt-sub-open');
                    if ( $navMenu.hasClass('crt-nav-menu-horizontal') || ( $navMenu.hasClass('crt-nav-menu-vertical') && $scope.hasClass('crt-sub-menu-position-absolute') ) ) {
                        subMenuAnimation( subMenuFirst.children('.crt-sub-menu'), false );
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
                        subMenuAnimation( subMenuFirst.children('.crt-sub-menu'), false );
                    }
                    if ( ! subMenuDeep.is(e.target) && subMenuDeep.has(e.target).length === 0 ) {
                        subMenuDeep.removeClass('crt-sub-open');
                        subMenuAnimation( subMenuDeep.children('.crt-sub-menu'), false );
                    }
                });
            } else {
                // Mouse Over
                subMenuFirst.on( 'mouseenter', function() {
                    if ( $navMenu.hasClass('crt-nav-menu-vertical') && $scope.hasClass('crt-sub-menu-position-absolute') ) {
                        $navMenu.find('li').not(this).children('.crt-sub-menu').hide();
                        // BUGFIX: when menu is vertical and absolute positioned, lvl2 depth sub menus wont show properly on hover
                    }

                    subMenuAnimation( $(this).children('.crt-sub-menu'), true );
                });

                // Deep Subs
                subMenuDeep.on( 'mouseenter', function() {
                    subMenuAnimation( $(this).children('.crt-sub-menu'), true );
                });


                // Mouse Leave
                if ( $navMenu.hasClass('crt-nav-menu-horizontal') ) {
                    subMenuFirst.on( 'mouseleave', function() {
                        subMenuAnimation( $(this).children('.crt-sub-menu'), false );
                    });

                    subMenuDeep.on( 'mouseleave', function() {
                        subMenuAnimation( $(this).children('.crt-sub-menu'), false );
                    });
                } else {

                    $navMenu.on( 'mouseleave', function() {
                        subMenuAnimation( $(this).find('.crt-sub-menu'), false );
                    });
                }
            }


            // Mobile Menu
            var mobileMenu = $mobileNavMenu.find( '.crt-mobile-nav-menu' );

            // Toggle Button
            $mobileNavMenu.find( '.crt-mobile-toggle' ).on( 'click', function() {
                $(this).toggleClass('crt-mobile-toggle-fx');

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
                $(this).parent().next().stop().slideToggle();

                // Fix Width
                fullWidthMobileDropdown();
            });

            // Sub Menu Class
            mobileMenu.find('.sub-menu').removeClass('crt-sub-menu').addClass('crt-mobile-sub-menu');

            // Sub Menu Dropdown
            mobileMenu.find('.menu-item-has-children').children('a').on( 'click', function(e) {
                var parentItem = $(this).closest('li');

                // Toggle
                if ( ! parentItem.hasClass('crt-mobile-sub-open') ) {
                    e.preventDefault();
                    parentItem.addClass('crt-mobile-sub-open');
                    parentItem.children('.crt-mobile-sub-menu').first().stop().slideDown();
                } else {
                    parentItem.removeClass('crt-mobile-sub-open');
                    parentItem.children('.crt-mobile-sub-menu').first().stop().slideUp();
                }
            });

            // Run Functions
            fullWidthMobileDropdown();

            // Run Functions on Resize
            $(window).smartresize(function() {
                fullWidthMobileDropdown();
            });

            // Full Width Dropdown
            function fullWidthMobileDropdown() {
                if ( ! $scope.hasClass( 'crt-mobile-menu-full-width' ) || (! $scope.closest('.elementor-column').length && ! $scope.closest('.e-con').length) ) {
                    return;
                }

                // GOGA: maybe in some cases elementor-element instead of e-con
                var topSection = $scope.closest('.elementor-top-section');

                var eColumn   = $scope.closest('.elementor-column').length ? $scope.closest('.elementor-column') : $scope.closest('.elementor-element'),
                    mWidth 	  = topSection.length ? (topSection.outerWidth() - 2 * mobileMenu.offset().left) : ($(window).outerWidth() - 2 * mobileMenu.offset().left),
                    mPosition = eColumn.offset().left + parseInt(eColumn.css('padding-left'), 10);

                // GOGA: don't need to calculate mWidth since it has tu be full
                mobileMenu.css({
                    'width' : mWidth +'px',
                    'left' : - mPosition +'px'
                });
            }

            // Sub Menu Animation
            function subMenuAnimation( selector, show ) {
                if ( show === true ) {
                    selector.prev().attr('aria-expanded', 'true');
                    if ( $scope.hasClass('crt-sub-menu-fx-slide') ) {
                        selector.stop().slideDown();
                    } else {
                        selector.stop().fadeIn();
                    }
                } else {
                    selector.prev().attr('aria-expanded', 'false');
                    if ( $scope.hasClass('crt-sub-menu-fx-slide') ) {
                        selector.stop().slideUp();
                    } else {
                        selector.stop().fadeOut();
                    }
                }
            }
        });
    });
})(jQuery);