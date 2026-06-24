<?php
/**
 * Service Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_service_';
$options[$prefix . 'section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Service', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Service', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#service .section-link'
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
            'label'           => esc_html__( 'List Service', 'crt-manage' ),
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Service Item','crt-manage'),
                'section' => $prefix . 'section',
                'custom_repeater_repeater_fields' => array(
                    'label' => array('List','Add Row','Delete Row'),
                    'key' => 'custom_repeater_repeater_fields',
                    'fields' => array(
                        'service_image' => array('class' => 'trigger_field', 'type' => 'icon', 'label' => 'Image'),
                        'service_name' => array('class' => 'trigger_field', 'type' => 'text', 'label' => 'Name Service'),
                        'service_intro' => array('class' => 'trigger_field', 'type' => 'text', 'label' => 'Intro'),
                        'service_button_url' => array('class' => 'trigger_field', 'type' => 'text','label' => 'URL', 'placeholder' => '#'),
                    )
                ),
            ),
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'service', 'crt-manage' ),
            'type' => 'text',
        ),

    )
);
