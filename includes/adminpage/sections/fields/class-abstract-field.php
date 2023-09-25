<?php
/**
 * Abstract class for admin settings page fields
 *
 * @class   Abstract_Field
 * @package Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Abstract_Field {

	/** @var string $section_id */
	protected $section_id;

	/** @var string $option_name */
	public $option_name;

	/** @var string $description */
	protected $description;

	/** @var mixed $value Field value */
	protected $value;

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
	 * Field description display
	 *
	 * @return void
	 */
	protected function show_description() {
		if ( ! empty( $this->description ) ) {
			printf(
				'<p class="description">%s</p>',
				esc_html( $this->description )
			);
		}
	}

	/**
	 * Generate a name for the option in the section
	 *
	 * @return string
	 */
	public function get_field_name() {
		return sprintf( '%s[%s]', $this->section_id, $this->option_name );
	}
}
