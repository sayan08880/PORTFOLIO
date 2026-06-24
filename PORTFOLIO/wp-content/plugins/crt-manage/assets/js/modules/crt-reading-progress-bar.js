(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-reading-progress-bar.default',function($scope) {

            if ( $scope.find('.crt-reading-progress-bar-container').length != 0 ) {
                var rpbContainer = $scope.find('.crt-reading-progress-bar-container');
                readingProgressBar($scope, rpbContainer);
            }

            function readingProgressBar($scope, rpbContainer) {

                var initialPaddingTop = $('body').css('paddingTop');
                var initialPaddingBottom = $('body').css('paddingBottom');

                if ( '0px' === rpbContainer.css('top') ) {
                    if ( 'colored' == rpbContainer.data('background-type') ) {
                        $('body').css('paddingTop', $scope.find('.crt-reading-progress-bar').css('height'));
                    }
                    if ( $('#wpadminbar').length ) {
                        rpbContainer.css('top', $('#wpadminbar').height());
                    }
                    $('body').css('paddingBottom', initialPaddingBottom);
                } else if ( '0px' === rpbContainer.css('bottom') && 'colored' == rpbContainer.data('background-type') ) {
                    $('body').css('paddingBottom', $scope.find('.crt-reading-progress-bar').css('height'));
                    $('body').css('paddingTop', initialPaddingTop);
                }

                readingProgressBarFill($scope);
                window.onscroll = function() {
                    readingProgressBarFill($scope);
                };

            }

            function readingProgressBarFill($scope) {
                if ( $scope.find('.crt-reading-progress-bar').length ) {
                    var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
                    var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
                    var scrolled = (winScroll / height) * 100;
                    $scope.find(".crt-reading-progress-bar").css('width', scrolled + "%");
                }
            }
        });
    });
})(jQuery);