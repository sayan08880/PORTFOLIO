<?php
/**
 * Typography Option
 *
 * @package crt_manage
 */

$options['crt_manage_general_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'General', 'crt-manage' ),
    'control' => array(
        'crt_manage_general_post_heading_font' => array(
            'label'           => esc_html__( 'Heading Post Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_body_font' => array(
            'label'           => esc_html__( 'Body Family', 'crt-manage' ),
            'def' => 'Roboto',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_bg_color' => array(
            'def' => array('color1'),
            'label'           => esc_html__( 'Background color', 'crt-manage' ),
            'type' => 'select',
            'choices' => array(
                'color1' => esc_html__( 'Color 1', 'crt-manage' ),
                'color2' => esc_html__( 'Color 2', 'crt-manage' ),
                'color3' => esc_html__( 'Color 3', 'crt-manage' ),
                'color4' => esc_html__( 'Color 4', 'crt-manage' ),
                'color5' => esc_html__( 'Color 5', 'crt-manage' ),
                'color6' => esc_html__( 'Color 6', 'crt-manage' ),
                'color7' => esc_html__( 'Color 7', 'crt-manage' ),
                'color8' => esc_html__( 'Color 8', 'crt-manage' ),
                'color9' => esc_html__( 'Color 9', 'crt-manage' ),
                'color10' => esc_html__( 'Color 10', 'crt-manage' ),
            ),
        ),
    )
);
