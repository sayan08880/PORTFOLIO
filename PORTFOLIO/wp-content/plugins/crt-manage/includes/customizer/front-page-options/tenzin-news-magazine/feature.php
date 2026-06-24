<?php
/**
 * Feature Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_feature_section'] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Post Feature', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_feature_section_enabled',
    'control' => array(
        'crt_manage_enable_feature_section' => array(
            'label'    => esc_html__( 'Enable Feature Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#feature .section-link'
        ),
        'crt_manage_feature_type' => array(
            'def' => 'v1',
            'label'           => esc_html__( 'Feature layout', 'crt-manage' ),
            'type' => 'radio_image',
            'choices' => array(
                'v1' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/feature-stack1.jpg',
                    'label' => esc_html__( 'Style 1', 'crt-manage' ),
                ),
                'v2' => array(
                    'url' => CRT_MANAGE_URI . '/assets/img/'.$this->crt_manage_theme.'/feature-stack2.jpg',
                    'label' => esc_html__( 'Style 2', 'crt-manage' ),
                ),
            ),
        ),
        'crt_manage_feature_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'World', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_feature_headline_sub' => array(
            'label'           => esc_html__( 'Headline sub', 'crt-manage' ),
            'def' => 'Topics news and opinion',
            'type' => 'text',
        ),
        'crt_manage_feature_post' => array(
            'label'           => esc_html__( 'Post', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select_multiple',
            'choices' => crt_manage_get_post_choices(),
            'class' => 'Crt_Manage_Customize_Select_Multiple',
        ),
        'crt_manage_feature_post_order' => array(
            'label'           => esc_html__( 'Post Order', 'crt-manage' ),
            'def' => 'DESC',
            'type' => 'select',
            'choices' => array(
                'DESC' => esc_html__( 'DESC', 'crt-manage' ),
                'ASC' => esc_html__( 'ASC', 'crt-manage' )
            ),
        ),
    )
);