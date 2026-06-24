<?php
/**
 * Template part for displaying page content in page.php
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */

?>
<div class="custom-block-woo__inner">
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
        <div class="entry-content editor-content">
            <?php the_content(); ?>
        </div><!-- .entry-content -->
    </article><!-- #post-<?php the_ID(); ?> -->
</div>