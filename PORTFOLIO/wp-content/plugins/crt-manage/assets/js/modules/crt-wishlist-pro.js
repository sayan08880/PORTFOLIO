(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-wishlist-pro.default',function($scope) {


            $.ajax({
                url: CRTConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'count_wishlist_items',
                    element_addcart_simple_txt: $scope.find('.crt-wishlist-products').attr('element_addcart_simple_txt'),
                    element_addcart_grouped_txt: $scope.find('.crt-wishlist-products').attr('element_addcart_grouped_txt'),
                    element_addcart_variable_txt: $scope.find('.crt-wishlist-products').attr('element_addcart_variable_txt')
                },
                success: function(response) {
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
                        var html = '<tr class="crt-wishlist-product" data-product-id="' + item.product_id + '">';
                        html += '<td><span class="crt-wishlist-remove" data-product-id="' + item.product_id + '"></span></td>';
                        html += '<td><a class="crt-wishlist-img-wrap" href="' + item.product_url + '">' + item.product_image + '</a></td>';
                        html += '<td><a class="crt-wishlist-product-name" href="' + item.product_url + '">' + item.product_title + '</a></td>';
                        html += '<td><div class="crt-wishlist-product-price">' + item.product_price + '</div></td>';
                        html += '<td><div class="crt-wishlist-product-status">' + item.product_stock + '</div></td>';
                        html += '<td><div class="crt-wishlist-product-atc">' + item.product_atc + '</div></td>';
                        html += '</tr>';
                        $scope.find('.crt-wishlist-products tbody').append(html);
                    });

                    if ( 0 < +response.wishlist_count ) {
                        if ( $scope.find('.crt-wishlist-products').hasClass('crt-wishlist-empty-hidden') ) {
                            $scope.find('.crt-wishlist-products').removeClass('crt-wishlist-empty-hidden');
                        }

                        if ( !$scope.find('.crt-wishlist-empty').hasClass('crt-wishlist-empty-hidden') ) {
                            $scope.find('.crt-wishlist-empty').addClass('crt-wishlist-empty-hidden');
                        }
                    } else {
                        if ( !$scope.find('.crt-wishlist-products').hasClass('crt-wishlist-empty-hidden') ) {
                            $scope.find('.crt-wishlist-products').addClass('crt-wishlist-empty-hidden');
                        }

                        if ( $scope.find('.crt-wishlist-empty').hasClass('crt-wishlist-empty-hidden') ) {
                            $scope.find('.crt-wishlist-empty').removeClass('crt-wishlist-empty-hidden');
                        }
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });

            $scope.on('click', '.crt-wishlist-remove', function(e) {
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
                    success: function(response) {
                        if ( 1 === $scope.find('.crt-wishlist-product').length ) {
                            if ( !$scope.find('.crt-wishlist-products').hasClass('crt-wishlist-empty-hidden') ) {
                                $scope.find('.crt-wishlist-products').addClass('crt-wishlist-empty-hidden');
                            }

                            if ( $scope.find('.crt-wishlist-empty').hasClass('crt-wishlist-empty-hidden') ) {
                                $scope.find('.crt-wishlist-empty').removeClass('crt-wishlist-empty-hidden');
                            }
                        }

                        $scope.find('.crt-wishlist-product[data-product-id="' + product_id + '"]').remove();
                        localStorage.setItem('changeActionTargetProductId', product_id);
                        $(document).trigger('removed_from_wishlist');
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });

            $( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
                button.next().fadeTo( 700, 1 );

                button.css('display', 'none');
            });
        });
    });
})(jQuery);