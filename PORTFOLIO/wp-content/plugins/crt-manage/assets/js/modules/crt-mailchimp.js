(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-mailchimp.default',function($scope) {
            var mailchimpForm = $scope.find( 'form' );
            mailchimpForm.on( 'submit', function(e) {
                e.preventDefault();
                var buttonText = $(this).find('button').text();
                // Change Text
                $(this).find('button').text( $(this).find('button').data('loading') );
                $.ajax({
                    url: CRTConfig.ajaxurl,
                    type: 'POST',
                    data: {
                        action: 'mailchimp_subscribe',
                        fields: $(this).serialize(),
                        listId: mailchimpForm.data( 'list-id' )
                    },
                    success: function(data) {
                        if ( 'yes' == mailchimpForm.data('clear-fields') ) {
                            mailchimpForm.find('input').each(function() {
                                $(this).val('');
                            });
                        }
                        mailchimpForm.find('button').text( buttonText );
                        if ( 'subscribed' === data.status ) {
                            $scope.find( '.crt-mailchimp-success-message' ).show();
                        } else {
                            $scope.find( '.crt-mailchimp-error-message' ).show();
                        }
                        $scope.find( '.crt-mailchimp-message' ).fadeIn();
                    }
                });
            });
        });
    });
})(jQuery);