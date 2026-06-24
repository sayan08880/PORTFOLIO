<?php
/**
 * Section Front End
 *
 * @package crt_manage
 */
if ( ! function_exists( 'crt_manage_hero_section' ) ) {
    function crt_manage_hero_section() {
        $template = get_template_directory() . '/sections/hero/hero.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_resume_section' ) ) {
    function crt_manage_resume_section() {
        $template = get_template_directory() . '/sections/resume.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_project_section' ) ) {
    function crt_manage_project_section() {
        $template = get_template_directory() . '/sections/project.php';
        if(file_exists($template)) {
            require $template;
        }
    }
}
if ( ! function_exists( 'crt_manage_service_section' ) ) {
    function crt_manage_service_section() {
        $template = get_template_directory() . '/sections/service.php';
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
if ( ! function_exists( 'crt_manage_price_section' ) ) {
    function crt_manage_price_section() {
        $template = get_template_directory() . '/sections/price.php';
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
if ( ! function_exists( 'crt_manage_html_section' ) ) {
    function crt_manage_html_section() {
        $template = get_template_directory() . '/sections/html.php';
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

if ( ! function_exists( 'crt_manage_contact_section' ) ) {
    function crt_manage_contact_section() {
        $template = get_template_directory() . '/sections/contact.php';
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
        'crt_manage_hero_section' => $i,
        'crt_manage_resume_section' => $i += 5,
        'crt_manage_project_section' => $i += 5,
        'crt_manage_service_section' => $i += 5,
        'crt_manage_client_section' => $i += 5,
        'crt_manage_price_section' => $i += 5,
        'crt_manage_latest_section' => $i += 5,
//        'crt_manage_product_section' => $i += 5,
        'crt_manage_html_section' => $i += 5,
        'crt_manage_shortcode_section' => $i += 5,
        'crt_manage_content_section' => $i += 5,
        'crt_manage_contact_section' => $i += 5,
    );
    return $section_order_default;
}
