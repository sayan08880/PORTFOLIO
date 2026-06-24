<?php
/**
 * Faqs
 *
 * @package Crt_Manage
 */

$options['crt_manage_faq_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'FAQs', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_faq_section' => array(
            'label'    => esc_html__( 'FAQs', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#faq .section-link'
        ),
        'crt_manage_faq_list' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('FAQs','crt-manage'),
                'label_item'   => esc_html__('FAQs Item','crt-manage'),
                'section' => 'crt_manage_faq_section',
                'custom_repeater_title_control' => true,
                'custom_repeater_text2_control' => true,
            ),
        ),
    )
);




?>