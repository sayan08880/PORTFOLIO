<?php 
//namespace CrtAddons\Admin\Notices;

use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

class CrtProFeaturesNotice {
    public function __construct() {

//        if ( !Utilities::is_pro() ) {

            if ( current_user_can('administrator') ) {

                if ( !get_option('crt_pro_features_dismiss_notice' ) ) {
                    add_action( 'admin_init', [$this, 'render_notice'] );
                }
            }

            if ( is_admin() && !get_option('crt_pro_features_dismiss_notice' ) ) {
                add_action( 'admin_head', [$this, 'enqueue_scripts' ] );
            }

            add_action( 'wp_ajax_crt_pro_features_dismiss_notice', [$this, 'crt_pro_features_dismiss_notice'] );
//        }
    }

    public function render_notice() {
        add_action( 'admin_notices', [$this, 'render_pro_features_notice' ]);
    }
    
    public function crt_pro_features_dismiss_notice() {
		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-notice-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        add_option( 'crt_pro_features_dismiss_notice', true );
        return 'responsetext';
    }

    public function render_pro_features_notice() {
        global $current_screen;

        if ( is_admin() && 'toplevel_page_crt-addons' == $current_screen->id ) {
            echo '<div class="crt-pro-features-notice-wrap">';
                echo '<div class="notice crt-pro-features-notice is-dismissible">
                    <h2><span>Big Update</span></h2>
                    <p>We are happy to announce that <strong>Royal Elementor Addons Expert</strong> version now supports <strong>Dynamic Content</strong> and <strong>Advanced Filters</strong> </p>
                    <div class="crt-dynamic-content-notice">
                        <h3>Dynamic Content</h3>
                        <ul class="crt-new-widgets-list">
                            <li><strong>Dynamic Tags (Elementor)</strong></li>
                            <li><strong>Advanced Custom Fields (Extended)</strong></li>
                            <li><strong>Custom Post Type Generator</strong></li>
                            <li><strong>Custom Taxonomy Generator</strong></li>
                            <li><a target="_blank" href="https://www.youtube.com/watch?v=wis1rQTn1tg">Product Wishlist (WooCommerce)</a></li>
                            <li><a target="_blank" href="https://www.youtube.com/watch?v=wis1rQTn1tg">Product Compare (WooCommerce)</a></li>
                        </ul>

                        <a class="crt-pro-features-btn crt-dynamic-tutorial" href="https://www.youtube.com/watch?v=kE1zmi3fxh8" target="_blank"><span class="dashicons dashicons-video-alt3"></span> Dynamic Tutorial</a>
                    </div>
                    <div class="crt-advanced-filters-notice">
                        <h3>Advanced Filters</h3>
                        <ul class="crt-new-widgets-list">
                            <li><strong><a target="_blank" href="https://demosites.royal-elementor-addons.com/woo-advanced-filters-preview/preview-links/">Use with any CPT, Taxonomy or Field</a></strong></li>
                            <li><strong><a target="_blank" href="https://demosites.royal-elementor-addons.com/woo-advanced-filters-preview/preview-links/">Filter by Price, Color, Brand, Rating, etc...</a></strong></li>
                            <li><strong>AJAX Ready for Fast Interaction</strong></li>
                            <li><strong>Interdependent filters</strong></li>
                            <li><strong>Manual Apply Button</strong></li>
                            <li><strong>Advanced Custom Fields (ACF) Ready</strong></li>
                        </ul>

                        <a class="crt-pro-features-btn crt-dynamic-tutorial" href="https://www.youtube.com/watch?v=ejbvzt2BkJE" target="_blank"><span class="dashicons dashicons-video-alt3"></span> Advanced Filters Tutorial</a>
                    </div>
                    <a class="crt-pro-features-btn crt-upgrade-expert" href="https://royal-elementor-addons.com/#purchasepro?ref=rea-plugin-backend-expertnoticebanner-checkbtn-upgrade-expert#purchasepro" target="_blank">Upgrade to Expert</a>
                        
                    <canvas id="crt-notice-confetti"></canvas>';
                echo '</div>';
            echo '</div>';
        }
    }
    
    public static function enqueue_scripts() {
        global $current_screen;

        
        if ( !(is_admin() && 'toplevel_page_crt-addons' == $current_screen->id) ) {
            return;
        }

        // Load Confetti
        wp_enqueue_script( 'crt-confetti-js', CRT_MANAGE_URI .'assets/js/admin/confetti/confetti.min.js', ['jquery'] );

        // Scripts & Styles
        echo "
        <script>
        jQuery( document ).ready( function($) {

            $('html, body').animate({
                scrollTop: 0
            }, 'slow');
            $('body').addClass('crt-pro-features-body');
            $(document).find('.crt-pro-features-notice-wrap').css('opacity', 1);

            if ( jQuery('#crt-notice-confetti').length ) {
                const crtConfetti = confetti.create( document.getElementById('crt-notice-confetti'), {
                    resize: true
                });

                setTimeout( function () {
                    crtConfetti( {
                        particleCount: 150,
                        origin: { x: 1, y: 2 },
                        gravity: 0.3,
                        spread: 50,
                        ticks: 150,
                        angle: 120,
                        startVelocity: 60,
                        colors: [
                            '#0e6ef1',
                            '#f5b800',
                            '#ff344c',
                            '#98e027',
                            '#9900f1',
                        ],
                    } );
                }, 500 );

                setTimeout( function () {
                    crtConfetti( {
                        particleCount: 150,
                        origin: { x: 0, y: 2 },
                        gravity: 0.3,
                        spread: 50,
                        ticks: 200,
                        angle: 60,
                        startVelocity: 60,
                        colors: [
                            '#0e6ef1',
                            '#f5b800',
                            '#ff344c',
                            '#98e027',
                            '#9900f1',
                        ],
                    } );
                }, 900 );
            }
        });
        </script>

        <style>
            .crt-pro-features-body {
                overflow: hidden;
            }

            .crt-pro-features-notice-wrap {
                position: absolute;
                display: block;
                top: 0;
                left: 0;
                height: 100%;
                width: 100%;
                z-index: 999;
                background-color: rgba(0, 0, 0, 0.2);
                opacity: 0;
            }

            .crt-settings-page-header .crt-pro-features-notice.notice {
                display: flex !important;
                width: 600px;
                position: absolute;
                top: 100px;
                left: 50%;
                transform: translate(-50%);
                align-items: center;
                justify-content: center;
                padding: 135px 65px 100px;
                border: 0 !important;
                box-shadow: 0 0 5px rgb(0 0 0 / 0.3);
                z-index: 999;
                border-radius: 3px;
            }

            .crt-dynamic-content-notice {
                padding-right: 50px;
                border-right: 1px solid #e8e8e8;
            }

            .crt-advanced-filters-notice {
                padding-left: 50px;
            }

            .crt-pro-features-notice h2 span {
                position: absolute;
                top: 20px;
                left: 50%;
                transform: translateX(-50%);

                display: inline-block;
                font-size: 13px;
                text-transform: uppercase;
                letter-spacing: 0.4px;
                color: #fff;
                background-color: #f51f3d;
                padding: 5px 18px 7px;
                border-radius: 3px;
            }

            .crt-pro-features-notice h2 + p {
                position: absolute;
                top: 50px;
                width: 400px;
                left: 50%;
                transform: translateX(-50%);
                text-align: center;
            }

            .crt-pro-features-notice h3 {
                font-size: 32px;
                margin-top: 0;
                margin-bottom: 20px;
            }

            .crt-pro-features-notice p {
              margin-top: 10px;
              margin-bottom: 25px;
              font-size: 14px;
            }
            
            .crt-pro-features-notice ul a {
                display: block;
                text-decoration: none;
            }
            
            .crt-pro-features-notice ul li:last-child a:after {
                display: none;
            }

            .crt-pro-features-btn,
            .crt-new-widgets-list + a {
                display: inline-block;
                text-decoration: none;
                text-transform: uppercase;
                color: #fff;
                background: #6A4BFF;
                padding: 5px 18px 7px;
                border-radius: 3px;
                margin-top: 30px;
                font-weight: 500;
                letter-spacing: 0.3px;
            }

            .crt-pro-features-btn .dashicons {
                width: 16px;
                height: 16px;
                font-size: 16px;
                margin-top: 2px;
            }

            .crt-upgrade-expert {
                position: absolute;
                bottom: 25px;
                left: 50%;
                transform: translateX(-50%);
                padding: 7px 25px 9px;
            }


            .crt-pro-features-btn.crt-dynamic-tutorial {
                background: #e1ad01;
            }

            .crt-pro-features-btn:hover {
                color: #FFF;
            }

            .crt-pro-features-btn:focus {
                color: #FFF;
            }

            .crt-get-started-button.button-primary {
            background-color: #6A4BFF;
            }

            .crt-get-started-button.button-primary:hover {
            background-color: #583ed7;
            }

            .crt-get-started-button.button-secondary {
            border: 1px solid #6A4BFF;
            color: #6A4BFF;
            }

            .crt-get-started-button.button-secondary:hover {
            background-color: #6A4BFF;
            border: 2px solid #6A4BFF;
            color: #fff;
            }

            .crt-get-started-button {
                padding: 5px 25px !important;
            }

            .crt-get-started-button .dashicons {
              font-size: 12px;
              line-height: 28px;
            }
            
            .crt-pro-features-notice .image-wrap {
              margin-left: auto;
            }

            .crt-pro-features-notice .image-wrap img {
              zoom: 0.45;
            }

            @media screen and (max-width: 1366px) {
                .crt-pro-features-notice h3 {
                    font-size: 32px;
                }

                .crt-pro-features-notice .image-wrap img {
                  zoom: 0.4;
                }
            }

            @media screen and (max-width: 1280px) {
                .crt-pro-features-notice .image-wrap img {
                  zoom: 0.35;
                }
            }

            #crt-notice-confetti {
              position: absolute;
              top: 0;
              left: 0;
              width: 100%;
              height: 100%;
              pointer-events: none;
            }
        </style>";

        
    }
}

//if ( 'CRT Builder' === Utilities::get_plugin_name() ) {
    new CrtProFeaturesNotice();
//}