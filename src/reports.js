import Vue from 'vue';
import CoreuiVue from '@coreui/vue';
import '@coreui/coreui/dist/css/coreui.min.css';
Vue.use(CoreuiVue);

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

var reports = new Vue({
	el: '#reports',
	data: function() {
		return {
			selected_ad_group: null, 
			topTenClicksFields,
			topTenCTRFields,
			topTenClicksOptions,
			topTenCTROptions,
			ajax_url: null,
			adgroups_security: null,
			byAdGroup: [],
			byAdGroupsChange: 0,
			total_clicks,
			total_impressions,
			total_CTR,
		}
	},
	mounted: function() {
		// get necessary data from DOM
		this.selected_ad_group = this.$refs.select_ad_group.value;
		this.ajax_url = this.$refs.adgroups_ajaxurl.value;
		this.adgroups_security = this.$refs.adgroups_security.value;
	},
	methods: {
		// ajax call when select ad group is changed
		onSelectAdGroupChange() {
			j.ajax({
                type: "POST",
                url: this.ajax_url,
                data: {
                    action: "selected_adgroup_reports",
                    selected_ad_group: this.selected_ad_group,
                    security: this.adgroups_security,
                },
            }).done((data) => {
                data = JSON.parse(data);
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
                    returnArray.push(temp);
                }
				this.byAdGroup = returnArray;
				returnArray = [];
            });
        },
	}
});