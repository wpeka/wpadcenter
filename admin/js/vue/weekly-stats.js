/*global returnArray*/

// chart data
const chartData = {
	datasets: [],
};

// chart options
const chartOptions = {
	scales: {
		xAxes: [ {
			type: 'time',
			time: {
				unit: 'day',
				parser: 'YYYY/MM/DD',
				tooltipFormat: 'll',
			},
		} ],
		yAxes: [ {
			ticks: {
				beginAtZero: true,
			},
		} ],
	},
	responsive: true,
	maintainAspectRatio: false,
};
// calculate datasets from localized data
let totalClicks = 0;
let totalViews = 0;

// initialize dataset
let tempClicks = {
	ID: returnArray[0].ad_id,
	label: 'Clicks',
	fill: false,
	data: [],
	borderColor: '#4198D7',
};

let tempViews = {
	ID: returnArray[0].ad_id,
	label: 'Views',
	fill: false,
	data: [],
	borderColor: '#6F4E7C',
};

// get dates
let dates = returnArray.splice( returnArray.length - 1, 1 );
dates = dates.flat();

// loop to add datasets
returnArray.forEach( function( item, index ) {
	dates = dates.filter( ( date ) => {
		return date !== item.ad_date;
	} );
	let temp1 = {
		x: item.ad_date,
		y: item.ad_clicks,
	};
	let temp2 = {
		x: item.ad_date,
		y: item.ad_impressions,
	};
	totalClicks += parseInt( item.ad_clicks );
	totalViews += parseInt( item.ad_impressions ),
	tempClicks.data.push( temp1 );
	tempViews.data.push( temp2 );
} );

// loop to add dates having no data in database
dates.forEach( function( item ) {
	let temp1 = {
		x: item,
		y: 0,
	};
	let temp2 = {
		x: item,
		y: 0,
	};
	tempClicks.data.push( temp1 );
	tempViews.data.push( temp2 );
} );

// sort the data by date
tempViews.data.sort( ( a, b ) => {
	return new Date( b.x ) - new Date( a.x );
} );
tempClicks.data.sort( ( a, b ) => {
	return new Date( b.x ) - new Date( a.x );
} );

// push data to datasets array of chartjs
chartData.datasets.push( tempClicks, tempViews );
let totalCTR = ( totalClicks / totalViews ) * 100;
if ( isNaN( totalCTR ) ) {
	totalCTR = 0.00;
}
totalCTR = totalCTR.toFixed( 2 ) + '%';

// Vue Component
var weeklyStats = new Vue( {
	el: '#wpadcenter-weekly-stats',
	data() {
		return {
			chartData,
			chartOptions,
			totalClicks,
			totalViews,
			totalCTR,
		};
	},
} );
