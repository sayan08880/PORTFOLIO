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
        'crt_manage_footer_logo' => array(
            'label'           => esc_html__( 'Logo Footer', 'crt-manage' ),
            'type' => 'image',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        'crt_manage_footer_social' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Social','crt-manage'),
                'intro'   => esc_html__('List social','crt-manage'),
                'label_item'   => esc_html__('Social Item','crt-manage'),
                'section' => 'crt_manage_footer_options',
                'custom_repeater_link_control' => true,
                'custom_repeater_icon_control' => true,
                'custom_repeater_color_control' => true,
            )
        ),
        'crt_manage_footer_intro' => array(
            'label'           => esc_html__( 'Footer Intro', 'crt-manage' ),
            'def' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Donec quis diam id nisl finibus tincidunt. Suspendisse tincidunt tristique arcu, a elementum mi imperdiet eget. Fusce luctus iaculis nisi in rhoncus. Nam quis auctor mi. Maecenas vitae ornare nibh. Vestibulum tincidunt pharetra luctus. Suspendisse molestie neque nec pulvinar tincidunt.',
            'type' => 'textarea',
        ),
        'crt_manage_footer_copyright' => array(
            'label'           => esc_html__( 'Footer Copyright', 'crt-manage' ),
            'def' => '© Copyright 2024, All rights reserved. Design by crthemes.com',
            'type' => 'text',
            'sanitize_callback' => 'wp_kses_post',
        ),
    )
);
