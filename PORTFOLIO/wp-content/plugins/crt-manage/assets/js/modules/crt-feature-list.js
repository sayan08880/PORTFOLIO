(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-feature-list.default',function($scope) {

            const target = document.querySelector('.e-n-tabs-content>div');

            if (target) {
                const observer = new MutationObserver(function(mutationsList) {
                    for (let mutation of mutationsList) {
                        if (mutation.type === 'attributes' || mutation.type === 'childList') {
                            featureList();
                        }
                    }
                });

                observer.observe(target, {
                    attributes: true,
                    childList: true,
                    subtree: true
                });
            }

            featureList();

            function featureList() {
                $scope.find('.crt-feature-list-item:not(:last-of-type)').find('.crt-feature-list-icon-wrap').each(function(index) {
                    var offsetTop = $scope.find('.crt-feature-list-item').eq(index + 1).find('.crt-feature-list-icon-wrap').offset().top;

                    $(this).find('.crt-feature-list-line').height(offsetTop - $(this).offset().top + 'px');
                });

                $(window).resize(function() {
                    $scope.find('.crt-feature-list-item:not(:last-of-type)').find('.crt-feature-list-icon-wrap').each(function(index) {
                        var offsetTop = $scope.find('.crt-feature-list-item').eq(index + 1).find('.crt-feature-list-icon-wrap').offset().top;

                        $(this).find('.crt-feature-list-line').height(offsetTop - $(this).offset().top + 'px');
                    });
                });
            }
        });
    });
})(jQuery);