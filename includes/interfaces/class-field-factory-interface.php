<?php
/**
 * Interface for factory class created admin page fields
 *
 * @class   Field_Factory_Interface
 * @package Levenyatko\MultiplePostAuthors\Interfaces
 */

namespace Levenyatko\MultiplePostAuthors\Interfaces;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

interface Field_Factory_Interface {

	/**
	 * @param string $type Field type name string.
	 * @param string $section_id Section id.
	 * @param string $page  Settings page id.
	 * @param array  $parameters Additional parameters array.
	 *
	 * @return Field_Interface|null
	 */
	public function make( $type, $section_id, $page, $parameters );
}
