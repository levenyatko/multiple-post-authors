<?php
/**
 * Class adds custom columns to posts list.
 *
 * @class   Guest_Author_Columns
 * @package Levenyatko\MultiplePostAuthors\Guest_Authors
 */

namespace Levenyatko\MultiplePostAuthors\Guest_Authors;

use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;
use Levenyatko\MultiplePostAuthors\Post\Interfaces\Table_Column_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Guest_Author_Columns implements Table_Column_Interface, Actions_Interface {

	/** @var array $screens Post types to show Metabox. */
	private $screens;

	/**
	 * @param array $screens Screens to add column.
	 */
	public function __construct( $screens ) {
		$this->screens = $screens;
	}

	/**
	 * @return array
	 */
	public function get_actions() {

		$actions = [];

		$actions['manage_posts_columns']       = [ 'add', 50, 2 ];
		$actions['manage_posts_custom_column'] = [ 'display', 30, 2 ];

		return $actions;
	}

	/**
	 * @param array  $columns Columns array.
	 * @param string $post_type Post Type.
	 *
	 * @return array
	 */
	public function add( $columns, $post_type ) {
		if ( ! in_array( $post_type, $this->screens, true ) ) {
			return $columns;
		}

		$columns['title'] = __( 'Display Name', 'multi-post-authors' );

		$date_col = $columns['date'];
		unset( $columns['date'] );

		$new_columns = [
			'guest_firstname' => __( 'First Name', 'multi-post-authors' ),
			'guest_email'     => __( 'Email', 'multi-post-authors' ),
			'date'            => $date_col,
		];

		return array_merge( $columns, $new_columns );
	}

	/**
	 * @param string $column_name Displayed column name.
	 * @param int    $post_id     Current Post id.
	 *
	 * @return void
	 */
	public function display( $column_name, $post_id ) {
		global $mpa_plugin;

		if ( 'guest_firstname' === $column_name ) {

			$val = $mpa_plugin->meta['guest_author']->get( $post_id );

			if ( empty( $val['first-name'] ) ) {
				$html = '-';
			} else {
				$html = $val['first-name'];
			}
			echo wp_kses_post( $html );

		} elseif ( 'guest_email' === $column_name ) {

			$val = $mpa_plugin->meta['guest_author']->get( $post_id );

			if ( empty( $val['email'] ) ) {
				$html = '-';
			} else {
				$html = $val['email'];
			}

			echo wp_kses_post( $html );
		}
	}
}
