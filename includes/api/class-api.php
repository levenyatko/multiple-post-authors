<?php
/**
 * Plugin API
 *
 * @class   Api
 * @package Levenyatko\MultiplePostAuthors\Api
 */

namespace Levenyatko\MultiplePostAuthors\Api;

use Levenyatko\MultiplePostAuthors\Api\Routes\Authors_Route;
use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;

class Api implements Actions_Interface {

	/** @var Api_Route_Interface[] $routes */
	private $routes = [];

	/**
	 * @return array
	 */
	public function get_actions() {
		return [
			'rest_api_init' => [ 'register_routes' ],
		];
	}

	/**
	 * @return void
	 */
	public function register_routes() {
		$routes['authors'] = new Authors_Route();
		$routes['authors']->register();
	}
}
