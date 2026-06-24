<?php
/**
 * Post Select Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_post_select_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post Select', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_post_select_section_enabled',
    'control' => array(
        'crt_manage_enable_select_post_section' => array(
            'label'    => esc_html__( 'Enable Select Post Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#s-pc .section-link'
        ),
        'crt_manage_post_select_center_post' => array(
            'label'           => esc_html__( 'Select Post', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices(),
        ),
        'crt_manage_post_select_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Top Headlines', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_post_select_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_post_select_view_more_url' => array(
            'label'           => esc_html__( 'Button URL', 'crt-manage' ),
            'def' => esc_html__( '#', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_post_select_view_more_text' => array(
            'label'           => esc_html__( 'Button Text', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
        ),
    )
);
