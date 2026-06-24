( function( $, elementor ) {

	"use strict";

	var CrtPopups = {

		init: function() {
			$(document).ready(function() {
				if ( ! $( '.crt-template-popup' ).length || CrtPopups.editorCheck() ) {
					return;
				}

				CrtPopups.openPopupInit();
				CrtPopups.closePopupInit();
			});
		},

		openPopupInit: function() {
			$( '.crt-template-popup' ).each( function() {
				var popup = $(this),
					popupID = CrtPopups.getID( popup );

				if ( ! CrtPopups.checkAvailability( popupID ) ) {
					return;
				}

				if ( ! CrtPopups.checkStopShowingAfterDate( popup ) ) {
					return;
				}

				// Set Local Storage
				CrtPopups.setLocalStorage( popup, 'show' );

				// Get Settings
				var getLocalStorage = JSON.parse( localStorage.getItem( 'CrtPopupSettings' ) ),
					settings = getLocalStorage[ popupID ];

				if ( ! CrtPopups.checkAvailableDevice( popup, settings ) ) {
					return false;
				}

				// Trigger Button Init
				CrtPopups.popupTriggerInit( popup );

				// Page Load
				if ( 'load' === settings.popup_trigger ) {
					var loadDelay = settings.popup_load_delay * 1000;

					$(window).on( 'load', function() {
						setTimeout( function() {
							CrtPopups.openPopup( popup, settings );
						}, loadDelay );
					});

				// Page Scroll
				} else if ( 'scroll' === settings.popup_trigger ) {
					$(window).on( 'scroll', function() {
						var scrollPercent = $(window).scrollTop() / ($(document).height() - $(window).height()),
							scrollPercent = Math.round( scrollPercent * 100 );

						if ( scrollPercent >= settings.popup_scroll_progress && ! popup.hasClass( 'crt-popup-open' ) ) {
							CrtPopups.openPopup( popup, settings );
						}
					});

				// Scroll to Element
				} else if ( 'element-scroll' === settings.popup_trigger ) {
					$(window).on( 'scroll', function() {
						var element = $( settings.popup_element_scroll ),
							ScrollBottom = $(window).scrollTop() + $(window).height();

						if ( ! element.length ) {
							return;
						}

						if ( element.offset().top < ScrollBottom && ! popup.hasClass( 'crt-popup-open' ) ) {
							CrtPopups.openPopup( popup, settings );
						}
					});

				// Specific Date
				} else if ( 'date' === settings.popup_trigger ) {
					var nowDate   = Date.now(),
						startDate = Date.parse( settings.popup_specific_date );

					if ( startDate < nowDate ) {

						setTimeout( function() {
							CrtPopups.openPopup( popup, settings );
						}, 1000 );
					}

				// User Inactivity
				} else if ( 'inactivity' === settings.popup_trigger ) {
					var idleTimer = null,
						inactivityTime = settings.popup_inactivity_time * 1000;

					$( '*' ).bind( 'mousemove click keyup scroll resize', function () {
						if ( popup.hasClass( 'crt-popup-open' ) ) {
							return;
						}

						// Reset Timer
						clearTimeout( idleTimer );

						// Open if Inactive
						idleTimer = setTimeout( function() { 
							CrtPopups.openPopup( popup, settings );
						}, inactivityTime );
					});

					$( 'body' ).trigger( 'mousemove' );

				// User Exit Intent
				} else if ( 'exit' === settings.popup_trigger ) {
					$(document).on( 'mouseleave', 'body', function( event ) {
						if ( ! popup.hasClass( 'crt-popup-open' ) ) {
							CrtPopups.openPopup( popup, settings );
						}
					} );

				// Custom Trigger
				} else if ( 'custom' === settings.popup_trigger ) {
					$( settings.popup_custom_trigger ).on( 'click', function() {
						CrtPopups.openPopup( popup, settings );
					});

					$( settings.popup_custom_trigger ).css( 'cursor', 'pointer' );
				}

				// Enable Scrollbar
				if ( '0px' !== popup.find('.crt-popup-container-inner').css('height') ) {
					const ps = new PerfectScrollbar(popup.find('.crt-popup-container-inner')[0], {
						suppressScrollX: true
					});
				}
			});
		}, // End openPopup

		openPopup: function( popup, settings ) {
			if ( 'notification' === settings.popup_display_as ) {
				popup.addClass( 'crt-popup-notification' );

				setTimeout(function() {
					$( 'body' ).animate({
						'padding-top' : popup.find( '.crt-popup-container' ).outerHeight() +'px'
					}, settings.popup_animation_duration * 1000, 'linear' );
				}, 10 );
			}

			// Disable Page Scroll
			if ( settings.popup_disable_page_scroll && 'modal' === settings.popup_display_as ) {
				$( 'body' ).css( 'overflow', 'hidden' );
			}

			// Open Popup
			popup.addClass( 'crt-popup-open' ).show();
			popup.find( '.crt-popup-container' ).addClass( 'animated '+ settings.popup_animation );

            // goga
            $(window).trigger('resize');

			// Overlay Fade In
			$( '.crt-popup-overlay' ).hide().fadeIn();

			// Close Button Show Up Delay
			popup.find( '.crt-popup-close-btn' ).css( 'opacity', '0' );
			setTimeout(function() {
				popup.find( '.crt-popup-close-btn' ).animate({
					'opacity' : '1'
				}, 500 );
			}, settings.popup_close_button_display_delay * 1000 );


			// Close Automatically
			if ( false !== settings.popup_automatic_close_switch ) {
				setTimeout(function() {
					CrtPopups.closePopup( popup );
				}, settings.popup_automatic_close_delay * 1000 );
			}
		}, // End openPopup

		closePopupInit: function() {
			// Close Button
			$( '.crt-popup-close-btn' ).on( 'click', function() {
				CrtPopups.closePopup( $(this).closest( '.crt-template-popup' ) );
			});

			// Overlay Click
			$( '.crt-popup-overlay' ).on( 'click', function() {
				var popup = $(this).closest( '.crt-template-popup' ),
					popupID = CrtPopups.getID( popup ),
					settings = CrtPopups.getLocalStorage( popupID );

				if ( false == settings.popup_overlay_disable_close ) {
					CrtPopups.closePopup( popup );
				}
			});

			// ESC Key Press
			$(document).on( 'keyup', function( event ) {
				var popup = $( '.crt-popup-open' );

				if ( popup.length ) {
					var	popupID = CrtPopups.getID( popup ),
						settings = CrtPopups.getLocalStorage( popupID );

					if ( 27 == event.keyCode && false == settings.popup_disable_esc_key ) {
						CrtPopups.closePopup( popup );
					}
				}
			});
		},

		closePopup: function( popup, ) {
			var popupID = CrtPopups.getID( popup ),
				settings = CrtPopups.getLocalStorage( popupID );

			// Notification
			if ( 'notification' === settings.popup_display_as ) {
				$( 'body' ).css( 'padding-top', 0 );
			}

			// Update Local Storage
			CrtPopups.setLocalStorage( popup, 'hide' );

			// Close Pupup
			if ( 'modal' === settings.popup_display_as ) {
				popup.fadeOut();
			} else {
				popup.hide();
			}

			// Enable Page Scrolling
			$( 'body' ).css( 'overflow', 'visible' );
			
            // goga
            $(window).trigger('resize');
		},

		popupTriggerInit: function( popup ) {
			var popupTrigger = popup.find( '.crt-popup-trigger-button' );

			if ( ! popupTrigger.length ) {
				return;
			}

			popupTrigger.on( 'click', function() {
				// Get Settings
				var settings = JSON.parse( localStorage.getItem( 'CrtPopupSettings') ) || {};

				var popupTriggerType = $(this).attr( 'data-trigger' ),
					popupShowDelay = $(this).attr( 'data-show-delay'),
					popupRedirect = $(this).attr( 'data-redirect'),
					popupRedirectURL = $(this).attr( 'data-redirect-url'),
					popupID = CrtPopups.getID( popup );

				if ( 'close' === popupTriggerType ) {
					settings[popupID].popup_show_again_delay = parseInt( popupShowDelay, 10 );
					settings[popupID].popup_close_time = Date.now();
				} else if ( 'close-permanently' === popupTriggerType ) {
					settings[popupID].popup_show_again_delay = parseInt( popupShowDelay, 10 );
					settings[popupID].popup_close_time = Date.now();
				} else if ( 'back' === popupTriggerType ) {
					window.history.back();
				}

				CrtPopups.closePopup( popup );

				// Save Settings in Browser
				localStorage.setItem( 'CrtPopupSettings', JSON.stringify( settings ) );

				if ( 'back' !== popupTriggerType && 'yes' === popupRedirect ) {
					setTimeout(function() {
						window.location.href = popupRedirectURL;
					}, 100);
				}
			});

		}, // End popupTriggerInit

		getLocalStorage: function( id ) {
			var getLocalStorage = JSON.parse( localStorage.getItem( 'CrtPopupSettings' ) );

			if ( null == getLocalStorage ) {
				return false;
			}

			// Get Settings
			var settings = getLocalStorage[ id ];

			if ( null == settings ) {
				return false;
			}

			return settings;
		},

		setLocalStorage: function( popup, display ) {
			var popupID = CrtPopups.getID( popup );

			// Parse Settings
			var dataSettings = JSON.parse( popup.attr( 'data-settings' ) ),
				settings = JSON.parse( localStorage.getItem( 'CrtPopupSettings') ) || {};

			// Merge With Defaults
			settings[popupID] = dataSettings;

			// Set Close Time
			if ( 'hide' === display ) {
				settings[popupID].popup_close_time = Date.now();
			} else {
				settings[popupID].popup_close_time = false;
			}

			// Save Settings in Browser
			localStorage.setItem( 'CrtPopupSettings', JSON.stringify( settings ) );
		},

		checkStopShowingAfterDate: function( popup ) {
			var settings = JSON.parse( popup.attr( 'data-settings' ) );

			// Current Date
			var currentDate = Date.now();

			// Stop Showing after Date
			if ( 'yes' === settings.popup_stop_after_date ) {
				if ( currentDate >= Date.parse( settings.popup_stop_after_date_select ) ) {
					return false;
				}
			}

			return true;
		},

		checkAvailability: function( id ) {
			var popup = $( '#crt-popup-id-'+ id ),
				dataSettings = JSON.parse( popup.attr( 'data-settings' ) ),
				currentURL = window.location.href;

			if ( 'yes' === dataSettings.popup_show_via_referral && -1 === currentURL.indexOf('crt_templates=user-popup') ) {
				if ( currentURL.indexOf( dataSettings.popup_referral_keyword ) == -1 ) {
					return;
				}
			}

			// If Storage not set, continue
			if ( false === CrtPopups.getLocalStorage( id ) ) {
				return true;
			}

			// Popup Trigger
			var trigger = popup.find( '.crt-popup-trigger-button' ),
				triggerShowDelay = trigger.attr( 'data-show-delay' );

			// Current Date
			var currentDate = Date.now();

			// Get Settings
			var settings = CrtPopups.getLocalStorage( id );

			// If delay has been changed
			if ( triggerShowDelay ) {

				var permanent = true;

				trigger.each(function() {
					var delay = $(this).attr( 'data-show-delay' );

					if ( settings.popup_show_again_delay == parseInt( delay, 10 ) ) {
						permanent = false;
					}
				});

				if ( true === permanent ) {
					return true;
				}
			} else {
				if ( settings.popup_show_again_delay != dataSettings.popup_show_again_delay ) {
					return true;
				}
			}

			// Get Dates
			var closeDate = settings.popup_close_time || 0,
				showDelay = parseInt( settings.popup_show_again_delay, 10 );

			if ( closeDate + showDelay >= currentDate ) {
				return false;
			} else {
				return true;
			}
		},

		checkAvailableDevice: function( popup, settings ) {//TODO: Add all 7 device support
			var viewport = $( 'body' ).prop( 'clientWidth' );

			if ( viewport > 1024 ) {
				return Boolean(settings.popup_show_on_device);
			} else if ( viewport > 768 ) {
				return Boolean(settings.popup_show_on_device_tablet);
			} else {
				return Boolean(settings.popup_show_on_device_mobile);
			}
		},

		getID: function( popup ) {
			var id = popup.attr( 'id' );

			return id.replace( 'crt-popup-id-', '' );
		},

		// Editor Check
		editorCheck: function() {
			return $( 'body' ).hasClass( 'elementor-editor-active' ) ? true : false;
		}
	} // End CrtPopups

	// Init
	CrtPopups.init();

}( jQuery, window.elementorFrontend ) );