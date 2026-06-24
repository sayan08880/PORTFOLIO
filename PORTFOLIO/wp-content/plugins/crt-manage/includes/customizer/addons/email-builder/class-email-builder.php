<?php
namespace CrtAddons\Classes\EmailBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Email_Builder {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		// Register Custom Post Type
		add_action( 'init', [ $this, 'register_cpt' ] );

		// Enable Elementor support for the CPT
		add_action( 'elementor/init', [ $this, 'add_cpt_support' ] );

		// Hook into WooCommerce to override email content
		add_filter( 'woocommerce_mail_content', [ $this, 'override_woocommerce_email' ], 10, 1 );

		// Enqueue editor scripts for dynamic preview data
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'enqueue_editor_scripts' ] );
		
		// Setup widgets
		add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
	}

	public function register_cpt() {
		$labels = [
			'name'               => _x( 'Email Templates', 'post type general name', 'crt-manage' ),
			'singular_name'      => _x( 'Email Template', 'post type singular name', 'crt-manage' ),
			'menu_name'          => _x( 'Email Templates', 'admin menu', 'crt-manage' ),
			'name_admin_bar'     => _x( 'Email Template', 'add new on admin bar', 'crt-manage' ),
			'add_new'            => _x( 'Add New', 'email template', 'crt-manage' ),
			'add_new_item'       => __( 'Add New Email Template', 'crt-manage' ),
			'new_item'           => __( 'New Email Template', 'crt-manage' ),
			'edit_item'          => __( 'Edit Email Template', 'crt-manage' ),
			'view_item'          => __( 'View Email Template', 'crt-manage' ),
			'all_items'          => __( 'All Email Templates', 'crt-manage' ),
			'search_items'       => __( 'Search Email Templates', 'crt-manage' ),
			'parent_item_colon'  => __( 'Parent Email Templates:', 'crt-manage' ),
			'not_found'          => __( 'No email templates found.', 'crt-manage' ),
			'not_found_in_trash' => __( 'No email templates found in Trash.', 'crt-manage' )
		];

		$args = [
			'labels'             => $labels,
			'public'             => false,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => true,
			'menu_icon'          => 'dashicons-email',
			'query_var'          => true,
			'rewrite'            => [ 'slug' => 'wc-email-template' ],
			'capability_type'    => 'post',
			'has_archive'        => false,
			'hierarchical'       => false,
			'menu_position'      => null,
			'supports'           => [ 'title', 'editor', 'elementor' ],
			'show_in_rest'       => true,
		];

		register_post_type( 'wc_email_template', $args );
	}

	public function add_cpt_support() {
		add_post_type_support( 'wc_email_template', 'elementor' );
	}

	public function register_widgets( $widgets_manager ) {
	    // Include widget files
	    require_once __DIR__ . '/widgets/class-widget-order-details.php';
	    require_once __DIR__ . '/widgets/class-widget-billing-address.php';
	    require_once __DIR__ . '/widgets/class-widget-shipping-address.php';
	    require_once __DIR__ . '/widgets/class-widget-order-items.php';
	    require_once __DIR__ . '/widgets/class-widget-customer-details.php';
	    require_once __DIR__ . '/widgets/class-widget-payment-method.php';
	    require_once __DIR__ . '/widgets/class-widget-shipping-method.php';

		// Register widgets
		$widgets_manager->register( new \CRT_Widget_Order_Details() );
		$widgets_manager->register( new \CRT_Widget_Billing_Address() );
		$widgets_manager->register( new \CRT_Widget_Shipping_Address() );
		$widgets_manager->register( new \CRT_Widget_Order_Items() );
		$widgets_manager->register( new \CRT_Widget_Customer_Details() );
		$widgets_manager->register( new \CRT_Widget_Payment_Method() );
		$widgets_manager->register( new \CRT_Widget_Shipping_Method() );
	}

	public function enqueue_editor_scripts() {
		// Used to pass the latest real order ID to JS for preview data if needed
	}

	public function override_woocommerce_email( $content ) {
	    // We need to inject the elementor generated context instead of standard output.
	    // This acts globally on WC emails, so we must identify which email is being sent 
	    // and fetch the mapped Template ID. This is typically done by hooking into action specific filters
	    // instead of general but we will intercept here and replace the overall email string.
	    
	    global $crt_current_email_id; // Setup via a different hook where we know which email is firing

	    if ( empty( $crt_current_email_id ) ) {
	        return $content;
	    }

	    $template_id = get_option( 'crt_wc_email_mapping_' . $crt_current_email_id );
	    
	    if ( ! $template_id ) {
	        return $content;
	    }

		if ( class_exists( '\Elementor\Plugin' ) ) {
			$elementor = \Elementor\Plugin::instance();
			$custom_content = $elementor->frontend->get_builder_content_for_display( $template_id );
			
			if ( ! empty( $custom_content ) ) {
			    // Add basic email HTML wrapper
				$html = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>Email</title>';
				$html .= '<style>' . $this->get_inline_css() . '</style>';
				$html .= '</head><body>';
				$html .= $custom_content;
				$html .= '</body></html>';
				return $html;
			}
		}

		return $content;
	}

	private function get_inline_css() {
	    // Optionally fetch Elementor styles and inline them for email compatibility
	    return 'body { margin: 0; padding: 0; font-family: sans-serif; background: #f7f7f7; }';
	}

	/**
	 * Helper to get an order object. 
	 * In Elementor editor (preview), it fetches the latest order to show sample data.
	 * In real environments, it relies on passed context.
	 */
	public static function get_current_order() {
		if ( \Elementor\Plugin::$instance->editor->is_edit_mode() || \Elementor\Plugin::$instance->preview->is_preview_mode() ) {
			// Find latest order for preview
			$orders = wc_get_orders( [
				'limit'   => 1,
				'orderby' => 'date',
				'order'   => 'DESC',
			] );

			if ( ! empty( $orders ) ) {
				return $orders[0];
			}
		}

		// Try to pull from global or hook context if available during wc_send_email execution
		global $crt_email_current_order;
		return $crt_email_current_order;
	}
}

CRT_Email_Builder::instance();
