<?php
/**
 * Product Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_category_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Category / Tax', 'crt-manage' ),
    'active_callback' => 'crt_manage_category_section_is_enabled',
    'control' => array(
        'crt_manage_enable_category_section' => array(
            'label'    => esc_html__( 'Enable Category', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#s-category .section-link'
        ),
        'crt_manage_category_is_headline' => array(
            'label'    => esc_html__( 'Enable Heading', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        'crt_manage_category_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Trending Topics', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_category_headline_sub' => array(
            'label'           => esc_html__( 'Headline Sub', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
        ),
        'crt_manage_category_list' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
        ),
        'crt_manage_the_category_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'tax-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout_tax_tenzin(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
    )
);