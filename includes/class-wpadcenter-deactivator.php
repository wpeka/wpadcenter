<?php
/**
 * Fired during plugin deactivation
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Wpadcenter
 * @subpackage Wpadcenter/includes
 * @author     WPEka <hello@wpeka.com>
 */
class Wpadcenter_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since 1.0.0
	 */
	public static function deactivate() {
		wp_clear_scheduled_hook( 'wpadcenter_monthly_cron' );
		delete_option( 'wpadcenter_update_placements_5.2.3' );
		delete_option( 'wpadcenter_placement_db' );

	}

}
