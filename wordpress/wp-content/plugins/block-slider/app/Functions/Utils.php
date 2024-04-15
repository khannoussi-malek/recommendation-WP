<?php
/**
 * Utilities.
 *
 * @package BlockSlider
 * @author zafarKamal
 */

namespace CakeWP\BlockSlider\Utils;

/**
 * Generates a new styles id for blockslider instance.
 *
 * @param string $styles - Styles to generate id for.
 */
function blockslider_generate_styles_id( $styles ) {
	// Creating a unique hash from these styles.
	// This id will only be changed if the styles has been updated.
	// Doing so, will allow us to group multiple same styles attributes into one css declaration.
	$unique_block_id = md5( $styles );

	// Providing the first 5 unique hash characters.
	if ( strlen( $unique_block_id ) > 5 ) {
		$unique_block_id = substr( $unique_block_id, 0, 6 );
	}

	return $unique_block_id;
}

/**
 * Provides a unique array without repeative values of a certain key.
 *
 * @param array  $array - Array to filter.
 * @param string $unique_key - Key to filter unique values by.
 *
 * @return array - Unique array.
 */
function blockslider_unique_array_by_key( $array, $unique_key ) {
	$unique = array();

	foreach ( $array as $value ) {
		$unique[ $value[ $unique_key ] ] = $value;
	}

	$data = array_values( $unique );

	return $data;
}

/**
 * Checks if the given url contains the expected query string.
 *
 * @param string $url Url to check.
 * @param string $query_arg_name Argument name to test.
 * @param string $query_arg_value Argument value to compare.
 *
 * @return bool True if exists, otherwise false.
 */
function blockslider_has_query_arg( $url, $query_arg_name, $query_arg_value = '' ) {

	$parsed_url     = \wp_parse_url( $url );
	$query_args     = isset( $parsed_url['query'] ) ? $parsed_url['query'] : '';
	$expected_query = $query_arg_name . '=' . $query_arg_value;

	// Only using query name if no value is provided for comparision.
	if ( '' === $query_arg_value ) {
		$expected_query = $query_arg_name;
	}

	return strpos( $query_args, $expected_query ) !== false;
}
