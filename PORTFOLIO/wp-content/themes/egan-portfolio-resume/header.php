<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div >
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Egan_Portfolio_Resume
 */
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="https://gmpg.org/xfn/11">
	<?php wp_head(); ?>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>
    <a class="skip-link screen-reader-text" href="#content"><?php esc_html_e( 'Skip to content','egan-portfolio-resume' ); ?></a>

    <?php do_action('egan_portfolio_resume_header'); ?>

