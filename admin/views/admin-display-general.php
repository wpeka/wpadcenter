<?php
/**
 * Provide a admin area view for the general tab.
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
<div class="wpadcenter-tab-content" data-id="<?php echo esc_attr( $target_id ); ?>">
	<table class="form-table">
		<?php do_action( 'wpadcenter_before_general_settings' ); ?>
		<tr valign="top">
			<th scope="row"><label for="auto_refresh_field"><?php esc_attr_e( 'Auto Refresh (Global)', 'wpadcenter' ); ?></label></th>
			<td>
				<input type="radio" id="auto_refresh_field_yes" name="auto_refresh_field" wpadcenter_frm_tgl-target="auto_refresh_field" class="wpadcenter_form_toggle" value="true" <?php echo ( true === $the_options['auto_refresh'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Enable', 'wpadcenter' ); ?>
				<input type="radio" id="auto_refresh_field_no" name="auto_refresh_field" wpadcenter_frm_tgl-target="auto_refresh_field" class="wpadcenter_form_toggle" value="false" <?php echo ( false === $the_options['auto_refresh'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Disable', 'wpadcenter' ); ?>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Auto refresh all ads on pages.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="auto_refresh_field" wpadcenter_frm_tgl-val="true">
			<th scope="row"><label for="transition_effect_field"><?php esc_attr_e( 'Transition Effect', 'wpadcenter' ); ?></label></th>
			<td>
				<select name="transition_effect_field" class="vvv_combobox">
					<?php $this->print_combobox_options( $this->get_transition_effect_options(), $the_options['transition_effect'] ); ?>
				</select>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Transition effect for ads, if Auto Refresh is enabled.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="auto_refresh_field" wpadcenter_frm_tgl-val="true">
			<th scope="row"><label for="transition_speed_field"><?php esc_attr_e( 'Transition Speed', 'wpadcenter' ); ?></label></th>
			<td>
				<input type="number" step="100" min="500" max="60000" name="transition_speed_field" value="<?php echo esc_html( stripslashes( $the_options['transition_speed'] ) ); ?>" />
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Speed in milliseconds for ad transitions, if Auto Refresh is enabled.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="auto_refresh_field" wpadcenter_frm_tgl-val="true">
			<th scope="row"><label for="transition_delay_field"><?php esc_attr_e( 'Transition Delay', 'wpadcenter' ); ?></label></th>
			<td>
				<input type="number" step="100" min="1000" max="60000" name="transition_delay_field" value="<?php echo esc_html( stripslashes( $the_options['transition_delay'] ) ); ?>" />
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Delay in milliseconds between ad transitions, if Auto Refresh is enabled.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top">
			<th scope="row"><label for="hide_ads_logged"><?php esc_attr_e( 'Hide all ads for logged users (Global)', 'wpadcenter' ); ?></label></th>
			<td>
				<input type="radio" name="hide_ads_logged_field" id="hide_ads_logged_yes" class="wpadcenter_form_toggle" value="true" <?php echo ( true === $the_options['hide_ads_logged'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Enable', 'wpadcenter' ); ?>
				<input type="radio" name="hide_ads_logged_field" id="hide_ads_logged_no" class="wpadcenter_form_toggle" value="false" <?php echo ( false === $the_options['hide_ads_logged'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Disable', 'wpadcenter' ); ?>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Hides/Shows the ads for logged in users', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" >
			<th scope="row"><label for="adblock_detector_field"><?php esc_attr_e( 'Adblock Detector', 'wpadcenter' ); ?></label></th>
			<td>
				<input type="radio" id="adblock_detector_field_yes" name="adblock_detector_field"  value="true" wpadcenter_frm_tgl-target="adblock_detector_field" class="wpadcenter_form_toggle" <?php echo ( true === $the_options['adblock_detector'] ) ? ' checked="checked"' : ''; ?> /><?php esc_attr_e( 'Enable', 'wpadcenter' ); ?>
				<input type="radio" id="adblock_detector_field_no" name="adblock_detector_field"  value="false" wpadcenter_frm_tgl-target="adblock_detector_field" class="wpadcenter_form_toggle" <?php echo ( false === $the_options['adblock_detector'] ) ? ' checked="checked" ' : ''; ?> /><?php esc_attr_e( 'Disable', 'wpadcenter' ); ?>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Enable adblock on all pages and/or posts.', 'wpadcenter' ); ?>
			</td>
		</tr>
		<tr valign="top" wpadcenter_frm_tgl-id="adblock_detector_field" wpadcenter_frm_tgl-val="true">
		<th scope="row"><label for="adblock_detected_message_field"><?php esc_attr_e( 'Adblock Detected Message', 'wpadcenter' ); ?></label></th>
			<td>
				<textarea name="adblock_detected_message_field" value="<?php echo esc_html( stripslashes( $the_options['adblock_detected_message'] ) ); ?>" rows="5" cols="50"><?php echo esc_html( stripslashes( $the_options['adblock_detected_message'] ) ); ?></textarea>
				<span class="wpadcenter_form_help"><?php esc_attr_e( 'Message displayed on the adblocker detected  pop up.', 'wpadcenter' ); ?>
			</td>
		</tr>

		<?php do_action( 'wpadcenter_after_general_settings' ); ?>
	</table>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>
