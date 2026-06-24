<?php
/**
 * Post Six Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_post_six_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post Six', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_post_six_section_enabled',
    'control' => array(
        'crt_manage_enable_post_six_section' => array(
            'label'    => esc_html__( 'Enable Feature Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#post-six .section-link'
        ),
        'crt_manage_post_six_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Technology', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_post_six_headline_sub' => array(
            'label'           => esc_html__( 'Headline sub', 'crt-manage' ),
            'def' => 'Topics news and opinion',
            'type' => 'text',
        ),
        'crt_manage_post_six_post_layout' => array(
            'label'           => esc_html__( 'Post Layout', 'crt-manage' ),
            'def' => '3',
            'type' => 'select',
            'choices' => array(
                '2' => esc_html__( '2 Column', 'crt-manage' ),
                '3' => esc_html__( '3 Column', 'crt-manage' ),
                '4' => esc_html__( '4 Column', 'crt-manage' ),
            ),
        ),
        'crt_manage_post_six_post' => array(
            'label'           => esc_html__( 'Post', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select_multiple',
            'choices' => crt_manage_get_post_choices(),
            'class' => 'Crt_Manage_Customize_Select_Multiple',
        ),
        'crt_manage_post_six_post_order' => array(
            'label'           => esc_html__( 'Post Order', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select',
            'choices' => array(
                'DESC' => esc_html__( 'DESC', 'crt-manage' ),
                'ASC' => esc_html__( 'ASC', 'crt-manage' )
            ),
        ),
    )
);