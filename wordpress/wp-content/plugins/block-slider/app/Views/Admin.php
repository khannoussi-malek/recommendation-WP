<?php
/**
 * Admin view file
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Views;

/**
 * Handles the rendering of admin page
 */
class Admin {


	/**
	 * Renders the view content
	 *
	 * This view will only render root element for react app
	 *
	 * @return void
	 */
	public static function render() {
		?>
			<div id="cwpbs-admin-root">
				<!-- React App should be rendered here -->
			</div>
		<?php
	}

}
