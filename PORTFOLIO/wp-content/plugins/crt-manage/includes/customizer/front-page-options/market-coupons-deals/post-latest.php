<?php
/**
 * Latest Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_latest_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_latest_section_enabled',
    'control' => array(
        'crt_manage_enable_latest_section' => array(
            'label'    => esc_html__( 'Enable Post', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#blog .section-link'
        ),
        'crt_manage_latest_news_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Blog', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_latest_news' => array(
            'label'           => esc_html__( 'Select Post', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices('post'),
            'active_callback' => 'crt_manage_is_section_product',
        ),
    )
);