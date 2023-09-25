<?php
/**
 * Class for plugin settings page
 *
 * @class   Settings_Page
 * @package Levenyatko\MultiplePostAuthors\Adminpage
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage;

use Levenyatko\MultiplePostAuthors\Utils;

class Settings_Page extends Abstract_Admin_Page {

	/**
	 * @return string
	 */
	protected function get_menu_title() {
		return __( 'Multiple Authors', 'multi-post-authors' );
	}

	/**
	 * @return string
	 */
	protected function get_page_title() {
		return __( 'Multiple post authors settings', 'multi-post-authors' );
	}

	/**
	 * Get current menu item slug.
	 *
	 * @return string
	 */
	protected function get_slug() {
		return 'post-authors-settings';
	}

	/**
	 * Register page sections
	 *
	 * @return void
	 */
	public function register_sections() {
		$this->register_general_options();
	}

	/**
	 * Register settings section with general settings
	 *
	 * @return void
	 */
	private function register_general_options() {

		$section_id = Utils::get_section_id( 'general' );

		$general_section = $this->register_section(
			$section_id,
			[ 'title' => __( 'General', 'multi-post-authors' ) ]
		);

		register_setting(
			$this->get_slug(),
			$section_id,
			[
				'sanitize_callback' => [ $general_section, 'sanitize' ],
			]
		);

		$cpt_support_author = get_post_types_by_support( 'author' );

		$support_post_types = get_post_types( [ 'public' => true ], 'objects' );
		$post_type_options  = [];
		foreach ( $support_post_types as $post_type ) {
			if ( in_array( $post_type->name, $cpt_support_author, true ) ) {
				$post_type_options[ $post_type->name ] = $post_type->label;
			}
		}

		$general_section->add_field(
			'multicheck',
			[
				'label'       => __( 'Enable for these post types', 'multi-post-authors' ),
				'name'        => 'post_type',
				'value'       => $this->options->get( 'post_type' ),
				'options'     => $post_type_options,
				'description' => __( 'Select the post types you want the plugin to be enabled for.', 'multi-post-authors' ),
			]
		);

		$general_section->add_field(
			'number',
			[
				'label'       => __( 'Maximum authors count for post', 'multi-post-authors' ),
				'name'        => 'max_authors_count',
				'value'       => $this->options->get( 'max_authors_count' ),
				'min'         => 0,
				'description' => __( 'Specify 0 if you do not want to add a restriction', 'multi-post-authors' ),
			]
		);
	}
}
