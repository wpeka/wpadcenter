<?php
/**
 * Class Wpadcenter_File_Test
 *
 * @package Wpadcenter
 * @subpackage Wpadcenter/tests
 */

/**
 * Main plugin file unit tests.
 */
class Wpadcenter_File_Test extends WP_UnitTestCase {

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
	 * Created ad group associated with created ad.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_group  ad group.
	 */
	public static $ad_group;

	/**
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids           = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group         = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$first_dummy_post = get_post( self::$ad_ids[0] );
			update_post_meta( self::$ad_ids[0], 'wpadcenter_ad_type', 'ad_code' );
			update_post_meta( self::$ad_ids[0], 'wpadcenter_start_date', time() );
			update_post_meta( self::$ad_ids[0], 'wpadcenter_end_date', '1924905600' );
			update_post_meta( self::$ad_ids[0], 'wpadcenter_ad_code', '<h1>testad</h1>' );
			update_post_meta( self::$ad_ids[0], 'wpadcenter_ad_size', '468x60' );
			update_post_meta(
				self::$ad_ids[0],
				'wpadcenter_ads_stats',
				array(
					'total_impressions' => 0,
					'total_clicks'      => 0,
				)
			);
		wp_set_post_terms( self::$ad_ids[0], array( self::$ad_group ), 'wpadcenter-adgroups' );
		update_post_meta( self::$ad_ids[0], 'wpadcenter_open_in_new_tab', true );
		update_post_meta( self::$ad_ids[0], 'wpadcenter_nofollow_on_link', true );
		update_post_meta( self::$ad_ids[0], 'wpadcenter_link_url', 'https://wpadcenter.com' );
	}

	/**
	 * Test for wpadcenter_display_ad function
	 */
	public function test_wpadcenter_display_ad() {
		$atts = array(
			'id'    => self::$ad_ids[0],
			'align' => 'left',
		);
		ob_start();
		wpadcenter_display_ad( $atts );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && wp_strip_all_tags( $output ) !== $output );
	}

	/**
	 * Test for wpadcenter_display_adgroup function
	 */
	public function test_wpadcenter_display_adgroup() {
		$atts = array(
			'adgroup_ids' => self::$ad_group,
			'align'       => 'left',
			'num_ads'     => '1',
			'num_columns' => '1',
		);
		ob_start();
		wpadcenter_display_adgroup( $atts );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && wp_strip_all_tags( $output ) !== $output );
	}
}
