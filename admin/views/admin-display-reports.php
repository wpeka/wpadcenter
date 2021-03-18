<?php
/**
 * Admin reports page with Core ui vue library
 *
 * @link  https://club.wpeka.com/
 * @since 1.0.0
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin/views
 */

?>

<style>
	.c-callout {
		display: inline-block;
		text-align: left;
	}

	.card {
		padding: 0;
		max-width: 100%;
	}

	#wpwrap {
		background: #F3F6FF;
	}

	#wpcontent {
		padding-right: 20px;
	}
</style>

<div id="reports">
	<c-tabs>
		<c-tab title="Dashboard" active>
			<c-card style="width: 100%; max-width: 100%">
				<c-card-header>
					<c-card-title tag="h5"><?php esc_html_e( 'All time summary', 'wpadcenter' ); ?></c-card-title>
					<c-card-subtitle tag="p"><?php esc_html_e( 'Shows reports regarding clicks, views and CTR of all time.', 'wpadcenter' ); ?></c-card-subtitle>
				</c-card-header>
				<c-card-body>
					<c-row>
						<c-col md="4" sm="4" xs="4" class="wpadcenter-stats">
							<c-callout color="info">
								<small class="p"><?php esc_html_e( 'Total Clicks', 'wpadcenter' ); ?></small><br>
								<strong class="h4">{{ total_clicks }}</strong>
							</c-callout>
						</c-col>
						<c-col md="4" sm="4" xs="4" class="wpadcenter-stats">
							<c-callout color="info">
								<small class="p"><?php esc_html_e( 'Total Views', 'wpadcenter' ); ?></small><br>
								<strong class="h4">{{ total_impressions }}</strong>
							</c-callout>
						</c-col>
						<c-col md="4" sm="4" xs="4" class="wpadcenter-stats">
							<c-callout color="info">
								<small class="p"><?php esc_html_e( 'Total Click Through Rate', 'wpadcenter' ); ?></small><br>
								<strong class="h4">{{ total_CTR.toFixed(2)+"%" }}</strong>
							</c-callout>
						</c-col>
					</c-row>
				</c-card-body>
			</c-card>
			<c-row>
				<c-col lg="4" md="6" sm="12">
					<c-card>
					<c-card-header>
						<c-card-title tag="h5"><?php esc_html_e( 'All-Time Top 10 Clicks', 'wpadcenter' ); ?></c-card-title>
						<c-card-subtitle tag="p"><?php esc_html_e( 'Shows reports of top 10 ads as per clicks', 'wpadcenter' ); ?></c-card-subtitle>
					</c-card-header>
					<c-card-body>
						<c-data-table :fields="topTenClicksFields" :items="topTenClicksOptions" :noItemsView="{ noResults: 'no filtering results available', noItems: 'no items available' }">
						</c-data-table>
					</c-card-body>
					</c-card>
				</c-col>
				<c-col lg="4" md="6" sm="12">
					<c-card>
					<c-card-header>
						<c-card-title tag="h5"><?php esc_html_e( 'All-Time Top 10 Click Through Rate', 'wpadcenter' ); ?></c-card-title>
						<c-card-subtitle tag="p"><?php esc_html_e( 'Shows reports of top 10 ads as per CTR', 'wpadcenter' ); ?></c-card-subtitle>
					</c-card-header>
					<c-card-body>
						<c-data-table :fields="topTenCTRFields" :items="topTenCTROptions">
						</c-data-table>
					</c-card-body>
					</c-card>
				</c-col>
				<c-col lg="4" md="12" sm="12">
					<c-card>
					<c-card-header style="display: flex; align-items: center; justify-content: space-between;">
						<div>
							<c-card-title tag="h5"><?php esc_html_e( 'All-Time By Ad Group', 'wpadcenter' ); ?></c-card-title>
							<c-card-subtitle tag="p"><?php esc_html_e( 'Shows Reports of all ads related to ad-group', 'wpadcenter' ); ?></c-card-subtitle>
						</div>
						<input type="hidden" ref="adgroups_ajaxurl" name="adgroups_ajaxurl" value="<?php echo esc_url( admin_url( 'admin-ajax.php' ) ); ?>">
						<input type="hidden" ref="adgroups_security" name="adgroups_security" value="<?php echo esc_attr( wp_create_nonce( 'adgroups_security' ) ); ?>">
						<select ref="select_ad_group" @change="onSelectAdGroupChange" v-model="selected_ad_group">
							<?php
							$array = get_terms( 'wpadcenter-adgroups', array( 'hide_empty' => false ) );
							if ( is_array( $array ) ) {
								foreach ( $array as $item ) {
									echo '<option value=' . esc_attr( $item->term_id ) . '>' . esc_attr( $item->name ) . '</option>';
								}
							}
							?>
						</select>
					</c-card-header>
					<c-card-body>
						<c-data-table :fields="topTenCTRFields" :items="byAdGroup" sorter :items-per-page="10" pagination :key="byAdGroupsChange">
						</c-data-table>
					</c-card-body>
					</c-card>
				</c-col>
			</c-row>
		</c-tab>
		<c-tab title="Custom Reports">	
		</c-tab>
	</c-tabs>
</div>
