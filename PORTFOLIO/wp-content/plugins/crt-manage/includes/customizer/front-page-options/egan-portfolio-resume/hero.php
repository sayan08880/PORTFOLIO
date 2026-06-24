<?php
/**
 * Hero V1 Section
 *
 * @package Crt_Manage
 */

$prefix = 'crt_manage_hero_';

$options[$prefix . 'section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Hero', 'crt-manage' ),
    'active_callback' => $prefix . 'section_callback',
    'control' => array(
        $prefix . 'enable_section' => array(
            'label'    => esc_html__( 'Enable Hero', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#hero-v1 .section-link'
        ),
        $prefix . 'layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'left-img',
            'type' => 'select',
            'choices' => array(
                'left-img' => esc_html__( 'Left Image', 'crt-manage' ),
                'right-img' => esc_html__( 'Right Image', 'crt-manage' ),
                'center-img' => esc_html__( 'Center Image', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        $prefix . 'img' => array(
            'label'           => esc_html__( 'Image', 'crt-manage' ),
            'type' => 'image',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        $prefix . 'label' => array(
            'label'           => esc_html__( 'Label', 'crt-manage' ),
            'type' => 'text',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        $prefix . 'works' => array(
            'label'           => esc_html__( 'Works', 'crt-manage' ),
            'type' => 'text',
            'description' => esc_html__('Example format: Development, Designer', 'crt-manage'),
            'sanitize_callback' => 'sanitize_text_field',
        ),
        $prefix . 'description' => array(
            'label'           => esc_html__( 'Intro', 'crt-manage' ),
            'type' => 'textarea',
            'sanitize_callback' => 'sanitize_text_field',
        ),
        $prefix . 'btn_text' => array(
            'label'           => esc_html__( 'Button Text', 'crt-manage' ),
            'type' => 'text',
            'def' => esc_html__( 'Contact', 'crt-manage' ),
            'sanitize_callback' => 'sanitize_text_field',
        ),
        $prefix . 'btn_url' => array(
            'label'           => esc_html__( 'Button URL', 'crt-manage' ),
            'type' => 'text',
            'def' => esc_html__( '#', 'crt-manage' ),
            'sanitize_callback' => 'sanitize_text_field',
        ),
        $prefix . 'attr_id' => array(
            'label'           => esc_html__( 'Attr ID', 'crt-manage' ),
            'def' => esc_html__( 'hero', 'crt-manage' ),
            'type' => 'text',
            'description' => esc_html__( 'Use when click nav go to ID', 'crt-manage' ),
        )
    )
);
