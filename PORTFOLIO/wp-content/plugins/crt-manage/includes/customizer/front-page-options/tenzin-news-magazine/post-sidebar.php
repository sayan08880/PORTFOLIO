<?php
/**
 * Post Sidebar Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_post_sidebar_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post Sidebar', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_sidebar_section_enabled',
    'control' => array(
        'crt_manage_enable_post_sidebar_section' => array(
            'label'    => esc_html__( 'Enable Post Sidebar Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#post-sidebar .section-link'
        ),
        'crt_manage_post_sidebar_type' => array(
            'def' => 'v1',
            'label'           => esc_html__( 'Post Sidebar layout', 'crt-manage' ),
            'type' => 'radio_image',
            'choices' => array(
                'v1' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/post-sidebar-stack1.jpg',
                    'label' => esc_html__( 'Style 1', 'crt-manage' ),
                ),
                'v2' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/post-sidebar-stack2.jpg',
                    'label' => esc_html__( 'Style 2', 'crt-manage' ),
                ),
            ),
        ),
        'crt_manage_post_sidebar_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Sports', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_post_sidebar_headline_sub' => array(
            'label'           => esc_html__( 'Headline sub', 'crt-manage' ),
            'def' => 'Topics news and opinion',
            'type' => 'text',
        ),
        'crt_manage_post_sidebar_left' => array(
            'label'           => esc_html__( 'Post Left', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'choices' => crt_manage_get_post_choices(),
            'class' => 'Crt_Manage_Customize_Select_Multiple',
        ),
        'crt_manage_post_sidebar_left_order' => array(
            'label'           => esc_html__( 'Post Left Order', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select',
            'choices' => array(
                'DESC' => esc_html__( 'DESC', 'crt-manage' ),
                'ASC' => esc_html__( 'ASC', 'crt-manage' )
            ),
        ),
        'crt_manage_post_sidebar_right' => array(
            'label'           => esc_html__( 'Post Right', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'choices' => crt_manage_get_post_choices(),
            'class' => 'Crt_Manage_Customize_Select_Multiple',
        ),
        'crt_manage_post_sidebar_right_order' => array(
            'label'           => esc_html__( 'Post Left Order', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select',
            'choices' => array(
                'DESC' => esc_html__( 'DESC', 'crt-manage' ),
                'ASC' => esc_html__( 'ASC', 'crt-manage' )
            ),
        ),
    )
);