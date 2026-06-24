(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-charts.default',function($scope) {

            var chartSettings = JSON.parse($scope.find('.crt-charts-container').attr('data-settings'));
            var labels = chartSettings.chart_labels;
            var customDatasets = chartSettings.chart_datasets ? JSON.parse(chartSettings.chart_datasets) : '';

            var newLegendClickHandler = function (e, legendItem, legend) {
                if ( (chartTypesArray.includes(chartSettings.chart_type) || chartSettings.chart_type === 'radar') ) {
                    const index = legendItem.datasetIndex;
                    const ci = legend.chart;
                    if (ci.isDatasetVisible(index)) {
                        ci.hide(index);
                        legendItem.hidden = true;
                    } else {
                        ci.show(index);
                        legendItem.hidden = false;
                    }
                }
            }

            const footer = (tooltipItems) => {
                let sum = 0;

                tooltipItems.forEach(function(tooltipItem) {
                    sum += tooltipItem.parsed.y;
                });

                if ( 'bar_horizontal' === chartSettings.chart_type ) {
                    sum = 0;
                    tooltipItems.forEach(function(tooltipItem) {
                        sum += tooltipItem.parsed.x;
                    });
                }

                if ( "radar" == chartSettings.chart_type || "pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type ) {
                    return false;
                }
                return 'Sum: ' + sum;
            };

            var lineDotsWidth = window.innerWidth >= 768 ? chartSettings.line_dots_radius
                : window.innerWidth <= 767 ? chartSettings.line_dots_radius_mobile : 0;
            var tooltipCaretSize = window.innerWidth >= 768 ? chartSettings.tooltip_caret_size
                :  window.innerWidth <= 767 ? chartSettings.chart_tooltip_caret_size_mobile : 0;

            var myChart = '';
            var config = '';
            var chartTypesArray = ['bar', 'bar_horizontal', 'line'];
            var globalOptions = {
                responsive: true,
                // layout: { // needs other approach
                // 	padding: chartPadding,
                // },
                showLine: chartSettings.show_lines,
                animation: chartSettings.chart_animation === 'yes' ? true : false,
                animations: {
                    tension: {
                        duration: chartSettings.chart_animation_duration,
                        easing: chartSettings.animation_transition_type,
                        from: 1,
                        to: 0,
                        loop: chartSettings.chart_animation_loop == 'yes' ? true : false,
                    },
                }, // specify exact inserting way
                events: [chartSettings.trigger_tooltip_on, chartSettings.exclude_dataset_on_click === 'yes' ? 'click' : '',],
                interaction: {
                    // Overrides the global setting
                    mode: chartSettings.chart_interaction_mode !== undefined ? chartSettings.chart_interaction_mode : 'nearest',
                },
                elements: {
                    point: {
                        radius: chartSettings.line_dots === 'yes' ? lineDotsWidth : 0 // default to disabled in all datasets
                    }
                },
                scales: { // remove if corrupts other chart_types data
                    x: {
                        reverse: chartSettings.reverse_x == 'yes' ? true : false,
                        stacked: chartSettings.stacked_bar_chart == 'yes' ? true : false,
                        type: 'bar_horizontal' === chartSettings.chart_type ? chartSettings.data_type : 'category',
                        min: chartSettings.min_value !== undefined ? chartSettings.min_value : null,
                        max: chartSettings.max_value !== undefined ? chartSettings.max_value : null,
                        grid: {
                            display: chartSettings.display_x_axis,
                            drawBorder: chartSettings.display_x_axis,
                            drawOnChartArea: chartSettings.display_x_axis,
                            drawTicks: chartSettings.display_x_axis,
                            color: chartSettings.axis_grid_line_color_x,
                            // borderColor: 'green',
                            // borderWidth: 5,
                            borderDash: [chartSettings.border_dash_length, chartSettings.border_dash_spacing],
                            borderDashOffset: chartSettings.border_dash_offset,
                            lineWidth: chartSettings.grid_line_width_x,
                        },
                        title: {
                            display: chartSettings.display_x_axis_title,
                            text: chartSettings.x_axis_title,
                            color: chartSettings.axis_title_color_x,
                            font: {
                                size: chartSettings.axis_title_font_size_x,
                                family: chartSettings.axis_title_font_family_x,
                                style: chartSettings.axis_title_font_style_x,
                                weight: chartSettings.axis_title_font_weight_x,
                            }
                        },
                        ticks: {
                            stepSize: 'bar_horizontal' === chartSettings.chart_type ? chartSettings.x_step_size : '',
                            display: chartSettings.display_x_ticks,
                            padding: chartSettings.ticks_padding_x,
                            autoSkip: false,
                            maxRotation: chartSettings.rotation_x,
                            minRotation: chartSettings.rotation_x,
                            color: chartSettings.ticks_color_x,
                            // backdropColor: 'rgb(128,0,128)',
                            font: {
                                size: chartSettings.ticks_font_size_x,
                                family: chartSettings.ticks_font_family_x,
                                style: chartSettings.ticks_font_style_x,
                                weight: chartSettings.ticks_font_weight_x,
                            }
                        },
                    },
                    y: {
                        reverse: chartSettings.reverse_y == 'yes' ? true : false,
                        stacked: chartSettings.stacked_bar_chart == 'yes' ? true : false,
                        type: 'bar' === chartSettings.chart_type || 'line' === chartSettings.chart_type ? chartSettings.data_type : 'category',
                        min: chartSettings.min_value !== undefined ? chartSettings.min_value : null,
                        max: chartSettings.max_value !== undefined ? chartSettings.max_value : null,
                        grid: {
                            display: chartSettings.display_y_axis,
                            drawBorder: chartSettings.display_y_axis,
                            drawOnChartArea: chartSettings.display_y_axis,
                            drawTicks: chartSettings.display_y_axis,
                            color: chartSettings.axis_grid_line_color_y,
                            // borderColor: 'green',
                            // borderWidth: 5,
                            borderDash: [chartSettings.border_dash_length, chartSettings.border_dash_spacing],
                            borderDashOffset: chartSettings.border_dash_offset,
                            lineWidth: chartSettings.grid_line_width_y,
                        },
                        title: {
                            display: chartSettings.display_y_axis_title,
                            text: chartSettings.y_axis_title,
                            color: chartSettings.axis_title_color_y,
                            font: {
                                size: chartSettings.axis_title_font_size_y,
                                family: chartSettings.axis_title_font_family_y,
                                style: chartSettings.axis_title_font_style_y,
                                weight: chartSettings.axis_title_font_weight_y,
                            }
                        },
                        ticks: {
                            stepSize: chartSettings.y_step_size,
                            display: chartSettings.display_y_ticks,
                            padding: chartSettings.ticks_padding_y,
                            autoSkip: false,
                            maxRotation: chartSettings.rotation_y,
                            minRotation: chartSettings.rotation_y,
                            color: chartSettings.ticks_color_y,
                            // backdropColor: 'rgb(128,0,128)',
                            font: {
                                size: chartSettings.ticks_font_size_y,
                                family: chartSettings.ticks_font_family_y,
                                style: chartSettings.ticks_font_style_y,
                                weight: chartSettings.ticks_font_weight_y,
                            }
                        },
                    },
                },
                plugins: {
                    datalabels: {
                        color: chartSettings.inner_datalabels_color,
                        // backgroundColor: chartSettings.inner_datalabels_bg_color,
                        font: {
                            // family: chartSettings.inner_datalabels_font_family,
                            size: chartSettings.inner_datalabels_font_size,
                            style: chartSettings.inner_datalabels_font_style,
                            weight: chartSettings.inner_datalabels_font_weight,
                        },
                    },
                    legend: {
                        onHover: (event, chartElement) => {
                            event.native.target.style.cursor = 'pointer';
                        },
                        onLeave: (event, chartElement) => {
                            event.native.target.style.cursor = 'default';
                        },
                        onClick: newLegendClickHandler,
                        reverse: chartSettings.reverse_legend === 'yes' ? true : false,
                        display: chartSettings.show_chart_legend == 'yes' ? true : false,
                        position: chartSettings.legend_position !== undefined ? chartSettings.legend_position : 'top',
                        align: chartSettings.legend_align !== undefined ? chartSettings.legend_align : 'center',
                        labels: {
                            usePointStyle: chartSettings.legend_shape == 'point' ? true : false,
                            padding: chartSettings.legend_padding,
                            boxWidth: chartSettings.legend_box_width,
                            boxHeight: chartSettings.legend_font_size,
                            color: chartSettings.legend_text_color,
                            font: {
                                family: chartSettings.legend_font_family,
                                size: chartSettings.legend_font_size,
                                style: chartSettings.legend_font_style,
                                weight: chartSettings.legend_font_weight,
                            },
                        }
                    },
                    title: {
                        display: 'yes' === chartSettings.show_chart_title ? true : false,
                        text: chartSettings.chart_title,
                        align: chartSettings.chart_title_align !== undefined ? chartSettings.chart_title_align : 'center',
                        position: chartSettings.chart_title_position !== undefined ? chartSettings.chart_title_position : 'top',
                        color: chartSettings.chart_title_color !== undefined ? chartSettings.chart_title_color : '#000',
                        padding: chartSettings.title_padding,
                        font: {
                            family: chartSettings.title_font_family,
                            size: chartSettings.title_font_size,
                            style: chartSettings.title_font_style,
                            weight: chartSettings.title_font_weight,
                        },
                    },
                    tooltip: {
                        callbacks: {
                            footer: footer,
                        },
                        enabled: 'yes' === chartSettings.show_chart_tooltip ? true : false,
                        position: chartSettings.tooltip_position !== undefined ? chartSettings.tooltip_position : 'nearest',
                        padding: chartSettings.tooltip_padding !== undefined ? chartSettings.tooltip_padding : 10,
                        caretSize: tooltipCaretSize,
                        backgroundColor: chartSettings.chart_tooltip_bg_color !== undefined ? chartSettings.chart_tooltip_bg_color : 'rbga(0, 0, 0, 0.2)',
                        titleColor: chartSettings.chart_tooltip_title_color !== undefined ? chartSettings.chart_tooltip_title_color : '#FFF',
                        titleFont: {
                            family: chartSettings.chart_tooltip_title_font,
                            size: chartSettings.chart_tooltip_title_font_size,
                        },
                        titleAlign: chartSettings.chart_tooltip_title_align,
                        titleMarginBottom: chartSettings.chart_tooltip_title_margin_bottom,
                        bodyColor: chartSettings.chart_tooltip_item_color !== undefined ? chartSettings.chart_tooltip_item_color : '#FFF',
                        bodyFont: {
                            family: chartSettings.chart_tooltip_item_font,
                            size: chartSettings.chart_tooltip_item_font_size,
                        },
                        bodyAlign: chartSettings.chart_tooltip_item_align,
                        bodySpacing: chartSettings.chart_tooltip_item_spacing,
                        boxPadding: 3
                    }
                },
            };

            !chartTypesArray.includes(chartSettings.chart_type) && delete globalOptions.scales;

            if ( !chartTypesArray.includes(chartSettings.chart_type) && (chartSettings.chart_type !== 'doughnut' && chartSettings.chart_type !== 'pie') ) {

                globalOptions.scales = {
                    r: {
                        angleLines: {
                            color: chartSettings.angle_lines_color,
                        },
                        pointLabels: {
                            color: chartSettings.point_labels_color_r,
                            font: {
                                size: chartSettings.point_labels_font_size_r,
                                family: chartSettings.point_labels_font_family_r,
                                style: chartSettings.point_labels_font_style_r,
                                weight: chartSettings.point_labels_font_weight_r,
                            }
                        },
                        ticks: {
                            stepSize: chartSettings.r_step_size,
                            display: chartSettings.display_r_ticks,
                            backdropColor: chartSettings.axis_labels_bg_color,
                            backdropPadding: +chartSettings.axis_labels_padding,
                            color: chartSettings.axis_labels_color,
                        },
                        grid: {
                            display: chartSettings.display_r_axis,
                            drawBorder: chartSettings.display_r_axis,
                            drawOnChartArea: chartSettings.display_r_axis,
                            drawTicks: chartSettings.display_r_axis,
                            color: chartSettings.axis_grid_line_color_r,
                            borderDash: [chartSettings.border_dash_length_r, chartSettings.border_dash_spacing_r],
                            borderDashOffset: chartSettings.border_dash_offset_r,
                            lineWidth: chartSettings.grid_line_width_r,
                        }
                    },
                }
            }

            if ('custom' === chartSettings.data_source) {
                const data = {
                    labels: labels,
                    datasets: JSON.parse(chartSettings.chart_datasets),
                }; // todo apply conditions if not suitable for other chart_types

                config = {
                    plugins: [chartSettings.inner_datalabels ? ChartDataLabels : ''],
                    type: chartSettings.chart_type == 'bar_horizontal' ? 'bar' : chartSettings.chart_type,
                    data: data,
                    options: globalOptions
                };

                chartSettings.chart_type == 'bar_horizontal' ? config.options.indexAxis = 'y' : '';

                if (chartSettings.tooltips_percent || "pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type) {
                    config.options.plugins.tooltip.callbacks.label = function (data) {
                        var prefixString = data.dataset.label + ": ";

                        if ("pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type) {
                            prefixString = data.label + ' ('+data.dataset.label+') ' + ": ";
                        }

                        var dataset = data.dataset;

                        var total = dataset.data.reduce(function (previousValue, currentValue) {
                            return parseFloat(previousValue) + parseFloat(currentValue);
                        });

                        var currentValue = data.formattedValue;

                        var percentage = ((currentValue / total) * 100).toPrecision(3);

                        return (
                            prefixString + (chartSettings.tooltips_percent ? percentage + "%" : data.formattedValue)
                        );
                    }
                }

                myChart = new Chart(
                    $scope.find('.crt-chart'),
                    config
                );
            } else {
                if ( chartSettings.url && (chartTypesArray.includes(chartSettings.chart_type) || chartSettings.chart_type === 'radar') ) {
                    $.ajax({
                        url: chartSettings.url,
                        type: "GET",
                        success: function (res) {
                            $scope.find(".crt-rotating-plane").remove();
                            renderCSVChart(res, chartSettings);
                        },
                        error: function (err) {
                            console.log(err);
                        }
                    });
                } else if (!chartSettings.url && (chartTypesArray.includes(chartSettings.chart_type) || chartSettings.chart_type === 'radar')) {
                    $scope.find(".crt-rotating-plane").remove();
                    $scope.find('.crt-charts-container').html('<p class="crt-charts-error-notice">Provide a csv file or remote URL</p>');
                } else {
                    $scope.find(".crt-rotating-plane").remove();
                    $scope.find('.crt-charts-container').html('<p class="crt-charts-error-notice">doughnut, pie and polareArea charts only work with custom data source</p>');
                }
            }

            $(window).resize(function() {
                lineDotsWidth = window.innerWidth >= 768 ? chartSettings.line_dots_radius
                    : window.innerWidth <= 767 ? chartSettings.line_dots_radius_mobile : 0;
                config.options.elements.point.radius = lineDotsWidth;
                config.options.plugins.tooltip.caretSize = tooltipCaretSize;
            });

            function renderCSVChart (res, chartSettings) {
                var ctx = $scope.find('.crt-chart'),
                    rowsData = res.split(/\r?\n|\r/),
                    labels = (rowsData.shift()).split(chartSettings.separator),
                    data = {
                        labels: labels,
                        datasets: []
                    };

                config = {
                    type: chartSettings.chart_type == 'bar_horizontal' ? 'bar' : chartSettings.chart_type,
                    data: data,
                    options: globalOptions,
                    plugins: [chartSettings.inner_datalabels ? ChartDataLabels : '', {
                        beforeInit: function(chart, options) {
                            chart.legend.afterFit = function() {
                                this.height = this.height + 50;
                            };
                        }
                    }],
                };

                chartSettings.chart_type == 'bar_horizontal' ? config.options.indexAxis = 'y' : '';

                if (chartSettings.tooltips_percent) {
                    config.options.plugins.tooltip.callbacks.label = function (data) {
                        var prefixString = data.dataset.label + ": ";

                        if ("pie" == chartSettings.chart_type || "doughnut" == chartSettings.chart_type || "polarArea" == chartSettings.chart_type) {
                            prefixString = data.label + ' ('+data.dataset.label+') ' + ": ";
                        }

                        var dataset = data.dataset;

                        var total = dataset.data.reduce(function (previousValue, currentValue) {
                            return parseFloat(previousValue) + parseFloat(currentValue);
                        });

                        var currentValue = data.formattedValue;

                        var percentage = ((currentValue / total) * 100).toPrecision(3);

                        return (
                            prefixString + (chartSettings.tooltips_percent ? percentage + "%" : data.formattedValue)
                        );
                    }
                }

                myChart = new Chart(ctx,
                    config
                );

                rowsData.forEach(function (row, index) {
                    if (row.length !== 0) {
                        var colData = {};


                        colData.data = row.split(chartSettings.separator);
                        //add properties only if repeater element exists
                        if (customDatasets[index]) {
                            colData.borderColor = customDatasets[index].borderColor;
                            colData.borderWidth = customDatasets[index].borderWidth;
                            colData.backgroundColor = customDatasets[index].backgroundColor;
                            colData.hoverBackgroundColor = customDatasets[index].hoverBackgroundColor;
                            colData.label = customDatasets[index].label;
                            colData.fill = customDatasets[index].fill
                        }

                        data.datasets.push(colData);
                        myChart.update();

                    }
                });
            }
        });
    });
})(jQuery);