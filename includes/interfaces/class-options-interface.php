<?php
/**
 * Interface for class retrieved options
 *
 * @class   Options_Interface
 * @package Levenyatko\MultiplePostAuthors\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Options_Interface {

	/**
	 * Return the option value based on the given option name.
	 *
	 * @param string $name Option name.
	 * @return mixed
	 */
	public function get( $name );

	/**
	 * Store the given value to an option with the given name.
	 *
	 * @param string $name       Option name.
	 * @param mixed  $value      Option value.
	 * @param string $section_id Section ID.
	 * @return bool              Whether the option was added.
	 */
	public function set( $name, $value, $section_id );

	/**
	 * Remove the option with the given name.
	 *
	 * @param string $name       Option name.
	 * @param string $section_id Section ID.
	 */
	public function remove( $name, $section_id );
}
