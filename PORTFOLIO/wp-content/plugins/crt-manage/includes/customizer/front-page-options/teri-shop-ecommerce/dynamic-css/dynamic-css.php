<?php
/**
 * CRT_Manage Dynamic CSS
 */

if ( ! function_exists( 'crt_manage_teri_shop_ecommerce_fonts' ) ) :
    /**
     * Return Google fonts URL.
     */
    function crt_manage_teri_shop_ecommerce_fonts() {
        $fonts_url = '';
        $fonts     = array();

        $all_fonts = crt_manage_get_all_google_fonts();

        if ( ! empty( get_theme_mod( 'crt_manage_general_body_font', 'Montserrat' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_general_body_font', 'Montserrat' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_header_logo_font', 'Roboto' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_header_logo_font', 'Roboto' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_single_content_font', 'Montserrat' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_single_content_font', 'Montserrat' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_general_nav_font', 'Montserrat' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_general_nav_font', 'Montserrat' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_general_post_heading_font', 'Montserrat' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_general_post_heading_font', 'Montserrat' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_general_heading_font', 'Oswald' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_general_heading_font', 'Oswald' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_typography_option', 'Oswald' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_typography_option', 'Oswald' ) );
        }

        if ( ! empty( get_theme_mod( 'crt_manage_entry_font', 'Oswald' ) ) ) {
            $fonts[] = esc_html( get_theme_mod( 'crt_manage_entry_font', 'Oswald' ) );
        }

        $fonts = array_unique( $fonts );

        $font_hide = array('100', '100italic','200', '200italic', '300italic', 'italic', '500italic', '600', '600italic', '700italic', '800', '800italic', '900', '900italic');

        foreach ( $fonts as $font ) {
            $variants      = $all_fonts[ $font ]['variants'];
            $variants = array_diff($variants, $font_hide);
            $font_family[] = $font . ':' . implode( ',', $variants );
        }
        $query_args = array(
            'family' => urlencode( implode( '|', $font_family ) ),
        );

        if ( ! empty( $font_family ) ) {
            $fonts_url = add_query_arg( $query_args, 'https://fonts.googleapis.com/css' );
        }

        return $fonts_url;
    }
endif;

function crt_manage_dynamic_css() {
    $body_font = get_theme_mod('crt_manage_general_body_font', 'Montserrat');
    $heading_font = get_theme_mod('crt_manage_general_heading_font', 'Montserrat');
    $logo_font = get_theme_mod('crt_manage_header_logo_font', 'Montserrat');
    $nav_font = get_theme_mod('crt_manage_general_nav_font', 'Montserrat');
    $nav_transform = get_theme_mod('crt_manage_general_nav_transform', 'uppercase');

    $custom_css = '';
    $custom_css .= ' :root {
       --body-font: '. esc_attr( $body_font ) .';
       --heading-font: '. esc_attr( $heading_font ) .';
       --logo-font: '. esc_attr( $logo_font ) .';
       --nav-font: '. esc_attr( $nav_font ) .';
       --header-nav-transform: '. esc_attr( $nav_transform ) .';
       --header-nav-bg: #DDD;
//       --background-color: "#fdfdff";
//       --background-active: "#222";
    }';

    wp_register_style( 'teri-shop-ecommerce-style-inline', false );
    wp_enqueue_style( 'teri-shop-ecommerce-style-inline' );
    wp_add_inline_style( 'teri-shop-ecommerce-style-inline', $custom_css );
    wp_enqueue_style( 'teri-shop-ecommerce-google-fonts', wptt_get_webfont_url( crt_manage_teri_shop_ecommerce_fonts() ), array(), null );
}
//add_action( 'wp_enqueue_scripts', 'crt_manage_dynamic_css' );
