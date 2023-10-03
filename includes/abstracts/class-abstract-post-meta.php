<?php
/**
 * Abstract class to work with post meta groups
 *
 * @class   Abstract_Post_Meta
 * @package Levenyatko\MultiplePostAuthors\Abstracts
 */

namespace Levenyatko\MultiplePostAuthors\Abstracts;

use Levenyatko\MultiplePostAuthors\Interfaces\Post_Meta_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Abstract_Post_Meta implements Post_Meta_Interface {

	/** @var string $meta_key Meta key to save and get post data. */
	public $meta_key;

	/** @var array $screens Post types to show Metabox. */
	protected $screens;

	/**
	 * Save post action for available screens.
	 *
	 * @return array
	 */
	public function get_save_actions() {

		$actions = [];

		if ( ! empty( $this->screens ) ) {
			foreach ( $this->screens as $screen ) {
				$actions[ "save_post_{$screen}" ] = [ 'save', 50 ];
			}
		}

		return $actions;
	}

	/**
	 * @param int $post_id Post ID to get Authors list.
	 *
	 * @return array
	 */
	public function get( $post_id ) {
		$meta_value = get_post_meta( $post_id, $this->meta_key, 1 );
		if ( empty( $meta_value ) ) {
			return [];
		}
		return maybe_unserialize( $meta_value );
	}

	/**
	 * @param int   $post_id Post ID to update Authors list.
	 * @param mixed $value New authors list.
	 *
	 * @return void
	 */
	public function update( $post_id, $value ) {
		update_post_meta( $post_id, $this->meta_key, $value );
	}

	/**
	 * @param int $post_id Saved post ID.
	 *
	 * @return void
	 */
	public function save( $post_id ) {
		// Must be implemented in child class.
	}
}
