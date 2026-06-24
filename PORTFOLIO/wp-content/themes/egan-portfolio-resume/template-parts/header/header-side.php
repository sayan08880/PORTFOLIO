<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */
$show_nav_button = get_theme_mod('crt_manage_header_right_show_nav_button');
$show_nav_button_m = get_theme_mod('crt_manage_header_right_show_nav_button_m');
?>
<div class="head__inner">
    <div class="head__logo">
        <?php get_template_part( 'template-parts/header/header-logo' ); ?>
    </div>
    <div class="head__nav">
        <?php get_template_part( 'template-parts/header/header-nav' ); ?>
    </div>
    <div class="head__feature">
        <?php get_template_part( 'template-parts/header/header-right' ); ?>
    </div>
</div>
