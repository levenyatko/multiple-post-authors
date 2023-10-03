<?php
/**
 * Class to retrieve the Guest author's meta
 *
 * @class   Guest_Author_Meta
 * @package Levenyatko\MultiplePostAuthors\Guest_Authors
 */

namespace Levenyatko\MultiplePostAuthors\Guest_Authors;

use Levenyatko\MultiplePostAuthors\Abstracts\Abstract_Post_Meta;
use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Guest_Author_Meta extends Abstract_Post_Meta implements Actions_Interface {

	/** @var string $meta_key Meta key to save and get post data. */
	public $meta_key = 'mpa_author_data';

	/**
	 * @param array $screens Screens list where meta box is displayed.
	 */
	public function __construct( $screens ) {
		$this->screens = $screens;
	}

	/**
	 * Return the actions to register.
	 *
	 * @return array
	 */
	public function get_actions() {
		$actions                        = $this->get_save_actions();
		$actions['wp_insert_post_data'] = [ 'make_title', 99 ];
		return $actions;
	}

	/**
	 * @param int $post_id Post ID to save Meta value.
	 *
	 * @return void
	 */
	public function save( $post_id ) {

		if ( ! current_user_can( 'edit_post', $post_id ) || empty( $_POST['mpa-guestauthor-nonce'] ) ) {
			return;
		}

		$nonce = sanitize_key( $_POST['mpa-guestauthor-nonce'] );
		if ( ! wp_verify_nonce( $nonce, 'mpa-guestauthor-save' ) ) {
			return;
		}

		$form_data = $_POST;

		$post_meta = [
			'first-name' => '',
			'last-name'  => '',
		];

		if ( ! empty( $form_data['first-name'] ) ) {
			$post_meta['first-name'] = sanitize_text_field( $form_data['first-name'] );
		}

		if ( ! empty( $form_data['last-name'] ) ) {
			$post_meta['last-name'] = sanitize_text_field( $form_data['last-name'] );
		}

		$post_meta['display-name'] = $this->make_display_name( array_merge( $form_data, $form_data ) );
		$post_meta['display-name'] = sanitize_text_field( $post_meta['display-name'] );

		if ( ! empty( $form_data['email'] ) ) {
			$post_meta['email'] = sanitize_email( $form_data['email'] );
		}

		if ( ! empty( $form_data['biography'] ) ) {
			$post_meta['biography'] = sanitize_textarea_field( $form_data['biography'] );
		}

		$this->update( $post_id, $post_meta );
	}

	/**
	 * @param array $data Data to generate Display name.
	 *
	 * @return mixed
	 */
	private function make_display_name( $data ) {
		if ( ! empty( $data['display-name'] ) ) {
			$display_name = $data['display-name'];
		} else {
			$names = [
				$data['first-name'] ?? '',
				$data['last-name'] ?? '',
			];

			$display_name = implode( ' ', $names );
		}
		return $display_name;
	}

	/**
	 * @param array $data New Post data.
	 *
	 * @return mixed
	 */
	public function make_title( $data ) {
		if ( in_array( $data['post_type'], $this->screens, true ) ) {
			$new_title          = sanitize_text_field( $this->make_display_name( $_POST ) );
			$data['post_title'] = $new_title;
		}
		return $data;
	}
}
