<?php
/**
 * Product Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_product_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Product', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_product_section' => array(
            'label'    => esc_html__( 'Enable Product Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#product .section-link'
        ),
        'crt_manage_product_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Hot Deals', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_section_product',
        ),
        'crt_manage_product_slider' => array(
            'label'    => esc_html__( 'Slider', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'active_callback' => 'crt_manage_is_section_product',
        ),
        'crt_manage_product_slider_on_row' => array(
            'label'           => esc_html__( 'Slider on Row', 'crt-manage' ),
            'def' => '4',
            'type' => 'select',
            'choices' => array(
                '2' => esc_html__( '2', 'crt-manage' ),
                '3' => esc_html__( '3', 'crt-manage' ),
                '4' => esc_html__( '4', 'crt-manage' ),
                '5' => esc_html__( '5', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_section_product',
        ),
        'crt_manage_product_list' => array(
            'label'           => esc_html__( 'Select Product', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices('product'),
            'active_callback' => 'crt_manage_is_section_product',
        ),
    )
);