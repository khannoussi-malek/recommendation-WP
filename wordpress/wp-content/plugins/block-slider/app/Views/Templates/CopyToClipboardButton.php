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
class CopyToClipboardButton {


	/**
	 * Renders the template based on the given properties.
	 *
	 * @param string $label - Button label.
	 * @param string $value - Value to copy.
	 *
	 * @return void
	 */
	public static function render( $label, $value ) {
		?>
		<div class="blockslider-clipboard-button-container">
			<button 
				class="blockslider-clipboard-button" 
				style="background: #ffffff !important; padding: 12px 24px !important; color: #000000 !important; cursor: pointer; border: none;"
				data-clipboardtext="<?php echo esc_html( wp_trim_words( $value ) ); ?>"
			>
				<?php echo esc_html( $label ); ?>
			</button>
		</div>
		<script>
			function blocksliderCopyText(text) {
				const temporaryInput = document.createElement('input');

				temporaryInput.value = text;
				temporaryInput.type  = "text";
				
				document.body.appendChild(temporaryInput);

				temporaryInput.select();

				document.execCommand("Copy");

				document.body.removeChild(temporaryInput);

			}

			window.addEventListener("load", () => {
				let clipboardButtonContainers = document.querySelectorAll(".blockslider-clipboard-button-container");

				clipboardButtonContainers.forEach((clipboardButtonContainer) => {
					let isClicked = false;
					let clipboardButton = clipboardButtonContainer.querySelector(".blockslider-clipboard-button");
					let clipboardText = clipboardButton.dataset.clipboardtext;
					let originalText = clipboardButton.innerHTML;

					clipboardButton.addEventListener("click", (click) => {
						if (!isClicked) {
							
							clipboardButton.innerHTML = "<?php echo esc_html_e( 'Copied', 'block-slider' ); ?>"
							
							blocksliderCopyText(clipboardText);
							
							isClicked = true
							
							setTimeout(() => {
								clipboardButton.innerHTML = originalText;
								isClicked = false;
							}, 1000);

						}
					})

				})
			})
		</script>
		<?php
	}
}
