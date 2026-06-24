(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-data-table.default',function($scope) {

            const ps = new PerfectScrollbar($scope.find('.crt-table-inner-container')[0], {
                // suppressScrollX: true
            });

            var beforeFilter = $scope.find("tbody .crt-table-row"),
                itemsPerPage = +$scope.find('.crt-table-inner-container').attr('data-rows-per-page'),
                paginationListItems = $scope.find('.crt-table-custom-pagination-list-item'),
                initialRows = $scope.find('.crt-table-inner-container tbody tr'),
                table = $scope.find('.crt-table-inner-container tbody'),
                pageIndex, value, paginationIndex;

            // Table Custom Pagination
            if ( 'yes' === $scope.find('.crt-table-inner-container').attr('data-custom-pagination') ) {

                var tableRows = initialRows.filter(function(index) {
                    return index < $scope.find('.crt-table-inner-container').attr('data-rows-per-page');
                });

                table.html(tableRows);

                adjustPaginationList();

                $scope.on('click', '.crt-table-custom-pagination-list-item', function() {
                    paginationListItems.removeClass('crt-active-pagination-item');
                    $(this).addClass('crt-active-pagination-item');
                    adjustPaginationList();
                    table.hide();
                    pageIndex = +$(this).text();
                    itemsPerPage = +$scope.find('.crt-table-inner-container').attr('data-rows-per-page');

                    table.html(initialRows.filter(function(index) {
                        index++;
                        return index > itemsPerPage * (pageIndex - 1) && index <= itemsPerPage * pageIndex;
                    }));

                    table.show();
                    beforeFilter = $scope.find("tbody .crt-table-row");
                    beforeFilter.find('.crt-table-tr-before-remove').each(function() {
                        $(this).removeClass('crt-table-tr-before-remove');
                    });

                    entryInfo();
                });

                $scope.find('.crt-table-prev-next').each(function() {
                    pageIndex = +$scope.find('.crt-active-pagination-item').text();

                    if ( $(this).hasClass('crt-table-custom-pagination-prev')) {

                        $(this).on('click', function() {

                            if ( 1 < pageIndex ) {
                                paginationListItems.removeClass('crt-active-pagination-item');
                                pageIndex--;

                                paginationListItems.each(function(index) {
                                    index++;
                                    if ( index === pageIndex) {
                                        $(this).addClass('crt-active-pagination-item');
                                        pageIndex = +$(this).text();
                                    }
                                });
                                adjustPaginationList();

                                table.html(initialRows.filter(function(index) {
                                    index++;
                                    return index > itemsPerPage * (pageIndex - 1) && index <= itemsPerPage * pageIndex;
                                }));

                                beforeFilter = $scope.find("tbody .crt-table-row");

                                if ( '' == value ) {
                                    table.html(beforeFilter);
                                }
                            }

                            entryInfo();
                        });

                    } else {

                        $(this).on('click', function() {

                            if (  paginationListItems.length > pageIndex ) {
                                paginationListItems.removeClass('crt-active-pagination-item');
                                pageIndex++;

                                paginationListItems.each(function(index) {
                                    index++;
                                    if ( index === pageIndex) {
                                        $(this).addClass('crt-active-pagination-item');
                                        pageIndex = +$(this).text();
                                    }
                                });
                                adjustPaginationList();

                                table.html(initialRows.filter(function(index) {
                                    index++;
                                    return index > itemsPerPage * (pageIndex - 1) && index <= itemsPerPage * pageIndex;
                                }));

                                beforeFilter = $scope.find("tbody .crt-table-row");

                                if ( '' == value ) {
                                    table.html(beforeFilter);
                                }
                            }

                            entryInfo();
                        });
                    }

                    beforeFilter.find('.crt-table-tr-before-remove').each(function() {
                        $(this).removeClass('crt-table-tr-before-remove');
                    });

                });

            }

            $scope.find('.crt-table-inner-container').removeClass('crt-hide-table-before-arrange');

            entryInfo();

            // Table Live Search
            beforeFilter = $scope.find("tbody .crt-table-row");
            $scope.find(".crt-table-live-search").keyup(function () {
                if ( this.value !== '' ) {
                    $scope.find('.crt-table-pagination-cont').addClass('crt-hide-pagination-on-search');
                } else {
                    $scope.find('.crt-table-pagination-cont').removeClass('crt-hide-pagination-on-search');
                }
                value = this.value.toLowerCase().trim();

                var afterFilter = [];

                initialRows.each(function (index) {
                    // if (!index) return; // TODO: restore if better
                    $(this).find("td").each(function () {
                        var id = $(this).text().toLowerCase().trim();
                        var not_found = (id.indexOf(value) == -1);
                        // $(this).closest('tr').toggle(!not_found);
                        // return not_found;
                        if ( !not_found ) {
                            afterFilter.push($(this).closest('tr'));
                        }
                    });
                });

                table.html(afterFilter);

                if ( '' == value ) {
                    table.html(beforeFilter);
                }

                entryInfo();
            });

            // Table Sorting
            if ( 'yes' === $scope.find('.crt-table-inner-container').attr('data-table-sorting') ) {
                $(window).click(function(e) {
                    if ( !$(e.target).hasClass('crt-table-th') && 0 === $(e.target).closest('.crt-table-th').length ) {
                        if ( !$(e.target).hasClass('crt-active-td-bg-color') && 0 === $(e.target).closest('.crt-active-td-bg-color').length ) {
                            $scope.find('td').each(function() {
                                if($(this).hasClass('crt-active-td-bg-color')) {
                                    $(this).removeClass('crt-active-td-bg-color');
                                }
                            });
                        }
                    }
                });

                $scope.find('th').click(function(){

                    var indexOfTr = $(this).index();

                    $scope.find('td').each(function() {
                        if($(this).index() === indexOfTr) {
                            $(this).addClass('crt-active-td-bg-color');
                        } else {
                            $(this).removeClass('crt-active-td-bg-color');
                        }
                    });

                    $scope.find('th').each(function() {
                        $(this).find('.crt-sorting-icon').html('<i class="fas fa-sort" aria-hidden="true"></i>');
                    });

                    var table = $(this).parents('table').eq(0);
                    var rows = table.find('tr:gt(0)').toArray().sort(comparer($(this).index()))

                    this.asc = !this.asc
                    if ($scope.hasClass('crt-data-table-type-custom') ? !this.asc : this.asc) {
                        if ($scope.hasClass('crt-data-table-type-custom')) {
                            $(this).find('.crt-sorting-icon').html('<i class="fas fa-sort-down" aria-hidden="true"></i>');
                        } else {
                            $(this).find('.crt-sorting-icon').html('<i class="fas fa-sort-up" aria-hidden="true"></i>');
                        }
                        rows = rows.reverse()
                    }

                    if($scope.hasClass('crt-data-table-type-custom') ? this.asc : !this.asc) {

                        if ($scope.hasClass('crt-data-table-type-custom')) {
                            $(this).find('.crt-sorting-icon').html('<i class="fas fa-sort-up" aria-hidden="true"></i>');
                        } else {

                            $(this).find('.crt-sorting-icon').html('<i class="fas fa-sort-down" aria-hidden="true"></i>');
                        }
                    }

                    for (var i = 0; i < rows.length; i++) {
                        table.append(rows[i])
                    }

                    beforeFilter.find('.crt-table-tr-before-remove').each(function() {
                        $(this).closest('.crt-table-row').next('.crt-table-appended-tr').remove();
                        $(this).removeClass('crt-table-tr-before-remove');
                    });
                });
            }

            if ( $scope.find('.crt-table-inner-container').attr('data-row-pagination') === 'yes' ) {
                $scope.find('.crt-table-head-row').prepend('<th class="crt-table-th-pag" style="vertical-align: middle;">' + '#' + '</th>')
                initialRows.each(function(index) {
                    $(this).prepend('<td class="crt-table-td-pag" style="vertical-align: middle;"><span style="vertical-align: middle;">'+ (index + 1) +'</span></td>')
                })
            }

            if ( $scope.find('.crt-table-export-button-cont').length ) {
                var exportBtn = $scope.find('.crt-table-export-button-cont .crt-button');;
                exportBtn.each(function() {
                    if ( $(this).hasClass('crt-xls')) {
                        $(this).on('click', function() {
                            let table = $scope.find('table');
                            TableToExcel.convert(table[0], { // html code may contain multiple tables so here we are refering to 1st table tag
                                name: `export.xlsx`, // fileName you could use any name
                                sheet: {
                                    name: 'Sheet 1' // sheetName
                                }
                            });
                        });
                    } else if ( $(this).hasClass('crt-csv')) {
                        $(this).on('click', function() {
                            htmlToCSV('why-this-arg?', "placeholder.csv", $scope.find('.crt-data-table'));
                        });
                    }
                });
            }

            // if('yes' === $scope.find('.crt-table-inner-container').attr('data-enable-tr-link')) {
            // 	$scope.find('tbody tr:eq('+ $scope.find('.crt-table-inner-container').attr('data-tr-index') +')').click(function() {
            // 		window.location.href = 'https://stackoverflow.com/questions/503093/how-do-i-redirect-to-another-webpage';
            // 		// window.open('https://stackoverflow.com/questions/503093/how-do-i-redirect-to-another-webpage', '_blank');
            // 	});
            // }

            function entryInfo() {

                if ( 'yes' !== $scope.find('.crt-table-inner-container').attr('data-entry-info') ) {
                    return;
                }

                var entryPage = +$scope.find('.crt-active-pagination-item').text(),
                    lastEntry = itemsPerPage * entryPage - (itemsPerPage - $scope.find('tbody tr').length),
                    firstEntry = lastEntry - $scope.find('tbody tr').length + 1;

                $scope.find('.crt-entry-info').html('Showing ' + firstEntry + ' to ' + lastEntry + ' of ' + initialRows.length + ' Entries.');
            }

            function adjustPaginationList() {

                paginationIndex = $scope.find('.crt-active-pagination-item').index();
                paginationListItems.each(function(index) {
                    if (index == 0 || index == paginationListItems.length - 1 || index <= paginationIndex && index >= paginationIndex - 2) {
                        $(this).css('display', 'flex');
                    } else {
                        $(this).css('display', 'none');
                    }
                });
            }

            function comparer(index) {
                return function(a, b) {
                    var valA = getCellValue(a, index), valB = getCellValue(b, index)
                    return $.isNumeric(valA) && $.isNumeric(valB) ? valA - valB : valA.toString().localeCompare(valB)
                }
            }

            function getCellValue (row, index) {
                return $(row).children('td').eq(index).text()
            }

            function htmlToCSV(html, filename, view) {
                var data = [];
                var rows = view.find(".crt-table-row");

                for (var i = 0; i < rows.length; i++) {
                    var row = [], cols = rows[i].querySelectorAll(".crt-table-text");

                    for (var j = 0; j < cols.length; j++) {
                        row.push(cols[j].innerText);
                    }

                    data.push(row.join(","));
                }

                downloadCSVFile(data.join("\n"), filename);
            }

            function downloadCSVFile(csv, filename) {
                var csv_file, download_link;

                csv_file = new Blob([csv], {type: "text/csv"});

                download_link = document.createElement("a");

                download_link.download = filename;

                download_link.href = window.URL.createObjectURL(csv_file);

                download_link.style.display = "none";

                document.body.appendChild(download_link);

                download_link.click();
            } // Data Table CSV export

        });
    });
})(jQuery);