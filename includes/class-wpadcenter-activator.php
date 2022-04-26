<?php
/**
 * Fired during plugin activation
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Activator {


	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function activate() {

		$wpadcenter_review_option_exists = get_option( 'wpadcenter_review_pending' );
		if ( ! $wpadcenter_review_option_exists ) {
			add_option( 'wpadcenter_review_pending', '0', '', true );
		}

		global $wpdb;
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		if ( is_multisite() ) {
			// Get all blogs in the network and activate plugin on each one.
			$blog_ids = $wpdb->get_col( "SELECT blog_id FROM $wpdb->blogs" );//phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching
			foreach ( $blog_ids as $blog_id ) {
				switch_to_blog( $blog_id );
				self::wpadcenter_install_table();
				self::wpadcenter_install_placement_table();
				restore_current_blog();
			}
		} else {
			self::wpadcenter_install_table();
			self::wpadcenter_install_placement_table();
		}
		update_option( 'wpadcenter_placement_table_install', true );
	}

	/**
	 * Function to create new table for ads
	 */
	public static function wpadcenter_install_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . 'ads_statistics';
		$sql             = "CREATE TABLE $table_name (
			ad_id int(11) NOT NULL,
			ad_date DATE DEFAULT NULL,
			ad_clicks int(11) DEFAULT 0,
			ad_impressions int(11) DEFAULT 0
			) $charset_collate;";
		dbDelta( $sql );
	}


	/**
	 * Function to create new table for placements
	 */
	public static function wpadcenter_install_placement_table() {
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		$table_name      = $wpdb->prefix . 'placements_statistics';
		$sql             = "CREATE TABLE $table_name (
			placement_name VARCHAR(30) NOT NULL,
			placement_date DATE DEFAULT NULL,
			placement_clicks int(11) DEFAULT 0,
			placement_impressions int(11) DEFAULT 0,
			placement_id VARCHAR(20) NOT NULL
			) $charset_collate;";
		dbDelta( $sql );
		update_option( 'wpadcenter_placement_db', '1' );
	}

}
