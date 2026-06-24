<?php
/**
 * Product Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_category_one_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Category', 'crt-manage' ),
    'control' => array(
        'crt_manage_enable_category_one_section' => array(
            'label'    => esc_html__( 'Enable Category', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#topic-tax .section-link'
        ),
        'crt_manage_category_one_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Trending Topics', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_category_one_list' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
        ),
    )
);