<?php
/**
 * Unsupported Version Notice
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Views;

use CakeWP\BlockSlider\App;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * This view contains notice for unsupported WP Version
 */
class UnsupportedVersionNotice {


	/**
	 * Renders the view content
	 *
	 * @return void
	 */
	public static function render() {
		?>
			<div class="notice blockslider-unsupported-version-notice notice-warning is-dismissible">
				<p>
					<?php
						sprintf(
							// translators: WordPress version.
							esc_html__( 'Some features of Block Slider might not work properly. Please upgrade your WordPress to version %1$s or higher.', 'block-slider' ),
							App::$minimum_required_wp_version
						)
					?>
				</p>
				<a style="text-decoration:none" class="notice-dismiss" href="?dismiss-blockslider-unsupported-notice">
					<span class="screen-reader-text">
						<?php esc_html_x( 'Dismiss this notice.', 'Dismiss unsupported version notice screen reader.', 'block-slider' ); ?>
					</span>
				</a>
			</div>
		<?php
	}

}
