(function($) {
    "use strict";
    var initialItems;
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-category-grid.default',function($scope) {
            var iGrid = $scope.find( '.crt-grid' ),
                loadedItems,
                experimentActionCount = $scope.hasClass('elementor-widget-crt-woo-grid') ? 'crt_get_filtered_count_products' :
                    $scope.hasClass('elementor-widget-crt-media-grid') ? 'crt_get_media_filtered_count' : 'crt_get_filtered_count_posts',
                experimentActionContent = $scope.hasClass('elementor-widget-crt-woo-grid') ? 'crt_woo_grid_filters_ajax' :
                    $scope.hasClass('elementor-widget-crt-media-grid') ? 'crt_filter_grid_media' : 'crt_grid_filters_ajax';

            if ( ! iGrid.length ) {
                return;
            }

            if ( $scope.find(".crt-grid-orderby form").length ) {
                var select = $scope.find(".crt-grid-orderby form");
                $scope.find(".orderby").on("change", function () {
                    select.trigger("submit");
                });
            }

            // Settings
            var settings = iGrid.attr( 'data-settings' );

            // Grid
            if ( typeof settings !== typeof undefined && settings !== false ) {
                settings = JSON.parse( iGrid.attr( 'data-settings' ) );

                $(window).on('load', function() {
                    setTimeout(function() {
                        if ($scope.find('.woocommerce-result-count').length) {
                            var resultCountText = $scope.find('.woocommerce-result-count').text();

                            var itemsPerPage = settings.query_posts_per_page ?
                                +settings.query_posts_per_page :
                                +CrtConfig.woo_shop_ppp;

                            if (CrtConfig.is_product_category) {
                                itemsPerPage = +CrtConfig.woo_shop_cat_ppp;
                            } else if (CrtConfig.is_product_tag) {
                                itemsPerPage = +CrtConfig.woo_shop_tag_ppp;
                            }

                            if (isNaN(itemsPerPage) || itemsPerPage <= 0) {
                                return;
                            }

                            var currentPage = 1;
                            var currentPageElement = $scope.find('.crt-grid-current-page');

                            if (currentPageElement.length) {
                                currentPage = parseInt(currentPageElement.text().trim()) || 1;
                            }

                            var totalItemsMatch = resultCountText.match(/(\d+)(?!.*\d+)/);
                            var totalItems = totalItemsMatch ? parseInt(totalItemsMatch[1].trim()) : itemsPerPage;

                            if (isNaN(totalItems) || totalItems <= 0) {
                                return;
                            }

                            var startItem = (currentPage - 1) * itemsPerPage + 1;
                            var endItem = Math.min(startItem + itemsPerPage - 1, totalItems);

                            resultCountText = resultCountText.replace(/\d+\u2013\d+/, startItem + '\u2013' + endItem);
                            $scope.find('.woocommerce-result-count').text(resultCountText);
                            setTimeout(function() {
                                $scope.find('.woocommerce-result-count').text(resultCountText);
                            }, 800);
                        }
                    }, 200);
                });

                // Init Functions
                CrtElements.isotopeLayout( settings, '', '', $scope );
                setTimeout(function() {
                    CrtElements.isotopeLayout( settings, '', '', $scope );
                }, 100 );

                // if ( CrtElements.editorCheck() ) {
                    // setTimeout(function() {
                    //     CrtElements.isotopeLayout( settings, '', '', $scope );
                    // }, 500 );
                    setTimeout(function() {
                        CrtElements.isotopeLayout( settings, '', '', $scope );
                    }, 1000 );
                // }

                $( window ).on( 'load', function() {
                    setTimeout(function() {
                        CrtElements.isotopeLayout( settings, '', '', $scope );
                    }, 100 );
                });

                $(document).ready(function() {
                    setTimeout(function() {
                        CrtElements.isotopeLayout( settings, '', '', $scope );
                    }, 100 );
                });

                $(window).smartresize(function(){
                    setTimeout(function() {
                        CrtElements.isotopeLayout( settings, '', '', $scope );
                    }, 200 );
                });

                if ( settings.grid_settings ) {
                    var pagesLoadedExperiment = 0;
                    if ( $('body').find('.crt-advanced-filters-wrap').length == 0 ) {
                        loadMoreExperiment();
                    }
                    filtersExperiment();
                }

                isotopeFilters( settings );

                CrtElements.changeInitialItems(0);

                // Filtering Transitions
                iGrid.on( 'arrangeComplete', function( event, filteredItems ) {
                    var deepLinkStager = 0,
                        filterStager = 0,
                        initStager = 0,
                        duration = settings.animation_duration,
                        filterDuration = settings.filters_animation_duration;

                    if ( iGrid.hasClass( 'grid-images-loaded' ) ) {
                        initStager = 0;
                    } else {
                        iGrid.css( 'opacity', '1' );

                        // Default Animation
                        if ( 'default' === settings.animation && 'default' === settings.filters_animation ) {
                            return;
                        }
                    }

                    for ( var key in filteredItems ) {
                        if ( initialItems == 0 || key > initialItems - 1 ) {
                            initStager += settings.animation_delay;
                            $scope.find( filteredItems[key]['element'] ).find( '.crt-grid-item-inner' ).css({
                                'opacity' : '1',
                                'top' : '0',
                                'transform' : 'scale(1)',
                                'transition' : 'all '+ duration +'s ease-in '+ initStager +'s',
                            });
                        }

                        filterStager += settings.filters_animation_delay;
                        if ( iGrid.hasClass( 'grid-images-loaded' ) ) {
                            $scope.find( filteredItems[key]['element'] ).find( '.crt-grid-item-inner' ).css({
                                'transition' : 'all '+ filterDuration +'s ease-in '+ filterStager +'s',
                            });
                        }

                        // DeepLinking
                        var deepLink = window.location.hash;

                        if ( deepLink.indexOf( '#filter:' ) >= 0 && deepLink.indexOf( '#filter:*' ) < 0 ) {
                            deepLink = deepLink.replace( '#filter:', '' );

                            $scope.find( filteredItems[key]['element'] ).filter(function() {
                                if ( $(this).hasClass( deepLink ) ) {
                                    deepLinkStager += settings.filters_animation_delay;
                                    return $(this);
                                }
                            }).find( '.crt-grid-item-inner' ).css({
                                'transition-delay' : deepLinkStager +'s'
                            });
                        }
                    }

                    CrtElements.changeInitialItems(filteredItems.length);
                });

                // iGrid.imagesLoaded().progress( function( instance, image ) {
                // });

                // Grid Images Loaded
                iGrid.imagesLoaded(function() {
                    if ( '1' !== iGrid.css( 'opacity' ) ) {
                        iGrid.css( 'opacity', '1' );
                    }

                    setTimeout(function() {
                        iGrid.addClass( 'grid-images-loaded' );
                    }, 500 );

                    // Equal Heights
                    setEqualHeight(settings);
                });

                // Infinite Scroll / Load More
                if ( 'load-more' === settings.pagination_type || 'infinite-scroll' === settings.pagination_type ) {
                    if ( $scope.find( '.crt-grid-pagination' ).length ) {
                        var pagination = $scope.find( '.crt-grid-pagination' ),
                            scopeClass = '.elementor-element-'+ $scope.attr( 'data-id' );

                        var navClass = false,
                            threshold = false;

                        if ( 'infinite-scroll' === settings.pagination_type ) {
                            threshold = 300;
                            navClass = scopeClass +' .crt-load-more-btn';
                        }

                        iGrid.infiniteScroll({
                            path: scopeClass +' .crt-grid-pagination a',
                            hideNav: navClass,
                            append: false,
                            history: false,
                            scrollThreshold: threshold,
                            status: scopeClass +' .page-load-status',
                            onInit: function() {
                                this.on( 'load', function() {
                                    iGrid.removeClass( 'grid-images-loaded' );
                                });
                            }
                        });

                        // Request
                        iGrid.on( 'request.infiniteScroll', function( event, path ) {
                            pagination.find( '.crt-load-more-btn' ).hide();
                            pagination.find( '.crt-pagination-loading' ).css( 'display', 'inline-block' );
                        });

                        // Load
                        var pagesLoaded = 0;

                        iGrid.on( 'load.infiniteScroll', function( event, response ) {
                            pagesLoaded++;

                            // get posts from response
                            var items = $( response ).find( scopeClass ).find( '.crt-grid-item' );

                            if ( $scope.find('.woocommerce-result-count').length ) {
                                var resultCount = $scope.find('.woocommerce-result-count').text();
                                var updatedResultCount = resultCount.replace( /\d\u2013\d+/, '1\u2013' + ( $scope.find('.crt-grid-item').length + items.length ) );
                                $scope.find('.woocommerce-result-count').text(updatedResultCount);
                            }

                            iGrid.infiniteScroll( 'appendItems', items );
                            iGrid.isotopecrt( 'appended', items );

                            items.imagesLoaded().progress( function( instance, image ) {
                                CrtElements.isotopeLayout( settings, '', '', $scope );

                                // Fix Layout
                                setTimeout(function() {
                                    CrtElements.isotopeLayout( settings, '', '', $scope );
                                    isotopeFilters( settings );
                                }, 10 );

                                setTimeout(function() {
                                    iGrid.addClass( 'grid-images-loaded' );
                                }, 500 );
                            });

                            // Loading
                            pagination.find( '.crt-pagination-loading' ).hide();

                            if ( settings.pagination_max_pages - 1 !== pagesLoaded ) {
                                if ( 'load-more' === settings.pagination_type ) {
                                    pagination.find( '.crt-load-more-btn' ).fadeIn();

                                    if ( $scope.find('.crt-grid-filters').length ) {
                                        if ( '*' !== $scope.find('.crt-active-filter').attr('data-filter') ) {
                                            if ( 0 < $scope.find('.crt-active-filter').length ) {
                                                let dataFilterClass = $scope.find('.crt-active-filter').attr('data-filter').substring(1);
                                                items.each(function() {
                                                    if ( !$(this).hasClass(dataFilterClass) ) {
                                                        loadedItems = false;
                                                    } else {
                                                        loadedItems = true;
                                                        return false;
                                                    }
                                                });

                                                if ( !loadedItems ) {
                                                    $scope.find( '.crt-grid' ).infiniteScroll( 'loadNextPage' );
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                pagination.find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                pagination.delay( 2000 ).fadeOut( 1000 );
                                setTimeout(function() {
                                    pagination.find( '.crt-pagination-loading' ).hide();
                                }, 500 );
                            }
                            // Init Likes
                            // No need for this anymore
                            // setTimeout(function() {
                            // 	postLikes( settings );
                            // }, 300 );

                            // Init Lightbox
                            CrtElements.lightboxPopup( settings, $scope, iGrid );

                            // Fix Lightbox
                            iGrid.data( 'lightGallery' ).destroy( true );
                            iGrid.lightGallery( settings.lightbox );

                            // Init Media Hover Link
                            CrtElements.mediaHoverLink($scope, iGrid);

                            // Init Post Sharing
                            postSharing();

                            lazyLoadObserver();

                            // Maybe there is some other way
                            setTimeout(function() {
                                setEqualHeight(settings);
                                window.dispatchEvent(new Event('resize'));
                            }, 500);
                        });

                        pagination.find( '.crt-load-more-btn' ).on( 'click', function() {
                            iGrid.infiniteScroll( 'loadNextPage' );
                            return false;
                        });
                    } else {
                        $scope.find( '.crt-load-more-btn' ).on( 'click', function() {
                            alert( 'Load More is Disabled in the Editor! Please Preview this Page to see it in action' );
                        });
                    }
                }

                // Slider
            } else {
                iGrid.animate({ 'opacity': '1' }, 1000);

                settings = JSON.parse( iGrid.attr( 'data-slick' ) );

                var sliderClass = $scope.attr('class'),
                    sliderColumnsDesktop = sliderClass.match(/crt-grid-slider-columns-\d/) ? +sliderClass.match(/crt-grid-slider-columns-\d/).join().slice(-1) : 2,
                    sliderColumnsWideScreen = sliderClass.match(/columns--widescreen\d/) ? +sliderClass.match(/columns--widescreen\d/).join().slice(-1) : sliderColumnsDesktop,
                    sliderColumnsLaptop = sliderClass.match(/columns--laptop\d/) ? +sliderClass.match(/columns--laptop\d/).join().slice(-1) : sliderColumnsDesktop,
                    sliderColumnsTablet = sliderClass.match(/columns--tablet\d/) ? +sliderClass.match(/columns--tablet\d/).join().slice(-1) : 2,
                    sliderColumnsTabletExtra = sliderClass.match(/columns--tablet_extra\d/) ? +sliderClass.match(/columns--tablet_extra\d/).join().slice(-1) : sliderColumnsTablet,
                    sliderColumnsMobileExtra = sliderClass.match(/columns--mobile_extra\d/) ? +sliderClass.match(/columns--mobile_extra\d/).join().slice(-1) : sliderColumnsTablet,
                    sliderColumnsMobile = sliderClass.match(/columns--mobile\d/) ? +sliderClass.match(/columns--mobile\d/).join().slice(-1) : 1,
                    sliderRows = settings.sliderRows,
                    sliderSlidesToScroll = settings.sliderSlidesToScroll;

                // GOGA - add rows control and vertical gutter maybe
                iGrid.slick({
                    appendDots : $scope.find( '.crt-grid-slider-dots' ),
                    rows: sliderRows,
                    customPaging : function ( slider, i ) {
                        var slideNumber = (i + 1),
                            totalSlides = slider.slideCount;

                        return '<span class="crt-grid-slider-dot"></span>';
                    },
                    slidesToShow: sliderColumnsDesktop,
                    responsive: [
                        {
                            breakpoint: 10000,
                            settings: {
                                slidesToShow: sliderColumnsWideScreen,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsWideScreen ? 1 : sliderSlidesToScroll
                            }
                        },
                        {
                            breakpoint: 2399,
                            settings: {
                                slidesToShow: sliderColumnsDesktop,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsDesktop ? 1 : sliderSlidesToScroll
                            }
                        },
                        {
                            breakpoint: 1221,
                            settings: {
                                slidesToShow: sliderColumnsLaptop,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsLaptop ? 1 : sliderSlidesToScroll
                            }
                        },
                        {
                            breakpoint: 1200,
                            settings: {
                                slidesToShow: sliderColumnsTabletExtra,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsTabletExtra ? 1 : sliderSlidesToScroll
                            }
                        },
                        {
                            breakpoint: 1024,
                            settings: {
                                slidesToShow: sliderColumnsTablet,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsTablet ? 1 : sliderSlidesToScroll
                            }
                        },
                        {
                            breakpoint: 880,
                            settings: {
                                slidesToShow: sliderColumnsMobileExtra,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsMobileExtra ? 1 : sliderSlidesToScroll
                            }
                        },
                        {
                            breakpoint: 768,
                            settings: {
                                slidesToShow: sliderColumnsMobile,
                                slidesToScroll: sliderSlidesToScroll > sliderColumnsMobile ? 1 : sliderSlidesToScroll
                            }
                        }
                    ],
                });

                var gridNavPrevArrow = $scope.find('.crt-grid-slider-prev-arrow');
                var gridNavNextArrow = $scope.find('.crt-grid-slider-next-arrow');

                if ( gridNavPrevArrow.length > 0 && gridNavNextArrow.length > 0 ) {
                    var positionSum = gridNavPrevArrow.position().left * -2;
                    if ( positionSum > 0 ) {
                        $(window).on('load', function() {
                            if ( $(window).width() <= ($scope.outerWidth() + gridNavPrevArrow.outerWidth() + gridNavNextArrow.outerWidth() + positionSum) ) {
                                gridNavPrevArrow.addClass('crt-adjust-slider-prev-arrow');
                                gridNavNextArrow.addClass('crt-adjust-slider-next-arrow');
                            }
                        });

                        $(window).smartresize(function() {
                            if ( $(window).width() <= ($scope.outerWidth() + gridNavPrevArrow.outerWidth() + gridNavNextArrow.outerWidth() + positionSum) ) {
                                gridNavPrevArrow.addClass('crt-adjust-slider-prev-arrow');
                                gridNavNextArrow.addClass('crt-adjust-slider-next-arrow');
                            } else {
                                gridNavPrevArrow.removeClass('crt-adjust-slider-prev-arrow');
                                gridNavNextArrow.removeClass('crt-adjust-slider-next-arrow');
                            }
                        });
                    }
                }

                // Adjust Horizontal Pagination
                if ( $scope.find( '.slick-dots' ).length && $scope.hasClass( 'crt-grid-slider-dots-horizontal') ) {
                    // Calculate Width
                    var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

                    // on Load
                    if ( $scope.find( '.slick-dots' ).length ) {
                        $scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
                    }


                    $(window).smartresize(function() {
                        setTimeout(function() {
                            // Calculate Width
                            var dotsWrapWidth = $scope.find( '.slick-dots li' ).outerWidth() * $scope.find( '.slick-dots li' ).length - parseInt( $scope.find( '.slick-dots li span' ).css( 'margin-right' ), 10 );

                            // Set Width
                            $scope.find( '.slick-dots' ).css( 'width', dotsWrapWidth );
                        }, 300 );
                    });
                }
            }

            checkWishlistAndCompare();
            addRemoveCompare();
            addRemoveWishlist();

            var mutationObserver = new MutationObserver(function(mutations) {
                // checkWishlistAndCompare();
                addRemoveCompare();
                addRemoveWishlist();
            });

            mutationObserver.observe($scope[0], {
                childList: true,
                subtree: true,
            });

            // Add To Cart AJAX
            if ( iGrid.find( '.crt-grid-item-add-to-cart' ).length ) {
                var addCartIcon = iGrid.find( '.crt-grid-item-add-to-cart' ).find( 'i' ),
                    addCartIconClass = addCartIcon.attr( 'class' );

                if ( addCartIcon.length ) {
                    addCartIconClass = addCartIconClass.substring( addCartIconClass.indexOf('fa-'), addCartIconClass.length );
                }

                $( 'body' ).on( 'adding_to_cart', function( ev, button, data ) {
                    button.fadeTo( 'slow', 0 );
                });

                $( 'body' ).on( 'added_to_cart', function(ev, fragments, hash, button) {
                    var product_id = button.data('product_id');

                    button.next().fadeTo( 700, 1 );

                    button.css('display', 'none');

                    if ( 'sidebar' === button.data('atc-popup') ) {
                        if ( $('.crt-mini-cart-toggle-wrap a').length ) {
                            $('.crt-mini-cart-toggle-wrap a').each(function() {
                                if ( 'none' === $(this).closest('.crt-mini-cart-inner').find('.crt-mini-cart').css('display') ) {
                                    $(this).trigger('click');
                                }
                            });
                        }
                    } else if ( 'popup' === button.data('atc-popup') ) {
                        var popupItem = button.closest('.crt-grid-item'),
                            popupText = popupItem.find('.crt-grid-item-title').text(),
                            popupLink = button.next().attr('href'),
                            popupImageSrc = popupItem.find('.crt-grid-image-wrap').length ? popupItem.find('.crt-grid-image-wrap').data('src') : '',
                            popupAnimation = button.data('atc-animation'),
                            fadeOutIn = button.data('atc-fade-out-in'),
                            animTime = button.data('atc-animation-time'),
                            popupImage,
                            animationClass = 'crt-added-to-cart-default',
                            removeAnimationClass;

                        if ( 'slide-left' === popupAnimation ) {
                            animationClass = 'crt-added-to-cart-slide-in-left';
                            removeAnimationClass = 'crt-added-to-cart-slide-out-left';
                        } else if ( 'scale-up' === popupAnimation ) {
                            animationClass = 'crt-added-to-cart-scale-up';
                            removeAnimationClass = 'crt-added-to-cart-scale-down';
                        } else if ( 'skew' === popupAnimation ) {
                            animationClass = 'crt-added-to-cart-skew';
                            removeAnimationClass = 'crt-added-to-cart-skew-off';
                        } else if ( 'fade' === popupAnimation ) {
                            animationClass = 'crt-added-to-cart-fade';
                            removeAnimationClass = 'crt-added-to-cart-fade-out';
                        } else {
                            removeAnimationClass = 'crt-added-to-cart-popup-hide';
                        }

                        if ( '' !== popupImageSrc ) {
                            popupImage = '<div class="crt-added-tc-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
                        } else {
                            popupImage = '';
                        }

                        if ( !($scope.find('.crt-grid').find('#crt-added-to-cart-'+product_id).length > 0) ) {
                            $scope.find('.crt-grid').append('<div id="crt-added-to-cart-'+product_id+'" class="crt-added-to-cart-popup ' + animationClass + '">'+ popupImage +'<div class="crt-added-tc-title"><p>'+ popupText + ' ' + CrtConfig.addedToCartText +'</p><p><a href='+popupLink+'>'+ CrtConfig.viewCart +'</a></p></div></div>');

                            setTimeout(() => {
                                $(this).find('#crt-added-to-cart-'+product_id).addClass(removeAnimationClass);
                                setTimeout(() => {
                                    $(this).find('#crt-added-to-cart-'+product_id).remove();
                                }, animTime * 1000);
                            }, fadeOutIn * 1000);
                        }
                    }

                    if ( addCartIcon.length ) {
                        button.find( 'i' ).removeClass( addCartIconClass ).addClass( 'fa-check' );
                        setTimeout(function() {
                            button.find( 'i' ).removeClass( 'fa-check' ).addClass( addCartIconClass );
                        }, 3500 );
                    }
                });
            }

            // Init Post Sharing
            postSharing();

            // Post Sharing
            function postSharing() {
                if ( $scope.find( '.crt-sharing-trigger' ).length ) {
                    var sharingTrigger = $scope.find( '.crt-sharing-trigger' ),
                        sharingInner = $scope.find( '.crt-post-sharing-inner' ),
                        sharingWidth = 5;

                    // Calculate Width
                    sharingInner.first().find( 'a' ).each(function() {
                        sharingWidth += $(this).outerWidth() + parseInt( $(this).css('margin-right'), 10 );
                    });

                    // Calculate Margin
                    var sharingMargin = parseInt( sharingInner.find( 'a' ).css('margin-right'), 10 );

                    // Set Positions
                    if ( 'left' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Width
                        sharingInner.css( 'width', sharingWidth +'px' );

                        // Set Position
                        sharingInner.css( 'left', - ( sharingMargin + sharingWidth ) +'px' );
                    } else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Width
                        sharingInner.css( 'width', sharingWidth +'px' );

                        // Set Position
                        sharingInner.css( 'right', - ( sharingMargin + sharingWidth ) +'px' );
                    } else if ( 'top' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Margins
                        sharingInner.find( 'a' ).css({
                            'margin-right' : '0',
                            'margin-top' : sharingMargin +'px'
                        });

                        // Set Position
                        sharingInner.css({
                            'top' : -sharingMargin +'px',
                            'left' : '50%',
                            '-webkit-transform' : 'translate(-50%, -100%)',
                            'transform' : 'translate(-50%, -100%)'
                        });
                    } else if ( 'right' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Width
                        sharingInner.css( 'width', sharingWidth +'px' );

                        // Set Position
                        sharingInner.css({
                            'left' : sharingMargin +'px',
                            // 'bottom' : - ( sharingInner.outerHeight() + sharingTrigger.outerHeight() ) +'px',
                        });
                    } else if ( 'bottom' === sharingTrigger.attr( 'data-direction') ) {
                        // Set Margins
                        sharingInner.find( 'a' ).css({
                            'margin-right' : '0',
                            'margin-bottom' : sharingMargin +'px'
                        });

                        // Set Position
                        sharingInner.css({
                            'bottom' : -sharingMargin +'px',
                            'left' : '50%',
                            '-webkit-transform' : 'translate(-50%, 100%)',
                            'transform' : 'translate(-50%, 100%)'
                        });
                    }

                    if ( 'click' === sharingTrigger.attr( 'data-action' ) ) {
                        sharingTrigger.on( 'click', function() {
                            var sharingInner = $(this).next();

                            if ( 'hidden' === sharingInner.css( 'visibility' ) ) {
                                sharingInner.css( 'visibility', 'visible' );
                                sharingInner.find( 'a' ).css({
                                    'opacity' : '1',
                                    'top' : '0'
                                });

                                setTimeout( function() {
                                    sharingInner.find( 'a' ).addClass( 'crt-no-transition-delay' );
                                }, sharingInner.find( 'a' ).length * 100 );
                            } else {
                                sharingInner.find( 'a' ).removeClass( 'crt-no-transition-delay' );

                                sharingInner.find( 'a' ).css({
                                    'opacity' : '0',
                                    'top' : '-5px'
                                });
                                setTimeout( function() {
                                    sharingInner.css( 'visibility', 'hidden' );
                                }, sharingInner.find( 'a' ).length * 100 );
                            }
                        });
                    } else {
                        sharingTrigger.on( 'mouseenter', function() {
                            var sharingInner = $(this).next();

                            sharingInner.css( 'visibility', 'visible' );
                            sharingInner.find( 'a' ).css({
                                'opacity' : '1',
                                'top' : '0',
                            });

                            setTimeout( function() {
                                sharingInner.find( 'a' ).addClass( 'crt-no-transition-delay' );
                            }, sharingInner.find( 'a' ).length * 100 );
                        });
                        $scope.find( '.crt-grid-item-sharing' ).on( 'mouseleave', function() {
                            var sharingInner = $(this).find( '.crt-post-sharing-inner' );

                            sharingInner.find( 'a' ).removeClass( 'crt-no-transition-delay' );

                            sharingInner.find( 'a' ).css({
                                'opacity' : '0',
                                'top' : '-5px'
                            });
                            setTimeout( function() {
                                sharingInner.css( 'visibility', 'hidden' );
                            }, sharingInner.find( 'a' ).length * 100 );
                        });
                    }
                }
            }

            // Init Media Hover Link
            CrtElements.mediaHoverLink($scope, iGrid);

            function isValidHttpUrl(string) {
                let url;
                try {
                    url = new URL(string);
                } catch (_) {
                    return false;
                }
                return url.protocol === "http:" || url.protocol === "https:";
            }

            // Init Lightbox
            if ( !$scope.hasClass('elementor-widget-crt-woo-category-grid') && !$scope.hasClass('elementor-widget-crt-category-grid') ) {
                CrtElements.lightboxPopup( settings, $scope, iGrid );
            }

            // Init Likes
            postLikes( settings );

            // Likes
            function postLikes( settings ) {
                if ( ! $scope.find( '.crt-post-like-button' ).length ) {
                    return;
                }

                $scope.on('click', '.crt-post-like-button', function(e) {
                    e.preventDefault();

                    var current = $(this);

                    if ( '' !== current.attr( 'data-post-id' ) ) {

                        $.ajax({
                            type: 'POST',
                            url: $('<div>').text(current.attr('data-ajax')).html(),
                            data: {
                                action : 'crt_likes_init',
                                post_id : current.attr( 'data-post-id' ),
                                nonce : current.attr( 'data-nonce' )
                            },
                            beforeSend:function() {
                                current.fadeTo( 500, 0.5 );
                            },
                            success: function( response ) {
                                // Get Icon
                                var iconClass = $('<div>').text( current.attr('data-icon') ).html();

                                // Get Count
                                var countHTML = response.count;

                                if ( '' === countHTML.replace(/<\/?[^>]+(>|$)/g, "") ) {
                                    countHTML = '<span class="crt-post-like-count">' + $('<div>').text( current.attr('data-text') ).html() + '</span>';

                                    if ( ! current.hasClass( 'crt-likes-zero' ) ) {
                                        current.addClass( 'crt-likes-zero' );
                                    }
                                } else {
                                    current.removeClass( 'crt-likes-zero' );
                                }

                                // Update Icon
                                if ( current.hasClass( 'crt-already-liked' ) ) {
                                    current.prop( 'title', 'Like' );
                                    current.removeClass( 'crt-already-liked' );
                                    current.html( '<i class="'+ iconClass.replace( 'fas', 'far' ) +'"></i>' + countHTML );
                                } else {
                                    current.prop( 'title', 'Unlike' );
                                    current.addClass( 'crt-already-liked' );
                                    current.html( '<i class="'+ iconClass.replace( 'far', 'fas' ) +'"></i>' + countHTML );
                                }

                                current.fadeTo( 500, 1 );
                            }
                        });

                    }

                    return false;
                });
            }

            // Set equal height to all grid-items
            function setEqualHeight( settings ) {
                let iGrid = $scope.find( '.crt-grid' ),
                    items = iGrid.children('article'),
                    columns = Math.floor(iGrid.outerWidth() / items.outerWidth());

                if ( 'fitRows' === settings.layout && columns > 1 ) {
                    let maxHeight = Math.max.apply(null, items.map(function(item) {
                        return $(this).outerHeight();
                    }));

                    items.each(function() {
                        $(this).css('height', maxHeight + 'px');
                    });

                    if ( 'yes' === settings.stick_last_element_to_bottom ) {
                        $scope.addClass('crt-grid-last-element-yes');
                    }
                }
            }

            function lazyLoadObserver() {
                setTimeout(function() {
                    let lazyLoadObserver = new IntersectionObserver((entries, observer) => {
                        entries.forEach(entry => {
                            if(entry.isIntersecting && entry.target.src.includes('icon-256x256')) {
                                setTimeout(function() {
                                    entry.target.src = entry.target.parentElement.dataset.src;
                                    entry.target.classList.toggle('crt-hidden-image');
                                    $(window).trigger('resize');
                                }, 100);
                            }
                        });
                    }, {});

                    $scope.find('.crt-grid-image-wrap img:first-of-type').each(function() {
                        lazyLoadObserver.observe($(this)[0]);
                    });
                }, 100);
            }

            lazyLoadObserver();

            // Isotope Filters
            function isotopeFilters( settings, event = 'load' ) { // GOGA: event listener parameter to determine load or other event

                // Count
                if ( 'yes' === settings.filters_count ) {
                    $scope.find( '.crt-grid-filters a, .crt-grid-filters span' ).each(function() {
                        let thisFilterHTML = $(this),
                            numberOfPosts;

                        if ( settings.grid_settings && 'load' == event  ) {

                            var	thisTaxonomy = '*' !== $(this).attr( 'data-filter') ? $(this).data('ajax-filter')[0] : '*',
                                thisFilter = '*' !== $(this).attr( 'data-filter') ? $(this).data('ajax-filter')[1] : '*';

                            let data = {
                                action: experimentActionCount,
                                nonce: CrtConfig.nonce,
                                crt_offset: 0,
                                crt_filter: thisFilter,
                                crt_taxonomy: thisTaxonomy,
                                grid_settings: settings.grid_settings,
                            };

                            $.ajax({
                                type: 'POST',
                                url: CrtConfig.ajaxurl,
                                data: data,
                                success: function(response) {
                                    numberOfPosts = response.data.query_found;
                                    thisFilterHTML.find( 'sup' ).text( response.data.query_found );
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        } else {
                            if ( '*' === $(this).attr( 'data-filter') ) {
                                $(this).find( 'sup' ).text( $scope.find( '.crt-grid-filters' ).next().find('article').length );
                            } else {
                                $(this).find( 'sup' ).text( $scope.find( $(this).attr( 'data-filter' ) ).length );
                            }
                        }
                    });
                }

                // Return if Disabled
                if ( 'yes' === settings.filters_linkable ) {
                    return;
                }

                // Deeplinking on Load
                if ( 'yes' === settings.deeplinking ) {
                    var deepLink = window.location.hash.replace( '#filter:', '.' );

                    if ( window.location.hash.match( '#filter:all' ) ) {
                        deepLink = '*';
                    }

                    var activeFilter = $scope.find( '.crt-grid-filters span[data-filter="'+ deepLink +'"]:not(.crt-back-filter)' ),
                        activeFilterWrap = activeFilter.parent();

                    // Sub Filters
                    if ( 'parent' === activeFilter.parent().attr( 'data-role' ) ) {
                        if ( activeFilterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ deepLink +'"]').length ) {
                            activeFilterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
                            activeFilterWrap.siblings( 'ul[data-parent="'+ deepLink +'"]' ).css( 'display', 'block' );
                        }
                    } else if ( 'sub' === activeFilter.parent().attr( 'data-role' ) ) {
                        activeFilterWrap.closest( '.crt-grid-filters' ).children( 'li' ).css( 'display', 'none' );
                        activeFilterWrap.parent( 'ul' ).css( 'display', 'inline-block' );
                    }

                    // Active Filter Class
                    $scope.find( '.crt-grid-filters span' ).removeClass( 'crt-active-filter' );
                    activeFilter.addClass( 'crt-active-filter' );

                    $scope.find( '.crt-grid' ).isotopecrt({ filter: deepLink });

                    // Fix Lightbox
                    if ( '*' !== deepLink ) {
                        settings.lightbox.selector = deepLink +' .crt-grid-image-wrap';
                    } else {
                        settings.lightbox.selector = ' .crt-grid-image-wrap';
                    }

                    CrtElements.lightboxPopup( settings, $scope, iGrid );
                }

                // Hide Empty Filters
                if ( 'yes' === settings.filters_hide_empty ) {
                    $scope.find( '.crt-grid-filters span' ).each(function() {
                        var searchClass = $(this).attr( 'data-filter' );

                        if ( '*' !== searchClass ) {
                            if ( 0 === iGrid.find(searchClass).length ) {
                                $(this).parent( 'li' ).addClass( 'crt-hidden-element' );
                            } else {
                                $(this).parent( 'li' ).removeClass( 'crt-hidden-element' );
                            }
                        }
                    });
                }

                // Set a Default Filter
                if ( !$scope.hasClass('elementor-widget-crt-woo-category-grid') && !$scope.hasClass('elementor-widget-crt-category-grid') ) {
                    if ( '' !== settings.filters_default_filter ) {
                        setTimeout(function() {
                            $scope.find( '.crt-grid-filters' ).find('span[data-filter*="-'+ settings.filters_default_filter +'"]')[0].click();
                        }, 100)
                    }
                }

                // Click Event
                if ( !settings.grid_settings ) {
                    $scope.find( '.crt-grid-filters span' ).on( 'click', function() {
                        CrtElements.changeInitialItems(0);

                        var filterClass = $(this).data( 'filter' ),
                            filterWrap = $(this).parent( 'li' ),
                            filterRole = filterWrap.attr( 'data-role' );

                        // Active Filter Class
                        $scope.find( '.crt-grid-filters span' ).removeClass( 'crt-active-filter' );
                        $(this).addClass( 'crt-active-filter' );

                        // Sub Filters
                        if ( 'parent' === filterRole ) {
                            if ( filterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ filterClass +'"]' ).length ) {
                                filterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
                                filterWrap.siblings( 'ul[data-parent="'+ filterClass +'"]' ).css( 'display', 'block' );
                            }
                        } else if ( 'back' === filterRole ) {
                            filterWrap.closest( '.crt-grid-filters' ).children( 'li' ).css( 'display', 'inline-block' );
                            filterWrap.parent().css( 'display', 'none' );
                        }

                        // Deeplinking
                        if ( 'yes' === settings.deeplinking ) {
                            var filterHash = '#filter:'+ filterClass.replace( '.', '' );

                            if ( '*' === filterClass ) {
                                filterHash = '#filter:all';
                            }

                            window.location.href = window.location.pathname + window.location.search + filterHash;
                        }

                        // Infinite Scroll
                        if ( 'infinite-scroll' === settings.pagination_type ) {
                            if ( 0 === iGrid.find($(this).attr('data-filter')).length ) {
                                $scope.find( '.crt-grid' ).infiniteScroll( 'loadNextPage' );
                            }
                        }

                        // Load More
                        if ( 'load-more' === settings.pagination_type ) {
                            if ( 0 === iGrid.find($(this).attr('data-filter')).length ) {
                                $scope.find( '.crt-grid' ).infiniteScroll( 'loadNextPage' );
                            }
                        }

                        // Filtering Animation
                        if ( 'default' !== settings.filters_animation ) {
                            $scope.find( '.crt-grid-item-inner' ).css({
                                'opacity' : '0',
                                'transition' : 'none'
                            });
                        }

                        if ( 'fade-slide' === settings.filters_animation ) {
                            $scope.find( '.crt-grid-item-inner' ).css( 'top', '20px' );
                        } else if ( 'zoom' === settings.filters_animation ) {
                            $scope.find( '.crt-grid-item-inner' ).css( 'transform', 'scale(0.01)' );
                        } else {
                            $scope.find( '.crt-grid-item-inner' ).css({
                                'top' : '0',
                                'transform' : 'scale(1)'
                            });
                        }

                        // Filter Grid Items
                        $scope.find( '.crt-grid' ).isotopecrt({ filter: filterClass });

                        // Fix Lightbox
                        if ( '*' !== filterClass ) {
                            settings.lightbox.selector = filterClass +' .crt-grid-image-wrap';
                        } else {
                            settings.lightbox.selector = ' .crt-grid-image-wrap';
                        }

                        // Destroy Lightbox
                        iGrid.data('lightGallery').destroy( true );
                        // Init Lightbox
                        iGrid.lightGallery( settings.lightbox );
                    });
                }
            }

            function checkWishlistAndCompare() {
                var wishlistArray;

                if ( iGrid.find('.crt-wishlist-add').length ) {

                    $.ajax({
                        url: CrtConfig.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'check_product_in_wishlist_grid',
                        },
                        success: function(response) {
                            wishlistArray = response;
                        }
                    });


                    iGrid.find('.crt-wishlist-add').each(function() {
                        var wishlistBtn = $(this);

                        if ( $.inArray(wishlistBtn.data('product-id'), wishlistArray) !== -1 ) {
                            if ( !wishlistBtn.hasClass('crt-button-hidden') ) {
                                wishlistBtn.addClass('crt-button-hidden');
                            }

                            if ( wishlistBtn.next().hasClass('crt-button-hidden') ) {
                                wishlistBtn.next().removeClass('crt-button-hidden');
                            }
                        }
                    });
                }

                if ( iGrid.find('.crt-compare-add').length > 0 ) {
                    var compareArray = [];

                    $.ajax({
                        url: CrtConfig.ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'check_product_in_compare_grid',
                        },
                        success: function(response) {
                            compareArray = response;
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });


                    iGrid.find('.crt-compare-add').each(function() {
                        var compareBtn = $(this);

                        if ( $.inArray(compareBtn.data('product-id'), compareArray) !== -1 ) {
                            if ( !compareBtn.hasClass('crt-button-hidden') ) {
                                compareBtn.addClass('crt-button-hidden');
                            }

                            if ( compareBtn.next().hasClass('crt-button-hidden') ) {
                                compareBtn.next().removeClass('crt-button-hidden');
                            }
                        }
                    });

                }
            }

            function addRemoveCompare() {
                if ( iGrid.find('.crt-compare-add').length ) {
                    $scope.find('.crt-compare-add').click(function(e) {
                        e.preventDefault();
                        var event_target = $(this);
                        var product_id = $(this).data('product-id');

                        event_target.fadeTo(500, 0);

                        $.ajax({
                            url: CrtConfig.ajaxurl,
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

                                if ( 'sidebar' === event_target.data('atcompare-popup') ) {
                                    // GOGA - configure after adding compare dropdown functinality
                                    if ( $('.crt-compare-toggle-btn').length ) {
                                        $('.crt-compare-toggle-btn').each(function() {
                                            if ( 'none' === $(this).next('.crt-compare').css('display') ) {
                                                $(this).trigger('click');
                                            }
                                        });
                                    }
                                } else if ( 'popup' === event_target.data('atcompare-popup') ) {
                                    // Popup Link needs wishlist
                                    var popupItem = event_target.closest('.crt-grid-item'),
                                        popupText = popupItem.find('.crt-grid-item-title').text(),
                                        popupLink = CrtConfig.comparePageURL,
                                        popupTarget = 'yes' == event_target.data('open-in-new-tab') ? '_blank' : '_self',
                                        popupImageSrc = popupItem.find('.crt-grid-image-wrap').length ? popupItem.find('.crt-grid-image-wrap').data('src') : '',
                                        popupAnimation = event_target.data('atcompare-animation'),
                                        fadeOutIn = event_target.data('atcompare-fade-out-in'),
                                        animTime = event_target.data('atcompare-animation-time'),
                                        popupImage,
                                        animationClass = 'crt-added-to-compare-default',
                                        removeAnimationClass;

                                    if ( 'slide-left' === popupAnimation ) {
                                        animationClass = 'crt-added-to-compare-slide-in-left';
                                        removeAnimationClass = 'crt-added-to-compare-slide-out-left';
                                    } else if ( 'scale-up' === popupAnimation ) {
                                        animationClass = 'crt-added-to-compare-scale-up';
                                        removeAnimationClass = 'crt-added-to-compare-scale-down';
                                    } else if ( 'skew' === popupAnimation ) {
                                        animationClass = 'crt-added-to-compare-skew';
                                        removeAnimationClass = 'crt-added-to-compare-skew-off';
                                    } else if ( 'fade' === popupAnimation ) {
                                        animationClass = 'crt-added-to-compare-fade';
                                        removeAnimationClass = 'crt-added-to-compare-fade-out';
                                    } else {
                                        removeAnimationClass = 'crt-added-to-compare-popup-hide';
                                    }

                                    if ( '' !== popupImageSrc ) {
                                        popupImage = '<div class="crt-added-tcomp-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
                                    } else {
                                        popupImage = '';
                                    }

                                    if ( !($scope.find('.crt-grid').find('#crt-added-to-comp-'+product_id).length > 0) ) {
                                        $scope.find('.crt-grid').append('<div id="crt-added-to-comp-'+product_id+'" class="crt-added-to-compare-popup ' + animationClass + '">'+ popupImage +'<div class="crt-added-tc-title"><p>'+ popupText +' was added to Compare</p><p><a target='+ popupTarget +' href='+popupLink+'>View Compare</a></p></div></div>');

                                        setTimeout(() => {
                                            $scope.find('#crt-added-to-comp-'+product_id).addClass(removeAnimationClass);
                                            setTimeout(() => {
                                                $scope.find('#crt-added-to-comp-'+product_id).remove();
                                            }, animTime * 1000);
                                        }, fadeOutIn * 1000);
                                    }
                                }
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
                            url: CrtConfig.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'remove_from_compare',
                                nonce: CrtConfig.nonce,
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

                }
            }

            function addRemoveWishlist() {
                let isPopupActive = false;
                if ( iGrid.find('.crt-wishlist-add').length ) {
                    $scope.find('.crt-wishlist-add').click(function(e) {
                        e.preventDefault();
                        var event_target = $(this);
                        var product_id = $(this).data('product-id');

                        event_target.fadeTo(500, 0);

                        $.ajax({
                            url: CrtConfig.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'add_to_wishlist',
                                nonce: CrtConfig.nonce,
                                product_id: product_id
                            },
                            success: function() {
                                $scope.find('.crt-wishlist-add[data-product-id="' + product_id + '"]').hide();
                                $scope.find('.crt-wishlist-remove[data-product-id="' + product_id + '"]').show();
                                $scope.find('.crt-wishlist-remove[data-product-id="' + product_id + '"]').fadeTo(500, 1);
                                localStorage.setItem('changeActionTargetProductId', product_id);
                                $(document).trigger('added_to_wishlist');

                                if ( 'sidebar' === event_target.data('atw-popup') ) {
                                    // GOGA - configure after adding wishlist dropdown functinality
                                    if ( $('.crt-wishlist-toggle-btn').length ) {
                                        $('.crt-wishlist-toggle-btn').each(function() {
                                            if ( 'none' === $(this).next('.crt-wishlist').css('display') ) {
                                                $(this).trigger('click');
                                            }
                                        });
                                    }
                                } else if ( 'popup' === event_target.data('atw-popup') ) {
                                    // Popup Link needs wishlist
                                    var popupItem = event_target.closest('.crt-grid-item'),
                                        popupText = popupItem.find('.crt-grid-item-title').text(),
                                        popupLink = CrtConfig.wishlistPageURL,
                                        popupTarget = 'yes' == event_target.data('open-in-new-tab') ? '_blank' : '_self',
                                        popupImageSrc = popupItem.find('.crt-grid-image-wrap').length ? popupItem.find('.crt-grid-image-wrap').data('src') : '',
                                        popupAnimation = event_target.data('atw-animation'),
                                        fadeOutIn = event_target.data('atw-fade-out-in'),
                                        animTime = event_target.data('atw-animation-time'),
                                        popupImage,
                                        animationClass = 'crt-added-to-wishlist-default',
                                        removeAnimationClass;

                                    if ( 'slide-left' === popupAnimation ) {
                                        animationClass = 'crt-added-to-wishlist-slide-in-left';
                                        removeAnimationClass = 'crt-added-to-wishlist-slide-out-left';
                                    } else if ( 'scale-up' === popupAnimation ) {
                                        animationClass = 'crt-added-to-wishlist-scale-up';
                                        removeAnimationClass = 'crt-added-to-wishlist-scale-down';
                                    } else if ( 'skew' === popupAnimation ) {
                                        animationClass = 'crt-added-to-wishlist-skew';
                                        removeAnimationClass = 'crt-added-to-wishlist-skew-off';
                                    } else if ( 'fade' === popupAnimation ) {
                                        animationClass = 'crt-added-to-wishlist-fade';
                                        removeAnimationClass = 'crt-added-to-wishlist-fade-out';
                                    } else {
                                        removeAnimationClass = 'crt-added-to-wishlist-popup-hide';
                                    }

                                    if ( '' !== popupImageSrc ) {
                                        popupImage = '<div class="crt-added-tw-popup-img"><img src='+popupImageSrc+' alt="" /></div>';
                                    } else {
                                        popupImage = '';
                                    }
                                    if (!isPopupActive) {
                                        isPopupActive = true;

                                        if ( !($scope.find('.crt-grid').find('#crt-added-to-wish-'+product_id).length > 0) ) {
                                            $scope.find('.crt-grid').append('<div id="crt-added-to-wish-'+product_id+'" class="crt-added-to-wishlist-popup ' + animationClass + '">'+ popupImage +'<div class="crt-added-tw-title"><p>'+ popupText +' was added to Wishlist</p><p><a target="'+ popupTarget +'" href='+popupLink+'>View Wishlist</a></p></div></div>');

                                            setTimeout(() => {
                                                $scope.find('#crt-added-to-wish-'+product_id).addClass(removeAnimationClass);
                                                setTimeout(() => {
                                                    $scope.find('#crt-added-to-wish-'+product_id).remove();
                                                }, animTime * 1000);
                                            }, fadeOutIn * 1000);
                                        }
                                    }
                                }
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
                            url: CrtConfig.ajaxurl,
                            type: 'POST',
                            data: {
                                action: 'remove_from_wishlist',
                                nonce: CrtConfig.nonce,
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

                }
            }

            function filtersExperiment() {
                let currentRequest = null;
                $scope.find('.crt-grid-filters').on('click', 'span', function(event) {
                    if (currentRequest) {
                        currentRequest.abort(); // Abort the previous request
                    }

                    pagesLoadedExperiment = 0;
                    var thisTaxonomy,
                        thisFilter,
                        loader = '<div class="crt-grid-loader-wrap"><div class="crt-ring"><div></div><div></div><div></div><div></div></div></div>';

                    var filterClass = $(this).data( 'filter' ),
                        filterWrap = $(this).parent( 'li' ),
                        filterRole = filterWrap.attr( 'data-role' );

                    if ( $(this).data('filter') !== '*' ) {
                        thisTaxonomy = $(this).data('ajax-filter')[0];
                        thisFilter = $(this).data('ajax-filter')[1];
                    } else {
                        thisTaxonomy = '*';
                        thisFilter = '*';
                    }

                    // Sub Filters
                    if ( 'parent' === filterRole ) {
                        if ( filterWrap.parent( 'ul' ).find( 'ul[data-parent="'+ filterClass +'"]').length ) {
                            filterWrap.parent( 'ul' ).children( 'li' ).css( 'display', 'none' );
                            filterWrap.siblings( 'ul[data-parent="'+ filterClass +'"]' ).css( 'display', 'block' );
                        }
                    } else if ( 'back' === filterRole ) {
                        filterWrap.closest( '.crt-grid-filters' ).children( 'li' ).css( 'display', 'inline-block' );
                        filterWrap.parent().css( 'display', 'none' );
                    }

                    $scope.find('.crt-grid-filters span').removeClass('crt-active-filter');
                    $(this).addClass('crt-active-filter');

                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();

                    iGrid.isotopecrt('remove', iGrid.children('.crt-grid-item'));
                    if ( iGrid.find('.crt-grid-loader-wrap').length < 1 ) {
                        iGrid.append(loader);
                    }
                    const start = performance.now();
                    $.ajax({
                        type: 'POST',
                        url: CrtConfig.ajaxurl,
                        data: {
                            action: experimentActionCount,
                            nonce: CrtConfig.nonce,
                            // crt_offset: +settings.grid_settings.query_offset + $scope.find('.crt-grid-item').length,
                            crt_offset: +settings.grid_settings.query_offset,
                            crt_filter: thisFilter,
                            crt_taxonomy: thisTaxonomy,
                            grid_settings: settings.grid_settings,
                        },
                        success: function(response) {
                            var pageCount = response.data.page_count;
                            var foundPosts = response.data.query_found;
                            const end = performance.now();
                            // console.log(`AJAX call took ${end - start} ms`);

                            const start2 = performance.now();
                            currentRequest = $.ajax({
                                type: 'POST',
                                url: CrtConfig.ajaxurl,
                                data: {
                                    action: experimentActionContent,
                                    nonce: CrtConfig.nonce,
                                    crt_item_length: +settings.grid_settings.query_offset + $scope.find('.crt-grid-item').length,
                                    crt_filter: thisFilter,
                                    crt_taxonomy: thisTaxonomy,
                                    grid_settings: settings.grid_settings,
                                },
                                success: function( response ) {
                                    setTimeout(function() {
                                        // iGrid.addClass('crt-zero-opacity');
                                        // $scope.find('.crt-grid').html($(response));
                                        // CrtElements.isotopeLayout( settings, '', '', $scope );
                                        const end2 = performance.now();
                                        // console.log(`AJAX call took ${end2 - start2} ms`);

                                        var newItems;

                                        if ( response.data && response.data.output ) {
                                            newItems = $(response.data.output);
                                        } else {
                                            newItems = $(response);
                                        }

                                        iGrid.find('.crt-grid-loader-wrap').remove();
                                        iGrid.addClass('crt-fix-grid-height');
                                        iGrid.append(newItems).isotopecrt('appended', newItems).isotopecrt('layout');
                                        setTimeout(function() {
                                            iGrid.removeClass('crt-fix-grid-height');
                                        }, 500);

                                        if ( settings.pagination_type == 'load-more' && pagination ) { // needs check if more posts in tax
                                            pagination.find( '.crt-pagination-finish' ).fadeOut( 100 );
                                            pagination.delay( 2000 ).fadeIn( 100 );

                                            if ( pageCount <= 1 && foundPosts <= newItems.length ) {
                                                // if ( items.length !== 0 ) {}
                                                if ( 'load-more' === settings.pagination_type ) {
                                                    pagination.find( '.crt-load-more-btn' ).fadeOut();
                                                }
                                            }

                                            if ( pageCount > 1 ) {
                                                // if ( items.length !== 0 ) {}
                                                if ( 'load-more' === settings.pagination_type ) {
                                                    pagination.find( '.crt-load-more-btn' ).fadeIn();
                                                }
                                            }
                                        }

                                        CrtElements.isotopeLayout( settings, '', '', $scope );
                                        CrtElements.lightboxPopup( settings, $scope, iGrid );

                                        // Fix Lightbox
                                        iGrid.data( 'lightGallery' ).destroy( true );
                                        iGrid.lightGallery( settings.lightbox );

                                        setTimeout(function() {
                                            CrtElements.isotopeLayout( settings, '', '', $scope );

                                            window.dispatchEvent(new Event('resize'));
                                            window.dispatchEvent(new Event('scroll'));
                                        }, 500);

                                        CrtElements.mediaHoverLink($scope, iGrid);
                                        lazyLoadObserver();
                                        // iGrid.removeClass('crt-zero-opacity');
                                        CrtElements.changeInitialItems(0);
                                    }, 800);
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                });
            }

            function loadMoreExperiment() {
                $scope.find('.crt-load-more-btn').on('click', function(event) {
                    event.preventDefault();

                    var thisTaxonomy,
                        thisFilter;

                    if ( $scope.find('.crt-active-filter').length && $scope.find('.crt-active-filter').data('filter') !== '*' ) {
                        thisTaxonomy = $scope.find('.crt-active-filter').data('ajax-filter')[0];
                        thisFilter = $scope.find('.crt-active-filter').data('ajax-filter')[1];
                    } else {
                        thisTaxonomy = '*';
                        thisFilter = '*';
                    }

                    event.preventDefault();
                    event.stopPropagation();
                    event.stopImmediatePropagation();

                    // Store the current scroll position
                    var scrollTop = $(window).scrollTop();

                    pagination.find( '.crt-load-more-btn' ).hide();
                    pagination.find( '.crt-pagination-loading' ).css( 'display', 'inline-block' );

                    $.ajax({
                        type: 'POST',
                        url: CrtConfig.ajaxurl,
                        data: {
                            action: experimentActionCount,
                            nonce: CrtConfig.nonce,
                            crt_offset: +settings.grid_settings.query_offset + $scope.find('.crt-grid-item').length,
                            crt_filter: thisFilter,
                            crt_taxonomy: thisTaxonomy,
                            grid_settings: settings.grid_settings,
                        },
                        success: function(response) {
                            var pageCount = response.data.page_count;

                            $.ajax({
                                type: 'POST',
                                url: CrtConfig.ajaxurl,
                                data: {
                                    action: experimentActionContent,
                                    nonce: CrtConfig.nonce,
                                    crt_offset: +settings.grid_settings.query_offset + $scope.find('.crt-grid-item').length,
                                    crt_filter: thisFilter,
                                    crt_taxonomy: thisTaxonomy,
                                    grid_settings: settings.grid_settings,
                                },
                                success: function( response ) {
                                    pagesLoadedExperiment++;
                                    var items;
                                    // iGrid.css('opacity', 0);
                                    if ( response.data && response.data.output ) {
                                        items = $(response.data.output);
                                    } else {
                                        items = $(response);
                                    }

                                    // $data.each(function() {
                                    // 	$(this).addClass('crt-grid-hidden-item');
                                    // });

                                    iGrid.infiniteScroll( 'appendItems', items );
                                    iGrid.isotopecrt( 'appended', items );
                                    // isotopeFilters( settings ); // GOGA - if not images loaded

                                    items.imagesLoaded().progress( function( instance, image ) {
                                        CrtElements.isotopeLayout( settings, '', '', $scope );

                                        // Fix Layout
                                        setTimeout(function() {
                                            CrtElements.isotopeLayout( settings, '', '', $scope );
                                            // isotopeFilters( settings );
                                        }, 100 );

                                        setTimeout(function() {
                                            iGrid.addClass( 'grid-images-loaded' );
                                        }, 500 );
                                    });

                                    // Loading
                                    pagination.find( '.crt-pagination-loading' ).hide();
                                    if ( pageCount > 1 ) {
                                        // if ( items.length !== 0 ) {
                                        if ( 'load-more' === settings.pagination_type ) {
                                            pagination.find( '.crt-load-more-btn' ).fadeIn();
                                        }
                                    } else {
                                        pagination.find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                        pagination.delay( 2000 ).fadeOut( 1000 );
                                        setTimeout(function() {
                                            pagination.find( '.crt-pagination-loading' ).hide();
                                        }, 500 );
                                    }

                                    // Init Likes
                                    // No need for this anymore
                                    // setTimeout(function() {
                                    // 	postLikes( settings );
                                    // }, 300 );

                                    // Init Lightbox
                                    CrtElements.lightboxPopup( settings, $scope, iGrid );

                                    // Fix Lightbox
                                    iGrid.data( 'lightGallery' ).destroy( true );
                                    iGrid.lightGallery( settings.lightbox );

                                    // Init Media Hover Link
                                    CrtElements.mediaHoverLink($scope, iGrid);

                                    // Init Post Sharing
                                    postSharing();

                                    lazyLoadObserver();

                                    // Maybe there is some other way
                                    window.dispatchEvent(new Event('resize'));
                                    window.dispatchEvent(new Event('scroll'));
                                    $(window).trigger('scroll');
                                },
                                error: function(error) {
                                    console.log(error);
                                }
                            });
                        }
                    });
                });
            }
        });

        var CrtElements = {
            isotopeLayout: function ( settings, $response = '', $altScope = '', $scope = '' ) {
                if ( '' != $altScope ) {
                    $scope = $altScope;
                }

                var grid = $scope.find( '.crt-grid' ),
                    item = grid.find( '.crt-grid-item' ),
                    itemVisible = item.filter( ':visible' ),
                    layout = settings.layout,
                    defaultLayout = settings.layout,
                    mediaAlign = settings.media_align,
                    mediaWidth = settings.media_width,
                    mediaDistance = settings.media_distance,
                    columns = 3,
                    columnsMobile = 1,
                    columnsMobileExtra,
                    columnsTablet = 2,
                    columnsTabletExtra,
                    columnsDesktop = parseInt(settings.columns_desktop, 10),
                    columnsLaptop,
                    columnsWideScreen,
                    gutterHr = settings.gutter_hr,
                    gutterVr = settings.gutter_vr,
                    gutterHrMobile = settings.gutter_hr_mobile,
                    gutterVrMobile = settings.gutter_vr_mobile,
                    gutterHrMobileExtra = settings.gutter_hr_mobile_extra,
                    gutterVrMobileExtra = settings.gutter_vr_mobile_extra,
                    gutterHrTablet = settings.gutter_hr_tablet,
                    gutterVrTablet = settings.gutter_vr_tablet,
                    gutterHrTabletExtra = settings.gutter_hr_tablet_extra,
                    gutterVrTabletExtra = settings.gutter_vr_tablet_extra,
                    gutterHrWideScreen = settings.gutter_hr_widescreen,
                    gutterVrWideScreen = settings.gutter_vr_widescreen,
                    gutterHrLaptop = settings.gutter_hr_laptop,
                    gutterVrLaptop = settings.gutter_vr_laptop,
                    contWidth = grid.width() + gutterHr - 0.3,
                    // viewportWidth = $( 'body' ).prop( 'clientWidth' ),
                    viewportWidth = $(window).outerWidth(),
                    defaultLayout,
                    transDuration = 400;

                if ( $response != '' ) {
                    item = $response
                }

                // Get Responsive Columns
                var prefixClass = $scope.attr('class'),
                    prefixClass = prefixClass.split(' ');

                for ( var i=0; i < prefixClass.length - 1; i++ ) {

                    if ( -1 !== prefixClass[i].search(/mobile\d/) ) {
                        columnsMobile = prefixClass[i].slice(-1);
                    }

                    if ( -1 !== prefixClass[i].search(/mobile_extra\d/) ) {
                        columnsMobileExtra = prefixClass[i].slice(-1);
                    }

                    if ( -1 !== prefixClass[i].search(/tablet\d/) ) {
                        columnsTablet = prefixClass[i].slice(-1);
                    }

                    if ( -1 !== prefixClass[i].search(/tablet_extra\d/) ) {
                        columnsTabletExtra = prefixClass[i].slice(-1);
                    }

                    if ( -1 !== prefixClass[i].search(/widescreen\d/) ) {
                        columnsWideScreen = prefixClass[i].slice(-1);
                    }

                    if ( -1 !== prefixClass[i].search(/laptop\d/) ) {
                        columnsLaptop = prefixClass[i].slice(-1);
                    }
                }

                var MobileResp = +elementorFrontend.config.responsive.breakpoints.mobile.value;
                var MobileExtraResp = +elementorFrontend.config.responsive.breakpoints.mobile_extra.value;
                var TabletResp = +elementorFrontend.config.responsive.breakpoints.tablet.value;
                var TabletExtraResp = +elementorFrontend.config.responsive.breakpoints.tablet_extra.value;
                var LaptopResp = +elementorFrontend.config.responsive.breakpoints.laptop.value;
                var wideScreenResp = +elementorFrontend.config.responsive.breakpoints.widescreen.value;

                var activeBreakpoints = elementorFrontend.config.responsive.activeBreakpoints;

                // Mobile
                if ( MobileResp >= viewportWidth && activeBreakpoints.mobile != null ) {
                    columns = columnsMobile;
                    gutterHr = gutterHrMobile;
                    gutterVr = gutterVrMobile;

                    // Mobile Extra
                } else if ( MobileExtraResp >= viewportWidth && activeBreakpoints.mobile_extra != null ) {
                    columns = (columnsMobileExtra) ? columnsMobileExtra : columnsTablet;
                    gutterHr = gutterHrMobileExtra;
                    gutterVr = gutterVrMobileExtra;

                    // Tablet
                } else if ( TabletResp >= viewportWidth && activeBreakpoints.tablet != null ) {
                    columns = columnsTablet;
                    gutterHr = gutterHrTablet;
                    gutterVr = gutterVrTablet;

                    // Tablet Extra
                } else if ( TabletExtraResp >= viewportWidth && activeBreakpoints.tablet_extra != null ) {
                    columns = (columnsTabletExtra) ? columnsTabletExtra : columnsTablet;
                    gutterHr = gutterHrTabletExtra;
                    gutterVr = gutterVrTabletExtra;

                    // Laptop
                } else if ( LaptopResp >= viewportWidth && activeBreakpoints.laptop != null ) {
                    columns = (columnsLaptop) ? columnsLaptop : columnsDesktop;
                    gutterHr = gutterHrLaptop;
                    gutterVr = gutterVrLaptop;

                    // Desktop
                } else if ( wideScreenResp > viewportWidth ) {
                    columns = columnsDesktop;
                    gutterHr = settings.gutter_hr;
                    gutterVr = settings.gutter_vr;
                }  else {
                    columns = (columnsWideScreen) ? columnsWideScreen : columnsDesktop;
                    gutterHr = gutterHrWideScreen;
                    gutterVr = gutterVrWideScreen;
                }

                // Limit Columns for Higher Screens
                if ( columns > 8 ) {
                    columns = 8;
                }

                if ( 'string' == typeof(columns) && -1 !== columns.indexOf('pro') ) {
                    columns = 3;
                }

                contWidth = grid.width() + gutterHr - 0.3;

                // Calculate Item Width
                item.outerWidth( Math.floor( contWidth / columns - gutterHr ) );

                // Set Vertical Gutter
                item.css( 'margin-bottom', gutterVr +'px' );

                // Reset Vertical Gutter for 1 Column Layout
                if ( 1 === columns ) {
                    item.last().css( 'margin-bottom', '0' );
                }

                // add last row & make all post equal height
                var maxTop = -1;
                itemVisible.each(function ( index ) {

                    // define
                    var thisHieght = $(this).outerHeight(),
                        thisTop = parseInt( $(this).css( 'top' ) , 10 );

                    // determine last row
                    if ( thisTop > maxTop ) {
                        maxTop = thisTop;
                    }

                });

                if ( 'fitRows' === layout ) {
                    itemVisible.each(function() {
                        if ( parseInt( $(this).css( 'top' ) ) === maxTop  ) {
                            $(this).addClass( 'rf-last-row' );
                        }
                    });
                }

                // List Layout
                if ( 'list' === layout ) {
                    var imageHeight = item.find( '.crt-grid-image-wrap' ).outerHeight();
                    item.find( '.crt-grid-item-below-content' ).css( 'min-height', imageHeight +'px' );

                    if ( $( 'body' ).prop( 'clientWidth' ) < 480 ) {

                        item.find( '.crt-grid-media-wrap' ).css({
                            'float' : 'none',
                            'width' : '100%'
                        });

                        item.find( '.crt-grid-item-below-content' ).css({
                            'float' : 'none',
                            'width' : '100%',
                        });

                        item.find( '.crt-grid-image-wrap' ).css( 'padding', '0' );

                        item.find( '.crt-grid-item-below-content' ).css( 'min-height', '0' );

                        if ( 'zigzag' === mediaAlign ) {
                            item.find( '[class*="elementor-repeater-item"]' ).css( 'text-align', 'center' );
                        }

                    } else {

                        if ( 'zigzag' !== mediaAlign ) {

                            item.find( '.crt-grid-media-wrap' ).css({
                                'float' : mediaAlign,
                                'width' : mediaWidth +'%'
                            });

                            var listGutter = 'left' === mediaAlign ? 'margin-right' : 'margin-left';
                            item.find( '.crt-grid-media-wrap' ).css( listGutter, mediaDistance +'px' );

                            item.find( '.crt-grid-item-below-content' ).css({
                                'float' : mediaAlign,
                                'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
                            });

                            // Zig-zag
                        } else {
                            // Even
                            item.filter(':even').find( '.crt-grid-media-wrap' ).css({
                                'float' : 'left',
                                'width' : mediaWidth +'%'
                            });
                            item.filter(':even').find( '.crt-grid-item-below-content' ).css({
                                'float' : 'left',
                                'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
                            });
                            item.filter(':even').find( '.crt-grid-media-wrap' ).css( 'margin-right', mediaDistance +'px' );

                            // Odd
                            item.filter(':odd').find( '.crt-grid-media-wrap' ).css({
                                'float' : 'right',
                                'width' : mediaWidth +'%'
                            });
                            item.filter(':odd').find( '.crt-grid-item-below-content' ).css({
                                'float' : 'right',
                                'width' : 'calc((100% - '+ mediaWidth +'%) - '+ mediaDistance +'px)',
                            });
                            item.filter(':odd').find( '.crt-grid-media-wrap' ).css( 'margin-left', mediaDistance +'px' );

                            // Fix Elements Align
                            if ( ! grid.hasClass( 'crt-grid-list-ready' ) ) {
                                item.each( function( index ) {
                                    var element = $(this).find( '[class*="elementor-repeater-item"]' );

                                    if ( index % 2 === 0 ) {
                                        element.each(function() {
                                            if ( ! $(this).hasClass( 'crt-grid-item-align-center' ) ) {
                                                if ( 'none' === $(this).css( 'float' ) ) {
                                                    $(this).css( 'text-align', 'left' );
                                                } else {
                                                    $(this).css( 'float', 'left' );
                                                }

                                                var inner = $(this).find( '.inner-block' );
                                            }
                                        });
                                    } else {
                                        element.each(function( index ) {
                                            if ( ! $(this).hasClass( 'crt-grid-item-align-center' ) ) {
                                                if ( 'none' === $(this).css( 'float' ) ) {
                                                    $(this).css( 'text-align', 'right' );
                                                } else {
                                                    $(this).css( 'float', 'right' );
                                                }

                                                var inner = $(this).find( '.inner-block' );

                                                if ( '0px' !== inner.css( 'margin-left' ) ) {
                                                    inner.css( 'margin-right', inner.css( 'margin-left' ) );
                                                    inner.css( 'margin-left', '0' );
                                                }

                                                // First Item
                                                if ( 0 === index ) {
                                                    if ( '0px' !== inner.css( 'margin-right' ) ) {
                                                        inner.css( 'margin-left', inner.css( 'margin-right' ) );
                                                        inner.css( 'margin-right', '0' );
                                                    }
                                                }
                                            }
                                        });
                                    }
                                });

                            }

                            setTimeout(function() {
                                if ( ! grid.hasClass( 'crt-grid-list-ready' ) ) {
                                    grid.addClass( 'crt-grid-list-ready' );
                                }
                            }, 500 );
                        }

                    }
                }

                // Set Layout
                defaultLayout = layout;
                if ( 'list' === layout ) {
                    layout = 'fitRows';
                }

                // No Transition
                if ( 'default' !== settings.filters_animation ) {
                    transDuration = 0;
                }

                // Run Isotope
                var iGrid = grid.isotopecrt({
                    layoutMode: layout,
                    masonry: {
                        // columnWidth: contWidth / columns,
                        gutter: gutterHr
                    },
                    fitRows: {
                        // columnWidth: contWidth / columns,
                        gutter: gutterHr
                    },
                    transitionDuration: transDuration,
                    percentPosition: true
                });
            },
            changeInitialItems: function(items) {
                initialItems = items;
            },
            mediaHoverLink: function ($scope, iGrid) {
                var img;
                var thisImgSrc;
                let secondaryImg;

                iGrid.find('.crt-grid-media-wrap').on('mouseover', function() {
                    if ( 'yes' === $(this).find('.crt-grid-image-wrap').attr('data-img-on-hover') ) {
                        // img = $(this).find( 'img' );
                        // thisImgSrc = img.attr('src');

                        // secondaryImg = $(this).find('.crt-grid-image-wrap').data('src-secondary');

                        // if ( isValidHttpUrl(secondaryImg) ) {
                        // 	img.attr( 'src', secondaryImg );
                        // }

                        if ( $(this).find('img:nth-of-type(2)').attr('src') !== undefined && $(this).find('img:nth-of-type(2)').attr('src') !== '' ) {
                            // $(this).find('img:first-of-type').fadeOut(0).addClass('crt-hidden-img');
                            // $(this).find('img:nth-of-type(2)').fadeIn(500).removeClass('crt-hidden-img');
                            $(this).find('img:first-of-type').addClass('crt-hidden-img');
                            $(this).find('img:nth-of-type(2)').removeClass('crt-hidden-img');
                        }
                    }
                });

                iGrid.find('.crt-grid-media-wrap').on('mouseleave', function() {
                    if ( 'yes' === $(this).find('.crt-grid-image-wrap').attr('data-img-on-hover') ) {
                        // if ( secondaryImg == img.attr('src') ) {
                        // 	img.attr('src', thisImgSrc);
                        // }

                        if ( $(this).find('img:nth-of-type(2)').attr('src') !== undefined && $(this).find('img:nth-of-type(2)').attr('src') !== '' ) {
                            // $(this).find('img:nth-of-type(2)').fadeOut(0).addClass('crt-hidden-img');
                            // $(this).find('img:first-of-type').fadeIn(500).removeClass('crt-hidden-img');
                            $(this).find('img:nth-of-type(2)').addClass('crt-hidden-img');
                            $(this).find('img:first-of-type').removeClass('crt-hidden-img');
                        }
                    }
                });

                if ( 'yes' === iGrid.find( '.crt-grid-media-wrap' ).attr( 'data-overlay-link' ) ) {
                    iGrid.find( '.crt-grid-media-wrap' ).css('cursor', 'pointer');

                    iGrid.find( '.crt-grid-media-wrap' ).on( 'click', function( event ) {

                        var targetClass = event.target.className;

                        if ( -1 !== targetClass.indexOf( 'inner-block' ) || -1 !== targetClass.indexOf( 'crt-cv-inner' ) ||
                            -1 !== targetClass.indexOf( 'crt-grid-media-hover' ) ) {
                            event.preventDefault();
                            event.stopPropagation();

                            var itemUrl = $(this).find( '.crt-grid-media-hover-bg' ).attr( 'data-url' ),
                                itemUrl = itemUrl.replace('#new_tab', '');

                            if (itemUrl) {
                                try {
                                    // Create a URL object to validate the URL
                                    var url = new URL(itemUrl);

                                    // Define a list of allowed protocols
                                    var allowedProtocols = ['http:', 'https:'];

                                    // Check if the URL's protocol is allowed
                                    if (allowedProtocols.includes(url.protocol)) {
                                        // Safe to use the URL
                                        var safeUrl = url.href;

                                        if ('_blank' === iGrid.find('.crt-grid-item-title a').attr('target')) {
                                            window.open(safeUrl, '_blank').focus();
                                        } else {
                                            window.location.href = safeUrl;
                                        }
                                    } else {
                                        console.error('Invalid URL scheme:', url.protocol);
                                    }
                                } catch (e) {
                                    console.error('Invalid URL:', itemUrl);
                                }
                            }
                        }
                    });
                }
            },
            lightboxPopup: function ( settings, $scope, iGrid ) {
                if ( -1 === iGrid.find( '.crt-grid-item-lightbox' ).length ) {
                    return;
                }

                var lightbox = iGrid.find( '.crt-grid-item-lightbox' ),
                    lightboxOverlay = lightbox.find( '.crt-grid-lightbox-overlay' ).first();

                // Set Src Attributes
                lightbox.each(function() {
                    var source = $(this).find('.inner-block > span').attr( 'data-src' ),
                        gridItem = $(this).closest( 'article' ).not('.slick-cloned');

                    if ( ! iGrid.hasClass( 'crt-media-grid' ) ) {
                        gridItem.find( '.crt-grid-image-wrap' ).attr( 'data-src', source );
                    }

                    var dataSource = gridItem.find( '.crt-grid-image-wrap' ).attr( 'data-src' );

                    if ( typeof dataSource !== typeof undefined && dataSource !== false ) {
                        if ( -1 === dataSource.indexOf( 'wp-content' ) ) {
                            gridItem.find( '.crt-grid-image-wrap' ).attr( 'data-iframe', 'true' );
                        }
                    }
                });

                // Init Lightbox
                iGrid.lightGallery( settings.lightbox );

                // Fix LightGallery Thumbnails
                iGrid.on('onAfterOpen.lg',function() {
                    if ( $('.lg-outer').find('.lg-thumb-item').length ) {
                        $('.lg-outer').find('.lg-thumb-item').each(function() {
                            var imgSrc = $(this).find('img').attr('src'),
                                newImgSrc = imgSrc,
                                extIndex = imgSrc.lastIndexOf('.'),
                                imgExt = imgSrc.slice(extIndex),
                                cropIndex = imgSrc.lastIndexOf('-'),
                                cropSize = /\d{3,}x\d{3,}/.test(imgSrc.substring(extIndex,cropIndex)) ? imgSrc.substring(extIndex,cropIndex) : false;

                            if ( 42 <= imgSrc.substring(extIndex,cropIndex).length ) {
                                cropSize = '';
                            }

                            if ( cropSize !== '' ) {
                                if ( false !== cropSize ) {
                                    newImgSrc = imgSrc.replace(cropSize, '-150x150');
                                } else {
                                    newImgSrc = [imgSrc.slice(0, extIndex), '-150x150', imgSrc.slice(extIndex)].join('');
                                }
                            }

                            // Change SRC
                            $(this).find('img').attr('src', newImgSrc);
                        });
                    }
                });

                // Show/Hide Controls
                iGrid.find( '.crt-grid' ).on( 'onAferAppendSlide.lg, onAfterSlide.lg', function( event, prevIndex, index ) {
                    var lightboxControls = $( '#lg-actual-size, #lg-zoom-in, #lg-zoom-out, #lg-download' ),
                        lightboxDownload = $( '#lg-download' ).attr( 'href' );

                    if ( $( '#lg-download' ).length ) {
                        if ( -1 === lightboxDownload.indexOf( 'wp-content' ) ) {
                            lightboxControls.addClass( 'crt-hidden-element' );
                        } else {
                            lightboxControls.removeClass( 'crt-hidden-element' );
                        }
                    }

                    // Autoplay Button
                    if ( '' === settings.lightbox.autoplay ) {
                        $( '.lg-autoplay-button' ).css({
                            'width' : '0',
                            'height' : '0',
                            'overflow' : 'hidden'
                        });
                    }
                });

                // Overlay
                if ( lightboxOverlay.length ) {
                    iGrid.find( '.crt-grid-media-hover-bg' ).after( lightboxOverlay.remove() );

                    iGrid.find( '.crt-grid-lightbox-overlay' ).on( 'click', function() {
                        $(this).closest( 'article' ).find( '.crt-grid-image-wrap' ).trigger( 'click' );
                    });
                } else {
                    lightbox.find( '.inner-block > span' ).on( 'click', function() {
                        var imageWrap = $(this).closest( 'article' ).find( '.crt-grid-image-wrap' );
                        imageWrap.trigger( 'click' );
                    });
                }
            },
        }

    });
})(jQuery);