<?php
/**
 * Resume Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_resume_';
$options[$prefix . 'section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Resume', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Resume', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#resume .section-link'
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
            'label'           => esc_html__( 'List Resume', 'crt-manage' ),
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Resume','crt-manage'),
                'label_item'   => esc_html__('Resume Item','crt-manage'),
                'section' => $prefix . 'section',
                'custom_repeater_title_control' => array('title' => 'Heading Resume'),
                'custom_repeater_radio_control' => array(
                    'name' => 'radio_type',
                    'id' => 'radio_type',
                    'label' => esc_html__( 'Type', 'jason-portfolio-resume' ),
                    'description' => esc_html__( 'This is a custom radio input.', 'jason-portfolio-resume' ),
                    'choices' => array(
                        'type_1' => esc_html__( 'Type 1 (for Precent)', 'jason-portfolio-resume' ),
                        'type_2' => esc_html__( 'Type 2 (for Content)', 'jason-portfolio-resume' ),
                    ),
                ),
                'custom_repeater_repeater_fields' => array(
                    'label' => array('List','Add Row','Delete Row'),
                    'key' => 'custom_repeater_repeater_fields',
                    'fields' => array(
                        'skill_title' => array('class' => 'trigger_field', 'type' => 'text', 'label' => 'Label'),
                        'skill_precent' => array('class' => 'trigger_field', 'type' => 'text','label' => 'Precent', 'placeholder' => '1-10'),
                        'skill_content' => array('class' => 'trigger_field', 'type' => 'textarea','label' => 'Content'),
                    )
                ),
            ),
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'resume', 'crt-manage' ),
            'type' => 'text',
        ),

    )
);
