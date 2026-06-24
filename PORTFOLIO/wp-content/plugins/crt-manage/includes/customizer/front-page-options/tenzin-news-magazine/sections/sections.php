<?php
/**
 * Section Front End
 *
 * @package crt_manage
 */
if ( ! function_exists( 'crt_manage_hero_v1_section' ) ) {
    function crt_manage_hero_v1_section() {
        $template = get_template_directory() . '/sections/hero/hero.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_feature_section' ) ) {
    function crt_manage_feature_section() {
        $template = get_template_directory() . '/sections/feature/feature.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_post_sidebar_section' ) ) {
    function crt_manage_post_sidebar_section() {
        $template = get_template_directory() . '/sections/post-sidebar/post-sidebar.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_post_six_section' ) ) {
    function crt_manage_post_six_section() {
        $template = get_template_directory() . '/sections/post-six-grid.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_latest_section' ) ) {
    function crt_manage_latest_section() {
        $template = get_template_directory() . '/sections/post-latest.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_html_section' ) ) {
    function crt_manage_html_section() {
        $template = get_template_directory() . '/sections/html.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_content_section' ) ) {
    function crt_manage_content_section() {
        $template = get_template_directory() . '/sections/content.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_shortcode_section' ) ) {
    function crt_manage_shortcode_section() {
        $template = get_template_directory() . '/sections/shortcode.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_post_tax_section' ) ) {
    function crt_manage_post_tax_section() {
        $template = get_template_directory() . '/sections/post-tax.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_html_second_section' ) ) {
    function crt_manage_html_second_section() {
        $template = get_template_directory() . '/sections/html-second.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_category_section' ) ) {
    function crt_manage_category_section() {
        $template = get_template_directory() . '/sections/tax/tax.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}


/**
 * Priority Section Blocks
 */
function priority_section_theme( $section_order_default = array()) {
    $i = 10;
    $section_order_default = array (
        'crt_manage_hero_v1_section' => $i,
        'crt_manage_feature_section' => $i += 5,
        'crt_manage_post_sidebar_section' => $i += 5,
        'crt_manage_post_six_section' => $i += 5,
        'crt_manage_latest_section' => $i += 5,
        'crt_manage_html_section' => $i += 5,
        'crt_manage_content_section' => $i += 5,
        'crt_manage_shortcode_section' => $i += 5,
        'crt_manage_post_tax_section' => $i += 5,
        'crt_manage_category_section' => $i += 5,
        'crt_manage_html_second_section' => $i += 5,
    );
    return $section_order_default;
}
