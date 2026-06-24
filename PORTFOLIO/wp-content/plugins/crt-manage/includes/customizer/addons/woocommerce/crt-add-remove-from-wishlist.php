<?php
//namespace WprAddons\Classes;

use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CRT_Add_Remove_From_Wishlist setup
 *
 * @since 1.0
 */
class CRT_Add_Remove_From_Wishlist { 

    /**
    ** Constructor
    */
    public function __construct() {
        // add_action('init', [$this, 'register_wishlist_cpt']);
        add_action( 'wp_ajax_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_nopriv_add_to_wishlist',[$this, 'add_to_wishlist'] );
        add_action( 'wp_ajax_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
        add_action( 'wp_ajax_nopriv_remove_from_wishlist', [$this, 'remove_from_wishlist'] );
    }
    
    function add_to_wishlist() {
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce($nonce, 'crt-addons-js') || ! isset( $_POST['product_id'] ) ) {
            return;
        }
        
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();
        
        // NEW CODE
        if ($user_id > 0) {
            $wishlist = get_user_meta( get_current_user_id(), 'crt_wishlist', true );
            $wishlist = get_user_meta($user_id, 'crt_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        if (in_array($product_id, $wishlist)) {
            wp_send_json_error(array('message' => esc_html__('Product is already in wishlist.', 'crt-addons')));
            return;
        }
    
        $wishlist[] = $product_id;
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'crt_wishlist', $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }
    
    function remove_from_wishlist() {
        $nonce = $_POST['nonce'];

        if ( ! wp_verify_nonce($nonce, 'crt-addons-js') || ! isset( $_POST['product_id'] ) ) {
            return;
        }
        $product_id = intval( $_POST['product_id'] );
        $user_id = get_current_user_id();

        if ($user_id > 0) {
            $wishlist = get_user_meta($user_id, 'crt_wishlist', true);
            if (!$wishlist) {
                $wishlist = array();
            }
        } else {
            $wishlist = $this->get_wishlist_from_cookie();
        }
    
        $wishlist = array_diff($wishlist, array($product_id));
    
        if ($user_id > 0) {
            update_user_meta($user_id, 'crt_wishlist', $wishlist);
        } else {
            $this->set_wishlist_to_cookie($wishlist);
        }

        wp_send_json_success();
    }
    
    function get_wishlist_from_cookie() {
        if (isset($_COOKIE['crt_wishlist'])) {
            return json_decode(stripslashes($_COOKIE['crt_wishlist']), true);
        } else if ( isset($_COOKIE['crt_wishlist_'. get_current_blog_id() .'']) ) {
            return json_decode(stripslashes($_COOKIE['crt_wishlist_'. get_current_blog_id() .'']), true);
        }
        return array();
    }
    
    function set_wishlist_to_cookie($wishlist) {
        if ( is_multisite() ) {
            setcookie('crt_wishlist_'. get_current_blog_id() .'', json_encode($wishlist), time() + (86400 * 10), '/'); // Expires in 7 days
        } else {
            setcookie('crt_wishlist', json_encode($wishlist), time() + (86400 * 10), '/'); // Expires in 7 days
        }
    }
}

new CRT_Add_Remove_From_Wishlist();