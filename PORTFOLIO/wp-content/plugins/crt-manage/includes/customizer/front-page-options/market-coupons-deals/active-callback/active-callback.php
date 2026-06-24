<?php

// Check if static home page is enabled.
function crt_manage_is_static_homepage_enabled( $control ) {
    return ( 'page' === $control->manager->get_setting( 'show_on_front' )->value() );
}
function crt_manage_is_hero_v1_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_hero_v1_section' )->value() );
}
function crt_manage_is_latest_section_enabled( $control ) {
    return ( $control->manager->get_setting( 'crt_manage_enable_latest_section' )->value() );
}
function crt_manage_is_section_product($control) {
    return $control->manager->get_setting( 'crt_manage_enable_product_section' )->value();
}
function crt_manage_heading_sub_enable_active($control) {
    return $control->manager->get_setting( 'crt_manage_heading_sub_enable' )->value();
}
?>