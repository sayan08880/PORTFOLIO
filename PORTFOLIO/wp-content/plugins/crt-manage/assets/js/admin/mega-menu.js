jQuery(document).ready(function( $ ) {
	"use strict";

    var CrtMegaMenuSettings = {

        getLiveSettings: CrtMegaMenuSettingsData.settingsData,

        init: function() {
            CrtMegaMenuSettings.initSettingsButtons();
        },

        initSettingsButtons: function() {
            $( '#menu-to-edit .menu-item' ).each( function() {
                var $this = $(this),
                    id = CrtMegaMenuSettings.getNavItemId($this),
                    depth = CrtMegaMenuSettings.getNavItemDepth($this);
                    
                // Settings Button
                $this.append('<div class="crt-mm-settings-btn" data-id="'+ id +'" data-depth="'+ depth +'">Mega Menu</div>');
            });
            
            // Open Popup
            $('.crt-mm-settings-btn').on( 'click', CrtMegaMenuSettings.openSettingsPopup );
        },

        openSettingsPopup: function() {
            // Set Settings
            CrtMegaMenuSettings.setSettings( $(this) );

            // Show Popup
            $('.crt-mm-settings-popup-wrap').fadeIn();

            // Close Temmplate Editor Popup
            CrtMegaMenuSettings.closeTemplateEditorPopup();

            // Menu Width
            CrtMegaMenuSettings.initMenuWidthToggle();

            // Mobile Content
            CrtMegaMenuSettings.initMobileContentToggle();

            // Color Pickers
            CrtMegaMenuSettings.initColorPickers();

            // Icon Picker
            CrtMegaMenuSettings.initIconPicker();

            // Close Settings Popup
            CrtMegaMenuSettings.closeSettingsPopup();

            // Save Settings
            CrtMegaMenuSettings.saveSettings( $(this) );

            // Edit Menu Button
            CrtMegaMenuSettings.initEditMenuButton( $(this) );

            // Set Tite
            $('.crt-mm-popup-title').find('span').text( $(this).closest('li').find('.menu-item-title').text() );
        },

        closeSettingsPopup: function() {
            $('.crt-mm-settings-close-popup-btn').on('click', function() {
                $('.crt-mm-settings-popup-wrap').fadeOut();
            });

            $('.crt-mm-settings-popup-wrap').on('click', function(e) {
                if(e.target !== e.currentTarget) return;
                $(this).fadeOut();
            });

            // Unbind Click
            $('.crt-save-mega-menu-btn').off('click');
            $('.crt-edit-mega-menu-btn').off('click');
        },

        initEditMenuButton: function( selector ) {
            $('.crt-edit-mega-menu-btn').on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth');

                CrtMegaMenuSettings.createOrEditMenuTemplate(id, depth);
            });
        },

		createOrEditMenuTemplate: function(id, depth) {
			$.ajax({
				type: 'POST',
				url: ajaxurl,
				data: {
					action: 'crt_create_mega_menu_template',
                    nonce: CrtMegaMenuSettingsData.nonce,
                    item_id: id,
                    item_depth: depth
				},
				success: function( response ) {
                    console.log(response.data['edit_link']);
                    CrtMegaMenuSettings.openTemplateEditorPopup(response.data['edit_link']);
				}
			});
		},

        openTemplateEditorPopup: function( editorLink ) {
            $('.crt-mm-editor-popup-wrap').fadeIn();

            if ( !$('.crt-mm-editor-popup-iframe').find('iframe').length ) {
                $('.crt-mm-editor-popup-iframe').append('<iframe src="'+ editorLink +'" width="100%" height="100%"></iframe>');
            }

            // $('body').css('overflow','hidden');
        },

        closeTemplateEditorPopup: function() {
            $('.crt-mm-editor-close-popup-btn').on('click', function() {
                $('.crt-mm-editor-popup-wrap').fadeOut();
                setTimeout(function() {
                    $('.crt-mm-editor-popup-iframe').find('iframe').remove();
                    // $('body').css('overflow','visible');
                }, 1000);
            });
        },

        initColorPickers: function() {
            $('.crt-mm-setting-color').find('input').wpColorPicker();

            // Fix Color Picker
            if ( $('.crt-mm-setting-color').length ) {
                $('.crt-mm-setting-color').find('.wp-color-result-text').text('Select Color');
                $('.crt-mm-setting-color').find('.wp-picker-clear').val('Clear');
            }
        },

        initIconPicker: function() {
            $('#crt_mm_icon_picker').iconpicker();

            // Bind iconpicker events to the element
            $('#crt_mm_icon_picker').on('iconpickerSelected', function(event) {
                $('.crt-mm-setting-icon div span').removeClass('crt-mm-active-icon');
                $('.crt-mm-setting-icon div span:last-child').addClass('crt-mm-active-icon');
                $('.crt-mm-setting-icon div span:last-child i').removeAttr('class');
                $('.crt-mm-setting-icon div span:last-child i').addClass(event.iconpickerValue);
            });

            // Bind iconpicker events to the element
            $('#crt_mm_icon_picker').on('iconpickerHide', function(event) {
                setTimeout(function() {
                    if ( 'crt-mm-active-icon' == $('.crt-mm-setting-icon div span:first-child').attr('class') ) {
                        $('#crt_mm_icon_picker').val('')
                    }

                    $('.crt-mm-settings-wrap').removeAttr('style');
                },100);
            });

            $('.crt-mm-setting-icon div span:first-child').on('click', function() {
                $('.crt-mm-setting-icon div span').removeClass('crt-mm-active-icon');
                $(this).addClass('crt-mm-active-icon');
            });

            $('.crt-mm-setting-icon div span:last-child').on('click', function() {
                $('#crt_mm_icon_picker').focus();
                $('.crt-mm-settings-wrap').css('overflow', 'hidden');
            });
        },

        saveSettings: function( selector ) {
            var $saveButton = $('.crt-save-mega-menu-btn');

            // Reset
            $saveButton.text('Save');

            $saveButton.on('click', function() {
                var id = selector.attr('data-id'),
                    depth = selector.attr('data-depth'),
                    settings = CrtMegaMenuSettings.getSettings();

                $.ajax({
                    type: 'POST',
                    url: ajaxurl,
                    data: {
                        action: 'crt_save_mega_menu_settings',
                        nonce: CrtMegaMenuSettingsData.nonce,
                        item_id: id,
                        item_depth: depth,
                        item_settings: settings
                    },
                    success: function( response ) {
                        $saveButton.text('Saved');
                        $saveButton.append('<span class="dashicons dashicons-yes"></span>');

                        setTimeout(function() {
                            $saveButton.find('.dashicons').remove();
                            $saveButton.text('Save');
                            $saveButton.blur();
                        }, 1000);

                        // Update Settings
                        CrtMegaMenuSettings.getLiveSettings[id] = settings;
                    }
                });
            });
            
        },

        getSettings: function() {
            var settings = {};

            $('.crt-mm-setting').each(function() {
                var $this = $(this),
                    checkbox = $this.find('input[type="checkbox"]'),
                    select = $this.find('select'),
                    number = $this.find('input[type="number"]'),
                    text = $this.find('input[type="text"]');

                // Checkbox
                if ( checkbox.length ) {
                    let id = checkbox.attr('id');
                    settings[id] = checkbox.prop('checked') ? 'true' : 'false';
                }

                // Select
                if ( select.length ) {
                    let id = select.attr('id');
                    settings[id] = select.val();
                }
                
                // Multi Value
                // if ( $this.hasClass('crt-mm-setting-radius') ) {
                //     let multiValue = [],
                //         id = $this.find('input').attr('id');

                //     $this.find('input').each(function() {
                //         multiValue.push($(this).val());
                //     });

                //     settings[id] = multiValue;
                // }

                // Number
                if ( number.length ) {
                    let id = number.attr('id');
                    settings[id] = number.val();
                }
                
                // Text
                if ( text.length ) {
                    let id = text.attr('id');

                    if ( 'crt_mm_icon_picker' !== id ) {
                        settings[id] = text.val();
                    } else {
                        let icon_class = $('.crt-mm-setting-icon div span.crt-mm-active-icon').find('i').attr('class');
                        settings[id] = 'fas fa-ban' !== icon_class ? icon_class : '';
                    }
                }
            });

            return settings;
        },

		getNavItemId: function( item ) {
			var id = item.attr( 'id' );
			return id.replace( 'menu-item-', '' );
		},

		getNavItemDepth: function( item ) {
			var depthClass = item.attr( 'class' ).match( /menu-item-depth-\d/ );

			if ( ! depthClass[0] ) {
				return 0;
			} else {
                return depthClass[0].replace( 'menu-item-depth-', '' );
            }
		},

        initMenuWidthToggle: function() {
            var select = $('#crt_mm_width'),
                option = $('#crt_mm_custom_width').closest('.crt-mm-setting');
            
            if ( 'custom' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'custom' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        },

        initMobileContentToggle: function() {
            var select = $('#crt_mm_mobile_content'),
                option = $('#crt_mm_render').closest('.crt-mm-setting');
            
            if ( 'mega' === select.val() ) {
                option.show();
            } else {
                option.hide();
            }

            select.on('change', function() {
                if ( 'mega' === select.val() ) {
                    option.show();
                } else {
                    option.hide();
                }            
            });
        },

        setSettings: function( selector ) {
            var id = selector.attr('data-id'),
                settings = CrtMegaMenuSettings.getLiveSettings[id];

            if ( ! $.isEmptyObject(settings) ) {
                // General
                if ( 'true' == settings['crt_mm_enable'] ) {
                    $('#crt_mm_enable').prop( 'checked', true );
                } else {
                    $('#crt_mm_enable').prop( 'checked', false );
                }

                $('#crt_mm_position').val(settings['crt_mm_position']).trigger('change');
                $('#crt_mm_width').val(settings['crt_mm_width']).trigger('change');
                $('#crt_mm_custom_width').val(settings['crt_mm_custom_width']);
                $('#crt_mm_render').val(settings['crt_mm_render']).trigger('change');
                $('#crt_mm_mobile_content').val(settings['crt_mm_mobile_content']).trigger('change');

                // Icon
                if ( '' !== settings['crt_mm_icon_picker'] ) {
                    $('.crt-mm-setting-icon div span').removeClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:last-child').addClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.crt-mm-setting-icon div span:last-child i').addClass(settings['crt_mm_icon_picker']);
                } else {
                    $('.crt-mm-setting-icon div span').removeClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:first-child').addClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.crt-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#crt_mm_icon_color').val(settings['crt_mm_icon_color']).trigger('keyup');
                $('#crt_mm_icon_size').val(settings['crt_mm_icon_size']);

                // Badge
                $('#crt_mm_badge_text').val(settings['crt_mm_badge_text']);
                $('#crt_mm_badge_color').val(settings['crt_mm_badge_color']).trigger('keyup');
                $('#crt_mm_badge_bg_color').val(settings['crt_mm_badge_bg_color']).trigger('keyup');
                if ( 'true' == settings['crt_mm_badge_animation'] ) {
                    $('#crt_mm_badge_animation').prop( 'checked', true );
                } else {
                    $('#crt_mm_badge_animation').prop( 'checked', false );
                }

            // Default Values
            } else {
                // General
                $('#crt_mm_enable').prop( 'checked', false );
                $('#crt_mm_position').val('default').trigger('change');
                $('#crt_mm_width').val('default').trigger('change');
                $('#crt_mm_custom_width').val('600');
                $('#crt_mm_render').val('default').trigger('change');
                $('#crt_mm_mobile_content').val('mega').trigger('change');

                // Icon
                if ( '' !== settings['crt_mm_icon_picker'] ) {
                    $('.crt-mm-setting-icon div span').removeClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:first-child').addClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.crt-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#crt_mm_icon_color').val('').trigger('change');
                $('#crt_mm_icon_size').val('');

                // Badge
                $('#crt_mm_badge_text').val('');
                $('#crt_mm_badge_color').val('#ffffff').trigger('keyup');
                $('#crt_mm_badge_bg_color').val('#000000').trigger('keyup');
                $('#crt_mm_badge_animation').prop( 'checked', false );
            }

            if ( 'false' === $('.crt-mm-settings-wrap').attr('data-pro-active') ) {
                $('#crt_mm_render').val('default').trigger('change');
                $('#crt_mm_mobile_content').val('mega').trigger('change');

                // Icon
                if ( '' !== settings['crt_mm_icon_picker'] ) {
                    $('.crt-mm-setting-icon div span').removeClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:first-child').addClass('crt-mm-active-icon');
                    $('.crt-mm-setting-icon div span:last-child i').removeAttr('class');
                    $('.crt-mm-setting-icon div span:last-child i').addClass('fas fa-angle-down');
                }
                $('#crt_mm_icon_color').val('').trigger('change');
                $('#crt_mm_icon_size').val('');

                // Badge
                $('#crt_mm_badge_text').val('');
                $('#crt_mm_badge_color').val('#ffffff').trigger('keyup');
                $('#crt_mm_badge_bg_color').val('#000000').trigger('keyup');
                $('#crt_mm_badge_animation').prop( 'checked', false );
            }
        }
    }

    // Init
    CrtMegaMenuSettings.init();

});