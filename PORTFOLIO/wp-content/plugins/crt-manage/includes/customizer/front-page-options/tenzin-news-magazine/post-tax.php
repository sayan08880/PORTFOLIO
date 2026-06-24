<?php
/**
 * Post Select Section
 *
 * @package Crt_Manage
 */
$prefix = 'crt_manage_post_tax_';

$first_blog = array_key_first(crt_manage_get_post_cat_choices());

$options['crt_manage_post_tax_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post by Tax', 'crt-manage' ),
    'control' => array(
        $prefix . '1_enable' => array(
            'label'    => esc_html__( 'Enable Tax 1', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#s-pt .section-link'
        ),
        $prefix . '1' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'type' => 'select',
            'def' => $first_blog,
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_headline_enable' => array(
            'label'    => esc_html__( 'Enable Headline', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_headline' => array(
            'label'           => esc_html__( 'Custom Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_headline_sub' => array(
            'label'           => esc_html__( 'Sub Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_view' => array(
            'label'           => esc_html__( 'View More', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout_tenzin(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_bg_color' => array(
            'label'           => esc_html__( 'BG Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '1_color' => array(
            'label'           => esc_html__( 'Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),

        $prefix . '2_enable' => array(
            'label'    => esc_html__( 'Enable Tax 2', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        $prefix . '2' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => $first_blog,
            'type' => 'select',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
            'active_callback' => 'crt_manage_is_tax_two'
        ),
        $prefix . '2_headline_enable' => array(
            'label'    => esc_html__( 'Enable Headline', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'active_callback' => 'crt_manage_is_tax_two'
        ),
        $prefix . '2_headline' => array(
            'label'           => esc_html__( 'Custom Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_two'
        ),
        $prefix . '2_headline_sub' => array(
            'label'           => esc_html__( 'Sub Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_two'
        ),
        $prefix . '2_view' => array(
            'label'           => esc_html__( 'View More', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_two'
        ),
        $prefix . '2_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout_tenzin(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_two'
        ),
        $prefix . '2_bg_color' => array(
            'label'           => esc_html__( 'BG Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '2_color' => array(
            'label'           => esc_html__( 'Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),

        $prefix . '3_enable' => array(
            'label'    => esc_html__( 'Enable Tax 3', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        $prefix . '3' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => $first_blog,
            'type' => 'select',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
            'active_callback' => 'crt_manage_is_tax_three'
        ),
        $prefix . '3_headline_enable' => array(
            'label'    => esc_html__( 'Enable Headline', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'active_callback' => 'crt_manage_is_tax_three'
        ),
        $prefix . '3_headline' => array(
            'label'           => esc_html__( 'Custom Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_three'
        ),
        $prefix . '3_headline_sub' => array(
            'label'           => esc_html__( 'Sub Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_three'
        ),
        $prefix . '3_view' => array(
            'label'           => esc_html__( 'View More', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_three'
        ),
        $prefix . '3_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout_tenzin(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_three'
        ),
        $prefix . '3_bg_color' => array(
            'label'           => esc_html__( 'BG Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '3_color' => array(
            'label'           => esc_html__( 'Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),

        $prefix . '4_enable' => array(
            'label'    => esc_html__( 'Enable Tax 4', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        $prefix . '4' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => $first_blog,
            'type' => 'select',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
            'active_callback' => 'crt_manage_is_tax_four'
        ),
        $prefix . '4_headline_enable' => array(
            'label'    => esc_html__( 'Enable Headline', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'active_callback' => 'crt_manage_is_tax_four'
        ),
        $prefix . '4_headline' => array(
            'label'           => esc_html__( 'Custom Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_four'
        ),
        $prefix . '4_headline_sub' => array(
            'label'           => esc_html__( 'Sub Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_four'
        ),
        $prefix . '4_view' => array(
            'label'           => esc_html__( 'View More', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_four'
        ),
        $prefix . '4_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout_tenzin(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_four'
        ),
        $prefix . '4_bg_color' => array(
            'label'           => esc_html__( 'BG Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '4_color' => array(
            'label'           => esc_html__( 'Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),

        $prefix . '5_enable' => array(
            'label'    => esc_html__( 'Enable Tax 5', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
        ),
        $prefix . '5' => array(
            'label'           => esc_html__( 'Select Category', 'crt-manage' ),
            'def' => $first_blog,
            'type' => 'select',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_post_cat_choices(),
            'active_callback' => 'crt_manage_is_tax_five'
        ),
        $prefix . '5_headline_enable' => array(
            'label'    => esc_html__( 'Enable Headline', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'selective_refresh' => true,
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'active_callback' => 'crt_manage_is_tax_five'
        ),
        $prefix . '5_headline' => array(
            'label'           => esc_html__( 'Custom Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_five'
        ),
        $prefix . '5_headline_sub' => array(
            'label'           => esc_html__( 'Sub Headline', 'crt-manage' ),
            'def' => '',
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_five'
        ),
        $prefix . '5_view' => array(
            'label'           => esc_html__( 'View More', 'crt-manage' ),
            'def' => esc_html__( 'View More', 'crt-manage' ),
            'type' => 'text',
            'active_callback' => 'crt_manage_is_tax_five'
        ),
        $prefix . '5_layout' => array(
            'label'           => esc_html__( 'Layout', 'crt-manage' ),
            'def' => 'layout-1',
            'type' => 'select',
            'choices' => crt_manage_sections_layout_tenzin(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_five'
        ),
        $prefix . '5_bg_color' => array(
            'label'           => esc_html__( 'BG Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
        $prefix . '5_color' => array(
            'label'           => esc_html__( 'Color', 'crt-manage' ),
            'def' => '',
            'type' => 'color',
            'sanitize_callback' => 'crt_manage_sanitize_select',
            'active_callback' => 'crt_manage_is_tax_one'
        ),
    )
);
