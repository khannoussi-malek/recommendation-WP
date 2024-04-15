<?php
/**
 * Library.
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

use WP_REST_Response;

/**
 * Proxying the Slider Library.
 */
class Library {

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {
		add_action( 'rest_api_init', array( $this, 'setup_proxy' ) );
	}

	/**
	 * Sets up the rest proxy route to avoid CORS issues.
	 *
	 * @return void
	 */
	public function setup_proxy() {
		register_rest_route(
			'blockslider/v1',
			'/library-proxy',
			array(
				'methods'             => 'POST',
				'permission_callback' => '__return_true',
				'callback'            => array( $this, 'proxy_handler' ),
			)
		);
	}

	/**
	 * Handles the proxied request.
	 *
	 * @param WP_REST_Request $request - Request.
	 * @return WP_REST_Response - Response.
	 */
	public function proxy_handler( $request ) {

		if ( false === $request->has_param( 'path' ) ) {
			return new WP_REST_Response(
				array( 'error' => 'Path is not provided.' ),
				400
			);
		}

		$path = $request->get_param( 'path' );
		$url  = wp_parse_url( $path );

		// Check 1: Checking if the url is matching the origin.
		if ( 'wpblockslider.com' !== $url['host'] ) {
			return new WP_REST_Response(
				array( 'error' => 'Unknown Host' ),
				400
			);
		}

		$response = wp_remote_get(
			$path
		);

		if ( is_wp_error( $response ) ) {
			return new WP_REST_Response(
				array( 'error' => 'API Error' ),
				500
			);
		}

		$response_body = wp_remote_retrieve_body( $response );
		$response_body = json_decode( $response_body );

		return new WP_REST_Response(
			$response_body,
			200,
			array(
				'link'            => wp_remote_retrieve_header( $response, 'link' ),
				'X-WP-Total'      => wp_remote_retrieve_header( $response, 'X-WP-Total' ),
				'X-WP-TotalPages' => wp_remote_retrieve_header( $response, 'X-WP-TotalPages' ),
			)
		);
	}
}

