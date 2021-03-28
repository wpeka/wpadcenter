<?php
/**
 * Admin Settings HTML with Core ui vue library
 *
 * @link  https://club.wpeka.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin/views
 */

$the_options = get_option( WPADCENTER_SETTINGS_FIELD );
$data_obj    = \Wpeka\Adcenter\Wpadcenter_Adsense::get_instance();
$data        = $data_obj->get_saved_accounts();
$nonce       = wp_create_nonce( 'wpeka-google-adsense' );
$auth_url    = \Wpeka\Adcenter\Wpadcenter_Google_Api::get_auth_url();

?>
<style>
	[v-cloak] {
		display: none;
	}
</style>
<div id="app" v-cloak>
	<c-tabs ref="active_tab">
		<c-tab title="General" active href="#general">
		<?php do_action( 'wpadcenter_before_general_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Tracking Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="wpadcenter-margin-mod">
						<input type="hidden" ref="roles_security" name="roles_security" value="<?php echo esc_attr( wp_create_nonce( 'roles_security' ) ); ?>">
						<input type="hidden" ref="roles_ajaxurl" name="roles_ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
						<label for="wpadcenter-select-roles"><?php esc_html_e( 'Roles to exclude from tracking', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Select certain roles to exclude from tracking the ads', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<input type="hidden" ref="roles_selected" v-model="roles_selected" name="roles_selected_field" value="<?php echo esc_html( stripslashes( $the_options['roles_selected'] ) ); ?>">
					</div>
					<v-select id="wpadcenter-select-roles" :options="roles" taggable multiple v-model="roles_selected">
					</v-select>
				</c-card-body>
			</c-card>
			<c-card>
				<c-card-header><?php esc_html_e( 'Statistics Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>

					<label for="inline-form-trim_stats" class="wpadcenter-margin-mod"><?php esc_html_e( 'Trim statistics older than (in months)', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="{content:'<?php esc_html_e( 'Automatically clean the statistics database records older than a set point. Setting this to 0 will disable it.', 'wpadcenter' ); ?>',placement:'top'}" color="primary" name="cib-google-keep"></c-icon>
					<c-input type="number" min="0" name="trim_stats_field" value="<?php echo esc_html( stripslashes( $the_options['trim_stats'] ) ); ?>" />
				</c-card-body>
			</c-card>
			<?php do_action( 'wpadcenter_after_general_settings' ); ?>
		</c-tab>
		<c-tab title="Scripts" href="#scripts">
		<?php do_action( 'wpadcenter_before_scripts_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Scripts Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<div class="ad-toggle">
						<label for="inline-form-enable_scripts"><?php esc_html_e( 'Enable Scripts', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Enable scripts on all pages and/or posts.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<input type="hidden" name="enable_scripts_field" v-model="enable_scripts">
					</div>
					<c-switch ref="enable_scripts" v-model="enable_scripts" id="inline-form-enable_scripts" variant="3d" size="sm" color="dark" <?php checked( $the_options['enable_scripts'] ); ?> v-on:update:checked="enable_scripts = !enable_scripts"></c-switch>
					<div class="enable_scripts_enabled" v-show="enable_scripts">
						<label for="header_scripts_field" class="form-label header-scripts">Header Scripts</label><c-icon  v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the head section on all pages and/or posts.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<textarea id="header_scripts_field" :placeholder="scriptInfo" name="header_scripts_field" class="form-control" rows="6"><?php echo esc_html( stripslashes( $the_options['header_scripts'] ) ); ?></textarea>
						<label for="body_scripts_field" class="form-label">Body Scripts</label><c-icon  v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the body section on all pages and/or posts.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<textarea id="body_scripts_field" :placeholder="scriptInfo" name="body_scripts_field" class="form-control" rows="6"><?php echo esc_html( stripslashes( $the_options['body_scripts'] ) ); ?></textarea>
						<label for="footer_scripts_field" class="form-label">Footer Scripts</label><c-icon  v-c-tooltip="'<?php esc_html_e( 'These scripts will be printed in the footer section on all pages and/or posts.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
						<textarea id="footer_scripts_field" :placeholder="scriptInfo" name="footer_scripts_field" class="form-control" rows="6"><?php echo esc_html( stripslashes( $the_options['footer_scripts'] ) ); ?></textarea>
					</div>
				</c-card-body>
			</c-card>
			<?php do_action( 'wpadcenter_after_scripts_settings' ); ?>
		</c-tab>
		<c-tab title="ads.txt" href="#adstxt">
			<c-card>
				<?php do_action( 'wpadcenter_before_ads_txt_settings' ); ?>
				<c-card-header><?php esc_html_e( 'Ads.txt Settings', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
				<div class="ad-toggle">
					<input type="hidden" name="ads_txt_tab" value="0" ref="ads_txt_tab">
					<input type="hidden" name="ads_txt_security" value="<?php echo esc_attr( wp_create_nonce( 'check_ads_txt_problems' ) ); ?>">
					<input type="hidden" name="ads_txt_replace_security" value="<?php echo esc_attr( wp_create_nonce( 'check_ads_txt_replace' ) ); ?>">
					<input type="hidden" name="ads_txt_ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
					<label for="inline-form-enable_ads_txt"><?php esc_html_e( 'Enable ads.txt', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Ads.txt or “Authorized Digital Sellers” is a technical specification developed by the IAB to combat ad fraud. Enable this option to create/update ads.txt file.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
					<input type="hidden" name="enable_ads_txt_field" v-model="enable_ads_txt">
				</div>
				<c-switch ref="enable_ads_txt" v-model="enable_ads_txt" id="inline-form-enable_ads_txt" variant="3d" size="sm" color="dark" <?php checked( $the_options['enable_ads_txt'] ); ?> v-on:update:checked="onChangeEnableAdsTxt"></c-switch>
				<div class="enable_ads_txt_enabled" v-show="enable_ads_txt">
					<label for="ads_txt_content" class="ads-txt-label"><?php esc_html_e( 'Content', 'wpadcenter' ); ?></label><c-icon  v-c-tooltip="'<?php esc_html_e( 'Additional records to add to the file, one record per line.', 'wpadcenter' ); ?>'" color="primary" name="cib-google-keep"></c-icon>
					<textarea id="ads_txt_content" name="ads_txt_content_field" class="form-control" rows="6" placeholder="google.com, pub-0000000000000000, DIRECT, f08c47fec0942fa0"><?php echo esc_html( stripslashes( $the_options['ads_txt_content'] ) ); ?></textarea>
					<c-spinner class="ads_txt_spinner" color="dark" grow></c-spinner><span class="ads_txt_problems"></span></td>
					<input type="button" class="button" name="check_ads_txt_problems" value="Check for Problems" id="check_ads_txt_problems" />
				</div>
				</c-card-body>
				<?php do_action( 'wpadcenter_after_ads_txt_settings' ); ?>
			</c-card>
		</c-tab>
		<c-tab title="Import From AdSense" href="#adsense">
		<?php do_action( 'wpadcenter_before_adsense_settings' ); ?>
			<c-card>
				<c-card-header><?php esc_html_e( 'Connect to AdSense', 'wpadcenter' ); ?></c-card-header>
				<c-card-body>
					<?php if ( ! empty( $data['accounts'] ) && is_array( $data['accounts'] ) ) : ?>
						<select name="gadsense_account_id">
							<?php foreach ( $data['accounts'] as $key => $account ) { ?>
								<option value="<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $key ); ?></option>
							<?php } ?>
						</select>

					<?php else : ?>
						<label for="mapi-code" class="wpadcenter-label-margin-bottom"><?php esc_html_e( 'Token', 'wpadcenter' ); ?></label>			
						<c-input type="text" id="mapi-code" value="" placeholder="<?php esc_html_e( 'Copy and paste the token after you have clicked connect to AdSense', 'wpadcenter' ); ?>"></c-input>
						<p class="wpadcenter-p-margin-mod"></p>
						<div class="token-submit">
							<button class="button init-gauthentication"><?php esc_html_e( 'Connect to AdSense', 'wpadcenter' ); ?></button>
								<button id="mapi-confirm-code" class="button">
									<?php esc_html_e( 'Submit Token', 'wpadcenter' ); ?>
								</button>
							<span class="spinner"></span>
						</div>
						<p class="wpadcenter-p-margin-mod"><?php esc_html_e( 'This will open another window. Please allow access to your AdSense account. Copy the text you get at the end and come back here.', 'wpadcenter' ); ?></p>
					<?php endif; ?>
				</c-card-body>
			</c-card>
			<?php do_action( 'wpadcenter_after_adsense_settings' ); ?>
		</c-tab>
	</c-tabs>
	<?php
	require 'admin-display-save-button.php';
	?>
</div>

<script type="text/javascript">
	if ('undefined' == typeof window.AdsenseGAPI) {
		AdsenseGAPI = {};
	}
	AdsenseGAPI.nonce = '<?php echo esc_html( $nonce ); ?>';
	AdsenseGAPI.oAuth2 = '<?php echo $auth_url; // phpcs:ignore WordPress.Security.EscapeOutput ?>';
</script>
