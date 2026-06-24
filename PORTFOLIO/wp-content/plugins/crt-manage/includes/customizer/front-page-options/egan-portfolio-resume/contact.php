<?php
/**
 * Contact Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_contact_';
$options[$prefix . 'section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Contact', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Contact', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#contact .section-link'
        ),
        $prefix . 'heading' => array(
            'label'           => esc_html__( 'Heading', 'crt-manage' ),
            'def' => esc_html__( '', 'crt-manage' ),
            'type' => 'text',
        ),
        $prefix . 'heading_label' => array(
            'label'           => esc_html__( 'Heading Label', 'crt-manage' ),
            'def' => esc_html__( '', 'crt-manage' ),
            'type' => 'text',
        ),
        $prefix . 'image' => array(
            'label'           => esc_html__( 'Image', 'crt-manage' ),
            'def' => '',
            'type' => 'image',
        ),
        $prefix . 'shortcode' => array(
            'label'           => esc_html__( 'Shortcode', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'contact', 'crt-manage' ),
            'type' => 'text',
        ),

    )
);
