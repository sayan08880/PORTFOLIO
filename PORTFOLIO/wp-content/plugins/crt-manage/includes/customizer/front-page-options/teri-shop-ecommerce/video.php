<?php
/**
 * Video Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_video_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Video', 'crt-manage' ),
    'active_callback' => '',
    'control' => array(
        'crt_manage_enable_video_section' => array(
            'label'    => esc_html__( 'Enable Video Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#video .section-link'
        ),
        'crt_manage_video_id' => array(
            'label'           => esc_html__( 'Video ID', 'crt-manage' ),
            'def' => '0iZT1A0W7y8',
            'type' => 'text',
            'description' => 'Sample video id from Youtube https://www.youtube.com/watch?v=0iZT1A0W7y8',
        ),
    )
);