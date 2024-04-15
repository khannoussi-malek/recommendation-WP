<?php
/**
 * Styles Manager.
 *
 * Main file to handle and manage blockslider styles.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

use function CakeWP\BlockSlider\Utils\blockslider_unique_array_by_key;

/**
 * Main class to handle blockslider styles.
 */
class StylesManager {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		\add_action( 'wp_footer', array( $this, 'render_inline_styles' ) );

	}

	/**
	 * Renders inline blockslider styles.
	 */
	public function render_inline_styles() {

		$styles = apply_filters( 'blockslider_inline_styles', array() );

		// Removing duplicate selectors.
		$styles = blockslider_unique_array_by_key( $styles, 'selector' );

		$inline_styles = '';

		foreach ( $styles as $style ) {
			$inline_styles .= sprintf( '.%1$s{%2$s}', $style['selector'], $style['styles'] );
		}

		/**
		 * The generated CSS should not contain any html markup.
		 * Using regex to match and strip these html tags.
		 *
		 * WordPress is also using some regex to validate the CSS output.
		 *
		 * @see https://github.com/WordPress/WordPress/blob/56c162fbc9867f923862f64f1b4570d885f1ff03/wp-includes/customize/class-wp-customize-custom-css-setting.php#L157-L168
		 */
		if ( preg_match( '#</?\w+#', $inline_styles ) ) {

			// Replacing all the matched tags.
			$inline_styles = preg_replace( '#</?\w+#', '', $inline_styles );

		}

		// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
		echo '<style id="blockslider-inline-styles"> ' . $inline_styles . ' </style>';
	}

}
