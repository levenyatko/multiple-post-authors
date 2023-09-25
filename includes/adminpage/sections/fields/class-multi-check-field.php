<?php
/**
 * Checkboxes field for admin settings page fields
 *
 * @class   Multi_Check_Field
 * @package Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage\Sections\Fields;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class Multi_Check_Field extends Abstract_Field implements Field_Interface {

	/** @var array $options Available options list  */
	private $options;

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
		$this->value       = $properties['value'] ?? [];
		$this->description = $properties['description'];

		if ( empty( $properties['options'] ) ) {
			$this->options = [];
		} else {
			$this->options = $properties['options'];
		}

		$field_args = [
			'class'     => $properties['class'] ?? 'field-' . $this->option_name,
			'label_for' => $this->get_field_name(),
			'section'   => $this->section_id,
		];

		add_settings_field(
			$this->option_name,
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
	 *
	 * @return mixed|void
	 */
	public function render( $args ) {
		if ( empty( $this->options ) ) {
			esc_html_e( 'No options to select', 'multi-post-authors' );
			return;
		}
		?>
			<fieldset>
			<?php
			foreach ( $this->options as $key => $label ) {
				$item_checked = isset( $this->value[ $key ] ) ? '1' : '0';
				$item_name    = $this->get_field_name() . '[' . $key . ']';
				?>
				<label for="<?php echo esc_attr( $item_name ); ?>">
					<input type="checkbox"
							class="checkbox"
							id="<?php echo esc_attr( $item_name ); ?>"
							name="<?php echo esc_attr( $item_name ); ?>"
							value="1"
							<?php checked( '1', $item_checked ); ?>
							/>
					<?php echo esc_html( $label ); ?>
				</label>
				<br>
				<?php
			}
			$this->show_description();
			?>
			</fieldset>
			<?php
	}

	/**
	 * @param array $values Field value to sanitize.
	 * @return string
	 */
	public function sanitize( $values ) {

		foreach ( $values as $post_type => $value ) {
			if ( ! isset( $this->options[ $post_type ] ) ) {
				unset( $values[ $post_type ] );
			}
		}

		return $values;
	}
}
