<?php
/**
 * Client Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_client_';
$options[$prefix . 'section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Client', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Client', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#client .section-link'
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
            'label'           => esc_html__( 'List Client', 'crt-manage' ),
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Client','crt-manage'),
                'section' => $prefix . 'section',
                'custom_repeater_repeater_fields' => array(
                    'label' => array('List','','Delete Row'),
                    'key' => 'custom_repeater_repeater_fields',
                    'fields' => array(
                        'client_image' => array('class' => 'trigger_field', 'type' => 'image', 'label' => 'Avatar'),
                        'client_content' => array('class' => 'trigger_field', 'type' => 'textarea', 'label' => 'Content'),
                        'client_name' => array('class' => 'trigger_field', 'type' => 'text','label' => 'Name'),
                        'client_job' => array('class' => 'trigger_field', 'type' => 'text','label' => 'Job position'),
                    )
                ),
            ),
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'client', 'crt-manage' ),
            'type' => 'text',
        ),
    )
);
