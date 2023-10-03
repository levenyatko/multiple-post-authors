<?php
/**
 * Class to retrieve the authors meta field value
 *
 * @class   Post_Authors_Meta
 * @package Levenyatko\MultiplePostAuthors\Post
 */

namespace Levenyatko\MultiplePostAuthors\Post;

use Levenyatko\MultiplePostAuthors\Abstracts\Abstract_Post_Meta;
use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Post_Authors_Meta extends Abstract_Post_Meta implements Actions_Interface {

	/** @var string $meta_key Meta key to save and get post data. */
	public $meta_key = 'mpa_authors_list';

	/**
	 * @param array $screens Screens list where meta box is displayed.
	 */
	public function __construct( $screens ) {
		$this->screens = apply_filters( 'mpa_metabox_screens_display', $screens );
	}

	/**
	 * @return array
	 */
	public function get_actions() {

		$actions                        = $this->get_save_actions();
		$actions['wp_insert_post_data'] = [ 'add_default', 50, 2 ];

		return $actions;
	}

	/**
	 * @param int $post_id Saved post ID.
	 *
	 * @return void
	 */
	public function save( $post_id ) {

		if ( ! current_user_can( 'edit_post', $post_id ) || empty( $_POST['mpa-authors-nonce'] ) ) {
			return;
		}

		$nonce = sanitize_key( $_POST['mpa-authors-nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'mpa-authors-save' ) ) {
			return;
		}

		if ( isset( $_POST['mpa_authors'] ) && is_array( $_POST['mpa_authors'] ) ) {
			$value   = [];
			$authors = wp_unslash( $_POST['mpa_authors'] );
			foreach ( $authors as $val ) {
				$el = [
					'id'    => intval( $val ),
					'login' => '',
				];

				$user = get_user_by( 'ID', $el['id'] );
				if ( isset( $user->user_nicename ) ) {
					$el['login'] = $user->user_nicename;
				}

				$value[] = $el;
			}

			$this->update( $post_id, $value );
		}
	}

	/**
	 * Save first post author to the default WP field
	 *
	 * @param array $data An array of slashed, sanitized, and processed post data.
	 * @param array $postarr An array of sanitized (and slashed) but otherwise unmodified post data.
	 *
	 * @return mixed
	 */
	public function add_default( $data, $postarr ) {

		if ( empty( $_POST['post_type'] ) || ! in_array( $_POST['post_type'], $this->screens, true ) ) {
			return $data;
		}

		if ( isset( $postarr['mpa_authors'][0] ) ) {
			$data['post_author'] = intval( $postarr['mpa_authors'][0] );
		}

		return $data;
	}
}
