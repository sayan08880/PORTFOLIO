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
        'crt_manage_hero_v1_type' => array(
            'def' => 'v1',
            'label'           => esc_html__( 'Hero layout', 'crt-manage' ),
            'type' => 'radio_image',
            'choices' => array(
                'v1' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack1.jpg',
                    'label' => esc_html__( 'Style 1', 'crt-manage' ),
                ),
                'v2' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack2.jpg',
                    'label' => esc_html__( 'Style 2', 'crt-manage' ),
                ),
                'v3' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack3.jpg',
                    'label' => esc_html__( 'Style 3', 'crt-manage' ),
                ),
            ),
        ),
        'crt_manage_enable_hero_v1_slider_full_width' => array(
            'label'    => esc_html__( 'Full Width', 'crt-manage' ),
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
            'active_callback' => ''
        ),
    )
);
