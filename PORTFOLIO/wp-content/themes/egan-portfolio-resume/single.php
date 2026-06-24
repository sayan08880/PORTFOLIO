<?php
/**
 * The template for displaying all single posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/#single-post
 *
 * @package Egan_Portfolio_Resume
 */

get_header();
?>
<?php
    $post_id = get_the_ID();
    $thumbnail = get_theme_mod('crt_manage_single_thumbnail', 'outer-thumb');
    $args = egan_portfolio_resume_archive_layout();
    $col_one = $args['col_one'];
    $col_two = $args['col_two'];
    $sidebar_position = $args['sidebar'];
    $related_layout = $args['layout'];
    $layout = get_theme_mod('crt_manage_single_sidebar', 'right-sidebar');
    if(get_post_format() == 'aside') {
        $layout = 'right-sidebar';
    }
    egan_portfolio_resume_set_post_view_count($post_id);
?>
<main id="content" class="site-main <?php echo esc_attr(egan_portfolio_resume_class_content()); ?> py-5 px-2 px-lg-5" itemscope="" itemtype="https://schema.org/CreativeWork">
    <section class="single-header">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center pb-4 pb-sm-5">
                    <div class="breadcrumb-option">
                        <?php do_action('egan_portfolio_resume_breadcrumb'); ?>
                    </div>
                    <div class="single-heading-default">
                        <div class="entry mt-3 d-flex justify-content-center">
                            <?php egan_portfolio_resume_entry_options(get_post($post_id), array('class' => 'd-flex justify-content-center', 'entry_date' => true, 'entry_cat' => false, 'entry_author' => true, 'entry_read_time' => false, 'entry_comment' => false, 'entry_view_count' => true)); ?>
                        </div>
                        <?php the_title( '<h1 class="single-heading-default__title text-center">', '</h1>' ); ?>
                        <div class="single-entry__excerpt">
                            <?php echo egan_portfolio_resume_excerpt_custom(30, $post_id); ?>
                        </div>
                        <div class="single-entry__cat">
                            <?php egan_portfolio_resume_entry_category($post_id) ?>
                        </div>

                    </div>
                </div>
                <div class="col-12">
                    <?php egan_portfolio_resume_post_thumb('image-default lazy ratio169 mb-3') ?>
                </div>
            </div>
        </div>
    </section>
    <section class="block-content mt-4">
        <div class="container">
            <div class="">
                <div class="row">
                    <div class="<?php echo esc_attr($col_one); ?>">
                        <div class="single-detail__inner">
                        <?php
                        while ( have_posts() ) :
                            the_post();

                            get_template_part( 'template-parts/content', 'single' );

                            do_action('egan_portfolio_resume_post_navigation');

                            do_action( 'egan_portfolio_resume_author', $post );

                            // If comments are open or we have at least one comment, load up the comment template.
                            if ( comments_open() || get_comments_number() ) :
                                comments_template();
                            endif;

                            // Related Posts
                            if ( is_singular( 'post' ) ) {
                                $related_heading = get_theme_mod( 'crt_manage_single_related_heading', __( 'Related Posts','egan-portfolio-resume' ) );
                                $grid = str_contains($related_layout, 'masonry');
                                $cat_content_id      = get_the_category( $post->ID )[0]->term_id;
                                $args                = array(
                                    'cat'            => $cat_content_id,
                                    'post__not_in'   => array( $post->ID ),
                                    'orderby'        => 'rand',
                                );
                                $query               = new WP_Query( $args );
                                if ( $query->have_posts() ) :
                                    ?>
                                    <div class="related-posts mt-4">
                                        <h2><?php echo esc_html( $related_heading ); ?></h2>
                                        <div class="<?php echo esc_attr($grid ? 'grid':'row') ?>">
                                            <?php
                                                while ( $query->have_posts() ) :
                                                $query->the_post();
                                                get_template_part( 'template-parts/content', $related_layout );
                                                endwhile;
                                                wp_reset_postdata();
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                endif;
                            }



                        endwhile; // End of the loop.
                        ?>
                        </div>
                    </div>
                    <div class="<?php echo esc_attr($col_two); ?>">
                        <?php if ($col_two != 'd-none'): ?>
                            <aside id="secondary" class="widget-area">
                                <?php
                                if ( is_active_sidebar( $sidebar_position ) ) {
                                    dynamic_sidebar( $sidebar_position );
                                }
                                ?>
                            </aside>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main><!-- #main -->

<?php
get_footer();
