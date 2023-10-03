<?php
/**
 * Query modificator to add search by custom field
 *
 * @class Authors_Query
 * @package Levenyatko\MultiplePostAuthors\Query
 */

namespace Levenyatko\MultiplePostAuthors\Query;

use Levenyatko\MultiplePostAuthors\Interfaces\Filters_Interface;
use Levenyatko\MultiplePostAuthors\Post\Post_Authors_Meta;

class Authors_Query implements Filters_Interface {

	/**
	 * Return the filters to register.
	 *
	 * @return array
	 */
	public function get_filters() {
		return [
			'posts_where'   => [ 'where', 10, 2 ],
			'posts_join'    => [ 'join', 10, 2 ],
			'posts_groupby' => [ 'groupby', 10, 2 ],
		];
	}

	/**
	 * @param string    $where The WHERE clause of the query.
	 * @param \WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function where( $where, $query ) {
		global $wpdb, $mpa_plugin;

		$fields = self::get_data_keys( $query );

		if ( ! $fields || ! isset( $query->query_vars[ $fields['author_key'] ] ) || ! $query->query_vars[ $fields['author_name_key'] ] ) {
			return $where;
		}

		$authors_metakey = $mpa_plugin->meta['authors']->meta_key;

		$author_id   = (int) $query->get( $fields['author_key'] );
		$author_name = $wpdb->esc_like( $query->get( $fields['author_name_key'] ) );

		if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
			$replace_from = ") OR ({$wpdb->posts}.post_content";
			$replace_to   = ") OR ({$wpdb->posts}.post_author = $author_id) OR (meta_key = '{$authors_metakey}' and meta_value LIKE '%{$author_name}%' " . $replace_from;

			$where = str_replace( $replace_from, $replace_to, $where );
		} else {
			$replace_from = '.post_author = ' . $author_id . ')';
			$replace_to   = ".post_author = $author_id OR (meta_key = '{$authors_metakey}' and meta_value LIKE '%{$author_name}%') )";

			$where = str_replace( $replace_from, $replace_to, $where );

			if ( ! $query->is_main_query() ) {
				$where = str_replace( " AND {$wpdb->posts}.post_author IN ($author_id)", '', $where );
			}
		}

		return $where;
	}

	/**
	 * @param string    $join The JOIN clause of the query.
	 * @param \WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function join( $join, $query ) {
		global $wpdb;

		$fields = self::get_data_keys( $query );

		if ( ! $fields
			|| ! isset( $query->query_vars[ $fields['author_key'] ] )
			|| ! $query->query_vars[ $fields['author_name_key'] ]
			) {
			return $join;
		}

		$author_id = (int) $query->get( $fields['author_key'] );

		if ( empty( $author_id ) ) {
			return $join;
		}

		if ( false === strpos( $join, "INNER JOIN {$wpdb->postmeta} ON" ) ) {
			$join .= " INNER JOIN {$wpdb->postmeta} ON ({$wpdb->posts}.ID = {$wpdb->postmeta}.post_id)";
		}

		return $join;
	}

	/**
	 * @param string    $groupby The GROUP BY clause of the query.
	 * @param \WP_Query $query The WP_Query instance (passed by reference).
	 *
	 * @return string
	 */
	public function groupby( $groupby, $query ) {
		global $wpdb;

		$fields = self::get_data_keys( $query );

		if ( ! $fields
			|| ! isset( $query->query_vars[ $fields['author_key'] ] )
			|| ! $query->query_vars[ $fields['author_name_key'] ]
			) {
			return $groupby;
		}

		$author_id = (int) $query->get( $fields['author_key'] );

		if ( empty( $author_id ) ) {
			return $groupby;
		}

		return $wpdb->posts . '.ID';
	}

	/**
	 * @param \WP_Query $query The WP_Query instance.
	 *
	 * @return array
	 */
	private static function get_data_keys( $query ) {
		if ( $query->is_main_query() && $query->is_search() && ! is_admin() ) {
			$keys = [
				'author_key'      => 'mpa_author',
				'author_name_key' => 'mpa_author_name',
			];
		} else {
			$keys = [
				'author_key'      => 'author',
				'author_name_key' => 'author_name',
			];
		}

		return $keys;
	}
}
