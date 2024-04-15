<?php
/**
 * Main file for pagination block
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Blocks;

use CakeWP\BlockSlider\Traits\DynamicPostClass;
use CakeWP\BlockSlider\Traits\VariablesCollector;

/**
 * Main class for pagination block.
 *
 * This block is the bases for the following blocks, the following blocks are the variation of this block:
 * 1. Numbered Pagination
 * 2. Dot Pagination
 */
class Pagination {


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
	public static $slug = 'cakewp/pagination';

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
			\wp_enqueue_script( 'cwpbs-block-slider-pagination-script' );
			\wp_enqueue_style( 'cwpbs-block-slider-pagination-editor-style' );
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
	 * Enqueue all the necessary assets to make blockslider pagination block work on the frontend.
	 *
	 * @return void
	 */
	public static function load_all_frontend_assets() {
		\wp_enqueue_style( 'cwpbs-block-slider-pagination-frontend-style' );
	}

	/**
	 * Renders the pagination block.
	 *
	 * @param array  $attributes - Block Attributes.
	 * @param string $content - Block Content.
	 *
	 * @return string - New block content.
	 */
	public function render( $attributes, $content ) {

		if ( ! isset( $attributes['paginationType'] ) || 'thumbnail' !== $attributes['paginationType'] ) {
			return $content;
		}

		return $this->render_thumbnails( $attributes, $content );
	}

	/**
	 * Dynamically render thumbnail images.
	 *
	 * @param array  $attributes - Block Attributes.
	 * @param string $content - Block Content.
	 *
	 * @return string
	 */
	private function render_thumbnails( $attributes, $content ) {

		$blockslider_post = apply_filters( 'blockslider_post', null );

		if ( is_null( $blockslider_post ) ) {
			return $content;
		}

		$slide_count = substr_count( $blockslider_post->post_content, 'blockslider-slide-inner-wrapper' );

		$thumbnails = '';

		for ( $index = 0; $index < $slide_count; $index++ ) {

			$indexed_key = 'index-' . $index;
			$attachment  = isset( $attributes['thumbnailImages'][ $indexed_key ] ) ? $attributes['thumbnailImages'][ $indexed_key ] : null;
			$placeholder = \CakeWP\BlockSlider\Assets::get_path( 'icons/image-placeholder.svg' );

			$attachment_id = isset( $attachment['id'] ) ? $attachment['id'] : -1;

			$alt_text = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );

			if ( '' === $alt_text ) {
				$alt_text = __( 'Image Thumbnail', 'block-slider' );
			}

			$attachment_src = isset( $attachment['sizes'][ $attributes['imageSize'] ]['url'] ) ? $attachment['sizes'][ $attributes['imageSize'] ]['url'] : null;

			// Falling back to the full image size if the selected size is unavailable.
			if ( is_null( $attachment_src ) ) {
				$attachment_src = isset( $attachment['sizes']['full']['url'] ) ? $attachment['sizes']['full']['url'] : null;
			}

			$thumbnail = sprintf(
				'<div class="blockslider-thumb-slide blockslider-block-pagination-thumb-item">
					<img src="%1$s" alt="%2$s" />
				</div>',
				is_null( $attachment ) ? $placeholder : esc_url( $attachment_src ),
				esc_html( $alt_text )
			);

			$thumbnails .= $thumbnail;
		}

		return str_replace( '{{content}}', $thumbnails, $content );
	}

	/**
	 * Register block.
	 *
	 * @return void
	 */
	public function register() {
		\register_block_type(
			CWPBS_DIR_PATH . 'blocks/pagination',
			array(

				'render_callback' => array( $this, 'render' ),
			)
		);

		\wp_register_style(
			'cwpbs-block-slider-pagination-frontend-style',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/pagination/pagination-frontend.css',
			array(),
			uniqid()
		);

		\wp_register_script(
			'cwpbs-block-slider-pagination-script',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/pagination/pagination.js',
			array(),
			uniqid(),
			true
		);

		\wp_register_style(
			'cwpbs-block-slider-pagination-editor-style',
			CWPBS_PLUGIN_URL . 'dist/blocks-library/pagination/pagination-editor.css',
			array(),
			uniqid()
		);
	}
}
