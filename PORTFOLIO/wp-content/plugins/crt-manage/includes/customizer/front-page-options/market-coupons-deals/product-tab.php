<?php
/**
 * Product Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_product_tab_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Product Tab', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_product_tab_section' => array(
            'label'    => esc_html__( 'Enable Product Tab', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#product-tab .section-link'
        ),
        'crt_manage_product_tab_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Deals & Coupons', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_section_product',
        ),
        'crt_manage_product_tab' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Tab','crt-manage'),
                'label_item'   => esc_html__('Tab Item','crt-manage'),
                'section' => 'crt_manage_product_tab_section',
                'custom_repeater_title_control' => array('title' => 'Tab Name'),
                'custom_repeater_repeater_fields' => array(
                    'label' => array('Products','Add','Delete'),
                    'key' => 'custom_repeater_repeater_fields',
                    'fields' => array(
                        'product' => array('class' => 'trigger_field', 'type' => 'choices','label' => 'Select Product', 'placeholder' => '', 'data' => crt_manage_get_post_choices('product')),
                    )
                ),
            ),
        ),
    )
);