<?php
/**
 * Class Wpadcenter_Elementor_RandomAd_Widget_Test
 *
 * @package Wpadcenter
 * @subpackage Wpadcenter/tests
 */

/**
 * Wpadcenter_Elementor_RandomAd_Widget class test cases.
 */
class Wpadcenter_Elementor_RandomAd_Widget_Test  extends WP_UnitTestCase {

	/**
	 * The Wpadcenter_Elementor_RandomAd_Widget class instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_elementor_randomad_widget class instance.
	 */
	public static $wpadcenter_elementor_randomad_widget;

	/**
	 * Created ad group associated with created ad.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_group  ad group.
	 */
	public static $ad_group;

	/**
	 * Created ad ids.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_ids ad ids.
	 */
	public static $ad_ids;

	/**
	 * Created a dummy post.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $first_dummy_post dummy post.
	 */
	public static $first_dummy_post;

	/**
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids                               = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$first_dummy_post                     = get_post( self::$ad_ids[0] );
		self::$ad_group                             = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$wpadcenter_elementor_randomad_widget = new Wpadcenter_Elementor_RandomAd_Widget();
		wp_set_post_terms( self::$ad_ids[0], array( self::$ad_group ), 'wpadcenter-adgroups' );
	}

	/**
	 * Test for get_name function.
	 */
	public function test_get_name() {
		$value = self::$wpadcenter_elementor_randomad_widget->get_name();
		$this->assertEquals( 'wpadcenter-random-ad', $value );
	}

	/**
	 * Test for get_title function.
	 */
	public function test_get_title() {
		$value = self::$wpadcenter_elementor_randomad_widget->get_title();
		$this->assertEquals( 'Random Ads (Deprecated)', $value );
	}

	/**
	 * Test for get_icon function.
	 */
	public function test_get_icon() {
		$value = self::$wpadcenter_elementor_randomad_widget->get_icon();
		$this->assertEquals( 'icon-adcenter', $value );
	}

	/**
	 * Test for get_categories function.
	 */
	public function test_get_categories() {
		$value = self::$wpadcenter_elementor_randomad_widget->get_categories();
		$this->assertEquals( array( 'general' ), $value );
	}

	/**
	 * Test for register_controls function
	 */
	public function test_register_controls() {

		$method = self::getMethod( 'register_controls' );
		$obj    = self::$wpadcenter_elementor_randomad_widget;
		$method->invoke( $obj );
		$all_controls = $obj->get_controls();
		$this->assertArrayHasKey( 'adgroup_ids', $all_controls, 'Failed to add adgroup_ids control.' );
		$this->assertArrayHasKey( 'alignment', $all_controls, 'Failed to add alignment control.' );
		$this->assertArrayHasKey( 'max_width', $all_controls, 'Failed to add max_width control.' );
		$this->assertArrayHasKey( 'max_width_px', $all_controls, 'Failed to add max_width_px control.' );
		$this->assertArrayHasKey( 'devices', $all_controls, 'Failed to add devices control.' );
	}

	/**
	 * Setup to test private or protected method.
	 *
	 * @param string $name Name of protected method to be call.
	 */
	protected static function getMethod( $name ) {
		$class  = new ReflectionClass( 'Wpadcenter_Elementor_RandomAd_Widget' );
		$method = $class->getMethod( $name );
		$method->setAccessible( true );
		return $method;
	}


	/**
	 * Test for get_adgroup_options function
	 */
	public function test_get_adgroup_options() {
		$value = self::$wpadcenter_elementor_randomad_widget->get_adgroup_options();
		$this->asserttrue( is_array( $value ) );
	}
}
