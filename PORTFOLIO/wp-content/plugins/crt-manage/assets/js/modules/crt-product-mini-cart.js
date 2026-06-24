(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-product-mini-cart.default',function($scope) {

            window.addEventListener('pageshow', function(event) {
                if (event.persisted || (window.performance && window.performance.navigation.type === 2)) {
                    updateMiniCart();
                }
            });

            $( document.body ).on( 'updated_wc_div', function() {
                updateMiniCart();
            });

            // function updateVH() {
            // 	let vh = window.innerHeight * 0.01;
            // 	document.documentElement.style.setProperty('--vh', `${vh}px`);
            // }

            // // Run the function initially
            // updateVH();

            // // Recalculate on window resize (for when the user rotates the device or resizes the viewport)
            // window.addEventListener('resize', updateVH);


            function updateMiniCart() {
                $.ajax({
                    url: wc_add_to_cart_params.ajax_url,
                    type: 'POST',
                    data: {
                        action: 'woocommerce_get_refreshed_fragments'
                    },
                    success: function(response) {
                        if (response && response.fragments) {
                            $.each(response.fragments, function(key, value) {
                                $(key).replaceWith(value);
                            });

                            $(document.body).trigger('wc_fragments_refreshed');
                        }
                    }
                });
            }

            $scope.find('.crt-mini-cart').css({"display": "none"});
            var animationSpeed = $scope.find('.crt-mini-cart-wrap').data('animation');

            $('body').on('click', function(e) {
                if ( !e.target.classList.value.includes('crt-mini-cart') && !e.target.closest('.crt-mini-cart') ) {
                    $scope.find('.crt-mini-cart-toggle-btn').closest('.elementor>.elementor-element').removeClass('crt-z-index');
                    if ( $scope.hasClass('crt-mini-cart-slide') ) {
                        $scope.find('.crt-mini-cart').slideUp(animationSpeed);
                    } else {
                        $scope.find('.crt-mini-cart').fadeOut(animationSpeed);
                    }
                }

                if ( e.target.classList.value.includes('crt-shopping-cart-wrap') ) {
                    $scope.find('.crt-mini-cart-toggle-btn').closest('.elementor>.elementor-element').removeClass('crt-z-index');
                }
            });

            if ( $scope.hasClass('crt-mini-cart-sidebar') ) {
                if ( $('#wpadminbar').length ) {
                    $scope.find('.crt-mini-cart').css({
                        // 'top': $('#wpadminbar').css('height'),
                        // 'height': $scope.find('.crt-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
                        'z-index': 999999
                    });
                }

                closeSideBar();

                $scope.find('.crt-shopping-cart-wrap').on('click', function(e) {
                    // if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
                    if ( !e.target.classList.value.includes('crt-shopping-cart-inner-wrap') && !e.target.closest('.crt-shopping-cart-inner-wrap') ) {
                        // $scope.find('.widget_shopping_cart_content').addClass('crt-mini-cart-slide-out');
                        $scope.find('.crt-shopping-cart-inner-wrap').addClass('crt-mini-cart-slide-out');
                        $scope.find('.crt-mini-cart-slide-out').css('animation-speed', animationSpeed);
                        $scope.find('.crt-shopping-cart-wrap').fadeOut(animationSpeed);
                        $('body').removeClass('crt-mini-cart-sidebar-body');
                        setTimeout(function() {
                            // $scope.find('.widget_shopping_cart_content').removeClass('crt-mini-cart-slide-out');
                            $scope.find('.crt-shopping-cart-inner-wrap').removeClass('crt-mini-cart-slide-out');
                            $scope.find('.crt-mini-cart').css({"display": "none"});
                        }, animationSpeed + 100);
                    }
                });
            }

            if ( $scope.find('.crt-mini-cart').length ) {
                if ( $scope.hasClass('crt-mini-cart-sidebar') || $scope.hasClass('crt-mini-cart-dropdown') ) { //
                    $scope.find('.crt-mini-cart-toggle-btn').on('click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        if ( 'none' === $scope.find('.crt-mini-cart').css("display") ) {
                            $(this).closest('.elementor>.elementor-element').addClass('crt-z-index');
                            if ( $scope.hasClass('crt-mini-cart-slide') ) {
                                $scope.find('.crt-mini-cart').slideDown(animationSpeed);
                            } else {
                                $scope.find('.crt-mini-cart').fadeIn(animationSpeed);
                            }
                            if ( $scope.hasClass('crt-mini-cart-sidebar') ) {
                                $scope.find('.crt-shopping-cart-wrap').fadeIn(animationSpeed);
                                // $scope.find('.widget_shopping_cart_content').addClass('crt-mini-cart-slide-in');
                                $scope.find('.crt-shopping-cart-inner-wrap').addClass('crt-mini-cart-slide-in');
                                $scope.find('.crt-mini-cart-slide-in').css('animation-speed', animationSpeed);
                                $('body').addClass('crt-mini-cart-sidebar-body');
                            } else if ( $scope.hasClass('crt-mini-cart-dropdown') ) {
                                $scope.find('.crt-shopping-cart-wrap').css('display', 'block');
                            }
                            setTimeout(function() {
                                // $scope.find('.widget_shopping_cart_content').removeClass('crt-mini-cart-slide-in');
                                $scope.find('.crt-shopping-cart-inner-wrap').removeClass('crt-mini-cart-slide-in');
                                if ( $scope.hasClass('crt-mini-cart-sidebar') ) {
                                    $scope.find('.crt-woo-mini-cart').trigger('resize');
                                }
                            }, animationSpeed + 100);
                        } else {
                            $(this).closest('.elementor>.elementor-element').removeClass('crt-z-index');
                            if ( $scope.hasClass('crt-mini-cart-slide') ) {
                                $scope.find('.crt-mini-cart').slideUp(animationSpeed);
                            } else {
                                $scope.find('.crt-mini-cart').fadeOut(animationSpeed);
                            }
                        }
                    });
                }
            }

            var mutationObserver = new MutationObserver(function(mutations) {
                if (  $scope.hasClass('crt-mini-cart-sidebar') ) {
                    closeSideBar();

                    // if ( $scope.find('.crt-mini-cart').data('close-cart-heading') ) {
                    // 	$scope.find('.crt-close-cart h2').text($scope.find('.crt-mini-cart').data('close-cart-heading').replace(/-/g, ' '));
                    // }
                }

                $scope.find('.woocommerce-mini-cart-item').on('click', '.crt-remove-item-from-mini-cart', function() {
                    $(this).closest('li').addClass('crt-before-remove-from-mini-cart');
                });
            });

            // Listen to Mini Cart Changes
            mutationObserver.observe($scope[0], {
                childList: true,
                subtree: true,
            });

            function closeSideBar() {
                $scope.find('.crt-close-cart span').on('click', function(e) {
                    // $scope.find('.widget_shopping_cart_content').addClass('crt-mini-cart-slide-out');
                    $scope.find('.crt-shopping-cart-inner-wrap').addClass('crt-mini-cart-slide-out');
                    $scope.find('.crt-mini-cart-slide-out').css('animation-speed', animationSpeed);
                    $scope.find('.crt-shopping-cart-wrap').fadeOut(animationSpeed);
                    $('body').removeClass('crt-mini-cart-sidebar-body');
                    setTimeout(function() {
                        // $scope.find('.widget_shopping_cart_content').removeClass('crt-mini-cart-slide-out');
                        $scope.find('.crt-shopping-cart-inner-wrap').removeClass('crt-mini-cart-slide-out');
                        $scope.find('.crt-mini-cart').css({"display": "none"});
                    }, animationSpeed + 100);
                    $scope.find('.crt-mini-cart-toggle-btn').closest('.elementor>.elementor-element').removeClass('crt-z-index');
                });
            }

        });
    });
})(jQuery);