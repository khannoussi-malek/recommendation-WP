<?php
/**
 * Main file for subscriber email block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

use CakeWP\BlockSlider\Assets;
use CakeWP\BlockSlider\Traits\DynamicPostClass;
use CakeWP\BlockSlider\Traits\VariablesCollector;

/**
 * Main class for blockslider block
 */
class BlockSlider {

	use DynamicPostClass {
		DynamicPostClass::__construct as private initialize_helper;
	}

	use VariablesCollector {
		VariablesCollector::__construct as private initialize_collector;
	}

	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/block-slider';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		\add_action( 'init', array( $this, 'register' ) );

		$this->initialize_helper( self::$slug );
		$this->initialize_collector( self::$slug );

		\add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		\add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_post_type_assets' ) );

	}

	/**
	 * Loads all necessary frontend assets in the CPT preview.
	 *
	 * @return void
	 */
	public function load_frontend_post_type_assets() {

		$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		if ( ! is_null( $post ) && 'blockslider' === $post->post_type && ! is_admin() ) {
			self::load_all_frontend_assets();
		}

	}


	/**
	 * All editor assets should be enqueued here.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {

		$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		if ( ! is_null( $post ) && 'blockslider' === $post->post_type ) {
			\wp_enqueue_script( 'cwpbs-block-slider-script' );
		}

	}


	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {

		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/block-slider',
		);

		if ( ! is_admin() ) {
			\wp_register_script(
				'cwpbs-block-slider-frontend',
				CWPBS_PLUGIN_URL . 'dist/frontend/frontend.js',
				array(),
				uniqid(),
				false
			);

			\wp_register_style(
				'cwpbs-block-slider-frontend-style',
				CWPBS_PLUGIN_URL . 'dist/blocks-library/block-slider/block-slider-frontend.css',
				array(),
				uniqid()
			);
		}
		\wp_register_script(
			'cwpbs-block-slider-script',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/block-slider/block-slider.js',
			array(
				'block-slider-components',
				'block-slider-data',
				'block-slider-plugins',
				'block-slider-transformkit',
				'block-slider-patterns-library',
				'lodash',
			),
			uniqid(),
			true
		);

		\wp_register_style(
			'cwpbs-block-slider-editor-style',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/block-slider/block-slider-editor.css',
			array(
				'block-slider-components',
				'block-slider-plugins',
				'block-slider-transformkit',
				'block-slider-data',
				'block-slider-patterns-library',
			),
			uniqid()
		);

		$licensing = array(
			'isPremium'   => cwpbs_fs()->can_use_premium_code__premium_only(),
			'placeholder' => Assets::get_path( 'icons/image-placeholder.svg' ),
		);

		\wp_localize_script( 'cwpbs-block-slider-script', 'blockslider', $licensing );
	}

	/**
	 * Enqueue all the necessary assets to make blockslider work on the frontend.
	 *
	 * @return void
	 */
	public static function load_all_frontend_assets() {
		\wp_enqueue_style( 'cwpbs-block-slider-frontend-style' );
		\wp_enqueue_script( 'cwpbs-block-slider-frontend' );
	}
}
