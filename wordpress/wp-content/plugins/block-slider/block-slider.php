<?php
/**
 * Plugin Name: Block Slider
 * Description: Create awesome sliders using block slider plugin.
 * Author: Cakewp
 * Author URI: https://www.cakewp.com
 * Version: 2.2.3
 * Requires at least: 5.7
 * Requires PHP: 5.6
 * Text Domain: block-slider
 * Domain Path: /languages
 * Tested up to: 6.4
 *
 * @package BlockSlider
 */

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access' );
}

if ( ! defined( 'CWPBS_DIR_PATH' ) ) {
	define( 'CWPBS_DIR_PATH', \plugin_dir_path( __FILE__ ) );
}

if ( is_readable( CWPBS_DIR_PATH . 'lib/autoload.php' ) ) {
	include_once CWPBS_DIR_PATH . 'lib/autoload.php';
}

if ( ! class_exists( 'CWPBS_BlockSlider' ) ) {
	/**
	 * Main plugin class
	 */
	final class CWPBS_BlockSlider {
		/**
		 * Var to make sure we only load once
		 *
		 * @var boolean $loaded
		 */
		public static $loaded = false;

		/**
		 * Set up the plugin
		 *
		 * @return void
		 */
		public function __invoke() {

			if ( ! function_exists( 'cwpbs_fs' ) ) {
				/**
				 * Freemius initialization.
				 */
				function cwpbs_fs() {
					global $cwpbs_fs;

					if ( ! isset( $cwpbs_fs ) ) {
						// Include Freemius SDK.
						require_once dirname( __FILE__ ) . '/freemius/start.php';

						$cwpbs_fs = fs_dynamic_init(
							array(
								'id'                  => '4892',
								'slug'                => 'block-slider',
								'type'                => 'plugin',
								'public_key'          => 'pk_7ae0c8f55e8e50153a0296efc09a5',
								'is_premium'          => false,
								'premium_suffix'      => 'Pro',
								// If your plugin is a serviceware, set this option to false.
								'has_premium_version' => true,
								'has_addons'          => false,
								'has_paid_plans'      => true,
								'trial'               => array(
									'days'               => 14,
									'is_require_payment' => false,
								),
								'menu'                => array(
									'slug'    => 'blockslider-dashboard',
									'contact' => false,
									'support' => false,
								),
							)
						);
					}

					return $cwpbs_fs;
				}

				// Init Freemius.
				cwpbs_fs();
				// Signal that SDK was initiated.
				do_action( 'cwpbs_fs_loaded' );
			}

			if ( ! self::$loaded ) {
				include_once CWPBS_DIR_PATH . 'bootstrap.php';

				self::$loaded = true;
				$app          = new CakeWP\BlockSlider\App();
			}
		}
	}

	$slider = new CWPBS_BlockSlider();
	$slider();
}
