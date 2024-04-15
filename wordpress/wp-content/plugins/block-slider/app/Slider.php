<?php
/**
 * Main file for slider
 *
 * @package BlockSlider
 */

namespace CakeWP\BlockSlider;

use CakeWP\BlockSlider\Views\Templates\ClipboardField;

if ( ! defined( 'ABSPATH' ) ) {
	die( 'No direct access.' );
}

/**
 * Main slider class
 */
class Slider {

	/**
	 * Post Type Slug.
	 *
	 * @var string
	 */
	public static $post_type = 'blockslider';

	/**
	 * Constructor.
	 *
	 * @return void
	 */
	public function __construct() {

		\add_action( 'init', array( $this, 'register' ) );
		\add_action( 'admin_menu', array( $this, 'register_menu' ), 8 );
		\add_action( 'init', array( $this, 'add_shortcode_support' ) );

	}

	/**
	 * All menus should be registered here.
	 *
	 * @return void
	 */
	public function register_menu() {

		\add_menu_page(
			App::$name,
			\__( 'Block Slider', 'block-slider' ),
			'manage_options',
			'blockslider-dashboard',
			array( \CakeWP\BlockSlider\Views\Admin::class, 'render' ),
			App::get_icon(),
			9
		);

		/**
		 * Adding a quick admin shortcut to create a new slider.
		 */
		$add_new_slider_hook = \add_submenu_page(
			'blockslider-dashboard',
			\__( 'Add new slider', 'block-slider' ),
			\__( 'Add new', 'block-slider' ),
			'edit_posts',
			'blockslider-new',
			'__return_null',
			10
		);

		add_action(
			"load-{$add_new_slider_hook}",
			function() {
				\wp_safe_redirect(
					add_query_arg(
						array( 'post_type' => self::$post_type ),
						\admin_url( 'post-new.php' )
					)
				);
			}
		);
	}

	/**
	 * Method that adds shortcode support to slider CPT.
	 *
	 * @return void
	 */
	public function add_shortcode_support() {

		// Adding custom shortcode column in the CPT table.
		\add_filter(
			'manage_' . self::$post_type . '_posts_columns',
			function( $columns ) {

				$author_col = $columns['author'];
				$date_col   = $columns['date'];

				unset( $columns['author'] );
				unset( $columns['date'] );

				$columns['shortcode'] = __( 'Shortcode', 'block-slider' );

				$columns['author'] = $author_col;
				$columns['date']   = $date_col;

				return $columns;
			}
		);

		// Attaching a shortcode to each post type.
		\add_action(
			'manage_' . self::$post_type . '_posts_custom_column',
			function( $column, $post_id ) {

				if ( 'shortcode' === $column ) {
					$shortcode = '[blockslider id="' . $post_id . '"]';
					ClipboardField::render( $shortcode );
				}

			},
			10,
			2
		);

	}

	/**
	 * All actions should be registered here.
	 *
	 * @return void
	 */
	public function register() {

		$labels = array(
			'name'                  => _x( 'Sliders', 'Post type general name', 'block-slider' ),
			'singular_name'         => _x( 'Slider', 'Post type singular name', 'block-slider' ),
			'menu_name'             => _x( 'Sliders', 'Admin Menu text', 'block-slider' ),
			'name_admin_bar'        => _x( 'Slider', 'Add New on Toolbar', 'block-slider' ),
			'add_new'               => __( 'Add New', 'block-slider' ),
			'add_new_item'          => __( 'Add New Slider', 'block-slider' ),
			'new_item'              => __( 'New Slider', 'block-slider' ),
			'edit_item'             => __( 'Edit Slider', 'block-slider' ),
			'view_item'             => __( 'View Slider', 'block-slider' ),
			'all_items'             => __( 'All Sliders', 'block-slider' ),
			'search_items'          => __( 'Search Sliders', 'block-slider' ),
			'parent_item_colon'     => __( 'Parent Sliders:', 'block-slider' ),
			'not_found'             => __( 'No Sliders found.', 'block-slider' ),
			'not_found_in_trash'    => __( 'No Sliders found in Trash.', 'block-slider' ),
			'featured_image'        => _x( 'Slider Cover Image', 'Overrides the “Featured Image” phrase for this post type. Added in 4.3', 'block-slider' ),
			'archives'              => _x( 'Slider archives', 'The post type archive label used in nav menus. Default “Post Archives”. Added in 4.4', 'block-slider' ),
			'insert_into_item'      => _x( 'Insert into slider', 'Overrides the “Insert into post”/”Insert into page” phrase (used when inserting media into a post). Added in 4.4', 'block-slider' ),
			'uploaded_to_this_item' => _x( 'Uploaded to this slider', 'Overrides the “Uploaded to this post”/”Uploaded to this page” phrase (used when viewing media attached to a post). Added in 4.4', 'block-slider' ),
			'filter_items_list'     => _x( 'Filter sliders list', 'Screen reader text for the filter links heading on the post type listing screen. Default “Filter posts list”/”Filter pages list”. Added in 4.4', 'block-slider' ),
			'items_list_navigation' => _x( 'Sliders list navigation', 'Screen reader text for the pagination heading on the post type listing screen. Default “Posts list navigation”/”Pages list navigation”. Added in 4.4', 'block-slider' ),
			'items_list'            => _x( 'Sliders list', 'Screen reader text for the items list heading on the post type listing screen. Default “Posts list”/”Pages list”. Added in 4.4', 'block-slider' ),
		);

		$args = array(
			'labels'             => $labels,
			'description'        => 'Slider custom post type.',
			'public'             => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => 'blockslider-dashboard',
			'rewrite'            => array( 'slug' => 'block-slider' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => false,
			'menu_position'      => 20,
			'supports'           => array( 'title', 'editor', 'author', 'custom-fields' ),
			'show_in_rest'       => true,
			'template'           => array(
				array(
					'cakewp/block-slider',
					array(),
					array(),
				),
			),
		);

		/**
		 * Adding a default slider title.
		 */
		add_filter(
			'default_title',
			function( $post_title, \WP_Post $post ) {

				if ( 'blockslider' === $post->post_type ) {
					// translators: id of the slider.
					return sprintf( __( 'Slider %1$s', 'block-slider' ), $post->ID );
				}

				return $post_title;
			},
			10,
			2
		);

		/**
		 * Using custom page template for CPT.
		 */
		\add_filter(
			'single_template',
			function( $single ) {
				$post = isset( $GLOBALS['post'] ) ? $GLOBALS['post'] : null;

				if ( ! is_null( $post ) && self::$post_type === $post->post_type ) {
					return CWPBS_DIR_PATH . 'app/Views/PageTemplates/Single.php';
				}

				return $single;
			}
		);

		\register_setting(
			'general',
			'_blockslider_is_flashed_cpt',
			array(
				'default'      => false,
				'show_in_rest' => false,
				'type'         => 'boolean',
			)
		);

		\register_post_type( self::$post_type, $args );

		\register_post_meta(
			self::$post_type,
			'blockslider_fallback_content',
			array(
				'default'      => '',
				'show_in_rest' => true,
				'single'       => true,
				'type'         => 'string',
			)
		);

		// Flushing rewrite rules if we haven't already to address the issue where
		// Custom Post Type redirects to 404 page.
		if ( false === get_option( '_blockslider_is_flashed_cpt' ) ) {
			// This is an expensive operation, so making sure that it only runs once.
			flush_rewrite_rules( false );
			update_option( '_blockslider_is_flashed_cpt', true );
		}
	}

}
