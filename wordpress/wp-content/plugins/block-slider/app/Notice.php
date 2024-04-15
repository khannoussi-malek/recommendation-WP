<?php
/**
 * Main file for slider
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * Main class to handle admin notices
 */
class Notice {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		$this->register();

	}

	/**
	 * All notices should be registered here.
	 *
	 * @return void
	 */
	public function register() {

		new \CakeWP\BlockSlider\Notices\UnsupportedVersionNotice();

	}

}
