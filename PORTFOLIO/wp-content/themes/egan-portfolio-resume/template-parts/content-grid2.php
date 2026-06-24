<?php
/**
 * Template part for displaying posts
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */
?>

<?php
    $entry_grid_two_date = get_theme_mod( 'crt_manage_entry_grid_two_date', true );
    $entry_grid_two_category = get_theme_mod( 'crt_manage_entry_grid_two_category', true );
    $entry_grid_two_author = get_theme_mod( 'crt_manage_entry_grid_two_author', false );
    $entry_grid_two_read_time = get_theme_mod( 'crt_manage_entry_grid_two_read_time', false );
    $entry_grid_two_comment = get_theme_mod( 'crt_manage_entry_grid_two_comment', false );
    $entry_grid_two_view = get_theme_mod( 'crt_manage_entry_grid_two_view', false );
    $get_thumbnail_url = get_the_post_thumbnail_url( get_the_ID() );
    $get_permalink = get_permalink();
    $post = get_post();
    $post_format = get_post_format($post) ? : 'standard';
?>
<div class="col-12 col-md-6 mb-4 post-grid-two__item <?php echo 'post_' . esc_attr($post_format); ?>">
    <div class="post-grid-two__item--inner border-default">
        <div class="position-relative">
            <a href="<?php echo esc_html($get_permalink); ?>">
                <figure class="post-grid-two__image lazy ratio32" data-src="<?php echo esc_html($get_thumbnail_url); ?>"></figure>
            </a>
        </div>

        <div class="post-grid-two__content">
            <?php egan_portfolio_resume_entry_options($post, array('class' => 'mb-2', 'entry_date' => $entry_grid_two_date, 'entry_cat' => true, 'entry_author' => $entry_grid_two_author, 'entry_read_time' => $entry_grid_two_read_time, 'entry_comment' => $entry_grid_two_comment, 'entry_view_count' => $entry_grid_two_view)); ?>
            <h3 class="post-grid-two__title"><a href="<?php echo esc_html($get_permalink); ?>"><?php echo get_the_title() ?></a></h3>
            <div class="post-grid-two__sub">
                <?php echo egan_portfolio_resume_excerpt_custom(30, get_the_ID()); ?>
            </div>
        </div>
    </div>
</div>
