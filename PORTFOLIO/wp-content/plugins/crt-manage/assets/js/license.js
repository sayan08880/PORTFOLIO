'use strict';
(function($) {
    $("body").on("click",".crt-btn-license",function(e){
        e.preventDefault();
        var _this = $(this);
        var _input = jQuery('.crt-field-license').val();
        if(_input == '') {
            alert('Please submit key');
            return;
        }
        _this.text('Checking ...');
        var data = {
            'action': 'crt_manage_theme_purchase_code',
            'code': _input,
        };
        jQuery.post(ajaxurl, data, function(response) {
            var result = jQuery.parseJSON(response);
            _this.text('Active');
            if(result.code == 0) {
                alert(result.messenger);
            } else {
                alert('Active successfully, thank you for purchasing the premium version.');
                location.reload();
            }
        });
    });


})(jQuery);