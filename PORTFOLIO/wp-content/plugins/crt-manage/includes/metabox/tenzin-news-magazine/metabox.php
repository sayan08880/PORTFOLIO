<?php
add_action( 'cmb2_admin_init', 'crt_manage_page_contact' );
/**
 * Define the metabox and field configurations.
 */
function crt_manage_page_contact() {
    /**
     * Initiate the metabox
     */
    $crt_manage_page_contact = new_cmb2_box( array(
        'id'            => 'crt_manage_shortcode_form',
        'title'         => __( 'Extra Form', 'cmb2' ),
        'object_types'  => array( 'page' ), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true,
        'show_on'      => array( 'key' => 'page-template', 'value' => 'template-contact.php' ),
    ) );

    $crt_manage_page_contact->add_field( array(
        'name'       => __( 'Shortcode Form', 'cmb2' ),
        'id'         => 'crt_manage_form_text_shortcode_field',
        'type'       => 'text',
    ) );

    $prefix_post = 'crt_manage_post_metabox_';

    $crt_manage_post_setting = new_cmb2_box( array(
        'id'            => $prefix_post . 'settings',
        'title'         => __( 'Settings', 'crt-manage' ),
        'object_types'  => array( 'post' ),
    ) );

    $crt_manage_post_setting->add_field( array(
        'name'             => 'Layout Header',
        'id'               => $prefix_post . 'header_type',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'v1',
        'options'          => array(
            'v1' => __( 'Layout 1', 'crt-manage' ),
            'v2' => __( 'Layout 2', 'crt-manage' ),
            'v3' => __( 'Layout 3', 'crt-manage' ),
            'v4' => __( 'Layout 4', 'crt-manage' ),
            'v5' => __( 'Layout 5', 'crt-manage' ),
        ),
    ) );

    $crt_manage_post_setting->add_field( array(
        'name'             => 'Time read',
        'id'               => $prefix_post . 'time_read',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'custom',
        'options'          => array(
            '1' => __( '1 Minute', 'crt-manage' ),
            '2'   => __( '2 Minute', 'crt-manage' ),
            '3'     => __( '3 Minute', 'crt-manage' ),
            '4'     => __( '4 Minute', 'crt-manage' ),
            '5'     => __( '5 Minute', 'crt-manage' ),
            '6'     => __( '6 Minute', 'crt-manage' ),
            '7'     => __( '7 Minute', 'crt-manage' ),
            '8'     => __( '8 Minute', 'crt-manage' ),
        ),
    ) );

    $crt_manage_post_setting->add_field( array(
        'name'             => 'View Count',
        'id'               => 'post_view_count',
        'type'             => 'text',
        'show_option_none' => true,
        'default'          => '',
    ) );

    $prefix_tax = 'crt_manage_tax_';
    /**
     * Metabox to add fields to categories and tags
     */
    $tax = new_cmb2_box( array(
        'id'               => $prefix_tax . 'edit',
        'title'            => __( 'Settings', 'crt-manage' ),
        'object_types'     => array( 'term' ),
        'taxonomies'       => array( 'category'),
    ) );
    $tax->add_field( array(
        'name'     => __( 'Image', 'crt-manage' ),
        'id'       => $prefix_tax . 'image',
        'taxonomy' => array('category', 'post_tag'),
        'type'     => 'file',
        'text'     => array(
            'no_terms_text' => __( 'Sorry, no terms could be found.', 'crt-manage' )
        ),
    ) );
    $tax->add_field( array(
        'name'     => __( 'Color', 'crt-manage' ),
        'id'       => $prefix_tax . 'color',
        'taxonomy' => array('category'),
        'type'     => 'colorpicker',
    ) );

    $tax->add_field( array(
        'name'             => 'Layout',
        'id'               => $prefix_tax . 'layout',
        'type'             => 'select',
        'taxonomy' => array('category'),
        'show_option_none' => true,
        'default'          => 'standard',
        'options'          => crt_manage_layout(),
    ) );

    $tax->add_field( array(
        'name'             => 'Sidebar',
        'id'               => $prefix_tax . 'sidebar',
        'type'             => 'select',
        'taxonomy' => array('category'),
        'show_option_none' => true,
        'default'          => 'right-sidebar',
        'options'          => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
    ) );

    $tax->add_field( array(
        'name'             => 'Sidebar Position',
        'id'               => $prefix_tax . 'sidebar_position',
        'type'             => 'select',
        'taxonomy' => array('category'),
        'show_option_none' => true,
        'default'          => 'sidebar-1',
        'options'          => crt_manage_sidebar(),
    ) );



}