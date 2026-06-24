<?php
/**
 * Front Page Options
 *
 * @package Crt_Manage
 */

$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';

// Custom logo text
$wp_customize->add_setting(
    'logo_text', array(
        'default' => 'T',
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'logo_text', array(
        'label'    => esc_html__( 'Logo text', 'crt-manage' ),
        'type'     => 'text',
        'section'    => 'title_tagline',
        'priority' => 10,
    )
);

$wp_customize->add_setting(
    'logo_tagline', array(
        'default' => false,
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    'logo_tagline', array(
        'label'    => esc_html__( 'Logo tagline', 'crt-manage' ),
        'type'     => 'checkbox',
        'section'    => 'title_tagline',
        'priority' => 10,
    )
);

// Save order section
$wp_customize->add_setting(
    'sections_order', array(
        'sanitize_callback' => 'crt_manage_sanitize_sections_order',
    )
);
$wp_customize->add_control(
    'sections_order', array(
        'section'  => 'static_front_page',
        'type'     => 'hidden',
        'priority' => 80,
    )
);

// Homepage Settings - Enable Homepage Content.
$wp_customize->add_setting(
    'crt_manage_enable_frontpage_content',
    array(
        'default'           => false,
        'sanitize_callback' => 'crt_manage_sanitize_checkbox',
    )
);

$wp_customize->add_control(
    'crt_manage_enable_frontpage_content',
    array(
        'label'           => esc_html__( 'Enable Homepage Content', 'crt-manage' ),
        'description'     => esc_html__( 'Check to enable content on static homepage.', 'crt-manage' ),
        'section'         => 'static_front_page',
        'type'            => 'checkbox',
        'active_callback' => 'crt_manage_is_static_homepage_enabled',
    )
);

$wp_customize->add_panel(
    'crt_manage_front_page_options',
    array(
        'title'    => esc_html__( 'Front Page Sections', 'crt-manage' ),
        'priority' => 130,
    )
);

$wp_customize->add_panel(
    'crt_manage_theme_options',
    array(
        'title'    => esc_html__( 'Theme Options', 'crt-manage' ),
        'priority' => 130,
    )
);

$crt_manage_dir_front_page = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme;
if(file_exists($crt_manage_dir_front_page)) {
    $crt_manage_dir_front_page = $crt_manage_dir_front_page.'/*.php';
    foreach (glob($crt_manage_dir_front_page) as $filename) {
        require $filename;
    }
}

$general_file = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/general.php';
if(file_exists($general_file)) {
    require $general_file;
}
$header_file = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/header.php';
if(file_exists($header_file)) {
    require $header_file;
}
$footer_file = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/footer.php';
if(file_exists($footer_file)) {
    require $footer_file;
}

require dirname( __FILE__, 2 ) . '/customizer/heading-options.php';

$archive_heading_option_file = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/archive-heading-options.php';
if(file_exists($archive_heading_option_file)) {
    require $archive_heading_option_file;
}
$archive_option_file = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/archive-options.php';
if(file_exists($archive_option_file)) {
    require $archive_option_file;
}
$single_option_file = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/single-options.php';
if(file_exists($single_option_file)) {
    require $single_option_file;
}
$layout_item = dirname( __FILE__, 2 ) . '/customizer/front-page-options/'.$this->crt_manage_theme.'/options/layout-item.php';
if(file_exists($layout_item)) {
    require $layout_item;
}
require dirname( __FILE__, 2 ) . '/customizer/front-page-options/' . $this->crt_manage_theme . '/options/entry-options.php';
require dirname( __FILE__, 2 ) . '/customizer/processor.php';
