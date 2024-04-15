<?php
/**
 * Bootstrap the application
 *
 * @author zafarKamal
 * @package BlockSlider
 */

use CakeWP\BlockSlider\Admin;
use CakeWP\BlockSlider\Assets;
use CakeWP\BlockSlider\Blocks;
use CakeWP\BlockSlider\Slider;
use CakeWP\BlockSlider\StylesManager;
use CakeWP\BlockSlider\Notice;
use CakeWP\BlockSlider\Shortcode;
use CakeWP\BlockSlider\Support;
use CakeWP\BlockSlider\Preview;
use CakeWP\BlockSlider\Library;
use CakeWP\BlockSlider\QuerySlider;
use CakeWP\BlockSlider\WooQuerySupport;
use CakeWP\BlockSlider\Animation;


if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

if ( ! defined( 'CWPBS_DIR_PATH' ) ) {
	define( 'CWPBS_DIR_PATH', \plugin_dir_path( __FILE__ ) );
}

if ( ! defined( 'CWPBS_PLUGIN_URL' ) ) {
	define( 'CWPBS_PLUGIN_URL', \plugins_url( '/', __FILE__ ) );
}

if ( is_readable( CWPBS_DIR_PATH . 'lib/autoload.php' ) ) {
	require CWPBS_DIR_PATH . 'lib/autoload.php';
}


new Assets();
new Admin();
new Slider();
new Blocks();
new Shortcode();
new Support();
new StylesManager();
new Notice();
new Preview();
new Library();
new QuerySlider();
new WooQuerySupport();
new Animation();
