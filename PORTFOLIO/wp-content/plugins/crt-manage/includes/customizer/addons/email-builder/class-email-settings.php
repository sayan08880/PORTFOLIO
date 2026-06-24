<?php
namespace CrtAddons\Classes\EmailBuilder;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class CRT_Email_Settings {

	private static $_instance = null;

	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'add_settings_page' ], 100 );
		add_action( 'admin_init', [ $this, 'register_settings' ] );
		
		// Hook into WC Emails before sending to capture the Email ID
		add_action( 'woocommerce_email_before_send', [ $this, 'capture_email_id' ], 10, 4 );
	}

	public function add_settings_page() {
		add_submenu_page(
			'edit.php?post_type=wc_email_template',
			__( 'Email Builder Settings', 'crt-manage' ),
			__( 'Settings', 'crt-manage' ),
			'manage_options',
			'wc_email_settings',
			[ $this, 'render_settings_page' ]
		);
	}

	public function register_settings() {
		register_setting( 'crt_email_builder_options', 'crt_wc_email_mapping' );
	}

	public function render_settings_page() {
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}

		if ( isset( $_GET['settings-updated'] ) ) {
			add_settings_error( 'crt_email_messages', 'crt_email_message', __( 'Settings Saved', 'crt-manage' ), 'updated' );
		}
		
		settings_errors( 'crt_email_messages' );

		// Get all Elementor Email Templates
		$templates = get_posts( [
			'post_type'      => 'wc_email_template',
			'posts_per_page' => -1,
			'post_status'    => 'publish',
		] );

		$options = get_option( 'crt_wc_email_mapping', [] );

		// Get Woocommerce Email Classes (Order statuses)
		$wc_emails = WC()->mailer()->get_emails();

		echo '<div class="wrap">';
		echo '<h1>' . esc_html( get_admin_page_title() ) . '</h1>';
		echo '<form action="options.php" method="post">';
		
		settings_fields( 'crt_email_builder_options' );
		
		echo '<table class="form-table" role="presentation">';
		echo '<tbody>';

		foreach ( $wc_emails as $email_id => $email ) {
		    // Only list emails that have order contexts (like new order, processing, completed, etc)
		    // We will skip non-order related emails for simplicity unless requested
		    
		    $current_val = isset( $options[ $email_id ] ) ? $options[ $email_id ] : '';

			echo '<tr>';
			echo '<th scope="row"><label for="mapping_' . esc_attr( $email_id ) . '">' . esc_html( $email->title ) . '</label></th>';
			echo '<td>';
			
			echo '<select id="mapping_' . esc_attr( $email_id ) . '" name="crt_wc_email_mapping[' . esc_attr( $email_id ) . ']">';
			echo '<option value="">' . __( '-- Default WooCommerce Output --', 'crt-manage' ) . '</option>';
			
			foreach ( $templates as $template ) {
				$selected = ( $current_val == $template->ID ) ? 'selected="selected"' : '';
				echo '<option value="' . esc_attr( $template->ID ) . '" ' . $selected . '>' . esc_html( $template->post_title ) . '</option>';
			}
			
			echo '</select>';
			echo '<p class="description">' . esc_html( $email->description ) . '</p>';
			echo '</td>';
			echo '</tr>';
		}

		echo '</tbody>';
		echo '</table>';
		
		submit_button( 'Save Email Mapping' );
		
		echo '</form>';
		echo '</div>';
	}

	public function capture_email_id( $email_class, $email_id, $order, $context ) {
	    // Globalize so the override output can identify the active email template
	    global $crt_current_email_id, $crt_email_current_order;
	    $crt_current_email_id = $email_id;
	    $crt_email_current_order = $order;
	    
	    $options = get_option( 'crt_wc_email_mapping', [] );
	    $template_id = isset( $options[ $email_id ] ) ? $options[ $email_id ] : false;

        // Note: Our override_woocommerce_email filter is setup in class-email-builder.php
        // but we can also inject directly into WooCommerce email template loader if preferred
	}
}

CRT_Email_Settings::instance();
