<?php
/**
 * Custom Option
 *
 * @package crt_manage
 */

// Social Section Header V1
$prefix_header_right_option = 'crt_manage_header_right_show_';

$options['crt_manage_header_options'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Header', 'crt-manage' ),
    'control' => array(
        'crt_manage_my_tabs' => array(
            'type' => 'tab',
            'tabs'    => array(
                'font_family' => array(
                    'nicename' => esc_html__( 'Desktop', 'crt-manage' ),
                    'icon'     => 'desktop',
                    'controls' => array(
                        'crt_manage_header_type',
                        $prefix_header_right_option.'nav_button',
                        $prefix_header_right_option.'social',
                        $prefix_header_right_option.'cart',
                        $prefix_header_right_option.'search',
                        $prefix_header_right_option.'dark_light',
                        'crt_manage_header_logo_font',
                        'crt_manage_general_nav_font',
                        'crt_manage_header_social',
                        'crt_manage_header_social_style',
                        'crt_manage_general_nav_transform',
                    ),
                ),
                'font_sizes'   => array(
                    'nicename' => esc_html__( 'Mobile', 'crt-manage' ),
                    'icon'     => 'mobile-screen-button',
                    'controls' => array(
                        $prefix_header_right_option.'nav_button_m',
                        $prefix_header_right_option.'social_m',
                        $prefix_header_right_option.'cart_m',
                        $prefix_header_right_option.'search_m',
                        $prefix_header_right_option.'dark_light_m',
                    ),
                ),
            ),
        ),
        $prefix_header_right_option.'nav_button' => array(
            'label'           => esc_html__( 'Right - Button Nav', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'social' => array(
            'label'           => esc_html__( 'Right - Social', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'cart' => array(
            'label'           => esc_html__( 'Right - Icon Cart (Woo)', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_right_option.'search' => array(
            'label'           => esc_html__( 'Right - Icon Search', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_right_option.'dark_light' => array(
            'label'           => esc_html__( 'Dark Light', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => false,
        ),
        'crt_manage_header_logo_font' => array(
            'label'           => esc_html__( 'Logo Font Family', 'crt-manage' ),
            'type' => 'select',
            'choices'  => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_nav_font' => array(
            'label'           => esc_html__( 'Navigation Font Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_header_social' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Social','crt-manage'),
                'intro'   => esc_html__('List social show in navigation','crt-manage'),
                'label_item'   => esc_html__('Social Item','crt-manage'),
                'section' => 'crt_manage_header_options',
                'custom_repeater_link_control' => true,
                'custom_repeater_icon_control' => true,
                'custom_repeater_color_control' => false,
            ),
        ),
        'crt_manage_general_nav_transform' => array(
            'label'           => __( 'Navigation Heading transform', 'crt-manage' ),
            'def' => 'uppercase',
            'type' => 'select',
            'choices' => array(
                'capitalize' => __('Capitalize', 'crt-manage'),
                'lowercase' => __('Lowercase', 'crt-manage'),
                'uppercase' => __('Uppercase', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        // Options for mobile

        $prefix_header_right_option.'nav_button_m' => array(
            'label'           => esc_html__( 'Right - Button Nav', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_right_option.'social_m' => array(
            'label'           => esc_html__( 'Right - Social', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'cart_m' => array(
            'label'           => esc_html__( 'Right - Icon Cart (Woo)', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => false,
        ),
        $prefix_header_right_option.'search_m' => array(
            'label'           => esc_html__( 'Right - Icon Search', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => false,
        ),
        $prefix_header_right_option.'dark_light_m' => array(
            'label'           => esc_html__( 'Dark Light', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => false,
        ),
    )
);

$wp_customize->add_setting(
    'logo_dark', array(
        'sanitize_callback' => 'sanitize_text_field',
    )
);
$wp_customize->add_control(
    new WP_Customize_Image_Control(
        $wp_customize,
        'logo_dark',
        array(
            'label'      => esc_html__( 'Logo dark', 'jason-portfolio-resume' ),
            'section'         => 'title_tagline',
            'priority' => 9,
        )
    )
);