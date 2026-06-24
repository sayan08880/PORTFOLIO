<?php
/**
 * Latest Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_latest_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Latest', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_latest_section_enabled',
    'control' => array(
        'crt_manage_enable_latest_section' => array(
            'label'    => esc_html__( 'Enable Latest Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#post-latest .section-link'
        ),
//        'crt_manage_latest_headline' => array(
//            'label'           => esc_html__( 'Headline', 'crt-manage' ),
//            'def' => esc_html__( 'Latest', 'crt-manage' ),
//            'type' => 'text',
//        ),
//        'crt_manage_latest_headline_sub' => array(
//            'label'           => esc_html__( 'Headline sub', 'crt-manage' ),
//            'def' => 'Topics news and opinion',
//            'type' => 'text',
//        ),
        'crt_manage_latest_post_per_page' => array(
            'label'           => esc_html__( 'Post Per Page', 'crt-manage' ),
            'def' => '10',
            'type' => 'text',
        ),
        'crt_manage_latest_post_order' => array(
            'label'           => esc_html__( 'Post Order', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select',
            'choices' => array(
                'DESC' => esc_html__( 'DESC', 'crt-manage' ),
                'ASC' => esc_html__( 'ASC', 'crt-manage' )
            ),
        ),
        'crt_manage_post_latest_layout' => array(
            'label'           => esc_html__( 'Latest Layout', 'crt-manage' ),
            'def' => 'masonry-3-columns',
            'type' => 'select',
            'choices' => crt_manage_layout_is_theme_mushava_magazine_blog(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_post_latest_sidebar' => array(
            'label'           => esc_html__( 'Latest Sidebar', 'crt-manage' ),
            'def' => 'right-sidebar',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
                'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_post_latest_sidebar_position' => array(
            'label'           => esc_html__( 'Latest Sidebar Position', 'crt-manage' ),
            'def' => 'sidebar-1',
            'type' => 'select',
            'choices' => crt_manage_sidebar(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
    )
);