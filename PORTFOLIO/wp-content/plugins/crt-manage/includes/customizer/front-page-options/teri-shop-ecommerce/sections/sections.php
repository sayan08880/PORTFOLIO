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
if ( ! function_exists( 'crt_manage_latest_section' ) ) {
    function crt_manage_latest_section() {
        $template = get_template_directory() . '/sections/post-latest.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_product_section' ) ) {
    function crt_manage_product_section() {
        $template = get_template_directory() . '/sections/product.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_product_2_section' ) ) {
    function crt_manage_product_2_section() {
        $template = get_template_directory() . '/sections/product-2.php';
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

if ( ! function_exists( 'crt_manage_content_section' ) ) {
    function crt_manage_content_section() {
        $template = get_template_directory() . '/sections/content.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_carousel_text_section' ) ) {
    function crt_manage_carousel_text_section() {
        $template = get_template_directory() . '/sections/carousel-text.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_text_section' ) ) {
    function crt_manage_text_section() {
        $template = get_template_directory() . '/sections/text.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_voucher_section' ) ) {
    function crt_manage_voucher_section() {
        $template = get_template_directory() . '/sections/voucher.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_slider_section' ) ) {
    function crt_manage_slider_section() {
        $template = get_template_directory() . '/sections/slider.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_term_condition_section' ) ) {
    function crt_manage_term_condition_section() {
        $template = get_template_directory() . '/sections/term-condition.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_faq_section' ) ) {
    function crt_manage_faq_section() {
        $template = get_template_directory() . '/sections/faq.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_video_section' ) ) {
    function crt_manage_video_section() {
        $template = get_template_directory() . '/sections/video.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if ( ! function_exists( 'crt_manage_client_section' ) ) {
    function crt_manage_client_section() {
        $template = get_template_directory() . '/sections/client.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}

if(!function_exists('crt_manage_category_section')) {
    function crt_manage_category_section() {
        $template = get_template_directory() . '/sections/category.php';
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
        'crt_manage_carousel_text_section' => $i += 5,
        'crt_manage_term_condition_section' => $i += 5,
        'crt_manage_category_section' => $i += 5,
        'crt_manage_text_section' => $i += 5,
        'crt_manage_product_section' => $i += 5,
        'crt_manage_voucher_section' => $i += 5,
        'crt_manage_product_2_section' => $i += 5,
        'crt_manage_slider_section' => $i += 5,
        'crt_manage_faq_section' => $i += 5,
        'crt_manage_latest_section' => $i += 5,
        'crt_manage_html_section' => $i += 5,
        'crt_manage_shortcode_section' => $i += 5,
        'crt_manage_content_section' => $i += 5,
    );
    return $section_order_default;
}
