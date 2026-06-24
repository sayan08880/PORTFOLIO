<?php 
//namespace CrtAddons\Admin\Notices;

use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CrtRatingNotice {
    private $past_date;

    public function __construct() {
        global $pagenow;
        $this->past_date = false == get_option('crt_maybe_later_time') ? strtotime( '-14 days' ) : strtotime('-7 days');

        if ( current_user_can('administrator') ) {
            if ( empty(get_option('crt_rating_dismiss_notice', false)) && empty(get_option('crt_rating_already_rated', false)) ) {
                add_action( 'admin_init', [$this, 'check_plugin_install_time'] );
            }
        }

        if ( is_admin() ) {
            add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
        }

        add_action( 'wp_ajax_crt_rating_dismiss_notice', [$this, 'crt_rating_dismiss_notice'] );
        add_action( 'wp_ajax_crt_rating_maybe_later', [$this, 'crt_rating_maybe_later'] );
        add_action( 'wp_ajax_crt_rating_already_rated', [$this, 'crt_rating_already_rated'] );
        add_action( 'wp_ajax_crt_rating_need_help', [$this, 'crt_rating_need_help'] );
    }

    public function check_plugin_install_time() {   
        $install_date = get_option('crt_elementor_addons_activation_time');

        if ( false == get_option('crt_maybe_later_time') && false !== $install_date && $this->past_date >= $install_date ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice']);
        } else if ( false != get_option('crt_maybe_later_time') && $this->past_date >= get_option('crt_maybe_later_time') ) {
            add_action( 'admin_notices', [$this, 'render_rating_notice']);
        }
        add_action( 'admin_notices', [$this, 'render_rating_notice']);
    }

    public function crt_rating_maybe_later() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'crt_maybe_later_time', strtotime('now') );
    }

    function crt_rating_already_rated() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'crt_rating_already_rated' , true );
    }
    
    public function crt_rating_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        update_option( 'crt_rating_dismiss_notice', true );
    }

    public function crt_rating_need_help() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        // Reset Activation Time if user Needs Help
        update_option( 'crt_elementor_addons_activation_time', strtotime('now') );
    }

    public function render_rating_notice() {
        global $pagenow;
        if ( is_admin() ) {
            $plugin_info = get_plugin_data( __FILE__ , true, true );
            $dont_disturb = esc_url( get_admin_url() . '?spare_me=1' );

            echo '<div class="notice crt-rating-notice is-dismissible" style="border-left-color: #e401fb !important; display: flex; align-items: center; position: relative;">
                        <div class="crt-rating-notice-logo">
                            <img src="' . CRT_MANAGE_URI . 'assets/img/logo-256x256.png' . '">
                        </div>
                        <div>
                            <h3>Thank you for using CRT Addons to build this website!</h3>
                            <p style="">Could you please do us a BIG favor and give it a 5-star rating on WordPress? Just to help us spread the word and boost our motivation.</p>
                            <p>
                                <a href="https://wordpress.org/support/plugin/crt-manage/reviews/" target="_blank" class="crt-you-deserve-it button button-primary">OK, you deserve it!</a>
                                <a class="crt-maybe-later"><span class="dashicons dashicons-clock"></span> Maybe Later</a>
                                <a class="crt-already-rated"><span class="dashicons dashicons-yes"></span> I Already did</a>
                            </p>
                        </div>
                        <button type="button" class="notice-dismiss"><span class="screen-reader-text">Dismiss this notice.</span></button>
                </div>';

        }
    }

    public static function enqueue_scripts() {
        echo "
        <style>
            .crt-rating-notice {
              padding: 10px 20px;
              border-top: 0;
              border-bottom: 0;
            }

            .crt-rating-notice-logo {
                margin-right: 20px;
                width: 100px;
                height: 100px;
            }

            .crt-rating-notice-logo img {
                max-width: 100%;
            }

            .crt-rating-notice h3 {
              margin-bottom: 0;
            }

            .crt-rating-notice p {
              margin-top: 3px;
              margin-bottom: 15px;
            }

            .crt-maybe-later,
            .crt-already-rated,
            .crt-need-support,
            .crt-notice-dismiss-2 {
              text-decoration: none;
              margin-left: 12px;
              font-size: 14px;
              cursor: pointer;
            }

            .crt-already-rated .dashicons,
            .crt-maybe-later .dashicons,
            .crt-need-support .dashicons {
              vertical-align: middle;
            }

            .crt-notice-dismiss-2 .dashicons {
              vertical-align: middle;
            }

            .crt-rating-notice .notice-dismiss {
                display: block;
                position: absolute;
                top: 0;
                right: 1px;
                border: none;
                margin: 0;
                padding: 9px;
                background: none;
                color: #787c82;
                cursor: pointer;
            }

            .crt-rating-notice .notice-dismiss:before {
                background: none;
                color: #787c82;
                content: '\\f153';
                display: block;
                font: normal 16px/20px dashicons;
                speak: never;
                height: 20px;
                text-align: center;
                width: 20px;
                -webkit-font-smoothing: antialiased;
                -moz-osx-font-smoothing: grayscale;
            }

            .crt-rating-notice .notice-dismiss:hover:before {
                color: #d63638;
            }
        </style>
        ";

        echo "
        <script>
            jQuery(document).ready(function($) {
                $('.crt-rating-notice .notice-dismiss').on('click', function() {
                    $.ajax({
                        url: ajaxurl,
                        type: 'POST',
                        data: {
                            action: 'crt_rating_dismiss_notice',
                            nonce: '" . wp_create_nonce('crt-plugin-notice-js') . "'
                        },
                        success: function() {
                            $('.crt-rating-notice').fadeOut();
                        }
                    });
                });
            });
        </script>
        ";
    }

}

//if ( 'CRT Builder' === Utilities::get_plugin_name() ) {
    new CrtRatingNotice();
//}