<?php
/**
 * Hero V1 Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_hero_v1_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Hero', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_hero_v1_section_enabled',
    'control' => array(
        'crt_manage_enable_hero_v1_section' => array(
            'label'    => esc_html__( 'Enable Hero Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#hero-v1 .section-link'
        ),
        'crt_manage_enable_hero_v1_slider_post_tax' => array(
            'label'    => esc_html__( 'Post / Tax', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_hero_v1_center_post' => array(
            'label'           => esc_html__( 'Select Post', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices(),
            'active_callback' => 'crt_manage_is_hero_v1_is_post',
        ),
        'crt_manage_hero_v1_center_tax' => array(
            'label'           => esc_html__( 'Select Tax', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
            'active_callback' => 'crt_manage_is_hero_v1_is_tax',
        ),
        'crt_manage_enable_hero_v1_slider_full_width' => array(
            'label'    => esc_html__( 'Full Width', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_enable_hero_v1_slider_center_mode' => array(
            'label'    => esc_html__( 'Center Mode', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_hero_v1_slider_on_row' => array(
            'label'           => esc_html__( 'Slider on Row', 'crt-manage' ),
            'def' => '1',
            'type' => 'select',
            'choices' => array(
                '1' => esc_html__( '1', 'crt-manage' ),
                '2' => esc_html__( '2', 'crt-manage' ),
                '3' => esc_html__( '3', 'crt-manage' ),
                '4' => esc_html__( '4', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_enable_hero_v1_slider_auto_play' => array(
            'label'    => esc_html__( 'Auto Play', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_hero_v1_thumbnail_size' => array(
            'label'           => esc_html__( 'Thumbnail Size', 'crt-manage' ),
            'def' => 'ratio219',
            'type' => 'select',
            'choices' => array(
                'ratio32' => esc_html__( 'Ratio 3x2', 'crt-manage' ),
                'ratio43' => esc_html__( 'Ratio 4x3', 'crt-manage' ),
                'ratio169' => esc_html__( 'Ratio 16x9', 'crt-manage' ),
                'ratio219' => esc_html__( 'Ratio 21x9', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_hero_v1_nav_style' => array(
            'label'           => esc_html__( 'Nav style', 'crt-manage' ),
            'def' => 'square',
            'type' => 'select',
            'choices' => array(
                'square' => esc_html__( 'Square', 'crt-manage' ),
                'disc' => esc_html__( 'Disc', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
    )
);
