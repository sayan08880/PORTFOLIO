<?php
/**
 * Voucher Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_voucher_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Voucher', 'crt-manage' ),
    'active_callback' => '',
    'control' => array(
        'crt_manage_enable_voucher_section' => array(
            'label'    => esc_html__( 'Enable Voucher Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#voucher .section-link'
        ),
        'crt_manage_voucher_id' => array(
            'label'           => esc_html__( 'Select Voucher', 'crt-manage' ),
            'def' => '',
            'type' => 'select',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_choices('shop_coupon'),
        ),
    )
);