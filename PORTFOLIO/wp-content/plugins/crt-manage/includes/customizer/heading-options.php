<?php
/**
 * Heading Options
 *
 * @package crt_manage
 */

$options['crt_manage_heading_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Heading Options', 'crt-manage' ),
    'control' => array(
        'crt_manage_heading_style' => array(
            'label'           => esc_html__( 'Heading Style', 'crt-manage' ),
            'def' => 'center',
            'type' => 'select',
            'choices' => array(
                'center' => esc_html__( 'Center', 'crt-manage' ),
                'left' => esc_html__( 'Left', 'crt-manage' ),
                'right' => esc_html__( 'Right', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_heading_line_full' => array(
            'label'    => esc_html__( 'Line Full', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_heading_line_color' => array(
            'label'           => esc_html__( 'Line Color', 'crt-manage' ),
            'def' => '#000',
            'type' => 'select',
            'choices' => array(
                '#000' => __('Black', 'crt-manage'),
                '#b8b8b8' => __('Grey', 'crt-manage'),
                '#dbdbdb' => __('Silver', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_heading_line_size' => array(
            'label'           => esc_html__( 'Line Size', 'crt-manage' ),
            'def' => '1px',
            'type' => 'select',
            'choices' => array(
                '1px' => esc_html__('Small', 'crt-manage'),
                '2px' => esc_html__('Medium', 'crt-manage'),
                '3px' => esc_html__('Large', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_heading_line_position' => array(
            'label'           => esc_html__( 'Line Position', 'crt-manage' ),
            'def' => 'bottom',
            'type' => 'select',
            'choices' => array(
                'bottom' => __('Bottom', 'crt-manage'),
                'center' => __('Center', 'crt-manage'),
                'top' => __( 'Top', 'crt-manage'),
                'none' => __( 'None', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_font' => array(
            'label'           => esc_html__( 'Heading Font Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_heading_size' => array(
            'label'           => esc_html__( 'Heading Size', 'crt-manage' ),
            'def' => '36px',
            'type' => 'select',
            'choices' => array(
                '24px' => esc_html__('X-small', 'crt-manage'),
                '28px' => esc_html__('Small', 'crt-manage'),
                '36px' => esc_html__('Medium', 'crt-manage'),
                '42px' => esc_html__('Large', 'crt-manage'),
                '54px' => esc_html__('X-large', 'crt-manage'),
                '84px' => esc_html__('XL-large', 'crt-manage'),
                '100px' => esc_html__('XXL-large', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_transform' => array(
            'label'           => esc_html__( 'Heading transform', 'crt-manage' ),
            'def' => 'uppercase',
            'type' => 'select',
            'choices' => array(
                'capitalize' => esc_html__('Capitalize', 'crt-manage'),
                'lowercase' => esc_html__('Lowercase', 'crt-manage'),
                'uppercase' => esc_html__('Uppercase', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_letter_spacing' => array(
            'label'           => esc_html__( 'Heading Letter Spacing', 'crt-manage' ),
            'def' => '0px',
            'type' => 'select',
            'choices' => array(
                '0px' => esc_html__('0px', 'crt-manage'),
                '1px' => esc_html__('1px', 'crt-manage'),
                '2px' => esc_html__('2px', 'crt-manage'),
                '3px' => esc_html__('3px', 'crt-manage'),
                '4px' => esc_html__('4px', 'crt-manage'),
                '5px' => esc_html__('5px', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_heading_sub_enable' => array(
            'label'    => esc_html__( 'Enable Sub', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_general_heading_sub_font' => array(
            'label'           => esc_html__( 'Heading Sub Font Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
            'active_callback' => 'crt_manage_heading_sub_enable_active',
        ),
        'crt_manage_general_heading_sub_size' => array(
            'label'           => esc_html__( 'Heading Sub Size', 'crt-manage' ),
            'def' => '12px',
            'type' => 'select',
            'choices' => array(
                '12px' => esc_html__('X-small', 'crt-manage'),
                '14px' => esc_html__('Small', 'crt-manage'),
                '15px' => esc_html__('Medium', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_heading_sub_enable_active',
        ),
        'crt_manage_general_heading_sub_transform' => array(
            'label'           => esc_html__( 'Heading Sub transform', 'crt-manage' ),
            'def' => 'uppercase',
            'type' => 'select',
            'choices' => array(
                'capitalize' => esc_html__('Capitalize', 'crt-manage'),
                'lowercase' => esc_html__('Lowercase', 'crt-manage'),
                'uppercase' => esc_html__('Uppercase', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_heading_sub_enable_active',
        ),
        'crt_manage_general_heading_sub_letter_spacing' => array(
            'label'           => esc_html__( 'Heading Sub Letter Spacing', 'crt-manage' ),
            'def' => '5px',
            'type' => 'select',
            'choices' => array(
                '0px' => esc_html__('0px', 'crt-manage'),
                '1px' => esc_html__('1px', 'crt-manage'),
                '2px' => esc_html__('2px', 'crt-manage'),
                '3px' => esc_html__('3px', 'crt-manage'),
                '4px' => esc_html__('4px', 'crt-manage'),
                '5px' => esc_html__('5px', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_heading_sub_enable_active',
        ),
        'crt_manage_general_heading_size_1' => array(
            'label'           => esc_html__( 'Heading 1', 'crt-manage' ),
            'def' => '2.5rem',
            'type' => 'select',
            'choices' => crt_manage_heading_sizes(),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_size_2' => array(
            'label'           => esc_html__( 'Heading 2', 'crt-manage' ),
            'def' => '2rem',
            'type' => 'select',
            'choices' => crt_manage_heading_sizes(),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_size_3' => array(
            'label'           => esc_html__( 'Heading 3', 'crt-manage' ),
            'def' => '1.75rem',
            'type' => 'select',
            'choices' => crt_manage_heading_sizes(),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_size_4' => array(
            'label'           => esc_html__( 'Heading 4', 'crt-manage' ),
            'def' => '1.5rem',
            'type' => 'select',
            'choices' => crt_manage_heading_sizes(),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_size_5' => array(
            'label'           => esc_html__( 'Heading 5', 'crt-manage' ),
            'def' => '1.25rem',
            'type' => 'select',
            'choices' => crt_manage_heading_sizes(),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_heading_size_6' => array(
            'label'           => esc_html__( 'Heading 6', 'crt-manage' ),
            'def' => '1rem',
            'type' => 'select',
            'choices' => crt_manage_heading_sizes(),
            'sanitize_callback' => 'wp_kses_post',
        ),
    )
);