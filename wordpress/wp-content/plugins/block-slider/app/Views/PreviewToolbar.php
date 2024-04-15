<?php

/**
 * Preview toolbar view file
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Views;

use CakeWP\BlockSlider\Views\Templates\CopyToClipboardButton;

/**
 * Handles the rendering of admin page
 */
class PreviewToolbar {


	/**
	 * Detect if we need to show the preview toolbar.
	 *
	 *  @return bool true if should show, otherwise false.
	 */
	public static function should_show() {      // phpcs:ignore WordPress.Security.NonceVerification.Recommended, WordPress.Security.ValidatedSanitizedInput.MissingUnslash, WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
		$should_hide = isset( $_GET['hide-blockslider-toolbar'] ) ? false : true && is_user_logged_in();

		return $should_hide;
	}

	/**
	 * Renders the view content
	 *
	 * @return void Slider
	 */
	public static function render() {
		if ( ! self::should_show() ) {
			return;
		}

		$current_post_id = get_the_ID();
		$post            = get_post( $current_post_id );
		$shortcode       = sprintf( '[blockslider id="%1$s"]', $current_post_id );

		$classlist = array( 'blockslider-preview-toolbar' );

		if ( is_admin_bar_showing() ) {
			$classlist[] = 'with-admin-bar';
		}

		?>
		<div class="<?php echo esc_html( join( ' ', $classlist ) ); ?>">
			<span>
				<?php echo esc_html( wp_trim_words( $post->post_title, 2 ) ); ?>
			</span>
			<span>	
				<?php CopyToClipboardButton::render( __( 'Copy Shortcode', 'block-slider' ), $shortcode ); ?>
			</span>
		</div>
		<?php
	}
}
