<?php
/**
 * Typography Option
 *
 * @package crt_manage
 */

$options['crt_manage_typography_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Typography', 'crt-manage' ),
    'control' => array(
        'crt_manage_general_nav_font' => array(
            'label'           => esc_html__( 'Navigation Font Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),

    )
);
