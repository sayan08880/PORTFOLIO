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
    $attr_color = '#000;';
    $header_nav_style = get_theme_mod('crt_manage_header_nav_style');
    $header_type = get_theme_mod('crt_manage_header_type');
    if($header_nav_style == 'bg-color' && $header_type != 'v3') {
        $attr_color = '#FFF';
    }
?>
<?php  ?>
<nav class="nav__desktop" role="navigation" itemscope itemtype="https://schema.org/SiteNavigationElement">
    <?php
        if( is_front_page() || is_home() ) {
            if ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu(
                    array(
                        'container' => false,
                        'theme_location' => 'primary',
                    )
                );
            }
        } else {
            if ( has_nav_menu( 'not_home_nav' ) ) {
                wp_nav_menu(
                    array(
                        'container' => false,
                        'theme_location' => 'not_home_nav',
                    )
                );
            } elseif ( has_nav_menu( 'primary' ) ) {
                wp_nav_menu(
                    array(
                        'container' => false,
                        'theme_location' => 'primary',
                    )
                );
            }
        }
    ?>
</nav>
