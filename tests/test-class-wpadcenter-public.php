<?php
/**
 * Class Wpadcenter_Public_Test
 *
 * @package Wpadcenter
 */

/**
 * Require Wpadcenter_Public class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-wpadcenter-public.php';

/**
 * Wpadcenter_Public class test case.
 */
class Wpadcenter_Public_Test extends WP_UnitTestCase {

	/**
	 * Created ad ids.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_ids ad ids.
	 */
	public static $ad_ids;

	/**
	 * The Wpadcenter_Public clas instance .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_public   class instance.
	 */
	public static $wpadcenter_public;

	/**
	 * Created ad group .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_group ad group associated with created ad.
	 */
	public static $ad_group;

	/**
	 * Created scripts .
	 *
	 * @access public
	 * @var    string $scripts scripts associated with created ad.
	 */
	public static $scripts;

	/**
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids            = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group          = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$wpadcenter_public = new Wpadcenter_Public( 'wpadcenter', '2.1.0' );
		$current_time            = time();
		foreach ( self::$ad_ids as $ad_id ) {
			update_post_meta( $ad_id, 'wpadcenter_ad_type', 'ad_code' );
			update_post_meta( $ad_id, 'wpadcenter_start_date', $current_time );
			update_post_meta( $ad_id, 'wpadcenter_end_date', '1924905600' );
			update_post_meta( $ad_id, 'wpadcenter_ad_code', '<h1>testad</h1>' );
			update_post_meta( $ad_id, 'wpadcenter_ad_size', '468x60' );
			update_post_meta(
				$ad_id,
				'wpadcenter_ads_stats',
				array(
					'total_impressions' => 0,
					'total_clicks'      => 0,
				)
			);

		}
		$post_id  = self::$ad_ids[0];
		$tag      = array( self::$ad_group );
		$taxonomy = 'wpadcenter-adgroups';
		wp_set_post_terms( $post_id, $tag, $taxonomy );
		// This is ok
		self::$scripts = array(
			'header_scripts' => '<script type="text/javascript">console.log("hello world in head");</script>',
			'body_scripts'   => '<script type="text/javascript">console.log("hello world in body");</script>',
			'footer_scripts' => '<script type="text/javascript">console.log("hello world in footer");</script>',
		);

		update_post_meta( self::$ad_ids[0], 'scripts', self::$scripts );
	}

	/**
	 * Test for Wpadcenter_Public constructor
	 */
	public function test_public_constructor() {
		remove_shortcode( 'wpadcenter_ad' );
		remove_shortcode( 'wpadcenter_adgroup' );
		remove_shortcode( 'wpadcenter_random_ad' );
		$this->assertFalse( shortcode_exists( 'wpadcenter_ad' ) );
		$this->assertFalse( shortcode_exists( 'wpadcenter_adgroup' ) );
		$this->assertFalse( shortcode_exists( 'wpadcenter_random_ad' ) );

		$wpadcenter_public_obj = new Wpadcenter_Public( 'wpadcenter', '2.1.0' );
		$this->assertTrue( $wpadcenter_public_obj instanceof Wpadcenter_Public );

		$this->assertTrue( shortcode_exists( 'wpadcenter_ad' ) );
		$this->assertTrue( shortcode_exists( 'wpadcenter_adgroup' ) );
		$this->assertTrue( shortcode_exists( 'wpadcenter_random_ad' ) );
	}

	/**
	 * Test for wpadcenter_ad_shortcode function.
	 */
	public function test_wpadcenter_ad_shortcode() {

		$received_output = do_shortcode( '[wpadcenter_ad id=' . self::$ad_ids[0] . ']' );
		$this->assertFalse( '[wpadcenter_ad id=' . self::$ad_ids[0] . ']' === $received_output );

		$this->assertTrue( is_string( $received_output ) );
	}

	/**
	 * Test for display_single_ad function.
	 */
	public function test_display_single_ad() {

		$received_output = self::$wpadcenter_public->display_single_ad( self::$ad_ids[0] );
		$this->assertTrue( is_string( $received_output ) );
	}

	/**
	 * Test for wpadcenter_adgroup_shortcode function.
	 */
	public function test_wpadcenter_adgroup_shortcode() {

		$received_output = do_shortcode( '[wpadcenter_adgroup adgroup_ids=' . self::$ad_group . ']' );
		$this->assertFalse( '[wpadcenter_adgroup adgroup_ids=' . self::$ad_group . ']' === $received_output );

		$this->assertTrue( is_string( $received_output ) );
	}

	/**
	 * Test for display_adgroup_ads function.
	 */
	public function test_display_adgroup_ads() {

		$atts = array(
			'adgroup_ids' => array( self::$ad_group ),
		);

		$received_output = self::$wpadcenter_public->display_adgroup_ads( $atts );
		$this->assertTrue( is_string( $received_output ) );
	}

	/**
	 * Test for wpadcenter_random_ad_shortcode function.
	 */
	public function test_wpadcenter_random_ad_shortcode() {

		$received_output = do_shortcode( '[wpadcenter_random_ad adgroup_ids=' . self::$ad_group . ']' );
		$this->assertFalse( '[wpadcenter_random_ad adgroup_ids=' . self::$ad_group . ']' === $received_output );

		$this->assertTrue( is_string( $received_output ) );
	}

	/**
	 * Test for display_random_ad function.
	 */
	public function test_display_random_ad() {

		$atts = array(
			'adgroup_ids' => array( self::$ad_group ),
		);

		$received_output = self::$wpadcenter_public->display_random_ad( $atts );
		$this->assertTrue( is_string( $received_output ) );
	}

	/**
	 * Test for wpadcenter_register_gutenberg_scripts function
	 */
	public function test_wpadcenter_register_gutenberg_scripts() {
		self::$wpadcenter_public->wpadcenter_register_gutenberg_scripts();
		do_action( 'wp_enqueue_scripts' );
		global $wp_styles;
		$all_registered_styles = $wp_styles->queue;
		$this->assertTrue( in_array( 'wpadcenter-frontend', $all_registered_styles ) );
	}

	/**
	 * Test for enqueue_styles function
	 */
	public function test_enqueue_styles() {
		global $wp_styles;
		wp_styles()->remove( 'wpadcenter-frontend' );
		$this->assertArrayNotHasKey( 'wpadcenter-frontend', $wp_styles->registered, 'wpadcenter-frontend style is registered.' );
		self::$wpadcenter_public->enqueue_styles();
		$this->assertArrayHasKey( 'wpadcenter-frontend', $wp_styles->registered, 'wpadcenter-frontend style is not registered.' );
	}

	/**
	 * Test for enqueue_scripts function
	 */
	public function test_enqueue_scripts() {
		global $wp_scripts;
		wp_scripts()->remove( 'wpadcenter-frontend' );
		$this->assertArrayNotHasKey( 'wpadcenter-frontend', $wp_scripts->registered, 'wpadcenter-frontend script is registered.' );
		self::$wpadcenter_public->enqueue_scripts();
		$this->assertArrayHasKey( 'wpadcenter-frontend', $wp_scripts->registered, 'wpadcenter-frontend script is not registered.' );
	}

	/**
	 * Test wp_ajax_set_clicks.
	 */
	public function test_wpadcenter_set_clicks() {
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

		self::$wpadcenter_public->wpadcenter_set_clicks();

		$today  = gmdate( 'Y-m-d' );
		$clicks = $wpdb->get_var( $wpdb->prepare( 'SELECT ad_clicks FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, $ad_id ) ) ); // db call ok; no-cache ok.
		 $this->assertEquals( '1', $clicks );
		 self::$wpadcenter_public->wpadcenter_set_clicks();
		 $clicks = $wpdb->get_var( $wpdb->prepare( 'SELECT ad_clicks FROM ' . $wpdb->prefix . 'ads_statistics WHERE ad_date = %s and ad_id = %d LIMIT 1', array( $today, $ad_id ) ) ); // db call ok; no-cache ok.
		 $this->assertEquals( '2', $clicks );
	}

	/**
	 * Test for wpadcenter_output_header_post function
	 */
	public function test_wpadcenter_output_header_post() {
		$url = get_permalink( self::$ad_ids[0] );
		$this->go_to( $url );
		$expected = "\r\n" . self::$scripts['header_scripts'] . "\r\n";
		$this->expectOutputString( $expected );
		self::$wpadcenter_public->wpadcenter_output_header_post();
	}

	/**
	 * Test for wpadcenter_output_body_post function
	 */
	public function test_wpadcenter_output_body_post() {
		$url = get_permalink( self::$ad_ids[0] );
		$this->go_to( $url );
		$expected = "\r\n" . self::$scripts['body_scripts'] . "\r\n";
		$this->expectOutputString( $expected );
		self::$wpadcenter_public->wpadcenter_output_body_post();
	}

	/**
	 * Test for wpadcenter_output_footer_post function
	 */
	public function test_wpadcenter_output_footer_post() {
		$url = get_permalink( self::$ad_ids[0] );
		$this->go_to( $url );
		$expected = "\r\n" . self::$scripts['footer_scripts'] . "\r\n";
		$this->expectOutputString( $expected );
		self::$wpadcenter_public->wpadcenter_output_footer_post();
	}

	/**
	 * Test for wpadcenter_verify_device function
	 */
	public function test_wpadcenter_verify_device() {
		$devices = array( 'mobile', 'tablet', 'desktop' );
		$display = self::$wpadcenter_public->wpadcenter_verify_device( $devices );
		$this->assertTrue( $display );
	}

	/**
	 * Test for wpadcenter_check_ads_txt_replace function
	 *
	 * @return void
	 */
	public function test_wpadcenter_get_root_domain_info() {
		$value = self::$wpadcenter_public->wpadcenter_get_root_domain_info( 'http://one.net.two/three/four/five' );
		$this->assertFalse( $value );
		$value = self::$wpadcenter_public->wpadcenter_get_root_domain_info( 'http://one.com.au/three/four/five' );
		$this->assertFalse( $value );
		$value = self::$wpadcenter_public->wpadcenter_get_root_domain_info( 'http://two.one.com/three/four/five' );
		$this->assertTrue( $value );
	}

	/**
	 * Test for wpadcenter_template_redirect function
	 */
	public function test_wpadcenter_template_redirect() {
		$url = get_permalink( self::$ad_ids[0] );
		$this->go_to( $url );
		self::$wpadcenter_public->wpadcenter_template_redirect();
		$disable_global_scripts = get_post_meta( self::$ad_ids[0], 'scripts', true );

		$this->assertEquals( '<script type="text/javascript">console.log("hello world in head");</script>', $disable_global_scripts['header_scripts'] );
		$this->assertEquals( '<script type="text/javascript">console.log("hello world in body");</script>', $disable_global_scripts['body_scripts'] );
		$this->assertEquals( '<script type="text/javascript">console.log("hello world in footer");</script>', $disable_global_scripts['footer_scripts'] );
	}
}
