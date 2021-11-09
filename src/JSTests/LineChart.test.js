/**
 * @jest-environment jsdom
 */
 import * as component from '../LineChart.js';

 it('test for line chart', ()=>{

    var module = {
        renderChart(chartData,options){
            this.chartData = 'Updated chart data';
            this.options = 'Updated options';
        },
        chartData:'data',
        options:'options'
    }
     var returnedProps = component.default.props;
     expect(returnedProps).toEqual(['options', 'chartdata']);

     var boundMounted = component.default.mounted.bind(module);
    boundMounted();
    expect(module.chartData).toEqual('Updated chart data');
    expect(module.options).toEqual('Updated options');

    module.chartData = '';
    module.options = '';

    var boundChartData = component.default.watch.chartdata.bind(module);
    boundChartData();
    expect(module.chartData).toEqual('Updated chart data');
    expect(module.options).toEqual('Updated options');
 })