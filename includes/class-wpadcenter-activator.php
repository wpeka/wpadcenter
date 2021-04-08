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
		global $wpdb;
        include_once ABSPATH . 'wp-admin/includes/upgrade.php';
        if (is_multisite() ) {
            // Get all blogs in the network and activate plugin on each one
            $blog_ids = $wpdb->get_col("SELECT blog_id FROM $wpdb->blogs");
            foreach ( $blog_ids as $blog_id ) {
                switch_to_blog($blog_id);
                Wpadcenter_Activator::wpadcenter_install_table();
                restore_current_blog();
            }
        } else {
            Wpadcenter_Activator::wpadcenter_install_table();
        }
	}

	public static function wpadcenter_install_table()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $table_name = $wpdb->prefix . 'ads_statistics';
        $sql = "CREATE TABLE $table_name (
			ad_id int(11) NOT NULL,
			ad_date DATE DEFAULT NULL,
			ad_clicks int(11) DEFAULT 0,
			ad_impressions int(11) DEFAULT 0
			) $charset_collate;";
        dbDelta($sql);
    }

}
