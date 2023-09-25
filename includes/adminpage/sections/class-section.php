<?php
/**
 * Section for admin settings page
 *
 * @class   Text_Field
 * @package Levenyatko\MultiplePostAuthors\Adminpage\Sections
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage\Sections;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Section {

	/** @var Field_Interface[] $fields Section field objects. */
	protected $fields = [];

	/** @var OptionsInterface $options */
	private $options;

	/** @var string $title Section title. */
	private $title;

	/** @var string $id Section ID. */
	private $id;

	/** @var string $page Slug-name of the settings page this section belongs to. */
	private $page;

	/** @var string $description Section description. */
	private $description;

	/** @var Field_Factory_Interface $field_factory */
	private $field_factory;

	/**
	 * Section constructor.
	 *
	 * @param string                  $id               Section ID.
	 * @param string                  $page             Slug-name of the settings page.
	 * @param OptionsInterface        $options_instance An instance of `OptionsInterface`.
	 * @param Field_Factory_Interface $field_factory    Factory to create field class.
	 * @param array                   $properties       Properties.
	 */
	public function __construct( $id, $page, $options_instance, $field_factory, $properties = [] ) {
		$properties = wp_parse_args(
			$properties,
			[
				'title'       => __( 'Section', 'multi-post-authors' ),
				'description' => '',
			]
		);

		$this->options       = $options_instance;
		$this->field_factory = $field_factory;

		$this->title       = $properties['title'];
		$this->description = $properties['description'];
		$this->page        = $page;
		$this->id          = $id;

		add_settings_section(
			$id,
			$this->title,
			[ $this, 'print_description' ],
			$page
		);
	}

	/**
	 * Print the section description.
	 *
	 * @return void
	 */
	public function print_description() {
		echo esc_html( $this->description );
	}

	/**
	 * Create and add a new field object to this section.
	 *
	 * @param string $field_type Field type name.
	 * @param array  $properties Field properties.
	 *
	 * @return void
	 */
	public function add_field( $field_type, $properties ) {
		if ( empty( $properties['name'] ) ) {
			return;
		}

		$field = $this->field_factory->make( $field_type, $this->id, $this->page, $properties );

		if ( $field ) {
			$this->fields[ $properties['name'] ] = $field;
		}
	}

	/**
	 * Sanitize the options' values.
	 *
	 * @param array $options Options to sanitize values.
	 *
	 * @return array
	 */
	public function sanitize( $options ) {

		$fields = $this->fields;

		foreach ( $options as $key => $value ) {
			if ( empty( $fields[ $key ] ) ) {
				continue;
			}
			$field           = $fields[ $key ];
			$sanitized_value = $field->sanitize( $value );
			$options[ $key ] = $sanitized_value;
		}

		return $options;
	}
}
