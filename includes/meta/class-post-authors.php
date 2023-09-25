<?php
/**
 * Class to retrieve the authors meta field value
 *
 * @class   Post_Authors
 * @package Levenyatko\MultiplePostAuthors\Meta
 */

namespace Levenyatko\MultiplePostAuthors\Meta;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Authors implements Post_Meta_Interface {

	/** @var string AUTHORS_LIST meta key to store authors list */
	const AUTHORS_LIST = 'mpa_authors_list';

	/**
	 * @param int $post_id Post ID to get Authors list.
	 *
	 * @return array
	 */
	public static function get( $post_id ) {
		$authors_list = get_post_meta( $post_id, self::AUTHORS_LIST, 1 );
		if ( empty( $authors_list ) ) {
			return [];
		}
		return maybe_unserialize( $authors_list );
	}

	/**
	 * @param int   $post_id Post ID to update Authors list.
	 * @param mixed $value New authors list.
	 *
	 * @return void
	 */
	public static function update( $post_id, $value ) {
		update_post_meta( $post_id, self::AUTHORS_LIST, $value );
	}
}
