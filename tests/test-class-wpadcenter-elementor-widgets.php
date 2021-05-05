<?php
/**
 * Class Wpadcenter_Elementor_Widgets_Test.
 *
 * @link  https://club.wpeka.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes/elementor
 */

 /**
  * Require Wpadcenter_Public class.
  */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/elementor/class-wpadcenter-elementor-widgets.php';

/**
 * Wpadceneter_Elementor_Widgets class test cases
 */
class Wpadcenter_Elementor_Widgets_Test extends WP_UnitTestCase {

	/**
	 * Wpadcenter_Elementor_Widgets class instance
	 *
	 * @access public
	 * @var  string $wpadcenter_elementor_widgets class instance
	 */

	public static $wpadcenter_elementor_widgets;

	/**
	 * Test for get_instance function.
	 */
	public function test_get_instance() {
		self::$wpadcenter_elementor_widgets = Wpadcenter_Elementor_Widgets::get_instance();
		$this->assertTrue( self::$wpadcenter_elementor_widgets instanceof Wpadcenter_Elementor_Widgets );
	}

	/**
	 * Test for on_plugins_loaded function.
	 */
	public function test_on_plugins_loaded() {
		self::$wpadcenter_elementor_widgets->on_plugins_loaded();
		$all_widget_types = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
		$this->assertArrayHasKey( 'wpadcenter-single-ad', $all_widget_types, 'Failed to register wpadcenter-single-ad widget' );
		$this->assertArrayHasKey( 'wpadcenter-adgroup', $all_widget_types, 'Failed to register wpadcenter-adgroup widget' );
		$this->assertArrayHasKey( 'wpadcenter-random-ad', $all_widget_types, 'Failed to register wpadcenter-random-ad widget' );
	}
}
