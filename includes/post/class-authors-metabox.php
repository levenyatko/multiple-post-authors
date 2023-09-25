<?php
/**
 * Class adds custom meta box to edit new authors list field
 *
 * @class   Authors_Metabox
 * @package Levenyatko\MultiplePostAuthors\Post
 */

namespace Levenyatko\MultiplePostAuthors\Post;

use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;
use Levenyatko\MultiplePostAuthors\Post\Interfaces\Metabox_Interface;
use Levenyatko\MultiplePostAuthors\Plugin;
use Levenyatko\MultiplePostAuthors\Utils;

class Authors_Metabox implements Metabox_Interface, Actions_Interface {

	/** @var Options_Interface $options Class to get options value. */
	private $options;

	/**
	 * @param Options_Interface $options Class to get options value.
	 */
	public function __construct( $options ) {
		$this->options = $options;
	}

	/**
	 * @return array
	 */
	public function get_actions() {
		return [
			'add_meta_boxes'        => [ 'add_metabox' ],
			'admin_enqueue_scripts' => [ 'enqueue_scripts' ],
		];
	}

	/**
	 * @return void
	 */
	public function add_metabox() {
		$screens = $this->get_screens();

		add_meta_box( Plugin::PREFIX . '_metabox', __( 'Authors', 'multi-post-authors' ), [ $this, 'display' ], $screens, 'side', 'core' );
	}

	/**
	 * @return array
	 */
	protected function get_screens() {
		$screens = $this->options->get( 'post_type' );
		$screens = array_keys( $screens );
		$screens = apply_filters( 'mpa_metabox_screens_display', $screens );
		return $screens;
	}

	/**
	 * @param WP_Post $post Current Post object.
	 * @param array   $meta Post meta array.
	 *
	 * @return void
	 */
	public function display( $post, $meta ) {
		$authors = Utils::get_post_authors( $post );
		$args    = [
			'authors' => $authors,
		];
		Utils::load_template( 'metabox.php', 'admin', $args );
	}

	/**
	 * @param string $hook Hook name.
	 *
	 * @return void
	 */
	public function enqueue_scripts( $hook ) {
		global $post;

		if ( empty( $post ) ) {
			return;
		}

		$supported_hooks = [ 'edit.php', 'post-new.php', 'post.php' ];
		$screens         = $this->get_screens();

		if ( in_array( $hook, $supported_hooks, true ) ) {

			if ( ! in_array( $post->post_type, $screens, true ) ) {
				return;
			}

			$vars = [
				'maxAuthors' => $this->options->get( 'max_authors_count' ),
				'searchUrl'  => get_rest_url( null, '/mpa/v1/authors' ),
				'nonce'      => wp_create_nonce( 'wp_rest' ),
			];

			wp_enqueue_style( 'mpa-admin-styles', MPA_PLUGIN_URL . 'assets/admin/css/styles.css', [], '1.0' );

			wp_register_script( 'mpa-admin-script', MPA_PLUGIN_URL . 'assets/admin/js/authors-field.js', [ 'jquery', 'jquery-ui-core' ], '1.0', true );
			wp_localize_script( 'mpa-admin-script', 'mpaJsVars', $vars );
			wp_enqueue_script( 'mpa-admin-script' );
		}
	}
}
