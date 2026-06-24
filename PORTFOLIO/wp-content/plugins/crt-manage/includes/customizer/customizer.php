<?php
require_once dirname( __FILE__, 2 ) . '/customizer/sanitize-callback.php';
require_once dirname( __FILE__, 2 ) . '/customizer/active-callback.php';
require_once dirname( __FILE__, 2 ) . '/customizer/custom-controls.php';
require_once dirname( __FILE__, 2 ) . '/customizer/google-fonts.php';

$crt_manage_dynamic_css = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'. $this->crt_manage_theme.'/dynamic-css';
if(file_exists($crt_manage_dynamic_css)) {
    require_once $crt_manage_dynamic_css .'/dynamic-css.php';
}

function crt_manage_sections_order_section_priority( $value, $key = '' ) {
    $orders = get_theme_mod( 'sections_order' );
    if ( ! empty( $orders ) ) {
        $json = json_decode( $orders );
        if ( isset( $json->$key ) ) {
            return $json->$key;
        }
    }

    return $value;
}
add_filter( 'section_priority', 'crt_manage_sections_order_section_priority', 10, 2 );

/**
 * Function to refresh customize preview when changing sections order
 */
function crt_manage_refresh_customize_preview() {
    $section_order         = get_theme_mod( 'sections_order' ); // Edit this
    $section_order_decoded = json_decode( $section_order, true );
    if ( ! empty( $section_order_decoded ) ) {
        remove_all_actions( 'crt_manage_theme_sections' );
        foreach ( $section_order_decoded as $k => $priority ) {
            if ( function_exists( $k ) ) {
                add_action( 'crt_manage_theme_sections', $k, $priority );
            }
        }
    }
}
add_action( 'customize_preview_init', 'crt_manage_refresh_customize_preview', 1 );

add_action( 'init', 'crt_manage_refresh_customize_preview');

$crt_manage_dir_front_page = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/sections';
if(file_exists($crt_manage_dir_front_page)) {
    require_once $crt_manage_dir_front_page . '/sections.php';
}

/**
 * Enqueue script for custom customize control.
 */
function crt_manage_custom_control_scripts() {
    wp_enqueue_style( 'crt-manage-custom-controls-css', CRT_MANAGE_URI . '/assets/css/custom-controls.css', array(), '1.0', 'all' );
    wp_enqueue_script( 'crt-manage-custom-controls-js', CRT_MANAGE_URI . '/assets/js/custom-controls.js', array( 'jquery', 'jquery-ui-core', 'jquery-ui-sortable' ), CRT_MANAGE_VERSION, true );

    wp_enqueue_script( 'crt-manage-order-script', CRT_MANAGE_URI . '/assets/js/customizer-sections-order.js', array( 'jquery', 'jquery-ui-sortable' ), CRT_MANAGE_VERSION, true );
    $control_settings = array(
        'sections_container' => '#sub-accordion-panel-crt_manage_front_page_options',
        'saved_data_input'   => '#customize-control-sections_order input',
    );
    wp_localize_script( 'crt-manage-order-script', 'control_settings', $control_settings );
    wp_enqueue_style( 'crt-manage-order-style', CRT_MANAGE_URI . '/assets/css/customizer-sections-order-style.css', array( 'dashicons' ), CRT_MANAGE_VERSION );

}
add_action( 'customize_controls_enqueue_scripts', 'crt_manage_custom_control_scripts' );


/**
 * Get all posts for customizer Post content type.
 */
function crt_manage_get_post_choices($type = '') {
    $choices = array( '' );
    $args    = array( 'numberposts' => -1, 'post_type' => $type );
    $posts   = get_posts( $args );

    foreach ( $posts as $post ) {
        $id             = $post->ID;
        $title          = $post->post_title;
        $choices[ $id ] = $title;
    }

    return $choices;
}

function crt_manage_get_authors() {
    $user_ids = get_users(array('fields' => array('ID', 'display_name')));
    $users = array();
    if(!empty($user_ids)) {
        foreach ($user_ids as $user) {
            $user = get_object_vars($user);
            $users[$user['id']] = $user['display_name'];
        }
    }
    return $users;
}

/**
 * Get all pages for customizer Page content type.
 */
function crt_manage_get_page_choices() {
    $choices = array( '' );
    $pages   = get_pages();

    foreach ( $pages as $page ) {
        $choices[ $page->ID ] = $page->post_title;
    }

    return $choices;
}

/**
 * Get all categories for customizer Category content type.
 */
function crt_manage_get_post_cat_choices($tax = array('taxonomy' => 'category')) {
    $choices = array();
    $cats    = get_categories($tax);

    foreach ( $cats as $cat ) {
        $choices[ $cat->term_id ] = $cat->name;
    }

    return $choices;
}


/**
 * Get all Voucher for customize.
 */
function crt_manage_get_voucher_choices($type = '') {
    $choices = array( '' );
    $args    = array( 'numberposts' => -1, 'post_type' => $type );
    $posts   = get_posts( $args );

    foreach ( $posts as $post ) {
        $id             = $post->ID;
        $title          = $post->post_title;
        $choices[ $id ] = $title;
    }

    return $choices;
}

function crt_manage_sections_layout($number = 8, $prefix = 'layout') {
    $layouts = array();
    for($layout = 1; $layout <= $number; $layout++):
        $layouts[$prefix . '-' . $layout] = esc_html__( 'Layout ' . $layout, 'crt-manage' );
    endfor;
    return $layouts;
}

function crt_manage_sections_layout_tenzin() {
    $layouts = array();
    for($layout = 1; $layout <= 12; $layout++):
        $layouts['layout-' . $layout] = esc_html__( 'Layout ' . $layout, 'crt-manage' );
    endfor;
    return $layouts;
}

function crt_manage_sections_layout_tax_tenzin() {
    $tax_result = array();
    for($tax = 1; $tax <= 3; $tax++):
        $tax_result['tax-' . $tax] = esc_html__( 'Tax ' . $tax, 'crt-manage' );
    endfor;
    return $tax_result;
}

function crt_manage_item_on_row($number = 6, $prefix = 'Item on row') {
    $item_on_rows = array();
    for($item = 3; $item <= $number; $item++):
        $item_on_rows[$item] = esc_html__( $prefix . ' ' . $item, 'crt-manage' );
    endfor;
    return $item_on_rows;
}


function crt_manage_section_link( $section_name ) {
    if(is_customize_preview()) {
?>
    <span class="section-link"><span class="section-link-title"><?php echo esc_html( $section_name ); ?></span></span>
    <style type="text/css">
        section:hover .section-link {
            visibility: visible;
        }
        .section-link {
            padding: 20px 10px;
            visibility: hidden;
            background-color: black;
            position: absolute;
            z-index: 99;
            left: 5%;
            top: 5%;
            color: #fff;
            text-align: center;
            font-size: 20px;
            border-radius: 10px;
            text-transform: capitalize;
        }
        .section-link-title {
            padding: 0 10px;
        }
    </style>
    <?php
    }
}

/**
 * Auto General height input
 */
if ( ! function_exists( 'general_height_from_count_post' ) ) {
    function general_height_from_count_post($data = array()) {
        $count = count($data);
        $height_default = 50;
        if($count < 30) {
            $height_default = 100;
        } elseif($count < 50) {
            $height_default = 150;
        } elseif($count < 100) {
            $height_default = 200;
        } elseif($count < 300) {
            $height_default = 250;
        }
        return $height_default;
    }
}

/**
 * Priority Section Blocks
 */
if ( ! function_exists( 'crt_manage_priority_section' ) ) {
    function crt_manage_priority_section($key) {
        $section_order_default = array();
        if(function_exists('priority_section_theme')) {
            $section_order_default = priority_section_theme();
        }
        $section_order         = get_theme_mod( 'sections_order' );
        $section_order_decoded = json_decode( $section_order, true );
        if(empty($section_order_decoded)) {
            $section_order_decoded = array();
        }
        $section_order_decoded = array_replace_recursive($section_order_default, $section_order_decoded);
        return isset($section_order_decoded[$key]) ? $section_order_decoded[$key]:10;
    }
}