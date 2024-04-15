<?php
/**
 * Main file for link template.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Views\Templates;

/**
 * Main class for link template.
 */
class Link {

	/**
	 * Renders the template based on the given properties.
	 *
	 * @param string $href - Link href.
	 * @param string $label - Link label.
	 *
	 * @return void
	 */
	public static function render( $href, $label ) {
		?>
			<a href="<?php echo esc_url( $href ); ?>">
				<?php
					echo \esc_html( $label );
				?>
			</a>
		<?php
	}

}
