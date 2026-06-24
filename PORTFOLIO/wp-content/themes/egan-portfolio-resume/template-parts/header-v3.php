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
$class_header = '';
$header_container = false;
$header_type = get_theme_mod('crt_manage_header_type');
if($header_type) {
    $class_header .= 'header__' . $header_type;
}
?>
<header class="head <?php echo esc_attr($class_header); ?>">
    <div class="header__row">
        <div class="container">
            <div class="border-left-right <?php echo esc_attr(!$header_container ? ' pe-lg-4 ps-lg-4 pe-md-3 ps-md-3 p-0 border-md-none border-sm-none':''); ?>">
                <div class="row align-items-center">
                    <div class="col-9 col-md-4">
                        <div class="head__logo">
                            <?php if ( has_custom_logo() ) : ?>
                                <div class="site-logo">
                                    <?php $logo = wp_get_attachment_url( get_theme_mod( 'custom_logo' ) );?>
                                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                                        <?php echo ( is_front_page() || is_home() ) ? '<h1 class="head__sologan">':'<h2 class="head__sologan">'; ?>
                                        <img class="dark" src="<?php echo esc_attr($logo); ?>" alt="<?php bloginfo( 'name' ); ?>">
                                        <?php echo ( is_front_page() || is_home() ) ? '</h1>':'</h2>'; ?>
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="site-identity">
                                    <?php if ( is_front_page() || is_home() ) : ?>
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><h1 class="head__sologan"><strong><?php bloginfo( 'name' ); ?></strong><?php if(get_bloginfo( 'description' )) { echo '<span>'; bloginfo( 'description' ); echo '</span>';} ?></h1></a>
                                    <?php else : ?>
                                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><h2 class="head__sologan"><strong><?php bloginfo( 'name' ); ?></strong><?php if(get_bloginfo( 'description' )) { echo '<span>'; bloginfo( 'description' ); echo '</span>';} ?></h2></a>
                                    <?php endif;  ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="col-3 col-md-8">
                        <div class="row">
                            <div class="col-12">
                                <div class="d-flex align-items-center justify-content-end">
                                    <div class="head__nav d-none d-md-block">
                                        <?php get_template_part( 'template-parts/header-nav' ); ?>
                                    </div>
                                    <div class="head__social d-none d-md-block">
                                        <?php get_template_part( 'template-parts/header-social', '', array('class' => 'justify-content-end mb-0', 'style' => '') ); ?>
                                    </div>
                                    <div class="d-flex d-md-none">
                                        <a class="toggle-menu" href="#">
                                            <span class="fa-navicon__custom"><i></i></span>
                                        </a>
                                    </div>
                                    <div class="head__search">
                                        <a class="head__button-search" href="#"><i class="fa fa-search" aria-hidden="true"></i></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12">
                        <div class="position-relative">
                            <div class="head__search">
                                <?php get_search_form(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php get_template_part( 'template-parts/header-mobile' ); ?>

</header>
