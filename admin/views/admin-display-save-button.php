<?php
/**
 * Provide a admin area view for the save button.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://club.wpeka.com
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin/views
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div style="clear: both;"></div>
<div class="wpadcenter-plugin-toolbar bottom">
		<div class="left"></div>
		<div class="right">
			<input type="submit" name="update_admin_settings_form" value="<?php esc_attr_e( 'Update', 'wpadcenter' ); ?>" class="button-primary" style="float:right;" onClick="return wpadcenter_settings_btn_click(this.name)" />
			<span class="spinner" style="margin-top:9px"></span>
		</div>
</div>
