<?php
use Elementor\Core\App\App;
use CrtAddons\Plugin;

defined('ABSPATH') or die('Sorry guys!');
/**
 * @class CRT_Manage_Base
 */
class CRT_Manage_Base {

    public static $_instance = '';

    protected $crt_manage_data;

    protected $crt_manage_theme;

    protected $crt_manage_child_theme;

    protected $crt_manage_depend_plugins = false;

    protected $crt_manage_license = array();

    protected $is_pre_theme = false;

    protected $crt_manage_theme_allow_used = array(
        'tenzin-news-magazine',
        'times-news-magazine-blog',
        'megan-blog-multipurpose',
        'market-coupons-deals',
        'nason-magazine-blog',
        'matthew-magazine-blog',
        'egan-portfolio-resume',
        'pascal-news-magazine',
        'mushava-magazine-blog',
        'teri-shop-ecommerce',
    );

    protected $crt_manage_theme_demo = array(
        'tenzin-news-magazine' => array('https://demo1.crthemes.com/tenzin', 'https://demo1.crthemes.com/times', 'https://demo1.crthemes.com/travel', 'https://demo1.crthemes.com/fashion'),
        'megan-blog-multipurpose' => array('https://demo1.crthemes.com/megan'),
        'market-coupons-deals' => array('https://demo1.crthemes.com/coupon'),
        'nason-magazine-blog' => array('https://demo1.crthemes.com/nason'),
        'matthew-magazine-blog' => array('https://demo1.crthemes.com/matthew'),
        'egan-portfolio-resume' => array('https://demo1.crthemes.com/egan'),
        'pascal-news-magazine' => array('https://demo1.crthemes.com/pascal'),
        'mushava-magazine-blog' => array('https://demo1.crthemes.com/mushava'),
        'teri-shop-ecommerce' => array('https://demo1.crthemes.com/teri'),
    );

    private static $prefix_pre = 'is_pre';

    public $crt_manage_theme_uri = '';

    public $crt_addons = 'C47vHY3J0LWFkZG9ucy1wcm8=';

    public function __construct() {
        $this->crt_manage_theme = get_option( 'template' );
        $this->crt_manage_child_theme = get_option( 'stylesheet' );

        add_action( 'wp_enqueue_scripts', array($this, 'crt_manage_front_end_scripts') );

        $licenses = !empty(get_option('crt_manage_license')) ? json_decode(get_option('crt_manage_license')) : array();
        if(in_array($this->crt_manage_theme, $licenses)) {
            $this->is_pre_theme = true;
        }
        $this->crt_manage_theme_uri = wp_get_theme()->get( 'ThemeURI' );

        if(!in_array($this->crt_manage_theme, $this->crt_manage_theme_allow_used)) {
            $this->crt_manage_depend_plugins = true;
        }
        if (!$this->crt_manage_depend_plugins) {
            $this->includes();
            add_action( 'customize_register', array($this, 'crt_manage_customize_register') );
            if(!$this->is_pre_theme) {
                add_action( 'admin_notices', array($this, 'crt_manage_add_notice_buy_theme') );
            }
            global $theme_premium;
            $licenses = !empty(get_option('crt_manage_license')) ? json_decode(get_option('crt_manage_license')) : array();
            if(in_array($this->crt_manage_theme, $licenses)) {
                $theme_premium = true;
            } else {
                $theme_premium = false;
            }

        }

//      add_action( 'wp_ajax_crt_manage_theme_purchase_code', array( $this, 'crt_manage_theme_purchase_code') );
        add_action( 'wp_ajax_crt_manage_theme_purchase_code', array( $this, 'crt_manage_theme_purchase_code_lemon') );
        add_action( 'admin_enqueue_scripts', array( $this, 'crt_manage_admin_js' ) );

        global $crt_manage_is_woo;
        if(class_exists( 'WooCommerce' )) {
            $crt_manage_is_woo = true;
        } else {
            $crt_manage_is_woo = false;
        }

        if ( did_action( 'elementor/loaded' ) ) {
            $this->addons_includes();
        } else {
            add_action( 'elementor/loaded', [ $this, 'addons_includes' ] );
        }

        if(!did_action( 'elementor/loaded' )) {
            add_action( 'admin_menu', 'register_settings_menus' );

            function register_settings_menus() {
                add_menu_page( esc_html__( 'CRT Manage', 'crt-manage' ), esc_html__( 'CRT Builder', 'crt-manage' ), 'manage_options', 'crt-manage', 'crt_manage_render', 'dashicons-admin-site', '58.6' );
            }
            function crt_manage_render(){
//        $active_tab = $_GET['tab'];
                $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'crt_tab_settings';
                ?>
                <div class="wrap crt-settings-page-wrap">
                    <div class="crt-settings-page-header">
                        <h1><?php echo esc_html('CRT Builder'); ?></h1>
                    </div>
                    <div class="crt-settings-page">
                        <form method="post" action="options.php">
                            <?php
                            settings_fields( 'crt_manage_settings' );
                            do_settings_sections( 'crt_manage_settings' );
                            ?>
                            <div class="nav-tab-wrapper crt-nav-tab-wrapper">
                                <a href="?page=crt-manage&tab=crt_tab_settings" data-title="Elements" class="nav-tab <?php echo ($active_tab == 'crt_tab_settings') ? 'nav-tab-active' : ''; ?>">
                                    <?php esc_html_e( 'Settings', 'crt-manage' ); ?>
                                </a>
                                <a href="?page=crt-manage&tab=crt_tab_license" data-title="Elements" class="nav-tab <?php echo ($active_tab == 'crt_tab_license') ? 'nav-tab-active' : ''; ?>">
                                    <?php esc_html_e( 'License', 'crt-manage' ); ?>
                                </a>
                            </div>
                            <?php if($active_tab == 'crt_tab_license'): ?>
                                <div id="poststuff">
                                    <div id="post-body" class="metabox-holder columns-2">
                                        <div id="postbox-container-1" class="postbox-container">
                                            <div class="postbox">
                                                <div class="postbox-header">
                                                    <h2><?php esc_html_e('License activated','crt-manage') ?></h2>
                                                </div>
                                                <div class="inside">
                                                    <ul>
                                                        <?php
                                                        $crt_manage_license = get_option('crt_manage_license');
                                                        if(!empty($crt_manage_license)) :
                                                            $licenses = json_decode($crt_manage_license);
                                                            foreach ($licenses as $item_license) {
                                                                $date = get_option($item_license . '_date');
                                                                $name = get_option($item_license . '_name');
                                                                $key = get_option($item_license . '_key');
                                                                $key_limit = get_option($item_license . '_key_limit');
                                                                $key_usage = get_option($item_license . '_key_usage');
                                                                if($date != '') {
                                                                    $date = strtotime($date);
                                                                    $date = date( 'd/m/Y H:i:s', $date);
                                                                } else {
                                                                    $date = 'Never';
                                                                }
                                                                ?>
                                                                <li>
                                                                    <h3><?php echo esc_html($item_license); ?></h3>
                                                                    <?php if($key): ?><p><code><?php echo esc_html($key); ?></code></p><?php endif; ?>
                                                                    <?php if($key_limit): ?><p><?php esc_html_e('Usage:','crt-manage') ?> <?php echo esc_html($key_usage . '/' . $key_limit); ?></p><?php endif; ?>
                                                                    <p><?php esc_html_e('Expires:','crt-manage') ?> <?php echo esc_html($date) ?></p>
                                                                    <?php if($name): ?><p><?php esc_html_e('Option:','crt-manage') ?> <?php echo esc_html($name); ?></p><?php endif; ?>
                                                                </li>
                                                                <?php
                                                            }
                                                        endif;
                                                        ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="postbox-container-2" class="postbox-container">
                                            <div class="postbox">
                                                <div class="inside">
                                                    <table class="form-table">
                                                        <tr style="vertical-align:top">
                                                            <th style="vertical-align:top">
                                                                <label><?php esc_html_e('License:','crt-manage') ?></label>
                                                            </th>
                                                            <td>
                                                                <p class="form-field form-field-wide">
                                                                    <input class="regular-text crt-field-license" type="text" placeholder="<?php echo esc_attr('Active Code'); ?>" />
                                                                </p>
                                                                <p class="form-field form-field-wide">
                                                                    <?php echo '<a href="#" class="button button-primary crt-btn-license">'.esc_html__('Active','crt-manage').'</a>'; ?>
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if($active_tab == 'crt_tab_settings'): ?>
                                <h3><?php esc_html_e('Optimizers', 'crt-manage'); ?></h3>
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('WP Rocket JS', 'crt-manage'); ?>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="crt_ignore_wp_rocket_js" id="crt_ignore_wp_rocket_js" <?php echo checked( get_option('crt_ignore_wp_rocket_js', 'on'), 'on', false ); ?>>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('WP Optimize JS', 'crt-manage'); ?>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="crt_ignore_wp_optimize_js" id="crt_ignore_wp_optimize_js" <?php echo checked( get_option('crt_ignore_wp_optimize_js', 'on'), 'on', false ); ?>>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('WP Optimize CSS', 'crt-manage'); ?>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="crt_ignore_wp_optimize_css" id="crt_ignore_wp_optimize_css" <?php echo checked( get_option('crt_ignore_wp_optimize_css', 'on'), 'on', false ); ?>>
                                        </td>
                                    </tr>
                                </table>

                                <h3><?php esc_html_e('General', 'crt-manage'); ?></h3>
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('MailChimp API Key', 'crt-manage'); ?>
                                            <br/><a class="crt-text-small-size" href="https://mailchimp.com/help/about-api-keys/" target="_blank"><?php esc_html_e( 'How to get MailChimp API Key?', 'crt-manage' ); ?></a>
                                        </th>
                                        <td>
                                            <input type="text" name="crt_mailchimp_api_key" value="<?php echo esc_attr( get_option('crt_mailchimp_api_key') ); ?>" class="regular-text" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Recaptcha V3 Site Key', 'crt-manage'); ?></th>
                                        <td>
                                            <input type="text" name="crt_recaptcha_v3_site_key" value="<?php echo esc_attr( get_option('crt_recaptcha_v3_site_key') ); ?>" class="regular-text" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Recaptcha V3 Secret Key', 'crt-manage'); ?></th>
                                        <td>
                                            <input type="text" name="crt_recaptcha_v3_secret_key" value="<?php echo esc_attr( get_option('crt_recaptcha_v3_secret_key') ); ?>" class="regular-text" />
                                        </td>
                                    </tr>
                                </table>
                                <h3><?php esc_html_e('Woocommerce', 'crt-manage'); ?></h3>
                                <table class="form-table">
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('Woocommerce Config', 'crt-manage'); ?>
                                            <br/><span class="crt-text-small-size"><?php esc_html_e('Below options work only if this option is enabled', 'crt-manage'); ?></span>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="crt_override_woo_templates" id="crt_override_woo_templates" <?php echo checked( get_option('crt_override_woo_templates', 'on'), 'on', false ); ?>>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('Cart', 'crt-manage'); ?>
                                            <br/><span class="crt-text-small-size"><?php esc_html_e('Overrides Default Cart Template', 'crt-manage'); ?></span>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="crt_override_woo_cart" id="crt_override_woo_cart" <?php echo checked( get_option('crt_override_woo_cart', 'on'), 'on', false ); ?>>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row">
                                            <?php esc_html_e('Mini Cart', 'crt-manage'); ?>
                                            <br/><span class="crt-text-small-size"><?php esc_html_e('Overrides Default Mini Cart Template', 'crt-manage'); ?></span>
                                        </th>
                                        <td>
                                            <input type="checkbox" name="crt_override_woo_mini_cart" id="crt_override_woo_mini_cart" <?php echo checked( get_option('crt_override_woo_mini_cart', 'on'), 'on', false ); ?>>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('WooCommerce Shop Posts Per Page', 'crt-manage'); ?></th>
                                        <td>
                                            <input type="number" name="crt_woo_shop_ppp" value="<?php echo esc_attr( get_option('crt_woo_shop_ppp', 9) ); ?>" class="small-text" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('WooCommerce Shop Category Posts Per Page', 'crt-manage'); ?></th>
                                        <td>
                                            <input type="number" name="crt_woo_shop_cat_ppp" value="<?php echo esc_attr( get_option('crt_woo_shop_cat_ppp', 9) ); ?>" class="small-text" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('WooCommerce Shop Tag Posts Per Page', 'crt-manage'); ?></th>
                                        <td>
                                            <input type="number" name="crt_woo_shop_tag_ppp" value="<?php echo esc_attr( get_option('crt_woo_shop_tag_ppp', 9) ); ?>" class="small-text" />
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Compare Page', 'crt-manage'); ?></th>
                                        <td>
                                            <?php
                                            wp_dropdown_pages( array(
                                                'name' => 'crt_compare_page',
                                                'echo' => 1,
                                                'show_option_none' => __( '&mdash; Select &mdash;' ),
                                                'option_none_value' => '0',
                                                'selected' => get_option('crt_compare_page')
                                            ) );
                                            ?>
                                        </td>
                                    </tr>
                                    <tr valign="top">
                                        <th scope="row"><?php esc_html_e('Wishlist Page', 'crt-manage'); ?></th>
                                        <td>
                                            <?php
                                            wp_dropdown_pages( array(
                                                'name' => 'crt_wishlist_page',
                                                'echo' => 1,
                                                'show_option_none' => __( '&mdash; Select &mdash;' ),
                                                'option_none_value' => '0',
                                                'selected' => get_option('crt_wishlist_page')
                                            ) );
                                            ?>
                                        </td>
                                    </tr>
                                </table>
                                <?php submit_button(); ?>
                            <?php endif; ?>
                        </form>
                    </div>
                </div>
                <?php
            }
        }
    }

    public static function instance() {
        if (empty(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function includes() {
        require_once CRT_MANAGE_DIR . '/includes/customizer/functions.php';
        require_once CRT_MANAGE_DIR . '/includes/customizer/customizer.php';
        require_once CRT_MANAGE_DIR . '/includes/customizer/users.php';
        require_once CRT_MANAGE_DIR . '/includes/cmb2/init.php';
        require_once CRT_MANAGE_DIR . '/includes/widget/crt-manage-author-widgets.php';

        $crt_manage_meta_box = CRT_MANAGE_DIR . '/includes/metabox/'.$this->crt_manage_theme;
        if(file_exists($crt_manage_meta_box)) {
            require_once $crt_manage_meta_box . '/metabox.php';
        }

        if(!class_exists( 'OCDI_Plugin' )) {
            require_once CRT_MANAGE_DIR . '/includes/ocdi/ocdi/ocdi.php';
        }
        require_once CRT_MANAGE_DIR . '/includes/ocdi/init.php';
    }

    public function addons_includes() {
        require_once CRT_MANAGE_DIR . '/includes/customizer/addons/addons.php';
        require_once CRT_MANAGE_DIR . '/includes/customizer/addons/builder.php';
        require_once CRT_MANAGE_DIR . '/includes/customizer/addons/utilities.php';
        require_once CRT_MANAGE_DIR . '/includes/cmb2/init.php';
    }


    public function crt_manage_theme_purchase_code() {
        $code = sanitize_text_field($_POST['code']);
        $personalToken = 'acijklmnopHIJKLMXYZ12345890!@^&*';
        $site = 'https://crthemes.com';
        $url = $site.'/wp-json/wc/v3/purchase_theme';
        $headers = array(
            'Content-Type' => 'multipart/form-data',
            'Api-Key' => $personalToken,
        );
        $data = array('code' => $code, 'site' => home_url( '/' ));
        $result = wp_remote_post($url, array('headers' => $headers, 'body' => json_encode($data)));
        $result = $result['body'];
        $result = base64_decode($result);

        $status_code = '';
        if($result === 'NOT_EXIST') {
            $status_code = 'NOT_EXIST';
            echo $status_code;
            exit();
        } elseif ($result === 'CODE_ACTIVED') {
            $status_code = 'CODE_ACTIVED';
            echo $status_code;
            exit();
        }
        $result = base64_decode($result);
        $key = explode('_', $result);
        $license_code = '';
        if(mb_strlen($key[0]) == mb_strlen($this->crt_manage_theme) || in_array($key[1], $this->crt_manage_theme_allow_used)) {
            $license_code = $key[1];
        } else {
            $status_code = 'NOT_EXIST';
            echo $status_code;
            exit();
        }

        // Update license
        $crt_manage_license = !empty(get_option('crt_manage_license')) ? json_decode(get_option('crt_manage_license')):array();
        update_option('crt_manage_license', wp_json_encode(array_merge($crt_manage_license, array($license_code))));
        $status_code = 'ACTIVE_SUCCESS';
        echo $status_code;
        exit();
    }

    public function crt_manage_theme_purchase_code_lemon() {
        $code = sanitize_text_field($_POST['code']);
        $personalToken = 'acijklmnopHIJKLMXYZ12345890!@^&*';
        $site = 'https://crthemes.com';
        $url = $site.'/wp-json/wc/v3/purchase_theme_lemon';
        $headers = array(
            'Content-Type' => 'multipart/form-data',
            'Api-Key' => $personalToken,
        );

        if(in_array($this->crt_manage_theme, $this->crt_manage_theme_allow_used)) {
            $data = array('code' => $code, 'site' => home_url( '/' ), 'theme' => $this->crt_manage_theme);
        } else {
            $data = array('code' => $code, 'site' => home_url( '/' ), 'addons' => $this->crt_addons);
        }

        $result = wp_remote_post($url, array('headers' => $headers, 'body' => json_encode($data)));
        $result = json_decode($result['body'], true);

        if(!$result['activated']) {
            echo json_encode(
                array(
                    'code' => 0,
                    'messenger' => $result['error']
                )
            );
            exit();
        }
        $key = $result['instance']['name'];
        $license_code = '';
        if(in_array($key, $this->crt_manage_theme_allow_used)) {
            $license_code = $key;
        } elseif ($key == $this->crt_addons) {
            $license_code = $key;
        } else {
            echo json_encode( array( 'code' => 0, 'messenger' => 'Not Exist' ) );
            exit();
        }
        
        // Update license
        $crt_manage_license = !empty(get_option(base64_decode('Y3J0X21hbmFnZV9saWNlbnNl'))) ? json_decode(get_option(base64_decode('Y3J0X21hbmFnZV9saWNlbnNl'))):array();
        if(!in_array($license_code, $crt_manage_license)) {
            update_option(base64_decode('Y3J0X21hbmFnZV9saWNlbnNl'), wp_json_encode (array_merge($crt_manage_license, array($license_code))));
        }
        update_option($license_code. '_key_limit', $result['license_key']['activation_limit']);
        update_option($license_code. '_key_usage', $result['license_key']['activation_usage']);
        update_option($license_code. '_key', $result['license_key']['key']);
        update_option($license_code. '_date', $result['license_key']['expires_at']);
        update_option($license_code. '_name', isset($result['meta']['variant_name']) ? $result['meta']['variant_name']:'');
        echo json_encode( array( 'code' => 1, 'messenger' => 'Active Success' ) );
        exit();
    }


    public function crt_manage_random_string($str_int = 10) {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890!@#$%^&*';
        $strings = array();
        $alphaLength = strlen($alphabet) - 1;
        for ($i = 0; $i < $str_int; $i++) {
            $n = rand(0, $alphaLength);
            $strings[] = $alphabet[$n];
        }
        return implode($strings);
    }

    public function crt_manage_admin_js() {
        wp_enqueue_script( 'crt-manage-child-js', CRT_MANAGE_URI . 'assets/js/license.js', array( 'jquery' ), CRT_MANAGE_VERSION, true );
    }

    public function crt_manage_customize_register($wp_customize) {
        require_once CRT_MANAGE_DIR . '/includes/customizer/front-page-options.php';
        $wp_customize->register_control_type( 'Crt_Manage_Customize_Select_Multiple' );
    }

    function crt_manage_add_notice_buy_theme() {
        $demos = $this->crt_manage_theme_demo[$this->crt_manage_theme];
        ?>
        <div class="notice notice-warning is-dismissible">
            <p><strong><?php esc_html_e(CRT_MANAGE_THEME_NAME) ?>: </strong><?php esc_html_e( 'You can buy the premium version, to be able to use blocks, one click demo import ... here: ', 'crt-manage' ); ?> <a href="<?php echo esc_url( CRT_MANAGE_URL_DEMO ) ?>" target="_blank" ><?php echo esc_url( CRT_MANAGE_URL_DEMO ) ?></a></p>
            <?php if(!empty($demos)): ?>
                <?php foreach ($demos as $c => $url): ?>
                    <p><?php esc_html_e( 'Demo ' .($c+1).': ', 'crt-manage' ); ?> <a href="<?php echo esc_url( $url ) ?>" target="_blank" ><?php echo esc_url( $url ) ?></a></p>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <?php
    }

    function crt_manage_front_end_scripts() {
        // Library css
        wp_register_style( 'crt-manage-lib-css-fancybox', CRT_MANAGE_URI . 'assets/css/frontend/jquery.fancybox.min.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-manage-lib-plyr-css', 'https://cdn.plyr.io/3.8.3/plyr.css', array(), CRT_MANAGE_VERSION, false );
        wp_register_style( 'crt-manage-lib-plyr-css', 'https://cdn.plyr.io/3.8.3/plyr.css', array(), CRT_MANAGE_VERSION, false );
        wp_register_style( 'crt-lightgallery-css', CRT_MANAGE_URI . 'assets/css/frontend/lightgallery/lightgallery.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-animations-css', CRT_MANAGE_URI . 'assets/css/frontend/animations/crt-animations.min.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-link-animations-css', CRT_MANAGE_URI . 'assets/css/frontend/animations/crt-link-animations.min.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-loading-animations-css', CRT_MANAGE_URI . 'assets/css/frontend/animations/loading-animations.min.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-button-animations-css', CRT_MANAGE_URI . 'assets/css/frontend/animations/button-animations.min.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-text-animations-css', CRT_MANAGE_URI . 'assets/css/frontend/animations/text-animations.min.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-air-datepicker-css', CRT_MANAGE_URI . 'assets/css/frontend/air-datepicker/air-datepicker.css', array(), CRT_MANAGE_VERSION );
        wp_register_style( 'crt-aos-css', CRT_MANAGE_URI . 'assets/css/frontend/aos/aos.min.css', array(), null );

        // Library javascript
        wp_register_script( 'crt-manage-lib-bootstrap', CRT_MANAGE_URI . 'assets/js/frontend/bootstrap.min.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-custom-woo', CRT_MANAGE_URI . 'assets/js/frontend/custom-woocommerce.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-infinite-scroll', CRT_MANAGE_URI . 'assets/js/frontend/infinite-scroll.pkgd.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-jquery-ez-plus', CRT_MANAGE_URI . 'assets/js/frontend/jquery.ez-plus.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-jquery-fancybox', CRT_MANAGE_URI . 'assets/js/frontend/jquery.fancybox.min.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-jquery-lazy', CRT_MANAGE_URI . 'assets/js/frontend/jquery.lazy.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-modernizr', CRT_MANAGE_URI . 'assets/js/frontend/modernizr-3.11.2.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-resize-sensor', CRT_MANAGE_URI . 'assets/js/frontend/resize-sensor.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-setting-sticky', CRT_MANAGE_URI . 'assets/js/frontend/setting-sticky.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-slick', CRT_MANAGE_URI . 'assets/js/frontend/slick.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-theia-sticky-sidebar', CRT_MANAGE_URI . 'assets/js/frontend/theia-sticky-sidebar.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-gsap', CRT_MANAGE_URI . 'assets/js/frontend/gsap.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-scroll-trigger', CRT_MANAGE_URI . 'assets/js/frontend/ScrollTrigger.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-scroll-smoother', CRT_MANAGE_URI . 'assets/js/frontend/ScrollSmoother.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-split-text', CRT_MANAGE_URI . 'assets/js/frontend/SplitText.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-plyr-js', 'https://cdn.plyr.io/3.8.3/plyr.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-isotope', CRT_MANAGE_URI . 'assets/js/frontend/isotope.js', array(  'jquery', 'imagesloaded' ), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-lazy', CRT_MANAGE_URI . 'assets/js/frontend/lazy-lib.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-manage-lib-woo', CRT_MANAGE_URI . 'assets/js/frontend/custom-woocommerce.js', array(), CRT_MANAGE_VERSION, true );
        wp_register_script( 'crt-lightgallery', CRT_MANAGE_URI . 'assets/js/frontend/lightgallery/lightgallery.js', array( 'jquery' ), '1.6.12', true  );
        wp_register_script( 'crt-perfect-scroll-js', CRT_MANAGE_URI .'assets/js/frontend/perfect-scrollbar/perfect-scrollbar.min.js',  array( 'jquery' ), '0.4.9' );
        wp_register_script( 'crt-table-to-excel-js', CRT_MANAGE_URI .'assets/js/frontend/tableToExcel/tableToExcel.js',  array(  ), '', true );
        wp_register_script( 'crt-marquee', CRT_MANAGE_URI .'assets/js/frontend/marquee/marquee.min.js',  array( 'jquery' ), '1.0' );
        wp_register_script( 'crt-air-datepicker', CRT_MANAGE_URI .'assets/js/frontend/air-datepicker/air-datepicker.js',  array( 'jquery' ), '1.0' );
        wp_register_script( 'crt-lottie', CRT_MANAGE_URI .'assets/js/frontend/lottie/lottie.min.js',  array( ), '1.0' );
        wp_register_script( 'crt-popup', CRT_MANAGE_URI .'assets/js/frontend/modal-popups.js',  array( 'jquery' ), CRT_MANAGE_VERSION );
        wp_register_script( 'crt-infinite-scroll', CRT_MANAGE_URI .'assets/js/frontend/infinite-scroll/infinite-scroll.min.js',  array( 'jquery' ), '3.0.5' );
        wp_register_script( 'crt-aos-js', CRT_MANAGE_URI .'assets/js/frontend/aos/aos.min.js',  array( ), null );
        wp_register_script( 'crt-charts-js', CRT_MANAGE_URI .'assets/js/frontend/charts/charts.min.js',  array( ), '3.7.0', true );
        wp_register_script( 'crt-google-maps-clusters', CRT_MANAGE_URI .'assets/js/frontend/gmap/markerclusterer.min.js',  array( ), '1.0.3', true );
        wp_register_script( 'jquery-event-move', CRT_MANAGE_URI .'assets/js/frontend/jquery-event-move/jquery.event.move.min.js',  array( ), '2.0', true );

        wp_localize_script( 'crt-manage-lib-lazy', 'CRT_MANAGE', array( 'URI' => CRT_MANAGE_URI ) );
    }


}