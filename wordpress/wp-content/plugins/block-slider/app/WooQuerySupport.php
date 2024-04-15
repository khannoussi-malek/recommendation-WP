<?php
/**
 * WOO Query Support
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * Main class to handle woo commerce query for query slider
 */
class WooQuerySupport {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_filter( 'blockslider_query_args', array( $this, 'maybe_apply_woo_query' ), 10, 2 );
	}

	/**
	 * Applies Woo Commerce Query if needed.
	 *
	 * @param array $args - Query Args.
	 * @param array $query - Query Attribute.
	 *
	 * @return array - Maybe Modified query args.
	 */
	public function maybe_apply_woo_query( $args, $query ) {

		// Check 1: Check if woo is active.
		$woo_active = class_exists( 'woocommerce' );

		if ( false === $woo_active ) {
			return $args;
		}

		// Check 2: Checking if woo post type is selected.
		$is_woo_post_type = isset( $query['postType'] ) && 'product' === $query['postType'];

		if ( ! $is_woo_post_type ) {
			return $args;
		}

		$woo_query = isset( $query['woocommerce'] ) ? $query['woocommerce'] : array();

		if ( isset( $woo_query['onSale'] ) && true === $woo_query['onSale'] && function_exists( 'wc_get_product_ids_on_sale' ) ) {
			$args['post__in'] = array_merge( $args['post__in'], wc_get_product_ids_on_sale() );
		}

		if ( isset( $woo_query['stockStatus'] ) && count( $woo_query['stockStatus'] ) > 0 ) {

			$stock_status_query = array_map(
				function( $status ) {
					return array(
						'key'     => '_stock_status',
						'value'   => $status,
						'compare' => '=',
					);
				},
				$woo_query['stockStatus']
			);

			$args['meta_query'][] = array(
				'relation' => 'OR',
				$stock_status_query,
			);
		}

		return $args;
	}
}
