<?php
/**
 * Class Wpadcenter_Single_Ad_Widget_Test
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

 /**
  * Require Wpadcenter_Single_Ad_Widget class.
  */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-wpadcenter-single-ad-widget.php';

/**
 * Wpadcenter_Single_Ad_Widget class test case.
 */
class Wpadcenter_Single_Ad_Widget_Test extends WP_UnitTestCase {

	/**
	 * The Wpadcenter_Single_Ad_Widget class instance.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_single_ad_widget class instance.
	 */
	public static $wpadcenter_single_ad_widget;

	/**
	 * Created ad ids.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $ad_ids ad ids.
	 */
	public static $ad_ids;

	/**
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids                      = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$wpadcenter_single_ad_widget = new Wpadcenter_Single_Ad_Widget();
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
			'ad_id'     => self::$ad_ids[0],
			'title'     => 'Sample title',
			'max_width' => 'on',
		);
		ob_start();
		$value  = self::$wpadcenter_single_ad_widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output !== strip_tags( $output ) ) );
	}

	  /**
	   * Test for update function
	   */
	public function test_update() {
		$output = self::$wpadcenter_single_ad_widget->update( 'new-title', 'old-title' );
		$this->assertEquals( 'new-title', $output );
	}

	/**
	 * Tets for form function
	 */
	public function test_form() {
		ob_start();
		self::$wpadcenter_single_ad_widget->form(
			array(
				'ad_id'     => self::$ad_ids[0],
				'title'     => 'Sample title',
				'max_width' => 'on',
			)
		);
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output !== strip_tags( $output ) ) );
	}
}
