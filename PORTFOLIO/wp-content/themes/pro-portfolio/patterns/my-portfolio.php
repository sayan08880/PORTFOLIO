
<?php
/**
 * Title: My Portfolio
 * Slug: pro-portfolio/my-portfolio
 * Categories: pro-portfolio
 */
?>
<!-- wp:group {"tagName":"section","metadata":{"name":"My Portfolio Section"},"align":"full","className":"my-portfolio","style":{"spacing":{"padding":{"top":"5rem","right":"30px","left":"30px","bottom":"0"}}},"layout":{"type":"constrained"}} -->
<section class="wp-block-group alignfull my-portfolio" style="padding-top:5rem;padding-right:30px;padding-bottom:0;padding-left:30px"><!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"bottom":"5rem"}},"border":{"bottom":{"color":"var:preset|color|border","width":"1px"},"top":[],"right":[],"left":[]}},"layout":{"type":"constrained"}} -->
<div class="wp-block-group alignwide" style="border-bottom-color:var(--wp--preset--color--border);border-bottom-width:1px;padding-bottom:5rem"><!-- wp:group {"align":"wide","style":{"spacing":{"blockGap":"0px"}}} -->
<div class="wp-block-group alignwide"><!-- wp:paragraph {"align":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"letterSpacing":"5px"}},"textColor":"primary"} -->
<p class="has-text-align-center has-primary-color has-text-color has-link-color" style="letter-spacing:5px"><?php echo esc_html__( 'Visit my portfolio and keep your feedback', 'pro-portfolio' ); ?></p>
<!-- /wp:paragraph -->

<!-- wp:heading {"textAlign":"center","style":{"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}},"textColor":"foreground","fontSize":"large"} -->
<h2 class="wp-block-heading has-text-align-center has-foreground-color has-text-color has-link-color has-large-font-size"><?php echo esc_html__( 'My Portfolio', 'pro-portfolio' ); ?></h2>
<!-- /wp:heading --></div>
<!-- /wp:group -->

<!-- wp:group {"align":"wide","layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group alignwide"><!-- wp:query {"queryId":1,"query":{"perPage":6,"pages":"1","offset":"0","postType":"post","order":"desc","orderBy":"date","author":"","search":"","exclude":[],"sticky":"","inherit":false},"tagName":"main","align":"wide","layout":{"type":"default"}} -->
<main class="wp-block-query alignwide"><!-- wp:post-template {"align":"wide","style":{"spacing":{"blockGap":"2rem"}},"layout":{"type":"grid","columnCount":3}} -->
<!-- wp:group {"className":"has-shadow-dark","style":{"spacing":{"padding":{"top":"34px","right":"34px","bottom":"34px","left":"34px"}},"border":{"radius":{"topLeft":"20px","topRight":"20px","bottomLeft":"20px","bottomRight":"20px"},"top":{"color":"var:preset|color|background-secondary","width":"2px"},"right":{"color":"#ffffff00"},"bottom":{"color":"#ffffff00"},"left":{"color":"var:preset|color|background-secondary"}},"color":{"gradient":"linear-gradient(140deg,rgb(226,232,236) 0%,rgb(254,254,254) 88%)"},"shadow":"var:preset|shadow|natural"},"layout":{"inherit":true,"type":"constrained"}} -->
<div class="wp-block-group has-shadow-dark has-background" style="border-top-left-radius:20px;border-top-right-radius:20px;border-bottom-left-radius:20px;border-bottom-right-radius:20px;border-top-color:var(--wp--preset--color--background-secondary);border-top-width:2px;border-right-color:#ffffff00;border-bottom-color:#ffffff00;border-left-color:var(--wp--preset--color--background-secondary);background:linear-gradient(140deg,rgb(226,232,236) 0%,rgb(254,254,254) 88%);padding-top:34px;padding-right:34px;padding-bottom:34px;padding-left:34px;box-shadow:var(--wp--preset--shadow--natural)"><!-- wp:post-featured-image {"isLink":true,"aspectRatio":"4/3","align":"wide","className":"no-padding","style":{"border":{"radius":{"topLeft":"10px","topRight":"10px","bottomLeft":"10px","bottomRight":"10px"}}}} /-->

<!-- wp:group {"align":"wide","style":{"spacing":{"padding":{"top":"0px","right":"0px","bottom":"0px","left":"0px"},"margin":{"top":"20px"},"blockGap":"10px"}}} -->
<div class="wp-block-group alignwide" style="margin-top:20px;padding-top:0px;padding-right:0px;padding-bottom:0px;padding-left:0px"><!-- wp:post-terms {"term":"category","style":{"elements":{"link":{"color":{"text":"var:preset|color|primary"}}},"typography":{"textDecoration":"none"}},"textColor":"primary","fontSize":"extra-small"} /-->

<!-- wp:post-title {"level":3,"isLink":true,"align":"wide","style":{"typography":{"fontStyle":"normal","fontWeight":"600"},"elements":{"link":{"color":{"text":"var:preset|color|foreground"}}}},"textColor":"foreground"} /--></div>
<!-- /wp:group --></div>
<!-- /wp:group -->
<!-- /wp:post-template --></main>
<!-- /wp:query --></div>
<!-- /wp:group --></div>
<!-- /wp:group --></section>
<!-- /wp:group -->