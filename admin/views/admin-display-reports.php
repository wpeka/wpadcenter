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

	.inline-flex {
		display: flex;
		align-items: center;
	}

	.wpads-custom-reports {
		display: flex;
		justify-content: space-between;
	}

	.wpadcenter-date {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.wpadcenter-date-item {
		margin-right: 40px;
	}

	.wpadcenter-select {
		display: block;
	}

	#adgroup_select_input {
		width: 100px;
		height: 30px;
		margin: 10px;
		border: 1px solid #d8dbe0;
	}
	#adgroup_select_input:focus {
		min-width: 100px;
		width: auto;
	}

	.alert-dismissible .close {
		position: absolute;
		top: 0;
		padding: 0;
		margin-right: 4px;
	}

	.alert-dismissible .close:focus {
		outline: none;
	}

	.close {
		opacity: 0.5;
	}

	.inline-flex {
		display: flex;
		align-items: center;
	}

	.wpads-custom-reports {
		display: flex;
		justify-content: space-between;
	}

	.wpadcenter-date {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.wpadcenter-date-item {
		margin-right: 40px;
	}

	.wpadcenter-select {
		display: block;
	}

	.inline-flex {
		display: flex;
		align-items: center;
	}

	.wpads-custom-reports {
		display: flex;
		justify-content: space-between;
	}

	.wpadcenter-date {
		display: flex;
		align-items: center;
		justify-content: space-between;
	}

	.wpadcenter-date-item {
		margin-right: 40px;
	}

	.wpadcenter-select {
		display: block;
	}

	#adgroup_select_input {
		width: 100px;
		height: 30px;
		margin: 10px;
		border: 1px solid #d8dbe0;
	}
	#adgroup_select_input:focus {
		min-width: 100px;
		width: auto;
	}
	[v-cloak] {
		display: none;
	}
</style>
<div class="wrap">
<h2></h2>
<div id="reports" v-cloak>
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
							<c-data-table :fields="topTenClicksFields" :items="topTenClicksOptions">
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
							<v-select placeholder="Select Ad Group" :options="select_adgroup" label="name" @input="onSelectAdGroupChange" style="min-width: 150px;"></v-select>
						</c-card-header>
						<c-card-body>
						<c-row style="margin: 0px 10px 20px 10px;" v-if="totalAdGroupClicks != null || totalAdGroupViews !=  null || totalAdGroupCTR != null">
							<c-col md="4" sm="4" xs="4" class="wpadcenter-stats">
								<div class="adgroup-callout">
									<small class="p"><?php esc_html_e( 'Total Clicks', 'wpadcenter' ); ?></small><br>
									<strong class="h4">{{ totalAdGroupClicks }}</strong>
								</div>
							</c-col>
							<c-col md="4" sm="4" xs="4" class="wpadcenter-stats">
								<div class="adgroup-callout">
									<small class="p"><?php esc_html_e( 'Total Views', 'wpadcenter' ); ?></small><br>
									<strong class="h4">{{ totalAdGroupViews }}</strong>
								</div>
							</c-col>
							<c-col md="4" sm="4" xs="4" class="wpadcenter-stats">
								<div class="adgroup-callout">
									<small class="p"><?php esc_html_e( 'Total Click Through Rate', 'wpadcenter' ); ?></small><br>
									<strong class="h4">{{ totalAdGroupCTR.toFixed(2)+"%" }}</strong>
								</div>
							</c-col>
						</c-row>
							<c-data-table :fields="topTenCTRFields" :items="byAdGroup" sorter :items-per-page="10" pagination :key="byAdGroupsChange">
							</c-data-table>
						</c-card-body>
					</c-card>
				</c-col>
			</c-row>
		</c-tab>
		<c-tab title="Custom Reports">
			<c-card>
				<c-card-header>
					<c-card-title tag="h5"><?php esc_html_e( 'Custom Reports', 'wpadcenter' ); ?></c-card-title>
					<c-card-subtitle tag="p"><?php esc_html_e( 'Choose from the options below to generate custom reports', 'wpadcenter' ); ?></c-card-subtitle>
				</c-card-header>
				<c-card-body >
						<div class="wpads-custom-reports">
							<div class="wpadcenter-date">
							<div class="wpadcenter-date-item">
								<label><?php esc_html_e( 'Start Date:', 'wpadcenter' ); ?></label>
								<v-date-picker class="inline-block h-full" v-model="startDate">
									<template v-slot="{ inputValue, togglePopover }">
										<c-input :value="inputValue" readonly>
											<template v-slot:prepend-content>
												<svg style="height: 20px; width: 20px; fill: #0D86FF; cursor: pointer;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" @click="togglePopover">
													<path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"></path>
												</svg>
											</template>
										</c-input>
									</template>
								</v-date-picker>
							</div>
							<div class="wpadcenter-date-item">
								<label><?php esc_html_e( 'End Date:', 'wpadcenter' ); ?></label>
								<v-date-picker class="inline-block h-full" v-model="endDate">
									<template v-slot="{ inputValue, togglePopover }">
										<c-input :value="inputValue" readonly @change="onSelectAdGroupChange">
											<template v-slot:prepend-content>
												<svg style="height: 20px; width: 20px; fill: #0D86FF; cursor: pointer;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" @click="togglePopover">
													<path d="M1 4c0-1.1.9-2 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H3a2 2 0 0 1-2-2V4zm2 2v12h14V6H3zm2-6h2v2H5V0zm8 0h2v2h-2V0zM5 9h2v2H5V9zm0 4h2v2H5v-2zm4-4h2v2H9V9zm0 4h2v2H9v-2zm4-4h2v2h-2V9zm0 4h2v2h-2v-2z"></path>
												</svg>
											</template>
										</c-input>
									</template>
								</v-date-picker>
							</div>
						</div>
						<div class="wpadcenter-select" style="width: 200px;">
							<label for="wpadcenter-select-ads"><?php esc_html_e( 'Choose Ad: ', 'wpadcenter' ); ?></label>
							<v-select placeholder="Select Ad" id="wpadcenter-select-ads" label="ad_title" :options="select_ad" taggable multiple style="max-width: 200px;" ref="wpadcenter_select_ads"  @input="onAdSelection">
							</v-select>
							<input type="hidden" ref="selectad_security" name="selectad_security" value="<?php echo esc_attr( wp_create_nonce( 'selectad_security' ) ); ?>">
						</div>
						</div>
					<c-alert v-if="validationError.length" color="danger" :show.sync="currentAlertCounter" :fade="true" style="margin: 0;" close-button>{{ validationError }}</c-alert>
				</c-card-body>
			</c-card>
			<form method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>?action=export_csv" id="post_csv">
				<input type="hidden" ref="csv_data" name="csv_data" value="none" />
				<input type="hidden" name="exportcsv_security" value="<?php echo esc_attr( wp_create_nonce( 'exportcsv_security' ) ); ?>" />
			</form>
			<c-card>
				<c-card-header style="display: flex; align-items: center; justify-content: space-between;">
					<div>
						<c-card-title tag="h5"><?php esc_html_e( 'Detailed Reports', 'wpadcenter' ); ?></c-card-title>
						<c-card-subtitle tag="p"><?php esc_html_e( 'Generated Detailed Reports are displayed', 'wpadcenter' ); ?></c-card-subtitle>
					</div>
					<c-button color="info" @click="onExportCSV" :disabled="detailedReportsOptions.length === 0">Export CSV</c-button>
				</c-card-header>
				<c-card-body>
					<c-data-table :fields="detailedReportsField" :items="detailedReportsOptions" sorter :items-per-page="5" pagination></c-data-table>
					<div class="chart-container">
						<line-chart :chart-data="chartData" :options="chartOptions"></line-chart>
					</div>
				</c-card-body>
			</c-card>
		</c-tab>
	</c-tabs>
</div>
</div>