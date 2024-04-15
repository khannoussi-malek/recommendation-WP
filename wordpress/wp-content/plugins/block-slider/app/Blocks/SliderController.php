<?php
/**
 * Main file for slider controller block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

/**
 * Main class for slider inserter
 */
class SliderController {

	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/slider-controller';

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
			\wp_enqueue_script( 'cwpbs-block-slider-slider-controller-script' );
			\wp_enqueue_style( 'cwpbs-block-slider-slider-controller-editor-style' );
		}
	}


	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {

		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/slider-controller'
		);

		\wp_register_script(
			'cwpbs-block-slider-slider-controller-script',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/slider-controller/slider-controller.js',
			array(),
			uniqid(),
			true
		);

		\wp_register_style(
			'cwpbs-block-slider-slider-controller-editor-style',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/slider-controller/slider-controller-editor.css',
			array(),
			uniqid()
		);
	}

}
