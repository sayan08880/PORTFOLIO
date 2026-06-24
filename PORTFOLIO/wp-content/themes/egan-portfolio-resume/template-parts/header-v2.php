<?php
/**
 * Template part for displaying a message that posts cannot be found
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package Egan_Portfolio_Resume
 */
?>
<?php

?>
<header class="head-v2">
    <div class="head-v2__row">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="head-v2__inner d-flex justify-content-between align-items-center">
                        <div class="head-v2__logo">
                            <?php get_template_part( 'template-parts/header/header-logo' ); ?>
                        </div>
                        <div class="head-v2__nav d-none d-lg-block">
                            <?php get_template_part( 'template-parts/header/header-nav-v2' ); ?>
                        </div>
                        <div class="d-flex d-lg-none">
                            <a class="nav-button-m" aria-label="Navigation" rel="nofollow" href="#"><i class="fa-solid fa-bars"></i></a>
                        </div>
                        <div class="head-v2__feature d-none d-lg-inline-flex align-items-center">
                            <?php get_template_part( 'template-parts/header/header-right' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php get_template_part( 'template-parts/header/header-side' ); ?>
    <?php get_template_part( 'template-parts/header/header-form-search' ); ?>
</header>
