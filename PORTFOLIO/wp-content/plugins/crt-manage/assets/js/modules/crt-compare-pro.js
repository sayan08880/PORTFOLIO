(function($) {
    "use strict";

    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-compare-pro.default',function($scope) {

            $.ajax({
                url: CRTConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'count_compare_items',
                    remove_text: $scope.find('.crt-compare-table-wrap').attr('remove_from_compare_text'),
                    compare_empty_text: $scope.find('.crt-compare-table-wrap').attr('compare_empty_text'),
                    element_addcart_simple_txt: $scope.find('.crt-compare-table-wrap').attr('element_addcart_simple_txt'),
                    element_addcart_grouped_txt: $scope.find('.crt-compare-table-wrap').attr('element_addcart_grouped_txt'),
                    element_addcart_variable_txt: $scope.find('.crt-compare-table-wrap').attr('element_addcart_variable_txt')
                },
                success: function(response) {
                    if ( true ) {
                        $scope.find('.crt-compare-table-wrap').html(response.compare_table);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });

            $scope.on('click', '.crt-compare-remove', function(e) {
                e.preventDefault();
                var productID = $(this).data('product-id');

                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'remove_from_compare',
                        nonce: CRTConfig.nonce,
                        product_id: productID
                    },
                    success: function() {
                        localStorage.setItem('changeActionTargetProductId', productID);
                        $scope.find('[data-product-id="' + productID + '"]').remove();
                        if ( !($scope.find('.crt-compare-remove').length > 0) ) {
                            $scope.find('.crt-compare-products').addClass('crt-hidden-element');
                            $scope.find('.crt-compare-empty').removeClass('crt-hidden-element');
                        } else {
                            $scope.find('.crt-compare-empty').addClass('crt-hidden-element');
                            $scope.find('.crt-compare-products').removeClass('crt-hidden-element');
                        }
                        $(document).trigger('removed_from_compare');
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