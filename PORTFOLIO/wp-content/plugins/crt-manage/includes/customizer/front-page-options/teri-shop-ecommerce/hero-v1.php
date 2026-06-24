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
        'crt_manage_hero_slider' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Slider','crt-manage'),
                'label_item'   => esc_html__('Slider Item','crt-manage'),
                'section' => 'crt_manage_hero_v1_section',
                'custom_repeater_title_control' => true,
                'custom_repeater_text_control' => array('title' => 'Content'),
                'custom_repeater_image_control' => true,
                'custom_repeater_link_control' => true,
                'custom_repeater_subtitle_control' => array('title' => 'Button Text'),
            ),
        ),
    )
);
