<?php
/**
 * Class adds custom column with authors list to the admin panel posts list
 *
 * @class   Authors_Column
 * @package Levenyatko\MultiplePostAuthors\Post
 */

namespace Levenyatko\MultiplePostAuthors\Post;

use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;
use Levenyatko\MultiplePostAuthors\Post\Interfaces\Table_Column_Interface;
use Levenyatko\MultiplePostAuthors\Utils;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Authors_Column implements Table_Column_Interface, Actions_Interface {

	/** @var string $column_name New authors column name. */
	private $column_name = 'mpa_authors';

	/** @var array $screens Post types to show Metabox. */
	private $screens;

	/**
	 * @param array $screens Screens to add column.
	 */
	public function __construct( $screens ) {
		$this->screens = apply_filters( 'mpa_column_screens_display', $screens );
	}

	/**
	 * @return array
	 */
	public function get_actions() {

		$actions = [];

		if ( ! empty( $this->screens ) ) {
			foreach ( $this->screens as $screen ) {
				$actions[ "manage_{$screen}_posts_columns" ]       = [ 'add', 50 ];
				$actions[ "manage_{$screen}_posts_custom_column" ] = [ 'display', 30, 2 ];
			}
		}

		$actions['quick_edit_custom_box'] = [ 'quickedit_fields', 10, 2 ];

		return $actions;
	}

	/**
	 * @param array $columns Columns array.
	 *
	 * @return array
	 */
	public function add( $columns ) {
		$new_columns = [];

		foreach ( $columns as $key => $value ) {
			if ( 'author' === $key ) {
				$key   = $this->column_name;
				$value = __( 'Authors', 'multi-post-authors' );
			}

			$new_columns[ $key ] = $value;
		}

		return $new_columns;
	}

	/**
	 * @param string $column_name Displayed column name.
	 * @param int    $post_id     Current Post id.
	 *
	 * @return void
	 */
	public function display( $column_name, $post_id ) {

		if ( $this->column_name === $column_name ) {

			$post_type = get_post_type( $post_id );
			$authors   = Utils::get_post_authors( $post_id );

			$authors_str = [];

			if ( $authors ) {
				foreach ( $authors as $author ) {

					if ( is_object( $author ) ) {

						$args = [
							'author_name' => $author->user_login,
						];

						if ( 'post' !== $post_type ) {
							$args['post_type'] = $post_type;
						}

						$url           = add_query_arg( array_map( 'rawurlencode', $args ), admin_url( 'edit.php' ) );
						$authors_str[] = sprintf(
							'<a href="%s" data-value="%d" class="author_name">%s</a>',
							esc_url( $url ),
							$author->ID,
							esc_html( $author->display_name )
						);

					}
				}
			}

			if ( empty( $authors_str ) ) {
				$authors_str[] = '<span aria-hidden="true">—</span><span class="screen-reader-text">' . __( 'No author', 'multi-post-authors' ) . '</span>';
			}

			$html = implode( '<br>', $authors_str );

			echo wp_kses_post( $html );
		}
	}

	/**
	 * @param string $column_name Current column name.
	 * @param string $post_type Current post type.
	 *
	 * @return void
	 */
	public function quickedit_fields( $column_name, $post_type ) {
		if ( ! in_array( $post_type, $this->screens, true ) ) {
			return;
		}

		if ( $column_name === $this->column_name ) {

			$args = [
				'authors' => [],
			];

			?>
			<fieldset class="inline-edit-col-left mpa-quickedit-field-wrap">
                <span class="inline-edit-categories-label">
                    <?php esc_html_e( 'Authors', 'multi-post-authors' ); ?>
                </span>
                <?php Utils::load_template( 'metabox.php', 'admin', $args ); ?>
			</fieldset>
			<?php
		}
	}
}
