<?php
/**
 * Shortcode Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_shortcode_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Shortcode', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_shortcode_section_enabled',
    'control' => array(
        'crt_manage_enable_shortcode_section' => array(
            'label'    => esc_html__( 'Enable Shortcode Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#shortcode .section-link'
        ),
        'crt_manage_shortcode_headline' => array(
            'label'    => esc_html__( 'Headline', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_shortcode_content' => array(
            'label'    => esc_html__( 'Shortcode', 'crt-manage' ),
            'type' => 'text',
        ),
    )
);