<?php
/**
 * Slider Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_slider_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Slider', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_slider_section' => array(
            'label'    => esc_html__( 'Enable Slider Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#slider .section-link'
        ),
        'crt_manage_slider_list' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Slider','crt-manage'),
                'label_item'   => esc_html__('Slider Item','crt-manage'),
                'section' => 'crt_manage_slider_section',
                'custom_repeater_title_control' => true,
                'custom_repeater_text_control' => array('title' => 'Content'),
                'custom_repeater_image_control' => true,
                'custom_repeater_link_control' => true,
            ),
        ),

    )
);