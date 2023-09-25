<?php
/**
 * Register actions and filters in WordPress
 *
 * @class   Hooks_Manager
 * @package Levenyatko\MultiplePostAuthors
 */

namespace Levenyatko\MultiplePostAuthors;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Hooks_Manager {

	/**
	 * Register an object.
	 *
	 * @param object $obj Class object which actions should be registered.
	 * @return void
	 */
	public function register( $obj ) {
		if ( $obj instanceof Interfaces\Actions_Interface ) {
			$this->register_actions( $obj );
		}

		if ( $obj instanceof Interfaces\Filters_Interface ) {
			$this->register_filters( $obj );
		}
	}

	/**
	 * Register the actions of the given object.
	 *
	 * @param object $obj Class object which actions should be registered.
	 * @return void
	 */
	private function register_actions( $obj ) {
		$actions = $obj->get_actions();

		foreach ( $actions as $action_name => $action_details ) {
			$method        = $action_details[0];
			$priority      = Utils::default_value( $action_details[1], 10 );
			$accepted_args = Utils::default_value( $action_details[2], 1 );

			add_action(
				$action_name,
				[ $obj, $method ],
				$priority,
				$accepted_args
			);
		}
	}

	/**
	 * Register the filters of the given object.
	 *
	 * @param object $obj Class object which filters should be registered.
	 * @return void
	 */
	private function register_filters( $obj ) {
		$filters = $obj->get_filters();

		foreach ( $filters as $filter_name => $filter_details ) {
			$method        = $filter_details[0];
			$priority      = Utils::default_value( $filter_details[1], 10 );
			$accepted_args = Utils::default_value( $filter_details[2], 1 );

			add_filter(
				$filter_name,
				[ $obj, $method ],
				$priority,
				$accepted_args
			);
		}
	}
}
