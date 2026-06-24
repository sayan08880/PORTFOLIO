<?php
/**
 * Template Name: Contact
 * The template for displaying all pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */

get_header();
?>
<main id="content" class="site-main <?php echo esc_attr(egan_portfolio_resume_class_content()); ?> py-5 px-2 px-lg-5">
    <section class="custom-block-woo">
        <div class="container-xl">
            <div class="">
                <?php
                while ( have_posts() ) :
                the_post();
                $form = get_post_meta( get_the_ID(), 'crt_manage_form_meta_shortcode', true );
                ?>
                <div class="row">
                    <div class="col-md-6 col-xl-6 mt-5 mb-5">
                        <div class="page-contact">
                            <div class="page-contact__headline">
                                <?php the_title( '<h3 class="entry-title heading-default-single">', '</h1>' ); ?>
                            </div>
                            <div class="page-contact__intro">
                                <?php
                                    the_content();
                                ?>
                            </div>
                            <div class="mt-3">
                                <?php get_template_part( 'template-parts/header-social', '', array('class' => 'justify-content-end', 'style' => 'border-line-solid') ); ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 col-xl-6 mt-5 mb-5">
                        <div class="tenzin-news-magazine-form">
                            <?php echo do_shortcode($form);?>
                        </div>
                    </div>
                </div>

                <?php
                    // If comments are open or we have at least one comment, load up the comment template.
                    if ( comments_open() || get_comments_number() ) :
                        comments_template();
                    endif;
                endwhile; // End of the loop.
                ?>
            </div>
        </div>
    </section>
</main><!-- #main -->
<?php
get_footer();
