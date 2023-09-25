<?php
/**
 * Factory class to create settings page field instance
 *
 * @class   Field_Factory
 * @package Levenyatko\MultiplePostAuthors
 */

namespace Levenyatko\MultiplePostAuthors;

use Levenyatko\MultiplePostAuthors\Interfaces\Field_Factory_Interface;

class Field_Factory implements Field_Factory_Interface {

	/**
	 * @param string $type       Field type name string.
	 * @param string $section_id Section id.
	 * @param string $page       Settings page id.
	 * @param array  $parameters Additional parameters array.
	 *
	 * @return Field_Interface|null
	 */
	public function make( $type, $section_id, $page, $parameters ) {

		$function_name = 'make_' . $type;
		if ( method_exists( $this, $function_name ) ) {
			$args = [
				$section_id,
				$page,
				$parameters,
			];
			return call_user_func_array( [ $this, $function_name ], $args );
		}

		return null;
	}

	/**
	 * @param string $section_id Section id.
	 * @param string $page Settings page id.
	 * @param array  $parameters additional options array.
	 *
	 * @return Adminpage\Sections\Fields\Text_Field
	 */
	private function make_text( $section_id, $page, $parameters ) {
		return new Adminpage\Sections\Fields\Text_Field( $section_id, $page, $parameters );
	}

	/**
	 * @param string $section_id Section id.
	 * @param string $page Settings page id.
	 * @param array  $parameters additional options array.
	 *
	 * @return Adminpage\Sections\Fields\Multi_Check_Field
	 */
	private function make_multicheck( $section_id, $page, $parameters ) {
		return new Adminpage\Sections\Fields\Multi_Check_Field( $section_id, $page, $parameters );
	}

	/**
	 * @param string $section_id Section id.
	 * @param string $page Settings page id.
	 * @param array  $parameters additional options array.
	 *
	 * @return Adminpage\Sections\Fields\Number_Field
	 */
	private function make_number( $section_id, $page, $parameters ) {
		return new Adminpage\Sections\Fields\Number_Field( $section_id, $page, $parameters );
	}
}
