<?php
/**
 * Provide a admin area view for the scripts tab.
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://club.wpeka.com
 * @since 1.0.1
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin/views
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}
?>
<div class="wpadcenter-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<table class="form-table">
		<?php do_action( 'wpadcenter_before_scripts_settings' ); ?>
		<tr valign="top">
			<th scope="row"><label for="enable_scripts_field"><?php esc_attr_e( 'Enable Scripts', 'wpadcenter' ); ?></label></th>
			<td>
				<input type="radio" id="enable_scripts_field_yes" name="enable_scripts_field" wpadcenter_frm_tgl-target="enable_scripts_field" class="wpadcenter_form_toggle" value="true" <?php echo ( true === $the_options['enable_scripts'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Enable', 'wpadcenter' ); ?>
				<input type="radio" id="enable_scripts_field_no" name="enable_scripts_field" wpadcenter_frm_tgl-target="enable_scripts_field" class="wpadcenter_form_toggle" value="false" <?php echo ( false === $the_options['enable_scripts'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Disable', 'wpadcenter' ); ?>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Enable scripts on all pages and/or posts.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="enable_scripts_field" wpadcenter_frm_tgl-val="true">
			<th scope="row"><label for="header_scripts_field"><?php esc_attr_e( 'Header Scripts (Global)', 'wpadcenter' ); ?></label></th>
			<td>
				<textarea id="header_scripts_field" name="header_scripts_field" class="vvv_textbox"><?php echo htmlentities( stripslashes( $the_options['header_scripts'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></textarea>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'These scripts will be printed in the head section on all pages and/or posts.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="enable_scripts_field" wpadcenter_frm_tgl-val="true">
			<th scope="row"><label for="body_scripts_field"><?php esc_attr_e( 'Body Scripts (Global)', 'wpadcenter' ); ?></label></th>
			<td>
				<textarea id="body_scripts_field" name="body_scripts_field" class="vvv_textbox"><?php echo htmlentities( stripslashes( $the_options['body_scripts'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></textarea>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'These scripts will be printed just below the opening body tag on all pages and/or posts.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="enable_scripts_field" wpadcenter_frm_tgl-val="true">
			<th scope="row"><label for="footer_scripts_field"><?php esc_attr_e( 'Footer Scripts (Global)', 'wpadcenter' ); ?></label></th>
			<td>
				<textarea id="footer_scripts_field" name="footer_scripts_field" class="vvv_textbox"><?php echo htmlentities( stripslashes( $the_options['footer_scripts'] ) ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></textarea>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'These scripts will be printed above the closing body tag on all pages and/or posts.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<?php do_action( 'wpadcenter_after_scripts_settings' ); ?>
	</table>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>
