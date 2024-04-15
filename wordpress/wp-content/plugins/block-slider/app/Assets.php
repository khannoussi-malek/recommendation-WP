<?php
/**
 * Main file for registering/loading different assets for blockslider plugin.
 *
 * @author  zafarKamal
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

use CakeWP\BlockSlider\App;

/**
 * Class for handling assets loading
 */
class Assets {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		\add_action( 'enqueue_block_editor_assets', array( $this, 'register' ) );

	}

	/**
	 * Provides constructed url path to the public asset file.
	 *
	 * @param string $absolute_path - Absolute path to merge.
	 *
	 * @return string
	 */
	public static function get_path( $absolute_path ) {
		return CWPBS_PLUGIN_URL . 'public/' . $absolute_path;
	}

	/**
	 * All additional blockslider assets should be registered here.
	 *
	 * @return void
	 */
	public function register() {

		$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

		// Using time as version in development environment. Doing so, will purge all the additional WP cache.
		$version = 'production' === App::$environment ? App::get( 'version' ) : time();

		if ( ! is_null( $post ) && is_admin() && 'blockslider' === $post->post_type ) {

			\wp_register_script(
				App::$slug . '-components',
				CWPBS_PLUGIN_URL . 'dist/components/components.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_register_style(
				App::$slug . '-components',
				CWPBS_PLUGIN_URL . 'dist/components/components.css',
				array(
					'wp-components',
				),
				$version
			);

			\wp_register_script(
				App::$slug . '-data',
				CWPBS_PLUGIN_URL . 'dist/data/data.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_register_style(
				App::$slug . '-data',
				CWPBS_PLUGIN_URL . 'dist/data/data.css',
				array(),
				$version
			);

			\wp_register_script(
				App::$slug . '-plugins',
				CWPBS_PLUGIN_URL . 'dist/plugins/plugins.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_register_style(
				App::$slug . '-plugins',
				CWPBS_PLUGIN_URL . 'dist/plugins/plugins.css',
				array(
					'wp-components',
				),
				$version
			);

			\wp_register_script(
				App::$slug . '-transformkit',
				CWPBS_PLUGIN_URL . 'dist/transformkit/transformkit.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_register_style(
				App::$slug . '-transformkit',
				CWPBS_PLUGIN_URL . 'dist/transformkit/transformkit.css',
				array(
					'wp-components',
				),
				$version
			);

			\wp_register_script(
				App::$slug . '-utils',
				CWPBS_PLUGIN_URL . 'dist/utils/utils.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_register_script(
				App::$slug . '-patterns-library',
				CWPBS_PLUGIN_URL . 'dist/patterns-library/patterns-library.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_localize_script(
				App::$slug . '-patterns-library',
				'blockSliderLibrary',
				array(
					'proxy' => rest_url( 'blockslider/v1/library-proxy' ),
				)
			);

			\wp_register_script(
				App::$slug . '-icons',
				CWPBS_PLUGIN_URL . 'dist/icons/icons.js',
				array(
					'lodash',
					'wp-api',
					'wp-i18n',
					'wp-components',
					'wp-element',
					'wp-editor',
				),
				$version,
				true
			);

			\wp_register_style(
				App::$slug . '-patterns-library',
				CWPBS_PLUGIN_URL . 'dist/patterns-library/patterns-library.css',
				array(
					'wp-components',
				),
				$version
			);

		}

	}
}
