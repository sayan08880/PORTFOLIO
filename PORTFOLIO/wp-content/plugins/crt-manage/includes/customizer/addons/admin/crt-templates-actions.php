<?php

use CrtAddons\Plugin;
use Elementor\TemplateLibrary\Source_Base;
use Elementor\Core\Common\Modules\Ajax\Module as Ajax;
use CrtAddons\Classes\Utilities;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}


/**
 * CRT_Templates_Actions setup
 *
 * @since 1.0
 */
class CRT_Templates_Actions {

	/**
	** Constructor
	*/
	public function __construct() {

		// Save Conditions
		add_action( 'wp_ajax_crt_save_template_conditions', [ $this, 'crt_save_template_conditions' ] );

		// Create Template
		add_action( 'wp_ajax_crt_create_template', [ $this, 'crt_create_template' ] );

		// Import Library Template
		add_action( 'wp_ajax_crt_import_library_template', [ $this, 'crt_import_library_template' ] );

		// Reset Template
		add_action( 'wp_ajax_crt_delete_template', [ $this, 'crt_delete_template' ] );

		// Install/Activate Backup Plugin
		add_action( 'wp_ajax_crt_install_activate_backup_plugin', [ $this, 'crt_install_activate_backup_plugin' ] );

		// Dismiss Backup Popup Permanently
		add_action( 'wp_ajax_crt_dismiss_backup_popup', [ $this, 'crt_dismiss_backup_popup' ] );

		// Set Pending Template for Backup Reminder
		add_action( 'wp_ajax_crt_set_pending_template', [ $this, 'crt_set_pending_template' ] );

		// Register Elementor AJAX Actions
//		add_action( 'elementor/ajax/register_actions', [ $this, 'register_elementor_ajax_actions' ] );

		// Enqueue Scripts
//		add_action( 'admin_enqueue_scripts', [ $this, 'templates_library_scripts' ] );

	}

	/**
	** Save Template Conditions
	*/
	public function crt_save_template_conditions() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template = isset($_POST['template']) ? sanitize_text_field(wp_unslash($_POST['template'])): false;

		// Header
		if ( isset($_POST['crt_header_conditions']) ) {
			update_option( 'crt_header_conditions', $this->sanitize_conditions($_POST['crt_header_conditions']) );  // phpcs:ignore

			$crt_header_show_on_canvas = isset($_POST['crt_header_show_on_canvas']) ? sanitize_text_field(wp_unslash($_POST['crt_header_show_on_canvas'])): false;
			if ( $crt_header_show_on_canvas && $template ) {
				update_post_meta( Utilities::get_template_id($template), 'crt_header_show_on_canvas', $crt_header_show_on_canvas );
			}
		}

		// Footer
		if ( isset($_POST['crt_footer_conditions']) ) {
			update_option( 'crt_footer_conditions', $this->sanitize_conditions($_POST['crt_footer_conditions']) );  // phpcs:ignore

			$crt_footer_show_on_canvas = isset($_POST['crt_footer_show_on_canvas']) ? sanitize_text_field(wp_unslash($_POST['crt_footer_show_on_canvas'])): false;
			if ( $crt_footer_show_on_canvas && $template ) {
				update_post_meta( Utilities::get_template_id($template), 'crt_footer_show_on_canvas', $crt_footer_show_on_canvas );
			}
		}

		// Archive
		if ( isset($_POST['crt_archive_conditions']) ) {
			update_option( 'crt_archive_conditions', $this->sanitize_conditions($_POST['crt_archive_conditions']) );  // phpcs:ignore
		}

		// Single
		if ( isset($_POST['crt_single_conditions']) ) {
			update_option( 'crt_single_conditions', $this->sanitize_conditions($_POST['crt_single_conditions']) );  // phpcs:ignore
		}

		// Product Archive
		if ( isset($_POST['crt_product_archive_conditions']) ) {
			update_option( 'crt_product_archive_conditions', $this->sanitize_conditions($_POST['crt_product_archive_conditions']) );  // phpcs:ignore
		}

		// Product Single
		if ( isset($_POST['crt_product_single_conditions']) ) {
			update_option( 'crt_product_single_conditions', $this->sanitize_conditions($_POST['crt_product_single_conditions']) );  // phpcs:ignore
		}

		// Popup
		if ( isset($_POST['crt_popup_conditions']) ) {
			update_option( 'crt_popup_conditions', $this->sanitize_conditions($_POST['crt_popup_conditions']) );  // phpcs:ignore
		}
	}

	public function sanitize_conditions( $data ) {
		return wp_unslash( json_encode( array_filter( json_decode(stripcslashes($data), true) ) ) );
	}

	/**
	** Create Template
	*/
	public function crt_create_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-options-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$user_template_type = isset($_POST['user_template_type']) ? sanitize_text_field(wp_unslash($_POST['user_template_type'])): false;
		$user_template_library = isset($_POST['user_template_library']) ? sanitize_text_field(wp_unslash($_POST['user_template_library'])): false;
		$user_template_title = isset($_POST['user_template_title']) ? sanitize_text_field(wp_unslash($_POST['user_template_title'])): false;
		$user_template_slug = isset($_POST['user_template_slug']) ? sanitize_text_field(wp_unslash($_POST['user_template_slug'])): false;
		
		$check_post_type =( $user_template_library == 'crt_templates' || $user_template_library == 'elementor_library' );

		if ( $user_template_title && $check_post_type ) {
			// Create
			$template_id = wp_insert_post(array (
				'post_type' 	=> $user_template_library,
				'post_title' 	=> $user_template_title,
				'post_name' 	=> $user_template_slug,
				'post_content' 	=> '',
				'post_status' 	=> 'publish'
			));

			// Set Types
			if ( 'crt_templates' === $_POST['user_template_library'] ) {

				wp_set_object_terms( $template_id, [$user_template_type, 'user'], 'crt_template_type' );

				if ( 'popup' === $_POST['user_template_type'] ) {
					update_post_meta( $template_id, '_elementor_template_type', 'crt-popups' );
				} else {
					if ( 'header' === $_POST['user_template_type'] ) {
						update_post_meta( $template_id, '_elementor_template_type', 'crt-theme-builder-header' );
					} elseif ( 'footer' === $_POST['user_template_type'] ) {
						update_post_meta( $template_id, '_elementor_template_type', 'crt-theme-builder-footer' );
					} else {
						update_post_meta( $template_id, '_elementor_template_type', 'crt-theme-builder' );
					}

					update_post_meta( $template_id, '_crt_template_type', $user_template_type );
				}
			} else {
				update_post_meta( $template_id, '_elementor_template_type', 'page' );
			}

			// Set Canvas Template
			update_post_meta( $template_id, '_wp_page_template', 'elementor_canvas' ); //tmp - maybe set for crt_templates only

			// Send ID to JS
			echo esc_html($template_id);
		}
	}

	/**
	** Import Library Template
	*/
	public function crt_import_library_template() {

		$nonce = $_POST['nonce'];
		if ( !wp_verify_nonce( $nonce, 'crt-addons-library-frontend-js')  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

        $source = new CRT_Library_Source();
		$slug = isset($_POST['slug']) ? sanitize_text_field(wp_unslash($_POST['slug'])) : '';
		$kit = isset($_POST['kit']) ? sanitize_text_field(wp_unslash($_POST['kit'])) : '';
		$section = isset($_POST['section']) ? sanitize_text_field(wp_unslash($_POST['section'])) : '';

        $data = $source->get_data([
        	'template_id' => $slug,
			'kit_id' => $kit,
			'section_id' => $section
        ]);
		$template = '' !== $kit ? $kit : $slug;
        $data = str_replace('wpr', 'crt', json_encode($data));
        $data = str_replace('Royal Elementor', 'CRThemes', $data);
        $data = str_replace('https://royal-elementor-addons.com', 'https://crthemes.com', $data);
        echo $data;
	}

	/**
	** Validate Template
	*/
	public function vts( $template ) {
		return true;
	}

	/**
	** Reset Template
	*/
	public function crt_delete_template() {

		$nonce = $_POST['nonce'];

		if ( !wp_verify_nonce( $nonce, 'delete_post-' . $_POST['template_slug'] )  || !current_user_can( 'manage_options' ) ) {
		  exit; // Get out of here, the nonce is rotten!
		}

		$template_slug = isset($_POST['template_slug']) ? sanitize_text_field(wp_unslash($_POST['template_slug'])): '';
		$template_library = isset($_POST['template_library']) ? sanitize_text_field(wp_unslash($_POST['template_library'])): '';

		$post = get_page_by_path( $template_slug, OBJECT, $template_library );

		if ( get_post_type($post->ID) == 'crt_templates' || get_post_type($post->ID) == 'elementor_library' ) {
			wp_delete_post( $post->ID, true );
		}
	}

	/**
	** Install/Activate Backup Plugin
	*/
	public function crt_install_activate_backup_plugin() {
		$nonce = isset($_POST['nonce']) ? sanitize_text_field($_POST['nonce']) : '';

		if ( !wp_verify_nonce( $nonce, 'crt-plugin-options-js' ) || !current_user_can( 'install_plugins' ) ) {
			wp_send_json_error( ['message' => 'Unauthorized'] );
		}

		// Save pending template data as transient EARLY (before any success returns).
		if ( isset($_POST['pending_edit_url']) && !empty($_POST['pending_edit_url']) ) {
			$template_data = array(
				'url'  => sanitize_url( $_POST['pending_edit_url'] ),
				'name' => isset( $_POST['pending_template_name'] ) ? sanitize_text_field( $_POST['pending_template_name'] ) : '',
			);
			set_transient( 'crt_pending_template_edit', $template_data, 5 * MINUTE_IN_SECONDS );
		}

		$plugin_slug = 'royal-backup-reset';
		$plugin_file = 'royal-backup-reset/royal-backup-reset.php';

		// Check if plugin is already active
		if ( is_plugin_active( $plugin_file ) ) {
			wp_send_json_success( ['status' => 'active', 'message' => 'Plugin is already active'] );
		}

		// Check if plugin is installed but not active
		if ( file_exists( WP_PLUGIN_DIR . '/' . $plugin_file ) ) {
			$result = activate_plugin( $plugin_file );
			if ( is_wp_error( $result ) ) {
				wp_send_json_error( ['message' => $result->get_error_message()] );
			}
			wp_send_json_success( ['status' => 'activated', 'message' => 'Plugin activated successfully'] );
		}

		// Plugin needs to be installed
		require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
		require_once ABSPATH . 'wp-admin/includes/plugin-install.php';

		$api = plugins_api( 'plugin_information', [
			'slug' => $plugin_slug,
			'fields' => ['sections' => false],
		] );

		if ( is_wp_error( $api ) ) {
			wp_send_json_error( ['message' => $api->get_error_message()] );
		}

		$skin = new \WP_Ajax_Upgrader_Skin();
		$upgrader = new \Plugin_Upgrader( $skin );
		$result = $upgrader->install( $api->download_link );

		if ( is_wp_error( $result ) ) {
			wp_send_json_error( ['message' => $result->get_error_message()] );
		}

		if ( $result === false ) {
			wp_send_json_error( ['message' => 'Plugin installation failed'] );
		}

		// Activate the plugin
		$activate_result = activate_plugin( $plugin_file );
		if ( is_wp_error( $activate_result ) ) {
			wp_send_json_error( ['message' => $activate_result->get_error_message()] );
		}

		wp_send_json_success( ['status' => 'installed', 'message' => 'Plugin installed and activated successfully'] );
	}

	/**
	 * Dismiss backup popup permanently for the current user.
	 */
	public function crt_dismiss_backup_popup() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'crt-plugin-options-js' ) || ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
		}

		$user_id = get_current_user_id();
		update_user_meta( $user_id, 'crt_dismiss_backup_popup', '1' );

		wp_send_json_success();
	}

	/**
	 * Set pending template transient for Royal Backup reminder.
	 */
	public function crt_set_pending_template() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( $_POST['nonce'] ) : '';

		if ( ! wp_verify_nonce( $nonce, 'crt-plugin-options-js' ) || ! current_user_can( 'edit_posts' ) ) {
			wp_send_json_error( [ 'message' => 'Unauthorized' ] );
		}

		if ( isset( $_POST['pending_edit_url'] ) && ! empty( $_POST['pending_edit_url'] ) ) {
			$template_data = array(
				'url'  => sanitize_url( $_POST['pending_edit_url'] ),
				'name' => isset( $_POST['pending_template_name'] ) ? sanitize_text_field( $_POST['pending_template_name'] ) : '',
			);
			set_transient( 'crt_pending_template_edit', $template_data, 5 * MINUTE_IN_SECONDS );
		}

		wp_send_json_success();
	}

	/**
	** Enqueue Scripts and Styles
	*/
	public function templates_library_scripts( $hook ) { }

	/**
	** Register Elementor AJAX Actions
	*/
	public function register_elementor_ajax_actions( Ajax $ajax ) { }
}

new CRT_Templates_Actions();

/**
 * CRT_Templates_Actions setup
 *
 * @since 1.0
 */
class CRT_Library_Source extends \Elementor\TemplateLibrary\Source_Base {

	public function get_id() {
		return 'crt-layout-manager';
	}

	public function get_title() {
		return 'CRT Layout Manager';
	}

	public function register_data() {}

	public function save_item( $template_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot save template to a CRT layout manager' );
	}

	public function update_item( $new_data ) {
		return new \WP_Error( 'invalid_request', 'Cannot update template to a CRT layout manager' );
	}

	public function delete_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot delete template from a CRT layout manager' );
	}

	public function export_template( $template_id ) {
		return new \WP_Error( 'invalid_request', 'Cannot export template from a CRT layout manager' );
	}

	public function get_items( $args = [] ) {
		return [];
	}

	public function get_item( $template_id ) {
		$templates = $this->get_items();

		return $templates[ $template_id ];
	}

	public function request_template_data( $template_id, $kit_id, $section_id ) {
		if ( empty( $template_id ) ) {
			return;
		}

		if ( '' !== $kit_id ) {
		    //Templates kit
			$url = 'https://demo1.crthemes.com/data/library/kit/'. $kit_id .'/';
		} elseif ( '' !== $section_id ) {
		    //Sections
			$url = 'https://demo1.crthemes.com/data/library/sections/';
			// $url = 'https://royal-elementor-addons.com/library/premade-sections/'. $kit_id .'/';
		} else {
		    //Blocks
			$url = 'https://demo1.crthemes.com/data/library/blocks/';
		}

		// Avoid Cache
		$randomNum = substr(str_shuffle("0123456789abcdefghijklmnopqrstvwxyzABCDEFGHIJKLMNOPQRSTVWXYZ"), 0, 7);

//      $site = 'https://demo1.crthemes.com/elementor/wp-json/library/header/1.json?='. $randomNum;
//		$response = wp_remote_get( $site, [
//			'timeout'   => 60,
//			'sslverify' => false
//		] );

        $response = wp_remote_get($url . $template_id .'.json?='. $randomNum, [
            'timeout'   => 60,
            'sslverify' => false
        ] );
		
		return wp_remote_retrieve_body( $response );
	}

	public function get_data( array $args ) {
		$data = $this->request_template_data( $args['template_id'], $args['kit_id'], $args['section_id'] );

		$data = json_decode( $data, true );

		if ( empty( $data ) || empty( $data['content'] ) ) {
			throw new \Exception( 'Template does not have any content' );
		}

		add_filter( 'intermediate_image_sizes_advanced', [new Utilities, 'disable_extra_image_sizes'], 10, 3 );

		// Remove Parallax Images from Import File
		foreach( $data['content'] as $key => $content ) {
			if ( isset($data['content'][$key]['settings']['bg_image']) ) {
				unset($data['content'][$key]['settings']['bg_image']);
			}
			if ( isset($data['content'][$key]['settings']['hover_parallax']) ) {
				unset($data['content'][$key]['settings']['hover_parallax']);
			}
		}

		$parallax_bg = get_option('crt-parallax-background', 'on');
		$parallax_multi = get_option('crt-parallax-multi-layer', 'on');

		// Disable Extensions during Import
		if ( 'on' === $parallax_bg ) {
			update_option('crt-parallax-background', '');
		}
		if ( 'on' === $parallax_multi ) {
			update_option('crt-parallax-multi-layer', '');
		}

		$data['content'] = $this->replace_elements_ids( $data['content'] );		
		$data['content'] = $this->process_export_import_content( $data['content'], 'on_import' );

		// Enable Back
		if ( 'on' === $parallax_bg ) {
			update_option('crt-parallax-background', 'on');
		}
		if ( 'on' === $parallax_multi ) {
			update_option('crt-parallax-multi-layer', 'on');
		}

		return $data;
	}

}