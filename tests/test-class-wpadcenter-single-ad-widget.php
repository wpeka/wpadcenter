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
	 * Dummy post .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $first_dummy_post dummy post.
	 */
	public static $first_dummy_post;

	/**
	 * Dummy post .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $second_dummy_post dummy post.
	 */
	public static $second_dummy_post;

	/**
	 * Set up function.
	 *
	 * @param class WP_UnitTest_Factory $factory class instance.
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids                      = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$first_dummy_post            = get_post( self::$ad_ids[0] );
		self::$second_dummy_post           = get_post( self::$ad_ids[1] );
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
			'ad_id'     => self::$ad_ids,
			'title'     => 'Sample title',
			'max_width' => 'on',
		);
		ob_start();
		$value  = self::$wpadcenter_single_ad_widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

		$instance['max_width'] = 'off';
		$instance['devices']   = array( 'set', 'mobile', 'tablet', 'desktop' );

		ob_start();
		$value  = self::$wpadcenter_single_ad_widget->widget( $args, $instance );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
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
				'ad_id'     => self::$ad_ids,
				'title'     => 'Sample title',
				'max_width' => 'off',
			)
		);
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for print_combobox_options function
	 */
	public function test_print_combobox_options() {
		$args = array(
			'post_type' => 'wpadcenter-ads',
		);

		$ads        = get_posts( $args );
		$single_ads = array();
		foreach ( $ads as $ad ) {
			$single_ads[ $ad->ID ] = $ad->post_title;
		}
		ob_start();
		self::$wpadcenter_single_ad_widget->print_combobox_options( $single_ads, self::$first_dummy_post->ID );
		$output   = ob_get_clean();
		$expected = '<option value="' . self::$second_dummy_post->ID . '">' . self::$second_dummy_post->post_title . '</option><option value="' . self::$first_dummy_post->ID . '" selected="selected">' . self::$first_dummy_post->post_title . '</option>';
		$this->assertEquals( $expected, $output );
	}

	/**
	 * Test for scripts function
	 */
	public function test_scripts() {
		global $wp_styles;
		self::$wpadcenter_single_ad_widget->scripts();
		$this->assertTrue( in_array( 'wpadcenter-frontend', $wp_styles->queue, true ) );
	}
}
