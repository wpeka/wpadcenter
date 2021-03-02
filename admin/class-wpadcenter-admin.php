<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since  1.0.0
	 * @access private
	 * @var    string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @param string    $plugin_name       The name of this plugin.
	 * @param string    $version    The version of this plugin.
	 * 
	 * @since 1.0.0
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpadcenter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpadcenter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wpadcenter-admin.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since 1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wpadcenter_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wpadcenter_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wpadcenter-admin.js', array( 'jquery' ), $this->version, false );

	}

	/**
	 * Adds action links to the plugin list table.
	 *
	 * Fired by `plugin_action_links` filter.
	 *
	 * @param array $links An array of plugin action links.
	 * 
	 * @since 1.0.0
	 * 
	 * @return array An array of plugin action links.
	 */
	public function wpadcenter_plugin_action_links( $links ) {
		if ( ! get_option( 'wpadcenter_pro_installed' ) ) {
			$links = array_merge(
				array(
					'<a href="' . esc_url( 'https://club.wpeka.com/product/wpadcenter/?utm_source=wpadcenter&utm_medium=plugins&utm_campaign=link&utm_content=upgrade-to-pro' ) . '" target="_blank" rel="noopener noreferrer"><strong style="color: #11967A; display: inline;">' . __( 'Upgrade to Pro', 'wpadcenter' ) . '</strong></a>',
				),
				$links
			);
		}
		return $links;
	}

	/**
	 * Define arguments for custom post type.
	 *
	 * @since  1.0.0
	 * @return mixed|void
	 */
	public function wpadcenter_get_cpt_args() {

		$cpt_args        = array();
		$cpt_args['ads'] = apply_filters(
			'wpadcenter_cpt_args_ads',
			array(
				'labels'              => apply_filters(
					'wpadcenter_cpt_args_labels_ads',
					array(
						'name'               => __( 'WPAdCenter: Ads', 'wpadcenter' ),
						'singular_name'      => __( 'Ad', 'wpadcenter' ),
						'menu_name'          => __( 'WPAdCenter', 'wpadcenter' ),
						'all_items'          => __( 'Manage Ads', 'wpadcenter' ),
						'add_new_item'       => __( 'Create New Ad', 'wpadcenter' ),
						'add_new'            => __( 'Create New', 'wpadcenter' ),
						'new_item'           => __( 'New Ad', 'wpadcenter' ),
						'edit_item'          => __( 'Edit Ad', 'wpadcenter' ),
						'update_item'        => __( 'Update Ad', 'wpadcenter' ),
						'view_item'          => __( 'View Ad', 'wpadcenter' ),
						'view_items'         => __( 'View Ad', 'wpadcenter' ),
						'search_items'       => __( 'Search Ad', 'wpadcenter' ),
						'not_found'          => __( 'No Ads found', 'wpadcenter' ),
						'not_found_in_trash' => __( 'No Ads found in Trash', 'wpadcenter' ),
					)
				),
				'supports'            => array( 'title' ),
				'public'              => true,
				'show_ui'             => true,
				'show_in_menu'        => true,
				'show_in_admin_bar'   => true,
				'show_in_nav_menus'   => true,
				'can_export'          => true,
				'has_archive'         => false,
				'hierarchical'        => false,
				'exclude_from_search' => true,
				'show_in_rest'        => false,
				'publicly_queryable'  => false,
				'menu_icon'           => WPADCENTER_PLUGIN_URL . 'images/menu-icon.png',
				'rewrite'             => array( 'slug' => 'wpadcenter-ads' ),
				'capability_type'     => 'post',
			)
		);

		return apply_filters( 'wpadcenter_cpt_args', $cpt_args );

	}

	/**
	 * Register custom post type.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_cpt() {
		$wpadcenter_cpt_args = $this->wpadcenter_get_cpt_args();
		foreach ( $wpadcenter_cpt_args as $key => $cpt_args ) {
			if ( ! post_type_exists( 'wpadcenter-' . $key ) ) {
				register_post_type( 'wpadcenter-' . $key, $cpt_args );
			}
		}
	}

	/**
	 * Registers taxonomy.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_register_taxonomy() {
		$labels = array(
			'name'              => _x( 'Manage Ad Groups', 'taxonomy general name', 'wpadcenter' ),
			'singular_name'     => _x( 'Manage Ad Group', 'taxonomy singular name', 'wpadcenter' ),
			'search_items'      => __( 'Search Ad Groups', 'wpadcenter' ),
			'all_items'         => __( 'All Groups', 'wpadcenter' ),
			'parent_item'       => __( 'Parent Group', 'wpadcenter' ),
			'parent_item_colon' => __( 'Parent Group:', 'wpadcenter' ),
			'edit_item'         => __( 'Edit Group', 'wpadcenter' ),
			'update_item'       => __( 'Update Group', 'wpadcenter' ),
			'add_new_item'      => __( 'Add New Group', 'wpadcenter' ),
			'new_item_name'     => __( 'New Group Name', 'wpadcenter' ),
			'menu_name'         => __( 'Manage Ad Groups', 'wpadcenter' ),
			'not_found'         => __( 'No Ad Groups Found', 'wpadcenter' ),
			'view_item'         => __( 'View Ad Group', 'wpadcenter' ),
			'no_terms'          => __( 'No Ad Groups', 'wpadcenter' ),
		);
		$args   = array(
			'labels'       => $labels,
			'hierarchical' => true,
			'show_ui'      => true,
			'show_in_rest' => false,
			'rewrite'      => array( 'slug' => 'wpadcenter-adgroups' ),
		);

		register_taxonomy( 'wpadcenter-adgroups', array( 'wpadcenter-ads' ), $args );
	}

	/**
	 * Register the menu for the plugin.
	 *
	 * @since 1.0.0
	 */
	public function wpadcenter_admin_menu() {
		// Reports - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Reports',
			__( 'Reports', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-reports'
		);
		// Settings - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Settings',
			__( 'Settings', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-settings'
		);
		// Getting Started - submenu.
		add_submenu_page(
			'edit.php?post_type=wpadcenter-ads',
			'Getting Started',
			__( 'Getting Started', 'wpadcenter' ),
			'manage_options',
			'wpadcenter-getting-started'
		);
		do_action( 'wpadcenter_admin_menu', 'edit.php?post_type=wpadcenter-ads', 'manage_options' ); // action to add submenu pages for pro versions
		// Getting Started - submenu.
		if ( ! get_option( 'wpadcenter_pro_active' ) ) {
			add_submenu_page(
				'edit.php?post_type=wpadcenter-ads',
				'Go Pro',
				__( 'Go Pro', 'wpadcenter' ),
				'manage_options',
				'wpadcenter-go-pro'
			);
		}
	}

	/**
	 * Manage Ads table columns.
	 *
	 * @since  1.0.0
	 * @return array|void
	 */
	public function wpadcenter_manage_edit_ads_columns() {
		global $current_screen;
		if ( 'wpadcenter-ads' !== $current_screen->post_type ) {
			return;
		}
		$columns = array(
			'cb'              => '<input type="checkbox" />',
			'ad-title'        => __( 'Ad Title', 'wpadcenter' ),
			'ad-type'         => __( 'Ad Type', 'wpadcenter' ),
			'ad-dimensions'   => __( 'Ad Dimensions', 'wpadcenter' ),
			'ad-group'        => __( 'Ad Group', 'wpadcenter' ),
			'shortcode'       => __( 'Shortcode', 'wpadcenter' ),
			'template-tag'    => __( 'Template Tag', 'wpadcenter' ),
			'stats-for-today' => __( 'Stats for today', 'wpadcenter' ),
			'start-date'      => __( 'Start Date', 'wpadcenter' ),
			'end-date'        => __( 'End Date', 'wpadcenter' ),
		);
		if ( get_option( 'wpadcenter_pro_active' ) ) {
			$columns['advertiser'] = __( 'Advertiser', 'wpadcenter' );
		}
		return $columns;
	}

	/**
	 * Manage groupads columns.
	 *
	 * @since  1.0.0
	 * @return array|void
	 */
	public function wpadcenter_manage_edit_adgroups_columns() {
		global $current_screen;
		if ( 'wpadcenter-ads' !== $current_screen->post_type ) {
			return;
		}
		$columns = array(
			'cb'                   => '<input type="checkbox" />',
			'name'                 => __( 'Name', 'wpadcenter' ),
			'shortcode'            => __( 'Shortcode', 'wpadcenter' ),
			'template-tag'         => __( 'Template tag', 'wpadcenter' ),
			'number-of-ads'        => __( 'Number of ads', 'wpadcenter' ),
			'number-of-active-ads' => __( 'Number of active ads', 'wpadcenter' ),
		);
		if ( get_option( 'wpadcenter_pro_active' ) ) {
			$columns['advertiser'] = __( 'Advertiser', 'wpadcenter' );
		}
		return $columns;
	}
}
