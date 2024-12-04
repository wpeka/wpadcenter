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
 * Version:           2.5.9
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
	define( 'WPADCENTER_SCRIPT_SUFFIX', ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '' );
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
define( 'WPADCENTER_VERSION', '2.5.7' );

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
	add_option('wpadcenter_do_activation_redirect', true);
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
    // Default allowed HTML tags and attributes
    $allowed_html = [
        'div' => [
            'id' => true,
            'class' => true,
            'style' => true,
        ],
        'span' => [
            'class' => true,
        ],
    ];

    // Sanitize the attributes
    $id = isset( $atts['id'] ) ? intval( $atts['id'] ) : 0; // Ensure ID is an integer
    $align = isset( $atts['align'] ) ? sanitize_text_field( $atts['align'] ) : 'left'; // Sanitize text input

    // Whitelist valid align values
    $allowed_alignments = [ 'left', 'center', 'right' ];
    if ( ! in_array( $align, $allowed_alignments, true ) ) {
        $align = 'left'; // Default to 'left' if invalid value is provided
    }

    // Prepare shortcode attributes
    $shortcode_attributes = [
        'id' => $id,
        'align' => $align,
    ];

    // Sanitize the attributes using wp_kses
    foreach ( $shortcode_attributes as $key => $value ) {
        $shortcode_attributes[ $key ] = wp_kses( $value, $allowed_html );
    }

    // Construct the sanitized shortcode
    $shortcode = sprintf(
        '[wpadcenter_ad id="%s" align="%s"]',
        esc_attr( $shortcode_attributes['id'] ),
        esc_attr( $shortcode_attributes['align'] )
    );

    // Output the sanitized shortcode
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
