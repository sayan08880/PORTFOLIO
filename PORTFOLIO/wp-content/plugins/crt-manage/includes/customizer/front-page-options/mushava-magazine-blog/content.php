<?php
/**
 * Content Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_content_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Content Page', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_content_section' => array(
            'label'    => esc_html__( 'Enable Content Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#content .section-link'
        ),
    )
);