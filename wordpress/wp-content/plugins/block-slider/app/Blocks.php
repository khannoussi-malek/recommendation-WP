<?php
/**
 * Main file for blockslider blocks
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

/**
 * Main class for handling blockslider blocks
 */
class Blocks {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		$this->register();
	}

	/**
	 * All blockslider core blocks should be registered here
	 *
	 * @return void
	 */
	public function register() {
		// Base Blocks.
		new \CakeWP\BlockSlider\Blocks\BlockSlider();
		new \CakeWP\BlockSlider\Blocks\BlockSlide();
		new \CakeWP\BlockSlider\Blocks\SliderInserter();

		// Premium Blocks.
		if ( cwpbs_fs()->can_use_premium_code() ) {
			new \CakeWP\BlockSlider\Blocks\Pagination();
			new \CakeWP\BlockSlider\Blocks\Navigation();
			new \CakeWP\BlockSlider\Blocks\NavigationButton();
			new \CakeWP\BlockSlider\Blocks\SliderController();
			new \CakeWP\BlockSlider\Blocks\NoResult();
		}

	}
}
