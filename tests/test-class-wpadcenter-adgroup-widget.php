<?php
/**
 * Class Wpadcenter_Adgroup_Widget_Test
 *
 * @package Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * Require Wpadcenter_Adgroup_Widget class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-adgroup-widget.php';

/**
 * Wpadcenter_Adgroup_Widget class test case.
 */
class Wpadcenter_Adgroup_Widget_Test extends WP_UnitTestCase {
	/**
	 * The Wpadcenter_Adgroup_Widget class instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_adgroup_widget class instance.
	 */
	public static $wpadcenter_adgroup_widget;

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
		self::$ad_ids                    = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group                  = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$wpadcenter_adgroup_widget = new Wpadcenter_Adgroup_Widget();
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
			'num_ads'     => '5',
			'num_columns' => '5',
			'alignment'   => 'left',
			'max_width'   => 'on',
		);
		ob_start();
		$value  = self::$wpadcenter_adgroup_widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output !== strip_tags( $output ) ) );
	}

	/**
	 * Test for update function
	 */
	public function test_update() {
		$output = self::$wpadcenter_adgroup_widget->update( 'new-title', 'old-title' );
		$this->assertEquals( 'new-title', $output );
	}

	/**
	 * Tets for form function
	 */
	public function test_form() {
		ob_start();
		self::$wpadcenter_adgroup_widget->form(
			array(
				'adgroup_ids' => array( self::$ad_group ),
				'title'       => 'Sample title',
				'num_ads'     => '5',
				'num_columns' => '5',
				'alignment'   => 'left',
				'max_width'   => 'on',
			)
		);
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output !== strip_tags( $output ) ) );
	}

}
