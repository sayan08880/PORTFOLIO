<?php
 /**
  * Title: Main Banner
  * Slug: pro-portfolio/main-banner
  * Categories: pro-portfolio
  */
?>

<!-- wp:group {"tagName":"section","metadata":{"name":"Banner Section"},"align":"full","className":"banner-section","style":{"spacing":{"padding":{"right":"30px","left":"30px","top":"var:preset|spacing|60","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull banner-section" style="padding-top:var(--wp--preset--spacing--60);padding-right:30px;padding-bottom:0;padding-left:30px"><!-- wp:columns {"verticalAlignment":"center","align":"wide","style":{"spacing":{"margin":{"top":"0","bottom":"0"},"padding":{"right":"0","left":"0","top":"0","bottom":"5rem"},"blockGap":{"top":"0","left":"0"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"}}}} -->
<div class="wp-block-columns alignwide are-vertically-aligned-center" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;margin-top:0;margin-bottom:0;padding-top:0;padding-right:0;padding-bottom:5rem;padding-left:0"><!-- wp:column {"verticalAlignment":"center"} -->
<div class="wp-block-column is-vertically-aligned-center"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0px","padding":{"top":"55px","right":"0px","bottom":"55px","left":"0px"}}},"layout":{"inherit":false,"wideSize":"1500px"}} -->
<div class="wp-block-group alignwide" style="padding-top:55px;padding-right:0px;padding-bottom:55px;padding-left:0px"><!-- wp:heading {"textAlign":"left","level":3,"style":{"typography":{"lineHeight":"1.5","textTransform":"uppercase","letterSpacing":"5px"},"elements":{"link":{"color":{"text":"var:preset|color|boulder"}}}},"textColor":"boulder","fontSize":"normal"} -->
<h3 class="wp-block-heading has-text-align-left has-boulder-color has-text-color has-link-color has-normal-font-size" style="letter-spacing:5px;line-height:1.5;text-transform:uppercase"><?php echo esc_html__( 'Welcome to my world', 'pro-portfolio' ); ?></h3>
<!-- /wp:heading -->

<!-- wp:heading {"textAlign":"left","style":{"typography":{"lineHeight":"1.2","fontStyle":"normal","fontWeight":"700"},"spacing":{"margin":{"bottom":"var:preset|spacing|50","top":"var:preset|spacing|50"}}},"textColor":"foreground","fontSize":"extra-large"} -->
<h2 class="wp-block-heading has-text-align-left has-foreground-color has-text-color has-extra-large-font-size" style="margin-top:var(--wp--preset--spacing--50);margin-bottom:var(--wp--preset--spacing--50);font-style:normal;font-weight:700;line-height:1.2"><?php echo esc_html__( 'Hi, I’m', 'pro-portfolio' ); ?>&nbsp;<span style="color: #f00069;" class="stk-highlight"><?php echo esc_html__( 'Jone Lee', 'pro-portfolio' ); ?></span><br><?php echo esc_html__( 'a', 'pro-portfolio' ); ?>&nbsp;<strong><em><?php echo esc_html__( 'UI/UX Designer', 'pro-portfolio' ); ?></em>.</strong></h2>
<!-- /wp:heading -->

<!-- wp:paragraph {"align":"left","style":{"elements":{"link":{"color":{"text":"var:preset|color|boulder"}}}},"textColor":"boulder"} -->
<p class="has-text-align-left has-boulder-color has-text-color has-link-color"><?php echo esc_html__( 'I use animation as a third dimension by which to simplify experiences and kuiding thro each and every interaction. I’m not adding motion just to spruce things up, but doing it in ways that.', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:buttons {"className":" animated animated-fadeInUp","style":{"spacing":{"margin":{"top":"20px","bottom":"0px"},"blockGap":"20px"}},"layout":{"type":"flex","justifyContent":"left"}} -->
<div class="wp-block-buttons animated animated-fadeInUp" style="margin-top:20px;margin-bottom:0px"><!-- wp:button {"textColor":"primary","className":"is-style-fill","style":{"color":{"gradient":"linear-gradient(145deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"fontStyle":"normal","fontWeight":"700"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"var:preset|color|background-secondary","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"var:preset|color|background-secondary","width":"2px"}}}} -->
<div class="wp-block-button is-style-fill"><a class="wp-block-button__link has-primary-color has-text-color has-background has-link-color wp-element-button" href="#" style="border-radius:10px;border-top-color:var(--wp--preset--color--background-secondary);border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:var(--wp--preset--color--background-secondary);border-left-width:2px;background:linear-gradient(145deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);box-shadow:var(--wp--preset--shadow--natural);font-style:normal;font-weight:700"><?php echo esc_html__( 'Get Started', 'pro-portfolio' ); ?></a></div>
<!-- /wp:button -->

<!-- wp:button {"textColor":"primary","className":"is-style-outline","style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"fontStyle":"normal","fontWeight":"700"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px"}}} -->
<div class="wp-block-button is-style-outline"><a class="wp-block-button__link has-primary-color has-text-color has-link-color wp-element-button" href="#" style="border-radius:10px;box-shadow:var(--wp--preset--shadow--natural);font-style:normal;font-weight:700"><?php echo esc_html__( 'Why Choose Us?', 'pro-portfolio' ); ?></a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"var:preset|spacing|80"},"blockGap":"var:preset|spacing|80"}},"layout":{"type":"flex","flexWrap":"wrap","orientation":"horizontal","justifyContent":"left"}} -->
<div class="wp-block-group" style="margin-top:var(--wp--preset--spacing--80)"><!-- wp:group {"style":{"spacing":{"blockGap":"0"}},"layout":{"type":"flex","orientation":"vertical"}} -->
<div class="wp-block-group"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|boulder"}}}},"textColor":"boulder","fontSize":"small"} -->
<p class="has-boulder-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html__( 'FIND WITH ME', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"gradient":"linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"#fff","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"#fff","width":"2px"}}},"layout":{"type":"constrained","contentSize":"30px"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;border-top-color:#fff;border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:#fff;border-left-width:2px;background:linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:social-links {"iconBackgroundColor":"foreground","iconBackgroundColorValue":"#000","size":"has-small-icon-size","className":"is-style-default"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-background-color is-style-default"><!-- wp:social-link {"url":"facebook.com","service":"facebook"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"gradient":"linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"#fff","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"#fff","width":"2px"}}},"layout":{"type":"constrained","contentSize":"30px"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;border-top-color:#fff;border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:#fff;border-left-width:2px;background:linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:social-links {"iconBackgroundColor":"foreground","iconBackgroundColorValue":"#000","size":"has-small-icon-size","className":"is-style-default"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-background-color is-style-default"><!-- wp:social-link {"url":"instagram.com","service":"instagram"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"gradient":"linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"#fff","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"#fff","width":"2px"}}},"layout":{"type":"constrained","contentSize":"30px"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;border-top-color:#fff;border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:#fff;border-left-width:2px;background:linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:social-links {"iconBackgroundColor":"foreground","iconBackgroundColorValue":"#000","size":"has-small-icon-size","className":"is-style-default"} -->
<ul class="wp-block-social-links has-small-icon-size has-icon-background-color is-style-default"><!-- wp:social-link {"url":"snapchat.com","service":"snapchat"} /--></ul>
<!-- /wp:social-links --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"blockGap":"0","padding":{"right":"0","left":"0"}}},"layout":{"type":"flex","orientation":"vertical","justifyContent":"left"}} -->
<div class="wp-block-group" style="padding-right:0;padding-left:0"><!-- wp:paragraph {"style":{"typography":{"fontStyle":"normal","fontWeight":"500"},"elements":{"link":{"color":{"text":"var:preset|color|boulder"}}}},"textColor":"boulder","fontSize":"small"} -->
<p class="has-boulder-color has-text-color has-link-color has-small-font-size" style="font-style:normal;font-weight:500"><?php echo esc_html__( 'BEST SKILL ON', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:group {"style":{"spacing":{"margin":{"top":"0"},"blockGap":"var:preset|spacing|30"}},"layout":{"type":"flex","flexWrap":"nowrap"}} -->
<div class="wp-block-group" style="margin-top:0"><!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"gradient":"linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"#fff","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"#fff","width":"2px"}}},"layout":{"type":"constrained","contentSize":"30px"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;border-top-color:#fff;border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:#fff;border-left-width:2px;background:linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:image {"id":3416,"width":"30px","aspectRatio":"1","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/banner-logo-1.png" alt="" class="wp-image-3416" style="aspect-ratio:1;object-fit:cover;width:30px"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"gradient":"linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"#fff","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"#fff","width":"2px"}}},"layout":{"type":"constrained","contentSize":"30px"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;border-top-color:#fff;border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:#fff;border-left-width:2px;background:linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:image {"id":3418,"width":"30px","height":"auto","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/banner-logo-2.png" alt="" class="wp-image-3418" style="object-fit:cover;width:30px;height:auto"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group -->

<!-- wp:group {"style":{"spacing":{"padding":{"top":"var:preset|spacing|30","bottom":"var:preset|spacing|30","left":"var:preset|spacing|30","right":"var:preset|spacing|30"}},"color":{"gradient":"linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%)"},"shadow":"var:preset|shadow|natural","border":{"radius":"10px","top":{"color":"#fff","width":"2px"},"right":{"color":"#ffffff00","width":"2px"},"bottom":{"color":"#ffffff00","width":"2px"},"left":{"color":"#fff","width":"2px"}}},"layout":{"type":"constrained","contentSize":"30px"}} -->
<div class="wp-block-group has-background" style="border-radius:10px;border-top-color:#fff;border-top-width:2px;border-right-color:#ffffff00;border-right-width:2px;border-bottom-color:#ffffff00;border-bottom-width:2px;border-left-color:#fff;border-left-width:2px;background:linear-gradient(135deg,rgb(226,232,236) 0%,rgb(255,255,255) 100%);padding-top:var(--wp--preset--spacing--30);padding-right:var(--wp--preset--spacing--30);padding-bottom:var(--wp--preset--spacing--30);padding-left:var(--wp--preset--spacing--30);box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:image {"id":3419,"width":"30px","height":"auto","scale":"cover","sizeSlug":"full","linkDestination":"none","style":{"layout":{"selfStretch":"fit","flexSize":null}}} -->
<figure class="wp-block-image size-full is-resized"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/banner-logo-3.png" alt="" class="wp-image-3419" style="object-fit:cover;width:30px;height:auto"/></figure>
<!-- /wp:image --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></div>
<!-- /wp:column -->

<!-- wp:column {"verticalAlignment":"center","width":"40%"} -->
<div class="wp-block-column is-vertically-aligned-center" style="flex-basis:40%"><!-- wp:image {"id":3488,"scale":"cover","sizeSlug":"full","linkDestination":"none","align":"center","style":{"color":{"duotone":"unset"}}} -->
<figure class="wp-block-image aligncenter size-full"><img src="<?php echo esc_url( get_template_directory_uri() ); ?>/assets/images/banner-1.png" alt="" class="wp-image-3488" style="object-fit:cover"/></figure>
<!-- /wp:image --></div>
<!-- /wp:column --></div>
<!-- /wp:columns --></section>
<!-- /wp:group -->