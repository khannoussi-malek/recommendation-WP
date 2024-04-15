<?php
/**
 * Divi Support
 *
 * @package BlockSlider
 * @author zafarKamal
 */

namespace CakeWP\BlockSlider\Support\PageBuilder;

use function CakeWP\BlockSlider\Utils\blockslider_has_query_arg;

/**
 * Handles divi support
 */
class Divi {

	/**
	 * Checks if divi visual builder is active.
	 *
	 * @return bool True if active, otherwise false.
	 */
	public static function is_active() {
		// phpcs:ignore WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$request_uri = isset( $_SERVER['REQUEST_URI'] ) ? $_SERVER['REQUEST_URI'] : '';
		return blockslider_has_query_arg( $request_uri, 'et_pb_preview', 'true' );
	}

}
