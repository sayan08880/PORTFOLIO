
<?php
    $show_social = get_theme_mod('crt_manage_header_show_social');
    $show_social_m = get_theme_mod('crt_manage_header_show_social_m');
    $class_m = '';
    if($show_social)
        get_template_part( 'template-parts/header/header-social', '', array('class' => 'justify-content-start right ' . $class_m, 'style' => '') );
?>

<?php
    $show_cart = get_theme_mod('crt_manage_header_show_cart');
    $show_cart_m = get_theme_mod('crt_manage_header_show_cart_m');
    $class_m = $show_cart_m ? 'd-block':'d-none d-md-block';
    if($show_cart)
        get_template_part( 'template-parts/header/header-cart' ,'', array('class' => 'right ' . $class_m, 'style' => '' )) ;
?>

<?php
    $show_search = get_theme_mod('crt_manage_header_show_search');
    $show_search_m = get_theme_mod('crt_manage_header_show_search_m');
    $class_m = '';
    if($show_search)
        get_template_part( 'template-parts/header/header-search' , '', array('class' => 'right ' . $class_m, 'style' => '' ) );
?>
<?php
    $show_dark_light_button = get_theme_mod('crt_manage_header_show_dark_light');
    $show_dark_light_button_m = get_theme_mod('crt_manage_header_show_dark_light_m');
    $class_m = $show_dark_light_button_m ? 'd-block':'d-none d-md-block';
    if($show_dark_light_button)
        get_template_part('template-parts/header/header-dark-light', '', array('class' => 'right ' . $class_m, 'style' => '' ));
    ?>
<?php
    $show_nav_button = get_theme_mod('crt_manage_header_show_nav_button');
    $show_nav_button_m = get_theme_mod('crt_manage_header_show_nav_button_m');
    $class_m = $show_nav_button_m ? 'd-flex':'d-none d-md-flex';
    if(!$show_nav_button && $show_nav_button_m) {
        $class_m = $show_nav_button_m ? 'd-flex d-md-none':'d-flex d-md-none';
    } else {
        $class_m = $show_nav_button_m ? 'd-flex':'d-none d-md-flex';
    }
    if($show_nav_button || $show_nav_button_m)
        get_template_part( 'template-parts/header/header-button-nav' ,'', array('class' => 'right '. $class_m, 'style' => '' ) ); ?>
