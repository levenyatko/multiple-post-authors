<?php
/**
 * Interface for admin settings page fields
 *
 * @class   Field_Interface
 * @package Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Field_Interface {

	/**
	 * @param array $args Arguments specified while adding settings field.
	 *
	 * @return mixed
	 */
	public function render( $args );

	/**
	 * @param mixed $value Field value to sanitize.
	 *
	 * @return mixed
	 */
	public function sanitize( $value );
}
