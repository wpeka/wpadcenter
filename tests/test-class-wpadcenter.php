<?php
/**
 * Class Wpadcenter_Test
 *
 * @package Wpadcenter
 */

/**
 * Require Wpadcenter_Admin class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter.php';

/**
 * Require Wpadcenter_Loader class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-loader.php';

/**
 * Wpadcenter class test case.
 */
class Wpadcenter_Test extends WP_UnitTestCase {

	/**
	 * The Wpadcenter class instance.
	 *
	 * @access public
	 * @var    string    $wpadcenter   class instance.
	 */
	public static $wpadcenter;

	/**
	 * Created ad ids.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_ids ad ids.
	 */
	public static $ad_ids;

	/**
	 * Dummy post .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $first_dummy_post dummy post.
	 */
	public static $first_dummy_post;

	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids           = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$first_dummy_post = get_post( self::$ad_ids[0] );
		update_post_meta(
			self::$ad_ids[0],
			'wpadcenter_ads_stats',
			array(
				'total_impressions' => 0,
				'total_clicks'      => 0,
			)
		);
	}


	/**
	 * Test for Wpadcenter class constructor
	 */
	public function test_wpadcenter_constructor() {
		self::$wpadcenter = new Wpadcenter();
		$this->assertTrue( self::$wpadcenter instanceof Wpadcenter );
	}

	/**
	 * Test for get_plugin_name function
	 */
	public function test_get_plugin_name() {
		$value = self::$wpadcenter->get_plugin_name();
		$this->assertEquals( 'wpadcenter', $value );
	}

	/**
	 * Test for get_loader function function
	 */
	public function test_get_loader() {
		$value = self::$wpadcenter->get_loader();
		$this->assertTrue( $value instanceof Wpadcenter_Loader );
	}

	/**
	 * Test for get_version function
	 */
	public function test_get_version() {
		$value = self::$wpadcenter->get_version();
		$this->assertEquals( '2.3.4', $value );
	}

	/**
	 * Test for is_request function
	 */
	public function test_is_request() {
		// case: admin
		$value = self::$wpadcenter->is_request( 'admin' );
		$this->assertFalse( $value );

		// case: ajax
		$value = self::$wpadcenter->is_request( 'ajax' );
		$this->assertFalse( $value );

		// case: cron
		$value = self::$wpadcenter->is_request( 'cron' );
		$this->assertFalse( $value );

		// case: frontend
		$value = self::$wpadcenter->is_request( 'frontend' );
		$this->assertTrue( $value );
	}

	/**
	 * Test for run function
	 */
	public function test_run() {
		self::$wpadcenter->run();
		$this->assertTrue( true );
	}

	/**
	 * Test for wpadcenter_get_default_settings function
	 */
	public function test_wpadcenter_get_default_settings() {
		$value = self::$wpadcenter->wpadcenter_get_default_settings( 'days_to_send_before' );
		$this->assertEquals( 1, $value );
		$value = self::$wpadcenter->wpadcenter_get_default_settings( '' );
		$this->assertTrue( is_array( $value ) && ! empty( $value ) );
	}

	/**
	 * Test for wpadcenter_sanitise_settings function
	 */
	public function test_wpadcenter_sanitise_settings() {

		$value = self::$wpadcenter->wpadcenter_sanitise_settings( 'enable_notifications', 'true' );
		$this->assertEquals( 1, $value );
		$value = self::$wpadcenter->wpadcenter_sanitise_settings( 'enable_scripts', 'false' );
		$this->assertEquals( 0, $value );
		$value = self::$wpadcenter->wpadcenter_sanitise_settings( 'trim_statistics', '' );
		$this->assertEquals( 0, $value );
		$value = self::$wpadcenter->wpadcenter_sanitise_settings( 'roles_selected', 'administrator/' );
		$this->assertEquals( trim( stripslashes( 'administrator/' ) ), $value );
		$value = self::$wpadcenter->wpadcenter_sanitise_settings( 'ads_txt_content', '<Ads contents>' );
		$this->assertEquals( esc_textarea( '<Ads contents>' ), $value );
		$value = self::$wpadcenter->wpadcenter_sanitise_settings( 'sample_key', '<Ads contents>' );
		$this->assertEquals( sanitize_text_field( '<Ads contents>' ), $value );
	}

	/**
	 * Test for wpadcenter_envelope_settings_tab function
	 */
	public function test_wpadcenter_envelope_settings_tab() {
		ob_start();
		self::$wpadcenter->wpadcenter_envelope_settings_tab( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views/admin-display-report.php', 2 );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Test for wpadcenter_set_impressions function
	 */
	public function test_wpadcenter_set_impressions() {
		global $wpdb;
		self::$wpadcenter->wpadcenter_set_impressions( self::$ad_ids[0] );
		$today   = gmdate( 'Y-m-d' );
		$records = $wpdb->get_results( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, self::$ad_ids[0] ) ) ); // db call ok; no-cache ok.
		$this->assertEquals( 1, count( $records ) );
		self::$wpadcenter->wpadcenter_set_impressions( self::$ad_ids[0] );
		$impressions = $wpdb->get_var( $wpdb->prepare( 'SELECT ad_impressions FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, self::$ad_ids[0] ) ) ); // db call ok; no-cache ok.
		$this->assertEquals( 2, $impressions );
	}

	/**
	 * Test for wpadcenter_generate_settings_tabhead function
	 */
	public function test_wpadcenter_generate_settings_tabhead() {
		ob_start();
		self::$wpadcenter->wpadcenter_generate_settings_tabhead( array( 'tab' => 'title' ) );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );

		ob_start();
		self::$wpadcenter->wpadcenter_generate_settings_tabhead( array( 'tab' => array( 'This is title of tab' ) ) );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

}
