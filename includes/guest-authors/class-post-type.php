<?php
/**
 *
 * @class Post_Type
 * @package Levenyatko\MultiplePostAuthors\Guest_Authors
 */

namespace Levenyatko\MultiplePostAuthors\Guest_Authors;

use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;
use Levenyatko\MultiplePostAuthors\Interfaces\CPT_Interface;

/**
 * Class Post_Type
 */
class Post_Type implements CPT_Interface, Actions_Interface {

	/**
	 * Return the actions to register.
	 *
	 * @return array
	 */
	public function get_actions() {
		return [
			'init' => [ 'register_post_type' ],
		];
	}

	/**
	 * @return void
	 */
	public function register_post_type() {
		$labels = [
			'name'                  => _x( 'Guest Authors', 'post type general name', 'multi-post-authors' ),
			'singular_name'         => _x( 'Guest Author', 'post type singular name', 'multi-post-authors' ),
			'menu_name'             => __( 'Authors', 'multi-post-authors' ),
			'name_admin_bar'        => __( 'Guest Author', 'multi-post-authors' ),
			'all_items'             => __( 'All Guest Authors', 'multi-post-authors' ),
			'add_new'               => _x( 'Add New', 'mpa-author', 'multi-post-authors' ),
			'add_new_item'          => __( 'Add New Guest Author', 'multi-post-authors' ),
			'edit_item'             => __( 'Edit Guest Author', 'multi-post-authors' ),
			'new_item'              => __( 'New Guest Author', 'multi-post-authors' ),
			'view_item'             => __( 'View Guest Author', 'multi-post-authors' ),
			'search_items'          => __( 'Search Guest Authors', 'multi-post-authors' ),
			'not_found'             => __( 'No Guest Authors Found', 'multi-post-authors' ),
			'not_found_in_trash'    => __( 'No Guest Authors Found in the Trash', 'multi-post-authors' ),
			'parent_item_colon'     => '',
			'featured_image'        => _x( 'Profile Image', 'mpa-author', 'multi-post-authors' ),
			'set_featured_image'    => _x( 'Set Profile Image', 'mpa-author', 'multi-post-authors' ),
			'remove_featured_image' => _x( 'Remove Profile Image', 'mpa-author', 'multi-post-authors' ),
			'use_featured_image'    => _x( 'Use as Profile Image', 'mpa-author', 'multi-post-authors' ),
		];

		$args = [
			'labels'              => $labels,
			'description'         => __( 'Guest author custom post type', 'multi-post-authors' ),
			'public'              => true,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'show_in_nav_menus'   => false,
			'menu_position'       => null,
			'menu_icon'           => 'dashicons-groups',
			'supports'            => [ 'thumbnail' ],
			'has_archive'         => false,
			'capability_type'     => [ 'mpa_author', 'mpa_authors' ], // only admin user can work wit the post type.
		];

		register_post_type( $this->get_type_slug(), $args );

		$this->set_capabilities();
	}

	/**
	 * Get current post type slug.
	 *
	 * @return string
	 */
	public function get_type_slug() {
		return 'mpa-author';
	}

	/**
	 * Add custom capabilities to admin user.
	 *
	 * @return void
	 */
	public function set_capabilities() {

		$role = get_role( 'administrator' );

		$caps = [
			[ 'mpa_author', 'mpa_authors' ],
		];

		foreach ( $caps as $cap ) {

			$singular = $cap[0];
			$plural   = $cap[1];

			$role->add_cap( "edit_{$singular}" );
			$role->add_cap( "edit_{$plural}" );
			$role->add_cap( "edit_others_{$plural}" );
			$role->add_cap( "publish_{$plural}" );
			$role->add_cap( "read_{$singular}" );
			$role->add_cap( "read_private_{$plural}" );
			$role->add_cap( "delete_{$singular}" );
			$role->add_cap( "delete_{$plural}" );
			$role->add_cap( "delete_private_{$plural}" );
			$role->add_cap( "delete_others_{$plural}" );
			$role->add_cap( "edit_published_{$plural}" );
			$role->add_cap( "edit_private_{$plural}" );
			$role->add_cap( "delete_published_{$plural}" );

		}
	}
}
