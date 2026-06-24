<?php
/**
 * Layout Item
 *
 * @package crt_manage
 */

$options['crt_manage_layout_item'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Post Item', 'crt-manage' ),
    'control' => array(
        'crt_manage_general_post_item_ratio' => array(
            'label'           => esc_html__( 'Ratio', 'crt-manage' ),
            'def' => 'ratio57',
            'type' => 'select',
            'choices' => array(
                'ratio32' => esc_html__( 'Ratio 3x2', 'crt-manage' ),
                'ratio43' => esc_html__( 'Ratio 4x3', 'crt-manage' ),
                'ratio11' => esc_html__( 'Ratio 1x1', 'crt-manage' ),
                'ratio57' => esc_html__( 'Ratio 5x7', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_general_post_item_align' => array(
            'label'           => esc_html__( 'Horizontal Align', 'crt-manage' ),
            'def' => 'text-left',
            'type' => 'select',
            'choices' => array(
                'text-start' => esc_html__( 'Text Left', 'crt-manage' ),
                'text-center' => esc_html__( 'Text Center', 'crt-manage' ),
                'text-end' => esc_html__( 'Text Right', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_general_post_item_vertical_align' => array(
            'label'           => esc_html__( 'Vertical Align', 'crt-manage' ),
            'def' => 'align-items-start',
            'type' => 'select',
            'choices' => array(
                'align-items-start' => esc_html__( 'Vertical Start', 'crt-manage' ),
                'align-items-center' => esc_html__( 'Vertical Center', 'crt-manage' ),
                'align-items-end' => esc_html__( 'Vertical End', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_general_post_heading_font' => array(
            'label'           => esc_html__( 'Heading post family', 'crt-manage' ),
            'def' => 'Montserrat',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_post_heading_transform' => array(
            'label'           => esc_html__( 'Heading post transform', 'crt-manage' ),
            'def' => 'uppercase',
            'type' => 'select',
            'choices' => array(
                'capitalize' => esc_html__('Capitalize', 'crt-manage'),
                'lowercase' => esc_html__('Lowercase', 'crt-manage'),
                'uppercase' => esc_html__('Uppercase', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_post_heading_weight' => array(
            'label'           => esc_html__( 'Heading post weight', 'crt-manage' ),
            'def' => '700',
            'type' => 'select',
            'choices' => array(
                '400' => esc_html__('400', 'crt-manage'),
                '600' => esc_html__('600', 'crt-manage'),
                '700' => esc_html__('700', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_extra_color' => array(
            'label'           => __( 'Color Extra', 'crt-manage' ),
            'def' => '#f1f1f1',
            'type' => 'color',
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_post_enable_excerpt' => array(
            'label'    => esc_html__( 'Enable Excerpt', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_general_post_enable_button' => array(
            'label'    => esc_html__( 'Enable Button View More', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_general_post_text_button' => array(
            'label'    => esc_html__( 'Button Text', 'crt-manage' ),
            'def' => esc_html__('Discover More', 'crt-manage'),
            'type' => 'text',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
    )
);
