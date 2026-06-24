<?php
/**
 * Single Option
 *
 * @package crt_manage
 */

$options['crt_manage_single_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Single Options', 'crt-manage' ),
    'control' => array(
        'crt_manage_single_thumbnail' => array(
            'label'           => esc_html__( 'Single Thumbnail', 'crt-manage' ),
            'def' => 'outer-thumb',
            'type' => 'select',
            'choices' => array(
                'none-thumb' => esc_html__( 'None Thumbnail', 'crt-manage' ),
                'outer-thumb' => esc_html__( 'Outer Thumbnail', 'crt-manage' ),
                'inner-thumb'  => esc_html__( 'Inner Thumbnail', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_single_is_excerpt' => array(
            'label'    => esc_html__( 'Enable Excerpt', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_single_is_min_read' => array(
            'label'    => esc_html__( 'Enable Min Read', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_single_is_comment' => array(
            'label'    => esc_html__( 'Enable Comment', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_single_is_view' => array(
            'label'    => esc_html__( 'Enable View', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_single_is_author' => array(
            'label'    => esc_html__( 'Enable Author', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_single_thumbnail_size' => array(
            'label'           => esc_html__( 'Single Thumbnail Size', 'crt-manage' ),
            'def' => 'ratio169',
            'type' => 'select',
            'choices' => array(
                'ratio32' => esc_html__( 'Ratio 3x2', 'crt-manage' ),
                'ratio43' => esc_html__( 'Ratio 4x3', 'crt-manage' ),
                'ratio169' => esc_html__( 'Ratio 16x9', 'crt-manage' ),
                'ratio219' => esc_html__( 'Ratio 21x9', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_single_sidebar' => array(
            'label'           => esc_html__( 'Single Sidebar', 'crt-manage' ),
            'def' => 'right-sidebar',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
                'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_single_sidebar_position' => array(
            'label'           => esc_html__( 'Single Sidebar Position', 'crt-manage' ),
            'def' => 'sidebar-1',
            'type' => 'select',
            'choices' => crt_manage_sidebar(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_single_content_font' => array(
            'label'  => esc_html__( 'Content Font Family', 'crt-manage' ),
            'def' => 'Merriweather',
            'type' => 'select',
            'choices'  => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_single_post_navigation_type' => array(
            'label'           => esc_html__( 'Single Navigation Post', 'crt-manage' ),
            'def' => 'single-post-navigation-thumb',
            'type' => 'select',
            'choices' => array(
                'single-post-navigation-link' => esc_html__( 'Nav Link', 'crt-manage' ),
                'single-post-navigation-thumb'  => esc_html__( 'Nav Thumb', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_single_related_heading' => array(
            'label'  => esc_html__( 'Related heading', 'crt-manage' ),
            'def' => 'Related Posts',
            'type' => 'text',
        ),
        'crt_manage_single_related_layout' => array(
            'label'  => esc_html__( 'Related Layout', 'crt-manage' ),
            'def' => 'standard',
            'type' => 'select',
            'choices' => crt_manage_layout(),
            'sanitize_callback' => 'crt_manage_sanitize_select'
        ),

    )
);