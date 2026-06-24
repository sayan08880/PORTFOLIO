(function($) {
    "use strict";

    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-compare-button-pro.default',function($scope) {


            $.ajax({
                url: CRTConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'check_product_in_compare',
                    product_id: $scope.find('.crt-compare-add').data('product-id')
                },
                success: function(response) {
                    if ( true == response ) {
                        if ( !$scope.find('.crt-compare-add').hasClass('crt-button-hidden') ) {
                            $scope.find('.crt-compare-add').addClass('crt-button-hidden');
                        }

                        if ( $scope.find('.crt-compare-remove').hasClass('crt-button-hidden') ) {
                            $scope.find('.crt-compare-remove').removeClass('crt-button-hidden');
                        }
                    }
                }
            });

            // $(document).ready(function() {
            $scope.find('.crt-compare-add').click(function(e) {
                e.preventDefault();
                var product_id = $(this).data('product-id');

                $(this).fadeTo(500, 0);

                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'add_to_compare',
                        nonce: CRTConfig.nonce,
                        product_id: product_id
                    },
                    success: function() {
                        $scope.find('.crt-compare-add[data-product-id="' + product_id + '"]').hide();
                        $scope.find('.crt-compare-remove[data-product-id="' + product_id + '"]').show();
                        $scope.find('.crt-compare-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
                        localStorage.setItem('changeActionTargetProductId', product_id);
                        $(document).trigger('added_to_compare');
                    },
                    error: function(response) {
                        var error_message = response.responseJSON.message;
                        // Display error message
                        alert(error_message);
                    }
                });
            });
            $scope.find('.crt-compare-remove').click(function(e) {
                e.preventDefault();
                var product_id = $(this).data('product-id');

                $(this).fadeTo(500, 0);

                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'remove_from_compare',
                        nonce: CRTConfig.nonce,
                        product_id: product_id
                    },
                    success: function() {
                        $scope.find('.crt-compare-remove[data-product-id="' + product_id + '"]').hide();
                        $scope.find('.crt-compare-add[data-product-id="' + product_id + '"]').show();
                        $scope.find('.crt-compare-add[data-product-id="' + product_id + '"]').fadeTo(500, 1);
                        localStorage.setItem('changeActionTargetProductId', product_id);
                        $(document).trigger('removed_from_compare');
                    }
                });
            });

            $(document).on('removed_from_compare', function() {
                $scope.find('.crt-compare-remove[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').hide();
                $scope.find('.crt-compare-add[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').show();
                $scope.find('.crt-compare-add[data-product-id="' + localStorage.getItem('changeActionTargetProductId') + '"]').fadeTo(500, 1);
            });

            // });
        });

        // var CrtElements = {
        //     changeActionTargetProductId: function(productId) {
        //         localStorage.getItem('changeActionTargetProductId') = productId;
        //     },
        // }
    });
})(jQuery);