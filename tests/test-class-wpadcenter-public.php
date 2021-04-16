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
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids   = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );

		self::$wpadcenter_public = new Wpadcenter_Public( 'wpadcenter', '2.0.0' );

		$current_time = time();
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

}
