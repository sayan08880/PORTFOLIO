<?php
/**
 * Clients
 *
 * @package Crt_Manage
 */

$options['crt_manage_client_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Client / Testimonials', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_client_section' => array(
            'label'    => esc_html__( 'Enable Client', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#client .section-link'
        ),
        'crt_manage_client_list' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Client','crt-manage'),
                'label_item'   => esc_html__('Client Item','crt-manage'),
                'section' => 'crt_manage_client_section',
                'custom_repeater_title_control' => true,
                'custom_repeater_text_control' => array('title' => 'Content'),
                'custom_repeater_subtitle_control' => array('title' => 'Name'),
                'custom_repeater_radio_control' => array(
                    'name' => 'rating_star',
                    'id' => 'rating_star',
                    'label' => esc_html__( 'Rating', 'crt-manage' ),
                    'choices' => array(
                        '5' => esc_html__( 'Rating 5', 'crt-manage' ),
                        '4' => esc_html__( 'Rating 4', 'crt-manage' ),
                        '3' => esc_html__( 'Rating 3', 'crt-manage' ),
                        '2' => esc_html__( 'Rating 2', 'crt-manage' ),
                        '1' => esc_html__( 'Rating 1', 'crt-manage' ),
                    ),
                ),
            ),
        ),
    )
);




?>