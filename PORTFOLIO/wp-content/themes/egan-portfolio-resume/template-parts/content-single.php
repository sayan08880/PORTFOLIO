<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */

?>
    <div class="single-detail__left">
        <div class="single-detail__share single-share-js">
            <?php get_template_part( 'template-parts/content', 'single-share' ); ?>
        </div>
        <div class="single-detail__content" id="single-height-js">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="entry-content editor-content">
                    <?php
                    the_content(
                        sprintf(
                            wp_kses(
                            /* translators: %s: Name of current post. Only visible to screen readers */
                                __( 'Continue reading<span class="screen-reader-text"> "%s"</span>','egan-portfolio-resume' ),
                                array(
                                    'span' => array(
                                        'class' => array(),
                                    ),
                                )
                            ),
                            wp_kses_post( get_the_title() )
                        )
                    );

                    wp_link_pages(
                        array(
                            'before' => '<div class="page-links">' . esc_html__( 'Pages:','egan-portfolio-resume' ),
                            'after'  => '</div>',
                        )
                    );
                    ?>
                </div><!-- .entry-content -->

                <footer class="entry-footer entry-footer--single">
                    <?php egan_portfolio_resume_entry_single_footer(); ?>
                </footer><!-- .entry-footer -->
            </article><!-- #post-<?php the_ID(); ?> -->


        </div>
    </div>

