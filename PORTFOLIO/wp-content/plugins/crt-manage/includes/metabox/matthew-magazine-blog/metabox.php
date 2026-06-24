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
        'name'             => 'Layout Header',
        'id'               => $prefix . 'header_type',
        'type'             => 'select',
        'show_option_none' => true,
        'default'          => 'v1',
        'options'          => array(
            'v1' => __( 'Layout 1', 'crt-manage' ),
            'v2' => __( 'Layout 2', 'crt-manage' ),
            'v3' => __( 'Layout 3', 'crt-manage' ),
            'v4' => __( 'Layout 4', 'crt-manage' ),
        ),
    ) );

    $crt_manage_post_setting->add_field( array(
        'name' => 'Gallery',
        'id'   => $prefix . 'Galleries',
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
        'name'             => 'Video',
        'id'               => 'post_video',
        'type'             => 'text',
        'show_option_none' => true,
        'default'          => '',
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

    // Group & Repeater

//    $group_field_id = $crt_manage_post_setting->add_field( array(
//        'id'          => 'wiki_test_repeat_group',
//        'type'        => 'group',
//        'description' => __( 'Generates reusable form entries', 'cmb2' ),
//        // 'repeatable'  => false, // use false if you want non-repeatable group
//        'options'     => array(
//            'group_title'       => __( 'Entry {#}', 'cmb2' ), // since version 1.1.4, {#} gets replaced by row number
//            'add_button'        => __( 'Add Another Entry', 'cmb2' ),
//            'remove_button'     => __( 'Remove Entry', 'cmb2' ),
//            'sortable'          => true,
//            // 'closed'         => true, // true to have the groups closed by default
//            // 'remove_confirm' => esc_html__( 'Are you sure you want to remove?', 'cmb2' ), // Performs confirmation before removing group.
//        ),
//    ) );
//
//    $crt_manage_post_setting->add_group_field( $group_field_id, array(
//        'name' => 'Entry Title',
//        'id'   => 'title',
//        'type' => 'text',
//        // 'repeatable' => true, // Repeatable fields are supported w/in repeatable groups (for most types)
//    ) );
//
//    $crt_manage_post_setting->add_group_field( $group_field_id, array(
//        'name' => 'Description',
//        'description' => 'Write a short description for this entry',
//        'id'   => 'description',
//        'type' => 'textarea_small',
//    ) );
//
//    $crt_manage_post_setting->add_group_field( $group_field_id, array(
//        'name' => 'Image Caption',
//        'id'   => 'image_caption',
//        'type' => 'text',
//    ) );
//
//    $crt_manage_post_setting->add_group_field( $group_field_id, array(
//        'id'            => 'answers',
//        'name'          => __('Answers 2', 'cgc-quiz'),
//        'type'          => 'text',
//        'sortable'      => true,
//        'repeatable'     => true,
////        'repeatable_max' => 10
//    ) );
//    $crt_manage_post_setting->add_group_field( $group_field_id, array(
//        'id'            => 'answers_2',
//        'name'          => __('Answers 2', 'cgc-quiz'),
//        'type'          => 'textarea_small',
//        'sortable'      => true,
//        'repeatable'     => true,
////        'repeatable_max' => 10
//    ) );


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
        'options'          => crt_manage_layout_is_theme_matthew_magazine_blog(),
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