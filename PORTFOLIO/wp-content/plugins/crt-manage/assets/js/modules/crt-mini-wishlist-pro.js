(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-mini-wishlist-pro.default',function($scope) {


            if ( !($scope.find('.crt-wishlist-count').length > 0 && 0 == $scope.find('.crt-wishlist-count').text()) ) {
                $scope.find('.crt-wishlist-count').css('display', 'inline-flex');
            } else {

            }

            function wishlistRemoveHandler() {
                $scope.find('.crt-wishlist-remove').on('click', function(e) {
                    e.preventDefault();
                    var product_id = $(this).data('product-id');
                    $.ajax({
                        url: CRTConfig.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'remove_from_wishlist',
                            nonce: CRTConfig.nonce,
                            product_id: product_id
                        },
                        success: function() {
                            $scope.find('.crt-wishlist-product[data-product-id="' + product_id + '"]').remove();
                            localStorage.setItem('changeActionTargetProductId', product_id);
                            $(document).trigger('removed_from_wishlist');
                        }
                    });
                });
            }

            wishlistRemoveHandler();

            var mutationObserver = new MutationObserver(function(mutations) {
                wishlistRemoveHandler();
            });

            mutationObserver.observe($scope[0], {
                childList: true,
                subtree: true,
            });

            $.ajax({
                url: CRTConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'count_wishlist_items',
                },
                success: function(response) {
                    if ( $scope.find('.crt-wishlist-count').css('display') == 'none' && 0 < response.wishlist_count ) {
                        $scope.find('.crt-wishlist-count').text(response.wishlist_count);
                        $scope.find('.crt-wishlist-count').css('display', 'inline-flex');
                    } else if ( 0 == response.wishlist_count ) {
                        $scope.find('.crt-wishlist-count').css('display', 'none');
                        $scope.find('.crt-wishlist-count').text(response.wishlist_count);
                    } else {
                        $scope.find('.crt-wishlist-count').text(response.wishlist_count);
                    }

                    if ( true ) {
                        // Get all elements with the class 'crt-wishlist-product' and their product IDs
                        var productElements = $scope.find('.crt-wishlist-product');
                        var productIds = productElements.map(function() {
                            return $(this).data('product-id');
                        }).get();

                        // Filter out the items in the response that match the product IDs
                        var newWishlistItems = response.wishlist_items.filter(function(item) {
                            return !productIds.includes(item.product_id);
                        });

                        // Convert the wishlist_items to an array of product_ids for easier searching
                        var wishlistProductIds = response.wishlist_items.map(function(item) {
                            return item.product_id;
                        });

                        productElements.each(function() {
                            var productId = $(this).data('product-id');

                            // If the product ID is not in the wishlistProductIds array, remove the element
                            if (!wishlistProductIds.includes(productId)) {
                                $(this).remove();
                            }
                        });

                        newWishlistItems.forEach(function(item) {
                            $scope.find('.crt-wishlist-products').append('<li class="crt-wishlist-product" data-product-id="'+ item.product_id +'"><a class="crt-wishlist-product-img" href="'+ item.product_url +'">'+ item.product_image +'</a><div><a href="'+ item.product_url +'">'+ item.product_title +'</a><div class="crt-wishlist-product-price">'+ item.product_price +'</div></div><span class="crt-wishlist-remove" data-product-id="'+ item.product_id +'"></span></li>');
                        });
                    }
                }
            });

            $(document).on('added_to_wishlist', function() {
                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'update_mini_wishlist',
                        product_id: localStorage.getItem('changeActionTargetProductId'),
                    },
                    success: function(response) {
                        if ( $scope.find('.crt-wishlist-products').find('li[data-product-id='+ response.product_id +']').length == 0 ) {
                            $scope.find('.crt-wishlist-products').append('<li class="crt-wishlist-product" data-product-id="'+ response.product_id +'"><a class="crt-wishlist-product-img" href="'+ response.product_url +'">'+ response.product_image +'</a><div><a href="'+ response.product_url +'">'+ response.product_title +'</a><div class="crt-wishlist-product-price">'+ response.product_price +'</div></div><span class="crt-wishlist-remove" data-product-id="'+ response.product_id +'"></span></li>');
                        }

                        $scope.find('.crt-wishlist-count').text(response.wishlist_count);
                        $scope.find('.crt-wishlist-count').css('display', 'inline-flex');
                    }
                });
            });

            $(document).on('removed_from_wishlist', function() {
                $scope.find('.crt-wishlist-product[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').remove();
                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'update_mini_wishlist',
                        product_id: localStorage.getItem('changeActionTargetProductId'),
                    },
                    success: function(response) {
                        $scope.find('.crt-wishlist-count').text(response.wishlist_count);

                        if ( response.wishlist_count == 0 ) {
                            $scope.find('.crt-wishlist-count').css('display', 'none');
                        } else {
                            $scope.find('.crt-wishlist-count').css('display', 'inline-flex');
                        }
                    }
                });
            });
            $scope.find('.crt-wishlist').css({"display": "none"});

            var animationSpeed = $scope.find('.crt-wishlist-wrap').data('animation');

            $('body').on('click', function(e) {
                if ( !e.target.classList.value.includes('crt-wishlist-wrap') && !e.target.closest('.crt-wishlist-wrap') ) {
                    if ( $scope.hasClass('crt-wishlist-slide') ) {
                        $scope.find('.crt-wishlist').slideUp(animationSpeed);
                    } else {
                        $scope.find('.crt-wishlist').fadeOut(animationSpeed);
                    }
                }
            });

            if ( 0 !== $scope.hasClass('crt-wishlist-sidebar').length ) {
                if ( $('#wpadminbar').length ) {
                    $scope.find('.crt-wishlist').css({
                        // 'top': $('#wpadminbar').css('height'),
                        // 'height': $scope.find('.crt-shopping-cart-wrap').css('height') -  $('#wpadminbar').css('height')
                        'z-index': 999999
                    });
                }

                closeSideBar();

                $scope.find('.crt-wishlist').on('click', function(e) {
                    // if ( !e.target.classList.value.includes('widget_shopping_cart_content') && !e.target.closest('.widget_shopping_cart_content') ) {
                    if ( !e.target.classList.value.includes('crt-wishlist-inner-wrap') && !e.target.closest('.crt-wishlist-inner-wrap') ) {
                        // $scope.find('.widget_shopping_cart_content').addClass('crt-mini-cart-slide-out');
                        $scope.find('.crt-wishlist-inner-wrap').addClass('crt-wishlist-slide-out');
                        $scope.find('.crt-wishlist-slide-out').css('animation-speed', animationSpeed);
                        $scope.find('.crt-wishlist').fadeOut(animationSpeed);
                        $('body').removeClass('crt-wishlist-sidebar-body');
                        setTimeout(function() {
                            // $scope.find('.widget_shopping_cart_content').removeClass('crt-mini-cart-slide-out');
                            $scope.find('.crt-wishlist-inner-wrap').removeClass('crt-wishlist-slide-out');
                            $scope.find('.crt-wishlist').css({"display": "none"});
                        }, animationSpeed + 100);
                    }
                });
            }

            if ( $scope.find('.crt-wishlist').length ) {
                if ( $scope.hasClass('crt-wishlist-sidebar') || $scope.hasClass('crt-wishlist-dropdown') ) {
                    $scope.find('.crt-wishlist-toggle-btn').on('click', function(e) {
                        e.stopPropagation();
                        e.preventDefault();
                        if ( 'none' === $scope.find('.crt-wishlist').css("display") ) {
                            if ( $scope.hasClass('crt-wishlist-slide') ) {
                                $scope.find('.crt-wishlist').slideDown(animationSpeed);
                            } else {
                                $scope.find('.crt-wishlist').fadeIn(animationSpeed);
                            }
                            if ( $scope.hasClass('crt-wishlist-sidebar') ) {
                                $scope.find('.crt-wishlist').fadeIn(animationSpeed);
                                $scope.find('.crt-wishlist-inner-wrap').addClass('crt-wishlist-slide-in');
                                $scope.find('.crt-wishlist-slide-in').css('animation-speed', animationSpeed);
                                $('body').addClass('crt-wishlist-sidebar-body');
                            }
                            setTimeout(function() {
                                // $scope.find('.widget_shopping_cart_content').removeClass('crt-mini-cart-slide-in');
                                $scope.find('.crt-wishlist').removeClass('crt-wishlist-slide-in');
                                if ( $scope.hasClass('crt-wishlist-sidebar') ) {
                                    $scope.find('.crt-wishlist').trigger('resize');
                                }
                            }, animationSpeed + 100);
                        } else {
                            if ( $scope.hasClass('crt-wishlist-slide') ) {
                                $scope.find('.crt-wishlist').slideUp(animationSpeed);
                            } else {
                                $scope.find('.crt-wishlist').fadeOut(animationSpeed);
                            }
                        }
                    });
                }
            }

            var mutationObserver = new MutationObserver(function(mutations) {
                if (  0 !== $scope.hasClass('crt-wishlist-sidebar').length ) {
                    closeSideBar();
                }

                $scope.find('.crt-wishlist-product').on('click', '.crt-wishlist-remove', function() {
                    $(this).closest('li').addClass('crt-before-remove-from-wishlist');
                });

                if ( $scope.find('.crt-wishlist-product').length !== 0 ) {
                    $scope.find('.crt-wishlist-empty').addClass('crt-wishlist-empty-hidden');
                    $scope.find('.crt-view-wishlist').removeClass('crt-hidden-element');
                } else {
                    $scope.find('.crt-wishlist-empty').removeClass('crt-wishlist-empty-hidden');
                    $scope.find('.crt-view-wishlist').addClass('crt-hidden-element');
                }
            });

            // Listen to Mini Cart Changes
            mutationObserver.observe($scope[0], {
                childList: true,
                subtree: true,
            });

            function closeSideBar() {
                $scope.find('.crt-close-wishlist span').on('click', function(e) {
                    // $scope.find('.widget_shopping_cart_content').addClass('crt-mini-cart-slide-out');
                    $scope.find('.crt-wishlist-inner-wrap').addClass('crt-wishlist-slide-out');
                    $scope.find('.crt-wishlist-slide-out').css('animation-speed', animationSpeed);
                    $scope.find('.crt-wishlist').fadeOut(animationSpeed);
                    $('body').removeClass('crt-wishlist-sidebar-body');
                    setTimeout(function() {
                        // $scope.find('.widget_shopping_cart_content').removeClass('crt-mini-cart-slide-out');
                        $scope.find('.crt-wishlist-inner-wrap').removeClass('crt-wishlist-slide-out');
                        $scope.find('.crt-wishlist').css({"display": "none"});
                    }, animationSpeed + 100);
                });
            }
        });
    });
})(jQuery);