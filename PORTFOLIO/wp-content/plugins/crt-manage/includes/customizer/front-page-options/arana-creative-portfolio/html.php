<?php
/**
 * HTML Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_html_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'HTML Custom', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_html_section' => array(
            'label'    => esc_html__( 'Enable HTML Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#html-custom .section-link'
        ),
        'crt_manage_html_fullwidth' => array(
            'label'    => esc_html__( 'Full HTML', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_html_content' => array(
            'label'    => esc_html__( 'HTML Content', 'crt-manage' ),
            'type' => 'textarea',
        ),
    )
);