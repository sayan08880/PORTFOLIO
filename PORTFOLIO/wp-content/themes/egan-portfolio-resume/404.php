<?php
/**
 * The template for displaying 404 pages (not found)
 *
 * @link https://codex.wordpress.org/Creating_an_Error_404_Page
 *
 * @package Egan_Portfolio_Resume
 */

get_header();
?>
<main id="content" class="site-main <?php echo esc_attr(egan_portfolio_resume_class_content()); ?>">
    <section class="block-default px-50  bg-grey">
        <div class="container-xl">
            <div class="">
                <div class="row">
                    <div class="col-12">
                        <div class="main__404">
                            <h1 class="main__404--title"><?php esc_html_e( '404','egan-portfolio-resume' ); ?></h1>
                            <h3 class="main__404--sub"><?php esc_html_e( 'Page Not Found','egan-portfolio-resume' ); ?></h3>
                            <p class="main__404--intro">
                                <?php esc_html_e( "The page requested couldn't be found. This could a spelling error in the URL or a removed page.",'egan-portfolio-resume' ); ?>
                            </p>
                            <a class="main__404--button" href="<?php echo esc_url(home_url()) ?>"><?php esc_html_e( 'Home Page','egan-portfolio-resume' ); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- #main -->
<?php
get_footer();
