<?php
function crt_manage_import_files() {
    $data = array(
        'import_file_url'            => 'http://demo1.crthemes.com/data/tenzin/content.xml',
        'import_widget_file_url'     => 'http://demo1.crthemes.com/data/tenzin/widgets.wie'
    );
    if(get_option('crt_manage_tenzin_import_content')) {
        $data = array();
    }
    return [
        array_merge([
            'import_file_name'             => 'Tenzin News Magazine',
            'categories'                   => [ 'Magazine' ],
            'import_customizer_file_url' => 'http://demo1.crthemes.com/data/tenzin/customizer.dat',
            'import_preview_image_url'     => CRT_MANAGE_URI . '/assets/img/'.get_option( 'template' ).'/screenshot-demo1.png',
            'preview_url'                  => 'https://demo1.crthemes.com/tenzin/',
        ], $data),
        array_merge([
            'import_file_name'             => 'Times News Magazine Blog',
            'categories'                   => [ 'Magazine' ],
            'import_customizer_file_url' => 'http://demo1.crthemes.com/data/tenzin/times-customizer.dat',
            'import_preview_image_url'     => CRT_MANAGE_URI . '/assets/img/'.get_option( 'template' ).'/screenshot-demo2.png',
            'preview_url'                  => 'https://demo1.crthemes.com/times/',
        ], $data),
        array_merge([
            'import_file_name'             => 'Travel Magazine Blog',
            'categories'                   => [ 'Magazine' ],
            'import_customizer_file_url' => 'http://demo1.crthemes.com/data/tenzin/travel-customizer.dat',
            'import_preview_image_url'     => CRT_MANAGE_URI . '/assets/img/'.get_option( 'template' ).'/screenshot-demo3.jpg',
            'preview_url'                  => 'https://demo1.crthemes.com/travel/',
        ], $data),
        array_merge([
            'import_file_name'             => 'Fashion Blog',
            'categories'                   => [ 'Magazine' ],
            'import_customizer_file_url' => 'http://demo1.crthemes.com/data/tenzin/fashion-customizer.dat',
            'import_preview_image_url'     => CRT_MANAGE_URI . '/assets/img/'.get_option( 'template' ).'/screenshot-demo4.jpg',
            'preview_url'                  => 'https://demo1.crthemes.com/fashion/',
        ], $data),
        array_merge([
            'import_file_name'             => 'Magazine Blog',
            'categories'                   => [ 'Magazine' ],
            'import_customizer_file_url' => 'http://demo1.crthemes.com/data/tenzin/magazine-customizer.dat',
            'import_preview_image_url'     => CRT_MANAGE_URI . '/assets/img/'.get_option( 'template' ).'/screenshot-demo5.jpg',
            'preview_url'                  => 'https://demo1.crthemes.com/magazine/',
        ], $data),
        array_merge([
            'import_file_name'             => 'Food Blog',
            'categories'                   => [ 'Magazine' ],
            'import_customizer_file_url' => 'http://demo1.crthemes.com/data/tenzin/food-customizer.dat',
            'import_preview_image_url'     => CRT_MANAGE_URI . '/assets/img/'.get_option( 'template' ).'/screenshot-demo6.jpg',
            'preview_url'                  => 'https://demo1.crthemes.com/food/',
        ], $data)
    ];
}
add_filter( 'ocdi/import_files', 'crt_manage_import_files' );


/**
 * OCDI before import.
 */
function crt_manage_before_content_import( $selected_import ) {

    if ( isset($selected_import['import_file_url']) ) {
        update_option( 'crt_manage_tenzin_import_content', true );
    }

    $shop = get_page_by_path('shop');
    $wishlist = get_page_by_path('wishlist');
    $home = get_page_by_path('home');
    $checkout = get_page_by_path('checkout');
    $blog = get_page_by_path('blog');
    $cart = get_page_by_path('cart');
    $myaccount = get_page_by_path( 'my-account' );

    wp_delete_post($shop->ID);
    wp_delete_post($wishlist->ID);
//    wp_delete_post($home->ID);
    wp_delete_post($checkout->ID);
    wp_delete_post($blog->ID);
    wp_delete_post($cart->ID);
    wp_delete_post($myaccount->ID);
}
add_action( 'ocdi/before_content_import', 'crt_manage_before_content_import' );

/**
 * OCDI after import.
 */
function crt_manage_after_import_setup() {

	// Assign menus to their locations.
	$primary_menu = get_term_by( 'name', 'Menu 1', 'nav_menu' );

	set_theme_mod(
		'nav_menu_locations',
		array(
			'primary' => $primary_menu->term_id,
		)
	);

	// Assign front page and posts page (blog page).
	$front_page_id = get_page_by_title( 'Home' );
	$blog_page_id  = get_page_by_title( 'Blog' );

	update_option( 'show_on_front', 'page' );
	update_option( 'page_on_front', $front_page_id->ID );
	update_option( 'page_for_posts', $blog_page_id->ID );

    $woocommerce_shop = get_page_by_path('shop');
    $woocommerce_checkout = get_page_by_path( 'checkout' );
    $woocommerce_cart = get_page_by_path( 'cart' );
    $woocommerce_myaccount = get_page_by_path( 'my-account' );
    update_option( 'woocommerce_cart', $woocommerce_cart->ID );
    update_option( 'woocommerce_checkout_page_id', $woocommerce_checkout->ID );
    update_option( 'woocommerce_cart_page_id', $woocommerce_cart->ID );
    update_option( 'woocommerce_myaccount_page_id', $woocommerce_myaccount->ID );
    update_option( 'woocommerce_shop_page_id', $woocommerce_shop->ID );
}
add_action( 'ocdi/after_import', 'crt_manage_after_import_setup' );



