<?php
/**
 * Number field for admin settings page fields
 *
 * @class   Text_Field
 * @package Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Number_Field extends Abstract_Field implements Field_Interface {

	private $min_val;
	private $max_val;

	/**
	 * @param string $section_id Section id.
	 * @param string $page  Settings page id.
	 * @param array  $properties additional options array.
	 */
	public function __construct( $section_id, $page, $properties = [] ) {
		$properties = wp_parse_args(
			$properties,
			[
				'label'       => __( 'Field', 'multi-post-authors' ),
				'description' => '',
			]
		);

		$this->section_id  = $section_id;
		$this->option_name = $properties['name'];
		$this->value       = $properties['value'] ?? '';
		$this->description = $properties['description'];

		$field_args = [
			'class'     => $properties['class'] ?? 'field-' . $this->option_name,
			'label_for' => $this->get_field_name(),
			'section'   => $this->section_id,
		];

		if ( isset( $properties['min'] ) ) {
			$this->min_val     = $properties['min'];
			$field_args['min'] = $properties['min'];
		} else {
			$this->min_val = null;
		}

		if ( isset( $properties['max'] ) ) {
			$this->max_val     = $properties['max'];
			$field_args['max'] = $properties['max'];
		} else {
			$this->max_val = null;
		}

		add_settings_field(
			$this->get_field_name(),
			$properties['label'],
			[ $this, 'render' ],
			$page,
			$section_id,
			$field_args
		);
	}

	/**
	 * Render the field.
	 *
	 * @param array $args Arguments specified while adding settings field.
	 * @return mixed|void
	 */
	public function render( $args ) {
		$fieldname = $this->get_field_name();

		$attrs = '';
		if ( isset( $args['min'] ) ) {
			$attrs = 'min="' . $args['min'] . '"';
		}
		if ( isset( $args['max'] ) ) {
			$attrs = 'max="' . $args['max'] . '"';
		}

		$input = sprintf( '<input type="number" name="%s" id="%s" %s value="%s"/>', esc_attr( $fieldname ), esc_attr( $fieldname ), $attrs, esc_attr( $this->value ) );
		echo wp_kses(
			$input,
			[
				'input' => [
					'type'  => [],
					'name'  => [],
					'value' => [],
					'id'    => [],
				],
			]
		);
		$this->show_description();
	}

	/**
	 * @param mixed $value Field value to sanitize.
	 * @return string
	 */
	public function sanitize( $value ) {
		return intval( $value );
	}
}
