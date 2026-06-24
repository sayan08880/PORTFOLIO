<?php
/**
 * Heading Options
 *
 * @package crt_manage
 */

$options['crt_manage_archive_heading_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => __( 'Archive Heading Options', 'crt-manage' ),
    'control' => array(
        'crt_manage_archive_heading_align' => array(
            'label'           => __( 'Heading Align', 'crt-manage' ),
            'def' => 'center',
            'type' => 'select',
            'choices' => array(
                'center' => __( 'Center', 'crt-manage' ),
                'left' => __( 'Left', 'crt-manage' ),
                'right' => __( 'Right', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
//        'crt_manage_archive_heading_style' => array(
//            'label'           => __( 'Heading Style', 'crt-manage' ),
//            'def' => 'br-bottom',
//            'type' => 'select',
//            'choices' => array(
//                'br-bottom' => __('Border Bottom', 'crt-manage'),
//                'bg-color' => __('Background Color', 'crt-manage'),
//            ),
//            'sanitize_callback' => 'wp_kses_post',
//        ),
//        'crt_manage_archive_heading_bg_color' => array(
//            'label'           => __( 'Heading Background Color', 'crt-manage' ),
//            'def' => '#000',
//            'type' => 'color',
//            'active_callback' => 'crt_manage_archive_heading_style_color',
//        ),
//        'crt_manage_archive_heading_color' => array(
//            'label'           => __( 'Heading Color', 'crt-manage' ),
//            'def' => '#FFF',
//            'type' => 'color',
//            'active_callback' => 'crt_manage_archive_heading_style_color',
//        ),
//        'crt_manage_archive_heading_font' => array(
//            'label'           => __( 'Heading Font Family', 'crt-manage' ),
//            'def' => 'Oswald',
//            'type' => 'select',
//            'choices' => crt_manage_get_all_google_font_families(),
//            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
//        ),
//        'crt_manage_archive_heading_size' => array(
//            'label'           => __( 'Heading Size', 'crt-manage' ),
//            'def' => '36px',
//            'type' => 'select',
//            'choices' => array(
//                '24px' => __('X-small', 'crt-manage'),
//                '28px' => __('Small', 'crt-manage'),
//                '36px' => __('Medium', 'crt-manage'),
//                '42px' => __('Large', 'crt-manage'),
//                '54px' => __('X-large', 'crt-manage'),
//            ),
//            'sanitize_callback' => 'wp_kses_post',
//        ),
//        'crt_manage_archive_heading_transform' => array(
//            'label'           => __( 'Heading transform', 'crt-manage' ),
//            'def' => 'uppercase',
//            'type' => 'select',
//            'choices' => array(
//                'capitalize' => __('Capitalize', 'crt-manage'),
//                'lowercase' => __('Lowercase', 'crt-manage'),
//                'uppercase' => __('Uppercase', 'crt-manage'),
//            ),
//            'sanitize_callback' => 'wp_kses_post',
//        ),
//        'crt_manage_archive_heading_letter_spacing' => array(
//            'label'           => __( 'Heading Letter Spacing', 'crt-manage' ),
//            'def' => '0px',
//            'type' => 'select',
//            'choices' => array(
//                '0px' => __('0px', 'crt-manage'),
//                '1px' => __('1px', 'crt-manage'),
//                '2px' => __('2px', 'crt-manage'),
//                '3px' => __('3px', 'crt-manage'),
//                '4px' => __('4px', 'crt-manage'),
//                '5px' => __('5px', 'crt-manage'),
//            ),
//            'sanitize_callback' => 'wp_kses_post',
//        ),

    )
);