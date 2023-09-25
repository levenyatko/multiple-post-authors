<?php
/**
 * Interface for classes with actions should be registered in WordPress
 *
 * @class   Actions_Interface
 * @package Levenyatko\MultiplePostAuthors\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Actions_Interface {

	/**
	 * Return the actions to register.
	 *
	 * @return array
	 */
	public function get_actions();
}
