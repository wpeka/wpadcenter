<?php
/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://wpadcenter.com/
 * @since             1.0.0
 * @package           Wpadcenter
 *
 * @wordpress-plugin
 * Plugin Name:       WPAdCenter
 * Plugin URI:        https://wpadcenter.com
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
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

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WPADCENTER_VERSION', '1.0.0' );

if ( ! function_exists( 'adc_fs' ) ) {
	/**
	 * Helper function to access SDK.
	 *
	 * @return Analytics
	 */
	function adc_fs() {
		global $adc_fs;

		if ( ! isset( $adc_fs ) ) {
			// Include Analytics SDK.
			require_once dirname( __FILE__ ) . '/analytics/start.php';

			$adc_fs = ras_dynamic_init(
				array(
					'id'              => '10',
					'slug'            => 'wpadcenter',
					'product_name'    => 'WPAdCenter',
					'module_type'     => 'plugin',
					'version'         => WPADCENTER_VERSION,
					'plugin_basename' => 'wpadcenter/wpadcenter.php',
					'plugin_url'      => WPADCENTER_PLUGIN_URL,
				)
			);
		}

		return $adc_fs;
	}

	// Init Analytics.
	adc_fs();
	// SDK initiated.
	do_action( 'adc_fs_loaded' );
}

if ( ! defined( 'WPADCENTER_PLUGIN_FILENAME' ) ) {
	define( 'WPADCENTER_PLUGIN_FILENAME', __FILE__ );
}

if ( ! defined( 'WPADCENTER_PLUGIN_BASENAME' ) ) {
	define( 'WPADCENTER_PLUGIN_BASENAME', plugin_basename( WPADCENTER_PLUGIN_FILENAME ) );
}

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wpadcenter-activator.php
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
 * @since    1.0.0
 */
function run_wpadcenter() {

	$plugin = new Wpadcenter();
	$plugin->run();

}
run_wpadcenter();
