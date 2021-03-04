<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin/partials
 */

?>

<script type="text/javascript">
	var wpadcenter_settings_success_message='<?php echo esc_attr__( 'Settings updated.', 'wpadcenter' ); ?>';
	var wpadcenter_settings_error_message='<?php echo esc_attr__( 'Unable to update Settings.', 'wpadcenter' ); ?>';
</script>
<div style="clear:both;"></div>
<div class="wrap">
	<div class="nav-tab-wrapper wp-clearfix wpadcenter-tab-head">
		<?php
		$tab_head_arr = apply_filters(
			'wpadcenter_tab_head_arr',
			array(
				'wpadcenter-general' => __( 'General', 'wpadcenter' ),
				'wpadcenter-scripts' => __( 'Scripts', 'wpadcenter' ),
				'wpadcenter-ads-txt' => __( 'ads.txt', 'wpadcenter' ),
				'wpadcenter-adsense' => __( 'Import From AdSense', 'wpadcenter' ),
			)
		);
		Wpadcenter::wpadcenter_generate_settings_tabhead( $tab_head_arr );
		?>
	</div>
	<div class="wpadcenter_settings_left">
		<div class="wpadcenter-tab-container">
			<?php
			$display_views = array(
				'wpadcenter-general' => 'admin-display-general.php',
				'wpadcenter-scripts' => 'admin-display-scripts.php',
				'wpadcenter-ads-txt' => 'admin-display-ads-txt.php',
				'wpadcenter-adsense' => 'admin-display-adsense.php',
			);
			?>
			<form method="post" action="
			<?php
			if ( isset( $_SERVER['REQUEST_URI'] ) ) {
				echo esc_url_raw( wp_unslash( $_SERVER['REQUEST_URI'] ) ); }
			?>
			" id="wpadcenter_settings_form">
				<input type="hidden" name="wpadcenter_update_action" value="" id="wpadcenter_update_action" />
				<?php
				// Set nonce.
				if ( function_exists( 'wp_nonce_field' ) ) {
					wp_nonce_field( 'wpadcenter-update-' . WPADCENTER_SETTINGS_FIELD );
				}
				foreach ( $display_views as $target_id => $value ) {
					$display_view = plugin_dir_path( WPADCENTER_PLUGIN_FILENAME ) . 'admin/views/' . $value;
					if ( file_exists( $display_view ) ) {
						include $display_view;
					}
				}
				do_action( 'wpadcenter_settings_form' );
				?>
			</form>
		</div>
	</div>
	<div class="wpadcenter_settings_right"></div>
</div>
