(function($) {
    "use strict";
    var initialItems;
    var paramsObj = {};
    var finalURL = window.location.href;

    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-advanced-filters-pro.default',function($scope) {

            var visualFilters = $scope.find('.crt-af-visual-wrap'),
                viewMoreLess = $scope.find('.crt-view-more-less'),
                actionSelector = $('body').find('.crt-grid[data-advanced-filters="yes"]').first(),
                widgetSelector = $('body').find('.crt-grid[data-advanced-filters="yes"]').first().closest('[class*="elementor-widget-crt-"]'),
                gridScopeId = widgetSelector.attr('data-id'),
                isWooGrid = actionSelector.length && actionSelector.closest('.elementor-widget-crt-woo-grid').length > 0,
                experimentActionCount = isWooGrid ? 'crt_get_filtered_count_products' : 'crt_get_filtered_count_posts',
                experimentActionContent = isWooGrid ? 'crt_woo_grid_filters_ajax' : 'crt_grid_filters_ajax',
                settings,
                datePickers = [],
                isDebounceAjaxCallRunning = false,
                pagesLoadedExperiment = 0,
                pageCount = 0;

            if ( widgetSelector.length > 0 ) {
                if ( widgetSelector.find('.crt-grid-pagination').data('pages') <= 1 ) {
                    widgetSelector.find('.crt-load-more-btn').hide();
                }
            }

            // Infinite scroll with Advanced Filters: same AJAX as load more (offset + crt_url_params from URL)
            if ( actionSelector.length > 0 ) {
                var gridDataSettings = actionSelector.attr( 'data-settings' );
                if ( gridDataSettings ) {
                    try {
                        var gridSettings = JSON.parse( gridDataSettings );
                        if ( gridSettings.pagination_type === 'infinite-scroll' ) {
                            widgetSelector.find( '.crt-load-more-btn' ).hide();
                            widgetSelector.find( '.crt-grid-pagination' ).css( { 'display': 'flex', 'justify-content': 'center' } );
                            var afInfiniteScrollLoading = false,
                                afInfiniteScrollFoundPosts = null,
                                afScrollThresholdPx = 400;
                            $( window ).off( 'scroll.afInfiniteScroll' ).on( 'scroll.afInfiniteScroll', function() {
                                if ( CrtElements.editorCheck() || ! widgetSelector.length ) return;
                                var currentCount = widgetSelector.find( '.crt-grid-item' ).length;
                                var perPage = ( gridSettings.grid_settings && gridSettings.grid_settings.query_posts_per_page ) || gridSettings.query_posts_per_page || 9;
                                if ( currentCount <= perPage ) {
                                    afInfiniteScrollFoundPosts = null;
                                }
                                if ( afInfiniteScrollFoundPosts !== null && currentCount >= afInfiniteScrollFoundPosts ) return;
                                if ( afInfiniteScrollLoading ) return;
                                var paginationEl = widgetSelector.find( '.crt-grid-pagination' )[ 0 ];
                                if ( ! paginationEl ) return;
                                var rect = paginationEl.getBoundingClientRect();
                                if ( rect.top > $( window ).height() + afScrollThresholdPx ) return;
                                afInfiniteScrollLoading = true;
                                settings = typeof gridSettings !== 'undefined' ? gridSettings : ( actionSelector.attr( 'data-settings' ) ? JSON.parse( actionSelector.attr( 'data-settings' ) ) : {} );
                                finalURL = window.location.href;
                                $('.crt-advanced-filters-wrap').each(function() {
                                    var wrap = $(this);
                                    if ( wrap.find('input[type="checkbox"], input[type="radio"], input.crt-rating-filter').length > 0 ) {
                                        wrap.find('input[type="checkbox"], input[type="radio"], input.crt-rating-filter').each(function() {
                                            if ( $(this).is(':checked') || $(this).hasClass('crt-rating-filter') ) {
                                                updateURL( $(this).attr('name'), $(this).val(), $(this), finalURL );
                                            }
                                        });
                                    }
                                    wrap.find('input[type="text"], input[type="date"]').each(function() {
                                        updateURL( $(this).attr('name'), $(this).val(), $(this), finalURL );
                                    });
                                    if ( wrap.find('select').length === 1 ) {
                                        var sel = wrap.find('select');
                                        updateURL( sel.attr('name'), sel.val(), sel, finalURL );
                                    } else if ( wrap.find('select').length > 1 ) {
                                        wrap.find('select').each(function() {
                                            updateURL( $(this).attr('name'), $(this).val(), $(this), finalURL );
                                        });
                                    }
                                    if ( wrap.find('.crt-af-range-apply-btn').length > 0 && wrap.data('crt-applied') == 'yes' ) {
                                        var minInp = wrap.find('.crt-af-rf-control-min-input'), maxInp = wrap.find('.crt-af-rf-control-max-input'), sn = minInp.attr('name'), sv = [];
                                        if ( minInp.length && maxInp.length && sn ) {
                                            var mn = parseRangeInputVal(minInp.val()), mx = parseRangeInputVal(maxInp.val());
                                            sv.push(isNaN(mn) ? (minInp.attr('min') || minInp.data('min')) : mn);
                                            sv.push(isNaN(mx) ? (maxInp.attr('max') || maxInp.data('max')) : mx);
                                            updateURL( sn, sv, wrap.find('.crt-af-range-apply-btn'), finalURL );
                                        }
                                    } else if ( wrap.find('.crt-af-range-container').length > 0 && wrap.data('crt-applied') == 'yes' ) {
                                        var minInp = wrap.find('.crt-af-rf-control-min-input'), maxInp = wrap.find('.crt-af-rf-control-max-input'), sn = minInp.attr('name'), sv = [];
                                        if ( minInp.length && maxInp.length && sn ) {
                                            var mn = parseRangeInputVal(minInp.val()), mx = parseRangeInputVal(maxInp.val());
                                            sv.push(isNaN(mn) ? (minInp.attr('min') || minInp.data('min')) : mn);
                                            sv.push(isNaN(mx) ? (maxInp.attr('max') || maxInp.data('max')) : mx);
                                            updateURL( sn, sv, wrap.find('.crt-af-range-container'), finalURL );
                                        }
                                    }
                                });
                                var paramsObj = {};
                                (new URL(finalURL)).searchParams.forEach( function( value, key ) { paramsObj[key] = value; } );
                                settings.grid_settings = settings.grid_settings || {};
                                settings.grid_settings.query_offset = currentCount;
                                widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).show();
                                var orderby = widgetSelector.find( 'select.orderby' ).length > 0 ? widgetSelector.find( 'select.orderby' ).val() : '';
                                $.ajax( {
                                    type: 'POST',
                                    url: CRTConfig.ajaxurl,
                                    data: {
                                        action: experimentActionContent,
                                        nonce: CRTConfig.nonce,
                                        crt_offset: currentCount,
                                        crt_item_length: currentCount,
                                        grid_settings: settings.grid_settings,
                                        crt_url_params: paramsObj,
                                        orderby: orderby,
                                    },
                                    success: function( response ) {
                                        var rawItems = response.data && response.data.output ? $( response.data.output ) : $();
                                        var items = rawItems.filter ? rawItems.filter( '.crt-grid-item' ) : rawItems;
                                        if ( response.data && response.data.found_posts != null ) {
                                            afInfiniteScrollFoundPosts = response.data.found_posts;
                                        } else if ( items.length === 0 ) {
                                            afInfiniteScrollFoundPosts = currentCount;
                                        } else if ( items.length < perPage ) {
                                            afInfiniteScrollFoundPosts = currentCount + items.length;
                                        }
                                        if ( items.length ) {
                                            actionSelector.append( items );
                                            actionSelector.isotopecrt( 'appended', items );
                                            items.imagesLoaded().progress( function() {
                                                CrtElements.isotopeLayout( settings, '', widgetSelector, true, $scope );
                                                setTimeout( function() {
                                                    CrtElements.isotopeLayout( settings, '', widgetSelector, true, $scope );
                                                }, 100 );
                                                setTimeout( function() { actionSelector.addClass( 'grid-images-loaded' ); }, 500 );
                                            } );
                                            CrtElements.lightboxPopup( settings, widgetSelector, actionSelector );
                                            if ( actionSelector.data( 'lightGallery' ) ) {
                                                actionSelector.data( 'lightGallery' ).destroy( true );
                                                actionSelector.lightGallery( settings.lightbox );
                                            }
                                            CrtElements.mediaHoverLink( widgetSelector, actionSelector );
                                        }
                                        widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).hide();
                                        if ( ! response.data || response.data.found_posts <= widgetSelector.find( '.crt-grid-item' ).length ) {
                                            widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                            widgetSelector.find( '.crt-grid-pagination' ).delay( 1000 ).fadeOut( 500 );
                                        }
                                        window.dispatchEvent( new Event( 'resize' ) );
                                        afInfiniteScrollLoading = false;
                                    },
                                    error: function() {
                                        widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).hide();
                                        afInfiniteScrollLoading = false;
                                    },
                                } );
                            } );
                        }
                    } catch ( e ) {}
                }
            }

            if ( $scope.find('.crt-af-from-slider').length > 0 ) {
                // Range filter slider
                const fromSlider = $scope.find('#crt-af-from-slider-' + $scope.data('id'));
                const toSlider = $scope.find('#crt-af-to-slider-' + $scope.data('id'));
                const fromInput = $scope.find('#crt-from-input-' + $scope.data('id'));
                const toInput = $scope.find('#crt-to-input-' + $scope.data('id'));

                // Controls slider handlers z-index
                setToggleAccessible(toSlider, 'to');
                fillSlider(fromInput, toInput);

                fromSlider.on('input', () => controlFromSlider(fromSlider, toSlider, fromInput));
                toSlider.on('input', () => controlToSlider(fromSlider, toSlider, toInput));
                fromInput.on('change', () => controlFromInput(fromSlider, fromInput, toInput));
                toInput.on('change', () => controlToInput(toSlider, fromInput, toInput));
                // Format displayed value on blur when using delimiters (e.g. user types "1234" -> "1,234.00")
                fromInput.on('blur', function() {
                    var rangeContainer = $(this).closest('.crt-af-range-container');
                    if (rangeContainer.data('format-numbers') === 'yes') {
                        var n = parseRangeInputVal($(this).val());
                        if (!isNaN(n)) setRangeInputVal($(this), n, rangeContainer);
                    }
                });
                toInput.on('blur', function() {
                    var rangeContainer = $(this).closest('.crt-af-range-container');
                    if (rangeContainer.data('format-numbers') === 'yes') {
                        var n = parseRangeInputVal($(this).val());
                        if (!isNaN(n)) setRangeInputVal($(this), n, rangeContainer);
                    }
                });
            }

            if ( $scope.find('input[name="crt_af_date"]').length > 0 ) {
                const elem = $scope.find('input[name="crt_af_date"]')[0];
                datePickers.push(elem);
            }

            if ( $scope.find('input[name="crt_af_date_range"').length > 0 ) {
                const elem1 = $scope.find('#crt-datepicker-1[name="crt_af_date_range"]')[0];
                const elem2 = $scope.find('#crt-datepicker-2[name="crt_af_date_range"]')[0];

                datePickers.push(elem1);
                datePickers.push(elem2);
            }

            // Loop through the datePickers array and initialize AirDatepicker for each
            $.each(datePickers, function(index, elem) {
                new AirDatepicker(elem, {  // elem[0] since `elem` is a jQuery object, but AirDatepicker needs a DOM element
                    dateFormat: 'yyyy-MM-dd', // Specify the desired date format,
                    multipleDates: false,
                    onShow: function() {
                        var scopeId = 'air-datepicker-' + $scope.attr('data-id');

                        // Access the AirDatepicker's container
                        var datepickerContainer = document.querySelector('#air-datepicker-global-container .air-datepicker'); // Adjust if different

                        // Add the class to the AirDatepicker container
                        if (datepickerContainer) {
                            datepickerContainer.classList.add(scopeId);
                        }
                    },
                    onSelect: function({date, formattedDate, datepicker}) {
                        var selectedName = $(elem).attr('name'),
                            selectedValue = formattedDate;

                        $(elem).attr('data-date-val', formattedDate);

                        // Check if AJAX filters are enabled and trigger accordingly
                        if ('yes' == $(elem).closest('.crt-advanced-filters-wrap').data('enable-ajax')) {
                            ajaxFilters($(elem));
                        } else {
                            updateURL(selectedName, selectedValue, $(elem));
                        }
                    }
                });

                // Handle the resetDate event to clear the AirDatepicker
                $(elem).on('resetDate', function() {
                    // Use the AirDatepicker API to clear the date
                    if (elem.airdatepicker) {
                        elem.airdatepicker.clear();
                    }
                });
            });

            visualFiltersFunc($scope);

            onFilterChange();

            renderActiveFilters();

            updateActiveFilters();

            resetFilters();

            applyAllFilters();

            viewMoreLessFunc();

            initDependentSelect($scope);

            function initDependentSelect($scope) {
                var mainSelect = $scope.find('.crt-af-main-select'),
                    dependentSelects = $scope.find('.crt-af-dependent-select'),
                    noneLabel = $scope.find('.crt-advanced-filters-wrap').data('none-label') || 'None';

                $(document).ready(function(){
                    var selectedValue = mainSelect.val();
                    var taxonomy = mainSelect.closest('.crt-advanced-filters-wrap').find('.crt-af-dependent-select').first().data('taxonomy');
                    var relatedTax = mainSelect.data('taxonomy');
                    noneLabel = $(this).find('option:first').text();

                    if ( selectedValue && dependentSelects.first().val() == 0 ) {
                        loadDependentOptions(selectedValue, taxonomy, dependentSelects.first(), relatedTax);
                    }
                });

                // Handle main select change
                mainSelect.on('change', function() {
                    var selectedValue = $(this).val();
                    var taxonomy = $(this).closest('.crt-advanced-filters-wrap').find('.crt-af-dependent-select').first().data('taxonomy');
                    var relatedTax = $(this).data('taxonomy');
                    noneLabel = $(this).find('option:first').text();

                    // Reset and disable all dependent selects
                    dependentSelects.each(function() {
                        noneLabel = $(this).find('option:first').text();
                        $(this).prop('disabled', true).empty().append('<option value="0">' + noneLabel + '</option>');
                    });

                    if (selectedValue) {
                        loadDependentOptions(selectedValue, taxonomy, dependentSelects.first(), relatedTax);
                    }
                });

                // Handle dependent select changes
                dependentSelects.on('change', function() {
                    var selectedValue = $(this).val();
                    var nextSelect = $(this).closest('.crt-af-select-wrap').nextAll('.crt-af-select-wrap').first().find('.crt-af-dependent-select');
                    var relatedTax = $(this).data('taxonomy');

                    // Log data-taxonomy from all previous selects (not just the first)
                    const prevSelects = $(this)
                        .closest('.crt-af-select-wrap')
                        .prevAll('.crt-af-select-wrap')
                        .find('select');

                    const taxonomies = prevSelects
                        .map(function() { return $(this).data('taxonomy'); })
                        .get();

                    const parentTerms = prevSelects
                        .map(function() { return $(this).val(); })
                        .get();

                    // Add current relatedTax before logging
                    taxonomies.unshift(relatedTax);
                    parentTerms.unshift(selectedValue);

                    // Reset and disable all following selects
                    $(this).nextAll('.crt-af-dependent-select').each(function() {
                        $(this).prop('disabled', true).empty().append('<option value="0">' + noneLabel + '</option>');
                    });

                    if (selectedValue && nextSelect.length) {
                        loadDependentOptions(selectedValue, nextSelect.data('taxonomy'), nextSelect, relatedTax, taxonomies, parentTerms);
                    }
                });

                function loadDependentOptions(parentTerm, taxonomy, targetSelect, relatedTax, taxArray = [], parentTerms = []) {

                    let dependentData = {
                        action: 'crt_get_dependent_terms',
                        nonce: CRTConfig.nonce,
                        taxonomy: taxonomy,
                        parent_term: parentTerm,
                        related_taxonomy: relatedTax,
                    }

                    if ( taxArray.length > 1) {
                        // dependentData['tax_array'] = JSON.stringify(taxArray);
                        // dependentData['parent_terms'] = JSON.stringify(parentTerms);
                        dependentData['tax_array'] = taxArray;
                        dependentData['parent_terms'] = parentTerms;
                    }

                    $.ajax({
                        url: CRTConfig.ajaxurl,
                        type: 'POST',
                        data: dependentData,
                        success: function(response) {
                            console.log(response);
                            if (response.success && response.data.length) {
                                var options = '<option value="">' + targetSelect.find('option:first').text() + '</option>';
                                response.data.forEach(function(term) {
                                    options += '<option value="' + term.id + '">' + term.name + '</option>';
                                });
                                targetSelect.html(options).prop('disabled', false);
                            }
                        }
                    });

                    renderActiveFilters('ajax');
                }
            }

            function viewMoreLessFunc() {
                if ( viewMoreLess.length > 0 ) { // DOESN'T WORK IN EDITOR FOR SOME REASON - maybe localstorage ?
                    // localstorage to keep the state of the view more/less button
                    var itemsToShow = $scope.find('.crt-view-ml-wrap').data('item-count'),
                        moreLess = $scope.find('.crt-view-more-less'),
                        visualFiltersEnabled = $scope.find('.crt-af-visual-wrap').length > 0,
                        elementsToShow = visualFiltersEnabled ? $scope.find('.crt-af-visual-wrap') : $scope.find('.crt-af-input-wrap'),
                        elementsToHide = visualFiltersEnabled ? $scope.find('.crt-af-visual-wrap:gt(' + (itemsToShow - 1) + ')') : $scope.find('.crt-af-input-wrap:gt(' + (itemsToShow - 1) + ')'),
                        isItemSelected = visualFiltersEnabled ? $scope.find('.crt-af-visual-active').length > 0 : $scope.find('.crt-af-input-wrap input:checked').length > 0;

                    // Hide items exceeding the initial limit, only if no item is selected
                    if (!isItemSelected) {
                        elementsToHide.addClass('crt-hidden-item');
                    } else {
                        moreLess.addClass('expanded');
                        moreLess.text(moreLess.data('less-text'));
                    }

                    if ( $scope.find('.crt-af-input-wrap').length <= itemsToShow ) {
                        moreLess.hide();
                    }

                    // Toggle visibility on "View More" click
                    moreLess.on('click', function (e) {
                        e.preventDefault();
                        $(this).toggleClass('expanded');

                        if ($(this).hasClass('expanded')) {
                            // Show all items
                            elementsToShow.removeAttr('style').removeClass('crt-hidden-item');
                            $(this).text($(this).data('less-text'));
                        } else {
                            // Hide items exceeding the limit
                            elementsToHide.removeAttr('style').addClass('crt-hidden-item');
                            $(this).text($(this).data('more-text'));
                        }
                    });
                }
            }

            function applyAllFilters() {
                if ( $('.crt-af-apply-btn').length > 0 ) {
                    $('.crt-af-apply-btn').on('click', function() {
                        ajaxFilters($(this));
                    });
                }

                if ( widgetSelector.find('.crt-load-more-btn').length > 0 ) {
                    widgetSelector.find('.crt-load-more-btn').on('click', function(e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        ajaxFilters($(this));
                    });
                }

                if ( widgetSelector.find('.crt-grid-orderby').length > 0 ) {
                    widgetSelector.find('.crt-grid-orderby').find('select').on('change', function(e) {
                        e.preventDefault();
                        e.stopImmediatePropagation();
                        ajaxFilters($(this));
                    });
                }
            }

            function visualFiltersFunc($scope) {
                if (visualFilters.length > 0) {
                    visualFilters.each(function(index) {
                        $(this).on('click', function(e) {
                            e.preventDefault();
                            let $this = $(this);

                            if ( $this.find('input').prop('disabled') ) {
                                return true;
                            }

                            // Remove all active classes
                            // if ( 'or' === $scope.find('.crt-advanced-filters-wrap').attr('data-crt-relation') ) {
                            // visualFilters.removeClass('crt-af-visual-active');
                            // }

                            // Find the corresponding input element by index
                            var correspondingInput = $this.find('.crt-af-input-wrap').find('input'); // TODO: choose approach for colors

                            if ( 'img' == $this.prop('tagName') ) {
                                correspondingInput = $scope.find('input[value="'+ $this.data('replace-value') +'"]');
                            }

                            // Set the checked property to true
                            if ( correspondingInput.prop('checked') == true ) {
                                $this.removeClass('crt-af-visual-active');
                                correspondingInput.prop('checked', false);
                            } else {
                                if  ( 'radio' == $scope.find('input').attr('type') )  {
                                    $scope.find('.crt-af-visual').removeClass('crt-af-visual-active');
                                }

                                setTimeout(function() {
                                    $this.addClass('crt-af-visual-active');
                                    correspondingInput.prop('checked', true);
                                }, 100);
                            }

                            setTimeout(function() {
                                // Trigger the change event on the corresponding input
                                correspondingInput.trigger('change');
                            }, 500);
                        });
                    });
                }
            }

            function updateActiveFilters() {
                if ( CrtElements.editorCheck() ) {
                    return;
                }

                $scope.on('click', '.crt-af-active-filters span.crt-remove-filter', function () {
                    var thisEl = $(this),
                        dataValue = thisEl.data('value').toString(),
                        dataType = thisEl.parent().data('crt-af-type'),
                        currentUrl = window.location.href,
                        urlParts = currentUrl.split('?'),
                        baseUrl = urlParts[0],
                        queryString = urlParts[1] || '',
                        params = queryString.split('&');

                    // Update parameters based on dataValue
                    var updatedParams = params.map(function (param) {
                        var parts = param.split('=');
                        var key = parts[0];
                        var value = decodeURIComponent(parts[1] || '').replace(/\+/g, ' ');

                        if (value.includes(',')) {

                            if ( dataValue == value ) {
                                return;
                            }

                            // Convert comma-separated values to an array
                            var valuesArray = value.split(',');

                            // Remove the clicked dataValue from the array
                            valuesArray = valuesArray.filter(function (val) {
                                return val != dataValue;
                            });

                            return valuesArray.length > 0 ? key + '=' + valuesArray.join(',') : null;
                        } else {
                            return value != dataValue ? param : null;
                        }
                    });

                    // Remove null or undefined values from updatedParams
                    updatedParams = updatedParams.filter(function (param) {
                        return param != null;
                    });

                    // Find keys to remove with _gfr_ if their _gf_ counterparts are removed
                    var removedKeys = updatedParams
                        .filter(param => param === null)
                        .map(param => params[params.indexOf(param)].split('=')[0].replace('_gf_', '_gfr_'));

                    // Remove those _gfr_ keys
                    updatedParams = updatedParams.filter(function (param) {
                        var key = param.split('=')[0];
                        return !removedKeys.includes(key);
                    });

                    // Join the base URL and updated parameters
                    var updatedUrl = baseUrl + (updatedParams.length > 0 ? '?' + updatedParams.join('&') : '');

                    if ( $('.crt-af-active-filters').closest('.crt-advanced-filters-wrap').data('enable-ajax') == 'yes' ) {

                        let matchingVisualFilter = $('body').find('*[data-replace-value="'+ thisEl.data('value') +'"]');

                        matchingVisualFilter.parent('.crt-af-visual-wrap').removeClass('crt-af-visual-active');

                        if ( $('body').find('*[data-date-val="'+ thisEl.data('value') +'"]').length > 0 ) {

                            $('body').find('*[data-date-val="'+ thisEl.data('value') +'"]').val('').trigger('resetDate');

                            ajaxFilters($('body').find('*[data-date-val="'+ thisEl.data('value') +'"]'));

                        } else {
                            if ( $('body').find('[data-crt-filter-type='+ dataType +']').find('*[value="'+ thisEl.data('value') +'"]').prop('tagName') == 'OPTION' ) {
                                $('body').find('*[value="'+ thisEl.data('value') +'"]').closest('select').prop('selectedIndex', 0).trigger('change');
                            } else {
                                if (dataType != 'rating') { // Maybe other types as well need proper handling
                                    $('body').find('[data-crt-filter-type='+ dataType +']').find('*[value="'+ thisEl.data('value') +'"]').prop('checked', false).trigger('change');
                                }
                            }

                            var values = thisEl.data('value').toString(),
                                rangeCheckMin = $('[data-id="' + $(this).data('rf-id') + '"]').find('.crt-af-rf-control-min-input'),
                                rangeCheckMax = $('[data-id="' + $(this).data('rf-id') + '"]').find('.crt-af-rf-control-max-input');

                            if (values && values.includes(',') && rangeCheckMin.filter((_, el) => parseRangeInputVal(values.split(',')[0]) === parseRangeInputVal($(el).val())).length > 0) {
                                let [minValue, maxValue] = values.split(','),
                                    rangeMin = rangeCheckMin.filter((_, el) => parseRangeInputVal(minValue) === parseRangeInputVal($(el).val())),
                                    rangeMax = rangeCheckMax.filter((_, el) => parseRangeInputVal(maxValue) === parseRangeInputVal($(el).val())),
                                    outerContainer = rangeMin.closest('.crt-advanced-filters-wrap'),
                                    rangeContainer = outerContainer.find('.crt-af-range-container'),
                                    fromSlider = rangeContainer.find('.crt-af-from-slider'),
                                    toSlider = rangeContainer.find('.crt-af-to-slider'),
                                    fromSliderText = rangeContainer.find('.crt-af-rs-value-min'),
                                    toSliderText = rangeContainer.find('.crt-af-rs-value-max'),
                                    minVal = rangeMin.attr('min') || rangeMin.data('min'),
                                    maxVal = rangeMax.attr('max') || rangeMax.data('max'),
                                    fmt = (v) => rangeContainer.data('format-numbers') === 'yes' ? formatRangeDisplay(v, rangeContainer) : v;

                                fromSlider.val(minVal);
                                toSlider.val(maxVal);
                                fromSliderText.text(fmt(minVal));
                                toSliderText.text(fmt(maxVal));

                                rangeMin.val(fmt(minVal)).attr('value', rangeMin.val()).trigger('input');
                                rangeMax.val(fmt(maxVal)).attr('value', rangeMax.val()).trigger('input');

                                rangeContainer.find('input.crt-af-from-slider').trigger('change');

                                if ( outerContainer.find('.crt-af-range-apply-btn').length > 0 ) {
                                    outerContainer.find('.crt-af-range-apply-btn').trigger('click');
                                }

                                fillSlider(rangeMin, rangeMax);
                            } else {
                                if ( dataType == 'rating') {
                                    $('body').find('[data-crt-filter-type='+ dataType +']').find('.crt-woo-rating-' + thisEl.data('value')).removeClass('crt-active-product-filter');
                                    $('body').find('[data-crt-filter-type='+ dataType +']').find('.crt-woo-rating-' + thisEl.data('value')).find('input').trigger('change');
                                } else if ( dataType != 'select') {
                                    ajaxFilters($('body').find('[data-crt-filter-type='+ dataType +']').find('*[value="'+ thisEl.data('value') +'"]'));
                                }
                            }

                        }

                        thisEl.remove();

                        hideActiveLabelReset();
                    } else {
                        // Update the URL
                        window.location.href = updatedUrl;
                    }
                });
            }

            function hideActiveLabelReset() {
                if ( $('body').find('.crt-remove-filter').length > 0 ) {
                    $('body').find('.crt-af-reset-btn').removeClass('crt-hidden-element');
                    $('body').find('.crt-af-active-filters').prev('.crt-af-filters-label').removeClass('crt-hidden-element');
                    $('body').find('.crt-af-active-filters').closest('.crt-advanced-filters-wrap').removeClass('crt-hidden-element');
                } else {
                    $('body').find('.crt-af-reset-btn').addClass('crt-hidden-element');
                    $('body').find('.crt-af-active-filters').prev('.crt-af-filters-label').addClass('crt-hidden-element');
                    $('body').find('.crt-af-active-filters').closest('.crt-advanced-filters-wrap').addClass('crt-hidden-element');
                }
            }

            function renderActiveFilters( $origin = '' ) {
                if ( $origin == 'ajax' ) {
                    var activeFilters = $('.crt-af-active-filters');
                } else {
                    var activeFilters = $scope.find('.crt-af-active-filters');
                }

                if ( activeFilters ) {
                    if ( CrtElements.editorCheck() && $scope.find('.crt-af-active-filters').length > 0 ) {
                        let activeExample = '<div class="crt-af-active-wrap-99999999" data-crt-af-type="checkbox"><span class="crt-remove-filter" data-value="99999" class="custom-cursor-on-hover">Tags: Tasty(7)<span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z"></path></svg></span></span></div>';
                        activeFilters.append(activeExample);
                    }

                    $('.crt-advanced-filters-wrap').each(function () {
                        var thisWrap = $(this),
                            label = thisWrap.find('h4').text().trim() || thisWrap.data('term-label'),
                            ratingActive = '',
                            ratingInactive = '',
                            uniqueID = thisWrap.closest('.elementor-element').data('id'),
                            firstSelectNoValue = !thisWrap.find('select').first().val() || thisWrap.find('select').first().val() == '0',
                            values = [];

                        // Checkboxes and Radios
                        thisWrap.find('input[type="checkbox"]:checked, input[type="radio"]:checked, .crt-active-product-filter input.crt-rating-filter').each(function () {
                            var checkboxLabel = $(this).parent('label').text().trim();

                            if ( $(this).hasClass('crt-rating-filter') ) {
                                checkboxLabel = $(this).val() + '/5';
                            }

                            values.push({ label: label, value: checkboxLabel, realVal: $(this).val() });
                        });

                        //, .crt-woo-rating:not(.crt-active-product-filter) input.crt-rating-filter
                        thisWrap.find('input[type="checkbox"]:not(:checked), input[type="radio"]:not(:checked), .crt-woo-rating:not(.crt-active-product-filter) input.crt-rating-filter').each(function () {
                            if ( $('body').find('span[data-value="'+ $(this).val() +'"]').parent().attr('data-crt-af-type') == thisWrap.data('crt-filter-type') ) {
                                if ( thisWrap.closest('[data-element_type="widget"]').data('id') == $('body').find('span[data-value="'+ $(this).val() +'"]').data('rf-id') ) {
                                    $('body').find('span[data-value="'+ $(this).val() +'"]').remove();
                                }
                            }
                        });

                        thisWrap.find('input[type="date"]').each(function() {
                            values.push({ label: label, value: $(this).val(), realVal: $(this).val() });
                        });

                        thisWrap.find('input[type="text"]').each(function() {
                            values.push({ label: label, value: $(this).val(), realVal: $(this).val() });
                        });

                        // Range Filter
                        if (thisWrap.find('.crt-af-rf-control').length > 0) {
                            var minInp = thisWrap.find('.crt-af-rf-control-min-input'),
                                maxInp = thisWrap.find('.crt-af-rf-control-max-input'),
                                rangeDisplay = [minInp.val(), maxInp.val()].join(' - '),
                                rangeRealVal = [parseRangeInputVal(minInp.val()), parseRangeInputVal(maxInp.val())];

                            values.push({ label: label, value: rangeDisplay, realVal: rangeRealVal});
                        }

                        thisWrap.find('select').each(function () {
                            var selectLabel = $(this).closest('.crt-af-select-wrap').find('h4').text().trim() || label,
                                selectValue = $(this).find('option:selected').text().trim(),
                                selectRealVal = $(this).find('option:selected').val();

                            values.push({ label: selectLabel, value: selectValue, realVal: selectRealVal });

                            if ( selectRealVal == '' || selectRealVal == '0' ) {
                                $('body').find('span[data-value="'+ selectRealVal +'"]').remove();
                                $('body').find('.crt-af-active-wrap-' + uniqueID).remove();
                            }
                        });

                        // If there are values, render results
                        if ( values.length > 0 ) {
                            var container = $('<div class="crt-af-active-wrap-'+ uniqueID +'" data-crt-af-type="'+ $(this).data('crt-filter-type') +'">'),
                                replaceFilters = ['select', 'radio', 'date', 'range'];

                            if ( $('body').find('.crt-af-active-wrap-'+ uniqueID ).length > 0 ) {
                                container = $('body').find('.crt-af-active-wrap-'+ uniqueID);
                            }

                            values.forEach(function (item) {
                                // Append value span
                                let thisLabel = item.label ? item.label : 'No Label';
                                item.value = item.value.replace(/\(\d+\)$/, '').trim();

                                if ( item.value != 'None' && item.value != '' && item.realVal != '0' && item.realVal != '' ) {
                                    if ( container.find('[data-value="' + item.realVal + '"]').length == 0 ) {
                                        var itemElement = $('<span class="crt-remove-filter" data-rf-id="'+ uniqueID +'" data-value="' + item.realVal + '">').text(thisLabel + ': ' + item.value);

                                        // Add remove icon in a separate span
                                        var removeIcon = $('<span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z"></path></svg></span>');

                                        itemElement.append(removeIcon);

                                        if ( replaceFilters.includes(container.attr('data-crt-af-type')) ) {
                                            if ( thisWrap.find('select').length <= 1   ) {
                                                container.html(itemElement);
                                            } else {
                                                if (thisWrap.find('select').length > 1) {
                                                    if (!thisWrap.find('select').first().val()) {
                                                        container.empty();
                                                    } else {
                                                        // Find the select containing the option with value == item.realVal
                                                        const selectWithOption = thisWrap.find('select').filter(function() {
                                                            return $(this).find('option[value="' + item.realVal + '"]').length > 0;
                                                        });
                                                        if (selectWithOption.length > 0) {
                                                            itemElement.attr('data-rf-type', selectWithOption.data('taxonomy'));
                                                        }

                                                        if (selectWithOption.is(thisWrap.find('select').first())) {
                                                            container.html(itemElement);
                                                        } else {
                                                            const existingItem = container.find('[data-rf-type="' + itemElement.attr('data-rf-type') + '"]');
                                                            if (existingItem.length > 0) {
                                                                existingItem.replaceWith(itemElement);
                                                            } else {
                                                                container.append(itemElement);
                                                            }
                                                        }
                                                    }
                                                } else {
                                                    container.append(itemElement);
                                                }
                                            }
                                        } else if ( 'date_range' == container.attr('data-crt-af-type') ) {
                                            let dateElementClass = $('*[data-date-val='+ item.realVal +']').attr('class');

                                            if ( 'crt-date-filter-start' == dateElementClass ) {
                                                itemElement = $('<span class="crt-remove-filter '+ dateElementClass +'" data-value="' + item.realVal + '">').text(thisLabel + ': ' + item.value);
                                            } else {
                                                itemElement = $('<span class="crt-remove-filter '+ dateElementClass +'" data-value="' + item.realVal + '">').text(item.value);
                                            }

                                            if ( container.find('.' + dateElementClass) ) {
                                                container.find('.' + dateElementClass).remove();
                                                if ( 'crt-date-filter-start' == dateElementClass ) {
                                                    container.prepend(itemElement);
                                                } else {
                                                    container.append(itemElement);
                                                }
                                            } else {
                                                container.append(itemElement);
                                            }
                                        } else {
                                            container.append(itemElement);
                                        }

                                    }
                                } else {
                                    $('span[data-value="'+ item.realVal +'"]').remove();
                                }
                            });

                            // Prepend container to .crt-af-active-filters
                            if ( $('body').find('.crt-af-active-wrap-'+ uniqueID).length == 0 && container.find('span').length > 0 ) {
                                activeFilters.append(container);
                            }
                        }

                        if ( thisWrap.find('select').length > 1 && firstSelectNoValue ) {
                            container.empty();
                        }
                    });

                    $('body').find('.crt-af-range-container').each(function() {
                        var rangeContainer = $(this),
                            rangeActive = rangeContainer.attr('data-active'),
                            uniqueID = rangeContainer.closest('.elementor-element').data('id'),
                            minInput = rangeContainer.find('.crt-af-rf-control-min-input'),
                            maxInput = rangeContainer.find('.crt-af-rf-control-max-input'),
                            minValue = parseRangeInputVal(minInput.val()),
                            minAllowed = parseRangeInputVal(minInput.attr('min') || minInput.data('min')),
                            maxValue = parseRangeInputVal(maxInput.val()),
                            maxAllowed = parseRangeInputVal(maxInput.attr('max') || maxInput.data('max')),
                            isMinAtDefault = minValue === minAllowed || (isNaN(minValue) && isNaN(minAllowed)),
                            isMaxAtDefault = maxValue === maxAllowed || (isNaN(maxValue) && isNaN(maxAllowed));

                        if ( isMinAtDefault && isMaxAtDefault ) {
                            $('span[data-rf-id="'+ uniqueID +'"]').remove();
                        }
                    });

                    hideActiveLabelReset();
                }
            }

            function onFilterChange() {
                $scope.find('input, select, textarea').on('change', function() {
                    var $this = $(this);
                    if ('checkbox' == $this.attr('type')) {
                        var $option = $this.closest('.crt-af-input-wrap');
                        if ($option.hasClass('crt-checked')) {
                            $this.prop('checked', false);
                            $option.removeClass('crt-checked');
                            $option.closest('.crt-af-visual-wrap').removeClass('crt-af-visual-active');
                        } else {
                            $option.addClass('crt-checked');
                        }
                    } else if ('radio' == $this.attr('type')) {
                        var name = $this.attr('name'),
                            $group = $('input[type="radio"][name="' + name + '"]');

                        $group.closest('.crt-af-input-wrap').removeClass('crt-checked');

                        if ($this.is(':checked')) {
                            $this.closest('.crt-af-input-wrap').addClass('crt-checked');
                        }
                    }
                });

                $scope.find('.crt-woo-rating').on('click', function() {
                    let rating = +$(this).data('rating');

                    $(this).toggleClass('crt-active-product-filter');

                    if ( $('.crt-af-apply-btn').length == 0 ) {
                        $(this).find('input.crt-rating-filter').val(rating).trigger('change');
                    } else {
                        $(this).find('input.crt-rating-filter').val(rating);
                    }
                });

                if ( $('.crt-af-apply-btn').length == 0 ) {
                    $scope.find('input[type="checkbox"], input[type="radio"]').on('change', function() {
                        var selectedName = $(this).attr('name'),
                            selectedValue = $(this).val();

                        if ( 'yes' == $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                            ajaxFilters($(this));
                        } else {
                            updateURL(selectedName, selectedValue, $(this));
                        }
                    });

                    $scope.on('changeDate', 'input[name="crt_af_date"]', function(e) { // GOGA - does it work at all ?
                        const date = e.detail.date;

                        if ( date ) {

                            // Adjust for the timezone offset
                            const localDate = new Date(date.getTime() - (date.getTimezoneOffset() * 60000));

                            // Format the date as 'YYYY-MM-DD'
                            var selectedName = 'crt_af_date',
                                selectedValue = localDate.toISOString().split('T')[0];

                            // const formattedDate = localDate.toISOString().split('T')[0];

                            ajaxFilters($scope.find('input[name="crt_af_date"]'));
                        }
                    });

                    $scope.find('input[type="date"]').on('change', function() {
                        var selectedName = $(this).attr('name'),
                            selectedValue = $(this).val();

                        if ( 'yes' == $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                            ajaxFilters($(this));
                        } else {
                            updateURL(selectedName, selectedValue, $(this));
                        }
                    });

                    $scope.find('input.crt-rating-filter').on('change', function() {
                        var selectedName = $(this).attr('name');
                        var selectedValue = $(this).val();

                        if ( 'yes' == $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                            ajaxFilters($(this));
                        } else {
                            updateURL(selectedName, selectedValue, $(this));
                        }
                    });

                    $scope.find('select').on('change', function() {
                        var selectedName = $(this).attr('name');
                        var selectedValue = $(this).val();
                        if ( 'yes' == $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                            ajaxFilters($(this));
                        } else {
                            updateURL(selectedName, selectedValue, $(this));
                        }
                    });

                    if ( $scope.find('.crt-af-range-apply-btn').length > 0 ) {
                        $scope.on('click', '.crt-af-range-apply-btn', function() {
                            var minInput = $scope.find('.crt-af-rf-control-min-input'),
                                maxInput = $scope.find('.crt-af-rf-control-max-input'),
                                selectedName = minInput.attr('name'),
                                selectedValue = [];
                            if (minInput.length && maxInput.length) {
                                var minVal = parseRangeInputVal(minInput.val());
                                var maxVal = parseRangeInputVal(maxInput.val());
                                selectedValue.push(isNaN(minVal) ? minInput.attr('min') || minInput.data('min') : minVal);
                                selectedValue.push(isNaN(maxVal) ? maxInput.attr('max') || maxInput.data('max') : maxVal);
                            }
                            if ( selectedValue.length ) {
                                if ( 'yes' == $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                                    $(this).closest('.crt-advanced-filters-wrap').attr('data-crt-applied', 'yes');
                                    ajaxFilters($(this));
                                } else {
                                    updateURL(selectedName, selectedValue, $(this));
                                }
                            }
                        });
                    } else {
                        $scope.find('.crt-af-range-container').find('input[type="range"], .crt-af-rf-control-min-input, .crt-af-rf-control-max-input').on('change', function() {
                            var minInput = $scope.find('.crt-af-rf-control-min-input'),
                                maxInput = $scope.find('.crt-af-rf-control-max-input'),
                                selectedName = minInput.attr('name'),
                                selectedValue = [];
                            if (minInput.length && maxInput.length) {
                                var minVal = parseRangeInputVal(minInput.val());
                                var maxVal = parseRangeInputVal(maxInput.val());
                                selectedValue.push(isNaN(minVal) ? minInput.attr('min') || minInput.data('min') : minVal);
                                selectedValue.push(isNaN(maxVal) ? maxInput.attr('max') || maxInput.data('max') : maxVal);
                            }
                            if ( selectedValue.length ) {
                                if ( 'yes' == $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                                    $(this).closest('.crt-advanced-filters-wrap').attr('data-crt-applied', 'yes');
                                    ajaxFilters($(this));
                                } else {
                                    updateURL(selectedName, selectedValue, $(this));
                                }
                            }
                        });
                    }
                }
            }

            function controlFromInput(fromSlider, fromInput, toInput) {
                const rangeContainer = fromInput.closest('.crt-af-range-container');
                const minAllowed = parseRangeInputVal(fromInput.attr('min') || fromInput.data('min'));
                const [from, to] = getParsed(fromInput, toInput);
                fillSlider(fromInput, toInput);
                setToggleAccessible(fromInput, 'from');
                if (from > to) {
                    fromSlider.val(to).attr('value', to);
                    setRangeInputVal(fromInput, to, rangeContainer);
                } else {
                    fromSlider.val(from).attr('value', from);
                    setRangeInputVal(fromInput, from, rangeContainer);
                }

                if (!isNaN(minAllowed) && from < minAllowed) {
                    fromSlider.val(minAllowed).attr('value', minAllowed);
                    setRangeInputVal(fromInput, minAllowed, rangeContainer);
                }
            }

            function controlToInput(toSlider, fromInput, toInput) {
                const rangeContainer = toInput.closest('.crt-af-range-container');
                const maxAllowed = parseRangeInputVal(toInput.attr('max') || toInput.data('max'));
                const [from, to] = getParsed(fromInput, toInput);
                fillSlider(fromInput, toInput);
                setToggleAccessible(toInput, 'to');

                if (from <= to) {
                    if (!isNaN(maxAllowed) && to > maxAllowed) {
                        toSlider.val(maxAllowed).attr('value', maxAllowed);
                        setRangeInputVal(toInput, maxAllowed, rangeContainer);
                    } else {
                        toSlider.val(to).attr('value', to);
                        setRangeInputVal(toInput, to, rangeContainer);
                    }
                } else {
                    if (!isNaN(maxAllowed) && to > maxAllowed) {
                        setRangeInputVal(toInput, maxAllowed, rangeContainer);
                    } else {
                        setRangeInputVal(toInput, from, rangeContainer);
                    }
                }
            }

            function parseRangeInputVal(val) {
                if (val === '' || val == null) return NaN;
                const str = String(val).replace(/,/g, '');
                return parseFloat(str);
            }

            function formatRangeDisplay(val, rangeContainer) {
                if (rangeContainer && rangeContainer.data('format-numbers') === 'yes') {
                    const num = parseRangeInputVal(val);
                    if (!isNaN(num)) {
                        const decimals = Math.max(0, Math.min(4, parseInt(rangeContainer.data('decimal-places'), 10) || 2));
                        return num.toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                    }
                }
                return val;
            }

            function setRangeInputVal($input, num, rangeContainer) {
                if (rangeContainer && rangeContainer.data('format-numbers') === 'yes') {
                    $input.val(formatRangeDisplay(num, rangeContainer));
                } else {
                    $input.val(num);
                }
                $input.attr('value', $input.val());
            }

            function controlFromSlider(fromSlider, toSlider, fromInput) {
                const rangeContainer = fromSlider.closest('.crt-af-range-container');
                const [from, to] = getParsed(fromSlider, toSlider);
                fillSlider(fromSlider, toSlider);
                setToggleAccessible(fromSlider, 'from');
                if (from > to) {
                    fromSlider.val(to).attr('value', to);
                    setRangeInputVal(fromInput, to, rangeContainer);
                    $scope.find('.crt-af-rs-value-min').text(formatRangeDisplay(to, rangeContainer));
                } else {
                    fromSlider.val(from).attr('value', from);
                    setRangeInputVal(fromInput, from, rangeContainer);
                    $scope.find('.crt-af-rs-value-min').text(formatRangeDisplay(from, rangeContainer));
                }
            }

            function controlToSlider(fromSlider, toSlider, toInput) {
                const rangeContainer = fromSlider.closest('.crt-af-range-container');
                const [from, to] = getParsed(fromSlider, toSlider);
                fillSlider(fromSlider, toSlider);
                setToggleAccessible(toSlider, 'to');
                if (from <= to) {
                    toSlider.val(to).attr('value', to);
                    setRangeInputVal(toInput, to, rangeContainer);
                    $scope.find('.crt-af-rs-value-max').text(formatRangeDisplay(to, rangeContainer));
                } else {
                    setRangeInputVal(toInput, from, rangeContainer);
                    toSlider.val(from).attr('value', from);
                    $scope.find('.crt-af-rs-value-max').text(formatRangeDisplay(from, rangeContainer));
                }
            }

            function getParsed(currentFrom, currentTo) {
                const from = parseRangeInputVal(currentFrom.val());
                const to = parseRangeInputVal(currentTo.val());
                return [isNaN(from) ? 0 : from, isNaN(to) ? 0 : to];
            }

            function fillSlider(fromSlider, toSlider) {
                const rangeContainer = fromSlider.closest('.crt-af-range-container');
                const rangeEl = rangeContainer.find('.crt-af-from-slider')[0] ? rangeContainer.find('.crt-af-from-slider') : toSlider;
                const min = parseFloat(rangeEl.prop('min')) || parseRangeInputVal(rangeEl.attr('min')) || parseRangeInputVal(rangeEl.data('min')) || 0;
                const max = parseFloat(rangeEl.prop('max')) || parseRangeInputVal(rangeEl.attr('max')) || parseRangeInputVal(rangeEl.data('max')) || 0;
                const fromVal = parseRangeInputVal(fromSlider.val());
                const toVal = parseRangeInputVal(toSlider.val());
                const from = Math.min(fromVal, toVal);
                const to = Math.max(fromVal, toVal);

                const rangeDistance = max - min;
                const fromPercent = (((from - min) / rangeDistance) * 100) > 0 ? ((from - min) / rangeDistance) * 100 : 0;
                let toPercent = ((to - min) / rangeDistance) * 100;

                const sliderFill = fromSlider.closest('.crt-advanced-filters-wrap').find('.crt-af-slider-fill');

                if ( toPercent > 100 ) {
                    toPercent = 100;
                }

                sliderFill.css({
                    left: `${fromPercent}%`,
                    width: `${toPercent - fromPercent}%`,
                });

                if ( from <= min && to >= max ) {
                    fromSlider.closest('.crt-advanced-filters-wrap').find('.crt-af-range-container').attr('data-active', 'no');
                } else {
                    fromSlider.closest('.crt-advanced-filters-wrap').find('.crt-af-range-container').attr('data-active', 'yes');
                }
            }

            function setToggleAccessible(currentSlider, direction = 'to') {
                if ( direction == 'to' ) {
                    const toSlider = $scope.find('#crt-af-to-slider-' + $scope.data('id'));

                    if ( Number(currentSlider.val()) <= +currentSlider.attr('min') ) {
                        toSlider.css('z-index', 2);
                    } else {
                        toSlider.css('z-index', 0);
                    }
                } else {
                    const fromSlider = $scope.find('#crt-af-from-slider-' + $scope.data('id'));

                    if ( Number(currentSlider.val()) >= +currentSlider.attr('max') ) {
                        fromSlider.css('z-index', 2);
                    } else {
                        fromSlider.css('z-index', 0);
                    }
                }
            }

            function resetFilters() {
                if ( CrtElements.editorCheck() ) {
                    return;
                }

                $scope.on('click', '.crt-af-reset-btn', function() {
                    // Create a new URL object from the current URL
                    var currentUrl = new URL(window.location.href);

                    // Get all parameter keys and iterate over them
                    var keys = Array.from(currentUrl.searchParams.keys());

                    keys.forEach(function(key) {
                        // Check if the parameter key starts with "crt_"
                        if (key.indexOf('crt_') === 0) {
                            // Remove the parameter
                            currentUrl.searchParams.delete(key);
                        }
                    });

                    if ( 'yes' !== $(this).closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                        // Redirect to the new URL
                        window.location.href = currentUrl.toString();
                    } else {
                        // Reset range inputs to their min/max values
                        $('body').find('.crt-advanced-filters-wrap .crt-af-range-container').each(function() {
                            let rangeContainer = $(this),
                                rangeMin = rangeContainer.find('.crt-af-rf-control-min-input'),
                                rangeMax = rangeContainer.find('.crt-af-rf-control-max-input'),
                                thisFromSlider = rangeContainer.find('input[type="range"].crt-af-from-slider'),
                                thisToSlider = rangeContainer.find('input[type="range"].crt-af-to-slider'),
                                thisFromSliderText = rangeContainer.find('.crt-af-rs-value-min'),
                                thisToSliderText = rangeContainer.find('.crt-af-rs-value-max'),
                                minVal = rangeMin.attr('min') || rangeMin.data('min'),
                                maxVal = rangeMax.attr('max') || rangeMax.data('max'),
                                formatRange = rangeContainer.data('format-numbers') === 'yes',
                                fmt = function(v) { return formatRange && !isNaN(parseRangeInputVal(v)) ? formatRangeDisplay(v, rangeContainer) : v; };

                            rangeMin.val(fmt(minVal)).attr('value', rangeMin.val());
                            rangeMax.val(fmt(maxVal)).attr('value', rangeMax.val());

                            fillSlider(rangeMin, rangeMax);

                            thisFromSlider.val(minVal).attr('value', minVal);
                            thisToSlider.val(maxVal).attr('value', maxVal);
                            thisFromSliderText.text(fmt(minVal));
                            thisToSliderText.text(fmt(maxVal));

                            rangeContainer.attr('data-active', 'no');
                        });

                        // Reset all select elements to their default option (usually the first option)
                        $('body').find('.crt-advanced-filters-wrap').find('select').prop('selectedIndex', 0);

                        // Uncheck only checked checkboxes and radio buttons
                        $('body').find('.crt-advanced-filters-wrap').find('input[type="checkbox"]:checked, input[type="radio"]:checked').prop('checked', false);

                        // Reset all date inputs to empty
                        $('body').find('input[name="crt_af_date"]').val('').trigger('resetDate');
                        $('body').find('input[name="crt_af_date_range"]').val('').trigger('resetDate');

                        // Reset text inputs
                        $('body').find('.crt-advanced-filters-wrap input[type="text"]').val('');

                        // Reset rating filters
                        $('body').find('.crt-advanced-filters-wrap input.crt-rating-filter').val('');
                        $('body').find('.crt-advanced-filters-wrap .crt-woo-rating').removeClass('crt-active-product-filter');

                        // Remove visual states
                        $('body').find('.crt-af-visual-wrap').removeClass('crt-af-visual-active');
                        $('body').find('.crt-af-input-wrap').removeClass('crt-checked');

                        $('body').find('.crt-af-active-filters').find('div').remove();

                        $('body').find('.crt-af-main-select').trigger('change');

                        ajaxFilters($(this));

                        $(this).addClass('crt-hidden-element');
                    }
                });
            }

            function updateResultsCount(settings) {
                if (isDebounceAjaxCallRunning) {
                    return;
                }

                let paramsArray = [];
                let elementsToUpdate = [];

                $('.crt-advanced-filters-wrap').each(function () {
                    const $wrap = $(this);

                    if ( $wrap.attr('data-show-count') === 'yes' && $wrap.attr('data-change-counter') !== '') {
                        if ( $wrap.attr('data-change-counter') === 'other_filters' && $wrap.closest('.elementor-widget-crt-advanced-filters-pro').is($scope) ) {
                            return true; // skip this wrap
                        }

                        const $inputs = $wrap.find('input[type="checkbox"], input[type="radio"], option, li.crt-woo-rating');

                        $inputs.each(function () {
                            const $input = $(this);
                            const isOption = $input.prop('tagName') === 'OPTION';
                            const selectedName = isOption ? $input.closest('select').attr('name') : $input.attr('name');
                            const selectedValue = $input.val() || $input.data('rating');

                            if (!selectedName || selectedValue === '') return;

                            // Clone the current state
                            let paramsObjTemp = { ...paramsObj };

                            // Get current values for this key if exist
                            let existingValues = paramsObjTemp[selectedName] ? paramsObjTemp[selectedName].split(',') : [];

                            // Ensure selectedValue is included
                            if (!existingValues.includes(selectedValue)) {
                                existingValues.push(selectedValue);
                            }

                            paramsObjTemp[selectedName] = existingValues.join(',');

                            // Store the element and its key info to update later
                            elementsToUpdate.push({
                                element: $input,
                                name: selectedName,
                                value: selectedValue
                            });

                            // Store for AJAX batch call
                            paramsArray.push(paramsObjTemp);
                        });
                    }
                });

                if (paramsArray.length === 0) {
                    console.warn('No filters to analyze.');
                    return;
                }

                // Make a single AJAX request with all hypothetical filter states
                $.ajax({
                    type: 'POST',
                    url: CRTConfig.ajaxurl,
                    data: {
                        action: experimentActionCount,
                        nonce: CRTConfig.nonce,
                        grid_settings: settings.grid_settings,
                        crt_url_params: paramsArray
                    },
                    success: function (response) {
                        if (!response || !Array.isArray(response.data)) {
                            console.warn('Invalid response:', response);
                            return;
                        }

                        setTimeout(() => {
                            response.data.forEach((dataEntry, index) => {
                                const countText = `(${dataEntry.found_posts})`;
                                const { element, value } = elementsToUpdate[index];

                                const $el = $(element);

                                if ($el.closest('.crt-advanced-filters-wrap').attr('data-crt-relation') == 'or') {
                                    if ( $el.closest('.elementor-widget-crt-advanced-filters-pro').is($scope) ) {
                                        return true;
                                    }
                                }

                                if ( $el.hasClass('crt-woo-rating') ) {
                                    $el.find('.crt-af-count').text(countText);
                                } else if ($el.prop('tagName') === 'OPTION') {
                                    if ( $el.attr('value') != 0 ) {
                                        const cleanText = $el.text().replace(/\(\d+\)/, '');
                                        $el.text(cleanText + countText);
                                    }
                                } else {
                                    $el.closest('.crt-af-input-wrap').find('.crt-af-count').text(countText);

                                    // // Also update active filters area if it exists
                                    // let $filterTag = $('.crt-af-active-filters').find(`span[data-value="${value}"]`),
                                    // 	removeIcon = '<span><svg version="1.1" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path d="M5.293 6.707l5.293 5.293-5.293 5.293c-0.391 0.391-0.391 1.024 0 1.414s1.024 0.391 1.414 0l5.293-5.293 5.293 5.293c0.391 0.391 1.024 0.391 1.414 0s0.391-1.024 0-1.414l-5.293-5.293 5.293-5.293c0.391-0.391 0.391-1.024 0-1.414s-1.024-0.391-1.414 0l-5.293 5.293-5.293-5.293c-0.391-0.391-1.024-0.391-1.414 0s-0.391 1.024 0 1.414z"></path></svg></span>';

                                    // if (
                                    // 	$filterTag.length > 0 &&
                                    // 	$filterTag.parent().attr('data-crt-af-type') === $el.closest('.crt-advanced-filters-wrap').data('crt-filter-type')
                                    // ) {
                                    // 	// Only update the filterTag that matches the data-rf-id of the current filter element
                                    // 	const elDataId = $el.closest('[data-id]').data('id');
                                    // 	$filterTag.each(function () {
                                    // 		if ($(this).attr('data-rf-id') == elDataId) {
                                    // 			let baseText = $(this).text().replace(/\(\d+\)/, '');
                                    // 			$(this).html(baseText + countText + removeIcon);
                                    // 		}
                                    // 	});
                                    // }
                                }

                                // Disable element if count is 0 and data-empty-action is set to disable
                                if (dataEntry.found_posts === 0) {
                                    if ($el.closest('.crt-advanced-filters-wrap').attr('data-empty-action') === 'hide') {
                                        if ( $el.attr('value') != 0 ) {
                                            $el.hide();
                                            if ($el.is(':checkbox') || $el.is(':radio')) {
                                                $el.closest('.crt-af-input-wrap').hide();
                                                if ( $el.closest('.crt-af-visual-wrap') ) {
                                                    $el.closest('.crt-af-visual-wrap').hide();
                                                }
                                            }
                                        }
                                    } else if ($el.closest('.crt-advanced-filters-wrap').attr('data-empty-action') === 'disable') {
                                        $el.prop('disabled', true);
                                    }
                                } else {
                                    $el.prop('disabled', false);
                                    $el.show();
                                    if ($el.is(':checkbox') || $el.is(':radio')) {
                                        $el.closest('.crt-af-input-wrap').show();
                                        $el.closest('.crt-af-visual-wrap').show();
                                    }
                                }

                                const $wrap = $el.closest('.crt-advanced-filters-wrap');
                                if ( $wrap.find('.crt-advanced-filters-inner').children(':visible').length === 0 ) {
                                    $wrap.find('.crt-af-filters-label').hide();
                                } else {
                                    $wrap.find('.crt-af-filters-label').show();
                                }
                            });
                        }, 800);
                    },
                    error: function (err) {
                        console.error('AJAX error:', err);
                    }
                });
            }

            function ajaxFilters(self = '') {
                if ( CrtElements.editorCheck() ) {
                    return;
                }

                var triggerElement = self,
                    activeFilters = $('.crt-af-active-filters');

                if ( activeFilters.length > 0 && activeFilters.hasClass('crt-hidden-element') ) {
                    activeFilters.removeClass('crt-hidden-element');
                }

                $('.crt-advanced-filters-wrap').each(function() {
                    if ( $(this).find('input[type="checkbox"], input[type="radio"], input.crt-rating-filter').length > 0 ) {
                        $(this).find('input[type="checkbox"], input[type="radio"], input.crt-rating-filter').each(function() {
                            let isRadioChecked = true;

                            if ( $(this).attr('type') == 'radio' && (!$(this).is(':checked')) && $(this).val() != self.val() ) {
                                isRadioChecked = false;
                            } else {
                                isRadioChecked = true;
                            }

                            if ( isRadioChecked || $(this).hasClass('crt-rating-filter')) {
                                var selectedName = $(this).attr('name');
                                var selectedValue = $(this).val();

                                updateURL(selectedName, selectedValue, $(this), finalURL);
                            }
                        });
                    }

                    $(this).find('input[type="text"], input[type="date"]').each(function() {
                        var selectedName = $(this).attr('name');
                        var selectedValue = $(this).val();

                        updateURL(selectedName, selectedValue, $(this), finalURL);
                    });

                    if ( $(this).find('select').length == 1 ) {
                        var selectedName = $(this).find('select').attr('name');
                        var selectedValue = $(this).find('select').val();

                        updateURL(selectedName, selectedValue, $(this).find('select'), finalURL);
                    } else if ( $(this).find('select').length > 1 ) {
                        if ( triggerElement && triggerElement.hasClass('crt-af-main-select') ) {
                            $(this).find('select').each(function() {
                                if ( $(this).hasClass('crt-af-main-select') ) {
                                    var selectedName = $(this).attr('name');
                                    var selectedValue = $(this).val();

                                    updateURL(selectedName, selectedValue, $(this), finalURL);
                                } else {
                                    var selectedName = $(this).attr('name');
                                    var selectedValue = 0;

                                    updateURL(selectedName, selectedValue, $(this), finalURL);
                                }
                            });
                        } else {
                            $(this).find('select').each(function() {
                                var selectedName = $(this).attr('name');
                                var selectedValue = $(this).val();

                                updateURL(selectedName, selectedValue, $(this), finalURL);
                            });
                        }
                    }

                    if ( $(this).find('.crt-af-range-apply-btn').length > 0 && $(this).data('crt-applied') == 'yes' ) {
                        var minInp = $(this).find('.crt-af-rf-control-min-input'),
                            maxInp = $(this).find('.crt-af-rf-control-max-input'),
                            selectedName = minInp.attr('name'),
                            selectedValue = [];
                        if ( minInp.length && maxInp.length && selectedName ) {
                            var mn = parseRangeInputVal(minInp.val()), mx = parseRangeInputVal(maxInp.val());
                            selectedValue.push(isNaN(mn) ? minInp.attr('min') || minInp.data('min') : mn);
                            selectedValue.push(isNaN(mx) ? maxInp.attr('max') || maxInp.data('max') : mx);
                            updateURL(selectedName, selectedValue, $(this).find('.crt-af-range-apply-btn'), finalURL);
                        }
                    } else if ( $(this).find('.crt-af-range-container').length > 0 && ($(this).data('crt-applied') == 'yes' || self.hasClass('crt-af-apply-btn')) ) {
                        var minInp = $(this).find('.crt-af-rf-control-min-input'),
                            maxInp = $(this).find('.crt-af-rf-control-max-input'),
                            selectedName = minInp.attr('name'),
                            selectedValue = [];
                        if ( minInp.length && maxInp.length && selectedName ) {
                            var mn = parseRangeInputVal(minInp.val()), mx = parseRangeInputVal(maxInp.val());
                            selectedValue.push(isNaN(mn) ? minInp.attr('min') || minInp.data('min') : mn);
                            selectedValue.push(isNaN(mx) ? maxInp.attr('max') || maxInp.data('max') : mx);
                            updateURL(selectedName, selectedValue, $(this).find('.crt-af-range-container'), finalURL);
                        }
                    }
                });

                renderActiveFilters('ajax');

                let targetGrid = actionSelector;

                // If not ajax relocate
                if ( self.closest('.crt-advanced-filters-wrap').length > 0 && 'yes' !== self.closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                    window.location.href = finalURL;
                } else {
                    // Settings
                    settings = targetGrid.attr( 'data-settings' );

                    if ( typeof settings !== typeof undefined && settings !== false ) {
                        settings = JSON.parse( targetGrid.attr( 'data-settings' ) );
                    }

                    // Create a URL object
                    var url = new URL(finalURL);

                    // Extract the search query parameters
                    var queryParams = new URLSearchParams(url.search),
                        loader = '<div class="crt-filters-loader-wrap"><div class="crt-ring"><div></div><div></div><div></div><div></div></div></div>';

                    // Convert queryParams into an object
                    paramsObj = {};

                    queryParams.forEach(function(value, key) {
                        paramsObj[key] = value;
                    });

                    let debounceTimer;

                    // Loading
                    widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-load-more-btn' ).hide();

                    if ( triggerElement.hasClass('crt-af-reset-btn') ) {
                        paramsObj = {};
                    }

                    debounceAjaxCall(debounceTimer, paramsObj, targetGrid, settings, widgetSelector, triggerElement);
                }
            }

            function updateURL(selectedName, selectedValue, element, ajaxFilterURL = '') {
                if ( !selectedName ) return;
                var currentURL = window.location.href,
                    dataRelation = '',
                    dataFilterType = '';


                if ( '' != ajaxFilterURL ) {
                    currentURL = ajaxFilterURL;
                }

                if ( $('.crt-af-apply-btn').length > 0 ) {
                    currentURL = finalURL;
                    dataRelation = element.closest('.crt-advanced-filters-wrap').attr('data-crt-relation');
                    dataFilterType = element.closest('.crt-advanced-filters-wrap').attr('data-crt-filter-type');
                } else {
                    dataRelation = element.closest('.crt-advanced-filters-wrap').attr('data-crt-relation');
                    dataFilterType = element.closest('.crt-advanced-filters-wrap').attr('data-crt-filter-type');
                }

                const url = new URL(currentURL);

                if ( dataRelation && dataFilterType ) {
                    url.searchParams.set(selectedName.replace('crt_af', 'crt_afr'), dataRelation + ',' + dataFilterType);
                }

                if ( element.prop('tagName') == "SELECT" ) {
                    if ( selectedValue == 0 ) {
                        url.searchParams.delete(selectedName);
                        url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                    } else {
                        url.searchParams.set(selectedName, selectedValue);
                    }
                } else if ( element.hasClass('crt-af-range-apply-btn') || element.hasClass('crt-af-range-container') || element.hasClass('crt-af-from-slider') || element.hasClass('crt-af-to-slider') || element.hasClass('crt-af-rf-control-min-input') || element.hasClass('crt-af-rf-control-max-input') ) {
                    url.searchParams.set(selectedName, selectedValue);
                    url.searchParams.set(selectedName.replace('crt_af', 'crt_aft'), dataFilterType);
                } else if ( element.attr('type') == "text" || element.attr('type') == "date"  || element.attr('type') == "date_range" )  {
                    if (url.searchParams.has(selectedName)) {

                        var currentValue = '';

                        // Get the current value of the query parameter
                        if ( url.searchParams.get(selectedName) !== null ) {
                            currentValue = url.searchParams.get(selectedName);
                        }

                        // Combine the current value and the new selected value
                        var updatedValue = selectedValue;

                        if ( element.hasClass('crt-date-filter') ) {
                            if ( selectedValue == '' ) {
                                url.searchParams.delete(selectedName);
                                url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                            } else {
                                url.searchParams.set(selectedName, selectedValue);
                            }
                        }

                        if ( element.hasClass('crt-date-filter-start') ) {
                            if ( currentValue.indexOf(',') !== -1 ) {
                                updatedValue = selectedValue + ',' + currentValue.split(',')[1];

                                if ( selectedValue == '' && currentValue.split(',')[1] == '' ) {
                                    url.searchParams.delete(selectedName);
                                    url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                                }
                            } else {
                                if ( selectedValue !== '' ) {
                                    updatedValue = selectedValue + ',' + currentValue;
                                } else {
                                    url.searchParams.delete(selectedName);
                                    url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                                }

                                if ( selectedValue == '' && currentValue.split(',')[1] == '' ) {
                                    url.searchParams.delete(selectedName);
                                    url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                                }
                            }
                        }

                        if ( element.hasClass('crt-date-filter-end') ) {
                            if ( currentValue.indexOf(',') !== -1 ) {
                                updatedValue = currentValue.split(',')[0] + ',' + selectedValue;

                                if ( selectedValue == '' && currentValue.split(',')[0] == '' ) {
                                    url.searchParams.delete(selectedName);
                                    url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                                }
                            } else {
                                if ( selectedValue !== '' ) {
                                    updatedValue = currentValue + ',' + selectedValue;
                                } else {
                                    url.searchParams.delete(selectedName);
                                    url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                                }

                                if ( selectedValue == '' && currentValue.split(',')[0] == '' ) {
                                    url.searchParams.delete(selectedName);
                                    url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                                }
                            }
                        }

                        if ( updatedValue.includes(',') ) {
                            updatedValue = updatedValue.split(',');
                            updatedValue = [...new Set(updatedValue)];

                            // updatedValue = updatedValue.filter(function(value) {
                            // 	return value !== "";
                            // });

                            updatedValue = updatedValue.join(',');
                        }

                        if (updatedValue !== '') {
                            // Set the query parameter with the updated value
                            url.searchParams.set(selectedName, updatedValue);
                        }
                    } else {
                        if ( element.hasClass('crt-date-filter') ) {
                            url.searchParams.set(selectedName, selectedValue);
                        }

                        // If the query parameter doesn't exist, set it with the selected value
                        if ( element.hasClass('crt-date-filter-start') ) {
                            url.searchParams.set(selectedName, selectedValue + ',' + '');
                        }

                        if ( element.hasClass('crt-date-filter-end') ) {
                            url.searchParams.set(selectedName, ',' + '' + selectedValue);
                        }
                    }

                } else {
                    if ( element.attr('type') == "radio" ) {
                        url.searchParams.delete(selectedName);
                        url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                    }

                    if ( element.is(':checked') || (element.hasClass('crt-rating-filter') && element.closest('.crt-active-product-filter').length > 0) ) {
                        if ( url.searchParams.has(selectedName) ) {
                            // Get the current value of the query parameter
                            const currentValue = url.searchParams.get(selectedName);

                            // Combine the current value and the new selected value
                            var updatedValue = selectedValue;
                            updatedValue = currentValue + ',' + selectedValue;

                            if ( $.inArray(selectedValue, currentValue.split(',')) !== -1 ||  currentValue == selectedValue ) {
                                return;
                            }

                            if ( updatedValue.includes(',') ) {
                                updatedValue = updatedValue.split(',');
                                updatedValue = [...new Set(updatedValue)];
                                updatedValue = updatedValue.filter(function(value) {
                                    return value !== "";
                                });

                                updatedValue = updatedValue.join(',');
                            }

                            // Set the query parameter with the updated value
                            url.searchParams.set(selectedName, updatedValue);
                        } else {
                            // If the query parameter doesn't exist, set it with the selected value
                            url.searchParams.set(selectedName, selectedValue);
                        }
                    } else {
                        if (url.searchParams.has(selectedName)) {
                            // Get the current value of the query parameter
                            const currentValue = url.searchParams.get(selectedName);

                            if ( currentValue.includes(',') || currentValue !== false ) {
                                const currentValueArray = currentValue.split(',');

                                // Remove duplicates using Set
                                const uniqueArray = [...new Set(currentValueArray)];

                                // Omit the selectedValue from the array if it exists
                                const indexToRemove = uniqueArray.indexOf(selectedValue);

                                if (indexToRemove !== -1) {
                                    uniqueArray.splice(indexToRemove, 1);
                                }

                                // Join the updated array back into a string using a comma (',') as the separator
                                var updatedValue = uniqueArray.join(',');

                                // Set the query parameter with the updated value
                                url.searchParams.set(selectedName, updatedValue);
                            } else {
                                url.searchParams.delete(selectedName);
                                url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                            }
                        } else {
                            // If the query parameter doesn't exist, set it with the selected value
                            url.searchParams.delete(selectedName);
                            url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                        }
                    }

                    if (url.searchParams.get(selectedName) == '') {
                        url.searchParams.delete(selectedName);
                        url.searchParams.delete(selectedName.replace('crt_af', 'crt_afr'));
                    }
                }

                // Replace the current URL with the updated one
                if ( $('.crt-af-apply-btn').length == 0 && 'yes' !== element.closest('.crt-advanced-filters-wrap').data('enable-ajax') ) {
                    window.location.href = url.toString();
                } else {
                    if ( $('.crt-af-apply-btn').length > 0 && $('.crt-af-apply-btn').data('redirect-url') != '#' ) {
                        url.pathname = $('.crt-af-apply-btn').data('redirect-url');
                    }

                    CrtElements.changeFinalURL(url.toString());
                }
            }

            function updateGrid(data, cache, startvar, targetGrid, settings, widgetSelector) {
                setTimeout(function() {
                    if ( !cache ) {
                        $('body').find('.crt-filters-loader-wrap').remove();
                    }

                    // Ensure data is a jQuery object
                    var newItems = $(data.data.output);

                    // Wait for images to load before layout
                    targetGrid.imagesLoaded(function() {
                        if ( cache ) {
                            targetGrid.removeClass( 'crt-grid-cache' );
                            $('body').find('.crt-filters-loader-wrap').remove();
                        }

                        targetGrid.isotopecrt('remove', targetGrid.children());
                        targetGrid.removeClass('crt-grid-loading');

                        // Append new items
                        targetGrid.append(newItems);
                        targetGrid.isotopecrt('appended', newItems).isotopecrt('layout');
                        CrtElements.isotopeLayout(settings, '', widgetSelector, true, $scope);
                        CrtElements.mediaHoverLink($scope, targetGrid);

                        if ( targetGrid.data('lightGallery') ) {
                            targetGrid.data( 'lightGallery' ).destroy( true );
                        }

                        setTimeout(function() {
                            CrtElements.isotopeLayout(settings, '', widgetSelector, true, $scope);
                            CrtElements.lightboxPopup( settings, $scope, targetGrid );
                            updateResultsCount(settings);

                            if ( widgetSelector.find('.woocommerce-result-count').length ) {
                                var resultCount = widgetSelector.find('.woocommerce-result-count').text();

                                // Determine the first number based on whether there are results
                                var firstNumber = data.data.post_count > 0 ? (resultCount.match(/(\d+)\u2013/)?.[1] || '1') : '0';

                                // If first number is 0 but we have results, change it to 1
                                if (firstNumber === '0' && data.data.post_count > 0) {
                                    firstNumber = '1';
                                }

                                // Replace with new values from AJAX response
                                var updatedResultCount = resultCount.replace(
                                    /(\d+)\u2013(\d+)\s+of\s+(\d+)/,
                                    firstNumber + '\u2013' + data.data.post_count + ' of ' + data.data.found_posts
                                );

                                widgetSelector.find('.woocommerce-result-count').text(updatedResultCount);
                            }

                            if ( data.data.found_posts > 0 && !(data.data.found_posts <= widgetSelector.find('.crt-grid-item').length) ) {
                                if ( 'load-more' === settings.pagination_type ) {
                                    widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-finish' ).hide();
                                    widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-load-more-btn' ).show();
                                    if (! widgetSelector.find( '.crt-grid-pagination' ).is(':visible') ) {
                                        widgetSelector.find( '.crt-grid-pagination' ).delay( 1000 ).fadeIn( 500 );
                                    }
                                }
                            } else {
                                widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-load-more-btn' ).hide();
                                widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                widgetSelector.find( '.crt-grid-pagination' ).delay( 1000 ).fadeOut( 500 );
                                setTimeout(function() {
                                    widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).hide();
                                }, 500 );
                            }
                        }, 500);
                    });
                    const end = performance.now();
                    // console.log(`AJAX call took ${end - startvar} ms`);
                }, 500);
            }

            function debounceAjaxCall(debounceTimer, paramsObj, targetGrid, settings, widgetSelector, triggerElement = '') {

                targetGrid.addClass('crt-grid-loading');

                clearTimeout(debounceTimer);

                debounceTimer = setTimeout(function() {
                    // isDebounceAjaxCallRunning = true;

                    const start = performance.now();
                    const cacheKey = 'crt_grid_cache_' + JSON.stringify(paramsObj);

                    // Check if data is already cached
                    const cachedData = localStorage.getItem(cacheKey);
                    const expiryTime = 24 * 60 * 60 * 1000; // 24 hours
                    const now = new Date().getTime();

                    if (cachedData && 'goga' === 'droga') {
                        const cachedDataParsed = JSON.parse(cachedData);

                        if (cachedDataParsed && ((now - cachedDataParsed.timestamp) < expiryTime)) {

                            console.log((now - cachedDataParsed.timestamp), expiryTime);

                            targetGrid.addClass( 'crt-grid-cache' );
                            updateGrid((cachedDataParsed.data), true, start, targetGrid, settings, widgetSelector);
                            return;
                            // Use cached data
                        } else {
                            localStorage.removeItem(cacheKey);
                        }
                    }

                    let orderby = '';

                    if ( widgetSelector.find('select.orderby').length > 0 ) {
                        orderby = widgetSelector.find('select.orderby').val();
                    }

                    if ( triggerElement.hasClass('crt-load-more-btn') ) {
                        widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).show();

                        settings.grid_settings.query_offset = +settings.grid_settings.query_offset + targetGrid.find('.crt-grid-item').length;

                        $.ajax({
                            type: 'POST',
                            url: CRTConfig.ajaxurl,
                            data: {
                                action: experimentActionContent,
                                nonce: CRTConfig.nonce,
                                crt_offset: +settings.grid_settings.query_offset + $scope.find('.crt-grid-item').length,
                                crt_item_length: targetGrid.find('.crt-grid-item').length,
                                grid_settings: settings.grid_settings,
                                crt_url_params: paramsObj,
                                orderby: orderby,
                            },
                            success: function(response) {
                                pagesLoadedExperiment++;
                                // iGrid.css('opacity', 0);
                                var items = $(response.data.output)

                                // $data.each(function() {
                                // 	$(this).addClass('crt-grid-hidden-item');
                                // });

                                targetGrid.infiniteScroll( 'appendItems', items );
                                targetGrid.isotopecrt( 'appended', items );
                                // isotopeFilters( settings ); // GOGA - if not images loaded

                                items.imagesLoaded().progress( function( instance, image ) {
                                    CrtElements.isotopeLayout(settings, '', widgetSelector, true, $scope);

                                    // Fix Layout
                                    setTimeout(function() {
                                        CrtElements.isotopeLayout(settings, '', widgetSelector, true, $scope);
                                        // isotopeFilters( settings );
                                    }, 100 );

                                    setTimeout(function() {
                                        targetGrid.addClass( 'grid-images-loaded' );
                                    }, 500 );
                                });

                                if ( response.data.found_posts > 0 && response.data.found_posts > widgetSelector.find('.crt-grid-item').length ) {
                                    if ( 'load-more' === settings.pagination_type ) {
                                        widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).hide();
                                        widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-load-more-btn' ).delay(500).show();
                                    }
                                } else {
                                    widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).hide();
                                    widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-finish' ).fadeIn( 1000 );
                                    widgetSelector.find( '.crt-grid-pagination' ).delay( 1000 ).fadeOut( 500 );
                                    setTimeout(function() {
                                        widgetSelector.find( '.crt-grid-pagination' ).find( '.crt-pagination-loading' ).hide();
                                    }, 500 );
                                }

                                // Init Likes
                                // No need for this anymore
                                // setTimeout(function() {
                                // 	postLikes( settings );
                                // }, 300 );

                                // Init Lightbox
                                CrtElements.lightboxPopup( settings, widgetSelector, targetGrid );

                                // Fix Lightbox
                                targetGrid.data( 'lightGallery' ).destroy( true );
                                targetGrid.lightGallery( settings.lightbox );

                                // Init Media Hover Link
                                CrtElements.mediaHoverLink($scope, targetGrid);
                                targetGrid.removeClass('crt-grid-loading');

                                // Init Post Sharing
                                // postSharing();

                                // lazyLoadObserver();
                                // Maybe there is some other way
                                window.dispatchEvent(new Event('resize'));
                                window.dispatchEvent(new Event('scroll'));
                                $(window).trigger('scroll');

                                if ( widgetSelector.find('.woocommerce-result-count').length ) {
                                    var resultCount = widgetSelector.find('.woocommerce-result-count').text(),
                                        updatedResultCount = resultCount.replace( /\d\u2013\d+/, '1\u2013' + ( widgetSelector.find('.crt-grid-item').length ) );

                                    widgetSelector.find('.woocommerce-result-count').text(updatedResultCount);
                                }
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    } else {
                        $.ajax({
                            type: 'POST',
                            url: CRTConfig.ajaxurl,
                            data: {
                                action: experimentActionContent,
                                nonce: CRTConfig.nonce,
                                crt_item_length: targetGrid.find('.crt-grid-item').length,
                                grid_settings: settings.grid_settings,
                                crt_url_params: paramsObj,
                                orderby: orderby,
                            },
                            success: function(response) { // GOGA: extend from filtersExperiment if/when needed
                                // Set item with expiration
                                const cacheData = {
                                    data: response.data.output,
                                    timestamp: new Date().getTime()
                                };

                                // Cache the response
                                // localStorage.setItem(cacheKey, JSON.stringify(cacheData));
                                CrtElements.changeInitialItems(0, gridScopeId);
                                updateGrid(response, false, start, targetGrid, settings, widgetSelector);
                            },
                            error: function(error) {
                                console.log(error);
                            }
                        });
                    }
                }, 300); // Adjust timeout value based on the desired debounce delay
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
            editorCheck: function() {
                return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
            },
            changeFinalURL: function(url) {
                finalURL = url;
            },
        }
    });
})(jQuery);