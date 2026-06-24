<?php
/**
 * Category Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_category_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Category', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_category_section' => array(
            'label'    => esc_html__( 'Enable Category', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#topic-tax .section-link'
        ),
        'crt_manage_category_section_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'category-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout(2, 'category'),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_category_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Top Categories', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_category_slider_on_row' => array(
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
        ),
        'crt_manage_category_list' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(array(
                'hide_empty' =>  0,
                'taxonomy'   =>  'product_cat'
            )),
        ),
    )
);