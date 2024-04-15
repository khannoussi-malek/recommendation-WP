<?php
/**
 * Query Slider
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * Main class to handle query slider
 */
class QuerySlider {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'render_block_cakewp/block-slide', array( $this, 'maybe_apply_query' ), 10, 3 );
		add_filter( 'render_block_cakewp/block-slider', array( $this, 'maybe_render_fallback' ), 10, 3 );
		add_filter( 'render_block_cakewp/no-result', array( $this, 'prepare_fallback' ), 10, 3 );
	}

	/**
	 * Prepares the fallback rendering.
	 *
	 * @param string    $block_content - Block Content.
	 * @param string    $parsed_block - Parsed block entity.
	 * @param \WP_Block $block - Block Instance.
	 *
	 * @return string
	 */
	public function prepare_fallback( $block_content, $parsed_block, $block ) {
		$is_fallback = isset( $block->context['isBlocksliderPreview'] ) ? $block->context['isBlocksliderPreview'] : false;

		return $is_fallback ? $block_content : '';
	}

	/**
	 * Builds Query Arguments from attributes query.
	 *
	 * @param array $query - Query attribute.
	 *
	 * @return array - Query Arguments.
	 */
	public function build_query_vars( $query ) {

		$args = array(
			'post_type'      => 'post',
			'posts_per_page' => 10,
			'post__not_in'   => array(),
			'post__in'       => array(),
			'meta_query'     => array(),
		);

		if ( isset( $query['postType'] ) ) {
			$args['post_type'] = $query['postType'];
		}

		if ( isset( $query['orderBy'] ) ) {
			list($orderby, $order) = explode( '/', $query['orderBy'] );
			$args['order']         = $order;
			$args['orderby']       = $orderby;
		}

		if ( isset( $query['maxItems'] ) ) {
			$args['posts_per_page'] = $query['maxItems'];
		}

		$sticky     = get_option( 'sticky_posts' );
		$sticky_arg = isset( $query['sticky'] ) ? $query['sticky'] : 'include';

		if ( 'only' === $sticky_arg ) {
			$args['post__in']            = ! empty( $sticky ) ? $sticky : array( 0 );
			$args['ignore_sticky_posts'] = 1;
		} elseif ( 'exclude' === $sticky_arg ) {
			$args['post__not_in'] = array_merge( $args['post__not_in'], $sticky );
		}

		$tax_query = isset( $query['taxQuery'] ) ? $query['taxQuery'] : array();

		$args['tax_query'] = array();
		foreach ( $tax_query as $taxonomy => $terms ) {
			if ( is_taxonomy_viewable( $taxonomy ) && ! empty( $terms ) ) {
				$args['tax_query'][] = array(
					'taxonomy'         => $taxonomy,
					'terms'            => array_filter( array_map( 'intval', $terms ) ),
					'include_children' => false,
				);
			}
		}

		$authors = isset( $query['author'] ) ? $query['author'] : -1;

		if (
			'' !== $authors
		) {
			$args['author'] = $authors;
		}

		$search = isset( $query['search'] ) ? $query['search'] : '';

		if (
			'' !== $search
		) {
			$args['s'] = $search;
		}

		return apply_filters( 'blockslider_query_args', $args, $query );
	}

	/**
	 * Finds the fallback block from the blockslider post.
	 *
	 * @param \WP_Post $post - Blockslider Post.
	 *
	 * @return string - Fallback content.
	 */
	private function get_fallback( $post ) {
		$blocks   = parse_blocks( $post->post_content );
		$fallback = '';

		foreach ( $blocks as $block ) {
			if ( isset( $block['blockName'] ) && 'cakewp/no-result' === $block['blockName'] ) {
				$fallback = ( new \WP_Block( $block ) )->render();
			}
		}

		return $fallback;
	}

	/**
	 * All notices should be registered here.
	 *
	 * @param string    $block_content - Block Content.
	 * @param string    $parsed_block - Parsed block entity.
	 * @param \WP_Block $block - Block Instance.
	 *
	 * @return string
	 */
	public function maybe_apply_query( $block_content, $parsed_block, $block ) {

		$is_query_slider = isset( $block->context['block-slider/is-query-slider'] ) ? $block->context['block-slider/is-query-slider'] : false;

		if ( false === $is_query_slider ) {
			return $block_content;
		}

		$query_attribute            = isset( $block->context['block-slider/query'] ) ? $block->context['block-slider/query'] : array();
		$is_inheriting_global_query = isset( $query_attribute['inherit'] ) ? $query_attribute['inherit'] : false;

		if ( $is_inheriting_global_query ) {
			global $wp_query;
			$query = clone $wp_query;
			$query->rewind_posts();
		} else {
			$query_args = $this->build_query_vars( $query_attribute );
			$query      = new \WP_Query( $query_args );
		}

		$content = '';

		while ( $query->have_posts() ) {
			$query->the_post();

			$block_instance = $block->parsed_block;

			$post_id   = get_the_ID();
			$post_type = get_post_type();

			$filter_block_context = static function( $context ) use ( $post_id, $post_type ) {
				$context['postType'] = $post_type;
				$context['postId']   = $post_id;
				return $context;
			};

			// Use an early priority to so that other 'render_block_context' filters have access to the values.
			add_filter( 'render_block_context', $filter_block_context, 1 );
			// Render the inner blocks of the Post Template block with `dynamic` set to `false` to prevent calling
			// `render_callback` and ensure that no wrapper markup is included.
			$new_block_content = ( new \WP_Block( $block_instance ) )->render( array( 'dynamic' => false ) );
			remove_filter( 'render_block_context', $filter_block_context, 1 );

			$content .= $new_block_content;
		}

		wp_reset_postdata();

		return $content;
	}

	/**
	 * Renders the fallback if the query responds with empty/no posts.
	 *
	 * @param string    $block_content - Block Content.
	 * @param string    $parsed_block - Parsed block entity.
	 * @param \WP_Block $block - Block Instance.
	 *
	 * @return string
	 */
	public function maybe_render_fallback( $block_content, $parsed_block, $block ) {
		$has_slides = stripos( $block_content, 'blockslider-slide-inner-wrapper' );

		if ( ! $has_slides ) {
			$blockslider_post = apply_filters( 'blockslider_post', null );

			if ( is_null( $blockslider_post ) ) {
				return '';
			}

			$fallback = get_post_meta( $blockslider_post->ID, 'blockslider_fallback_content', true );

			$parsed_fallback = parse_blocks( $fallback );

			if ( is_array( $parsed_fallback ) && count( $parsed_fallback ) > 0 ) {
				$rendered_fallback = ( new \WP_Block(
					$parsed_fallback[0],
					array(
						'isBlocksliderPreview' => true,
					)
				) )->render();
				return $rendered_fallback;
			}

			return '';
		}

		return $block_content;

	}
}
