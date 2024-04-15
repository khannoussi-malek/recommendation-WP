<?php
/**
 * Admin
 *
 * Main admin file for the plugin.
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

use CakeWP\BlockSlider\App;

/**
 * This class handles any file loading for the admin area.
 */
class Admin {

	/**
	 * The instance
	 *
	 * @var $instance
	 */
	public static $instance = null;


	/**
	 * Adds various actions to set up the page
	 *
	 * @return self|void
	 */
	public function __construct() {
		if ( self::$instance ) {
			return self::$instance;
		}

		self::$instance = $this;

		\add_action( 'init', array( $this, 'register_assets' ) );
		\add_action( 'admin_enqueue_scripts', array( $this, 'load_assets' ) );

	}

	/**
	 * All assets registrations should be done here
	 *
	 * @return void
	 */
	public function register_assets() {

		// TODO: change this dynamic version to static plugin version in production environment.
		$version = wp_unique_id( App::$slug );

		\wp_register_script(
			App::$slug . '-admin-scripts',
			CWPBS_PLUGIN_URL . 'dist/admin/admin.js',
			array(
				'lodash',
				'wp-api',
				'wp-i18n',
				'wp-components',
				'wp-element',
				'wp-editor',
			),
			uniqid(),
			true
		);

		\wp_register_style(
			App::$slug . '-admin-scripts',
			CWPBS_PLUGIN_URL . 'dist/admin/admin.css',
			array(
				'wp-components',
			),
			uniqid()
		);

	}


	/**
	 * All assets enqueuing should be done here
	 *
	 * @param string $hook_suffix - Page suffix.
	 * @return void
	 */
	public function load_assets( $hook_suffix ) {
		if ( 'toplevel_page_blockslider-dashboard' === $hook_suffix ) {
			\wp_enqueue_script( App::$slug . '-admin-scripts' );
			\wp_localize_script(
				App::$slug . '-admin-scripts',
				'blockslider',
				array(
					'version'   => App::get( 'version' ),
					'isPremium' => cwpbs_fs()->can_use_premium_code__premium_only(),
					'url'       => CWPBS_PLUGIN_URL,
					'createUrl' => add_query_arg(
						array( 'post_type' => \CakeWP\BlockSlider\Slider::$post_type ),
						\admin_url( 'post-new.php' )
					),
				)
			);
			\wp_enqueue_style( App::$slug . '-admin-scripts' );
		}
	}


}
