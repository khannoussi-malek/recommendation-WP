<?php
/**
 * Main file for slider inserter block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

use CakeWP\BlockSlider\Traits\DynamicPostClass;
use CakeWP\BlockSlider\Traits\VariablesCollector;


/**
 * Main class for slider inserter
 */
class Navigation {


	/**
	 * Block slug.
	 *
	 * @var string
	 */
	public static $slug = 'cakewp/navigation';

	use DynamicPostClass {
		DynamicPostClass::__construct as private initialize_helper;
	}

	use VariablesCollector {
		VariablesCollector::__construct as private initialize_collector;
	}


	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		\add_action( 'init', array( $this, 'register' ) );
		\add_action( 'enqueue_block_editor_assets', array( $this, 'enqueue_editor_assets' ) );
		\add_action( 'wp_enqueue_scripts', array( $this, 'load_frontend_post_type_assets' ) );

		$this->initialize_helper( self::$slug );
		$this->initialize_collector( self::$slug );
	}

	/**
	 * All editor assets should be enqueued here.
	 *
	 * @return void
	 */
	public function enqueue_editor_assets() {

		$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		if ( ! is_null( $post ) && 'blockslider' === $post->post_type ) {
			\wp_enqueue_script( 'cwpbs-block-slider-navigation-script' );
			\wp_enqueue_style( 'cwpbs-block-slider-navigation-editor-style' );
		}
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
	 * Enqueue all the necessary assets to make blockslider navigation block work on the frontend.
	 *
	 * @return void
	 */
	public static function load_all_frontend_assets() {
		\wp_enqueue_style( 'cwpbs-block-slider-navigation-frontend-style' );
	}

	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {

		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/navigation',
			array()
		);

		if ( ! is_admin() ) {
			\wp_register_style(
				'cwpbs-block-slider-navigation-frontend-style',
				CWPBS_PLUGIN_URL . 'dist/blocks-library/navigation/navigation-frontend.css',
				array(),
				uniqid()
			);
		}

		\wp_register_script(
			'cwpbs-block-slider-navigation-script',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/navigation/navigation.js',
			array(),
			uniqid(),
			true
		);

		\wp_register_style(
			'cwpbs-block-slider-navigation-editor-style',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/navigation/navigation-editor.css',
			array(),
			uniqid()
		);
	}
}
