<?php
/**
 * Class Wpadcenter_Admin_Test
 *
 * @package Wpadcenter
 */

/**
 * Require Wpadcenter_Admin class.
 */
require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-wpadcenter-admin.php';

/**
 * Wpadcenter_Admin class test case.
 */
class Wpadcenter_Admin_Test extends WP_UnitTestCase {

	/**
	 * The Wpadcenter_Admin clas instance .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string    $wpadcenter_admin   class instance.
	 */
	public static $wpadcenter_admin;

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


	 /**
	  * Current time.
	  *
	  * @access public
	  * @var int $current_time current time
	  */
	public static $current_time;

	  /**
	   * Term id for taxonomy wpadcenter-adgroups for created dummy post
	   *
	   * @access public
	   * @var int $term_id term id
	   */
	public static $term_id;
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$ad_ids           = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group         = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$wpadcenter_admin = new Wpadcenter_Admin( 'wpadcenter', '2.0.1' );
		self::$first_dummy_post = get_post( self::$ad_ids[0] );
		self::$current_time     = time();
		foreach ( self::$ad_ids as $ad_id ) {
			update_post_meta( $ad_id, 'wpadcenter_ad_type', 'ad_code' );
			update_post_meta( $ad_id, 'wpadcenter_start_date', self::$current_time );
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
		$post_id       = self::$ad_ids[0];
		self::$term_id = array( self::$ad_group );
		$taxonomy      = 'wpadcenter-adgroups';
		wp_set_post_terms( $post_id, self::$term_id, $taxonomy );
	}

	/**
	 * Test for get_default_metafields function.
	 */
	public function test_get_default_metafields() {

		$received_metafields = self::$wpadcenter_admin->get_default_metafields();

		$this->assertTrue( is_array( $received_metafields ) );
	}

	/**
	 * Test for get_ad_meta_relation function.
	 */
	public function test_get_ad_meta_relation() {

		$received_ad_meta_relation = self::$wpadcenter_admin->get_ad_meta_relation();

		$this->assertTrue( is_array( $received_ad_meta_relation ) );
	}

	/**
	 * Test for get_default_ad_types function.
	 */
	public function test_get_default_ad_types() {

		$received_ad_types = self::$wpadcenter_admin->get_default_ad_types();
		$this->assertTrue( is_array( $received_ad_types ) );
	}

	/**
	 * Test for wpadcenter_add_meta_boxes function.
	 */
	public function test_wpadcenter_add_meta_boxes() {
		global $wp_meta_boxes;
		self::$wpadcenter_admin->wpadcenter_add_meta_boxes( self::$first_dummy_post );
		$metaboxes_high_priority = $wp_meta_boxes['wpadcenter-ads']['normal']['high'];
		$metaboxes_high_priority = array_keys( $metaboxes_high_priority );
		$expected_metaboxes      = array( 'ad-type', 'ad-size', 'ad-code', 'external-image-link', 'ad-google-adsense' );
		$this->assertFalse( boolval( array_diff( $expected_metaboxes, $metaboxes_high_priority ) ) );

		$metaboxes_low_priority = $wp_meta_boxes['wpadcenter-ads']['normal']['low'];
		$metaboxes_low_priority = array_keys( $metaboxes_low_priority );
		$expected_metaboxes     = array( 'ad-details' );
		$this->assertFalse( boolval( array_diff( $expected_metaboxes, $metaboxes_low_priority ) ) );
	}


	/**
	 * Test for wpadcenter_register_widgets function .
	 */
	public function test_wpadcenter_register_widgets() {
		self::$wpadcenter_admin->wpadcenter_register_widgets();
		$widgets = array_keys( $GLOBALS['wp_widget_factory']->widgets );

		$this->assertTrue( in_array( 'Wpadcenter_Single_Ad_Widget', $widgets, true ) );
		$this->assertTrue( in_array( 'Wpadcenter_Adgroup_Widget', $widgets, true ) );
		$this->assertTrue( in_array( 'Wpadcenter_Random_Ad_Widget', $widgets, true ) );

	}


	/**
	 * Test for wpadcenter_manage_edit_adgroups_columns function
	 */
	public function test_wpadcenter_manage_edit_adgroups_columns() {
		$value = self::$wpadcenter_admin->wpadcenter_manage_edit_adgroups_columns();

		$this->assertArrayHasKey( 'cb', $value, "Array doesn't contains 'cb'" );
		$this->assertArrayHasKey( 'name', $value, "Array doesn't contains 'name'" );
		$this->assertArrayHasKey( 'shortcode', $value, "Array doesn't contains 'shortcode'" );
		$this->assertArrayHasKey( 'template-tag', $value, "Array doesn't contains 'template-tag'" );
		$this->assertArrayHasKey( 'number-of-ads', $value, "Array doesn't contains 'number-of-ads'" );
		$this->assertArrayHasKey( 'number-of-active-ads', $value, "Array doesn't contains 'number-of-active-ads'" );
	}

	/**
	 * Test for wpadcenter_manage_edit_ads_columns function
	 */
	public function test_wpadcenter_manage_edit_ads_columns() {
		$value = self::$wpadcenter_admin->wpadcenter_manage_edit_ads_columns();
		$this->assertEquals( null, $value );

		global $current_screen;
		$current_screen->post_type = 'wpadcenter-ads';

		$value = self::$wpadcenter_admin->wpadcenter_manage_edit_ads_columns();
			$this->assertArrayHasKey( 'cb', $value, "Array doesn't contains 'cb'" );
			$this->assertArrayHasKey( 'title', $value, "Array doesn't contains 'title'" );
			$this->assertArrayHasKey( 'ad-type', $value, "Array doesn't contains 'ad-type'" );
			$this->assertArrayHasKey( 'ad-dimensions', $value, "Array doesn't contains 'ad-dimensions'" );
			$this->assertArrayHasKey( 'ad-group', $value, "Array doesn't contains 'ad-group'" );
			$this->assertArrayHasKey( 'shortcode', $value, "Array doesn't contains 'shortcode'" );
			$this->assertArrayHasKey( 'template-tag', $value, "Array doesn't contains 'template-tag'" );
			$this->assertArrayHasKey( 'stats-for-today', $value, "Array doesn't contains 'stats-for-today'" );
			$this->assertArrayHasKey( 'start-date', $value, "Array doesn't contains 'start-date'" );
			$this->assertArrayHasKey( 'end-date', $value, "Array doesn't contains 'end-date'" );
	}

	/**
	 * Tests for wpadcenter_manage_ad_groups_column_values function()
	 */
	public function test_wpadcenter_manage_ad_groups_column_values() {

		$columns = array( 'shortcode', 'template-tag', 'number-of-ads', 'number-of-active-ads' );
		foreach ( $columns as $col ) {
			switch ( $col ) {
				case 'shortcode':
					$value = self::$wpadcenter_admin->wpadcenter_manage_ad_groups_column_values( '', $col, self::$term_id );
					$this->assertTrue( is_string( $value ) );
					break;
				case 'template-tag':
					$value = self::$wpadcenter_admin->wpadcenter_manage_ad_groups_column_values( '', $col, self::$term_id );
					$this->assertTrue( is_string( $value ) );
					break;
				case 'number-of-ads':
					$value = self::$wpadcenter_admin->wpadcenter_manage_ad_groups_column_values( '', $col, self::$term_id );
					$this->assertEquals( 1, $value, $col . ' returns wrong value.' );
					break;
				case 'number-of-active-ads':
					$value = self::$wpadcenter_admin->wpadcenter_manage_ad_groups_column_values( '', $col, self::$term_id );
					$this->assertEquals( 1, $value, $col . ' returns wrong value.' );
					break;
			}
		};
	}

	/**
	 * Tests for wpadcenter_manage_ads_column_values function
	 */
	public function test_wpadcenter_manage_ads_column_values() {

		$sizes_list    = self::$wpadcenter_admin->get_default_ad_sizes();
		$ad_types_list = self::$wpadcenter_admin->get_default_ad_types();
		$columns       = array(
			'ad-type',
			'ad-dimensions',
			'start-date',
			'end-date',
			'ad-group',
			'shortcode',
			'template-tag',
			'stats-for-today',
		);
		$expected      = '';
		foreach ( $columns as $column ) {
			switch ( $column ) {
				case 'ad-type':
					$ad_type   = get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_type', true );
					$expected .= $ad_types_list[ $ad_type ];
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'ad-dimensions':
					$ad_size   = get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_size', true );
					$expected .= strval( $sizes_list[ $ad_size ][0] );
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'start-date':
					$start_date = get_post_meta( self::$ad_ids[0], 'wpadcenter_start_date', true );
					$expected  .= date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $start_date );
					$value      = self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'end-date':
					$end_date  = get_post_meta( self::$ad_ids[0], 'wpadcenter_end_date', true );
					$expected .= 'Forever';
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'ad-group':
					$expected .= 'Term 0000046';
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'shortcode':
					$expected .= sprintf( '<a href="#" class="wpadcenter_copy_text" data-attr="[wpadcenter_ad id=%d align=\'none\']">[shortcode]</a>', intval( self::$ad_ids[0] ) );
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'template-tag':
					$expected .= sprintf( '<a href="#" class="wpadcenter_copy_text" data-attr="wpadcenter_display_ad( array( \'id\' => %d, \'align\' => \'none\' ) );">&lt;?php</a>', intval( self::$ad_ids[0] ) );
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'stats-for-today':
					$expected .= '0 clicks / 0 views / 0.00% CTR';
					$this->expectOutputString( $expected );
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;
			}
		}
	}

	/**
	 * Tests for default_ad_sizes function
	 */
	public function test_default_ad_sizes() {
		$received_default_ad_sizes = self::$wpadcenter_admin->get_default_ad_sizes();
		$this->assertTrue( is_array( $received_default_ad_sizes ) && ! empty( $received_default_ad_sizes ) );
	}

	/**
	 * Tests for wpadcenter_register_taxonomy function
	 */
	public function test_wpadcenter_register_taxonomy() {
		unregister_taxonomy( 'wpadcenter-adgroups' );
		$this->assertFalse( taxonomy_exists( 'wpadcenter-adgroups' ) );
		self::$wpadcenter_admin->wpadcenter_register_taxonomy();
		$this->assertTrue( taxonomy_exists( 'wpadcenter-adgroups' ) );
	}

	/**
	 * Tests for get_transition_effect_options function
	 */
	public function test_get_transition_effect_options() {
		$received_transition_effect_options = self::$wpadcenter_admin->get_transition_effect_options();
		$this->assertTrue( is_array( $received_transition_effect_options ) && ! empty( $received_transition_effect_options ) );
	}

	/**
	 * Tests for wpadcenter_register_cpt function
	 */
	public function test_wpadcenter_register_cpt() {
		unregister_post_type( 'wpadcenter-ads' );
		$this->assertFalse( post_type_exists( 'wpadcenter-ads' ) );
		self::$wpadcenter_admin->wpadcenter_register_cpt();
		$this->assertTrue( post_type_exists( 'wpadcenter-ads' ) );
	}

	/**
	 * Tests for enqueue_scripts function
	 */
	public function test_enqueue_scripts() {
		self::$wpadcenter_admin->enqueue_scripts();
		global $wp_scripts;
		$all_enqueued_scripts = $wp_scripts->queue;
		$this->assertTrue( in_array( 'wpadcenter-gapi-settings', $all_enqueued_scripts ) );

		$all_registered_scripts = $wp_scripts->registered;
		$this->assertArrayHasKey( 'wpadcenter-settings', $all_registered_scripts, 'Failed to register script: wpadcenter-settings' );
		$this->assertArrayHasKey( 'wpadcenter-main', $all_registered_scripts, 'Failed to register script: wpadcenter-main' );
		$this->assertArrayHasKey( 'wpadcenter', $all_registered_scripts, 'Failed to register script: wpadcenter' );
		$this->assertArrayHasKey( 'wpadcenteradscheduler', $all_registered_scripts, 'Failed to register script: wpadcenteradscheduler' );
		$this->assertArrayHasKey( 'wpadcenter-gettingstarted', $all_registered_scripts, 'Failed to register script: wpadcenter-gettingstarted' );
		$this->assertArrayHasKey( 'wpadcenter-reports', $all_registered_scripts, 'Failed to register script: wpadcenter-reports' );
		$this->assertArrayHasKey( 'wpadcenter-weekly-stats', $all_registered_scripts, 'Failed to register script: wpadcenter--weekly-stats' );
	}

	/**
	 * Tests for enqueue_styles function
	 */

	public function test_enqueue_styles() {
		self::$wpadcenter_admin->enqueue_styles();
		global $wp_styles;
		$all_registered_styles = $wp_styles->registered;
		$this->assertArrayHasKey( 'wpadcenter-settings', $all_registered_styles, 'Failed to register style: ' );
		$this->assertArrayHasKey( 'wpadcenter', $all_registered_styles, 'Failed to register style: ' );
		$this->assertArrayHasKey( 'wpadcenterjquery-ui', $all_registered_styles, 'Failed to register style: ' );
		$this->assertArrayHasKey( 'wpadcenter-gettingstarted-css', $all_registered_styles, 'Failed to register style: ' );
	}

	/**
	 * Tests for wpadcenter_plugin_action_links function
	 */
	public function test_wpadcenter_plugin_action_links() {
		$value = self::$wpadcenter_admin->wpadcenter_plugin_action_links( array() );
		$this->assertTrue( is_array( $value ) );
	}
}
