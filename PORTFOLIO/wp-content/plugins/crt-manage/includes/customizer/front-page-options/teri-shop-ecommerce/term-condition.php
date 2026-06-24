<?php
/**
 * Term Condition
 *
 * @package Crt_Manage
 */

$options['crt_manage_term_condition_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Term & Condition', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_term_condition_section' => array(
            'label'    => esc_html__( 'Enable Term & Condition', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#term_condition .section-link'
        ),
        'crt_manage_term_condition_list' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Term','crt-manage'),
                'label_item'   => esc_html__('Condition Item','crt-manage'),
                'section' => 'crt_manage_term_condition_section',
                'custom_repeater_icon_control' => true,
                'custom_repeater_title_control' => true,
                'custom_repeater_subtitle_control' => true,
            ),
        ),
    )
);




?>