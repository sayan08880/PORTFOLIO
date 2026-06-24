<?php
/**
 * Hero V1 Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_hero_v1_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post Hero', 'crt-manage' ),
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
                'v4' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack4.jpg',
                    'label' => esc_html__( 'Style 4', 'crt-manage' ),
                ),
                'v5' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack5.jpg',
                    'label' => esc_html__( 'Style 5', 'crt-manage' ),
                ),
            ),
        ),
        'crt_manage_hero_v1_left_post' => array(
            'label'           => esc_html__( 'Cols - One', 'crt-manage' ),
            'description'     => esc_html__( 'Can you choosen multiple', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices(),
        ),
        'crt_manage_hero_v1_center_post' => array(
            'label'           => esc_html__( 'Cols - Two', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices(),
            'active_callback' => 'crt_manage_hero_style1_2',
        ),
        'crt_manage_hero_v1_right_post' => array(
            'label'           => esc_html__( 'Cols - Three', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices(),
            'active_callback' => 'crt_manage_hero_style1_2',
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
                'ratio57' => esc_html__( 'Ratio 5x7', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_hero_style3',
        ),
        'crt_manage_hero_v1_thumbnail_background' => array(
            'label'           => esc_html__( 'Thumbnail Background', 'crt-manage' ),
            'def' => 'bg-content',
            'type' => 'select',
            'choices' => array(
                'full-thumbnail' => esc_html__( 'Full Thumbnail', 'crt-manage' ),
                'bg-content' => esc_html__( 'Background Content', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_hero_style3',
        ),
        'crt_manage_hero_v1_thumbnail_background_color' => array(
            'label'           => esc_html__( 'Thumbnail Background Color', 'crt-manage' ),
            'def' => 'white',
            'type' => 'select',
            'choices' => array(
                'white' => esc_html__( 'Background White', 'crt-manage' ),
                'black' => esc_html__( 'Background Black', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_hero_style3',
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
            'active_callback' => 'crt_manage_hero_style3',
        ),
    )
);
