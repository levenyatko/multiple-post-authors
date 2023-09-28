<?php
/**
 * Main plugin class
 *
 * @class   Plugin
 * @package Levenyatko\MultiplePostAuthors
 */

namespace Levenyatko\MultiplePostAuthors;

use Levenyatko\MultiplePostAuthors\Post\Authors_Column;
use Levenyatko\MultiplePostAuthors\Post\Authors_Metabox;
use Levenyatko\MultiplePostAuthors\Post\Post_Meta;
use Levenyatko\MultiplePostAuthors\Api\Api;
use Levenyatko\MultiplePostAuthors\Adminpage\Settings_Page;
use Levenyatko\MultiplePostAuthors\Query\Authors_Query;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

final class Plugin {

	/** @var string PREFIX plugin settings prefix  */
	const PREFIX = 'mpauthors';

	/** @var Options $options An instance of the `Options` class. */
	public $options;

	/** @var Hooks_Manager $hooks_manager An instance of the `Hooks_Manager` class. */
	public $hooks_manager;

	/** @var array $post_types Supported post types array */
	public $post_types;

	/**
	 * Class construct
	 */
	public function __construct() {
		$this->require_files();

		add_action( 'plugins_loaded', [ $this, 'init' ] );
	}

	/**
	 * Require autoloader file and register it
	 *
	 * @return void
	 */
	private function require_files() {
		require_once __DIR__ . '/class-autoloader.php';

		$autoloader = new Autoloader();
		$autoloader->register();
	}

	/**
	 * Initialize classes required for the plugin.
	 *
	 * @return void
	 */
	public function init() {
		$this->options       = new Plugin_Options();
		$this->hooks_manager = new Hooks_Manager();

		$screens          = $this->options->get( 'post_type' );
		$this->post_types = array_keys( $screens );

		$rest_api = new Api();
		$this->hooks_manager->register( $rest_api );

		if ( is_admin() ) {
			$this->admin_init();
		}

		$authors_query = new Authors_Query();
		$this->hooks_manager->register( $authors_query );
	}

	/**
	 * @return void
	 */
	private function admin_init() {
		$fields_factory = new Field_Factory();

		$settings_page = new Settings_Page( $this->options, $fields_factory );
		$this->hooks_manager->register( $settings_page );

		$authors_column = new Authors_Column( $this->post_types );
		$this->hooks_manager->register( $authors_column );

		$authors_metabox = new Authors_Metabox( $this->options );
		$this->hooks_manager->register( $authors_metabox );

		$post_meta = new Post_Meta( $this->post_types );
		$this->hooks_manager->register( $post_meta );
	}
}
