<?php
/**
 * Class adds custom meta box to edit Guest Author's data
 *
 * @class   Guest_Author_Metabox
 * @package Levenyatko\MultiplePostAuthors\Guest_Authors
 */

namespace Levenyatko\MultiplePostAuthors\Guest_Authors;

use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;
use Levenyatko\MultiplePostAuthors\Interfaces\Metabox_Interface;
use Levenyatko\MultiplePostAuthors\Utils;

class Guest_Author_Metabox implements Metabox_Interface, Actions_Interface {

	/** @var array $options Metabox display options. */
	private $options;

	/**
	 * @param array $options Class to get options value.
	 */
	public function __construct( $options ) {
		$this->options = $options;
	}

	/**
	 * @return array
	 */
	public function get_actions() {
		return [
			'add_meta_boxes' => [ 'add_metabox' ],
		];
	}

	/**
	 * @return void
	 */
	public function add_metabox() {
		$screens = $this->options['screens'];

		add_meta_box( 'guest_author_metabox', __( 'Author Data', 'multi-post-authors' ), [ $this, 'display' ], $screens, 'normal', 'core' );
	}


	/**
	 * @param WP_Post $post Current Post object.
	 * @param array   $meta Post meta array.
	 *
	 * @return void
	 */
	public function display( $post, $meta ) {
		global $mpa_plugin;
		$args = $mpa_plugin->meta['guest_author']->get( $post->ID );

		Utils::load_template( 'guest-author-meta.php', 'admin', $args );
	}
}
