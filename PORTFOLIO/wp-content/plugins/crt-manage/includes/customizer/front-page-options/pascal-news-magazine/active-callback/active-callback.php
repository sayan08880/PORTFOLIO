<?php

// Check if static home page is enabled.
function crt_manage_is_static_homepage_enabled( $control ) {
    return ( 'page' === $control->manager->get_setting( 'show_on_front' )->value() );
}
function crt_manage_is_hero_v1_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_hero_v1_section' )->value() );
}
function crt_manage_is_layout_hero_v1 ( $control ) {
    return ( 'v1' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_is_layout_hero_v2 ( $control ) {
    return ( 'v2' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_is_layout_hero_v3 ( $control ) {
    return ( 'v3' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_is_layout_hero_v2_3 ( $control ) {
    return ( 'v3' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() || 'v2' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
}
function crt_manage_is_layout_hero_v1_2 ( $control ) {
    return ( 'v1' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() || 'v2' === $control->manager->get_setting( 'crt_manage_hero_v1_type' )->value() );
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
function crt_manage_is_author_section_enabled($control) {
    return ( $control->manager->get_setting( 'crt_manage_enable_author_section' )->value() );
}
function crt_manage_is_section_product($control) {
    return $control->manager->get_setting( 'crt_manage_enable_product_section' )->value();
}
function crt_manage_is_post_select_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_select_post_section' )->value() );
}
function crt_manage_is_tax_one($control) {
    return ( $control->manager->get_setting( 'crt_manage_post_tax_1_enable' )->value() );
}
function crt_manage_is_tax_two($control) {
    return ( $control->manager->get_setting( 'crt_manage_post_tax_2_enable' )->value() );
}
function crt_manage_is_tax_three($control) {
    return ( $control->manager->get_setting( 'crt_manage_post_tax_3_enable' )->value() );
}
function crt_manage_is_tax_four($control) {
    return ( $control->manager->get_setting( 'crt_manage_post_tax_4_enable' )->value() );
}
function crt_manage_is_tax_five($control) {
    return ( $control->manager->get_setting( 'crt_manage_post_tax_5_enable' )->value() );
}
function crt_manage_is_shortcode_section_enabled($control) {
    return ( $control->manager->get_setting( 'crt_manage_enable_shortcode_section' )->value() );
}


?>