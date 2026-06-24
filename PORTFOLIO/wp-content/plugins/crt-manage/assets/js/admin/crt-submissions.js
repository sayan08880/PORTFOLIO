jQuery(document).ready(function($) {
    $('.page-title-action').remove();
    
    $('body').on('click', '.column-read_status', function() {
        const post_id = $(this).parent().attr('id').replace('post-', '');
        const read_status = $(this).text() === 'Read' ? '0' : '1';
        const nonce = CrtSubmissions.nonce; // Replace 'your_custom_vars' with the variable name you use in the next step (step 8) to localize the script

        $.ajax({
            url: CrtSubmissions.ajaxurl,
            type: 'POST',
            data: {
                action: 'crt_submissions_update_read_status',
                post_id: post_id,
                read_status: read_status,
                nonce: nonce,
            },
            success: function(response) {
                if (response.success) {
                    // if (read_status === '1') {
                    //     $('#post-' + post_id + ' .column-read_status').text('Read');
                    // } else {
                    //     $('#post-' + post_id + ' .column-read_status').text('Unread');
                    // }
                } else {
                    alert('Error updating read status');
                }
            },
        });
    });
    
    $('<input>').attr({
        type: 'hidden',
        id: 'crt_submission_changes',
        name: 'crt_submission_changes',
    }).appendTo('#post');

    let changes = {};

    $('.crt-submissions-wrap input, .crt-submissions-wrap textarea').each(function() {
        if ( $(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio' ) {
            $(this).attr('disabled', true);
        } else {
            $(this).attr('readonly', true);
        }
    });        
    
    $('input, textarea').on('change', function () {
        let key = $(this).attr('id');
        let value;

        // Check if the input is a checkbox
        if ( $(this).attr('type') === 'checkbox' ||  $(this).attr('type') == 'radio' ) {
            value = [];
            key = $(this).closest('.crt-submissions-wrap').find('label:first-of-type').attr('for');
            // console.log(key);
            value[0] = $(this).attr('type');
            value[1] = [];
            value[2] = $(this).closest('.crt-submissions-wrap').find('label:first-of-type').text();
            $(this).closest('.crt-submissions-wrap').find('input').each(function() {
                var inputData = [];
                inputData[0] = $(this).val();
                inputData[1] = $(this).is(':checked');
                inputData[2] = $(this).attr('name');
                inputData[3] = $(this).attr('id');
                value[1].push(inputData);
            });
            changes[key] = value; // Store the changes in the changes object
            // console.log(value);
        } else {
            value = [];
            value[0] = $(this).attr('type');
            value[1] = $(this).val();
            value[2] = $(this).prev('label').text();
            changes[key] = value; // Store the changes in the changes object
        }
        $('#crt_submission_changes').val(JSON.stringify(changes));
    });

    $('.crt-edit-submissions').on('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        $('#crt_submission_changes').val('');
        let thisButton = $(this);
        $('input, textarea').each(function() {
            if ( $(this).attr('readonly') || $(this).attr('disabled') ) {
                if ( $(this).attr('readonly') ) {
                    $(this).attr('readonly', false);
                }
                if ( $(this).attr('disabled') ) {
                    $(this).attr('disabled', false);
                }
                thisButton.text('Cancel');
            } else {
                if ( $(this).attr('type') == 'checkbox' || $(this).attr('type') == 'radio' ) {
                    $(this).attr('disabled', true);
                } else {
                    $(this).attr('readonly', true);
                }
                thisButton.text('Edit');
            }
        });
    })

    $('.crt-submission-unread').closest('tr').addClass('crt-submission-unread-column');

    // Check if the post type is crt_submissions
    if ($('#submitdiv').length > 0) {

        // Access the variables
        var form_name = CrtSubmissions.form_name;
        var form_id = CrtSubmissions.form_id;
        var form_page = CrtSubmissions.form_page;
        var form_page_id = CrtSubmissions.form_page_id;
        var agent_ip = CrtSubmissions.agent_ip
        var user_agent = CrtSubmissions.form_agent

        // Use the variables as needed
        // console.log(form_name, form_id, form_page, form_page_id);
        $('#minor-publishing').remove();
        $('#submitdiv .postbox-header').find('h2').text('Extra Info');
        $('<div class="misc-pub-section">Form: <a href="'+ CrtSubmissions.form_page_editor +'" target="_blank">'+ form_name + ' (' + form_id + ')' +'</a></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Page: <a href="'+ CrtSubmissions.form_page_url +'" target="_blank">'+ form_page +'</a></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Created at: <span class="crt-submissions-meta">'+ CrtSubmissions.post_created +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">Updated at: <span class="crt-submissions-meta">'+ CrtSubmissions.post_updated +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">User IP: <span class="crt-submissions-meta">'+ agent_ip +'</sp></div>').insertBefore('#major-publishing-actions');
        $('<div class="misc-pub-section">User Agent: <span class="crt-submissions-meta">'+ user_agent +'</sp></div>').insertBefore('#major-publishing-actions');

        $('#postbox-container-1').css('opacity', 1);
        $('#postbox-container-2').css('opacity', 1);
    }
});