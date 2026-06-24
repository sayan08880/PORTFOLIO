<?php
/**
 * Archive Option
 *
 * @package crt_manage
 */

global $crt_manage_is_woo;
$control_news = array(
    'crt_manage_page_sidebar' => array(
        'label'           => esc_html__( 'Page Sidebar', 'crt-manage' ),
        'def' => 'right-sidebar',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_page_sidebar_position' => array(
        'label'           => esc_html__( 'Page Sidebar Position', 'crt-manage' ),
        'def' => 'sidebar-1',
        'type' => 'select',
        'choices' => crt_manage_sidebar(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_page_thumbnail' => array(
        'label'           => esc_html__( 'Page Thumbnail', 'crt-manage' ),
        'def' => 'outer-thumb',
        'type' => 'select',
        'choices' => array(
            'none-thumb' => esc_html__( 'None Thumbnail', 'crt-manage' ),
            'outer-thumb' => esc_html__( 'Outer Thumbnail', 'crt-manage' ),
            'inner-thumb'  => esc_html__( 'Inner Thumbnail', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_page_thumbnail_size' => array(
        'label'           => esc_html__( 'Page Thumbnail Size', 'crt-manage' ),
        'def' => 'ratio169',
        'type' => 'select',
        'choices' => array(
            'ratio32' => esc_html__( 'Ratio 3x2', 'crt-manage' ),
            'ratio43' => esc_html__( 'Ratio 4x3', 'crt-manage' ),
            'ratio169' => esc_html__( 'Ratio 16x9', 'crt-manage' ),
            'ratio219' => esc_html__( 'Ratio 21x9', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_category_layout' => array(
        'label'           => esc_html__( 'Category Layout', 'crt-manage' ),
        'def' => 'standard',
        'type' => 'select',
        'choices' => crt_manage_layout_is_theme_matthew_magazine_blog(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_category_sidebar' => array(
        'label'           => esc_html__( 'Category Sidebar', 'crt-manage' ),
        'def' => 'right-sidebar',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_category_sidebar_position' => array(
        'label'           => esc_html__( 'Category Sidebar Position', 'crt-manage' ),
        'def' => 'sidebar-1',
        'type' => 'select',
        'choices' => crt_manage_sidebar(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_tag_layout' => array(
        'label'           => esc_html__( 'Tag Layout', 'crt-manage' ),
        'def' => 'standard',
        'type' => 'select',
        'choices' => crt_manage_layout_is_theme_matthew_magazine_blog(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_tag_sidebar' => array(
        'label'           => esc_html__( 'Tag Sidebar', 'crt-manage' ),
        'def' => 'right-sidebar',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_tag_sidebar_position' => array(
        'label'           => esc_html__( 'Tag Sidebar Position', 'crt-manage' ),
        'def' => 'sidebar-1',
        'type' => 'select',
        'choices' => crt_manage_sidebar(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_date_month_year_layout' => array(
        'label'           => esc_html__( 'Date Month Year Layout', 'crt-manage' ),
        'def' => 'standard',
        'type' => 'select',
        'choices' => crt_manage_layout_is_theme_matthew_magazine_blog(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_date_month_year_sidebar' => array(
        'label'           => esc_html__( 'Date Month Year Sidebar', 'crt-manage' ),
        'def' => 'right-sidebar',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_date_month_year_sidebar_position' => array(
        'label'           => esc_html__( 'Date/Moth/Year Sidebar Position', 'crt-manage' ),
        'def' => 'sidebar-1',
        'type' => 'select',
        'choices' => crt_manage_sidebar(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_author_layout' => array(
        'label'           => esc_html__( 'Author Layout', 'crt-manage' ),
        'def' => 'standard',
        'type' => 'select',
        'choices' => crt_manage_layout_is_theme_matthew_magazine_blog(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_author_sidebar' => array(
        'label'           => esc_html__( 'Author Sidebar', 'crt-manage' ),
        'def' => 'right-sidebar',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_author_sidebar_position' => array(
        'label'           => esc_html__( 'Author Sidebar Position', 'crt-manage' ),
        'def' => 'sidebar-1',
        'type' => 'select',
        'choices' => crt_manage_sidebar(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_search_layout' => array(
        'label'           => esc_html__( 'Search Layout', 'crt-manage' ),
        'def' => 'standard',
        'type' => 'select',
        'choices' => crt_manage_layout_is_theme_matthew_magazine_blog(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_search_sidebar' => array(
        'label'           => esc_html__( 'Search Sidebar', 'crt-manage' ),
        'def' => 'right-sidebar',
        'type' => 'select',
        'choices' => array(
            'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
            'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
            'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
        ),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    ),
    'crt_manage_search_sidebar_position' => array(
        'label'           => esc_html__( 'Search Sidebar Position', 'crt-manage' ),
        'def' => 'sidebar-1',
        'type' => 'select',
        'choices' => crt_manage_sidebar(),
        'sanitize_callback' => 'crt_manage_sanitize_select',
    )
);
$control_woo = array();
if($crt_manage_is_woo) {
    $control_woo = array(
        'crt_manage_product_category_sidebar' => array(
            'label'           => esc_html__( 'Product Sidebar', 'crt-manage' ),
            'def' => 'right-sidebar',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
                'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_product_category_sidebar_position' => array(
            'label'           => esc_html__( 'Product Sidebar Position', 'crt-manage' ),
            'def' => 'sidebar-e-commerce',
            'type' => 'select',
            'choices' => crt_manage_sidebar(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_product_single_sidebar' => array(
            'label'           => esc_html__( 'Single Product Sidebar', 'crt-manage' ),
            'def' => 'right-sidebar',
            'type' => 'select',
            'choices' => array(
                'right-sidebar' => esc_html__( 'Right Sidebar', 'crt-manage' ),
                'left-sidebar'  => esc_html__( 'Left Sidebar', 'crt-manage' ),
                'no-sidebar'    => esc_html__( 'No Sidebar', 'crt-manage' ),
            ),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        ),
        'crt_manage_product_single_sidebar_position' => array(
            'label'           => esc_html__( 'Single Product Sidebar Position', 'crt-manage' ),
            'def' => 'sidebar-e-commerce',
            'type' => 'select',
            'choices' => crt_manage_sidebar(),
            'sanitize_callback' => 'crt_manage_sanitize_select',
        )
    );
}
$options['crt_manage_sidebar_option-is_pre'] = array(
    'panel' => 'crt_manage_theme_options',
    'title'    => esc_html__( 'Archive Layout', 'crt-manage' ),
    'control' => array_merge($control_news, $control_woo)
);
