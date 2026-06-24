(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-search.default',function($scope) {
            var isFound = false;

            $scope.find('.crt-search-form-input').on( {
                focus: function() {
                    $scope.addClass( 'crt-search-form-input-focus' );
                },
                blur: function() {
                    $scope.removeClass( 'crt-search-form-input-focus' );
                }
            } );

            if ( $scope.find('.crt-category-select').length > 0 ) {
                // Set the selected value on page load
                $(document).ready(function() {
                    var crtSelectedCategory = localStorage.getItem('crtSelectedCategory');
                    if (crtSelectedCategory) {
                        $scope.find('.crt-category-select option').each(function() {
                            if ($(this).val() === crtSelectedCategory) {
                                isFound = true;
                                $scope.find('.crt-category-select').val(crtSelectedCategory);
                                return false; // Breaks out of the .each() loop
                            } else {
                                $scope.find('.crt-category-select').val(0);
                            }
                        });
                    }
                });

                $scope.find('.crt-category-select').on('change', function(e) {

                    var selectedValue = $(this).val();
                    localStorage.setItem('crtSelectedCategory', selectedValue);

                    if ($scope.find('.crt-search-form-input').attr('ajax-search') === 'yes') {
                        postsOffset = 0;
                        $scope.find('.crt-data-fetch').hide();
                        $scope.find('.crt-data-fetch ul').html('');
                        ajaxSearchCall($scope.find('.crt-search-form-input'), postsOffset, e);
                    }
                });
            }

            // if ( $scope.find('.crt-search-input-hidden') ) {
            // 	$scope.find('.crt-search-form-submit').on('click', function(e) {
            // 		e.preventDefault();
            // 		if ($scope.find('input').hasClass('crt-search-input-hidden')) {
            // 			$scope.find('input').removeClass('crt-search-input-hidden');
            // 		} else {
            // 			$scope.find('input').addClass('crt-search-input-hidden');
            // 			$scope.find('.crt-search-form-input').val('');
            // 			$scope.find('.crt-data-fetch').slideUp(200);
            // 			setTimeout(function() {
            // 				$scope.find('.crt-data-fetch ul').html('');
            // 				$scope.find('.crt-no-results').remove();
            // 			}, 400);
            // 			postsOffset = 0;
            // 		}
            // 	});
            // }

            var prevData;
            var searchTimeout = null;

            function ajaxSearchCall(thisObject, postsOffset, e) {
                if ( e.which === 13 ) {
                    return false;
                }

                if (searchTimeout != null) {
                    clearTimeout(searchTimeout);
                }
                var optionPostType = ($scope.find('.crt-category-select').length > 0 && $scope.find('.crt-category-select').find('option:selected').data('post-type'));
                var crtTaxonomyType = $scope.find('.crt-search-form-input').attr('crt-taxonomy-type');

                if ( $scope.find('.crt-category-select').length > 0) {
                    if (!crtTaxonomyType) {
                        if ($scope.find('.crt-search-form-input').attr('crt-query-type') == 'product') {
                            crtTaxonomyType = 'product_cat';
                        } else {
                            crtTaxonomyType = 'category';
                        }
                    }
                }

                searchTimeout = setTimeout(() => {
                    var thisValue = thisObject.val();

                    $.ajax({
                        type: 'POST',
                        url: CRTConfig.ajaxurl,
                        data: {
                            action: 'crt_data_fetch',
                            nonce: CRTConfig.nonce,
                            crt_keyword: $scope.find('.crt-search-form-input').val(),
                            crt_meta_query: $scope.find('.crt-search-form-input').attr('meta-query'),
                            crt_query_type: $scope.find('.crt-search-form-input').attr('crt-query-type'),
                            crt_option_post_type: optionPostType ? $scope.find('.crt-category-select').find('option:selected').data('post-type') : '',
                            crt_taxonomy_type: crtTaxonomyType,
                            crt_category: $scope.find('.crt-category-select').length > 0 ? $scope.find('.crt-category-select').val() : '',
                            crt_number_of_results: $scope.find('.crt-search-form-input').attr('number-of-results'),
                            crt_search_results_offset: postsOffset,
                            crt_show_description: $scope.find('.crt-search-form-input').attr('show-description'),
                            crt_number_of_words: $scope.find('.crt-search-form-input').attr('number-of-words'),
                            crt_show_ajax_thumbnail: $scope.find('.crt-search-form-input').attr('show-ajax-thumbnails'),
                            crt_show_product_price: $scope.find('.crt-search-form-input').attr('show-product-price'),
                            crt_show_view_result_btn: $scope.find('.crt-search-form-input').attr('show-view-result-btn'),
                            crt_view_result_text: $scope.find('.crt-search-form-input').attr('view-result-text'),
                            crt_no_results: $scope.find('.crt-search-form-input').attr('no-results'),
                            crt_exclude_without_thumb: $scope.find('.crt-search-form-input').attr('exclude-without-thumb'),
                            crt_ajax_search_link_target: $scope.find('.crt-search-form-input').attr('link-target'),
                            crt_show_ps_pt: $scope.find('.crt-search-form-input').attr('password-protected'),
                            crt_show_attachments: $scope.find('.crt-search-form-input').attr('attachments'),
                            // crt_ajax_search_img_size: $scope.find('.crt-search-form-input').attr('ajax-search-img-size')
                        },
                        success: function(data) {
                            $scope.closest('section').addClass('crt-section-z-index');
                            if ( $scope.find('.crt-data-fetch ul').html() === '' ) {
                                $scope.find( '.crt-pagination-loading' ).hide();
                                $scope.find('.crt-data-fetch ul').html( data );
                                $scope.find('.crt-no-more-results').fadeOut(100);
                                setTimeout(function() {
                                    if (!data.includes('crt-no-results')) {
                                        $scope.find('.crt-ajax-search-pagination').css('display', 'flex');
                                        if ( $scope.find('.crt-data-fetch ul').find('li').length < $scope.find('.crt-search-form-input').attr('number-of-results') ||
                                            $scope.find('.crt-data-fetch ul').find('li').length == $scope.find('.crt-data-fetch ul').find('li').data('number-of-results')) {
                                            $scope.find('.crt-ajax-search-pagination').css('display', 'none');
                                            $scope.find('.crt-load-more-results').fadeOut(100);
                                        } else {
                                            $scope.find('.crt-ajax-search-pagination').css('display', 'flex');
                                            $scope.find('.crt-load-more-results').fadeIn(100);
                                        }
                                    } else {
                                        $scope.find('.crt-ajax-search-pagination').css('display', 'none');
                                    }
                                }, 100);
                                prevData = data;
                            } else {
                                if ( data != prevData ) {
                                    prevData = data;
                                    if (data.includes('crt-no-results')) {
                                        $scope.find('.crt-ajax-search-pagination').css('display', 'none');
                                        $scope.find('.crt-data-fetch ul').html('');
                                        $scope.closest('section').removeClass('crt-section-z-index');
                                    } else {
                                        $scope.find('.crt-ajax-search-pagination').css('display', 'flex');
                                    }

                                    $scope.find('.crt-data-fetch ul').append( data );

                                    if (data == '') {
                                        $scope.find('.crt-load-more-results').fadeOut(100);
                                        setTimeout(function() {
                                            $scope.find( '.crt-pagination-loading' ).hide();
                                            $scope.find('.crt-no-more-results').fadeIn(100);
                                        }, 100);
                                    } else {
                                        $scope.find( '.crt-pagination-loading' ).hide();
                                        $scope.find('.crt-load-more-results').show();
                                    }

                                    if ($scope.find('.crt-data-fetch ul').find('li').length < $scope.find('.crt-search-form-input').attr('number-of-results')) {
                                        $scope.find('.crt-load-more-results').fadeOut(100);
                                        setTimeout(function() {
                                            $scope.find( '.crt-pagination-loading' ).hide();
                                            $scope.find('.crt-no-more-results').fadeIn(100);
                                        }, 100);
                                    } else {
                                        $scope.find('.crt-load-more-results').show();
                                    }

                                    if ( $scope.find('.crt-data-fetch ul').find('li').length == $scope.find('.crt-data-fetch ul').find('li').data('number-of-results') ) {
                                        $scope.find('.crt-load-more-results').fadeOut(100);
                                        setTimeout(function() {
                                            $scope.find( '.crt-pagination-loading' ).hide();
                                            $scope.find('.crt-no-more-results').fadeIn(100);
                                        }, 100);
                                    } else {
                                        $scope.find('.crt-load-more-results').show();
                                    }
                                    // $scope.find( '.crt-pagination-loading' ).hide();
                                }
                            }

                            if (data.includes('crt-no-results')) {
                                $scope.find('.crt-ajax-search-pagination').css('display', 'none');
                                $scope.find('.crt-load-more-results').fadeOut();
                            } else {
                                $scope.find('.crt-ajax-search-pagination').css('display', 'flex');
                            }

                            if (thisValue.length > 2) {
                                $scope.find('.crt-data-fetch').slideDown(200);
                                $scope.find('.crt-data-fetch ul').fadeTo(200, 1);
                            } else {
                                $scope.find('.crt-data-fetch').slideUp(200);
                                $scope.find('.crt-data-fetch ul').fadeTo(200, 0);
                                setTimeout(function() {
                                    $scope.find('.crt-data-fetch ul').html('');
                                    $scope.find('.crt-no-results').remove();
                                    $scope.closest('section').removeClass('crt-section-z-index');
                                }, 600);
                                postsOffset = 0;
                            }
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                }, 400);
            }

            if ($scope.find('.crt-search-form-input').attr('ajax-search') === 'yes') {

                $scope.find('.crt-search-form').attr('autocomplete', 'off');

                var postsOffset = 0;
                // $scope.find('.crt-data-fetch ul').on('scroll', function(e) {
                // 	if ( $(this).scrollTop() + $(this).innerHeight() >=  $(this)[0].scrollHeight ) {
                // 		postsOffset += +$scope.find('.crt-search-form-input').attr('number-of-results');
                // 		ajaxSearchCall($scope.find('.crt-search-form-input'), postsOffset, e);
                // 	}
                // });

                $scope.find('.crt-load-more-results').on('click', function(e) {
                    postsOffset += +$scope.find('.crt-search-form-input').attr('number-of-results');
                    $scope.find('.crt-load-more-results').hide();
                    $scope.find( '.crt-pagination-loading' ).css( 'display', 'inline-block' );
                    ajaxSearchCall($scope.find('.crt-search-form-input'), postsOffset, e);
                });

                $scope.find('.crt-search-form-input').on('keyup', function(e) {
                    postsOffset = 0;
                    $scope.find('.crt-data-fetch').hide();
                    $scope.find('.crt-data-fetch ul').html('');
                    ajaxSearchCall($(this), postsOffset, e);
                });

                $scope.find('.crt-data-fetch').on('click', '.crt-close-search', function() {
                    $scope.find('.crt-search-form-input').val('');
                    $scope.find('.crt-data-fetch').slideUp(200);
                    setTimeout(function() {
                        $scope.find('.crt-data-fetch ul').html('');
                        $scope.find('.crt-no-results').remove();
                        $scope.closest('section').removeClass('crt-section-z-index');
                    }, 400);
                    postsOffset = 0;
                });

                $('body').on('click', function(e) {
                    if ( !e.target.classList.value.includes('crt-data-fetch') && !e.target.closest('.crt-data-fetch') ) {
                        if ( !e.target.classList.value.includes('crt-search-form') && !e.target.closest('.crt-search-form') ) {
                            $scope.find('.crt-search-form-input').val('');
                            $scope.find('.crt-data-fetch').slideUp(200);
                            setTimeout(function() {
                                $scope.find('.crt-data-fetch ul').html('');
                                $scope.find('.crt-no-results').remove();
                                $scope.closest('section').removeClass('crt-section-z-index');
                            }, 400);
                            postsOffset = 0;
                        }
                    }
                });

                var mutationObserver = new MutationObserver(function(mutations) {
                    $scope.find('.crt-data-fetch li').on('click', function() {
                        var itemUrl = $(this).find('a').attr('href');
                        var itemUrlTarget = $(this).find('a').attr('target');
                        window.open(itemUrl, itemUrlTarget).focus();
                    });
                });

                // Listen to Mini Cart Changes
                mutationObserver.observe($scope[0], {
                    childList: true,
                    subtree: true,
                });
            }
        });
    });
})(jQuery);