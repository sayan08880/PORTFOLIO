<?php
/**
 * The template for displaying archive pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */

get_header();
?>
<main id="content" class="<?php echo esc_attr(egan_portfolio_resume_class_content()); ?>">
    <?php
        if ( is_front_page() && ! is_home() && crt_manage_plugins_is_active()) :
            do_action('crt_manage_theme_sections');
        elseif ( is_front_page() && is_home() || !crt_manage_plugins_is_active()) :
            require get_template_directory() . '/home.php';
        endif;
    ?>
</main>
<?php
get_footer();
