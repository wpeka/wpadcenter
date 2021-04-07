<?php
require_once ABSPATH . 'wp-admin/includes/ajax-actions.php';
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
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_groups = $factory->term->create_many( 4, array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		add_role( 'advertiser', 'Advertiser', 'edit_posts' );
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
	 * Test wp_ajax_set_clicks.
	 */
	/*public function test_wpadcenter_set_clicks() {
		global $wpdb;
		$args  = array(
			'post_type' => 'wpadcenter-ads',
		);
		$ad_id = wp_insert_post( $args );
		$array = array(
			'total_clicks'      => 0,
			'total_impressions' => 0,
		);
		update_post_meta( $ad_id, 'wpadcenter_ads_stats', $array );

		// Set up a default request.
		$_POST['security'] = wp_create_nonce( 'wpadcenter_set_clicks' );
		$_POST['action']   = 'set_clicks';
		$_POST['ad_id']    = $ad_id;
		try {
			$this->_handleAjax( 'set_clicks' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		// Response.
		$data_in_db = get_post_meta( $ad_id, 'wpadcenter_ads_stats', true );
		error_log( print_r( $data_in_db, true ) );
		$this->assertTrue( true );
	}*/
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
}
