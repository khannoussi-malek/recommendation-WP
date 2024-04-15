<?php
/**
 * Main preview file
 *
 * @author  zafarKamal
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

/**
 * Handles block slider preview in the CPT.
 */
class Preview {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		\add_action( 'wp_enqueue_scripts', array( $this, 'load_assets' ) );

		/**
		 * This hook fires before wp determines which template to load.
		 *
		 * @see https://developer.wordpress.org/reference/hooks/template_redirect/
		 */
		add_filter( 'template_redirect', array( $this, 'handle_admin_bar_visibility' ) );
		add_action( 'wp_head', array( $this, 'handle_not_found_request' ) );

	}

	/**
	 * Loads necessary assets for blockslider preview.
	 *
	 * @return void
	 */
	public function load_assets() {
		\wp_enqueue_style(
			'blockslider-preview-style',
			CWPBS_PLUGIN_URL . 'css/slider-preview.css',
			array(),
			'latest_new'
		);
	}

	/**
	 * Handles the not found request for blockslider preview ancestor frame.
	 *
	 * The following script helps to report back to the ancestor frame that the required slider
	 * is failed to be obtained, and it should handle the request accordingly.
	 *
	 * @return void
	 */
	public function handle_not_found_request() {

		if ( self::is_blockslider_preview_page( true ) && is_404() ) {
			?>
				<script id="blockslider-preview-not-found">
					function blocksliderReportBackStatus() {
						const params = new URLSearchParams( window.location.search );
						const ancestorFrameId = params.get( 'blockslider-frame-id' );
						parent.postMessage( 'blockslider-not-found-'.concat( ancestorFrameId ) );
					}

					if ( parent ) {
						blocksliderReportBackStatus();
					}
				</script>
			<?php

		}

	}

	/**
	 * Checks if the block slider preview is currently active.
	 *
	 * @param  bool $only_check_query Will only check for url query, if this param is true.
	 * @return bool True if active, otherwise false.
	 */
	public static function is_blockslider_preview_page( $only_check_query = false ) {
		$current_post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

        // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$is_blockslider_preview = isset( $_GET['blockslider-preview'] ) ? true : false;

		if ( $only_check_query ) {
			return $is_blockslider_preview;
		}

		return $is_blockslider_preview && ! is_null( $current_post ) && \CakeWP\BlockSlider\Slider::$post_type === $current_post->post_type;

	}

	/**
	 * Handles the visibility of admin bar.
	 *
	 * @return void
	 */
	public function handle_admin_bar_visibility() {

		if ( self::is_blockslider_preview_page() ) {
			show_admin_bar( false );
		}

	}

}
