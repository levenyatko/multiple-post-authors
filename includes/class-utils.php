<?php
/**
 * Class for common functions
 *
 * @class   Utils
 * @package Levenyatko\MultiplePostAuthors
 */

namespace Levenyatko\MultiplePostAuthors;

use Levenyatko\MultiplePostAuthors\Meta\Post_Authors;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Utils {

	/**
	 * Return the given value if it's set, otherwise return the default one.
	 *
	 * @param mixed $value Value to get.
	 * @param mixed $default_value Default value if $value wasn't set.
	 *
	 * @return mixed
	 */
	public static function default_value( &$value, $default_value ) {
		if ( isset( $value ) ) {
			return $value;
		}

		if ( isset( $default_value ) ) {
			return $default_value;
		}

		return null;
	}

	/**
	 * @param string $section_id Section ID.
	 *
	 * @return string
	 */
	public static function get_section_id( $section_id ) {
		return sprintf( '%s_%s', Plugin::PREFIX, $section_id );
	}

	/**
	 * @param string $template Template file name.
	 * @param string $directory Directory name.
	 * @param array  $args Arguments to pass in file.
	 * @return void
	 */
	public static function load_template( $template, $directory, $args ) {
		$template_path = MPA_PLUGIN_DIR . 'templates/' . $directory . '/' . $template;
		load_template( $template_path, 1, $args );
	}

	/**
	 * @param int|Post $post Post to get authors list.
	 * @param array    $fields Fields to retrieve.
	 *
	 * @return mixed|null
	 */
	public static function get_post_authors( $post, $fields = [ 'ID', 'display_name', 'user_login' ] ) {
		if ( is_int( $post ) ) {
			$post = get_post( $post );
		}

		if ( empty( $fields ) ) {
			$fields = 'all';
		}

		$post_authors = [];

		if ( $post ) {

			$authors_list = Post_Authors::get( $post->ID );

			if ( $authors_list ) {
				$authors_include = [];

				foreach ( $authors_list as $item ) {
					$authors_include[] = $item['id'];
				}

				$post_authors = get_users(
					[
						'include' => $authors_include,
						'fields'  => $fields,
					]
				);

				usort(
					$post_authors,
					function ( $a, $b ) use( $authors_include ) {
						$q = array_flip( $authors_include );
						return $q[ $a->ID ] - $q[ $b->ID ];
					}
				);

			} else {
				$post_authors[] = get_user_by( 'id', $post->post_author );
			}
		}

		return apply_filters( 'mpa_post_authors_list', $post_authors );
	}
}
