<?php
/**
 * Class to get plugin options from the database
 *
 * @class   Plugin_Options
 * @package Levenyatko\MultiplePostAuthors
 */

namespace Levenyatko\MultiplePostAuthors;

use Levenyatko\MultiplePostAuthors\Interfaces\Options_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Plugin_Options implements Options_Interface {

	/** @var array $options Stored options. */
	private $options;

	/** @var array DEFAULTS Default options. */
	const DEFAULTS = [
		'general' => [
			'max_authors_count' => 3,
			'post_type'         => [
				'post' => '1',
			],
		],
	];

	/**
	 * Class constructor
	 */
	public function __construct() {
		$all_options = [];

		foreach ( self::DEFAULTS as $section_id => $section_default_options ) {
			$db_option_name  = Utils::get_section_id( $section_id );
			$section_options = get_option( $db_option_name );

			if ( false === $section_options ) {
				add_option( $db_option_name, $section_default_options );
				$section_options = $section_default_options;
			}

			$all_options = array_merge( $all_options, $section_options );
		}

		$this->options = $all_options;
	}

	/**
	 * Return the option value based on the given option name.
	 *
	 * @param string $name Option name.
	 *
	 * @return mixed
	 */
	public function get( $name ) {
		if ( ! isset( $this->options[ $name ] ) ) {
			return false;
		}

		return $this->options[ $name ];
	}

	/**
	 * Store the given value to an option with the given name.
	 *
	 * @param string $name       Option name.
	 * @param mixed  $value      Option value.
	 * @param string $section_id Section ID. Defaults to 'general'.
	 *
	 * @return bool              Whether the option was added.
	 */
	public function set( $name, $value, $section_id = 'general' ) {
		$db_option_name = Plugin::PREFIX . '_' . $section_id;
		$stored_option  = get_option( $db_option_name );

		$stored_option[ $name ] = $value;

		return update_option( $db_option_name, $stored_option );
	}

	/**
	 * Remove the option with the given name.
	 *
	 * @param string $name       Option name.
	 * @param string $section_id Section ID. Defaults to 'general'.
	 *
	 * @return bool              Whether the option was removed.
	 */
	public function remove( $name, $section_id = 'general' ) {
		$initial_value = [];

		if ( isset( self::DEFAULTS[ $section_id ][ $name ] ) ) {
			$initial_value = self::DEFAULTS[ $section_id ][ $name ];
		}

		return $this->set( $name, $initial_value, $section_id );
	}

	/**
	 * Return option keys.
	 *
	 * @return array
	 */
	public static function get_option_keys() {
		return array_keys( self::DEFAULTS );
	}
}
