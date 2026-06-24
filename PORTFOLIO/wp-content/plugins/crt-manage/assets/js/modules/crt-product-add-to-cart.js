(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-product-add-to-cart.default',function($scope) {
            var qtyInput = jQuery('.woocommerce .crt-quantity-wrapper'),
                qtyInputInStock = qtyInput.find('input.qty').attr('max') ? qtyInput.find('input.qty').attr('max') : 99999999,
                qtyLayout = $scope.find('.crt-product-add-to-cart').attr('layout-settings'),
                qtyWrapper = $scope.find('.crt-add-to-cart-icons-wrap'),
                plusIconChild = !$scope.find('.crt-add-to-cart-icons-wrap').length ? 'last-child' : 'first-child',
                minusIconChild = !$scope.find('.crt-add-to-cart-icons-wrap').length ? 'first-child' : 'last-child';

            if ( qtyWrapper.length === 0 ) {
                plusIconChild = 'last-child';
                minusIconChild = 'first-child';
            }

            if ( $scope.find('select').length > 0 ) {
                $scope.find('select').on('change', function() {
                    $scope.find('.reset_variations').css('visibility', 'visible');
                });

                $scope.find('.reset_variations').on('click', function() {
                    alert('Reset is Disabled in Editor. Please Preview this Page to see it in action.');
                });
            }

            $scope.find('input.qty').each(function() {
                if (!$(this).val()) {
                    $(this).val(0);
                }
            });

            $scope.find('.variations').find('select').on('change', function () {
                var resetButtonDisplay = false;
                $scope.find('.variations').find('select').each(function () {
                    if ( 'choose an option' !== $(this).find('option:selected').text().toLowerCase() ) {
                        resetButtonDisplay = true;
                    }
                });

                if ( resetButtonDisplay == false ) {
                    $scope.find('.reset_variations').css('display', 'none');
                } else {
                    $scope.find('.reset_variations').css('display', 'inline-block');
                }
            });

            // convert to text input
            if (qtyLayout !== 'default' ) {
                qtyInput.find('input.qty').attr('type', 'text').removeAttr('step').removeAttr('min').removeAttr('max');
            }

            // plus
            // Ensure event handlers are not attached multiple times
            $scope.off('click', 'i:' + plusIconChild).on('click', 'i:' + plusIconChild, function() {
                var $qtyInput = jQuery(this).prev('.quantity').find('input.qty');
                if ($qtyInput.length === 0) {
                    $qtyInput = jQuery(this).closest('.crt-quantity-wrapper').find('.quantity').find('input.qty');
                }

                var currentVal = parseInt($qtyInput.val(), 10);

                if (currentVal < qtyInputInStock && qtyLayout == 'both') {
                    $qtyInput.val(currentVal + 1);
                    $scope.find('input[name="update_cart"]').removeAttr('disabled');
                } else if (currentVal < qtyInputInStock && qtyLayout !== 'both' && qtyLayout !== 'default') {
                    $qtyInput.val(currentVal + 1);
                    $scope.find('input[name="update_cart"]').removeAttr('disabled');
                }
            });

            $scope.off('click', 'i:' + minusIconChild).on('click', 'i:' + minusIconChild, function() {
                var $qtyInput = jQuery(this).next('.quantity').find('input.qty');
                if ($qtyInput.length === 0) {
                    $qtyInput = jQuery(this).closest('.crt-quantity-wrapper').find('.quantity').find('input.qty');
                }

                var currentVal = parseInt($qtyInput.val(), 10);

                if (currentVal > 0 && qtyLayout == 'both') {
                    $qtyInput.val(currentVal - 1);
                    $scope.find('input[name="update_cart"]').removeAttr('disabled');
                } else if (currentVal > 0 && qtyLayout !== 'both' && qtyLayout !== 'default') {
                    $qtyInput.val(currentVal - 1);
                    $scope.find('input[name="update_cart"]').removeAttr('disabled');
                }
            });

            // in stock range check
            qtyInput.find('input.qty').keyup(function() {
                if ( jQuery(this).val() > qtyInputInStock ) {
                    jQuery(this).val( qtyInputInStock );
                }
            });

            var addToCartTimeout;
            // var isAddingToCart = false;
            $(document).ready(function () {
                if ( 'yes' === $scope.find('.crt-product-add-to-cart').data('ajax-add-to-cart') ) {
                    if ( !$('div[data-elementor-type="crt-theme-builder"]').hasClass('product-type-external') ) {
                        $scope.find('.single_add_to_cart_button').on('click', ajaxAddToCart);
                    }
                }
            });

            function ajaxAddToCart(e) {
                e.preventDefault();

                // If an AJAX request is already in progress, prevent another one
                // if (isAddingToCart) {
                // 	return;
                // }

                // Set the flag to indicate that an AJAX request is in progress
                // isAddingToCart = true;

                let $form = $( this ).closest('form');

                var $variationForm = $form.closest('.variations_form');

                let isGrouped = $form.hasClass('grouped_form');

                if ( ! $form[0].checkValidity() ) {
                    $form[0].reportValidity();

                    return false;
                }

                let $thisBtn = $( this ),
                    product_id = $thisBtn.val() || '',
                    cartFormData = $form.serialize();

                // // Get the ID of the selected variation
                // let variation_id = $scope.find('input[name="variation_id"]').val();
                // // Get the data of the selected variation
                // let variation_data = window['wc_variation_form'].variation_data[variation_id];

                // // Get the availability HTML of the selected variation
                // let availability_html = variation_data.availability_html;

                // // Check if the variation is in stock
                // if (availability_html.indexOf('In stock') !== -1) {
                // } else {
                // }

                if (isGrouped) {
                    let nonZero = false;
                    $scope.find('.woocommerce-grouped-product-list-item__quantity').find('input').each(function() {
                        if ($(this).val() > 0 ) {
                            nonZero = true;
                        }
                    });

                    if ( !nonZero ) {
                        // The grouped product does not have the required number of items selected
                        alert(CRTConfig.chooseQuantityText);
                        return;
                    }
                }

                $.ajax( {
                    type: 'POST',
                    url: CRTConfig.ajaxurl,
                    data: 'action=crt_addons_add_cart_single_product&add-to-cart=' + product_id + '&' + cartFormData,
                    beforeSend: function () {
                        if ( $variationForm.length > 0 && ! $variationForm.find('.variations select').val() ) {
                            // Do not trigger added_to_cart event if options are not selected for variable product
                            return;
                        }
                        if ( $thisBtn.hasClass('disabled') ) {
                            return
                        }

                        $thisBtn.removeClass( 'added' ).addClass( 'loading' );
                    },
                    complete: function () {
                        if ( $variationForm.length > 0 && ! $variationForm.find('.variations select').val() ) {
                            // Do not trigger added_to_cart event if options are not selected for variable product
                            return;
                        }

                        if ( $thisBtn.hasClass('disabled') ) {
                            return
                        }

                        $thisBtn.addClass( 'added' ).removeClass( 'loading' );
                    },
                    success: function ( response ) {

                        // GOGA - remove later
                        if (response.notices && response.notices.length > 0) {

                            // The selected variation is low in stock, display a warning message
                            if (response.notices[0].type === 'wc_low_stock') {
                                alert('Only ' + response.notices[0].message + ' left in stock!');
                            } else {
                                alert(response.notices[0].message);
                            }
                        }

                        if ( response.error && response.product_url ) {
                            window.location = response.product_url;
                            return;
                        }

                        if ( typeof wc_add_to_cart_params === 'undefined' ) {
                            return false;
                        }

                        if ( $variationForm.length > 0 && ! $variationForm.find('.variations select').val() ) {
                            // Do not trigger added_to_cart event if options are not selected for variable product
                            return;
                        }

                        if ( $thisBtn.hasClass('disabled') ) {
                            return;
                        }

                        $( document.body ).trigger( 'wc_fragment_refresh' );
                        $( document.body ).trigger( 'added_to_cart', [ response.fragments, response.cart_hash, $thisBtn ] );

                        // setTimeout( function () {
                        // 	$thisBtn.removeClass( 'added' );
                        // 	var currentCartCount = parseInt($('.crt-mini-cart-icon-count').text());
                        // 	var updatedCartCount = parseInt($scope.find('.crt-quantity-wrapper .qty').val());
                        //     console.log(currentCartCount, updatedCartCount);
                        // 	$('.crt-mini-cart-icon-count').text(currentCartCount + updatedCartCount);
                        // }, 1000 );
                    },
                } );

                $( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
                    // button.next().fadeTo( 700, 1 );

                    // button.css('display', 'none');
                });
            }
        });
    });
})(jQuery);