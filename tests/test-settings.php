<?php
require_once ABSPATH . 'wp-admin/includes/ajax-actions.php';

/**
 * Require Wpadcenter_Public class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpadcenter-public.php';

class SettingsTest extends WP_Ajax_UnitTestCase {

	/**
	 * The Wpadcenter_Public clas instance .
	 *
	 * @access public
	 * @var    string    $wpadcenter_public  class instance.
	 */
	public $wpadcenter_public;

	public function setup() {
		parent::setup();
		$this->wpadcenter_public = new Wpadcenter_Public( 'wpadcenter', '2.2.0' );
	}

	/**
	 * Test for exlude roles on save
	 */
	public function test_wpadcenter_exclude_roles_settings() {
		// become administrator.
		$this->_setRole( 'administrator' );
		$saved                                    = 'Editor, Administrator';
		$_POST['wpadcenter_settings_ajax_update'] = 'update_admin_settings_form';
		$_POST['_wpnonce']                        = wp_create_nonce( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
		$_POST['roles_selected_field']            = $saved;
		$_POST['action']                          = 'save_settings';

		$echoed = '';

		try {
			$this->_handleAjax( 'save_settings' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		// get response.
		$options = get_option( WPADCENTER_SETTINGS_FIELD );
		$this->assertEquals( $options['roles_selected'], $saved, 'roles selected error' );
	}
	/**
	 * Test for trim stats on save
	 */
	public function test_wpadcenter_trim_stats_settings() {
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
		// get response.
		$options = get_option( WPADCENTER_SETTINGS_FIELD );
		$this->assertEquals( $options['trim_stats'], $saved, 'trim stats error' );
	}
	/**
	 * Test wp_ajax_get_roles.
	 */
	public function test_wpadcenter_get_roles() {
		global $wp_roles;
		$roles = $wp_roles->get_names();
		// become administrator.
		$this->_setRole( 'administrator' );

		// Set up a default request.
		$_POST['security'] = wp_create_nonce( 'roles_security' );
		$_POST['action']   = 'get_roles';
		try {
			$this->_handleAjax( 'get_roles' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		// Response.
		$response = json_decode( $this->_last_response );
		foreach ( $roles as $meta_key => $meta_value ) {
			$this->assertTrue( in_array( $meta_value, $response ) );
		}
	}
	/**
	 * Test for scripts settings on save
	 */
	public function test_wpadcenter_scripts_settings() {
		// become administrator.
		$this->_setRole( 'administrator' );
		$header_scripts                           = '<script type="text/javascript">console.log("hello world in head");</script>';
		$body_scripts                             = '<script type="text/javascript">console.log("hello world in body");</script>';
		$footer_scripts                           = '<script type="text/javascript">console.log("hello world in footer");</script>';
		$_POST['wpadcenter_settings_ajax_update'] = 'update_admin_settings_form';
		$_POST['_wpnonce']                        = wp_create_nonce( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
		$_POST['header_scripts_field']            = $header_scripts;
		$_POST['body_scripts_field']              = $body_scripts;
		$_POST['footer_scripts_field']            = $footer_scripts;
		$_POST['action']                          = 'save_settings';

		$echoed = '';

		try {
			$this->_handleAjax( 'save_settings' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}

		// Test for public class wpadcenter_output_header_global function
		$expected = "\r\n" . $header_scripts . "\r\n";
		$this->wpadcenter_public->wpadcenter_output_header_global();
		$this->assertTrue( true );

		// Test for public class wpadcenter_output_body_global function
		$expected .= "\r\n" . $body_scripts . "\r\n";
		$this->wpadcenter_public->wpadcenter_output_body_global();
		$this->assertTrue( true );

		// Test for public class wpadcenter_output_footer_global function
		$expected .= "\r\n" . $footer_scripts . "\r\n";
		$this->expectOutputString( $expected );
		$this->wpadcenter_public->wpadcenter_output_footer_global();
		$this->assertTrue( true );
	}

	/**
	 * Test for ads txt content settings on save
	 */
	public function test_wpadcenter_ads_txt_settings() {
		// become administrator.
		$this->_setRole( 'administrator' );
		$saved                                    = 'google.com, DIRECT, PUB-9999999999999999999, something';
		$_POST['wpadcenter_settings_ajax_update'] = 'update_admin_settings_form';
		$_POST['_wpnonce']                        = wp_create_nonce( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
		$_POST['ads_txt_content_field']           = $saved;
		$_POST['ads_txt_tab']                     = 1;
		$_POST['action']                          = 'save_settings';

		try {
			$this->_handleAjax( 'save_settings' );
		} catch ( WPAjaxDieContinueException $e ) {
			unset( $e );
		}
		// get response.
		$options = get_option( WPADCENTER_SETTINGS_FIELD );
		$this->assertEquals( $options['ads_txt_content'], $saved );
	}
}
