<?php
/**
 * Interface for classes creates custom post types
 *
 * @class   CPT_Interface
 * @package Levenyatko\MultiplePostAuthors\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface CPT_Interface {

	/**
	 * @return void
	 */
	public function register_post_type();

	/**
	 * @return string
	 */
	public function get_type_slug();
}
