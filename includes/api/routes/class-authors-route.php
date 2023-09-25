<?php
/**
 * Route to search users
 *
 * @class   Authors_Route
 * @package Levenyatko\MultiplePostAuthors\Api\Routes
 */

namespace Levenyatko\MultiplePostAuthors\Api\Routes;

class Authors_Route implements Api_Route_Interface {

	/**
	 * @return void
	 */
	public function register() {
		register_rest_route(
			'mpa/v1',
			'authors',
			[
				'methods'             => 'GET',
				'callback'            => [ $this, 'get' ],
				'permission_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
				'args'                => [
					'search'  => [
						'description'       => 'Search string',
						'required'          => true,
						'type'              => 'string',
						'sanitize_callback' => 'sanitize_text_field',
					],
					'ignored' => [
						'description' => 'Excluded from search authors',
						'required'    => false,
					],
				],
			]
		);
	}

	/**
	 * @param \WP_REST_Request $request Rest request object.
	 *
	 * @return void
	 */
	public function get( \WP_REST_Request $request ) {

		$params = $request->get_params();

		$args = [
			'orderby'        => 'display_name',
			'order'          => 'ASC',
			'search_columns' => [ 'login', 'nicename', 'email' ],
			'number'         => 4, // show only first 4 results.
			'fields'         => [ 'ID', 'display_name' ],
		];

		if ( isset( $params['ignored'] ) && is_array( $params['ignored'] ) ) {
			$args['exclude'] = $params['ignored'];
		}

		$args['search'] = $params['search'] . '*';

		$args = apply_filters( 'mpa_users_search_args', $args );

		$users = get_users( $args );

		wp_send_json( [ 'data' => $users ] );
	}
}
