<?php
/**
 * Class Wpadcenter_Admin_Test
 *
 * @package Wpadcenter
 * @subpackage Wpadcenter/tests
 */

/**
 * Required file.
 */
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
	protected static $ad_groups;

	/**
	 * The Wpadcenter_Admin class instance .
	 *
	 * @access public
	 * @var    string    $wpadcenter_admin  class instance.
	 */
	public static $wpadcenter_admin;

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

	/**
	 * Term id for taxonomy wpadcenter-adgroups for created dummy post
	 *
	 * @access public
	 * @var int $term_id term id
	 */
	public static $term_id;


	/**
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_groups = $factory->term->create_many( 4, array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		add_role( 'advertiser', 'Advertiser', 'edit_posts' );
		self::$wpadcenter_admin = new Wpadcenter_Admin( 'wpadcenter', '2.2.4' );
		self::$ad_ids           = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$first_dummy_post = get_post( self::$ad_ids[0] );
		self::$term_id          = array( self::$ad_groups );
		$taxonomy               = 'wpadcenter-adgroups';
		wp_set_post_terms( self::$ad_ids[0], self::$term_id, $taxonomy );
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
		$this->assertEquals( $response->$ad->ad_id, $ad, 'Ad Id doesnt match' );
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

		// before setting role as an admin.
		$this->expectException( 'WPAjaxDieStopException' );
		$this->_handleAjax( 'check_ads_txt_problems' );

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

	/**
	 * Test for wpadcenter_add_custom_filters function
	 */
	public function test_wpadcenter_add_custom_filters() {

		global $current_screen;
		$current_screen->post_type = 'wpadcenter-ads';

		// become administrator.
		$this->_setRole( 'administrator' );
		update_option( 'wpadcenter_pro_active', true );
		$_POST['wpadcenter_add_custom_filter_nonce'] = wp_create_nonce( 'wpadcenter_add_custom_filter' );
		$_POST['wpadcenter_settings_ajax_update']    = 'update_admin_settings_form';
		$_POST['_wpnonce']                           = wp_create_nonce( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
		$_POST['enable_advertisers_field']           = 'true';
		$_POST['action']                             = 'save_settings';

		try {
			$this->_handleAjax( 'save_settings' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// added role as advertiser to current user.
		$user_id = self::factory()->user->create();
		$user    = new WP_User( $user_id );
		$user->add_role( 'administrator' );
		$user->add_role( 'editor' );
		wp_set_current_user( $user_id );

		ob_start();
		self::$wpadcenter_admin->wpadcenter_add_custom_filters();
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
  }
   
  /*
	 * Test for wpadcenter_check_ads_txt_replace function
	 */
	public function test_wpadcenter_check_ads_txt_replace() {

		// before setting role as an admin.
		$this->expectException( 'WPAjaxDieStopException' );
		$this->_handleAjax( 'check_ads_txt_replace' );

		$_POST['action']   = 'check_ads_txt_replace';
		$_POST['security'] = wp_create_nonce( 'check_ads_txt_replace' );

		try {
			$this->_handleAjax( 'check_ads_txt_replace' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		$this->assertTrue( true );
	}
}
