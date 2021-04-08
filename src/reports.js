import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import '@coreui/coreui/dist/css/coreui.min.css';
import 'vue-select/dist/vue-select.css';
import VCalendar from 'v-calendar';
import vSelect from 'vue-select';
import LineChart from './LineChart';

Vue.component('v-select', vSelect);
Vue.use(CoreuiVue);
Vue.use(VCalendar);
// jquery $ as j
const j = jQuery.noConflict();

// Fields for "All-time top 10 Clicks table" 
const topTenClicksFields = [
	{ key: 'Ad Title', _style:'min-width:200px' },
	{ key: 'Clicks' },
];

// Fields for "All-time top 10 CTR table"
const topTenCTRFields = [
	{ key: 'Ad Title' },
	{ key: 'Views' },
	{ key: 'Clicks' },
	{ key: 'CTR' },
];

// Global Total CLicks, impressions and CTR
let total_clicks = 0;
let total_impressions = 0;
let total_CTR = 0;
// calculate total clicks, total impressions, total CTR
if( reportsArray.length === 1 ) {
	
	total_clicks = reportsArray[0].ad_meta.total_clicks;
	total_impressions = reportsArray[0].ad_meta.total_impressions;
}
else {
	total_clicks = reportsArray.reduce(( acc, item ) => {
		return parseInt(item.ad_meta.total_clicks + acc);
	},0);
	
	total_impressions = reportsArray.reduce((acc, item) => {
		return parseInt(item.ad_meta.total_impressions + acc);
	}, 0);
}

total_CTR = (total_clicks / total_impressions) * 100;
if( isNaN(total_CTR) ) {
	total_CTR = 0.00;
}

// items/data for "All-time top 10 Clicks table"
let topTenClicksOptions = [];
for(let i = 0; i < reportsArray.length; i++) {
	let temp = {
		'Ad Title': reportsArray[i].ad_title,
		'Clicks': reportsArray[i].ad_meta.total_clicks
	}
	topTenClicksOptions.push(temp);
}
// Sort the data by clicks
topTenClicksOptions.sort((a, b) => {
	return a.Clicks < b.Clicks ? 1 : -1;
});

// slice the array to show only top 10 sorted data
topTenClicksOptions = topTenClicksOptions.slice(0,10);

// items/data for "All-time top 10 CTR table"
let topTenCTROptions = [];
for(let i = 0; i < reportsArray.length; i++) {
	let CTR = (reportsArray[i].ad_meta.total_clicks / reportsArray[i].ad_meta.total_impressions) * 100;
	if ( isNaN(CTR) ) {
		CTR = 0.00;
	}
	let temp = {
		'Ad Title': reportsArray[i].ad_title,
		'Views': reportsArray[i].ad_meta.total_impressions,
		'Clicks': reportsArray[i].ad_meta.total_clicks,
		'CTR': CTR.toFixed(2)+"%",
	}
	topTenCTROptions.push(temp);
}

// Sort the data by CTR
topTenCTROptions.sort((a, b) => {
	return parseInt(a.CTR) < parseInt(b.CTR) ? 1 : -1;
});

// slice the array to show only top 10 sorted data
topTenCTROptions = topTenCTROptions.slice(0,10);

// global returnArray for ajax calls
var returnArray = [];

// generate options for ad selection in custom reports
let select_ad = [];
select_ad = reportsArray.map((item) => {
	return {
		ad_id: item.ad_id,
		ad_title: item.ad_title
	};
});


// Fields for detailed reports
const detailedReportsField = [
	{ key: 'Ad Title' },
	{ key: 'Date' },
	{ key: 'Views' },
	{ key: 'Clicks' },
	{ key: 'CTR' },
];
var timeFormat = 'YYYY/MM/DD';

// chart data
const chartData = {
	datasets: []
}

// chart options
const chartOptions = {
	scales: {
		xAxes: [{
			type: 'time',
			time: {
				unit: 'day',
				parser: 'YYYY/MM/DD',
				tooltipFormat: 'll'
			}
		}],
		yAxes: [{
			ticks: {
				beginAtZero: true,
			}
		}]
	}
}
var reports = new Vue({
	el: '#reports',
	data: function() {
		return {
			// selected ad group - select element in dashboard page
			selected_ad_group: null, 
			// top ten field names of table ( column names ) 
			topTenClicksFields,
			topTenCTRFields,
			// rows / values
			topTenClicksOptions,
			topTenCTROptions,
			// AJAX URL
			ajax_url: null,
			// Nonce security for ajax url
			adgroups_security: null,
			// table by ad group values
			byAdGroup: [],
			byAdGroupsChange: 0,
			// total clicks, impressions , ctr in all time summary
			total_clicks,
			total_impressions,
			total_CTR,
			// custom reports start date and end date
			startDate: null,
			endDate: null,
			// select element in custom reports
			selectedAds: [],
			// ads to be selected
			select_ad,
			// validation for generating custom reports
			validationError: "",
			// detailed reports table field
			detailedReportsField,
			// detailed reports table rows/values/options
			detailedReportsOptions: [],
			// chart datasets
			chartData,
			// chart configuration options
			chartOptions,
			// total clicks, views, ctr by ad group
			totalAdGroupClicks: null,
			totalAdGroupViews: null,
			totalAdGroupCTR: null,
			chartDataSets: [],
			// alert counter in seconds
			currentAlertCounter: 10,
			// select ad group option
			select_adgroup: [{ name: 'none' }],
			// select advertisers (pro)
			select_advertiser: [],
		}
	},
	mounted: function() {
		// get necessary data from DOM
		this.ajax_url = this.$refs.adgroups_ajaxurl.value;
		this.adgroups_security = this.$refs.adgroups_security.value;
		this.selectad_security = this.$refs.selectad_security.value;
		this.selectadvertiser_security = this.$refs.hasOwnProperty('selectadvertiser_security') ? this.$refs.selectadvertiser_security.value : ''; 
		// manually setting start date and end date
		this.endDate = new Date();
		this.startDate = new Date();
		this.startDate.setMonth( this.startDate.getMonth() - 1 );
		// get ad groups from server
		j.ajax({
			type: "POST",
			url: this.ajax_url,
			data: {
				action: 'get_adgroups',
				security: this.adgroups_security,
			}
		}).done(data => {
			data = JSON.parse(data);
			this.select_adgroup = data;
		});
		// get advertisers from server
		if( this.selectadvertiser_security !== '' ) {
			j.ajax({
				type: "POST",
				url: this.ajax_url,
				data: {
					action: 'get_advertisers',
					security: this.selectadvertiser_security
				}
			}).done(data => {
				data = JSON.parse(data);
				this.select_advertiser = data;
			});
		}
	},
	methods: {
		// ajax call when select ad group is changed
		onSelectAdGroupChange(data) {
			if( data === null ) {
				this.selected_ad_group = [];
				this.byAdGroup = [];
				this.totalAdGroupCTR = null;
				this.totalAdGroupClicks = null;
				this.totalAdGroupViews = null;
				return;
			}
			this.selected_ad_group = data.term_id;
			j.ajax({
                type: "POST",
                url: this.ajax_url,
                data: {
                    action: "selected_adgroup_reports",
                    selected_ad_group: this.selected_ad_group,
                    security: this.adgroups_security,
                },
            }).done((data) => {
				// generate data to add into table also calculate totla ad group clicks, views and CTR.
                data = JSON.parse(data);
				this.totalAdGroupClicks = 0;
				this.totalAdGroupViews = 0;
                for (let i = 0; i < data.length; i++) {
                    let CTR =
                        (data[i].ad_meta.total_clicks /
                            data[i].ad_meta.total_impressions) *
                        100;
                    if (isNaN(CTR)) {
                        CTR = 0.0;
                    }
                    let temp = {
                        "Ad Title": data[i].ad_title,
                        Views: data[i].ad_meta.total_impressions,
                        Clicks: data[i].ad_meta.total_clicks,
                        CTR: CTR.toFixed(2) + "%",
                    };
					this.totalAdGroupClicks += data[i].ad_meta.total_clicks;
					this.totalAdGroupViews += data[i].ad_meta.total_impressions;
                    returnArray.push(temp);
                }
				this.totalAdGroupCTR = ( this.totalAdGroupClicks / this.totalAdGroupViews ) * 100;
				if( isNaN( this.totalAdGroupCTR ) ) {
					this.totalAdGroupCTR = 0;
				}
				this.byAdGroup = returnArray;
				returnArray = [];
            });
        },
		onAdSelection(data) {
			// check for validations
			this.selectedAds = data;
			let flag = false;
			if( this.startDate === null || this.endDate === null ) {
				this.validationError = "Start date And/or End date cannot be empty.";
				flag = true;
			}
			else if( this.startDate > this.endDate ) {
				this.validationError = "Start date cannot be greater than End date.";
				flag = true;
			}
			else if( data.length === 0 ) {
				this.validationError = "You must choose an ad to generate reports.";
				flag = true;
			}
			if ( flag ) {
				this.detailedReportsOptions = [];
				this.currentAlertCounter = 10;
				this.chartData = {
					datasets: [],
				};
				return;
			}
			this.validationError = "";
			// if no validation error call ajax to get detailed reports data from database
			j.ajax({
				type: "POST",
				url: this.ajax_url,
				data: {
					action: "selected_ad_reports",
                	selected_ad: data,
                	security: this.selectad_security,
					start_date: parseInt((this.startDate.getTime() / 1000).toFixed(0)),
					end_date: parseInt((this.endDate.getTime() / 1000).toFixed(0)),
				},
			}).done((data) => {
				// generate datasets accordingly for views and clicks
				data = JSON.parse(data);
				for( let i = 0; i < data.length; i++ ) {
					let CTR =
                        (data[i].ad_clicks /
                            data[i].ad_impressions ) *
                        100;
                    if (isNaN(CTR)) {
                        CTR = 0.0;
                    }
					let temp = {
						'Ad Title': data[i].ad_title,
						'Date': data[i].ad_date,
						'Views': parseInt(data[i].ad_impressions),
						'Clicks': parseInt(data[i].ad_clicks),
						'CTR': CTR.toFixed(2)+"%",
						'Ad id': data[i].ad_id,
					}
					returnArray.push(temp);
				}
				const data1 = JSON.parse(JSON.stringify([...returnArray]));
				returnArray.forEach(function(item) {
					delete item['Ad id'];
				});
				this.detailedReportsOptions = [...returnArray];
				returnArray = [];
				let assocColorWithId = {};
				let IDs = [];
				let outputViews = [];
				let labels = [];
				for(let i = 0; i < data1.length ; i++) {
					let color = getRandomColor();
					if( IDs.includes(data1[i]['Ad id']) ) {
						for(let j = 0; j < outputViews.length; j++) {
							if( data1[i]['Ad id'] === outputViews[j].ID ) {
								let temp = {
									y: data1[i]['Views'],
									x: data1[i]['Date'],
								}
								outputViews[j].data.push(temp);
							}
						}
					}
					else {
						let temp = {
							ID: data1[i]['Ad id'],
							label: data1[i]['Ad Title']+" Views",
							data: [{
								y: data1[i]['Views'],
								x: data1[i]['Date'],
							}],
							borderColor: color,
							borderDash: [10,5],
							fill: false,
						};
						assocColorWithId[data1[i]['Ad id']] = color;
						outputViews.push(temp);
						IDs.push(data1[i]['Ad id']);
					}

					if( ! labels.includes(data1[i]['Date']) ) {
						labels.push(data1[i]['Date']);
					}
				}
				IDs = [];
				let outputClicks = [];
				labels = [];
				for(let i = 0; i < data1.length ; i++) {
					let color = getRandomColor();
					if( IDs.includes(data1[i]['Ad id']) ) {
						for(let j = 0; j < outputClicks.length; j++) {
							if( data1[i]['Ad id'] === outputClicks[j].ID ) {
								let temp = {
									y: data1[i]['Clicks'],
									x: data1[i]['Date'],
								}
								outputClicks[j].data.push(temp);
							}
						}
					}
					else {
						let temp = {
							ID: data1[i]['Ad id'],
							label: data1[i]['Ad Title']+" Clicks",
							data: [{
								y: data1[i]['Clicks'],
								x: data1[i]['Date'],
							}],
							borderColor: assocColorWithId[data1[i]['Ad id']],
							fill: false,
						};
						outputClicks.push(temp);
						IDs.push(data1[i]['Ad id']);
					}

					if( ! labels.includes(data1[i]['Date']) ) {
						labels.push(data1[i]['Date']);
					}
				}
				var data = {
					datasets: [...outputViews, ...outputClicks],
				}
				this.chartData = data;
				this.chartDataSets = [...outputViews, ...outputClicks];
			});
		},
		onExportCSV() {
			// create csv string and send to server using POST request.
			const csvString = [
				[
					'Ad Title',
					'Date',
					'Views',
					'Clicks',
					'CTR'
				],
				...this.detailedReportsOptions.map(item => [
					item['Ad Title'],
					item['Date'],
					item['Views'],
					item['Clicks'],
					item['CTR'],
				])
			]
			.map(e => e.join(",")) 
   			.join("\n");
			this.$refs.csv_data.value = csvString;
			document.getElementById('post_csv').submit();
		},
		onAdvertiserSelection(data) {
			if( data === null ) { 
				this.select_ad = select_ad;
				return;
			}
			j.ajax({
				type: "POST",
				url: this.ajax_url,
				data: {
					action: "selected_advertiser_get_ads",
                	selected_advertiser: data,
                	security: this.selectadvertiser_security,
				},
			}).done(data => {
				data = JSON.parse(data);
				this.select_ad = data;
			});
		}
	},
	components: {
		'line-chart': LineChart,
	},
	watch: {
		// watch startdate and enddate and fire when value changes for validations.
		startDate: function(val) {
			this.onAdSelection(this.selectedAds);
		},
		endDate: function(val) {
			this.onAdSelection(this.selectedAds);
		}
	}
});
// generate random color.
let getRandomColor = function () {
    var random = Math.random();
    var exponent = --random.toExponential().split('-')[1];

    random *= Math.pow(10, exponent);

    return '#' + ( ~~(random * (1 << 24) )).toString(16);
};
