<?php
/**
 * Shortcode
 *
 * @package BlockSlider
 * @author zafarKamal
 */

namespace CakeWP\BlockSlider;

use \CakeWP\BlockSlider\Blocks\Navigation;
use \CakeWP\BlockSlider\Blocks\Pagination;

/**
 * Main class to handle shortcodes for block slider.
 */
class Shortcode {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		\add_shortcode( 'blockslider', array( $this, 'render' ) );
		\add_action( 'wp_enqueue_scripts', array( $this, 'prepare_shortcode_assets' ), 1 );
	}

	/**
	 * Prepares/Loads necessary slider assets, only if shortcode is used on the particular post.
	 *
	 * @return void
	 */
	public function prepare_shortcode_assets() {

		if ( is_admin() ) {
			return;
		}

		$post_id = get_the_ID();
		$post    = get_post( $post_id );

		if ( is_a( $post, 'WP_Post' ) && \has_shortcode( $post->post_content, 'blockslider' ) ) {

			// Enqueueing all the necessary assets.
			\CakeWP\BlockSlider\Blocks\BlockSlider::load_all_frontend_assets();

		}

	}

	/**
	 * Prints the slider based on the given shortcode args.
	 * This handler will render an embeddable frame if the shortcode is used in
	 * supported page builders, Otherwise the slider will be rendered.
	 *
	 * @param string[] $attrs - Shortcode Attributes.
	 *
	 * @return string Slider
	 */
	public function render( $attrs ) {

		if ( ! $this->can_render_slider( $attrs ) ) {
			return __( 'No slider found!', 'block-slider' );
		}

		// Should render a frame if any page builder is active.
		$_should_render_frame = \CakeWP\BlockSlider\Support::is_any_page_builder_active() || \wp_doing_ajax();

		return $_should_render_frame ? $this->render_slider_frame( $attrs ) : $this->render_slider( $attrs );
	}

	/**
	 * Prints/Embeds slider within an iframe.
	 *
	 * @param string[] $attrs - Shortcode attributes.
	 */
	private function render_slider_frame( $attrs ) {

		$preview_link = \get_preview_post_link( $attrs['id'] );
		$_frame_src   = \add_query_arg(
			array(
				'hide-blockslider-toolbar' => true,
				'blockslider-preview'      => true,
			),
			$preview_link
		);

		return '<iframe class="blockslider-frame" frameborder="0" width="100%" scrolling="no" onload="this.height=(this.contentWindow.document.documentElement.scrollHeight+20) + `px`;" src="' . \esc_url( $_frame_src ) . '"></iframe>';
	}

	/**
	 * Checks if the slider can be rendered via shortcode.
	 *
	 * @param string[] $attrs - Slider shortcode attributes.
	 *
	 * @return bool True if can be rendered, otherwise false.
	 */
	private function can_render_slider( $attrs ) {

		$slider_id = isset( $attrs['id'] ) ? $attrs['id'] : null;
		$post      = \get_post( $slider_id );

		if ( is_null( $slider_id ) || is_null( $post ) ) {
			return false;
		}

		return true;
	}


	/**
	 * Renders out the slider based on the given shortcode args.
	 *
	 * @param string[] $attrs - Shortcode Attributes.
	 *
	 * @return string Slider
	 */
	private function render_slider( $attrs ) {

		$post = \get_post( $attrs['id'] );

		if ( has_block( Navigation::$slug, $post ) ) {
			Navigation::load_all_frontend_assets();
		}

		if ( has_block( Pagination::$slug, $post ) ) {
			Pagination::load_all_frontend_assets();
		}

		$filter_blockslider_post = static function() use ( $post ) {
			return $post;
		};

		add_filter(
			'blockslider_post',
			$filter_blockslider_post
		);

		$content = \do_blocks( \apply_filters( 'the_content', $post->post_content ) );

		remove_filter(
			'blockslider_post',
			$filter_blockslider_post
		);

		return $content;
	}

}
