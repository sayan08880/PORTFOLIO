'use strict';
(function($) {
    $(".crt-action-page-type").on("change",function(e){
        var page = $(this).val();
        var post_id = $(this).attr('data-id');
        var data = {
            'action': 'crt_manage_action_page_type',
            'page': page,
            'post_id': post_id,
        };
        jQuery.post(ajaxurl, data, function(response) {
            var result = jQuery.parseJSON(response);
            if(result.code == 1) {
                location.reload();
            }
        });
    });
})(jQuery);