<?php
/**
 * PHPUnit bootstrap file
 *
 * @package Wpadcenter
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wordpress-tests-lib';
}

if ( ! file_exists( $_tests_dir . '/includes/functions.php' ) ) {
	echo "Could not find $_tests_dir/includes/functions.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	exit( 1 );
}

// Give access to tests_add_filter() function.
require_once $_tests_dir . '/includes/functions.php';

/**
 * Manually load the plugin being tested.
 */
function _manually_load_plugin() {

	$string = dirname( dirname( __FILE__ ) ) . '/wpadcenter.php';
	require $string;
	// activate the plugin to get ads_statistics table on activation for testing.
	do_action( 'activate_' . trim( $string, '/' ) ); //phpcs:ignore
	$plugins_dir = ABSPATH . str_replace( site_url() . '/', '', plugins_url() ) . '/';
	require_once $plugins_dir . 'elementor/elementor.php';
}
tests_add_filter( 'muplugins_loaded', '_manually_load_plugin' );

/*
* Load PHPUnit Polyfills for the WP testing suite.
* @see https://github.com/WordPress/wordpress-develop/pull/1563/
*/
define( 'WP_TESTS_PHPUNIT_POLYFILLS_PATH', dirname( dirname( __FILE__ ) ) . '/vendor/yoast/phpunit-polyfills/phpunitpolyfills-autoload.php' );

// Start up the WP testing environment.
require $_tests_dir . '/includes/bootstrap.php';
