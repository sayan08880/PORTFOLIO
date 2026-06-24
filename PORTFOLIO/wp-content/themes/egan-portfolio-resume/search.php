<?php
/**
 * The template for displaying search results pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#search-result
 *
 * @package Egan_Portfolio_Resume
 */

get_header();
?>
<main id="content" class="site-main <?php echo esc_attr(egan_portfolio_resume_class_content()); ?> py-5 px-2 px-lg-5">
    <?php do_action('egan_portfolio_resume_archive_header'); ?>
    <section class="block-default mt-4">
        <div class="container-xl">
            <div class="">
                <div class="row bor-col-d">
                    <?php
                        $args = egan_portfolio_resume_archive_layout();
                        $col_one = $args['col_one'];
                        $col_two = $args['col_two'];
                        $layout = $args['layout'];
                        $sidebar_position = $args['sidebar'];
                        $grid = str_contains($layout, 'masonry');
                    ?>
                    <div class="<?php echo esc_attr($col_one); ?>">
                        <div class="archive__inner">
                            <div class="<?php echo esc_attr($grid ? 'grid bor-col-d':'row bor-col-d') ?>">
                                <?php if ( have_posts() ) : ?>
                                    <?php
                                    /* Start the Loop */
                                    while ( have_posts() ) :
                                        the_post();
                                        /*
                                        * Include the Post-Type-specific template for the content.
                                        * If you want to override this in a child theme, then include a file
                                        * called content-___.php (where ___ is the Post Type name) and that will be used instead.
                                        */
                                        get_template_part( 'template-parts/content', $layout );
                                    endwhile;
                                else :
                                    get_template_part( 'template-parts/content', 'none' );
                                endif;
                                ?>
                                <?php egan_portfolio_resume_bor_col($layout); ?>
                            </div>
                            <?php
                                do_action( 'egan_portfolio_resume_posts_pagination' );
                            ?>
                        </div>
                    </div>
                    <div class="<?php echo esc_attr($col_two); ?>">
                        <?php
                        if ( ! is_active_sidebar( $sidebar_position ) ) {
//                            return;
                        }
                        ?>
                        <aside id="secondary" class="widget-area">
                            <?php dynamic_sidebar( $sidebar_position ); ?>
                        </aside>
                    </div>
                    <?php if($col_two != 'd-none'): ?>
                        <div class="br-col br-col66 br-sm-col-none"></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</main><!-- #main -->
<?php
get_footer();
