<?php
/**
 * Pro Portfolio functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage Pro Portfolio
 * @since Pro Portfolio 1.0.1
 */


if ( ! function_exists( 'pro_portfolio_support' ) ) :

	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * @since Pro Portfolio 1.0.1
	 *
	 * @return void
	 */
	function pro_portfolio_support() {

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style.css' );

		// Experimental support for adding blocks inside nav menus
		add_theme_support( 'block-nav-menus' );

		// Add support for experimental link color control.
		add_theme_support( 'experimental-link-color' );
	}

endif;

add_action( 'after_setup_theme', 'pro_portfolio_support' );

/**
 * Enqueue scripts and styles.
 */
function pro_portfolio_scripts() {

	// Main style.
	wp_enqueue_style( 'pro-portfolio-style', get_template_directory_uri() . '/style.css', array(), '1.0.1' );

}
add_action( 'wp_enqueue_scripts', 'pro_portfolio_scripts' );
