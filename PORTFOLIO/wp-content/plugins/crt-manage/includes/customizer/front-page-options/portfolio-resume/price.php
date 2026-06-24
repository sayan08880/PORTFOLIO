<?php
/**
 * Price Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_price_';
$options[$prefix . 'section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Price', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Price', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#price .section-link'
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'price', 'crt-manage' ),
            'type' => 'text',
        ),

    )
);
