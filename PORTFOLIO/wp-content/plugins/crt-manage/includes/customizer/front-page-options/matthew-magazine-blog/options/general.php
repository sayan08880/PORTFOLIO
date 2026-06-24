<?php
/**
 * General Options
 *
 * @package crt_manage
 */

$options['crt_manage_general_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'General', 'crt-manage' ),
    'control' => array(
        'crt_manage_general_post_heading_font' => array(
            'label'           => esc_html__( 'Heading Post Family', 'crt-manage' ),
            'def' => 'Montserrat',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_body_font' => array(
            'label'           => esc_html__( 'Body Family', 'crt-manage' ),
            'def' => 'Montserrat',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_auto_scroll_load_post' => array(
            'label'    => esc_html__( 'Auto Scroll Load Post', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_general_manual_load_post_button_text' => array(
            'label'    => esc_html__( 'Button Text', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_general_is_not_load_post',
        ),
    )
);
