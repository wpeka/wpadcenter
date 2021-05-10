<?php
/**
 * Unit test cases for multisite plugin activation.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/tests
 * @author     WPEka <hello@wpeka.com>
 */

/**
 * Require Wpadcenter_Activator class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-activator.php';

class Wpadcenter_Activation_Test extends WP_UnitTestCase {

	/**
	 * The Wpadcenter_Activator class instance .
	 *
	 * @access public
	 * @var    string    $wpadcenter_activator class instance.
	 */
	public static $wpadcenter_activator;

	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$wpadcenter_activator = new Wpadcenter_Activator();
	}

	/**
	 * Tests 'ads_statistics' table creation
	 */
	function test_wpadcenter_tables_should_get_created() {

		self::$wpadcenter_activator->activate();
		global $wpdb;
		$table_name = $wpdb->prefix . 'ads_statistics';
		if ( is_multisite() ) {
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				$wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );
				$this->assertEquals( $table_name, $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) );
				restore_current_blog();
			}
		} else {
			$this->assertEquals( $table_name, $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) );
		}
	}
}

