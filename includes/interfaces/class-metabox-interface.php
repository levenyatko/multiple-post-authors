<?php
/**
 * Interface for a class that adds metabox to the admin panel
 *
 * @class   Metabox_Interface
 * @package Levenyatko\MultiplePostAuthors\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Interfaces;

interface Metabox_Interface {

	/**
	 * @return void
	 */
	public function add_metabox();

	/**
	 * @param WP_Post $post Current Post object.
	 * @param array   $meta Post meta array.
	 *
	 * @return void
	 */
	public function display( $post, $meta );
}
