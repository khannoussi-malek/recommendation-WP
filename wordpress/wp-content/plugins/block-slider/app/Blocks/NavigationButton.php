<?php
/**
 * Main file for slider inserter block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

/**
 * Main class for navigation button.
 *
 * Note: This block is compiled with navigation buttons parent block.
 */
class NavigationButton {

	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/navigation-button';


	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		\add_action( 'init', array( $this, 'register' ) );
	}

	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {
		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/navigation-button',
			array()
		);
	}

}
