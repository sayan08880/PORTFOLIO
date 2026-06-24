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

//    $prefix_product = 'crt_manage_product_metabox_';
//
//    $crt_manage_product_setting = new_cmb2_box( array(
//        'id'            => $prefix_product . 'settings',
//        'title'         => __( 'Settings', 'crt-manage' ),
//        'object_types'  => array( 'product' ),
//    ) );

    $prefix_tax_product = 'crt_manage_tax_product_';
    /**
     * Metabox to add fields to categories and tags
     */
    $tax_product = new_cmb2_box( array(
        'id'               => $prefix_tax_product . 'edit',
        'title'            => __( 'Settings', 'crt-manage' ),
        'object_types'     => array( 'term' ),
        'taxonomies'       => array( 'pa_brand', 'pa_store'),
    ) );
    $tax_product->add_field( array(
        'name'     => __( 'Image', 'crt-manage' ),
        'id'       => $prefix_tax_product . 'image',
        'taxonomy' => array('pa_brand', 'pa_store'),
        'type'     => 'file',
        'text'     => array(
            'no_terms_text' => __( 'Sorry, no terms could be found.', 'crt-manage' )
        ),
    ) );

}