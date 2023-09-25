<?php
/**
 * Text field for admin settings page fields
 *
 * @class   Text_Field
 * @package Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Text_Field extends Abstract_Field implements Field_Interface {

	/**
	 * Render the field.
	 *
	 * @param array $args Arguments specified while adding settings field.
	 * @return mixed|void
	 */
	public function render( $args ) {
		$fieldname = $this->get_field_name();
		printf( '<input type="text" name="%s" id="%s" value="%s"/>', esc_attr( $fieldname ), esc_attr( $fieldname ), esc_attr( $this->value ) );
		$this->show_description();
	}

	/**
	 * @param mixed $value Field value to sanitize.
	 * @return string
	 */
	public function sanitize( $value ) {
		return esc_html( $value );
	}
}
