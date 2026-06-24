<?php
/**
 * Project Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_project_';
$options[$prefix . 'section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Project', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Project', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#project .section-link'
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
        $prefix . 'project' => array(
            'label'           => esc_html__( 'Project', 'crt-manage' ),
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Project','crt-manage'),
                'label_item'   => esc_html__('Project Item','crt-manage'),
                'section' => $prefix . 'section',
                'custom_repeater_title_control' => array('title' => 'Heading Tab'),
                'custom_repeater_repeater_fields' => array(
                    'label' => array('List','Add Row','Delete Row'),
                    'key' => 'custom_repeater_repeater_fields',
                    'fields' => array(
                        'project_type' => array('class' => 'trigger_field', 'type' => 'choices', 'data' => array('image' => 'Image', 'video' => 'Video', 'url' => 'URL Link'), 'label' => 'Name Project'),
                        'project_name' => array('class' => 'trigger_field', 'type' => 'text', 'label' => 'Name Project'),
                        'project_category' => array('class' => 'trigger_field', 'type' => 'text', 'label' => 'Category'),
                        'project_image' => array('class' => 'trigger_field', 'type' => 'image', 'label' => 'Image'),
                        'project_url' => array('class' => 'trigger_field', 'type' => 'text','label' => 'URL', 'placeholder' => '#'),
                        'project_url_video' => array('class' => 'trigger_field', 'type' => 'text','label' => 'URL Video', 'placeholder' => 'https://youtu.be/l3zE0R2M7XE?si=6yj3Ubt7cBEYMpUi'),
                    )
                ),
            )
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'project', 'crt-manage' ),
            'type' => 'text',
        ),

    )
);
