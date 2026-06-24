<?php
    $enable_latest = get_theme_mod('crt_manage_enable_latest_section');
    if(!$enable_latest) {
        return;
    }
    $latest_post_per_page = get_theme_mod('crt_manage_latest_post_per_page');
    $latest_post_order = get_theme_mod('crt_manage_latest_post_order', 'DESC');
    $sidebar_position = get_theme_mod('crt_manage_post_latest_sidebar_position', 'sidebar-1');
    $heading_label = get_theme_mod('crt_manage_latest_headline_sub', 'Latest Blog');
    $heading = get_theme_mod('crt_manage_latest_headline', 'Recent articles');
    $attr_id = get_theme_mod(  'crt_manage_latest_attr_id', 'post-latest' );
?>

<section id="<?php echo esc_attr($attr_id); ?>" class="py-5 br-b">
    <div class="container position-relative">
        <?php crt_manage_section_link( 'Post Latest' ); ?>
        <div class="">
            <div class="row">
                <div class="col-12">
                    <?php egan_portfolio_resume_heading($heading, $heading_label); ?>
                </div>
            </div>
            <div class="row bor-col-d">
                <?php
                    $args = egan_portfolio_resume_archive_layout();
                    $col_one = $args['col_one'];
                    $col_two = $args['col_two'];
                    $layout = $args['layout'];
                    $grid = str_contains($layout, 'masonry');
                ?>
                <div class="<?php echo esc_attr($col_one); ?>">
                    <div>
                        <div class="<?php echo esc_attr($grid ? 'grid bor-col-d':'row bor-col-d') ?>">
                            <?php
                            $paged = ( get_query_var( 'page' ) ) ? get_query_var( 'page' ) : 1;
                            $args = array(
                                'paged'          => $paged,
                                'posts_per_page' => ( $latest_post_per_page ) ? $latest_post_per_page : 10,
                                'order' => isset($latest_post_order[0]) ? $latest_post_order[0]:'DESC'
                            );
                            $loop = new WP_Query( $args );
                            if ( $loop->have_posts() ) :
                                while ( $loop->have_posts() ) :
                                    $loop->the_post();
                                    get_template_part( 'template-parts/content', $layout, array('sidebar' => $col_two) );
                                endwhile;
                            endif;
                            wp_reset_postdata();
                            ?>
                        </div>
                    </div>
                    <?php egan_portfolio_resume_pagination_custom( $paged, $loop->max_num_pages); ?>
                </div>
                <div class="<?php echo esc_attr($col_two); ?>">
                    <?php if($col_two != 'd-none'): ?>
                        <aside id="secondary" class="widget-area">
                            <?php dynamic_sidebar( $sidebar_position ); ?>
                        </aside>
                    <?php endif; ?>
                </div>
                <?php if($col_two != 'd-none'): ?>
                    <div class="br-col br-col66 br-sm-col-none"></div>
                <?php endif; ?>
            </div>
        </div>

    </div>
</section>