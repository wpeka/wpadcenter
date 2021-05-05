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
	 * Dummy post .
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $second_dummy_post dummy post.
	 */
	public static $second_dummy_post;

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
		self::$ad_ids            = $factory->post->create_many( 2, array( 'post_type' => 'wpadcenter-ads' ) );
		self::$ad_group          = $factory->term->create( array( 'taxonomy' => 'wpadcenter-adgroups' ) );
		self::$first_dummy_post  = get_post( self::$ad_ids[0] );
		self::$second_dummy_post = get_post( self::$ad_ids[1] );
		self::$current_time      = time();
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
		update_post_meta( self::$first_dummy_post->ID, 'wpadcenter_open_in_new_tab', true );
		update_post_meta( self::$first_dummy_post->ID, 'wpadcenter_nofollow_on_link', true );
		update_post_meta( self::$first_dummy_post->ID, 'wpadcenter_link_url', 'https://wpadcenter.com' );
	}

	/**
	 * Test for admin constructor()
	 */
	public function test_admin_constructor() {
		self::$wpadcenter_admin = new Wpadcenter_Admin( 'wpadcenter', '2.0.1' );
		$this->assertTrue( self::$wpadcenter_admin instanceof Wpadcenter_Admin );
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
					$expected .= ( $end_date === '1924905600' ) ? esc_html__( 'Forever', 'wpadcenter' ) : esc_html( date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), $end_date ) );
					self::$wpadcenter_admin->wpadcenter_manage_ads_column_values( $column, self::$ad_ids[0] );
					$this->assertTrue( true );
					break;

				case 'ad-group':
					$expected .= wp_get_post_terms( self::$ad_ids[0], 'wpadcenter-adgroups', array( 'fields' => 'names' ) )[0];
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
		do_action( 'admin_enqueue_scripts' );
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

	/**
	 * Tests for wpadcenter_register_gutenberg_blocks function
	 */
	public function test_wpadcenter_register_gutenberg_blocks() {

		$registered_blocks = WP_Block_Type_Registry::get_instance()->get_all_registered();
		$this->assertArrayHasKey( 'wpadcenter/single-ad', $registered_blocks, 'Failed to register single ad gutenberg block' );
		$this->assertArrayHasKey( 'wpadcenter/adgroup', $registered_blocks, 'Failed to register adgroup gutenberg block' );
		$this->assertArrayHasKey( 'wpadcenter/random-ad', $registered_blocks, 'Failed to register random ad gutenberg block' );

	}

	/**
	 * Tests for gutenberg_display_single_ad_cb function
	 */
	public function test_gutenberg_display_single_ad_cb() {
		$attributes     = array(
			'ad_id'           => self::$ad_ids[0],
			'ad_alignment'    => 'aligncenter',
			'max_width_check' => false,
			'max_width_px'    => '100',
		);
		$single_ad_html = self::$wpadcenter_admin->gutenberg_display_single_ad_cb( $attributes );
		$this->assertTrue( is_string( $single_ad_html ) );

	}

	/**
	 * Tests for gutenberg_display_adgroup_cb function
	 */
	public function test_gutenberg_display_adgroup_cb() {
		$attributes   = array(
			'adgroup_ids'       => self::$ad_group ,
			'adgroup_alignment' => 'aligncenter',
			'num_ads'           => '1',
			'num_columns'       => '1',
			'max_width_check'   => false,
			'max_width_px'      => '100',
		);
		$adgroup_html = self::$wpadcenter_admin->gutenberg_display_adgroup_cb( $attributes );
		$this->assertTrue( is_string( $adgroup_html ) );

	}

	/**
	 * Tests for gutenberg_display_random_ad_cb function
	 */
	public function test_gutenberg_display_random_ad_cb() {
		$attributes     = array(
			'adgroup_ids'       => self::$ad_group ,
			'adgroup_alignment' => 'aligncenter',
			'max_width_check'   => false,
			'max_width_px'      => '100',
		);
		$random_ad_html = self::$wpadcenter_admin->gutenberg_display_random_ad_cb( $attributes );
		$this->assertTrue( is_string( $random_ad_html ) );

	}

	/**
	 * Tests for wpadcenter_register_rest_fields function
	 */
	public function test_wpadcenter_register_rest_fields() {
		// test for wpadcenter-ads rest fields
		$request = new WP_REST_Request( 'GET', '/wp/v2/wpadcenter-ads' );
		$request->set_query_params( array( 'per_page' => 1 ) );
		$response                   = rest_do_request( $request );
		$server                     = rest_get_server();
		$wpadcenter_ads_rest_fields = $server->response_to_data( $response, false );
		$this->assertArrayHasKey( 'ad_html', $wpadcenter_ads_rest_fields[0], 'Failed to register ad html rest field' );

		// test for wpadcenter-adgroups rest fields.
		$request = new WP_REST_Request( 'GET', '/wp/v2/wpadcenter-adgroups' );
		$request->set_query_params( array( 'per_page' => 1 ) );
		$response                        = rest_do_request( $request );
		$server                          = rest_get_server();
		$wpadcenter_adgroups_rest_fields = $server->response_to_data( $response, false );
		$this->assertArrayHasKey( 'ad_ids', $wpadcenter_adgroups_rest_fields[0], 'Failed to register ad ids rest field' );

	}

	/**
	 * Tests for wpadcenter_ad_html_rest_field_cb function
	 */
	public function test_wpadcenter_ad_html_rest_field_cb() {
		$object['id'] = self::$ad_ids[0];
		$ad_html      = self::$wpadcenter_admin->wpadcenter_ad_html_rest_field_cb( $object );
		$this->assertTrue( is_string( $ad_html ) );

	}

	/**
	 * Tests for wpadcenter_ad_ids_rest_field_cb function
	 */
	public function test_wpadcenter_ad_ids_rest_field_cb() {
		$object['id'] = self::$ad_group;
		$ad_html      = self::$wpadcenter_admin->wpadcenter_ad_ids_rest_field_cb( $object );
		$this->assertTrue( is_array( $ad_html ) );

	}


	/**
	 * Test for wpadcenter_reports function
	 */
	public function test_wpadcenter_reports() {

		ob_start();
		self::$wpadcenter_admin->wpadcenter_reports();
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Test for wpadcenter_ad_statistics function
	 */
	public function test_wpadcenter_ad_statistics() {

		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_statistics( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Tests for wpadcenter_limit_impressions_clicks
	 */
	public function test_wpadcenter_limit_impressions_clicks() {

		ob_start();
		self::$wpadcenter_admin->wpadcenter_limit_impressions_clicks( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Tests for wpadcenter_edit_form_after_title
	 */
	public function test_wpadcenter_edit_form_after_title() {
		self::$wpadcenter_admin->wpadcenter_edit_form_after_title( self::$first_dummy_post );
		global $wp_scripts, $wp_styles;
		$all_enqueued_scripts = $wp_scripts->queue;
		$this->assertTrue( in_array( 'wpadcenter-select2', $all_enqueued_scripts ) );
		$this->assertTrue( in_array( 'wpadcenter', $all_enqueued_scripts ) );

		$all_enqueued_styles = $wp_styles->queue;
		$this->assertTrue( in_array( 'wpadcenter-select2', $all_enqueued_styles ) );
	}

	/**
	 * Test for wpadcenter_admin_menu function
	 */
	public function test_wpadcenter_admin_menu() {
		$current_user = wp_get_current_user();
		$current_user->add_cap( 'manage_options' );

		self::$wpadcenter_admin->wpadcenter_admin_menu();
		global $submenu;
		$submenu_array = wp_list_pluck( $submenu['edit.php?post_type=wpadcenter-ads'], 2 );
		$this->assertTrue( isset( $submenu['edit.php?post_type=wpadcenter-ads'] ) );
		$this->assertTrue( in_array( 'wpadcenter-reports', $submenu_array ) );
		$this->assertTrue( in_array( 'wpadcenter-settings', $submenu_array ) );
		$this->assertTrue( in_array( 'wpadcenter-getting-started', $submenu_array ) );
		$this->assertTrue( in_array( 'https://club.wpeka.com/product/wpadcenter', $submenu_array ) );
	}

	/**
	 * Test for wpadcenter_ad_size_metabox function
	 */
	public function test_wpadcenter_ad_size_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_size_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Test for wpadcenter_collect_locations function
	 */
	public function test_wpadcenter_collect_locations() {
		self::$wpadcenter_admin->wpadcenter_collect_locations( 'https://wpadcenter.com/' );
		$this->assertTrue( true );
	}

	/**
	 * Test for print_combobox_options
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
		self::$wpadcenter_admin->print_combobox_options( $single_ads, self::$first_dummy_post->post_title );
		$output   = ob_get_clean();
		$expected = '<option value="' . self::$second_dummy_post->post_title . '">' . self::$second_dummy_post->ID . '</option><option value="' . self::$first_dummy_post->post_title . '" selected="selected">' . self::$first_dummy_post->ID . '</option>';
		$this->assertSame( $expected, $output );
	}

	/**
	 * Test for wpadcenter_ad_detail_metabox function
	 */
	public function test_wpadcenter_ad_detail_metabox() {
		$open_in_new_tab  = get_post_meta( self::$first_dummy_post->ID, 'wpadcenter_open_in_new_tab', true );
		$nofollow_on_link = get_post_meta( self::$first_dummy_post->ID, 'wpadcenter_nofollow_on_link', true );
		$url              = get_post_meta( self::$first_dummy_post->ID, 'wpadcenter_link_url', true );

		$this->assertEquals( 1, $open_in_new_tab );
		$this->assertEquals( 1, $nofollow_on_link );
		$this->assertEquals( 'https://wpadcenter.com', $url );

		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_detail_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Test for wpadcenter_ad_code_metabox function
	 */
	public function test_wpadcenter_ad_code_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_code_metabox( self::$first_dummy_post );
		$output   = ob_get_clean();
		$expected = '<textarea name="ad-code" style="width:100%;height:200px" >' . esc_textarea( get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_code', true ) ) . '</textarea>';
		$this->assertEquals( $expected, $output );
	}

	/**
	 * Test for wpadcenter_ad_google_adsense function
	 */
	public function test_wpadcenter_ad_google_adsense() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_google_adsense( self::$first_dummy_post );
		$output   = ob_get_clean();
		$expected = '<textarea name="ad-google-adsense" id="wpadcenter-google-adsense-code" style="width:100%;height:200px" >' . esc_textarea( get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_google_adsense', true ) ) . '</textarea>';
		ob_start();
		self::$wpadcenter_admin->render_adsense_selection();
		$expected .= ob_get_clean();
		$this->assertEquals( $expected, $output );
	}

	/**
	 * Test for wpadcenter_external_image_link_metabox function
	 */
	public function test_wpadcenter_external_image_link_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_external_image_link_metabox( self::$first_dummy_post );
		$output   = ob_get_clean();
		$expected = '<input name="external-image-link" type="text" value="' . esc_textarea( get_post_meta( self::$ad_ids[0], 'wpadcenter_external_image_link', true ) ) . '" style="width:100%">';
		$this->assertEquals( $expected, $output );
	}

	/**
	 * Test for wpadcenter_ad_type function
	 */
	public function test_wpadcenter_ad_type() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_type( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Tests for wpadcenter_getting_started function
	 */
	public function test_wpadcenter_getting_started() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_getting_started();
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );

		global $wp_scripts;
		$all_enqueued_scripts = $wp_scripts->queue;
		$this->assertTrue( in_array( 'wpadcenter-gettingstarted', $all_enqueued_scripts ) );

		global $wp_styles;
		$all_enqueued_styles = $wp_styles->queue;
		$this->assertTrue( in_array( 'wpadcenter-gettingstarted-css', $all_enqueued_styles ) );

		$current_user = wp_get_current_user();
		$current_user->remove_cap( 'manage_options' );
		$this->expectException( 'WPDieException' );
		self::$wpadcenter_admin->wpadcenter_getting_started();
	}

	/**
	 * Test for wpadcenter_page_posts_scripts function
	 */
	public function test_wpadcenter_page_posts_scripts() {
		global $wp_meta_boxes;
		$this->assertFalse( isset( $wp_meta_boxes['post'] ) );
		$this->assertFalse( isset( $wp_meta_boxes['page'] ) );
		self::$wpadcenter_admin->wpadcenter_page_posts_scripts();
		$this->assertTrue( isset( $wp_meta_boxes['post'] ) );
		$this->assertTrue( isset( $wp_meta_boxes['page'] ) );
	}

	/**
	 * Test for wpadcenter_page_posts_metabox_render function
	 */
	public function test_wpadcenter_page_posts_metabox_render() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_page_posts_metabox_render( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( $output != strip_tags( $output ) ) );
	}

	/**
	 * Test for wpadcenter_remove_permalink function
	 */
	public function test_wpadcenter_remove_permalink() {
		$url = get_permalink( self::$ad_ids[0] );
		$this->go_to( $url );
		global $post_type;
		ob_start();
		self::$wpadcenter_admin->wpadcenter_remove_permalink();
		$output  = ob_get_clean();
		$expcted = '<style>#edit-slug-box {display:none;}</style>';
		$this->assertEquals( $expcted, $output );
	}

	/**
	 * Test for wpadcenter_check_ads_txt_replace function
	 */
	public function test_wpadcenter_get_root_domain_info() {
		$value = self::$wpadcenter_admin->wpadcenter_get_root_domain_info( 'http://one.net.two/three/four/five' );
		$this->assertFalse( $value );
		$value = self::$wpadcenter_admin->wpadcenter_get_root_domain_info( 'http://one.com.au/three/four/five' );
		$this->assertFalse( $value );
		$value = self::$wpadcenter_admin->wpadcenter_get_root_domain_info( 'http://two.one.com/three/four/five' );
		$this->assertTrue( $value );
	}
}
