<?php
/**
 * Interface for Admin post table column Class
 *
 * @class   Table_Column_Interface
 * @package Levenyatko\MultiplePostAuthors\Post\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Post\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Table_Column_Interface {

	/**
	 * Add column to the Posts table.
	 *
	 * @param array $columns Columns array.
	 * @return array
	 */
	public function add( $columns );

	/**
	 * Display column data.
	 *
	 * @param string $column_name Displayed column name.
	 * @param int    $post_id Current Post id.
	 *
	 * @return void
	 */
	public function display( $column_name, $post_id );
}
