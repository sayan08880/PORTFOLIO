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
        'crt_manage_general_homepage_options' => array(
            'label'           => esc_html__( 'Home Page Type', 'crt-manage' ),
            'def' => 'home-boxed',
            'type' => 'select',
            'choices' => array(
                'home-boxed' => __('Boxed', 'crt-manage'),
                'home-none-boxed' => __('None Boxed', 'crt-manage'),
                'home-border-item' => __('Border Item', 'crt-manage'),
                'home-border-body' => __('Border Page', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_homepage_border_color_page' => array(
            'label'           => esc_html__( 'Border Color', 'crt-manage' ),
            'def' => '#000',
            'type' => 'color',
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_general_homepage_option_border_body',
        ),
        'crt_manage_general_homepage_border_size_page' => array(
            'label'           => esc_html__( 'Border Size', 'crt-manage' ),
            'def' => '10px',
            'type' => 'select',
            'choices' => array(
                '8px' => __('Small', 'crt-manage'),
                '10px' => __('Medium', 'crt-manage'),
                '15px' => __('Large', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_general_homepage_option_border_body',
        ),
        'crt_manage_general_homepage_border_item_space' => array(
            'label'           => esc_html__( 'Space LR', 'crt-manage' ),
            'def' => 'large',
            'type' => 'select',
            'choices' => array(
                'large' => __('Large', 'crt-manage'),
                'small' => __('Small', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_general_homepage_option_border_item',
        ),
        'crt_manage_general_homepage_border_item_color' => array(
            'label'           => esc_html__( 'Color', 'crt-manage' ),
            'def' => '#000',
            'type' => 'select',
            'choices' => array(
                '#000' => __('Black', 'crt-manage'),
                '#999' => __('Grey', 'crt-manage'),
                '#DDD' => __('Silver', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_general_homepage_option_border_item',
        ),
        'crt_manage_general_post_heading_font' => array(
            'label'           => esc_html__( 'Heading Post Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_body_font' => array(
            'label'           => esc_html__( 'Body Family', 'crt-manage' ),
            'def' => 'Merriweather',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),

    )
);
