<?php
/**
 * Custom Option
 *
 * @package crt_manage
 */

// Social Section Footer V1
$options['crt_manage_footer_options'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Footer', 'crt-manage' ),
    'control' => array(
        'crt_manage_footer_copyright' => array(
            'label'           => esc_html__( 'Footer Copyright', 'crt-manage' ),
            'def' => 'Copyright © 2025 All rights reserved.',
            'type' => 'text',
            'sanitize_callback' => 'wp_kses_post',
        ),
    )
);
