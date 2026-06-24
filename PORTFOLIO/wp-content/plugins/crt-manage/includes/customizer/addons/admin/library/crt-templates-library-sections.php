<?php
//namespace CrtAddons\Admin\Templates\Library;
use CrtAddons\Classes\Utilities;
use CrtAddons\Admin\Templates\Library\CRT_Templates_Data;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * CRT_Templates_Library_Sections setup
 *
 * @since 1.0
 */
class CRT_Templates_Library_Sections {

	/**
	** Constructor
	*/
	public function __construct() {

		// Template Library Popup
		add_action( 'wp_ajax_render_library_templates_sections', [ $this, 'render_library_templates_sections' ] );

	}

	/**
	** Template Library Popup
	*/
	public static function render_library_templates_sections() {
		$license = 'premium';

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

                            $sections = CRT_Templates_Data::get_available_sections();
                            
							foreach ($sections as $title => $data) {
                                $slug = self::create_slug($title);
								echo '<li data-filter="'. esc_attr($slug) .'">'. esc_html($title) .'</li>';
							}

							?>
						</ul>
					</div>
				</div>
			</div>
			<div class="crt-tplib-search">
				<input type="text" placeholder="Search Template">
				<i class="eicon-search"></i>
			</div>
		</div>

		<div class="crt-tplib-template-gird crt-tplib-sections-grid elementor-clearfix">
			<div class="crt-tplib-template-gird-inner">

			<?php

			foreach ($sections as $title => $data) :
                $slug = self::create_slug($title);

				for ( $i=0; $i < count($data); $i++ ) :

					$template_slug 	 = $slug .'-'. $data[$i];
                    $template_class = ' crt-tplib-pro-active';

			?>

				<div class="crt-tplib-template-wrap<?php echo esc_attr($template_class); ?>" data-title="<?php echo esc_attr(strtolower($title)); ?>">
					<div class="crt-tplib-template" data-slug="<?php echo esc_attr($data[$i]); ?>" data-filter="<?php echo esc_attr($slug); ?>" data-preview-type="image">
						<div class="crt-tplib-template-media">
                            <?php
                                $url = 'https://royal-elementor-addons.com/library/premade-sections/'. $slug .'/'. $data[$i] .'.jpg';
                            ?>
							<img class="lazy" src="<?php echo esc_url(CRT_MANAGE_URI .'assets/img/icon-256x256.png'); ?>" data-src="<?php echo esc_url($url); ?>">
							<div class="crt-tplib-template-media-overlay">
								<i class="eicon-eye"></i>
							</div>
						</div>
						<div class="crt-tplib-template-footer elementor-clearfix">
							<?php $title_v = $title .' '. esc_html($data[$i]);?>
                            <h3><?php echo strpos($template_slug, 'pro') ? esc_html(str_replace('-pro', '', $title_v)) : esc_html(str_replace('-zzz', '', $title_v)); ?></h3>
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

		if ( !(isset($current_screen) && 'royal-addons_page_crt-premade-sections' === $current_screen->id) ) {
			exit;
		}
	}

	public static function create_slug($str, $delimiter = '-'){
		// Try to convert the string with iconv
		$converted_str = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
	
		// If iconv fails, fallback to the original string
		if ($converted_str === false) {
			$converted_str = $str; // Fallback to the original string
		}
	
		// Continue with the rest of the slug generation
		$slug = strtolower(trim(preg_replace('/[\s-]+/', $delimiter, preg_replace('/[^A-Za-z0-9-]+/', $delimiter, preg_replace('/[&]/', 'and', preg_replace('/[\']/', '', $converted_str)))), $delimiter));
		
		return $slug;
	}	

}

new CRT_Templates_Library_Sections();