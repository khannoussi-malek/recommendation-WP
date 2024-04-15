<?php
/**
 * Variables Collector.
 *
 * This traits helps collecting css3 variables from a block instance
 * and merges it in the head of the page.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Traits;

use function CakeWP\BlockSlider\Utils\blockslider_generate_styles_id;

trait VariablesCollector {


	/**
	 * Initializes the collector.
	 *
	 * @param string $slug - Block slug.
	 */
	public function __construct( $slug ) {
		\add_filter( sprintf( 'render_block_%1$s', $slug ), array( $this, 'handle_block_variables' ), 10, 2 );
	}


	/**
	 * Handles the variables.
	 *
	 * @param string $block_content - Block content.
	 */
	public function handle_block_variables( $block_content ) {

		libxml_use_internal_errors( true ); // For surpressing any document error.

		$document = new \DOMDocument();

		if ( '' === $block_content ) {
			return $block_content;
		}

		// Setting correct encoding type.
		$document->loadHTML( '<?xml encoding="utf-8" ?>' . $block_content );

		$finder = new \DOMXPath( $document );

		$block_elements_with_variables = $finder->query( "//*[contains(@class, 'blockslider-has-styles')]" );

		foreach ( $block_elements_with_variables as $block_element ) {

			// Merging to inline styles.
			$this->process_block_styles( $block_element );
		}

		libxml_clear_errors();

		return $document->saveHTML( $document->getElementsByTagName( 'body' )->item( 0 )->firstChild );

	}

	/**
	 * Processes the block styles.
	 *
	 * @param \DOMNode $block_element - Reference to Block element remove variables from.
	 */
	private function process_block_styles( \DOMNode &$block_element ) {

		$block_classnames = $block_element->getAttribute( 'class' );

		// Checking if this block has already been processed.
		if ( false !== stripos( $block_classnames, 'blockslider-uid-' ) ) {
			return;
		}

		$block_styles = $block_element->getAttribute( 'data-style' );
		$block_uid    = blockslider_generate_styles_id( $block_styles );
		$block_element->removeAttribute( 'data-style' );

		$classlist = explode( ' ', $block_element->getAttribute( 'class' ) ?? '' );

		$classlist[] = 'blockslider-uid-' . $block_uid;

		$block_element->setAttribute(
			'class',
			join( ' ', $classlist )
		);

		\add_filter(
			'blockslider_inline_styles',
			function( $blockslider_inline_styles ) use ( $block_uid, $block_styles ) {
				$blockslider_inline_styles[] = array(
					'selector' => 'blockslider-uid-' . $block_uid,
					'styles'   => $block_styles,
				);

				return $blockslider_inline_styles;
			}
		);

	}


}
