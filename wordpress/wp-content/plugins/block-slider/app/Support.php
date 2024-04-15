<?php
/**
 * Support
 *
 * @package BlockSlider
 * @author zafarKamal
 */

namespace CakeWP\BlockSlider;

use CakeWP\BlockSlider\Support\PageBuilder\Divi;
use CakeWP\BlockSlider\Support\PageBuilder\Beaver;
use CakeWP\BlockSlider\Support\PageBuilder\Elementor;
use CakeWP\BlockSlider\Support\PageBuilder\VisualComposer;

/**
 * Handles support for various plugins and page builders
 */
class Support {

	/**
	 * Currently active page builder.
	 *
	 * @var string
	 */
	public static $active_page_builder = '';

	/**
	 * Supported page builders with block slider.
	 *
	 * @var string[]
	 */
	public static $supported_page_builders = array(
		'gutenberg'       => 'Gutenberg',
		'classic-editor'  => 'Classic Editor',
		'elementor'       => 'Elementor',
		'divi'            => 'Divi',
		'beaver-builder'  => 'Beaver Builder',
		'visual-composer' => 'Visual Composer',
		'wpbakery'        => 'WPBakery Page Builder',
	);


	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->add_support_for_page_builders();
		self::$active_page_builder = self::get_active_page_builder();
	}

	/**
	 * Checks if a page builder is active.
	 *
	 * @return bool True, if active. otherwise false.
	 */
	public static function is_any_page_builder_active() {
		return '' !== self::$active_page_builder;
	}

	/**
	 * Checks if any page builder is active.
	 *
	 * @return bool
	 */
	private static function get_active_page_builder() {

		if ( Elementor::is_active() ) {
			return 'elementor';
		}

		if ( Divi::is_active() ) {
			return 'divi';
		}

		if ( VisualComposer::is_active() ) {
			return 'visual-composer';
		}

		if ( Beaver::is_active() ) {
			return 'beaver';
		}

		return '';
	}

	/**
	 * Initializes support for page builders.
	 *
	 * @return void
	 */
	public function add_support_for_page_builders() {
		new \CakeWP\BlockSlider\Support\PageBuilder\Divi();
		new \CakeWP\BlockSlider\Support\PageBuilder\Beaver();
		new \CakeWP\BlockSlider\Support\PageBuilder\Elementor();
		new \CakeWP\BlockSlider\Support\PageBuilder\VisualComposer();
	}
}
