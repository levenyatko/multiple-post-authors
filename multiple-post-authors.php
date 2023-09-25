<?php
/**
 * Plugin Name:       Multiple Post Authors
 * Description:       Multiple authors Plugin for WordPress. Easy to use and modify. Add co-authors, multiple authors and guest authors to your WordPress post types.
 * Text Domain:       multi-post-authors
 * Requires PHP:      7.4.0
 * Requires at least: 5.2
 * Tested up to:      6.3.1
 * Author:            Daria Levchenko
 * Author URI:        https://github.com/levenyatko
 * License:           GPL v3 or later
 * License URI:       http://www.gnu.org/licenses/gpl-3.0.txt
 * Version:           0.0.1
 *
 * @package     Levenyatko\MultiplePostAuthors
 * @author      Levenyatko
 * @license     GPL-3.0+
 *
 * @wordpress-plugin
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! defined( 'MPA_PLUGIN_URL' ) ) {
	define( 'MPA_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'MPA_PLUGIN_DIR' ) ) {
	define( 'MPA_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
}

require_once __DIR__ . '/includes/class-plugin.php';

new Levenyatko\MultiplePostAuthors\Plugin();
