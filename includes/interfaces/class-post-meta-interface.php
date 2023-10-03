<?php
/**
 * Interface for a class that retrieves the value of a post meta field
 *
 * @class   Post_Meta_Interface
 * @package Levenyatko\MultiplePostAuthors\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Post_Meta_Interface {

	/**
	 * @param int $post_id Post ID to get Authors list.
	 *
	 * @return mixed
	 */
	public function get( $post_id );

	/**
	 * @param int   $post_id Post ID to update meta value.
	 * @param mixed $value New meta value.
	 *
	 * @return void
	 */
	public function update( $post_id, $value );

	/**
	 * @param int $post_id Post ID to update meta value.
	 *
	 * @return void
	 */
	public function save( $post_id );
}
