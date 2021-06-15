<?php
/**
 * Class Wpadcenter_Elementor_Widget_Test
 *
 * @package Wpadcenter
 * @subpackage Wpadcenter/tests
 */

/**
 * Wpadcenter_Elementor_Widgets class test cases.
 */
class Wpadcenter_Elementor_Widget_Test  extends WP_UnitTestCase {

	/**
	 * The Wpadcenter_Elementor_Widget class instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_elementor_widget class instance.
	 */
	public static $wpadcenter_elementor_widget;

	/**
	 * Test for get_instance function.
	 */
	public function test_get_instance() {
		self::$wpadcenter_elementor_widget = Wpadcenter_Elementor_Widgets::get_instance();
		$this->assertTrue( self::$wpadcenter_elementor_widget instanceof Wpadcenter_Elementor_Widgets );
	}

	/**
	 * Test for on_plugin_loaded function.
	 */
	public function test_on_plugin_loaded() {
		self::$wpadcenter_elementor_widget->on_plugins_loaded();
		$this->assertTrue( true );
	}

	/**
	 * Test for is_compatible function.
	 */
	public function test_is_compatible() {
		$value = self::$wpadcenter_elementor_widget->is_compatible();
		$this->assertTrue( $value );
	}

	/**
	 * Test for init function
	 */
	public function test_init() {
		self::$wpadcenter_elementor_widget->init();
		$all_registered_widgets = \Elementor\Plugin::instance()->widgets_manager->get_widget_types();
		$this->assertArrayHasKey( 'wpadcenter-random-ad', $all_registered_widgets, 'Failed to register widget wpadcenter-random-ad' );
		$this->assertArrayHasKey( 'wpadcenter-adgroup', $all_registered_widgets, 'Failed to register widget wpadcenter-random-ad' );
		$this->assertArrayHasKey( 'wpadcenter-single-ad', $all_registered_widgets, 'Failed to register widget wpadcenter-random-ad' );
	}
}
