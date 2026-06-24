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

function crt_manage_is_hero_v1_is_post($control) {
    return $control->manager->get_setting( 'crt_manage_enable_hero_v1_slider_post_tax' )->value();
}

function crt_manage_is_hero_v1_is_tax($control) {
    return !$control->manager->get_setting( 'crt_manage_enable_hero_v1_slider_post_tax' )->value();
}

function crt_manage_is_section_product($control) {
    return $control->manager->get_setting( 'crt_manage_enable_product_section' )->value();
}
?>