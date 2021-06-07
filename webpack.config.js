/**
 * Config file for webpack.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package Wpadcenter
 */
const path = require('path');
const MiniCssExtractPlugin =require('mini-css-extract-plugin');
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");

var vueconfig = {
    entry: {
        main: './src/index.js',
        gettingstarted: './src/getting-started.js',
        adscheduler: './src/adscheduler.js',
        reports: './src/reports.js',
        weeklyStats: './src/weekly-stats.js',
        mascot: './src/mascot.js'
    },
    output: {
        path: path.resolve(__dirname, 'admin/js/vue'),
        filename: 'wpadcenter-admin-[name].js'
    },
    mode: 'development',
    resolve: {
        alias: {
            'vue$': 'vue/dist/vue.esm.js'
        }
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: ['style-loader', 'css-loader']
            }
        ]
    }
}
var sassconfig={
    entry: {
        
        public: './src/sass/wpadcenter-public.scss'
    },
    output: {
        path: path.resolve(__dirname),
    },
    mode: 'development',
  
    module: {
        rules: [
            
            {test: /\.scss$/,
        use: [
          MiniCssExtractPlugin.loader,
          'css-loader',
          'sass-loader',
        ]
    }
        ]
    },
    plugins: [
        new FixStyleOnlyEntriesPlugin(),

        new MiniCssExtractPlugin({
            filename:'public/css/wpadcenter-[name].css'
        })
    ]
}
module.exports = [
    vueconfig,sassconfig      
];