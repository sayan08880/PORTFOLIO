<?php
/**
 * Custom Option
 *
 * @package crt_manage
 */

$prefix_header_left_option = 'crt_manage_header_left_show_';
$prefix_header_right_option = 'crt_manage_header_right_show_';

// Social Section Header V1
$options['crt_manage_header_options'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Header', 'crt-manage' ),
    'control' => array(
        'crt_manage_my_tabs' => array(
            'type' => 'tab',
            'tabs'    => array(
                'font_family' => array(
                    'nicename' => esc_html__( 'Desktop', 'crt-manage' ),
                    'icon'     => 'desktop',
                    'controls' => array(
                        'crt_manage_header_type',
                        $prefix_header_left_option.'nav_button',
                        $prefix_header_left_option.'social',
                        $prefix_header_left_option.'date',
                        $prefix_header_left_option.'search',
                        $prefix_header_right_option.'nav_button',
                        $prefix_header_right_option.'social',
                        $prefix_header_right_option.'date',
                        $prefix_header_right_option.'search',
                        'crt_manage_header_logo_heading',
                        'crt_manage_header_nav_full_width',
                        'crt_manage_header_nav_style',
                        'crt_manage_header_social_heading',
                        'crt_manage_header_social',
                        'crt_manage_header_social_style',
                        'crt_manage_header_social_intro',
                        'crt_manage_header_date_heading',
                        'crt_manage_header_right_date_format',
                        'crt_manage_header_right_vol',
                        'crt_manage_header_logo_font',
                        'crt_manage_header_logo_height',
                        'crt_manage_header_nav_heading',
                        'crt_manage_general_nav_font',
                        'crt_manage_general_nav_transform',
                        'crt_manage_general_nav_bg_color',
                        'crt_manage_general_nav_line_color',
                        'crt_manage_general_nav_color',
                        'crt_manage_general_nav_font_size',
                        'crt_manage_general_nav_size',
                        'crt_manage_header_search_heading',
                        'crt_manage_header_search_type',
                        'crt_manage_header_search_label',
                        'crt_manage_header_search_hide_tax',
                    ),
                ),
                'font_sizes'   => array(
                    'nicename' => esc_html__( 'Mobile', 'crt-manage' ),
                    'icon'     => 'mobile-screen-button',
                    'controls' => array(
                        $prefix_header_left_option.'nav_button_m',
                        $prefix_header_left_option.'social_m',
                        $prefix_header_left_option.'date_m',
                        $prefix_header_left_option.'search_m',
                        $prefix_header_right_option.'nav_button_m',
                        $prefix_header_right_option.'social_m',
                        $prefix_header_right_option.'date_m',
                        $prefix_header_right_option.'search_m',
                    ),
                ),
            ),
        ),

        'crt_manage_header_type' => array(
            'def' => 'v1',
            'label'           => esc_html__( 'Header layout', 'crt-manage' ),
            'type' => 'radio_image',
            'choices' => array(
                'v1' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/header-stack3.jpg',
                    'label' => esc_html__( 'Style 1', 'crt-manage' ),
                ),
                'v2' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/header-stack4.jpg',
                    'label' => esc_html__( 'Style 2', 'crt-manage' ),
                ),
                'v3' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/header-inline.jpg',
                    'label' => esc_html__( 'Style 3', 'crt-manage' ),
                ),
                'v4' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/header-stack2.jpg',
                    'label' => esc_html__( 'Style 4', 'crt-manage' ),
                ),
                'v5' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/header-stack1.jpg',
                    'label' => esc_html__( 'Style 5', 'crt-manage' ),
                ),
            ),
        ),
        $prefix_header_left_option.'nav_button' => array(
            'label'           => esc_html__( 'Left - Button Nav', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_left_option.'social' => array(
            'label'           => esc_html__( 'Left - Social', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_left_option.'date' => array(
            'label'           => esc_html__( 'Left - Date', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_left_option.'search' => array(
            'label'           => esc_html__( 'Left - Icon Search', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'nav_button' => array(
            'label'           => esc_html__( 'Right - Button Nav', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'social' => array(
            'label'           => esc_html__( 'Right - Social', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'date' => array(
            'label'           => esc_html__( 'Right - Date', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_right_option.'search' => array(
            'label'           => esc_html__( 'Right - Icon Search', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        'crt_manage_header_logo_heading' => array(
            'label'    => esc_html__( 'Logo Settings', 'crt-manage' ),
            'type' => 'heading',
        ),
        'crt_manage_header_logo_font' => array(
            'label'           => esc_html__( 'Logo Font Family', 'crt-manage' ),
            'type' => 'select',
            'choices'  => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_header_logo_height' => array(
            'label'           => __( 'Logo Height', 'crt-manage' ),
            'def' => '85px',
            'type' => 'select',
            'choices' => array(
                '60px' => __('x-Small', 'crt-manage'),
                '75px' => __('Small', 'crt-manage'),
                '85px' => __('Medium', 'crt-manage'),
                '100px' => __('Large', 'crt-manage'),
                '120px' => __('x-Large', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
//            'active_callback' => 'crt_manage_header_style1_style2',
        ),
        'crt_manage_header_nav_heading' => array(
            'label'    => esc_html__( 'Nav Settings', 'crt-manage' ),
            'type' => 'heading',
        ),
        'crt_manage_header_nav_full_width' => array(
            'def' => 'false',
            'label'           => esc_html__( 'Header Nav Fullwidth', 'crt-manage' ),
            'type' => 'checkbox',
//            'active_callback' => 'crt_manage_header_style1_style2',
        ),
        'crt_manage_header_nav_style' => array(
            'def' => 'bg-color',
            'label'           => esc_html__( 'Header Nav Style', 'crt-manage' ),
            'type' => 'radio',
            'choices' => array(
                'bg-color' => esc_html__('Background Color','crt-manage'),
                'line' => esc_html__('Line','crt-manage'),
                'line-dual' => esc_html__('Line dual','crt-manage'),
                'line-vertical' => esc_html__('Line & Line Vertical','crt-manage'),
                'line-bottom' => esc_html__('Line Bottom','crt-manage'),
                'line-top' => esc_html__('Line Top','crt-manage'),
            ),
//            'active_callback' => 'crt_manage_header_style1_style2',
        ),
        'crt_manage_general_nav_bg_color' => array(
            'label'           => __( 'Navigation background color', 'crt-manage' ),
            'def' => '#000',
            'type' => 'color',
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_nav_color' => array(
            'label'           => __( 'Navigation text color', 'crt-manage' ),
            'def' => '#FFF',
            'type' => 'color',
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_nav_font' => array(
            'label'           => esc_html__( 'Navigation Font Family', 'crt-manage' ),
            'def' => 'Oswald',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),
        'crt_manage_general_nav_transform' => array(
            'label'           => __( 'Navigation transform', 'crt-manage' ),
            'def' => 'uppercase',
            'type' => 'select',
            'choices' => array(
                'capitalize' => __('Capitalize', 'crt-manage'),
                'lowercase' => __('Lowercase', 'crt-manage'),
                'uppercase' => __('Uppercase', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),

        'crt_manage_general_nav_line_color' => array(
            'label'           => __( 'Navigation line color', 'crt-manage' ),
            'def' => '#000',
            'type' => 'select',
            'choices' => array(
                '#CCC' => __('Grey', 'crt-manage'),
                '#000' => __('Black', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
//            'active_callback' => 'crt_manage_header_style1_style2',
        ),
        'crt_manage_general_nav_font_size' => array(
            'label'           => __( 'Navigation font size', 'crt-manage' ),
            'def' => '16px',
            'type' => 'select',
            'choices' => array(
                '14px' => __('Small', 'crt-manage'),
                '16px' => __('Medium', 'crt-manage'),
                '18px' => __('Large', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_general_nav_size' => array(
            'label'           => __( 'Navigation size', 'crt-manage' ),
            'def' => '38px',
            'type' => 'select',
            'choices' => array(
                '38px' => __('Small', 'crt-manage'),
                '45px' => __('Medium', 'crt-manage'),
                '50px' => __('Large', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
//            'active_callback' => 'crt_manage_header_style1_style2',
        ),
        'crt_manage_header_social_heading' => array(
            'label'    => esc_html__( 'Social Settings', 'crt-manage' ),
            'type' => 'heading',
        ),
        'crt_manage_header_social_style' => array(
            'label'           => esc_html__( 'Social Style', 'crt-manage' ),
            'def' => 'bg-color',
            'type' => 'select',
            'choices' => array(
                'bg-color' => esc_html__('Background Color','crt-manage'),
                'color' => esc_html__('Color','crt-manage'),
                'border-line-solid' => esc_html__('Border Line Solid','crt-manage'),
                'none-border-solid' => esc_html__('None Border Line Solid','crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_header_social' => array(
            'def' => '',
            'type' => 'repeater',
            'sanitize_callback' => 'crt_manage_customizer_repeater_sanitize',
            'repeater_fields' => array(
                'label'   => esc_html__('Social','crt-manage'),
                'intro'   => esc_html__('List social show in navigation','crt-manage'),
                'label_item'   => esc_html__('Social Item','crt-manage'),
                'section' => 'crt_manage_header_options',
                'custom_repeater_link_control' => true,
                'custom_repeater_icon_control' => true,
                'custom_repeater_color_control' => true,
            ),
        ),
        'crt_manage_header_date_heading' => array(
            'label'    => esc_html__( 'Date Settings', 'crt-manage' ),
            'type' => 'heading',
        ),
        'crt_manage_header_right_date_format' => array(
            'label'           => esc_html__( 'Date format', 'crt-manage' ),
            'def' => 'd-m-Y',
            'type' => 'select',
            'choices' => array(
                'd-MMMM-y' => date('d-M-Y'),
                'd MMMM y' => date('d M Y'),
                'MM d, y' => date('m j, Y'),
                'y-MMMM-d' => date('Y-M-d'),
            ),
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_header_style1_style2',
        ),
        'crt_manage_header_right_vol' => array(
            'label'           => esc_html__( 'Vol', 'crt-manage' ),
            'def' => 'Vol <strong>19</strong>',
            'type' => 'text',
            'sanitize_callback' => 'wp_kses_post',
            'active_callback' => 'crt_manage_header_style1_style2',
        ),

        'crt_manage_header_search_heading' => array(
            'label'    => esc_html__( 'Search Settings', 'crt-manage' ),
            'type' => 'heading',
        ),
        'crt_manage_header_search_type' => array(
            'label'           => esc_html__( 'Search Style', 'crt-manage' ),
            'def' => 'v1',
            'type' => 'select',
            'choices' => array(
                'v1' => __('Style 1', 'crt-manage'),
                'v2' => __('Style 2', 'crt-manage'),
            ),
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_header_search_label' => array(
            'label'  => esc_html__( 'Search Label', 'crt-manage' ),
            'def' => 'Or check our Popular Categories...',
            'type' => 'text',
            'sanitize_callback' => 'wp_kses_post',
        ),
        'crt_manage_header_search_hide_tax' => array(
            'label'    => esc_html__( 'Search Tax', 'crt-manage' ),
            'def' => true,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
        ),


        // Options for mobile
        $prefix_header_left_option.'nav_button_m' => array(
            'label'           => esc_html__( 'Left - Button Nav', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_left_option.'social_m' => array(
            'label'           => esc_html__( 'Left - Social', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_left_option.'date_m' => array(
            'label'           => esc_html__( 'Left - Date', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_left_option.'search_m' => array(
            'label'           => esc_html__( 'Left - Icon Search', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'nav_button_m' => array(
            'label'           => esc_html__( 'Right - Button Nav', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => true,
        ),
        $prefix_header_right_option.'social_m' => array(
            'label'           => esc_html__( 'Right - Social', 'crt-manage' ),
            'type' => 'checkbox',
        ),
        $prefix_header_right_option.'date_m' => array(
            'label'           => esc_html__( 'Right - Date', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => false,
        ),
        $prefix_header_right_option.'search_m' => array(
            'label'           => esc_html__( 'Right - Icon Search', 'crt-manage' ),
            'type' => 'checkbox',
            'def' => false,
        ),

    )
);