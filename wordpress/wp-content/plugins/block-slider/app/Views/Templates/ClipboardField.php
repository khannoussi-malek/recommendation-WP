<?php
/**
 * Main file for clipboard field template.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider\Views\Templates;

/**
 * Main class for clipboard template.
 */
class ClipboardField {

	/**
	 * Renders the template based on the given properties.
	 *
	 * @param string $text - Text that will be copied.
	 *
	 * @return void
	 */
	public static function render( $text ) {
		// TODO: Currently, the clipboard renders as a code style, make this conditional?
		?>
			<input 
				style="font-family: monospace;background-color: #eee; width: 100%; background: transparent; border: none; height: 30px; padding: 0px 4px;" 
				onfocus="this.select();" 
				value="<?php echo \esc_html( $text ); ?>" 
				readonly
				class="blockslider-clipboard-field"
			>
		<?php
	}

}
