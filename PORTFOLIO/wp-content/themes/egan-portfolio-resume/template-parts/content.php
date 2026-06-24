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
    $entry_list_date = get_theme_mod( 'crt_manage_entry_list_date', true );
    $entry_list_category = get_theme_mod( 'crt_manage_entry_list_category', true );
    $entry_list_author = get_theme_mod( 'crt_manage_entry_list_author', false );
    $entry_list_read_time = get_theme_mod( 'crt_manage_entry_list_read_time', false );
    $entry_list_comment = get_theme_mod( 'crt_manage_entry_list_comment', false );
    $entry_list_view = get_theme_mod( 'crt_manage_entry_list_view', false );
    $get_thumbnail_url = get_the_post_thumbnail_url( get_the_ID(), 'egan-portfolio-resume-image-medium' );

    $get_permalink = get_permalink();
    $post = get_post();
    $post_format = get_post_format($post) ? : 'standard';
?>
<div id="post-<?php the_ID(); ?>" class="post-list__item mb-lg-6 <?php echo 'post_' . esc_attr($post_format); ?>">
    <div class="post-list__item--inner">
        <div class="row">
            <div class="col-12 col-md-5 mb-3 mb-md-0 ">
                <div class="position-relative">
                    <a href="<?php echo esc_html($get_permalink); ?>">
                        <figure class="image-default lazy ratio32 " data-src="<?php echo esc_html($get_thumbnail_url); ?>"></figure>
                    </a>
                </div>
            </div>
            <div class="col-12 col-md-7">
                <div class="post-list__item--content">
                    <div class="entry">
                        <?php egan_portfolio_resume_entry_options($post, array('class' => 'mb-2', 'entry_date' => true, 'entry_cat' => true, 'entry_author' => false, 'entry_read_time' => false, 'entry_comment' => false, 'entry_view_count' => false)); ?>
                    </div>
                    <h3 class="post-list__title">
                        <a href="<?php echo esc_html($get_permalink); ?>"><?php echo get_the_title() ?></a>
                    </h3>
                    <div class="post-list__sub">
                        <?php echo egan_portfolio_resume_excerpt_custom(30, get_the_ID()); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>