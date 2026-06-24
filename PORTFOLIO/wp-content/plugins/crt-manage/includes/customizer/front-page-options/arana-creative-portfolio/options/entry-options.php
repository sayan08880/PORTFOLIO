<?php
/**
 * Entry Options
 *
 * @package crt_manage
 */
$prefix_entry_list = 'crt_manage_entry_list_';
$prefix_entry_grid_two = 'crt_manage_entry_grid_two_';
$prefix_entry_grid_three = 'crt_manage_entry_grid_three_';
$prefix_entry_grid_four = 'crt_manage_entry_grid_four_';
$prefix_entry_masonry_two = 'crt_manage_entry_masonry_two_';
$prefix_entry_masonry_three = 'crt_manage_entry_masonry_three_';
$prefix_entry_masonry_four = 'crt_manage_entry_masonry_four_';

$options['crt_manage_entry_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Entry Options', 'crt-manage' ),
    'control' => array(
        'crt_manage_entry_font' => array(
            'label'           => esc_html__( 'Entry Font Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices'  => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_entry_date_format' => array(
            'label'           => esc_html__( 'Date format', 'crt-manage' ),
            'def' => 'F d, Y',
            'type' => 'select',
            'choices' => array(
                'd-m-Y' => date('d-m-Y'),
                'd, m, Y' => date('d, m, Y'),
                'Y-m-d' => date('Y-m-d'),
                'Y, m, d' => date('Y, m, d'),
                'F d, Y' => date('F d, Y'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_entry_text_transform' => array(
            'label'           => esc_html__( 'Text transform', 'crt-manage' ),
            'def' => 'F d, Y',
            'type' => 'select',
            'choices' => array(
                'capitalize' => esc_html__('capitalize', 'crt-manage'),
                'lowercase' => esc_html__('lowercase', 'crt-manage'),
                'uppercase' => esc_html__('uppercase', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_entry_text_character' => array(
            'label'           => esc_html__( 'Text Character', 'crt-manage' ),
            'def' => '•',
            'type' => 'text',
            'sanitize_callback' => 'wp_kses_post',
        ),
        $prefix_entry_list . 'heading' => array(
            'label'    => esc_html__( 'Layout List', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_list . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_list . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_list . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_list . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_list . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_list . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_list . 'line' => array(
            'type' => 'line',
        ),

        $prefix_entry_grid_two . 'heading' => array(
            'label'    => esc_html__( 'Layout Grid Two', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_grid_two . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_two . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_two . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_two . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_two . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_two . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_two . 'line' => array(
            'type' => 'line',
        ),

        $prefix_entry_grid_three . 'heading' => array(
            'label'    => esc_html__( 'Layout Grid Three', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_grid_three . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_three . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_three . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_three . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_three . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_three . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_three . 'line' => array(
            'type' => 'line',
        ),

        $prefix_entry_grid_four . 'heading' => array(
            'label'    => esc_html__( 'Layout Grid Four', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_grid_four . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_four . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_four . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_four . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_four . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_four . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_grid_four . 'line' => array(
            'type' => 'line',
        ),

        $prefix_entry_masonry_two . 'heading' => array(
            'label'    => esc_html__( 'Layout Masonry Two', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_masonry_two . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_two . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_two . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_two . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_two . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_two . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_two . 'line' => array(
            'type' => 'line',
        ),


        $prefix_entry_masonry_three . 'heading' => array(
            'label'    => esc_html__( 'Layout Masonry Three', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_masonry_three . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_three . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_three . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_three . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_three . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_three . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_three . 'line' => array(
            'type' => 'line',
        ),

        $prefix_entry_masonry_four . 'heading' => array(
            'label'    => esc_html__( 'Layout Masonry Four', 'crt-manage' ),
            'type' => 'heading',
        ),
        $prefix_entry_masonry_four . 'date' => array(
            'label'    => esc_html__( 'Date', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_four . 'category' => array(
            'label'    => esc_html__( 'Category', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_four . 'author' => array(
            'label'    => esc_html__( 'Author', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_four . 'read_time' => array(
            'label'    => esc_html__( 'Read Time', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_four . 'comment' => array(
            'label'    => esc_html__( 'Comment', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_four . 'view' => array(
            'label'    => esc_html__( 'View', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),
        $prefix_entry_masonry_four . 'line' => array(
            'type' => 'line',
        ),

    )
);