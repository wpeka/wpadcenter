<?php
require_once ABSPATH . 'wp-admin/includes/ajax-actions.php';

/**
 * Require Wpadcenter_Admin class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpadcenter-admin.php';
/**
 * Unit test cases for ajax request in reports page.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/tests
 * @author     WPEka <hello@wpeka.com>
 */
class AjaxTest extends WP_Ajax_UnitTestCase {
	/**
	 * Arrays of adgroups.
	 *
	 * @var object
	 */
	protected static $ad_groups = null;
	/**
	 * Setup necessary variables.
	 *
	 * @param mixed $factory factory to create variables.
	 */

	 /**
	  * The Wpadcenter_Admin class instance .
	  *
	  * @access public
	  * @var    string    $wpadcenter_admin  class instance.
	  */
	public static $wpadcenter_admin;


	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_groups = $factory->term->create_many( 4, array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		add_role( 'advertiser', 'Advertiser', 'edit_posts' );
		self::$wpadcenter_admin = new Wpadcenter_Admin( 'wpadcenter', '2.1.0' );
	}
	/**
	 * Test wp_ajax_get_adgroups.
	 */
	public function test_wpadcenter_get_adgroups() {

		// become administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['security'] = wp_create_nonce( 'adgroups_security' );
		$_POST['action']   = 'get_adgroups';
		try {
			$this->_handleAjax( 'get_adgroups' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// get response.
		$response = json_decode( $this->_last_response );
		$count    = count( self::$ad_groups );
		for ( $i = 0; $i < $count; $i++ ) {
			$this->assertEquals( $response[ $i ]->term_id, self::$ad_groups[ $i ], 'ad group ids didnt match' );
		}
	}
	/**
	 * Test wp_ajax_selected_adgroup_reports.
	 */
	public function test_wpadcenter_selected_adgroup_reports() {
		$args     = array(
			'post_type' => 'wpadcenter-ads',
		);
		$ad       = wp_insert_post( $args );
		$ad_group = wp_set_object_terms( $ad, 'adgroup1', 'wpadcenter-adgroups' );
		// become administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['security']          = wp_create_nonce( 'adgroups_security' );
		$_POST['action']            = 'selected_adgroup_reports';
		$_POST['selected_ad_group'] = $ad_group[0];
		try {
			$this->_handleAjax( 'selected_adgroup_reports' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		// get response.
		$response = json_decode( $this->_last_response );
		$this->assertEquals( $response[0]->ad_id, $ad, 'Ad Id doesnt match' );
	}

	/**
	 * Test wp_ajax_selected_ad_reports.
	 */
	public function test_wpadcenter_selected_ad_reports() {
		global $wpdb;
		$args           = array(
			'post_type' => 'wpadcenter-ads',
		);
		$ad             = wp_insert_post( $args );
		$today          = gmdate( 'Y-m-d' );
		$ad_clicks      = 23;
		$ad_impressions = 21;
		$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'ads_statistics` (`ad_impressions`, `ad_date`, `ad_id`, `ad_clicks`) VALUES (%d,%s,%d,%d)', array( $ad_impressions, $today, $ad, $ad_clicks ) ) );
		// become administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['security']    = wp_create_nonce( 'selectad_security' );
		$_POST['action']      = 'selected_ad_reports';
		$_POST['selected_ad'] = array( array( 'ad_id' => $ad ) );
		$_POST['start_date']  = strtotime( $today ) - 5 * 24 * 60 * 60;
		$_POST['end_date']    = strtotime( $today ) + 5 * 24 * 60 * 60;
		try {
			$this->_handleAjax( 'selected_ad_reports' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$response = json_decode( $this->_last_response );
		$this->assertEquals( $response[0]->ad_id, $ad );
		$this->assertEquals( $response[0]->ad_date, $today );
		$this->assertEquals( $response[0]->ad_impressions, $ad_impressions );
		$this->assertEquals( $response[0]->ad_clicks, $ad_clicks );
	}

	/**
	 * Test wp_ajax_check_ads_txt_problems when content is not entered.
	 */
	public function test_check_ads_txt_problems() {
		// become administrator.
		$this->_setRole( 'administrator' );

		$_POST['security'] = wp_create_nonce( 'check_ads_txt_problems' );
		$_POST['action']   = 'check_ads_txt_problems';
		try {
			$this->_handleAjax( 'check_ads_txt_problems' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$response = json_decode( $this->_last_response );
		$this->assertEquals( intval( $response->response ), 1 );
		$this->assertEquals( $response->error_message, '' );
		$this->assertEquals( $response->file_available, '<p>The file was not created.</p>' );
	}


	/**
	 * Test for wpadcenter_monthly_schedule_clean_stats function
	 */
	public function test_wpadcenter_monthly_schedule_clean_stats() {
		global $wpdb;
		$args           = array(
			'post_type' => 'wpadcenter-ads',
		);
		$ad             = wp_insert_post( $args );
		$today          = gmdate( 'Y-m-d', mktime( 0, 0, 0, 10, 23, 2020 ) );
		$ad_clicks      = 23;
		$ad_impressions = 21;
		$wpdb->query( $wpdb->prepare( 'INSERT IGNORE INTO `' . $wpdb->prefix . 'ads_statistics` (`ad_impressions`, `ad_date`, `ad_id`, `ad_clicks`) VALUES (%d,%s,%d,%d)', array( $ad_impressions, $today, $ad, $ad_clicks ) ) );

		// become administrator.
		$this->_setRole( 'administrator' );
		$saved                                    = 2;
		$_POST['wpadcenter_settings_ajax_update'] = 'update_admin_settings_form';
		$_POST['_wpnonce']                        = wp_create_nonce( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
		$_POST['trim_stats_field']                = $saved;
		$_POST['action']                          = 'save_settings';

		$echoed = '';

		try {
			$this->_handleAjax( 'save_settings' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		self::$wpadcenter_admin->wpadcenter_monthly_schedule_clean_stats();
		$record = $wpdb->get_col( $wpdb->prepare( 'SELECT * FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_id = %d', $ad ) ); // db call ok; no-cache ok.
		$this->assertTrue( is_array( $record ) && empty( $record ) );
	}
	/**
	 * Test for wp_ajax_adsense_load_adcode.
	 */
	public function test_load_google_adsense_code() {
		// become administrator.
		$this->_setRole( 'administrator' );

		$_POST['_wpnonce'] = wp_create_nonce( 'wpeka-google-adsense' );
		$_POST['action']   = 'adsense_load_adcode';
		$_POST['adunit']   = 'ca-app-pub-3940256099942544/3419835294';

		try {
			$this->_handleAjax( 'adsense_load_adcode' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$response = json_decode( $this->_last_response );
		$this->assertTrue( $response->error );
		$this->assertTrue( is_string( $response->message ) );
	}

}
