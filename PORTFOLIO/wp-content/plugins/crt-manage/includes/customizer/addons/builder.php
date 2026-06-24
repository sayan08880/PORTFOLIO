<?php

use Elementor\Controls_Manager;
use CrtAddons\Classes\Utilities;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use CrtAddons\Plugin;

/**
* Class For Builder
*/
class CRT_Manage_Builder{

    function __construct(){

        add_action( 'admin_init', array( $this, 'admin_init' ) );
        add_action( 'admin_menu', array( $this, 'register_settings_menus' ) );

        add_action( 'init', array( $this,'post_type' ) ,0 );

        add_action( 'elementor/frontend/after_enqueue_scripts', array( $this,'widget_scripts' ) );
        add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_inner_panel_scripts' ), 988 );
        add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_panel_styles' ], 988 );
        add_action( 'elementor/editor/after_enqueue_scripts', [ $this, 'enqueue_panel_scripts' ], 988 );

        add_action( 'admin_enqueue_scripts', array( $this, 'crt_manage_addons_js' ) );

        add_action( 'wp_ajax_crt_manage_action_page_type', array( $this, 'crt_manage_action_page_type') );

        add_action( 'save_post', array( $this, 'crt_manage_save_page_type' ) );

        add_action( 'add_meta_boxes', array($this , 'crt_manage_archive_meta_box') );

        add_filter( 'single_template', array( $this, 'load_canvas_template' ) );

        add_action( 'wp', [ $this, 'global_compatibility' ] );

        add_filter( 'template_include', [ $this, 'convert_to_canvas' ], 12 ); // 12 after WP Pages and WooCommerce.
        add_action( 'elementor/page_templates/canvas/crt_print_content', array( $this, 'canvas_page_content_display' ) );
        add_action( 'admin_enqueue_scripts', [ $this, 'templates_library_scripts' ] );
        add_filter( 'display_post_states', array($this, 'crt_display_post_states'), 10, 2 );
        add_filter( 'post_row_actions', array($this, 'crt_add_action'), 12, 3);

        add_action( 'cmb2_admin_init', array($this, 'crt_manage_page_metabox') );
        add_filter('upload_mimes', array($this, 'crt_manage_add_file_types_to_uploads'));

        $this->register_megamenu_route();
    }

    /**
     * Define the metabox and field configurations.
     */

    public function admin_init() {
        register_setting( 'crt_manage_settings', 'crt_ignore_wp_rocket_js' );
        register_setting( 'crt_manage_settings', 'crt_ignore_wp_optimize_js' );
        register_setting( 'crt_manage_settings', 'crt_ignore_wp_optimize_css' );
        register_setting( 'crt_manage_settings', 'crt_google_map_api_key' );
        register_setting( 'crt_manage_settings', 'crt_google_map_language' );
        register_setting( 'crt_manage_settings', 'crt_mailchimp_api_key' );
        register_setting( 'crt_manage_settings', 'crt_recaptcha_v3_site_key' );
        register_setting( 'crt_manage_settings', 'crt_recaptcha_v3_secret_key' );
        register_setting( 'crt_manage_settings', 'crt_woo_shop_ppp' );
        register_setting( 'crt_manage_settings', 'crt_woo_shop_cat_ppp' );
        register_setting( 'crt_manage_settings', 'crt_woo_shop_tag_ppp' );
        register_setting( 'crt_manage_settings', 'crt_compare_page' );
        register_setting( 'crt_manage_settings', 'crt_wishlist_page' );
    }

    public function crt_manage_page_metabox() {

        $prefix = 'crt_manage_page_metabox_';

        $crt_manage_post_setting = new_cmb2_box( array(
            'id'            => $prefix . 'settings',
            'title'         => __( 'Header', 'crt-manage' ),
            'object_types'  => array( 'page', 'post' ),
        ) );

        $headers = $this->crt_manage_header_choose_option(array('header-theme' => 'Header of theme'), 'Builder - ');

        $crt_manage_post_setting->add_field( array(
            'name'             => 'Template Header',
            'id'               => $prefix . 'header_template',
            'type'             => 'select',
            'show_option_none' => true,
            'default'          => 'header-theme',
            'options'          => $headers,
        ) );
    }

    public function templates_library_scripts() {
        // enqueue CSS
        wp_enqueue_style( 'crt-plugin-options-css', CRT_MANAGE_URI.'/assets/css/admin/plugin-options.css', [], CRT_MANAGE_VERSION );

    }

    public function crt_manage_addons_js() {
        wp_enqueue_script( 'crt-manage-addons-script', CRT_MANAGE_URI . 'assets/js/addons-script.js', array( 'jquery' ), CRT_MANAGE_VERSION, true );
    }

    public function crt_manage_action_page_type() {
        $post_id = sanitize_text_field($_POST['post_id']);
        $page_type = sanitize_text_field($_POST['page']);

        foreach (Utilities::PT_DATA as $key => $label) {
            update_post_meta( $post_id, 'crt-' . $key, false );
        }
        update_post_meta($post_id, 'crt-' . $page_type, true);
        echo json_encode(
            array(
                'code' => 1,
                'messenger' => 'Updated'
            )
        );
        exit();
    }

    public function widget_scripts( ) {
        wp_enqueue_script( 'crt-addons-core',CRT_MANAGE_URI.'/assets/js/addons-core.js',array( 'jquery' ),CRT_MANAGE_VERSION,true );
        wp_enqueue_script( 'crt-header',CRT_MANAGE_URI.'assets/js/modules/crt-header.js',array( 'jquery'),CRT_MANAGE_VERSION,true );
        wp_enqueue_style( 'crt-addons-core-css',CRT_MANAGE_URI . 'assets/css/frontend.css',array( ),CRT_MANAGE_VERSION );

        if ( \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
            wp_enqueue_style(
                'crt-addons-library-frontend-css',
                CRT_MANAGE_URI . 'assets/css/admin/library-frontend.min.css',
                [],
                CRT_MANAGE_VERSION
            );

        }

        wp_localize_script(
            'crt-addons-core',
            'CRTConfig', // This is used in the js file to group all of your scripts together
            [
                'ajaxurl' => admin_url( 'admin-ajax.php' ),
                'resturl' => get_rest_url() . 'crtaddons/v1',
                'nonce' => wp_create_nonce( 'crt-addons-js' ),
                'addedToCartText' => esc_html__('was added to cart', 'crt-manage'),
                'viewCart' => esc_html__('View Cart', 'crt-manage'),
                'comparePageID' => get_option('crt_compare_page'),
                'comparePageURL' => get_permalink(get_option('crt_compare_page')),
                'wishlistPageID' => get_option('crt_wishlist_page'),
                'wishlistPageURL' => get_permalink(get_option('crt_wishlist_page')),
                'chooseQuantityText' => esc_html__('Please select the required number of items.', 'crt-manage'),
                'site_key' => get_option('crt_recaptcha_v3_site_key'),
                'is_admin' => current_user_can('manage_options'),
                'input_empty' => esc_html__('Please fill out this field', 'crt-manage'),
                'select_empty' => esc_html__('Nothing selected', 'crt-manage'),
                'file_empty' => esc_html__('Please upload a file', 'crt-manage'),
                'recaptcha_error' => esc_html__('Recaptcha Error', 'crt-manage'),
                'woo_shop_ppp' => get_option('crt_woo_shop_ppp', 9),
                'woo_shop_cat_ppp' => get_option('crt_woo_shop_cat_ppp', 9),
                'woo_shop_tag_ppp' => get_option('crt_woo_shop_tag_ppp', 9),
                'is_product_category' => function_exists('is_product_category') ? is_product_category() : false,
                'is_product_tag' => function_exists('is_product_tag') ? is_product_tag() : false,
                'sticky_section' => $this->crt_get_extension_option( 'crt-sticky-section' ),

                // 'token' => $custom_token
            ]
        );
    }

    public function enqueue_panel_scripts() {

        wp_enqueue_script( 'crt-addons-editor-js',CRT_MANAGE_URI.'/assets/js/admin/editor.min.js',array( 'jquery', 'wp-i18n' ),CRT_MANAGE_VERSION,true );
        $args = ['nonce' => wp_create_nonce( 'crt-addons-editor-js' ), 'adminURL' => admin_url(), 'isWooCommerceActive' => class_exists('WooCommerce') ? true : false];
        $args = array_merge($args, \CrtAddons\Classes\Utilities::get_registered_modules());

        wp_localize_script(
            'crt-addons-editor-js',
            'registered_modules',
            $args
        );
    }

    public function enqueue_inner_panel_scripts() {
        wp_enqueue_script( 'crt-macy-js',CRT_MANAGE_URI.'/assets/js/admin/macy/macy.js',array(  ),'3.0.6',true );

        wp_enqueue_script( 'crt-addons-library-frontend-js',CRT_MANAGE_URI.'/assets/js/admin/library-frontend.min.js',array( 'jquery', 'crt-macy-js'  ),CRT_MANAGE_VERSION,true );
        wp_localize_script( 'crt-addons-library-frontend-js', 'white_label', [ 'logo_url' => !empty(get_option('crt_wl_plugin_logo')) ? esc_url(wp_get_attachment_image_src(get_option('crt_wl_plugin_logo'), 'full')[0]) : CRT_MANAGE_URI .'assets/img/logo-256x256.png' ] );
        wp_localize_script( 'crt-addons-library-frontend-js', 'CrtLibFrontLoc', [ 'nonce' => wp_create_nonce('crt-addons-library-frontend-js') ] );

        wp_enqueue_script( 'crt-addons-library-editor-js',CRT_MANAGE_URI.'/assets/js/admin/library-editor.min.js',array( 'jquery' ),CRT_MANAGE_VERSION,true );

    }

    public function enqueue_panel_styles() {
        wp_enqueue_style(
            'crt-addons-library-editor-css',
            CRT_MANAGE_URI . 'assets/css/admin/editor.min.css',
            [],
            CRT_MANAGE_VERSION
        );
    }


    public function crt_manage_add_elementor_page_settings_controls( \Elementor\Core\DocumentTypes\Post $page ){
        $post_type = get_post_type( get_the_ID() );
        if($post_type == 'crt_manage_archive') {
            $this->crt_control_archive($page);
        } elseif ($post_type == 'crt_manage_header') {
            $this->crt_control_header($page);
        }

    }

    public function crt_control_archive($page) {


        $post_types = Utilities::get_custom_types_of( 'post', false );

        $post_taxonomies = Utilities::get_custom_types_of( 'tax', false );

        $default_archives = [
            'archive/posts' => esc_html__( 'Posts Archive', 'crt-manage' ),
            'product_archive/products' => esc_html__( 'Products Archive', 'crt-manage' ),
            'product_archive/product_search' => esc_html__('Products Search', 'crt-manage'),
            'archive/author' => esc_html__( 'Author Archive', 'crt-manage' ),
            'archive/date' => esc_html__( 'Date Archive', 'crt-manage' ),
            'archive/search' => esc_html__( 'Search Results', 'crt-manage' ),
        ];

        $taxonomy_archives = $post_taxonomies;

        // Add CPT to Default Archives
        foreach ($post_types as $post_type => $value) {
            if ( 'post' === $post_type || 'page' === $post_type || 'e-landing-page' === $post_type ) {
                continue;
            }

            $default_archives['archive/'. $post_type] = $value .' '. esc_html__( 'Archive', 'crt-manage' );
        }

        $page->start_controls_section(
            'preview_settings',
            [
                'label' => esc_html__( 'Preview Settings', 'crt-manage' ),
                'tab'   => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $id = get_the_ID();
        $query = 'archive/author';

        $page->add_control(
            'preview_source',
            [
                'label' => esc_html__( 'Preview Source', 'crt-manage' ),
                'type' => Controls_Manager::SELECT,
                'default' => $query,
                'groups' => [
                    'archive' => [
                        'label' => __( 'Archives', 'crt-manage' ),
                        'options' => $default_archives + $taxonomy_archives,
                    ],
                    'single' => [
                        'label' => __( 'Singular', 'crt-manage' ),
                        'options' => $post_types
                    ],
                ],
            ]
        );

        $wp_users = Utilities::get_users();
        reset($wp_users);
        $first_user_id = key($wp_users);

        $page->add_control(
            'preview_archive_author',
            [
                'label' => esc_html__( 'Select Author', 'crt-manage' ),
                'type' => Controls_Manager::SELECT2,
                'options' => Utilities::get_users(),
                'default' => $first_user_id,
                'separator' => 'before',
                'condition' => [
                    'preview_source' => 'archive/author'
                ]
            ]
        );

        $page->add_control(
            'preview_archive_search',
            [
                'label' => esc_html__( 'Search Keyword', 'crt-manage' ),
                'type' => Controls_Manager::TEXT,
                'separator' => 'before',
                'default' => 'a',
                'condition' => [
                    'preview_source' => 'archive/search',
                ]
            ]
        );

        $page->add_control(
            'review_changes',
            [
                'type' => Controls_Manager::RAW_HTML,
                'raw' => '<div class="elementor-update-preview-button editor-crt-preview-update"><span>Click the Publish Button, then Click the Apply Button</span><button class="elementor-button elementor-button-success" onclick="elementor.reloadPreview();">Apply</button></div>',
                'separator' => 'after'
            ]
        );

        $page->end_controls_section();
    }

    public function crt_control_header($page) {
        $page->start_controls_section(
            'crt_manage_header_option',
            [
                'label'     => __( 'Header Option', 'crt-manage' ),
                'tab'       => Controls_Manager::TAB_SETTINGS,
            ]
        );

        $page->add_control(
            'crt_manage_header_style',
            [
                'label'     => __( 'Header Option', 'crt-manage' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'prebuilt'             => __( 'Pre Built', 'crt-manage' ),
                    'header_builder'       => __( 'Header Builder', 'crt-manage' ),
                ],
                'default'   => 'prebuilt',
            ]
        );

        $page->add_control(
            'crt_manage_header_builder_option',
            [
                'label'     => __( 'Header Name', 'crt-manage' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $this->crt_manage_header_choose_option(),
                'condition' => [ 'crt_manage_header_style' => 'header_builder'],
                'default'	=> ''
            ]
        );

        $page->end_controls_section();

        $page->start_controls_section(
            'crt_manage_footer_option',
            [
                'label'     => __( 'Footer Option', 'crt-manage' ),
                'tab'       => Controls_Manager::TAB_SETTINGS,
            ]
        );
        $page->add_control(
            'crt_manage_footer_choice',
            [
                'label'         => __( 'Enable Footer?', 'crt-manage' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Yes', 'crt-manage' ),
                'label_off'     => __( 'No', 'crt-manage' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );
        $page->add_control(
            'crt_manage_footer_style',
            [
                'label'     => __( 'Footer Style', 'crt-manage' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => [
                    'prebuilt'             => __( 'Pre Built', 'crt-manage' ),
                    'footer_builder'       => __( 'Footer Builder', 'crt-manage' ),
                ],
                'default'   => 'prebuilt',
                'condition' => [ 'crt_manage_footer_choice' => 'yes' ],
            ]
        );
        $page->add_control(
            'crt_manage_footer_builder_option',
            [
                'label'     => __( 'Footer Name', 'crt-manage' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $this->crt_manage_footer_choose_option(),
                'condition' => [ 'crt_manage_footer_style' => 'footer_builder','crt_manage_footer_choice' => 'yes' ],
                'default'	=> ''
            ]
        );

        $page->end_controls_section();
    }

    public function register_settings_menus(){
        add_menu_page( esc_html__( 'CRT Addons', 'crt-manage' ), esc_html__( 'CRT Addons', 'crt-manage' ), 'manage_options', 'crt-manage', [ $this, 'crt_manage_render'], 'dashicons-admin-site', '58.6' );
        add_submenu_page('crt-manage', esc_html__('Header Builder', 'crt-manage'), esc_html__('Header Builder', 'crt-manage'), 'manage_options', 'edit.php?post_type=crt_manage_header');
        add_submenu_page('crt-manage', esc_html__('Archive Builder', 'crt-manage'), esc_html__('Archive Builder', 'crt-manage'), 'manage_options', 'edit.php?post_type=crt_manage_archive');
        add_submenu_page('crt-manage', esc_html__('Footer Builder', 'crt-manage'), esc_html__('Footer Builder', 'crt-manage'), 'manage_options', 'edit.php?post_type=crt_manage_footer');
        add_submenu_page('crt-manage', esc_html__('Template Builder', 'crt-manage'), esc_html__('Template Builder', 'crt-manage'), 'manage_options', 'edit.php?post_type=crt_manage_template');
        add_submenu_page('crt-manage', esc_html__('Popup Builder', 'crt-manage'), esc_html__('Popup Builder', 'crt-manage'), 'manage_options', 'edit.php?post_type=crt_manage_popup');
//        add_submenu_page(
//            'crt-manage',
//            esc_html__('License','crt-manage'),
//            esc_html__('License','crt-manage'),
//            'manage_options',
//            'crt-manage-license',
//            array( $this,'crt_manage_render' ),
//        );


    }

    // Callback Function
    public function register_settings_contents_settings(){

        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'crt_tab_settings';
    ?>

    <?php
        echo '<h2>';
            echo esc_html__( 'Welcome To Header And Footer Builder Of This Theme','crt-manage' );
        echo '</h2>';
    }

    public function post_type() {

        $labels = array(
            'name'               => __( 'Footer', 'crt-manage' ),
            'singular_name'      => __( 'Footer', 'crt-manage' ),
            'menu_name'          => __( 'CRT Footer Builder', 'crt-manage' ),
            'name_admin_bar'     => __( 'Footer', 'crt-manage' ),
            'add_new'            => __( 'Add New', 'crt-manage' ),
            'add_new_item'       => __( 'Add New Footer', 'crt-manage' ),
            'new_item'           => __( 'New Footer', 'crt-manage' ),
            'edit_item'          => __( 'Edit Footer', 'crt-manage' ),
            'view_item'          => __( 'View Footer', 'crt-manage' ),
            'all_items'          => __( 'All Footer', 'crt-manage' ),
            'search_items'       => __( 'Search Footer', 'crt-manage' ),
            'parent_item_colon'  => __( 'Parent Footer:', 'crt-manage' ),
            'not_found'          => __( 'No Footer found.', 'crt-manage' ),
            'not_found_in_trash' => __( 'No Footer found in Trash.', 'crt-manage' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'rewrite'             => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'elementor' ),
        );

        register_post_type( 'crt_manage_footer', $args );

        $labels = array(
            'name'               => __( 'Header', 'crt-manage' ),
            'singular_name'      => __( 'Header', 'crt-manage' ),
            'menu_name'          => __( 'CRT Header Builder', 'crt-manage' ),
            'name_admin_bar'     => __( 'Header', 'crt-manage' ),
            'add_new'            => __( 'Add New', 'crt-manage' ),
            'add_new_item'       => __( 'Add New Header', 'crt-manage' ),
            'new_item'           => __( 'New Header', 'crt-manage' ),
            'edit_item'          => __( 'Edit Header', 'crt-manage' ),
            'view_item'          => __( 'View Header', 'crt-manage' ),
            'all_items'          => __( 'All Header', 'crt-manage' ),
            'search_items'       => __( 'Search Header', 'crt-manage' ),
            'parent_item_colon'  => __( 'Parent Header:', 'crt-manage' ),
            'not_found'          => __( 'No Header found.', 'crt-manage' ),
            'not_found_in_trash' => __( 'No Header found in Trash.', 'crt-manage' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'rewrite'             => false,
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'supports'            => array( 'title', 'elementor' ),
        );

        register_post_type( 'crt_manage_header', $args );

        $labels = array(
            'name'               => __( 'Archive', 'crt-manage' ),
            'singular_name'      => __( 'Archive', 'crt-manage' ),
            'menu_name'          => __( 'Archive Builder', 'crt-manage' ),
            'name_admin_bar'     => __( 'Archive', 'crt-manage' ),
            'add_new'            => __( 'Add New', 'crt-manage' ),
            'add_new_item'       => __( 'Add New Archive', 'crt-manage' ),
            'new_item'           => __( 'New Archive', 'crt-manage' ),
            'edit_item'          => __( 'Edit Archive', 'crt-manage' ),
            'view_item'          => __( 'View Archive', 'crt-manage' ),
            'all_items'          => __( 'All Archive', 'crt-manage' ),
            'search_items'       => __( 'Search Archive', 'crt-manage' ),
            'parent_item_colon'  => __( 'Parent Archive:', 'crt-manage' ),
            'not_found'          => __( 'No Archive found.', 'crt-manage' ),
            'not_found_in_trash' => __( 'No Archive found in Trash.', 'crt-manage' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'rewrite'             => false,
//            'rewrite'            => array('slug' => 'manage-archive'),
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'has_archive'        => true,
            'supports'            => array( 'title', 'elementor' ),
        );

        register_post_type( 'crt_manage_archive', $args );

        $labels = array(
            'name'               => __( 'Popup', 'crt-manage' ),
            'singular_name'      => __( 'Popup', 'crt-manage' ),
            'menu_name'          => __( 'Popup Builder', 'crt-manage' ),
            'name_admin_bar'     => __( 'Popup', 'crt-manage' ),
            'add_new'            => __( 'Add New', 'crt-manage' ),
            'add_new_item'       => __( 'Add New Popup', 'crt-manage' ),
            'new_item'           => __( 'New Popup', 'crt-manage' ),
            'edit_item'          => __( 'Edit Template', 'crt-manage' ),
            'view_item'          => __( 'View Popup', 'crt-manage' ),
            'all_items'          => __( 'All Popup', 'crt-manage' ),
            'search_items'       => __( 'Search Popup', 'crt-manage' ),
            'parent_item_colon'  => __( 'Parent Popup:', 'crt-manage' ),
            'not_found'          => __( 'No Popup found.', 'crt-manage' ),
            'not_found_in_trash' => __( 'No Popup found in Trash.', 'crt-manage' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'rewrite'             => false,
//            'rewrite'            => array('slug' => 'manage-archive'),
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'has_archive'        => true,
            'supports'            => array( 'title', 'elementor' ),
        );

        register_post_type( 'crt_manage_popup', $args );

        $labels = array(
            'name'               => __( 'Template', 'crt-manage' ),
            'singular_name'      => __( 'Template', 'crt-manage' ),
            'menu_name'          => __( 'Template Builder', 'crt-manage' ),
            'name_admin_bar'     => __( 'Template', 'crt-manage' ),
            'add_new'            => __( 'Add New', 'crt-manage' ),
            'add_new_item'       => __( 'Add New Template', 'crt-manage' ),
            'new_item'           => __( 'New Template', 'crt-manage' ),
            'edit_item'          => __( 'Edit Template', 'crt-manage' ),
            'view_item'          => __( 'View Template', 'crt-manage' ),
            'all_items'          => __( 'All Template', 'crt-manage' ),
            'search_items'       => __( 'Search Template', 'crt-manage' ),
            'parent_item_colon'  => __( 'Parent Template:', 'crt-manage' ),
            'not_found'          => __( 'No Template found.', 'crt-manage' ),
            'not_found_in_trash' => __( 'No Template found in Trash.', 'crt-manage' ),
        );

        $args = array(
            'labels'              => $labels,
            'public'              => true,
            'rewrite'             => false,
//            'rewrite'            => array('slug' => 'manage-archive'),
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'exclude_from_search' => true,
            'capability_type'     => 'post',
            'hierarchical'        => false,
            'has_archive'        => true,
            'supports'            => array( 'title', 'elementor' ),
        );

        register_post_type( 'crt_manage_template', $args );
    }

    function load_canvas_template( $single_template ) {

        global $post;

        if ( 'crt_manage_footer' == $post->post_type || 'crt_manage_header' == $post->post_type ) {

            $elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

            if ( file_exists( $elementor_2_0_canvas ) ) {
                return $elementor_2_0_canvas;
            } else {
                return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
            }
        }

        return $single_template;
    }

    public function crt_manage_footer_choose_option($custom_label = array('' => 'Select a Footer'), $prefix = ''){

        $ambrox_post_query = new WP_Query( array(
            'post_type'			=> 'crt_manage_footer',
            'posts_per_page'	    => -1,
        ) );

        $crt_builder_post_title = array();
        $crt_builder_post_title = $custom_label;

        while( $ambrox_post_query->have_posts() ) {
            $ambrox_post_query->the_post();
            $crt_builder_post_title[ get_the_ID() ] =  $prefix . get_the_title();
        }
        wp_reset_postdata();

        return $crt_builder_post_title;

    }

    public function crt_manage_header_choose_option($custom_label = array('' => 'Select Header'), $prefix = ''){

        $crt_builder_post_query = new WP_Query( array(
            'post_type'			=> 'crt_manage_header',
            'posts_per_page'	    => -1,
        ) );

        $crt_builder_post_title = array();
        $crt_builder_post_title = $custom_label;

        while( $crt_builder_post_query->have_posts() ) {
            $crt_builder_post_query->the_post();
            $crt_builder_post_title[ get_the_ID() ] = $prefix . get_the_title();
        }
        wp_reset_postdata();
        return $crt_builder_post_title;
    }

    public function crt_manage_render(){
//        $active_tab = $_GET['tab'];
        $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'crt_tab_settings';
        ?>
        <div class="wrap crt-settings-page-wrap">
            <div class="crt-settings-page-header">
                <h1><?php echo esc_html('CRT Addons'); ?></h1>
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
                                <?php esc_html_e('Google Map API Key', 'crt-manage'); ?>
                                <br/><a class="crt-text-small-size" href="https://www.youtube.com/watch?v=hsNlz7-abd0" target="_blank"><?php esc_html_e( 'How to get Google Map API Key?', 'crt-manage' ); ?></a>
                            </th>
                            <td>
                                <input type="text" name="crt_google_map_api_key" value="<?php echo esc_attr( get_option('crt_google_map_api_key') ); ?>" class="regular-text" />
                            </td>
                        </tr>

                        <tr valign="top">
                            <th scope="row">
                                <?php esc_html_e('Google Map Language', 'crt-manage'); ?>
                            </th>
                            <td>
                                <select name="crt_google_map_language" id="crt_google_map_language">
                                    <option value=""><?php esc_html_e('Default', 'crt-manage'); ?></option>
                                    <option value="en" <?php selected(get_option('crt_google_map_language'), 'en'); ?>><?php esc_html_e('English', 'crt-manage'); ?></option>
                                    <option value="es" <?php selected(get_option('crt_google_map_language'), 'es'); ?>><?php esc_html_e('Spanish', 'crt-manage'); ?></option>
                                    <option value="fr" <?php selected(get_option('crt_google_map_language'), 'fr'); ?>><?php esc_html_e('French', 'crt-manage'); ?></option>
                                    <option value="de" <?php selected(get_option('crt_google_map_language'), 'de'); ?>><?php esc_html_e('German', 'crt-manage'); ?></option>
                                    <option value="zh" <?php selected(get_option('crt_google_map_language'), 'zh'); ?>><?php esc_html_e('Chinese', 'crt-manage'); ?></option>
                                    <option value="ja" <?php selected(get_option('crt_google_map_language'), 'ja'); ?>><?php esc_html_e('Japanese', 'crt-manage'); ?></option>
                                    <option value="ko" <?php selected(get_option('crt_google_map_language'), 'ko'); ?>><?php esc_html_e('Korean', 'crt-manage'); ?></option>
                                    <option value="hi" <?php selected(get_option('crt_google_map_language'), 'hi'); ?>><?php esc_html_e('Hindi', 'crt-manage'); ?></option>
                                    <option value="ar" <?php selected(get_option('crt_google_map_language'), 'ar'); ?>><?php esc_html_e('Arabic', 'crt-manage'); ?></option>
                                </select>
                            </td>
                        </tr>

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

    public function global_compatibility() {
        // Work with page Default template.
        $template = get_post_meta(get_the_ID(), '_wp_page_template', true);
//        if($template == 'elementor_canvas') {
            add_action( 'get_header', array( $this, 'replace_header' ) );
            // Work with page Elementor Canvas.
            add_action( 'elementor/page_templates/canvas/before_content', array( $this, 'add_canvas_header' ) );
            add_action( 'get_footer', array( $this, 'replace_footer' ) );
            add_action( 'elementor/page_templates/canvas/after_content', array( $this, 'add_canvas_footer' ), 9 );
//        }
    }

    public function replace_header() {
        require CRT_MANAGE_DIR . 'includes/customizer/addons/templates/views/theme-header.php';
        $templates   = [];
        $templates[] = 'header.php';
        remove_all_actions( 'wp_head' ); // Avoid running wp_head hooks again.
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }

    public function replace_footer() {
        require CRT_MANAGE_DIR . 'includes/customizer/addons/templates/views/theme-footer.php';
        $templates   = [];
        $templates[] = 'footer.php';
        remove_all_actions( 'wp_footer' ); // Avoid running wp_footer hooks again.
        ob_start();
        locate_template( $templates, true );
        ob_get_clean();
    }

    public function add_canvas_footer() {
        $template_id = Utilities::crt_manage_get_header_footer_id('crt_manage_footer');
        $this->crt_render_content($template_id);
    }

    public function add_canvas_header($template) {
        $template_id = Utilities::crt_manage_get_header_footer_id('crt_manage_header');
        $this->crt_render_content($template_id);
    }

    public function crt_render_content($template_id, $has_css = true) {
        $get_elementor_content = \Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $template_id, $has_css);
        if ( '' === $get_elementor_content ) {
            return;
        }
        echo '' . $get_elementor_content;
    }

    public function convert_to_canvas( $template ) {
        $is_theme_builder_edit = \Elementor\Plugin::$instance->preview->is_preview_mode() ? true : false;
        $template_id = Utilities::canvas_page_content_display_conditions();
        if(empty($template_id) && !$is_theme_builder_edit) {
            // use template default
            return $template;
        }
        return CRT_MANAGE_DIR . 'includes/customizer/addons/templates/crt-canvas.php';
    }

    public function canvas_page_content_display($template) {
        $template_id = Utilities::canvas_page_content_display_conditions();
        if($template_id) {
            Utilities::render_elementor_template_id( $template_id );
        }
    }

    public function crt_display_post_states( $post_states, $post ) {
        if ( $post->post_type === 'crt_manage_archive' ) {

            foreach (Utilities::PT_DATA as $key => $label) {
                if ( get_post_meta( $post->ID, 'crt-' . $key, true ) ) {
                    $post_states['crt-' . $key] = $label;
                }
            }
        }
        return $post_states;
    }

    public function crt_add_action($actions, $post)
    {
        if ($post->post_type == 'crt_manage_archive') {
            $actions['archive_type'] = Utilities::crt_add_action_archive_html();
        }
        return $actions;
    }


    public function crt_manage_archive_meta_box() {
        add_meta_box(
            'crt_manage_archive_meta_box',
            'Archive Type',
            array( $this, 'crt_manage_archive_callback' ),
            'crt_manage_archive',
            'normal',
            'high'
        );
    }

    public function crt_manage_archive_callback( $post ) {
        wp_nonce_field( 'crt_archive_page_type_nonce', 'crt_archive_page_type_nonce_field' );

        $options = array();
        foreach (Utilities::PT_DATA as $key => $label) {
            $options['crt-' . $key] = $label;
        }
        echo '<select name="crt_archive_page_type" id="crt_archive_page_type">';
        foreach ( $options as $value => $label ) {
            $selected = get_post_meta( $post->ID, $value, true );
            $html_selected = '';
            if($selected) {
                $html_selected = 'selected="selected"';
            }
            echo '<option value="' . esc_attr( $value ) . '" ' . $html_selected . '>' . esc_html( $label ) . '</option>';
        }
        echo '</select>';
    }

    public function crt_manage_save_page_type( $post_id ) {
        if ( ! isset( $_POST['crt_archive_page_type_nonce_field'] ) || ! wp_verify_nonce( $_POST['crt_archive_page_type_nonce_field'], 'crt_archive_page_type_nonce' ) ) {
            return;
        }
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
        if ( isset( $_POST['crt_archive_page_type'] ) ) {
            foreach (Utilities::PT_DATA as $key => $label) {
                update_post_meta( $post_id, 'crt-' . $key, false );
            }
            update_post_meta( $post_id, $_POST['crt_archive_page_type'], true );
        }
    }

    public function crt_manage_add_file_types_to_uploads($file_types){
        $new_filetypes = array();
        $new_filetypes['svg'] = 'image/svg+xml';
        $file_types = array_merge($file_types, $new_filetypes );
        return $file_types;
    }

    private static $crt_extension_options = null;


    private function crt_get_extension_option( $key, $default = 'on' ) {
        if ( null === self::$crt_extension_options ) {
            self::$crt_extension_options = [
                'crt-particles'             => get_option( 'crt-particles', 'on' ),
                'crt-parallax-background'   => get_option( 'crt-parallax-background', 'on' ),
                'crt-parallax-multi-layer'  => get_option( 'crt-parallax-multi-layer', 'on' ),
                'crt-sticky-section'        => get_option( 'crt-sticky-section', 'on' ),
                'crt-custom-css'            => get_option( 'crt-custom-css', 'on' ),
                'crt_override_woo_templates'=> get_option( 'crt_override_woo_templates', 'on' ),
            ];
        }
        return isset( self::$crt_extension_options[ $key ] ) ? self::$crt_extension_options[ $key ] : $default;
    }

    public function register_megamenu_route() {
        add_action( 'rest_api_init', function() {
            register_rest_route(
                'crtaddons/v1',
                '/crtmegamenu/',
                [
                    'methods' => 'GET',
                    'callback' =>  [$this, 'mega_menu_ajax_loading'],
                    'permission_callback' => '__return_true'
                ]
            );
        } );
    }

    public function mega_menu_ajax_loading() {
        $elementor = \Elementor\Plugin::instance();
        $mega_id = get_post_meta( $_GET['item_id'], 'crt-mega-menu-item', true);
        $type = get_post_meta($mega_id, '_elementor_template_type', true);
        $has_css = 'internal' === get_option( 'elementor_css_print_method' ) || '' !== $type;

        $content = $elementor->frontend->get_builder_content_for_display($mega_id, $has_css);

        wp_send_json( $content );
    }


}

$builder_execute = new CRT_Manage_Builder();