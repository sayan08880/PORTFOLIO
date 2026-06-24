(function($) {
    "use strict";
    $( document ).ready( function () {
		$('.sidebar-fixed').theiaStickySidebar({
			additionalMarginTop: 20
		});

        $('.single-share-js').theiaStickySidebar({
            additionalMarginTop: 100
        });
    });
})(jQuery);