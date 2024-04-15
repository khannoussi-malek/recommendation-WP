<?php
/**
 * Admin
 *
 * Main app file for the plugin.
 *
 * @package BlockSlider
 * @version 1.0.0
 * @author  zafarKamal
 * @license https://www.gnu.org/licenses/gpl-3.0.txt GNU/GPLv3
 * @since   1.0.0
 */

namespace CakeWP\BlockSlider;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * Controller for handling various app data
 */
class App {

	/**
	 * Plugin name
	 *
	 * @var string
	 */
	public static $name = 'Block Slider';

	/**
	 * Plugin slug
	 *
	 * @var string
	 */
	public static $slug = 'block-slider';

	/**
	 * Plugin text domain
	 *
	 * @var string
	 */
	public static $text_domain = 'block-slider';

	/**
	 * Plugin API REST version
	 *
	 * @var string
	 */
	public static $api_version = 'v1';

	/**
	 * Plugin environment
	 *
	 * @var string
	 */
	public static $environment = 'development';

	/**
	 * Host plugin
	 *
	 * @var string
	 */
	public static $required_capabillities = 'manage_options';

	/**
	 * Minimum required WordPress version
	 *
	 * @var string
	 */
	public static $minimum_required_wp_version = '5.9';

	/**
	 * Plugin config
	 *
	 * @var array
	 */
	public static $config = array();

	/**
	 * Obtains specific app details.
	 *
	 * @param string $key Data key to obtain details for.
	 *
	 * @return any|null details if found, otherwise null.
	 */
	public static function get( $key ) {

		$available_data_keys = array( 'version' );

		if ( ! in_array( $key, $available_data_keys, true ) ) {
			return null;
		}

		$plugin_details = \get_plugin_data( CWPBS_DIR_PATH . '/block-slider.php' );

		// TODO: Add more detail entries later...
		switch ( $key ) {
			case 'version':
				return $plugin_details['Version'];
		}

		return null;

	}

	/**
	 * Provides base64 encoded app icon to use.
	 *
	 * @return string Base64 Icon buffer.
	 */
	public static function get_icon() {

		// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
		$icon = file_get_contents( CWPBS_DIR_PATH . '/block-slider.svg' );

		// phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
		return 'data:image/svg+xml;base64,' . base64_encode( $icon );

	}

}
