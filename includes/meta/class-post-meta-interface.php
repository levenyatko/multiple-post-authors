<?php
/**
 * Interface for a class that retrieves the value of a post meta field
 *
 * @class   Post_Meta_Interface
 * @package Levenyatko\MultiplePostAuthors\Meta
 */

namespace Levenyatko\MultiplePostAuthors\Meta;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Post_Meta_Interface {

	/**
	 * @param int $post_id Post ID to get Authors list.
	 *
	 * @return mixed
	 */
	public static function get( $post_id );

	/**
	 * @param int   $post_id Post ID to update Authors list.
	 * @param mixed $value New authors list.
	 *
	 * @return mixed
	 */
	public static function update( $post_id, $value );
}
