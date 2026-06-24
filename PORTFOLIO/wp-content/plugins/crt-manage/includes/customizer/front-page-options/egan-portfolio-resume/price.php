<?php
/**
 * Price Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_price_';
$options[$prefix . 'section'] = array(
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
        $prefix . 'list' => array(
            'label'           => esc_html__( 'List Price', 'crt-manage' ),
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Price Item','crt-manage'),
                'section' => $prefix . 'section',
                'custom_repeater_repeater_fields' => array(
                    'label' => array('List','Add Row','Delete Row'),
                    'key' => 'custom_repeater_repeater_fields',
                    'fields' => array(
                        'price_title' => array('class' => 'trigger_field', 'type' => 'text','label' => 'Title'),
                        'price_value' => array('class' => 'trigger_field', 'type' => 'text', 'label' => 'Price'),
                        'price_description' => array('class' => 'trigger_field', 'type' => 'textarea','label' => 'Description'),
                        'price_button_text' => array('class' => 'trigger_field', 'type' => 'text','label' => 'Button Text'),
                        'price_button_url' => array('class' => 'trigger_field', 'type' => 'text','label' => 'Button URL'),
                    )
                )
            ),
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'price', 'crt-manage' ),
            'type' => 'text',
        ),

    )
);
