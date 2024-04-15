<?php
/**
 * Main file for no result block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

use CakeWP\BlockSlider\Traits\DynamicPostClass;
use CakeWP\BlockSlider\Traits\VariablesCollector;


/**
 * Main class for no result block
 */
class NoResult {


	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/no-result';


	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		\add_action( 'init', array( $this, 'register' ) );
		\add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );

	}

	/**
	 * All editor assets should be enqueued here.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {

		$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		if ( ! is_null( $post ) && 'blockslider' === $post->post_type ) {
			\wp_enqueue_script(
				'cwpbs-block-slider-no-result',
				CWPBS_PLUGIN_URL . 'dist/blocks-library/no-result/no-result.js',
				array(),
				uniqid(),
				true
			);
		}
	}

	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {

		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/no-result',
			array()
		);

	}
}
