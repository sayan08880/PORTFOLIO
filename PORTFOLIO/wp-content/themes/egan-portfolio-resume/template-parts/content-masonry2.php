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
    $entry_masonry_two_date = get_theme_mod( 'crt_manage_entry_masonry_two_date', true );
    $entry_masonry_two_category = get_theme_mod( 'crt_manage_entry_masonry_two_category', true );
    $entry_masonry_two_author = get_theme_mod( 'crt_manage_entry_masonry_two_author', false );
    $entry_masonry_two_read_time = get_theme_mod( 'crt_manage_entry_masonry_two_read_time', false );
    $entry_masonry_two_comment = get_theme_mod( 'crt_manage_entry_masonry_two_comment', false );
    $entry_masonry_two_view = get_theme_mod( 'crt_manage_entry_masonry_two_view', false );
    $get_thumbnail_url = get_the_post_thumbnail_url( get_the_ID() );
    $get_permalink = get_permalink();
    $date = get_the_date('F d, Y');
    $post = get_post();
    $post_format = get_post_format($post) ? : 'standard';
?>
<div class="grid__item grid__item-two mb-4 <?php echo 'post_' . esc_attr($post_format); ?>">
    <div class="post-masonry-two__item--inner border-default">
        <div class="position-relative">
            <a href="<?php echo esc_html($get_permalink); ?>">
                <img class="post-masonry-two__image" src="<?php echo esc_html($get_thumbnail_url); ?>" alt="<?php echo get_the_title() ?>" />
            </a>
        </div>
        <div class="post-masonry-two__content">
            <?php egan_portfolio_resume_entry_options($post, array('class' => 'mb-2', 'entry_date' => $entry_masonry_two_date, 'entry_cat' => true, 'entry_author' => $entry_masonry_two_author, 'entry_read_time' => $entry_masonry_two_read_time, 'entry_comment' => $entry_masonry_two_comment, 'entry_view_count' => $entry_masonry_two_view)); ?>
            <h3 class="post-masonry-two__title"><a href="<?php echo esc_html($get_permalink); ?>"><?php echo get_the_title() ?></a></h3>
        </div>
    </div>
</div>
