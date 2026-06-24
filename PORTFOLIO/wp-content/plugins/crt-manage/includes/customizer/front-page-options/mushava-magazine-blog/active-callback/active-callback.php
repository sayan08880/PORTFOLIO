<?php

// Check if static home page is enabled.
function crt_manage_is_static_homepage_enabled( $control ) {
    return ( 'page' === $control->manager->get_setting( 'show_on_front' )->value() );
}
function crt_manage_is_hero_v1_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_hero_v1_section' )->value() );
}
function crt_manage_is_sidebar_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_post_sidebar_section' )->value() );
}
function crt_manage_is_latest_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_latest_section' )->value() );
}
function crt_manage_general_homepage_option_border_item($control) {
    return ( 'home-border-item' === $control->manager->get_setting( 'crt_manage_general_homepage_options' )->value() );
}
function crt_manage_heading_sub_enable_active($control) {
    return $control->manager->get_setting( 'crt_manage_heading_sub_enable' )->value();
}
function crt_manage_is_section_product($control) {
    return $control->manager->get_setting( 'crt_manage_enable_product_section' )->value();
}
function crt_manage_general_is_not_load_post($control) {
    return !$control->manager->get_setting( 'crt_manage_general_auto_scroll_load_post' )->value();
}
function crt_manage_header_nav_style_is_bd($control) {
    return ( 'bd-line' === $control->manager->get_setting( 'crt_manage_header_nav_style' )->value() );
}
function crt_manage_header_nav_style_is_bg($control) {
    return ( 'bg-color' === $control->manager->get_setting( 'crt_manage_header_nav_style' )->value() );
}
function crt_manage_hero_type_is_v1($control) {
    return ( 'v1' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_hero_type_is_v2($control) {
    return ( 'v2' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_hero_is_type1_2($control) {
    return ( 'v1' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() || 'v2' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_hero_is_type2_3($control) {
    return ( 'v2' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() || 'v3' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
//function crt_manage_general_color_active($control) {
//    $color = $control->manager->get_setting( 'crt_manage_general_color' )->value();
//    if($color === 'v1') {
//        set_theme_mod('crt_manage_header_nav_top_style_bg_color', '#FFF');
//        set_theme_mod('crt_manage_header_nav_top_color', '#000');
//    } elseif($color === 'v2') {
//        set_theme_mod('crt_manage_header_nav_top_style_bg_color', '#c18870');
//        set_theme_mod('crt_manage_header_nav_top_color', '#FFF');
//    } elseif($color === 'v3') {
//        set_theme_mod('crt_manage_header_nav_top_style_bg_color', '#6c7f7e');
//        set_theme_mod('crt_manage_header_nav_top_color', '#000');
//    }
//    return true;
//}
?>