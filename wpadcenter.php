<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link    https://wpadcenter.com/
 * @since   1.0.0
 * @package Wpadcenter
 *
 * @wordpress-plugin
 * Plugin Name:       WPAdCenter
 * Plugin URI:        https://wpadcenter.com
 * Description:       Advertising management plugin for WordPress.
 * Version:           2.4.0
 * Author:            WPEka Club
 * Author URI:        https://club.wpeka.com/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wpadcenter
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! defined( 'WPADCENTER_PLUGIN_URL' ) ) {
	define( 'WPADCENTER_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
}

if ( ! defined( 'WPADCENTER_SCRIPT_SUFFIX' ) ) {
	define( 'WPADCENTER_SCRIPT_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min' );
}

if ( ! defined( 'WPADCENTER_PLUGIN_FILENAME' ) ) {
	define( 'WPADCENTER_PLUGIN_FILENAME', __FILE__ );
}

define( 'WPADCENTER_SETTINGS_FIELD', 'WPAdCenter-Settings' );

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPADCENTER_VERSION', '2.4.0' );

if ( ! defined( 'WPADCENTER_PLUGIN_FILENAME' ) ) {
	define( 'WPADCENTER_PLUGIN_FILENAME', __FILE__ );
}

if ( ! defined( 'WPADCENTER_PLUGIN_BASENAME' ) ) {
	define( 'WPADCENTER_PLUGIN_BASENAME', plugin_basename( WPADCENTER_PLUGIN_FILENAME ) );
}

if ( ! defined( 'WPADCENTER_SCRIPT_SUFFIX' ) ) {
	define( 'WPADCENTER_SCRIPT_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min' );
}



/**
 * The code that runs during plugin activation.
 *  * This action is documented in includes/class-wpadcenter-activator.php
 */
function activate_wpadcenter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpadcenter-activator.php';
	Wpadcenter_Activator::activate();
}


/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wpadcenter-deactivator.php
 */
function deactivate_wpadcenter() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wpadcenter-deactivator.php';
	Wpadcenter_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wpadcenter' );
register_deactivation_hook( __FILE__, 'deactivate_wpadcenter' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wpadcenter.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since 1.0.0
 */
function run_wpadcenter() {
	$plugin = new Wpadcenter();
	$plugin->run();

}
run_wpadcenter();

/**
 * Wpadcenter display single ad template.
 *
 * @param array $atts attributes/parameters for shortcode.
 *
 * @return void echos html to render on frontend
 */
function wpadcenter_display_ad( $atts ) {
	$shortcode = '[wpadcenter_ad id=' . $atts['id'] . ' align=' . $atts['align'] . ']';
	echo do_shortcode( $shortcode );
}

/**
 * Wpadcenter display ad group.
 *
 * @param array $atts attributes/parameters for shortcode.
 *
 * @return void echos html to render on frontend
 */
function wpadcenter_display_adgroup( $atts ) {
	$shortcode = '[wpadcenter_adgroup adgroup_ids=' . $atts['adgroup_ids'] . ' align=' . $atts['align'] . ' num_ads=' . $atts['num_ads'] . ' num_columns=' . $atts['num_columns'] . ']';
	echo do_shortcode( $shortcode );
}
