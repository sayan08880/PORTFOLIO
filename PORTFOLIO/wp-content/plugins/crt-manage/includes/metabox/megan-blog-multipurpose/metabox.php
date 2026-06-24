<?php
add_action( 'cmb2_admin_init', 'crt_manage_post_metabox' );
/**
 * Define the metabox and field configurations.
 */

function crt_manage_post_metabox() {

    $prefix = 'crt_manage_post_metabox_';

    $crt_manage_post_setting = new_cmb2_box( array(
        'id'            => $prefix . 'settings',
        'title'         => __( 'Settings', 'crt-manage' ),
        'object_types'  => array( 'post' ),
    ) );

//    $crt_manage_post_setting->add_field( array(
//        'name'             => __( 'Heading Type', 'crt-manage' ),
//        'id'               => $prefix . 'heading',
//        'type'             => 'radio_image',
//        'options'          => array(
//            'full-width'    => __('Full Width', 'crt-manage'),
//            'sidebar-left'  => __('Left Sidebar', 'crt-manage'),
//            'sidebar-right' => __('Right Sidebar', 'crt-manage'),
//        ),
//        'images_path'      => CRT_MANAGE_URI.'/assets/img/'.get_option( 'template' ),
//        'images'           => array(
//            'full-width'    => 'header-stack1.jpg',
//            'sidebar-left'  => 'header-stack2.jpg',
//            'sidebar-right' => 'header-stack3.jpg',
//        )
//    ) );

    $crt_manage_post_setting->add_field( array(
        'name'             => 'Time read',
        'id'               => $prefix . 'time_read',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'custom',
        'options'          => array(
            '1' => __( '1 Minute', 'crt-manage' ),
            '2'   => __( '2 Minute', 'crt-manage' ),
            '3'     => __( '3 Minute', 'crt-manage' ),
            '4'     => __( '4 Minute', 'crt-manage' ),
            '5'     => __( '5 Minute', 'crt-manage' ),
        ),
    ) );

    $crt_manage_post_setting->add_field( array(
        'name' => 'Gallery',
        'id'   => $prefix . 'gallery',
        'type' => 'file_list',
        'text' => array(
            'add_upload_files_text' => 'Upload', // default: "Add or Upload Files"
            'remove_image_text' => 'Replacement', // default: "Remove Image"
            'file_text' => 'Replacement', // default: "File:"
            'file_download_text' => 'Replacement', // default: "Download"
            'remove_text' => 'Replacement', // default: "Remove"
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