<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */

?>
<?php
    $get_thumbnail_url = get_the_post_thumbnail_url( get_the_ID() );
    $get_permalink = get_permalink();
    $date = get_the_date('F d, Y');
?>
<div id="post-<?php the_ID(); ?>" class="post-type-five__left--item mb-3 mb-lg-6">
    <div class="row">
        <div class="col-12 col-md-5 mb-3 mb-md-0">
            <a href="<?php echo esc_html($get_permalink); ?>">
                <figure class="post-type-five__left--image lazy ratio32" data-src="<?php echo esc_html($get_thumbnail_url); ?>"></figure>
            </a>
        </div>
        <div class="col-12 col-md-7">
            <div class="entry mt-0 mb-b">
                <span class="entry__date"><?php echo esc_html($date); ?></span>
            </div>
            <h3 class="post-type-five__left--title">
                <a href="<?php echo esc_html($get_permalink); ?>"><?php echo get_the_title() ?></a>
            </h3>
            <div class="post-type-five__left--sub">
                <?php echo egan_portfolio_resume_excerpt_custom(30, get_the_ID()); ?>
            </div>
        </div>
    </div>
</div>
