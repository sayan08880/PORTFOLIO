<?php
 /**
  * Title: 404 Error Page
  * Slug: pro-portfolio/404
  * Categories: pro-portfolio
  */
?>
<!-- wp:group {"tagName":"main","style":{"spacing":{"padding":{"top":"80px","bottom":"80px"}}},"layout":{"inherit":true,"type":"constrained"}} -->
<main class="wp-block-group" style="padding-top:80px;padding-bottom:80px"><!-- wp:heading {"className":"has-text-align-center","style":{"typography":{"fontSize":"clamp(4rem, 40vw, 20rem)","fontWeight":"200","lineHeight":"1"}},"textColor":"primary","fontFamily":"literata"} -->
<h2 class="wp-block-heading has-text-align-center has-primary-color has-text-color has-literata-font-family" style="font-size:clamp(4rem, 40vw, 20rem);font-weight:200;line-height:1"><?php echo esc_html__( ' 4', 'pro-portfolio' ); ?><mark style="background-color: rgba(0, 0, 0, 0)" class="has-inline-color has-secondary-color"> <?php echo esc_html__( '0', 'pro-portfolio' ); ?></mark><?php echo esc_html__( '4 ', 'pro-portfolio' ); ?></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"center"} -->
<p class="has-text-align-center"><?php echo esc_html__( 'Oops! That page can’t be found.', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:search {"label":"Search","showLabel":false,"placeholder":"Search Now","width":75,"widthUnit":"%","buttonText":"Search","buttonUseIcon":true,"align":"center","backgroundColor":"primary","textColor":"white"} /--></main>
<!-- /wp:group -->