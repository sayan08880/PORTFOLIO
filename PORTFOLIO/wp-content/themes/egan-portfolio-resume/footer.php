<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package Egan_Portfolio_Resume
 */

?>
<!-- start footer -->
<footer class="footer <?php echo esc_attr(egan_portfolio_resume_class_content()); ?> py-4" itemscope="" itemtype="https://schema.org/WPFooter">
    <div class="container">
        <div class="row">
            <div class="col-12 col-md-7">
                <div class="d-flex align-items-center flex-wrap justify-content-center justify-content-md-start">
                    <?php if ( has_custom_logo() ) : ?>
                        <?php
                            $logo = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );
                        ?>
                        <h2 class="footer__logo m-0 text-center text-md-start">
                            <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                <img src="<?php echo esc_attr($logo ? $logo : get_template_directory_uri() . '/assets/img/logo.png'); ?>" alt="<?php bloginfo( 'name' ); ?>">
                            </a>
                        </h2>
                    <?php else : ?>
                        <div class="footer__logo--text">
                            <h3 class="m-0"><?php bloginfo( 'name' ); ?></h3>
                        </div>
                    <?php endif; ?>
                    <?php
                        if ( has_nav_menu( 'footer' ) ) {
                            wp_nav_menu(
                                array(
                                    'menu_class' => 'footer-nav list-unstyled d-flex mt-2 mt-md-0 m-0 ms-md-4',
                                    'container' => false,
                                    'theme_location' => 'footer',
                                )
                            );
                        }
                    ?>
                </div>
            </div>
            <div class="col-12 col-md-5">
                <?php
                   $text_footer = get_theme_mod('crt_manage_footer_copyright', __('Copyright © 2025 All rights reserved.','egan-portfolio-resume'));
                ?>
                <p class="mt-3 mt-md-0 m-0 text-center text-md-end"><?php echo esc_html($text_footer); ?></p>
            </div>
        </div>
    </div>
</footer>

<div id="progress"><span id="progress-value"><i class="fa-solid fa-angle-up"></i></span></div>
<!-- end footer -->

<?php wp_footer(); ?>

</body>
</html>
