/**
 * Config file for webpack.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package Wpadcenter
 */
const path = require('path');

module.exports = {
    entry: {
        main: './src/index.js',
        gettingstarted: './src/getting-started.js',
        adscheduler: './src/adscheduler.js'
    },
    output: {
        path: path.resolve(__dirname, 'admin/js'),
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