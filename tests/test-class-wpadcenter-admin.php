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
	 * WordPress default post type.
	 *
	 * @since  1.0.0
	 * @access public
	 * @var    string $default_post  post of WordPress default post type.
	 */
	public static $default_post;

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

	/**
	 * Set up function.
	 *
	 * @param WP_UnitTest_Factory $factory helper for unit test functionality.
	 *
	 * @since 1.0.0
	 */
	public static function wpSetUpBeforeClass( WP_UnitTest_Factory $factory ) {
		self::$default_post      = $factory->post->create();
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
		update_post_meta( self::$second_dummy_post->ID, 'wpadcenter_open_in_new_tab', false );
		update_post_meta( self::$second_dummy_post->ID, 'wpadcenter_nofollow_on_link', false );
		update_post_meta( self::$first_dummy_post->ID, 'wpadcenter_link_url', 'https://wpadcenter.com' );
	}

	/**
	 * Test for admin constructor()
	 */
	public function test_admin_constructor() {
		self::$wpadcenter_admin = new Wpadcenter_Admin( 'wpadcenter', '2.3.0' );
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
		update_option( 'wc_am_client_wpadcenter_pro_activated', 'Activated' );
		update_option( 'wpadcenter_pro_active', true );
		global $wp_meta_boxes;
		self::$wpadcenter_admin->wpadcenter_add_meta_boxes( self::$first_dummy_post );
		$metaboxes_high_priority = $wp_meta_boxes['wpadcenter-ads']['normal']['high'];
		$metaboxes_high_priority = array_keys( $metaboxes_high_priority );
		$expected_metaboxes      = array( 'ad-type', 'ad-size', 'ad-code', 'external-image-link', 'ad-google-adsense' );
		$this->assertFalse( boolval( array_diff( $expected_metaboxes, $metaboxes_high_priority ) ) );

		$metaboxes_low_priority = $wp_meta_boxes['wpadcenter-ads']['normal']['low'];
		$metaboxes_low_priority = array_keys( $metaboxes_low_priority );
		$expected_metaboxes     = array( 'ad-limits' );
		$this->assertFalse( boolval( array_diff( $expected_metaboxes, $metaboxes_low_priority ) ) );

		$metaboxes_core_priority = $wp_meta_boxes['wpadcenter-ads']['normal']['core'];
		$metaboxes_core_priority = array_keys( $metaboxes_core_priority );
		$expected_metaboxes      = array( 'ad-details', 'html5-ad-upload' );
	}


	/**
	 * Test for wpadcenter_register_widgets function .
	 */
	public function test_wpadcenter_register_widgets() {
		global $wp_version;

		self::$wpadcenter_admin->wpadcenter_register_widgets();
		$widgets = array_keys( $GLOBALS['wp_widget_factory']->widgets );

		if ( version_compare( $wp_version, '5.8' ) >= 0 ) {
			$this->assertFalse( in_array( 'Wpadcenter_Single_Ad_Widget', $widgets, true ) );
			$this->assertFalse( in_array( 'Wpadcenter_Adgroup_Widget', $widgets, true ) );
			$this->assertFalse( in_array( 'Wpadcenter_Random_Ad_Widget', $widgets, true ) );
		} else {
			$this->assertTrue( in_array( 'Wpadcenter_Single_Ad_Widget', $widgets, true ) );
			$this->assertTrue( in_array( 'Wpadcenter_Adgroup_Widget', $widgets, true ) );
			$this->assertTrue( in_array( 'Wpadcenter_Random_Ad_Widget', $widgets, true ) );

		}

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
		global $current_screen;
		$current_screen            = (object) $current_screen;
		$current_screen->post_type = 'wpadcenter-adgroup';

		$value = self::$wpadcenter_admin->wpadcenter_manage_edit_ads_columns();
		$this->assertTrue( empty( $value ) );

		$current_screen->post_type = 'wpadcenter-ads';
		$value                     = self::$wpadcenter_admin->wpadcenter_manage_edit_ads_columns();
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
			'adgroup_ids'       => self::$ad_group,
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
			'adgroup_ids'       => self::$ad_group,
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
		// Test for wpadcenter-ads rest fields.
		$request = new WP_REST_Request( 'GET', '/wp/v2/wpadcenter-ads' );
		$request->set_query_params( array( 'per_page' => 1 ) );
		$response                   = rest_do_request( $request );
		$server                     = rest_get_server();
		$wpadcenter_ads_rest_fields = $server->response_to_data( $response, false );
		$this->assertArrayHasKey( 'ad_html', $wpadcenter_ads_rest_fields[0], 'Failed to register ad html rest field' );

		// Test for wpadcenter-adgroups rest fields.
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
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for wpadcenter_ad_statistics function
	 */
	public function test_wpadcenter_ad_statistics() {

		ob_start();
		self::$wpadcenter_admin->wpadcenter_ad_statistics( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Tests for wpadcenter_limit_impressions_clicks
	 */
	public function test_wpadcenter_limit_impressions_clicks() {

		ob_start();
		self::$wpadcenter_admin->wpadcenter_limit_impressions_clicks( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
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
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
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
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
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
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Tests for wpadcenter_getting_started function
	 */
	public function test_wpadcenter_getting_started() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_getting_started();
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

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
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
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

	/**
	 * Test for  wpadcenter_amp_preference_metabox function
	 */
	public function test_wpadcenter_amp_preference_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_amp_preference_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for  wpadcenter_amp_attributes_metabox function
	 */
	public function test_wpadcenter_amp_attributes_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_amp_attributes_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

		update_post_meta(
			self::$first_dummy_post->ID,
			'wpadcenter_amp_attributes',
			array(
				'type',
				'width',
				'height',
			)
		);
		update_post_meta(
			self::$first_dummy_post->ID,
			'wpadcenter_amp_values',
			array(
				'industrybrains',
				'300',
				'200',
			)
		);
		ob_start();
		self::$wpadcenter_admin->wpadcenter_amp_attributes_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for  wpadcenter_video_details_metabox function
	 */
	public function test_wpadcenter_video_details_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_video_details_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}
	
	/**
	 * Test for  wpadcenter_text_ad_metabox function
	 */
	public function test_wpadcenter_text_ad_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_text_ad_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for  wpadcenter_save_ad_meta function
	 */
	public function test_wpadcenter_save_ad_meta() {
		$user_id = self::factory()->user->create(
			array(
				'role' => 'editor',
			)
		);
		wp_set_current_user( $user_id );
		$_POST['wpadcenter_save_ad_nonce'] = wp_create_nonce( 'wpadcenter_save_ad' );
		$_POST['ad_type']                  = 'ad_code';
		$_POST['ad-code']                  = '<h1>new test ad code</h1>';
		self::$wpadcenter_admin->wpadcenter_save_ad_meta( self::$ad_ids[0] );
		$saved_ad_code = get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_code', true );
		$this->assertEquals( $saved_ad_code, '<h1>new test ad code</h1>' );
		$_POST['ad-type']                  = 'external_image_link';
		$_POST['ad-size']                  = '200x200';
		$_POST['open-in-new-tab']          = '1';
		$_POST['nofollow-on-link']         = '1';
		$_POST['start_date']               = '1622117869';
		$_POST['limit-ad-impressions-set'] = '1';
		$_POST['limit-ad-impressions']     = 2;
		self::$wpadcenter_admin->wpadcenter_save_ad_meta( self::$ad_ids[0] );
		$saved_ad_type = get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_type', true );
		$this->assertEquals( $saved_ad_type, 'external_image_link' );

		$saved_ad_size = get_post_meta( self::$ad_ids[0], 'wpadcenter_ad_size', true );
		$this->assertEquals( $saved_ad_size, '200x200' );

		$saved_open_in_new_tab = get_post_meta( self::$ad_ids[0], 'wpadcenter_open_in_new_tab', true );
		$this->assertEquals( $saved_open_in_new_tab, '1' );

		$saved_nofollow_on_link = get_post_meta( self::$ad_ids[0], 'wpadcenter_nofollow_on_link', true );
		$this->assertEquals( $saved_nofollow_on_link, '1' );

		$saved_start_date = get_post_meta( self::$ad_ids[0], 'wpadcenter_start_date', true );
		$this->assertEquals( $saved_start_date, '1622117869' );

		$saved_limit_ad_impressions_set = get_post_meta( self::$ad_ids[0], 'wpadcenter_limit_impressions_set', true );
		$this->assertEquals( $saved_limit_ad_impressions_set, '1' );

		$saved_limit_ad_impressions = get_post_meta( self::$ad_ids[0], 'wpadcenter_limit_impressions', true );
		$this->assertEquals( $saved_limit_ad_impressions, 2 );
	}

	/**
	 * Test for wpadcenter_gutenberg_block_categories function
	 */
	public function test_wpadcenter_gutenberg_block_categories() {

		$value = self::$wpadcenter_admin->wpadcenter_gutenberg_block_categories( array() );
		$this->assertTrue( is_array( $value ) );
	}

	/**
	 * Test for wpadcenter_ads_txt_replace function
	 */
	public function test_wpadcenter_ads_txt_replace() {
		$output = self::$wpadcenter_admin->wpadcenter_ads_txt_replace();
		$this->assertTrue( is_wp_error( $output ) );
	}

	/**
	 * Test for wpadcenter_post_submitbox_start function
	 */
	public function test_wpadcenter_post_submitbox_start() {
		$url = get_permalink( self::$ad_ids[0] );
		$this->go_to( $url );

		ob_start();
		self::$wpadcenter_admin->wpadcenter_post_submitbox_start( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}

	/**
	 * Test for test_wpadcenter_settings function
	 */
	public function test_wpadcenter_settings() {
		$this->expectException( 'WPDieException' );
		self::$wpadcenter_admin->wpadcenter_settings();
	}

	/**
	 * Test for wpadcenter_parse_file function.
	 */
	public function test_wpadcenter_parse_file() {
		$sample_text = 'This#is#a#sample#text.#This#is#a#sample#text.';
		$value       = self::$wpadcenter_admin->wpadcenter_parse_file( $sample_text );
		$this->assertTrue( is_array( $value ) && ! empty( $value ) );
	}

	/**
	 * Test for wpadcenter_ad_selected function.
	 */
	public function test_wpadcenter_ad_selected() {
		$_POST['security'] = wp_create_nonce( 'selectad_security' );
		$_POST['action']   = 'selected_ad_reports';
		$this->expectException( 'WPDieException' );
		self::$wpadcenter_admin->wpadcenter_ad_selected();
	}

	/**
	 * Test for wpadcenter_export_csv function.
	 */
	public function test_wpadcenter_export_csv() {
		$_POST['action']   = 'post_export_csv';
		$_POST['security'] = wp_create_nonce( 'exportcsv_security' );

		$_POST['csv_data'] = 'sample-text';
		$this->expectException( 'WPDieException' );
		self::$wpadcenter_admin->wpadcenter_export_csv();
	}

	/**
	 * Test for wpadcanter_dequeue_styles function
	 */
	public function test_wpadcanter_dequeue_styles() {
		$user_id = self::factory()->user->create(
			array(
				'role' => 'administrator',
			)
		);
		wp_set_current_user( $user_id );
		set_current_screen( 'edit.php ' );
		global $current_screen;
		$current_screen->post_type = 'wpadcenter-ads';
		$current_screen->base      = 'wpadcenter-ads_page_wpadcenter-reports';
		$value                     = self::$wpadcenter_admin->wpadcanter_dequeue_styles( 'http://localhost/example1/forms.css' );
		$this->assertFalse( $value );
		$value = self::$wpadcenter_admin->wpadcanter_dequeue_styles( 'http://localhost/example1' );
		$this->assertEquals( 'http://localhost/example1', $value );
	}

	/**
	 * Test for wpadcenter_remove_forms_style function
	 */
	public function test_wpadcenter_remove_forms_style() {
		$user_id = self::factory()->user->create(
			array(
				'role' => 'administrator',
			)
		);
		wp_set_current_user( $user_id );
		set_current_screen( 'edit.php ' );
		global $current_screen;
		$current_screen->post_type = 'wpadcenter-ads';
		$current_screen->base      = 'wpadcenter-ads_page_wpadcenter-reports';
		$value                     = self::$wpadcenter_admin->wpadcenter_remove_forms_style(
			array(
				'forms',
				'revisions',
			)
		);
		$this->assertTrue( empty( $value ) );
	}

	/**
	 * Test for wpadcenter_save_scripts function
	 */
	public function test_wpadcenter_save_scripts() {
		$user_id = self::factory()->user->create(
			array(
				'role' => 'editor',
			)
		);
			wp_set_current_user( $user_id );
			$_REQUEST['nonce'] = wp_create_nonce( 'action' );

			$_POST['body_scripts'] = '<h1>Heading for unit test.</h1>';

			self::$wpadcenter_admin->wpadcenter_save_scripts( self::$default_post );

			$scripts = get_post_meta( self::$default_post, 'scripts', true );

			$this->assertEquals( $scripts['body_scripts'], '<h1>Heading for unit test.</h1>' );
	}

	/**
	 * Test for wpadcenter_link_options_metabox function
	 */
	public function test_wpadcenter_link_options_metabox() {
		ob_start();
		self::$wpadcenter_admin->wpadcenter_link_options_metabox( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

		ob_start();
		self::$wpadcenter_admin->wpadcenter_link_options_metabox( self::$second_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

	}

	/**
	 * Test for wpadcenter_mascot_on_pages function
	 */
	public function test_wpadcenter_mascot_on_pages() {
		set_current_screen( 'edit.php' );
		global $wp_scripts;
		global $current_screen;
		$current_screen->post_type = 'wpadcenter-ads';
		ob_start();
		self::$wpadcenter_admin->wpadcenter_mascot_on_pages();
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
		$this->assertArrayHasKey( 'wpadcenter-mascot', $wp_scripts->registered, 'wpadcenter-mascot script is not registered.' );
	}

		/**
		 * Test for wpadcenter_check_ads_txt_replace function
		 */
	public function test_wpadcenter_check_ads_txt_replace() {
		$user_id = self::factory()->user->create(
			array(
				'role' => 'administrator',
			)
		);
		wp_set_current_user( $user_id );
		$_POST['action']   = 'check_ads_txt_replace';
		$_POST['security'] = wp_create_nonce( 'check_ads_txt_replace' );
		$this->expectException( 'WPDieException' );
		self::$wpadcenter_admin->wpadcenter_check_ads_txt_replace();
	}

	/**
	 * Test for wpadcenter_get_notices function
	 */
	public function test_wpadcenter_get_notices() {
		$value = self::$wpadcenter_admin->wpadcenter_get_notices();
		$this->assertTrue( $value['response'] );
		$this->assertEquals( '', $value['error_message'] );
		$this->assertEquals( '<p>The file was not created.</p>', $value['file_available'] );
	}

	/**
	 * Test for wpadcenter_is_subdir function
	 */
	public function test_wpadcenter_is_subdir() {
		$value = self::$wpadcenter_admin->wpadcenter_is_subdir();
		$this->assertFalse( $value );
		$value = self::$wpadcenter_admin->wpadcenter_is_subdir( 'https://wordpress.org/plugins/wpadcenter/' );
		$this->assertTrue( $value );
	}

	/**
	 * Test for wpadcenter_custom_filters_query function
	 */
	public function test_wpadcenter_custom_filters_query() {
		$user_id = self::factory()->user->create(
			array(
				'role' => 'administrator',
			)
		);
		set_current_screen( 'edit.php ' );
		wp_set_current_user( $user_id );
		$wp_user_object = new WP_User( $user_id );
		$wp_user_object->add_role( 'advertiser' );
		$nonce                                      = wp_create_nonce( 'wpadcenter_add_custom_filter' );
		$_GET['wpadcenter_add_custom_filter_nonce'] = $nonce;
		$_GET['post_type']                          = 'wpadcenter-ads';
		$_GET['ADMIN_FILTER_FIELD_AD_TYPE']         = 'ad_code';
		$_GET['ADMIN_FILTER_FIELD_AD_SIZE']         = '468x60';
		$_GET['ADMIN_FILTER_FIELD_AD_GROUP']        = self::$ad_group;
		$_GET['ADMIN_FILTER_FIELD_ADVERTISER']      = $user_id;
		global $pagenow;
		$pagenow = 'edit.php';
		$query   = new WP_Query();
		self::$wpadcenter_admin->wpadcenter_custom_filters_query( $query );
		$query_vars_array = $query->query_vars;
		$this->assertArrayHasKey( 'meta_query', $query_vars_array, 'failed to add meta_query' );
		$this->assertArrayHasKey( 'tax_query', $query_vars_array, 'failed to add tax_query' );
	}

	/**
	 * Test for wpadcenter_upgrade_to_pro function
	 */
	public function test_wpadcenter_upgrade_to_pro() {
		set_current_screen( 'edit.php ' );
		global $current_screen;
		$current_screen->post_type = 'wpadcenter-ads';
		$current_screen->base      = 'wpadcenter-ads_page_wpadcenter-reports';
		ob_start();
		self::$wpadcenter_admin->wpadcenter_upgrade_to_pro();
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );

	}

	/**
	 * Tests for wpadcenter_html5_ad_upload
	 */
	public function test_wpadcenter_html5_ad_upload() {

		ob_start();
		self::$wpadcenter_admin->wpadcenter_html5_ad_upload( self::$first_dummy_post );
		$output = ob_get_clean();
		$this->assertTrue( is_string( $output ) && ( wp_strip_all_tags( $output ) !== $output ) );
	}
}

