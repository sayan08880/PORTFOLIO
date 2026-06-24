<?php
/**
 * General Options
 *
 * @package crt_manage
 */

$options['crt_manage_general_option'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'General', 'crt-manage' ),
    'control' => array(
//        'crt_manage_general_color' => array(
//            'def' => 'v1',
//            'label'           => esc_html__( 'Color', 'crt-manage' ),
//            'type' => 'radio_image',
//            'choices' => array(
//                'v1' => array(
//                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack1.jpg',
//                    'label' => esc_html__( 'Style 1', 'crt-manage' ),
//                ),
//                'v2' => array(
//                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack2.jpg',
//                    'label' => esc_html__( 'Style 2', 'crt-manage' ),
//                ),
//                'v3' => array(
//                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/hero-stack3.jpg',
//                    'label' => esc_html__( 'Style 3', 'crt-manage' ),
//                ),
//            ),
//            'active_callback' => ''
//        ),
        'crt_manage_general_body_font' => array(
            'label'           => esc_html__( 'Body Family', 'crt-manage' ),
            'def' => 'Montserrat',
            'type' => 'select',
            'choices' => crt_manage_get_all_google_font_families(),
            'sanitize_callback' => 'crt_manage_sanitize_google_fonts',
        ),

    )
);
