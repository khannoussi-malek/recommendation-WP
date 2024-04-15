<?php
/**
 * Main file for slider inserter block
 *
 * @author  zafarKamal
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

/**
 * Main class for slider inserter
 */
class SliderInserter {

	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/slider-inserter';

	/**
	 * Script handle for gutenberg editor.
	 *
	 * @var string
	 */
	public static $editor_script_handle = 'cwpbs-block-slider-inserter-script';

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

		if ( is_null( $post ) || 'blockslider' !== $post->post_type ) {
			\wp_enqueue_script( self::$editor_script_handle );
			\wp_enqueue_style( 'cwpbs-block-slider-inserter-editor-style' );
		}

	}


	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {

		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/slider-inserter'
		);

		if ( ! is_admin() ) {
			\wp_register_style(
				'cwpbs-block-slider-inserter-frontend-style',
				CWPBS_PLUGIN_URL . 'dist/blocks-library/slider-inserter/slider-inserter-frontend.css',
				array(),
				uniqid()
			);
		}

		\wp_register_script(
			self::$editor_script_handle,
			CWPBS_PLUGIN_URL . 'dist/blocks-library/slider-inserter/slider-inserter.js',
			array(
				'lodash',
			),
			uniqid(),
			true
		);

		\wp_register_style(
			'cwpbs-block-slider-inserter-editor-style',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/slider-inserter/slider-inserter-editor.css',
			array(),
			uniqid()
		);
	}

}
