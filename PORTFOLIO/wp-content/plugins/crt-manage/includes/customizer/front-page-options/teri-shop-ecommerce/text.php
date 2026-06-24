<?php
/**
 * Text Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_text_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Text', 'crt-manage' ),
    'active_callback' => '',
    'control' => array(
        'crt_manage_enable_text_section' => array(
            'label'    => esc_html__( 'Enable Carousel Text Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#carousel .section-link'
        ),
        'crt_manage_text_heading' => array(
            'label'    => esc_html__( 'Heading', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
        ),
        'crt_manage_text_content' => array(
            'label'    => esc_html__( 'Content', 'crt-manage' ),
            'def' => '',
            'type' => 'textarea',
        ),

    )
);