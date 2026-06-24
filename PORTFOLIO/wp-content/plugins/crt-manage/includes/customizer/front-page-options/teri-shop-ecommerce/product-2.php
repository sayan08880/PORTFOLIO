<?php
/**
 * Product 2 Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_product_2_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Product 2', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_product_2_section' => array(
            'label'    => esc_html__( 'Enable Product 2 Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#product .section-link'
        ),
        'crt_manage_product_2_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout(3, 'product'),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_product_2_on_row' => array(
            'label'           => esc_html__( 'On Row', 'crt-manage' ),
            'def' => '3',
            'type' => 'select',
            'choices' => crt_manage_item_on_row(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_product_2_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Shop', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_section_product_2',
        ),
        'crt_manage_product_2_list' => array(
            'label'           => esc_html__( 'Select Product', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices('product'),
            'active_callback' => 'crt_manage_is_section_product_2',
        ),
        'crt_manage_product_2_button_text' => array(
            'label'           => esc_html__( 'Button Text', 'crt-manage' ),
            'def' => esc_html__( 'View All', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_section_product_2',
        ),
        'crt_manage_product_2_button_url' => array(
            'label'           => esc_html__( 'Button URL', 'crt-manage' ),
            'def' => esc_html__( '#', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_section_product_2',
        ),
    )
);