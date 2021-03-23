import { Line, mixins } from 'vue-chartjs';
const { reactiveProp } = mixins;

export default {
	extends: Line,
	mixins: [reactiveProp],
	props: ['options', 'chartdata'],

	mounted() {
		this.renderChart(this.chartData, this.options);
	},
	watch: {
		chartdata: function() {
			this.renderChart(this.chartdata, this.options);
		}
	}
}