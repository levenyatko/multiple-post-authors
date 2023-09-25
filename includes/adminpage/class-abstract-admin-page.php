<?php
/**
 * Abstract class for admin settings page
 *
 * @class   Abstract_Admin_Page
 * @package Levenyatko\MultiplePostAuthors\Adminpage
 */

namespace Levenyatko\MultiplePostAuthors\Adminpage;

use Levenyatko\MultiplePostAuthors\Interfaces\Actions_Interface;
use Levenyatko\MultiplePostAuthors\Adminpage\Sections\Section;
use Levenyatko\MultiplePostAuthors\Adminpage\Sections\Settings_Section;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

abstract class Abstract_Admin_Page implements Actions_Interface {

	/**  @var array $sections Contains all page sections */
	private $sections = [];

	/** @var Options_Interface $options Class to get options value */
	protected $options;

	/** @var Field_Factory_Interface $field_factory Factory class to create fields */
	protected $field_factory;

	/**
	 * @param Options_Interface       $options Class to get options value.
	 * @param Field_Factory_Interface $field_factory Factory to create fields.
	 */
	public function __construct( $options, $field_factory ) {
		$this->options       = $options;
		$this->field_factory = $field_factory;
	}

	/**
	 * Return the actions to register.
	 *
	 * @return array
	 */
	public function get_actions() {
		return [
			'admin_menu'            => [ 'add_page' ],
			'admin_init'            => [ 'register_sections' ],
			'admin_enqueue_scripts' => [ 'enqueue_stylesheets' ],
		];
	}

	/**
	 * Render this admin page.
	 *
	 * @return void
	 */
	public function render() {
		?>

			<div class="wrap">
				<form action="options.php" method="post">
					<h1><?php echo esc_html( $this->get_page_title() ); ?></h1>
				<?php
					settings_fields( $this->get_slug() );
					do_settings_sections( $this->get_slug() );
					submit_button();
				?>
				</form>
			</div>

			<?php
	}

	/**
	 * Enqueue stylesheets for all admin pages.
	 *
	 * @param string $hook_suffix The current admin page.
	 *
	 * @return void
	 */
	public function enqueue_stylesheets( $hook_suffix ) {
		if ( $this->get_slug() !== $hook_suffix ) {
			return;
		}

		wp_enqueue_style(
			'admin_page',
			PLUGIN_URL . 'assets/css/admin-page.css',
			[],
			VERSION
		);
	}

	/**
	 * Add this page as a top-level menu page.
	 *
	 * @return void
	 */
	public function add_page() {
		add_menu_page(
			$this->get_page_title(),
			$this->get_menu_title(),
			$this->get_capability(),
			$this->get_slug(),
			[ $this, 'render' ],
			$this->get_icon_url(),
			$this->get_position()
		);
	}

	/**
	 * Return the menu title.
	 *
	 * @return string
	 */
	abstract protected function get_menu_title();

	/**
	 * Return the page title.
	 *
	 * @return string
	 */
	abstract protected function get_page_title();

	/**
	 * Return the capability required for this menu to be displayed to the user.
	 *
	 * @return string
	 */
	protected function get_capability() {
		return 'manage_options';
	}

	/**
	 * Return page slug.
	 *
	 * @return string
	 */
	abstract protected function get_slug();

	/**
	 * Return the URL to the icon to be used for this menu.
	 *
	 * @return string
	 */
	protected function get_icon_url() {
		return '';
	}

	/**
	 * Return the position in the menu order this item should appear.
	 *
	 * @return int|null
	 */
	protected function get_position() {
		return null;
	}

	/**
	 * Register sections.
	 *
	 * Used to add new sections to an admin page.
	 *
	 * @return void
	 */
	abstract public function register_sections();

	/**
	 * Create and register a new section object.
	 *
	 * @param string $section_id Section ID.
	 * @param array  $properties Section properties.
	 *
	 * @return Section
	 */
	protected function register_section( $section_id, $properties = [] ) {
		$section          = new Section( $section_id, $this->get_slug(), $this->options, $this->field_factory, $properties );
		$this->sections[] = $section;

		return $section;
	}
}
