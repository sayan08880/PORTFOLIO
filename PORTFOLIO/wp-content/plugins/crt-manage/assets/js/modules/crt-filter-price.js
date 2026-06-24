(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-filter-price.default',function($scope) {
            $scope.find('.crt-price-filter-wrapper').each(function () {
                var $wrapper = $(this);
                var $slider = $wrapper.find('.crt-price-slider');
                var minPrice = parseFloat($wrapper.data('min'));
                var maxPrice = parseFloat($wrapper.data('max'));
                var currentMin = parseFloat($wrapper.find('.crt-price-min').val()) || minPrice;
                var currentMax = parseFloat($wrapper.find('.crt-price-max').val()) || maxPrice;
                $slider.slider({
                    range: true,
                    min: minPrice,
                    max: maxPrice,
                    values: [currentMin, currentMax],
                    slide: function (event, ui) {
                        $wrapper.find('.crt-price-min').val(ui.values[0]);
                        $wrapper.find('.crt-price-max').val(ui.values[1]);
                        $wrapper.find('.crt-af-price-value').val(ui.values[0] + '-' + ui.values[1]);
                    },
                    change: function (event, ui) {
                        $wrapper.find('.crt-af-price-value').val(ui.values[0] + '-' + ui.values[1]);
                    }
                });

                // Update slider when inputs change
                $wrapper.find('.crt-price-min, .crt-price-max').on('change', function () {
                    var minVal = parseFloat($wrapper.find('.crt-price-min').val()) || minPrice;
                    var maxVal = parseFloat($wrapper.find('.crt-price-max').val()) || maxPrice;

                    if (minVal > maxVal) {
                        var temp = minVal;
                        minVal = maxVal;
                        maxVal = temp;
                    }

                    $slider.slider("values", [minVal, maxVal]);
                    $wrapper.find('.crt-af-price-value').val(minVal + '-' + maxVal);
                });
            });

            $scope.find('.crt-filter-price-widget form').on('submit', function (e) {
                // e.preventDefault(); // Let standard submission happen or handle existing logic
                // Existing inline script logic handled URL params. We should implement that here to be safe and clean.
                e.preventDefault();

                var $form = $(this);
                var min = $form.find('.crt-price-min').val();
                var max = $form.find('.crt-price-max').val();
                var url = new URL(window.location.href);

                url.searchParams.set('filter_price', min + '-' + max);
                url.searchParams.delete('paged');

                window.location.href = url.toString();
            });

            $scope.find('.crt-filter-price-widget .crt-filter-reset-btn').on('click', function (e) {
                e.preventDefault();
                var url = new URL(window.location.href);
                url.searchParams.delete('filter_price');
                url.searchParams.delete('paged');
                window.location.href = url.toString();
            });
        });
    });
})(jQuery);
