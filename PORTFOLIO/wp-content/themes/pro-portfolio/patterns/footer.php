<?php
 /**
  * Title: Footer
  * Slug: pro-portfolio/footer
  * Categories: pro-portfolio
  */
?>

<!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0px"}},"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:group {"align":"full","style":{"border":{"bottom":{"color":"var:preset|color|border","width":"1px"},"top":[],"right":[],"left":[]},"spacing":{"padding":{"right":"30px","left":"30px","top":"16px","bottom":"16px"}}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-top:16px;padding-right:30px;padding-bottom:16px;padding-left:30px"><!-- wp:columns {"align":"wide"} -->
<div class="wp-block-columns alignwide"><!-- wp:column {"width":"25%","style":{"spacing":{"padding":{"top":"20px","right":"20px","bottom":"20px","left":"0px"},"blockGap":"10px"}}} -->
<div class="wp-block-column" style="padding-top:20px;padding-right:20px;padding-bottom:20px;padding-left:0px;flex-basis:25%"><!-- wp:group {"className":"","style":{"spacing":{"padding":{"bottom":"10px"}}}} -->
<div class="wp-block-group" style="padding-bottom:10px"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"1.5rem"}},"textColor":"foreground"} -->
<h3 class="wp-block-heading has-foreground-color has-text-color" style="font-size:1.5rem;font-style:normal;font-weight:600"><?php echo esc_html__( 'Pro Portfolio', 'pro-portfolio' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"textColor":"boulder"} -->
<p class="has-boulder-color has-text-color"><?php echo esc_html__( 'Lorem ipsum dolor sit amet consectetur adipiscing elit non natoque ullamcorper facilisis dui, erat mi pharetra gravida eu netus laoreet scelerisque nunc risus libero rutrum enim, condimentum consequat sems.', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"padding":{"top":"20px"},"blockGap":"10px"}}} -->
<div class="wp-block-column" style="padding-top:20px"><!-- wp:group {"className":"","style":{"spacing":{"padding":{"bottom":"10px"}}}} -->
<div class="wp-block-group" style="padding-bottom:10px"><!-- wp:heading {"level":3,"style":{"typography":{"fontStyle":"normal","fontWeight":"600","fontSize":"1.5rem"}},"textColor":"foreground"} -->
<h3 class="wp-block-heading has-foreground-color has-text-color" style="font-size:1.5rem;font-style:normal;font-weight:600"><?php echo esc_html__( 'Gallery', 'pro-portfolio' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:gallery {"linkTo":"none","sizeSlug":"thumbnail","style":{"spacing":{"blockGap":"10px"}}} -->
<figure class="wp-block-gallery has-nested-images columns-default is-cropped"><!-- wp:image {"id":3330,"aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none"} -->
<figure class="wp-block-image size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/gallery-1.jpg" alt="" class="wp-image-3330" style="aspect-ratio:1;object-fit:cover"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":3333,"sizeSlug":"thumbnail","linkDestination":"none"} -->
<figure class="wp-block-image size-thumbnail"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/gallery-2.jpg" alt="" class="wp-image-3333"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":3332,"sizeSlug":"thumbnail","linkDestination":"none"} -->
<figure class="wp-block-image size-thumbnail"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/gallery-3.jpg" alt="" class="wp-image-3332"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":3331,"sizeSlug":"thumbnail","linkDestination":"none"} -->
<figure class="wp-block-image size-thumbnail"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/gallery-4.jpg" alt="" class="wp-image-3331"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":3335,"sizeSlug":"thumbnail","linkDestination":"none"} -->
<figure class="wp-block-image size-thumbnail"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/gallery-5.jpg" alt="" class="wp-image-3335"/></figure>
<!-- /wp:image -->

<!-- wp:image {"id":3338,"sizeSlug":"thumbnail","linkDestination":"none"} -->
<figure class="wp-block-image size-thumbnail"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/gallery-6.jpg" alt="" class="wp-image-3338"/></figure>
<!-- /wp:image --></figure>
<!-- /wp:gallery --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"blockGap":"10px","padding":{"top":"20px"}}}} -->
<div class="wp-block-column" style="padding-top:20px"><!-- wp:group {"className":"","style":{"spacing":{"padding":{"bottom":"10px"}}}} -->
<div class="wp-block-group" style="padding-bottom:10px"><!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.5rem","fontStyle":"normal","fontWeight":"600"}},"textColor":"foreground"} -->
<h3 class="wp-block-heading has-foreground-color has-text-color" style="font-size:1.5rem;font-style:normal;font-weight:600"><?php echo esc_html__( 'Quick Links', 'pro-portfolio' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:list {"className":"ff-list-style-one","style":{"typography":{"lineHeight":"1.9","fontStyle":"normal","fontWeight":"300"}},"textColor":"boulder","fontSize":"normal"} -->
<ul style="font-style:normal;font-weight:300;line-height:1.9" class="wp-block-list ff-list-style-one has-boulder-color has-text-color has-normal-font-size"><!-- wp:list-item -->
<li><?php echo esc_html__( 'About Us', 'pro-portfolio' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__( 'Services', 'pro-portfolio' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__( 'Contact Us', 'pro-portfolio' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__( 'FAQ', 'pro-portfolio' ); ?></li>
<!-- /wp:list-item -->

<!-- wp:list-item -->
<li><?php echo esc_html__( 'Our Vision', 'pro-portfolio' ); ?></li>
<!-- /wp:list-item --></ul>
<!-- /wp:list --></div>
<!-- /wp:column -->

<!-- wp:column {"style":{"spacing":{"padding":{"top":"20px"},"blockGap":"10px"}}} -->
<div class="wp-block-column" style="padding-top:20px"><!-- wp:group {"className":"","style":{"spacing":{"padding":{"bottom":"10px"}}}} -->
<div class="wp-block-group" style="padding-bottom:10px"><!-- wp:heading {"level":3,"style":{"typography":{"fontSize":"1.5rem","fontStyle":"normal","fontWeight":"600"}},"textColor":"foreground"} -->
<h3 class="wp-block-heading has-foreground-color has-text-color" style="font-size:1.5rem;font-style:normal;font-weight:600"><?php echo esc_html__( 'Quick Contact', 'pro-portfolio' ); ?></h3>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"className":"","style":{"spacing":{"blockGap":"var:preset|spacing|30"}},"textColor":"body-text","layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group has-body-text-color has-text-color"><!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group -->
<div class="wp-block-group"><!-- wp:image {"id":8088,"sizeSlug":"full","linkDestination":"none","className":"is-style-default vertical-aligncenter","style":{"color":{"duotone":["rgb(255, 1, 77)","#fff"]}}} -->
<figure class="wp-block-image size-full is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-phone.png" alt="" class="wp-image-8088"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}},"textColor":"foreground","fontSize":"small"} -->
<p class="has-foreground-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html__( '+1 2059 458 96548 59', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group -->
<div class="wp-block-group"><!-- wp:image {"id":8087,"width":"18px","height":"18px","sizeSlug":"full","linkDestination":"none","className":"is-style-default vertical-aligncenter","style":{"color":{"duotone":["rgb(255, 1, 77)","#fff"]}}} -->
<figure class="wp-block-image size-full is-resized is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-mail.png" alt="" class="wp-image-8087" style="width:18px;height:18px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|body-text"}}}},"textColor":"foreground","fontSize":"small"} -->
<p class="has-foreground-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:500"><a href="mailto:support@example.com"><?php echo esc_html__( 'support@example.com', 'pro-portfolio' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group -->
<div class="wp-block-group"><!-- wp:image {"id":8087,"sizeSlug":"full","linkDestination":"none","className":"is-style-default vertical-aligncenter","style":{"color":{"duotone":["#ff014d","#ffffff"]}}} -->
<figure class="wp-block-image size-full is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-location.png" alt="" class="wp-image-8087"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"foreground","fontSize":"small"} -->
<p class="has-foreground-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html__( '23 Miller Court, Conway', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"10px"}},"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"center"}} -->
<div class="wp-block-group"><!-- wp:group {"layout":{"type":"flex","flexWrap":"nowrap","verticalAlignment":"top"}} -->
<div class="wp-block-group"><!-- wp:group -->
<div class="wp-block-group"><!-- wp:image {"id":53,"sizeSlug":"full","linkDestination":"none","className":"is-style-default vertical-aligncenter","style":{"color":{"duotone":["#ff014d","#ffffff"]}}} -->
<figure class="wp-block-image size-full is-style-default vertical-aligncenter"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/icon-time.png" alt="" class="wp-image-53"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|white"}}}},"textColor":"foreground","fontSize":"small"} -->
<p class="has-foreground-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html__( '9 AM  - 6 PM', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"full","style":{"spacing":{"blockGap":"0px","padding":{"top":"10px","right":"30px","bottom":"10px","left":"30px"}}},"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignfull" style="padding-top:10px;padding-right:30px;padding-bottom:10px;padding-left:30px"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"1rem","bottom":"1rem"}},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}}},"textColor":"white","layout":{"type":"flex","justifyContent":"space-between"}} -->
<div class="wp-block-group alignwide has-white-color has-text-color has-link-color" style="padding-top:1rem;padding-bottom:1rem"><!-- wp:social-links {"iconColor":"primary","iconColorValue":"#ff004d","openInNewTab":true,"showLabels":true,"size":"has-normal-icon-size","className":"mobile-aligncenter is-style-logos-only","style":{"spacing":{"margin":{"top":"0px","bottom":"0px"},"blockGap":{"top":"20px","left":"20px"}}},"layout":{"type":"flex","justifyContent":"center"}} -->
<ul class="wp-block-social-links has-normal-icon-size has-visible-labels has-icon-color mobile-aligncenter is-style-logos-only" style="margin-top:0px;margin-bottom:0px"><!-- wp:social-link {"url":"https://facebook.com/","service":"facebook"} /-->

<!-- wp:social-link {"url":"https://twitter.com/","service":"twitter"} /-->

<!-- wp:social-link {"url":"https://youtube.com/","service":"youtube"} /-->

<!-- wp:social-link {"url":"https://instagram.com/","service":"instagram"} /-->

<!-- wp:social-link {"url":"https://wordpress.com/","service":"wordpress"} /--></ul>
<!-- /wp:social-links -->

<!-- wp:paragraph {"align":"right","className":"mobile-aligncenter","style":{"layout":{"selfStretch":"fit","flexSize":null}},"textColor":"foreground"} -->
<p class="has-text-align-right mobile-aligncenter has-foreground-color has-text-color"><?php echo esc_html__( 'Copyright © 2025 | Powered by', 'pro-portfolio' ); ?> <a href="https://wordpress.org" rel="nofollow"><?php echo esc_html__( 'WordPress', 'pro-portfolio' ); ?></a></p>
<!-- /wp:paragraph --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->