<?php
//namespace CrtAddons\Admin\Templates\Library;
use CrtAddons\Classes\Utilities;
use CrtAddons\Admin\Templates\Library\CRT_Templates_Data;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CRT_Templates_Library_Popups setup
 *
 * @since 1.0
 */
class CRT_Templates_Library_Popups {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_popups', [ $this, 'render_library_templates_popups' ] );

	}

	/**
	** Template Library Popup
	*/
	public function render_library_templates_popups() {
		$license = !defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ? 'free' : 'premium';

		?>

		<div class="crt-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="crt-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>

			<div class="crt-tplib-filters-wrap">
				<div class="crt-tplib-filters">
					<h3>
						<span><?php esc_html_e( 'Category', 'crt-addons' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</h3>

					<div class="crt-tplib-filters-list">
						<ul>
							<li data-filter="all"><?php esc_html_e( 'All', 'crt-addons' ) ?></li>
							<li data-filter="cookie"><?php esc_html_e( 'Cookie', 'crt-addons' ) ?></li>
							<li data-filter="discount"><?php esc_html_e( 'Discount', 'crt-addons' ) ?></li>
							<li data-filter="subscribe"><?php esc_html_e( 'Subscribe', 'crt-addons' ) ?></li>
							<li data-filter="yesno"><?php esc_html_e( 'Yes/No', 'crt-addons' ) ?></li>
						</ul>
					</div>
				</div>
			</div>

		</div>

		<div class="crt-tplib-template-gird elementor-clearfix">
			<div class="crt-tplib-template-gird-inner">

			<?php

			$popups = CRT_Templates_Data::get_available_popups();

			foreach ($popups as $type => $data) :

				for ( $i=0; $i < count($popups[$type]); $i++ ) :

					$template_slug 	= array_keys($popups[$type])[$i];
					$template_title = ucfirst($type) .' '. $template_slug;
					$preview_type 	= $popups[$type][$template_slug]['type'];
					$preview_url 	= $popups[$type][$template_slug]['url'];
					$template_class = ( strpos($template_slug, 'pro') && (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) ) ? ' crt-tplib-pro-wrap' : '';

			?>

			<div class="crt-tplib-template-wrap<?php echo esc_attr($template_class); ?>">
				<div class="crt-tplib-template" data-slug="<?php echo esc_attr($template_slug); ?>" data-filter="<?php echo esc_attr($type); ?>" data-preview-type="<?php echo esc_attr($preview_type); ?>" data-preview-url="<?php echo esc_attr($preview_url); ?>">
					<div class="crt-tplib-template-media">
						<img class="lazy" src="<?php echo esc_url(CRT_ADDONS_ASSETS_URL .'img/icon-256x256.png'); ?>" data-src="<?php echo esc_url('https://royal-elementor-addons.com/library/premade-styles/popups/'. $type .'/'. $template_slug .'.jpg'); ?>">
						<div class="crt-tplib-template-media-overlay">
							<i class="eicon-eye"></i>
						</div>
					</div>
					<div class="crt-tplib-template-footer elementor-clearfix">
						<h3><?php echo esc_html(str_replace('-pro', ' Pro', $template_title)); ?></h3>

						<?php if ( strpos($template_slug, 'pro') && (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) ) : ?>
							<span class="crt-tplib-insert-template crt-tplib-insert-pro"><i class="eicon-star"></i> <span><?php esc_html_e( 'Go Pro', 'crt-addons' ); ?></span></span>
						<?php else : ?>
							<span class="crt-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'crt-addons' ); ?></span></span>
						<?php endif; ?>
					</div>
				</div>
			</div>

				<?php endfor; ?>
			<?php endforeach; ?>

			</div>
		</div>

		<?php exit();
	}

}