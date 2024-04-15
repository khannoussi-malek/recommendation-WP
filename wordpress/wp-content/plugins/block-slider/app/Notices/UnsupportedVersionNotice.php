<?php
/**
 * Unsupported Version Notice.
 *
 * @package BlockSlider
 * @author zafarKamal
 */

namespace CakeWP\BlockSlider\Notices;

use CakeWP\BlockSlider\App;
use CakeWP\BlockSlider\Views\UnsupportedVersionNotice as UnsupportedVersionNoticeView;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * Notice for handling unsupported wp version.
 */
class UnsupportedVersionNotice {

	/**
	 * Notice unique slug.
	 *
	 * @var string
	 */
	public static $slug = 'unsupported-version';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		\add_action( 'init', array( $this, 'register' ), 7 );
		\add_action( 'init', array( $this, 'handle_dismiss' ), 10 );

	}

	/**
	 * Checks if the supported version is satisfied.
	 *
	 * @return bool True if can show notice, otherwise false.
	 */
	public function should_show_notice() {
		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( version_compare( $GLOBALS['wp_version'], App::$minimum_required_wp_version, '<' ) && ! isset( $_GET['dismiss-blockslider-unsupported-notice'] ) ) {
			return true;
		}

		return false;
	}


	/**
	 * Handles the dismissing for this particular notice.
	 *
	 * @return void
	 */
	public function handle_dismiss() {

		// phpcs:ignore WordPress.Security.NonceVerification.Recommended
		if ( isset( $_GET['dismiss-blockslider-unsupported-notice'] ) ) {
			$notice_store_id = 'blockslider-notice-' . self::$slug;
			\update_option( $notice_store_id, 'hidden' );
		}
	}

	/**
	 * Registeration.
	 *
	 * @return void
	 */
	public function register() {

		$notice_store_id = 'blockslider-notice-' . self::$slug;

		/**
		 * Registering an option to store notice display status.
		 */
		\register_setting(
			'blockslider',
			$notice_store_id,
			array(
				'type'              => 'string',
				'description'       => 'Blockslider notice status for unsupported wordpress version.',
				'sanitize_callback' => 'sanitize_text_field',
				'show_in_rest'      => true,
				'default'           => 'initial', // Can either be 'hidden', or 'initial'.
			)
		);

		/**
		 * Attaching a custom action for each notice.
		 */
		if ( $this->should_show_notice() ) {
			\add_action( 'admin_notices', array( UnsupportedVersionNoticeView::class, 'render' ) );
		}

	}

}
