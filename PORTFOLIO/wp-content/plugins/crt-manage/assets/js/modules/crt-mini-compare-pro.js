(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-mini-compare-pro.default',function($scope) {

            // $scope.find('.crt-compare-text').click(function(e) {
            // 	e.preventDefault();
            // 	alert(CRTConfig.comparePageID);
            // 	$scope.find('.crt-compare-popup').removeClass('crt-compare-popup-hidden');
            // 	$.ajax({
            // 		// url: CRTConfig.ajaxurl,
            // 		url: '/royal-wp/wp-json/crtaddons/v1/page-content/' + CRTConfig.comparePageID,
            // 		type: 'GET',
            // 		// data: {
            // 		// 	action: 'crt_get_page_content',
            // 		// 	crt_compare_page_id: CRTConfig.comparePageID // Replace with the ID of the page you want to retrieve
            // 		// },
            // 		// success: function(response) {
            // 		// 	// $scope.find('.crt-compare-popup').append(response.data.content);
            // 		// 	$scope.find('.crt-compare-popup').append(response);
            // 		// },
            // 		dataType: 'json',
            // 		success: function(response) {
            // 				$scope.find('.crt-compare-popup').append(response);
            // 				elementorFrontend.init();
            // 		},
            // 		error: function(xhr, status, error) {
            // 		}
            // 	});
            // });

            if ( !($scope.find('.crt-compare-count').length > 0 && 0 == $scope.find('.crt-compare-count').text()) ) {
                $scope.find('.crt-compare-count').css('display', 'inline-flex');
            }

            // WITH AJAX
            if ( $scope.hasClass('crt-compare-style-popup') ) {
                $scope.find('.crt-compare-toggle-btn').on('click', function(e) {
                    e.preventDefault();

                    $('body').addClass('crt-body-overflow-hidden');

                    $scope.find('.crt-compare-bg').removeClass('crt-compare-popup-hidden');
                    $scope.find('.crt-compare-popup').removeClass('crt-compare-fade-out').addClass('crt-compare-fade-in');
                    $scope.find('.crt-compare-bg').removeClass('crt-compare-fade-out').addClass('crt-compare-fade-in');

                    $scope.find('.crt-compare-popup-inner-wrap').html('<div class="crt-compare-loader-wrap"><div class="crt-double-bounce"><div class="crt-child crt-double-bounce1"></div><div class="crt-child crt-double-bounce2"></div></div></div>');
                    $.ajax({
                        url: CRTConfig.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'crt_get_page_content',
                            nonce: CRTConfig.nonce,
                            crt_compare_page_id: CRTConfig.comparePageID
                        },
                        success: function(response) {
                            $scope.find('.crt-compare-popup-inner-wrap').html(response.data.content);
                            CrtElements.widgetCompare($scope);

                            $scope.find('.crt-compare-remove').click(function(e) {
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
                                        localStorage.setItem('changeActionTargetProductId', product_id);
                                        $scope.find('[data-product-id="' + productID + '"]').remove();
                                        if ( !($scope.find('.crt-compare-popup-inner-wrap').find('.crt-compare-remove').length > 0) ) {
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
                        },
                        error: function(xhr, status, error) {
                            console.log(xhr.responseText);
                        }
                    });
                });

                $scope.find('.crt-close-compare').click(function(e) {
                    $scope.find('.crt-compare-popup').removeClass('crt-compare-fade-in').addClass('crt-compare-fade-out');
                    $scope.find('.crt-compare-bg').removeClass('crt-compare-fade-in').addClass('crt-compare-fade-out');
                    setTimeout(function() {
                        $scope.find('.crt-compare-bg').addClass('crt-compare-popup-hidden');
                        $('body').removeClass('crt-body-overflow-hidden');
                    }, 600)
                });

                $scope.find('.crt-compare-bg').click(function(e) {
                    if ( !e.target.classList.value.includes('crt-compare-popup') && !e.target.closest('.crt-compare-popup') ) {
                        var thisTarget = $(this);
                        $scope.find('.crt-compare-popup').removeClass('crt-compare-fade-in').addClass('crt-compare-fade-out');
                        $scope.find('.crt-compare-bg').removeClass('crt-compare-fade-in').addClass('crt-compare-fade-out');
                        setTimeout(function() {
                            thisTarget.addClass('crt-compare-popup-hidden');
                            $('body').removeClass('crt-body-overflow-hidden');
                        }, 600);
                    }
                });

            }

            $.ajax({
                url: CRTConfig.ajaxurl,
                type: 'POST',
                data: {
                    action: 'count_compare_items',
                },
                success: function(response) {
                    let compare_count = response.compare_count;
                    if ( $scope.find('.crt-compare-count').css('display') == 'none' && 0 < compare_count ) {
                        $scope.find('.crt-compare-count').text(compare_count);
                        $scope.find('.crt-compare-count').css('display', 'inline-flex');
                    } else if ( 0 == compare_count ) {
                        $scope.find('.crt-compare-count').css('display', 'none');
                        $scope.find('.crt-compare-count').text(compare_count);
                    } else {
                        $scope.find('.crt-compare-count').text(compare_count);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });

            $(document).on('removed_from_compare', function() {
                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'update_mini_compare',
                        product_id: localStorage.getItem('changeActionTargetProductId'),
                    },
                    success: function(response) {
                        $scope.find('.crt-compare-count').text(response.compare_count);

                        if ( response.compare_count == 0 ) {
                            $scope.find('.crt-compare-count').css('display', 'none');
                        } else {
                            $scope.find('.crt-compare-count').css('display', 'inline-flex');
                        }
                    }
                });
            });

            $(document).on('added_to_compare', function() {
                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'update_mini_compare',
                        product_id: localStorage.getItem('changeActionTargetProductId'),
                    },
                    success: function(response) {
                        $scope.find('.crt-compare-count').text(response.compare_count);
                        $scope.find('.crt-compare-count').css('display', 'inline-flex');
                    }
                });
            });
        });
        
        var CrtElements = {
            widgetCompare: function($scope) {
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
            }, // end widgetCompare
        }
    });
})(jQuery);