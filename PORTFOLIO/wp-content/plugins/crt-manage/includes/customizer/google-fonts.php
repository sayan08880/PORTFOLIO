<?php

if ( ! function_exists( 'crt_manage_get_all_google_fonts' ) ) :
	/**
	 * Returns list of Google fonts.
	 */
	function crt_manage_get_all_google_fonts() {
		$webfonts_json   = CRT_MANAGE_DIR . 'includes/customizer/google-webfonts.json';
		$fonts_json_data = file_get_contents( $webfonts_json );

		$all_fonts = json_decode( $fonts_json_data, true );

		$google_fonts = array();
		foreach ( $all_fonts as $font ) {
			$google_fonts[ $font['family'] ] = array(
				'family'   => $font['family'],
				'variants' => $font['variants'],
			);
		}
		return $google_fonts;
	}
endif;

if ( ! function_exists( 'crt_manage_get_all_google_font_families' ) ) :
	/**
	 * Returns list of Google font families.
	 */
	function crt_manage_get_all_google_font_families() {
		$google_fonts  = crt_manage_get_all_google_fonts();
		$font_families = array();
		foreach ( $google_fonts as $font ) {
			$font_families[ $font['family'] ] = $font['family'];
		}
		return $font_families;
	}
endif;

