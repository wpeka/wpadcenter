<?php
/**
 * Class Wpadcenter_Random_Ad_Widget_Test
 *
 * @package Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * Require Wpadcenter_Random_Ad_Widget class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-random-ad-widget.php';
/**
 * Wpadcenter_Random_Ad_Widget class test case.
 */
class Wpadcenter_Random_Ad_Widget_Test extends WP_UnitTestCase {
	/**
	 * The Wpadcenter_Random_Ad_Widget class instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_random_ad_widget class instance.
	 */
	public static $wpadcenter_random_ad_widget;

	/**
	 * Created ad ids.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_ids ad ids.
	 */
	public static $ad_ids;

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
		self::$ad_ids                      = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group                    = $factory->term->create_many( 4, array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$wpadcenter_random_ad_widget = new Wpadcenter_Random_Ad_Widget();
	}

	/**
	 * Test for widget function
	 */
	public function test_widget() {
		$args = array(
			'before_widget' => '<div>',
			'after_widget'  => '</div>',
			'before_title'  => '<h4>',
			'after_title'   => '</h4>',
		);

		$instance = array(
			'adgroup_ids' => array( self::$ad_group ),
			'title'       => 'Sample title',
			'max_width'   => 'on',
		);
		ob_start();
		$value  = self::$wpadcenter_random_ad_widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

		$instance['max_width'] = 'off';
		$instance['devices']   = array( 'set', 'mobile', 'tablet', 'desktop' );

		ob_start();
		$value  = self::$wpadcenter_random_ad_widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for update function
	 */
	public function test_update() {
		$output = self::$wpadcenter_random_ad_widget->update( 'new-title', 'old-title' );
		$this->assertEquals( 'new-title', $output );
	}

	/**
	 * Tets for form function
	 */
	public function test_form() {
		ob_start();
		self::$wpadcenter_random_ad_widget->form(
			array(
				'adgroup_ids' => array( self::$ad_group ),
				'title'       => 'Sample title',
				'max_width'   => 'off',
			)
		);
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}


}
