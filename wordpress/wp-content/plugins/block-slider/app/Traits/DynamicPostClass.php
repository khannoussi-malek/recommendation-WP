<?php
/**
 * This trait helps replacing post id class in a certain block.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Traits;

/**
 * Main trait used to replace dynamic post class a block.
 */
trait DynamicPostClass {

	/**
	 * Initialize the helper.
	 *
	 * @param string $slug - Block slug.
	 *
	 * @return void
	 */
	public function __construct( $slug ) {
		\add_filter( sprintf( 'render_block_%1$s', $slug ), array( $this, 'add_class' ), 10, 2 );
	}

	/**
	 * Adds a post class to a block instance.
	 *
	 * @param string $block_content - Block content.
	 */
	public function add_class( $block_content ) {

		libxml_use_internal_errors( true ); // For surpressing any document error.

		if ( '' === $block_content || is_admin() ) {
			return $block_content;
		}

		$document = new \DOMDocument();
		// Setting correct encoding type.
		$document->loadHTML( '<?xml encoding="utf-8" ?>' . $block_content );

		$block_wrapper = $document->getElementsByTagName( 'body' )->item( 0 )->firstChild;

		if ( is_null( $block_wrapper ) ) {
			return $block_content;
		}

		libxml_clear_errors();

		$classlist = explode( ' ', $block_wrapper->getAttribute( 'class' ) ?? '' );

		$post = apply_filters( 'blockslider_post', null );
		// Adding a dynamic class.
		array_push( $classlist, 'blockslider-post-' . $post->ID );

		$block_wrapper->setAttribute(
			'class',
			join( ' ', $classlist )
		);

		return $document->saveHTML( $document->getElementsByTagName( 'body' )->item( 0 )->firstChild );
	}

}
