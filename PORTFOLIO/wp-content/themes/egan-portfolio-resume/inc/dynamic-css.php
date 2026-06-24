<?php

/**
 * Dynamic CSS
 */
function egan_portfolio_resume_dynamic_css() {

	$header_font           = get_theme_mod( 'egan_portfolio_resume_header_font', 'Fira Sans' );
	$body_font             = get_theme_mod( 'egan_portfolio_resume_body_font', 'Merriweather' );
	$site_title_font       = get_theme_mod( 'egan_portfolio_resume_site_title_font', 'Fira Sans' );
	$site_description_font = get_theme_mod( 'egan_portfolio_resume_site_description_font', 'Roboto' );
	$homepage_type         = get_theme_mod( 'crt_manage_general_homepage_options');
	$border_item_space     = get_theme_mod( 'crt_manage_general_homepage_border_item_space', 'large' );
	$border_item_color     = get_theme_mod( 'crt_manage_general_homepage_border_item_color', '#000' );

    $space = '1.5rem';
    if($homepage_type == 'home-border-item') {
        if($border_item_space == 'large') {
            $space = '3rem';
        } elseif($border_item_space == 'small') {
            $space = '2rem';
        }
    }
    $custom_css = '';

	$custom_css .= '
    /* Color */
    :root {
		--header-text-color: ' . '#' . esc_attr( get_header_textcolor() ) . ';
    }
    ';

	$custom_css .= '
    /* Typograhpy */
    :root {
        --font-heading: "' . esc_attr( $header_font ) . '", serif;
        --font-main: -apple-system, BlinkMacSystemFont,"' . esc_attr( $body_font ) . '", "Segoe UI", Roboto, Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", sans-serif;
    }

    body,
	button, input, select, optgroup, textarea {
        font-family: "' . esc_attr( $body_font ) . '", serif;
	}

	.site-title a {
        font-family: "' . esc_attr( $site_title_font ) . '", serif;
	}
    
	.site-description {
        font-family: "' . esc_attr( $site_description_font ) . '", serif;
	}
	.home-border-item {
	    --primary-custom-border-color: '.esc_attr( $border_item_color ).';
	}
	.home-border-item .row {
        --bs-gutter-x: '.esc_attr( $space ).';
    }
    ';

	wp_add_inline_style( 'tenzin-news-magazine-style', $custom_css );

}
add_action( 'wp_enqueue_scripts', 'egan_portfolio_resume_dynamic_css', 99 );
