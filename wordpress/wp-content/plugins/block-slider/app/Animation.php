<?php
/**
 * Animation.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

class Animation {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		if ( cwpbs_fs()->can_use_premium_code() ) {
			add_filter( 'render_block_cakewp/block-slider', array( $this, 'add_support' ), 10, 3 );
		}
	}

	/**
	 * Adds the animation needed support to the block slider.
	 *
	 * Note: Using the native html tag processor API if found. Otherwise fallback to the DOMDocument.
	 *
	 * @param string    $block_content - Block content.
	 * @param string    $block         - Parsed block.
	 * @param \WP_Block $instance      - Parsed block instance.
	 */
	public function add_support( $block_content, $block, $instance ) {

		$is_processor_supported = class_exists( 'WP_HTML_Tag_Processor' );
		$animation              = isset( $block['attrs']['blocksliderAnimation'] ) ? $block['attrs']['blocksliderAnimation'] : array();

		$has_animation = isset( $animation['type'] ) && isset( $animation['speed'] ) && isset( $animation['direction'] );

		if ( ! $has_animation ) {
			return $block_content;
		}

		if ( $is_processor_supported ) {

			$processor = new \WP_HTML_Tag_Processor( $block_content );
			$processor->next_tag();

			$processor->set_attribute( 'data-animation-type', $animation['type'] );
			$processor->set_attribute( 'data-animation-speed', $animation['speed'] );
			$processor->set_attribute( 'data-animation-direction', $animation['direction'] );

			return $processor->get_updated_html();
		} else {
			$dom = new \DOMDocument();

			libxml_use_internal_errors( true ); // Suppress errors.
			$dom->loadHTML( $block_content, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD );
			libxml_clear_errors(); // Clear any errors that were suppressed.

			$xpath     = new \DOMXPath( $dom );
			$container = $xpath->query( "//*[contains(@class, 'wp-block-slider')]" );

			$container->item( 0 )->setAttribute( 'data-animation-type', $animation['type'] );
			$container->item( 0 )->setAttribute( 'data-animation-speed', $animation['speed'] );
			$container->item( 0 )->setAttribute( 'data-animation-direction', $animation['direction'] );

			return $dom->saveHTML();
		}

	}
};
