<?php
//namespace CrtAddons\Admin\Templates\Library;
use CrtAddons\Classes\Utilities;
use CrtAddons\Admin\Templates\Library\CRT_Templates_Data;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CRT_Templates_Library_Blocks setup
 *
 * @since 1.0
 */
class CRT_Templates_Library_Blocks {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_blocks', [ $this, 'render_library_templates_blocks' ] );

	}

	/**
	** Template Library Popup
	*/
	public static function render_library_templates_blocks() {
		$license = !defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code() ? 'free' : 'premium';

		?>

		<div class="crt-tplib-sidebar" data-license="<?php echo esc_attr($license); ?>">
			<div class="crt-tplib-filters-wrap">
				<div class="crt-tplib-filters">
					<h3>
						<span data-filter="all"><?php esc_html_e( 'Category', 'crt-addons' ); ?></span>
						<i class="fas fa-angle-down"></i>
					</h3>

					<div class="crt-tplib-filters-list">
						<ul>

							<li data-filter="all"><?php esc_html_e( 'All', 'crt-addons' ) ?></li>

							<?php

							$modules = Utilities::get_available_modules( Utilities::get_registered_modules() );

							$exclude_widgets = [
								'logo',
								'mega-menu',
								'forms',
								'phone-call',
								'back-to-top',
								'popup-trigger',
								'lottie-animations',
								'taxonomy-list',
								'page-list',
								'elementor-template',
								'flip-carousel',
								'feature-list',
								'dual-color-heading',
								'reading-progress-bar',
								'image-accordion',
								'advanced-accordion',
								'charts',
							];
							
							foreach ($modules as $title => $slug) {
								if ( ! in_array($slug[0], $exclude_widgets) ) {
									echo '<li data-filter="'. esc_attr($slug[0]) .'">'. esc_html($title) .'</li>';
								}
							}

							?>
						</ul>
					</div>
				</div>

				<div class="crt-tplib-sub-filters">
					<ul>
						<li data-sub-filter="all" class="crt-tplib-activ-filter"><?php esc_html_e( 'All', 'crt-addons' ); ?></li>
						<li data-sub-filter="grid"><?php esc_html_e( 'Grid', 'crt-addons' ) ?></li>
						<li data-sub-filter="slider"><?php esc_html_e( 'Slider', 'crt-addons' ) ?></li>
						<li data-sub-filter="carousel"><?php esc_html_e( 'Carousel', 'crt-addons' ) ?></li>
					</ul>
				</div>
			</div>
			<div class="crt-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>

		<div class="crt-tplib-template-gird elementor-clearfix">
			<div class="crt-tplib-template-gird-inner">

			<?php

			foreach ($modules as $title => $data) :
				$module_slug = $data[0];
				$blocks = CRT_Templates_Data::get_available_blocks();

				if ( !isset($blocks[$module_slug]) ) {
					continue;
				}

				for ( $i=0; $i < count($blocks[$module_slug]); $i++ ) :

					$template_slug 	= array_keys($blocks[$module_slug])[$i];
					$template_sub 	= isset($blocks[$module_slug][$template_slug]['sub']) ? $blocks[$module_slug][$template_slug]['sub'] : '';
					$template_title = $title .' '. $template_slug;
					$preview_type 	= $blocks[$module_slug][$template_slug]['type'];
					$preview_url 	= $blocks[$module_slug][$template_slug]['url'];
					$template_class = (strpos($template_slug, 'pro') && (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) ) || (strpos($template_slug, 'zzz') && (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) ) ? ' crt-tplib-pro-wrap' : '';

					if (true) {
						$template_class .= ' crt-tplib-pro-active';
					}

					$template_slug_for_image = strpos($template_slug, 'zzz') ? substr($template_slug, 0, -4) : $template_slug;

					// Add Extra Keywords for Search
					$data_template_title = $template_title;
					if ( false !== strpos($title, 'Form Builder') ) {
						$data_template_title .= ' contact';
					} else if ( false !== strpos($title, 'Nav Menu') ) {
						$data_template_title .= ' header';
					} else if ( false !== strpos($title, 'Post Grid') ) {
						$data_template_title .= ' blog';
					}

			?>

				<div class="crt-tplib-template-wrap<?php echo esc_attr($template_class); ?>" data-title="<?php echo esc_attr(strtolower($data_template_title)); ?>">
					<div class="crt-tplib-template" data-slug="<?php echo esc_attr($template_slug); ?>" data-filter="<?php echo esc_attr($module_slug); ?>" data-sub-filter="<?php echo esc_attr($template_sub); ?>" data-preview-type="<?php echo esc_attr($preview_type); ?>" data-preview-url="<?php echo esc_attr($preview_url); ?>">
						<div class="crt-tplib-template-media">
							<img  class="lazy" src="<?php echo esc_url(CRT_MANAGE_URI .'assets/img/icon-256x256.png'); ?>" data-src="<?php echo esc_url('https://royal-elementor-addons.com/library/premade-styles/'. $module_slug .'/'. $template_slug_for_image .'.jpg'); ?>">
							<div class="crt-tplib-template-media-overlay">
								<i class="eicon-eye"></i>
							</div>
						</div>
						<div class="crt-tplib-template-footer elementor-clearfix">
							<?php if ( !defined('CRT_ADDONS_PRO_VERSION') && (!defined('CRT_ADDONS_PRO_VERSION') || !crt_fs()->can_use_premium_code()) ) : ?>
								<h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', ' Pro', $template_title)) : esc_html(str_replace('-zzz', ' Pro', $template_title)); ?></h3>
							<?php else : ?>
								<h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', '', $template_title)) : esc_html(str_replace('-zzz', '', $template_title)); ?></h3>
							<?php endif; ?>

                            <span class="crt-tplib-insert-template"><i class="eicon-file-download"></i> <span><?php esc_html_e( 'Insert', 'crt-addons' ); ?></span></span>
						</div>
					</div>
				</div>

				<?php endfor; ?>
			<?php endforeach;?>

			</div>
		</div>

		<?php

		$current_screen = get_current_screen();

		if ( !(isset($current_screen) && 'royal-addons_page_crt-premade-blocks' === $current_screen->id) ) {
			exit;
		}
	}

}

new CRT_Templates_Library_Blocks();