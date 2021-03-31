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
		add_option( 'wpadcenter_active', true );
		global $wpdb;
		$charset_collate = $wpdb->get_charset_collate();
		require_once ABSPATH . 'wp-admin/includes/upgrade.php';

		$table_name = $wpdb->prefix . 'ads_statistics';

		$sql = "CREATE TABLE $table_name (
			ad_id int(11) NOT NULL,
			ad_date DATE DEFAULT NULL,
			ad_clicks int(11) DEFAULT 0,
			ad_impressions int(11) DEFAULT 0
			) $charset_collate;";
		dbDelta( $sql );
	}

}
