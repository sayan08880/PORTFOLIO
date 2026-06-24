<?php
/**
 * Carousel Text Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_carousel_text_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Carousel Text', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_carousel_text_section_enabled',
    'control' => array(
        'crt_manage_enable_carousel_text_section' => array(
            'label'    => esc_html__( 'Enable Carousel Text Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#carousel .section-link'
        ),
        'crt_manage_carousel_text_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'carousel-text-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout(3, 'carousel-text'),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_carousel_text_list' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Text Item','crt-manage'),
                'label_item'   => esc_html__('Slider Item','crt-manage'),
                'section' => 'crt_manage_carousel_text_section',
                'custom_repeater_title_control' => array('title' => 'Label'),
                'custom_repeater_link_control' => true,
            ),
        ),
        'crt_manage_carousel_text_speed' => array(
            'label'           => esc_html__( 'Speed', 'crt-manage' ),
            'def' => 'bg-color',
            'type' => 'select',
            'choices' => array(
                '1' => esc_html__('1','crt-manage'),
                '2' => esc_html__('2','crt-manage'),
                '3' => esc_html__('3','crt-manage'),
                '4' => esc_html__('4','crt-manage'),
                '5' => esc_html__('5','crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_carousel_text_icon' => array(
            'label'           => esc_html__( 'Icon', 'crt-manage' ),
            'def' => 'fa-asterisk',
            'type' => 'select',
            'choices' => array(
                'fa-star' => esc_html__('Star 1','crt-manage'),
                'fa-star-of-life' => esc_html__('Star 2','crt-manage'),
                'fa-star-of-david' => esc_html__('Star 3','crt-manage'),
                'fa-sun' => esc_html__('Sun','crt-manage'),
                'fa-certificate' => esc_html__('Certificate','crt-manage'),
                'fa-asterisk' => esc_html__('Asterisk','crt-manage'),
                'fa-circle-dot' => esc_html__('Circle','crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),

    )
);