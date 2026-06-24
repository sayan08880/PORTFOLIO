<?php
/**
 * Author Section
 *
 * @package Crt_Manage
 */

$options['crt_manage_author_section-'.self::$prefix_pre] = array(
    'panel' => 'crt_manage_front_page_options',
    'title'    => esc_html__( 'Author', 'crt-manage' ),
    'active_callback' => 'crt_manage_is_author_section_enabled',
    'control' => array(
        'crt_manage_enable_author_section' => array(
            'label'    => esc_html__( 'Enable Author Section', 'crt-manage' ),
            'def' => false,
            'type' => 'toggle_switch',
            'class' => 'Crt_Manage_Toggle_Switch_Custom_Control',
            'selective_refresh' => true,
            'selector' => '#s-pc .section-link'
        ),
        'crt_manage_author_headline' => array(
            'label'           => esc_html__( 'Headline', 'crt-manage' ),
            'def' => esc_html__( 'Editor', 'crt-manage' ),
            'type' => 'text',
        ),
        'crt_manage_author_ids' => array(
            'label'           => esc_html__( 'Author', 'crt-manage' ),
            'def' => '',
            'type' => 'select_multiple',
            'class' => 'Crt_Manage_Customize_Select_Multiple',
            'choices' => crt_manage_get_authors(),
        ),
    )
);
