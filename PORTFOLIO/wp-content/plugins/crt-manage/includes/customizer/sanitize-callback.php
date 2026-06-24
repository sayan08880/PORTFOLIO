<?php

/**
 * Sanitize select field
 *
 * @param  string $input   Selected input.
 * @param  string $setting Input setting.
 */
function crt_manage_sanitize_select( $input, $setting ) {
	// input must be a slug: lowercase alphanumeric characters, dashes and underscores are allowed only.
	$input = sanitize_key( $input );

	// get the list of possible select options.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// return input if valid or return default option.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );
}

/**
 * Sanitize checkbox field
 *
 * @param  string $input Selected input.
 */
function crt_manage_sanitize_checkbox( $input ) {

	// returns true if checkbox is checked.
	return ( ( isset( $input ) && true == $input ) ? true : false );
}

function crt_manage_sanitize_google_fonts( $input, $setting ) {

	// Get list of choices from the control associated with the setting.
	$choices = $setting->manager->get_control( $setting->id )->choices;

	// If the input is a valid key, return it; otherwise, return the default.
	return ( array_key_exists( $input, $choices ) ? $input : $setting->default );

}

function crt_manage_sanitize_image( $image, $setting ) {
	/*
	 * Array of valid image file types.
	 *
	 * The array includes image mime types that are included in wp_get_mime_types()
	 */
	$mimes = array(
		'jpg|jpeg|jpe' => 'image/jpeg',
		'gif'          => 'image/gif',
		'png'          => 'image/png',
		'bmp'          => 'image/bmp',
		'tif|tiff'     => 'image/tiff',
		'ico'          => 'image/x-icon',
		'svg'          => 'image/svg+xml',
	);
	// Return an array with file extension and mime_type.
	$file = wp_check_filetype( $image, $mimes );
	// If $image has a valid mime_type, return it; otherwise, return the default.
	return ( $file['ext'] ? $image : $setting->default );
}

/**
 * Sanitize switch control
 *
 * @param  string   Switch value
 * @return integer  Sanitized value
 */
function crt_manage_sanitize_switch( $input ) {
	if ( true === $input ) {
		return true;
	} else {
		return false;
	}
}

/**
 * Sanitize number range
 *
 * @param  string $input Input value.
 * @param  string $setting Input setting.
 */
function crt_manage_sanitize_number_range( $input, $setting ) {
	$input = absint( $input );
	$atts  = $setting->manager->get_control( $setting->id )->input_attrs;

	$min  = ( isset( $atts['min'] ) ? $atts['min'] : $input );
	$max  = ( isset( $atts['max'] ) ? $atts['max'] : $input );
	$step = ( isset( $atts['step'] ) ? $atts['step'] : 1 );

	// If the number is within the valid range, return it; otherwise, return the default.
	return ( $min <= $input && $input <= $max ? $input : $setting->default );
}

function crt_manage_ic_sanitize_pdf( $file, $setting ) {
    $mimes = array(
        'jpg|jpeg|jpe' => 'image/jpeg',
        'png'          => 'image/png',
        'pdf'          => 'application/pdf',
        'pptx'         => 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        'ppt'          => 'application/vnd.ms-powerpoint'
    );

    //check file type from file name
    $file_ext = wp_check_filetype( $file, $mimes );

    //if file has a valid mime type return it, otherwise return default
    return ( $file_ext['ext'] ? $file : $setting->default );
}

function crt_manage_customizer_repeater_sanitize($input){
    $input_decoded = json_decode($input,true);
    if(!empty($input_decoded)) {
        foreach ($input_decoded as $boxk => $box ){
            foreach ($box as $key => $value){
                $input_decoded[$boxk][$key] = wp_kses_post( force_balance_tags( $value ) );
            }
        }
        return json_encode($input_decoded);
    }
    return $input;
}

function crt_manage_sanitize_sections_order( $input ) {

    $json = json_decode( $input, true );
    foreach ( $json as $section => $priority ) {
        if ( ! is_string( $section ) || ! is_int( $priority ) ) {
            return false;
        }
    }
    $filter_empty = array_filter( $json, 'crt_manage_check_not_empty' );
    return json_encode( $filter_empty );
}

/**
 * Function to filter json empty elements.
 *
 * @param int $val Element of json decoded.
 *
 * @return bool
 */
function crt_manage_check_not_empty( $val ) {
    return ! empty( $val );
}

/**
 * Function to sanitize controls that returns arrays
 *
 * @since 1.1.40
 * @param mixed $input Control output.
 */
function crt_manage_sanitize_array( $input ) {
    $output = $input;
    if ( ! is_array( $input ) ) {
        $output = explode( ',', $input );
    }
    if ( ! empty( $output ) ) {
        return array_map( 'sanitize_text_field', $output );
    }
    return array();
}