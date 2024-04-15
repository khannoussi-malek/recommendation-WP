<?php
/**
 * Main file for subscriber email block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

/**
 * Main class for subscriber email block
 */
class BlockSlide {


	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/block-slide';

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
			CWPBS_DIR_PATH . 'blocks/block-slide'
		);

	}

}
