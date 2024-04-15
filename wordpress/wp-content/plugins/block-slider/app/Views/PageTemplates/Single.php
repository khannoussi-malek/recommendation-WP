<?php
/**
 * Template Name: Blockslider Single Page
 * Template Post Type: blockslider
 *
 * @package BlockSlider
 * @author zafarKamal
 * @since 1.0.0
 */

use \CakeWP\BlockSlider\Views\PreviewToolbar;

$current_post_id = get_the_ID();
$current_post    = get_post( $current_post_id );

add_filter(
	'blockslider_post',
	function() use ( $current_post ) {
		return $current_post;
	}
);

$post_content = apply_filters( 'the_content', $current_post->post_content );

?>
<?php wp_head(); ?>

<style id="blockslider-page-template-inline-style">
	.block-slider {
		margin-top: 0px;
		margin-bottom: 0px;
	}

	html {
		margin-top: <?php echo is_admin_bar_showing() ? 'var( --wp-admin--admin-bar--height, 32px )' : '0px'; ?> !important;
	}
</style>

<?php PreviewToolbar::render(); ?>

<div class="entry-content is-layout-constrained">
	<?php echo $post_content; ?>
</div>
<?php wp_footer(); ?>
