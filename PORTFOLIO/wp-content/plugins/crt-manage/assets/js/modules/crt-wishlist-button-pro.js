(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-wishlist-button-pro.default',function($scope) {


            $.ajax({
                url: CRTConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'check_product_in_wishlist',
                    product_id: $scope.find('.crt-wishlist-add').data('product-id')
                },
                success: function(response) {
                    if ( true == response ) {
                        if ( !$scope.find('.crt-wishlist-add').hasClass('crt-button-hidden') ) {
                            $scope.find('.crt-wishlist-add').addClass('crt-button-hidden');
                        }

                        if ( $scope.find('.crt-wishlist-remove').hasClass('crt-button-hidden') ) {
                            $scope.find('.crt-wishlist-remove').removeClass('crt-button-hidden');
                        }
                    }
                }
            });

            $scope.find('.crt-wishlist-add').click(function(e) {
                e.preventDefault();
                var product_id = $(this).data('product-id');

                $(this).fadeTo(500, 0);

                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'add_to_wishlist',
                        nonce: CRTConfig.nonce,
                        product_id: product_id
                    },
                    success: function() {
                        $scope.find('.crt-wishlist-add[data-product-id="' + product_id + '"]').hide();
                        $scope.find('.crt-wishlist-remove[data-product-id="' + product_id + '"]').show();
                        $scope.find('.crt-wishlist-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
                        localStorage.setItem('changeActionTargetProductId', product_id);
                        $(document).trigger('added_to_wishlist');
                    },
                    error: function(response) {
                        var error_message = response.responseJSON.message;
                        // Display error message
                        alert(error_message);
                    }
                });
            });
            $scope.find('.crt-wishlist-remove').on('click', function(e) {
                e.preventDefault();
                var product_id = $(this).data('product-id');

                $(this).fadeTo(500, 0);

                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'remove_from_wishlist',
                        nonce: CRTConfig.nonce,
                        product_id: product_id
                    },
                    success: function() {
                        $scope.find('.crt-wishlist-remove[data-product-id="' + product_id + '"]').hide();
                        $scope.find('.crt-wishlist-add[data-product-id="' + product_id + '"]').show();
                        $scope.find('.crt-wishlist-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
                        localStorage.setItem('changeActionTargetProductId', product_id);
                        $(document).trigger('removed_from_wishlist');
                    }
                });
            });

            $(document).on('removed_from_wishlist', function() {
                $scope.find('.crt-wishlist-remove[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').hide();
                $scope.find('.crt-wishlist-add[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').show();
                $scope.find('.crt-wishlist-add[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').fadeTo(500, 1);
            });

        });
    });
})(jQuery);