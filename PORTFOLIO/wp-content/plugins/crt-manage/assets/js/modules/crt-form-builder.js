(function($) {
    "use strict";
    $(window).on( 'elementor/frontend/init', function() {
        elementorFrontend.hooks.addAction('frontend/element_ready/crt-form-builder.default',function($scope) {


            var formContent = {};

            var fileUrl = {};

            if ( $('body').find('.crt-form-field-type-recaptcha-v3').length > 0 ) {
                var script = document.createElement('script');
                script.src = 'https://www.google.com/recaptcha/api.js?render='+ $scope.find('#g-recaptcha-response').data('site-key') +'';
                document.body.appendChild(script);
            }

            var currentTab = 0; // Current tab is set to be the first tab (0)
            if ( 0 < $scope.find('.crt-step-tab').length ) {
                showTab(currentTab); // Display the current tab

                $scope.find('.crt-step-prev').each(function() {
                    $(this).on('click', function() {
                        nextPrev(-1);
                    });
                });

                $scope.find(".crt-step-next").each(function() {
                    $(this).on('click', function() {
                        nextPrev(1);
                    });
                });
            }

            var actions = $scope.find('.crt-form-field-type-submit').data('actions');

            $scope.find('input[type="file"]').on('change', function(e) {
                var files = this.files;
                var thisInput = $(this);
                var eventType = e.type;
                handleFileValidityAndUpload(thisInput, files, eventType);
            });

            $scope.find('input, select, textarea').each(function() {
                $(this).on('change', function() {
                    var $this = $(this);
                    if ('checkbox' == $this.attr('type')) {
                        var $option = $this.closest('.crt-form-field-option');
                        if ($option.hasClass('crt-checked')) {
                            $option.removeClass('crt-checked');
                        } else {
                            $option.addClass('crt-checked');
                        }
                    } else if ('radio' == $this.attr('type')) {
                        // Find all radio buttons in the same group
                        var name = $this.attr('name');
                        var $group = $('input[type="radio"][name="' + name + '"]');

                        // Remove 'crt-checked' from all options in the group
                        $group.closest('.crt-form-field-option').removeClass('crt-checked');

                        // Add 'crt-checked' to the selected option
                        if ($this.is(':checked')) {
                            $this.closest('.crt-form-field-option').addClass('crt-checked');
                        }
                    }
                });

                $(this).on('input change keyup', function(e) {
                    if ( $(this).closest('.crt-select-wrap').length > 0 ) {
                        $(this).closest('.crt-select-wrap').removeClass('crt-form-error-wrap');
                    }
                    $(this).removeClass('crt-form-error');
                    $(this).closest('.crt-field-group').find('.crt-submit-error').remove();
                });
            });

            $scope.find('.crt-button').on('click', function(e) {
                e.preventDefault();

                var eventType = e.type;

                formContent = {};

                // Create an array to store the promises of the file uploads
                let fileUploadPromises = [];

                if ( 0 < $scope.find('input[type="file"]').length ) {
                    $scope.find('input[type="file"]').each(function() {
                        var files = this.files;
                        var thisInput = $(this);

                        fileUploadPromises.push(handleFileValidityAndUpload(thisInput, files, eventType));
                    });

                    // Wait for all file uploads to complete
                    Promise.all(fileUploadPromises)
                        .then(() => {
                            createFormContent();

                            // Check if the form is valid and submit the form
                            if (validateForm()) {
                                $(this).closest('form').trigger('submit');
                            }
                        })
                        .catch((error) => {
                            // Handle errors
                            console.error(error);
                        });
                } else {
                    createFormContent();

                    if ( validateForm() ) {
                        $(this).closest('form').trigger('submit');
                    }
                }
            });


            $scope.find('form').on('submit', function(e) {

                e.preventDefault();

                let responsesArray = [];

                $scope.find('.crt-button>span').addClass('crt-loader-hidden');
                $scope.find('.crt-button').find('.crt-double-bounce').removeClass('crt-loader-hidden');

                if ( $scope.find('.crt-submit-error') ) {
                    $scope.find('.crt-submit-error').remove();
                }

                if ( $scope.find('.crt-submit-success') ) {
                    $scope.find('.crt-submit-success').remove();
                }

                function processRecaptcha(callback) {
                    if ($scope.find('#g-recaptcha-response').length > 0) {
                        grecaptcha.ready(function() {
                            grecaptcha.execute(CRTConfig.site_key, {action: 'submit'}).then(function(token) {
                                // Set the token value to the hidden input field
                                $scope.find('#g-recaptcha-response').val(token);

                                // Perform the AJAX call after the token is set
                                $.ajax({
                                    type: 'POST',
                                    url: CRTConfig.ajaxurl,
                                    data: {
                                        action: 'crt_verify_recaptcha',
                                        'g-recaptcha-response': token
                                    },
                                    success: function(response) {
                                        if( !response.success ) {
                                            setTimeout(function() {
                                                $scope.find('.crt-button').find('.crt-double-bounce').addClass('crt-loader-hidden');
                                                $scope.find('.crt-button>span').removeClass('crt-loader-hidden');
                                                $scope.find('form').append('<p class="crt-submit-notice crt-submit-error">'+ CRTConfig.recaptcha_error +'</p>');
                                            }, 500);
                                            callback(false); // Call the callback with failure
                                        } else {
                                            callback(true); // Call the callback with success
                                        }
                                    },
                                    error: function(error) {
                                        console.log(error);
                                        setTimeout(function() {
                                            $scope.find('.crt-button').find('.crt-double-bounce').addClass('crt-loader-hidden');
                                            $scope.find('.crt-button>span').removeClass('crt-loader-hidden');
                                            $scope.find('form').append('<p class="crt-submit-notice crt-submit-error">'+ CRTConfig.recaptcha_error +'</p>');
                                        }, 500);
                                        callback(false); // Call the callback with failure
                                    }
                                });
                            });
                        });
                    } else {
                        callback(true); // Call the callback if there's no reCAPTCHA
                    }
                }

                // Call the processRecaptcha function and pass a callback that submits the form on success
                processRecaptcha(function(isRecaptchaSuccessful) {
                    if (isRecaptchaSuccessful) {

                        // Perform the form submission here
                        var actionsObject = {
                            emailPromise: sendEmail,
                            submissionsPromise: createPost,
                            mailchimpPromise: subscribeMailchimp,
                            webhookPromise: sendWebhook
                        }

                        // Wait for all Promises to resolve
                        Promise.all(
                            actions.map((action) => {
                                try {
                                    if (actionsObject[action + 'Promise']) {
                                        return actionsObject[action + 'Promise']();
                                    }
                                } catch (error) {
                                    console.error(error);
                                    return Promise.reject(error);
                                }
                            })
                        )
                            .then((responses) => {
                                // Find the post ID from the createPost() response
                                const createPostResponse = responses.find((response) => response && response.data.action === 'crt_form_builder_submissions');

                                const postId = createPostResponse ? createPostResponse.data.post_id : null;

                                // Update post meta for each action
                                var updateMetaPromises = actions.map((action) => {
                                    if ( action !== 'redirect' ) {
                                        action = 'crt_form_builder_' + action;

                                        // Find the response object for the current action
                                        const response = responses.find((response) => response && response.data.action === action);

                                        // Store the message from the response object in a variable
                                        const message = response ? response.data.message : '';

                                        if (response && response.data.status === 'success') {
                                            responsesArray.push('success');

                                            if (postId) {
                                                return updateFormActionMeta(postId, action, 'success', message);
                                            }
                                        } else {
                                            responsesArray.push('error');

                                            if (postId) {
                                                return updateFormActionMeta(postId, action, 'error', message);
                                            }
                                        }
                                    }
                                });

                                return Promise.all(updateMetaPromises).then(() => {
                                    if (responsesArray.includes('error')) {
                                        var sanitizedErrorMessage = $('<div>').text($scope.data('settings').error_message).html();
                                        $scope.find('form').append('<p class="crt-submit-notice crt-submit-error">' + sanitizedErrorMessage + '</p>');
                                    } else {
                                        $scope.find('form').append(
                                            $('<p class="crt-submit-notice crt-submit-success"></p>').text($scope.data('settings').success_message)
                                        );
                                        // $scope.find('form').append('<p class="crt-submit-success">'+ $scope.data('settings').success_message +'</p>');
                                        $scope.find('button').attr('disabled', true);
                                        $scope.find('button').css('opacity', 0.6);
                                    }
                                });
                                // }
                            })
                            .catch((error) => {
                                // Handle errors
                                console.error(error);
                            })
                            .then(() => {
                                // All AJAX actions have completed
                                setTimeout(function() {
                                    // Switch submit button from loader back to submit
                                    $scope.find('.crt-button').find('.crt-double-bounce').addClass('crt-loader-hidden');
                                    $scope.find('.crt-button>span').removeClass('crt-loader-hidden');
                                    setTimeout(function() {
                                        if (actions.includes('redirect') && responsesArray.includes('success')) {
                                            // window.location.replace($scope.find('.crt-form-field-type-submit').data('redirect-url'));
                                            $(location).prop('href', $scope.find('.crt-form-field-type-submit').data('redirect-url'))
                                        }
                                    }, 500);
                                }, 500);
                            })
                            .catch((error) => {
                                // Handle errors
                                console.error(error);
                            });
                    } else {
                        // Handle the case when reCAPTCHA fails
                        return false;
                    }
                });

                function updateFormActionMeta(postId, actionName, status, message) {
                    return $.ajax({
                        type: 'POST',
                        url: CRTConfig.ajaxurl,
                        data: {
                            action: 'crt_update_form_action_meta',
                            nonce: CRTConfig.nonce,
                            // custom_token: CRTConfig.token,
                            post_id: postId,
                            action_name: actionName,
                            status: status,
                            message: message
                        },
                    });
                }

                function deepCopy(obj) {
                    return JSON.parse(JSON.stringify(obj));
                }

                function sendEmail() {
                    var data = deepCopy(formContent);

                    for (let key in data) {
                        if (data[key][0] == 'radio' || data[key][0] == 'checkbox' ) {
                            if (Array.isArray(data[key][1])) {
                                let trueValues = data[key][1].filter(innerArray => innerArray[1] === true).map(innerArray => innerArray[0]);
                                let trueValuesString = trueValues.join(', ');
                                data[key][1] = trueValuesString;
                            }
                        }
                    }

                    return $.ajax({
                        type: 'POST',
                        url: CRTConfig.ajaxurl,
                        data: {
                            action: 'crt_form_builder_email',
                            nonce: CRTConfig.nonce,
                            form_content: data,
                            crt_form_id: $scope.find('input[name="form_id"]').val(),
                        },
                        success: function(response) {
                            if ( !response.success ) {
                                // if (CRTConfig.is_admin) {
                                // 	$scope.find('form').append('<p class="crt-submit-error">'+ response.data.message +'</p>');
                                // }
                            } else {
                                // if (CRTConfig.is_admin) {
                                // 	$scope.find('form').append('<p class="crt-submit-success">'+ response.data.message +'</p>');
                                // }
                            }
                        },
                        error: function(error) {
                            // if (CRTConfig.is_admin) {
                            // 	$scope.find('form').append('<p class="crt-submit-error">'+ error.data.message +'</p>');
                            // }
                        }
                    });
                }

                function sendWebhook() {
                    var data = deepCopy(formContent);

                    for (let key in data) {
                        if (data[key][0] == 'radio' || data[key][0] == 'checkbox' ) {
                            if (Array.isArray(data[key][1])) {
                                let trueValues = data[key][1].filter(innerArray => innerArray[1] === true).map(innerArray => innerArray[0]);
                                let trueValuesString = trueValues.join(', ');
                                data[key][1] = trueValuesString;
                            }
                        }
                    }

                    return $.ajax({
                        type: 'POST',
                        url: CRTConfig.ajaxurl,
                        data: {
                            action: 'crt_form_builder_webhook',
                            nonce: CRTConfig.nonce,
                            form_content: data,
                            crt_form_id: $scope.find('input[name="form_id"]').val(),
                            form_name: $scope.find('form').attr('name')
                        },
                        success: function(response) {
                            if ( !response.success ) {
                                // if (CRTConfig.is_admin) {
                                // 	$scope.find('form').append('<p class="crt-submit-error">'+ response.data.message +'</p>');
                                // }
                            } else {
                                // if (CRTConfig.is_admin) {
                                // 	$scope.find('form').append('<p class="crt-submit-success">'+ response.data.message +'</p>');
                                // }
                            }
                        },
                        error: function(error) {
                            console.log(error);
                            // if (CRTConfig.is_admin) {
                            // 	$scope.find('form').append('<p class="crt-submit-error">'+ error.data.message +'</p>');
                            // }
                        }
                    });
                }

                function createPost() {
                    var data = {
                        action: 'crt_form_builder_submissions',
                        nonce: CRTConfig.nonce,
                        form_content: formContent,
                        status: 'publish',
                        form_name: $scope.find('form').attr('name'),
                        form_id: $scope.find('input[name="form_id"]').val(),
                        form_page: $scope.find('form').attr('page'),
                        form_page_id: $scope.find('form').attr('page_id')
                    };

                    return $.ajax({
                        type: 'POST',
                        url: CRTConfig.ajaxurl,
                        data: data,
                        success: function(response) {
                            // if (CRTConfig.is_admin) {
                            // 	$scope.find('form').append('<p class="crt-submit-success">'+ response.data.message +'</p>');
                            // }
                        },
                        error: function(error) {
                            console.log(error)
                            // if (CRTConfig.is_admin) {
                            // 	$scope.find('form').append('<p class="crt-submit-error">'+ response.data.message +'</p>');
                            // }
                        }
                    });
                }

                function subscribeMailchimp() {

                    const submitButton = $scope.find('.crt-form-field-type-submit');
                    const mailchimpFields = JSON.parse(submitButton.attr('data-mailchimp-fields'));

                    let formData = {};

                    Object.keys(mailchimpFields).forEach(function (fieldId) {
                        if ( fieldId == 'group_id' ) {

                            var fieldValue = Array.isArray(mailchimpFields[fieldId]) ? mailchimpFields[fieldId].join(',') : mailchimpFields[fieldId];
                        } else {
                            var fieldValue = $scope.find('#form-field-' + mailchimpFields[fieldId]).val();
                        }
                        if ( fieldValue ) {
                            if ( fieldId == 'birthday_field') {
                                formData[fieldId] = convertToMailchimpBirthdayFormat(fieldValue);
                            } else {
                                formData[fieldId] = fieldValue;
                            }
                        }
                    });

                    return $.ajax({
                        url: CRTConfig.ajaxurl,
                        method: 'POST',
                        data: {
                            action: 'crt_form_builder_mailchimp',
                            nonce: CRTConfig.nonce,
                            form_data: formData,
                            listId: submitButton.data( 'list-id' )
                            // security: mailchimpSubscription.security
                        },
                        beforeSend: function () {
                            submitButton.prop('disabled', true);
                        },
                        success: function (response) {
                            if (!response.success) {
                                // if (CRTConfig.is_admin) {
                                // 	$scope.find('form').append('<p class="crt-submit-error">'+ response.data.message +'</p>');
                                // }
                            } else {
                                // if (CRTConfig.is_admin) {
                                // 	$scope.find('form').append('<p class="crt-submit-success">'+ response.data.message +'</p>');
                                // }
                            }
                            // Handle success response, e.g., show a success message.
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            console.log(errorThrown);
                            // if (CRTConfig.is_admin) {
                            // 	$scope.find('form').append('<p class="crt-submit-error">'+ errorThrown.message +'</p>');
                            // }
                        },
                        complete: function () {
                            submitButton.prop('disabled', false);
                        }
                    });
                }
            });

            function createFormContent() {
                $scope.find('.crt-form-field, .crt-form-field-type-radio, .crt-form-field-type-checkbox, .crt-step-input').each(function() {

                    var label = '';
                    if ( $(this).prev('label') ) {
                        if ( $(this).prev('label').data('alt-label') ) {
                            label = $(this).prev('label').data('alt-label').trim();
                        } else {
                            label = $(this).prev('label').text().trim();
                        }
                    } else {
                        label = '';
                    }

                    if ( 'textarea' !== $(this).prop('tagName').toLowerCase() ) {
                        if ( $(this).hasClass('crt-select-wrap') ) {
                            var selectValue = $(this).find('select').val();
                            if ( Array.isArray($(this).find('select').val()) ) {
                                selectValue = $(this).find('select').val().join(', ');
                            } else {
                                selectValue = $(this).find('select').val();
                            }
                            formContent[$(this).find('select').attr('id').replace('-', '_')] = ['select', selectValue, label];
                        } else if ( $(this).hasClass('crt-form-field-type-radio' ) || $(this).hasClass('crt-form-field-type-checkbox') ) {
                            var valuesArray = [];
                            var checkedField = $(this).find('input');
                            var type;
                            checkedField.each(function() {
                                valuesArray.push([$(this).val(), $(this).is(':checked'), $(this).attr('name'), $(this).attr('id')]);
                            });

                            if ( $(this).hasClass('crt-form-field-type-radio') ) {
                                type = 'radio'
                            } else {
                                type = 'checkbox';
                            }

                            var inputLabel = $(this).find('.crt-form-field-label').text().trim();

                            if ( $(this).find('.crt-form-field-label').data('alt-label') ) {
                                inputLabel = $(this).find('.crt-form-field-label').data('alt-label').trim();
                            }

                            if (checkedField.length > 0) {
                                formContent[$(this).find('.crt-form-field-option').data('key').replace('-', '_')] = [type, valuesArray, inputLabel];
                            }
                        } else if ( $(this).hasClass('crt-step-input') ) {
                            formContent[$(this).attr('id').replace('-', '_')] = [$(this).attr('type'), '', $(this).val(), label];
                        } else {
                            if ( $(this).attr('type') == 'file' ) {
                                formContent[$(this).attr('id').replace('-', '_')] = [$(this).attr('type'), fileUrl[$(this).attr('id')], label];
                            } else {
                                formContent[$(this).attr('id').replace('-', '_')] = [$(this).attr('type'), $(this).val(), label];
                            }
                        }
                    } else {
                        formContent[$(this).attr('id').replace('-', '_')] = [$(this).prop('tagName').toLowerCase(), $(this).val(), label];
                    }

                });
            }

            function handleFileValidityAndUpload(thisInput, files, eventType) {
                var thisId = thisInput.attr('id');

                if (0 < thisInput.closest('.crt-field-group').find('.crt-submit-error').length) {
                    thisInput.closest('.crt-field-group').find('.crt-submit-error').remove();
                }

                // Get the data-maxfs value from the input.
                var maxFileSize = thisInput.data('maxfs') ? thisInput.data('maxfs') : 0;
                var allowedFileTypes = thisInput.data('allft') ? thisInput.data('allft') : 0;

                // Create an array to store the upload promises
                let uploadPromises = [];

                for (let i = 0; i < files.length; i++) {
                    var fileInput = files[i];

                    // Create a new FormData object.
                    var formDataForFile = new FormData();
                    formDataForFile.append('action', 'crt_addons_upload_file');
                    formDataForFile.append('uploaded_file', fileInput);
                    formDataForFile.append('max_file_size', maxFileSize);
                    formDataForFile.append('allowed_file_types', allowedFileTypes);
                    formDataForFile.append('triggering_event', eventType);
                    formDataForFile.append('crt_addons_nonce', CRTConfig.nonce);
                    formDataForFile.append('form_field_id', thisId);

                    if ('click' == eventType) {
                        if (!fileUrl[thisId]) {
                            fileUrl[thisId] = [];
                        }
                    }

                    // Wrap the AJAX call in a Promise and push it to the uploadPromises array
                    uploadPromises.push(
                        new Promise((resolve, reject) => {
                            $.ajax({
                                url: CRTConfig.ajaxurl,
                                type: 'POST',
                                data: formDataForFile,
                                processData: false,
                                contentType: false,
                                success: function(response) {
                                    if (response.success) {
                                        // Do something with the uploaded file's URL (e.g., store it in a hidden input)
                                        if (eventType == 'click') {
                                            fileUrl[thisId][i] = response.data.url;
                                        }
                                        resolve(response);
                                    } else {
                                        console.error('Error:', response);
                                        if (response.data ) {
                                            if ( 'filesize' === response.data.cause ) {
                                                let maxFileNotice = thisInput.data('maxfs-notice') ? thisInput.data('maxfs-notice') : response.data.message;
                                                thisInput.closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">' + maxFileNotice + '</p>');
                                            }

                                            if ( 'filetype' == response.data.cause ) {
                                                thisInput.closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">' + response.data.message + '</p>');
                                            }
                                        }

                                        reject(response);
                                    }
                                },
                                error: function(error) {
                                    if ( 'filesize' === error.cause ) {
                                        let maxFileNotice = thisInput.data('maxfs-notice') ? thisInput.data('maxfs-notice') : error.message;
                                        thisInput.closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">' + maxFileNotice + '</p>');
                                    }

                                    if ( 'filetype' == error.cause ) {
                                        thisInput.closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">' + error.message + '</p>');
                                    }
                                    console.log(error);
                                    reject(error);
                                },
                            });
                        }),
                    );
                }

                // Return a Promise that resolves when all uploadPromises are resolved
                return Promise.all(uploadPromises);
            }

            function convertToMailchimpBirthdayFormat(dateString) {
                const date = new Date(dateString);
                const month = (date.getMonth() + 1).toString().padStart(2, '0');
                const day = date.getDate().toString().padStart(2, '0');
                return `${month}/${day}`;
            }

            function showTab(n) {
                // This function will display the specified tab of the form...
                var $stepTab = $scope.find(".crt-step-tab");
                $stepTab.eq(n).removeClass('crt-step-tab-hidden');
                //... and fix the Previous/Next buttons:
                if (n === 0) {
                    $scope.find(".crt-step-prev").hide();
                } else {
                    $scope.find(".crt-step-prev").show();
                }
                //... and run a function that will display the correct step indicator:
                fixStepIndicator(n);
            }

            function nextPrev(n) {
                // This function will figure out which tab to display
                var $stepTab = $scope.find(".crt-step-tab");

                // Exit the function if any field in the current tab is invalid:
                if (n === 1 && !validateForm()) {
                    return false;
                }
                // Hide the current tab:
                $stepTab.eq(currentTab).addClass('crt-step-tab-hidden');
                // Increase or decrease the current tab by 1:
                currentTab = currentTab + n;
                // if you have reached the end of the form...
                if (currentTab >= $stepTab.length) {
                    // ... the form gets submitted:
                    $scope.find("form").submit();
                    return false;
                }
                // Otherwise, display the correct tab:
                showTab(currentTab);
            }

            function validateForm() {
                var valid = true;
                var $stepTab = $scope.find(".crt-step-tab");
                if ( !($stepTab.length > 0) ) {
                    $stepTab = $scope.find('.crt-form-fields-wrap');
                    currentTab = 0;
                }
                var $types = ['text', 'email', 'password', 'file', 'url', 'tel', 'number', 'date', 'datetime-local', 'time', 'week', 'month', 'color']; // radio checkbox ?

                $stepTab.eq(currentTab).find('input, select, textarea').each(function() {
                    const type = $(this).attr('type');

                    var requiredField = $(this).closest('.crt-field-group').find('.crt-form-field').attr('required') === 'required' || $(this).closest('.crt-field-group').find('.crt-form-field-textual').attr('required') === 'required';

                    //   if ( this.tagName === 'SELECT' ) {
                    // 	requiredField = $(this).attr('required') === 'required';
                    //   }

                    if ( type !== undefined && $.inArray(type, $types) !== -1 && $(this).val() === '' && requiredField ) {
                        // add an "invalid" class to the field:
                        $(this).addClass("crt-form-error");
                        // and set the current valid status to false
                        valid = false;
                    } else if ( type === 'radio' || type === 'checkbox' ) {
                        let requiredOption = $(this).closest('.crt-field-group').find('.crt-form-field-option input').attr('required') === 'required';

                        if ( requiredOption && $stepTab.eq(currentTab).find('input[type="'+ type +'"]:checked').length === 0 ) {
                            // add an "invalid" class to the field:
                            $(this).addClass("crt-form-error");
                            // and set the current valid status to false
                            valid = false;
                        }
                    } else if ( requiredField && this.tagName === 'SELECT' && $(this).val().trim() === '' ) {
                        // select error wrap
                        $(this).closest('.crt-select-wrap').addClass('crt-form-error-wrap');
                        // add an "invalid" class to the field:
                        $(this).addClass("crt-form-error");
                        // and set the current valid status to false
                        valid = false;
                    } else if ( requiredField && this.tagName === 'TEXTAREA' && $(this).val().trim() === '' ) {
                        // add an "invalid" class to the field:
                        $(this).addClass("crt-form-error");
                        // and set the current valid status to false
                        valid = false;
                    }
                });

                if (!valid) {
                    $stepTab.eq(currentTab).find('.crt-form-error, .crt-form-error-wrap').each(function() {
                        if ( !($(this).closest('.crt-field-group').find('.crt-submit-error').length > 0) ) {
                            if ( $(this).attr('type') == 'file' ) {
                                $(this).closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">'+ CRTConfig.file_empty +'</p>');
                            } else if ( $(this).is('select') || $(this).attr('type') === 'radio' || $(this).attr('type') === 'checkbox' ) {
                                $(this).closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">'+ CRTConfig.select_empty +'</p>');
                            } else {
                                $(this).closest('.crt-field-group').append('<p class="crt-submit-notice crt-submit-error">'+ CRTConfig.input_empty +'</p>');
                            }
                        }
                    });
                }

                if (valid) {
                    $scope.find(".crt-step").eq(currentTab).addClass("crt-step-finish");
                } else {
                    if ( $scope.find(".crt-step").eq(currentTab).hasClass('crt-step-finish') ) {
                        $scope.find(".crt-step").eq(currentTab).removeClass('crt-step-finish');
                    }
                }

                return valid;
            }

            function fixStepIndicator(n) {
                // This function removes the "active" class of all steps...
                var $step = $scope.find(".crt-step");
                $step.removeClass("crt-step-active");
                //... and adds the "active" class on the current step:
                $step.eq(n).addClass("crt-step-active");

                if ( $scope.find('.crt-step-active').hasClass('crt-step-finish') ) {
                    $scope.find('.crt-step-active').removeClass('crt-step-finish');
                }

                const stepTabs = $scope.find('.crt-step-tab');
                const progressBarFill = $scope.find('.crt-step-progress-fill');

                let currentStep = n + 1;

                updateProgressBar()

                function updateProgressBar() {
                    const totalSteps = stepTabs.length;
                    const progressPercentage = (currentStep / totalSteps) * 100;

                    progressBarFill.css('width', progressPercentage + '%');
                    setTimeout(function() {
                        progressBarFill.text(Math.round(progressPercentage) + '%');
                    }, 500);
                }
            }
        });
    });
})(jQuery);