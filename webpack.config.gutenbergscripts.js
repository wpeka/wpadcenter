/**
 * Config file for webpack.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package Wpadcenter
 */
 const path = require('path');

 const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );
 
 module.exports = {
     ...defaultConfig,
     entry: {
 
         singlead:'./src/gutenberg-blocks/single-ad/main.js',
         adgroup:'./src/gutenberg-blocks/adgroup/adgroup-main.js',
         randomad:'./src/gutenberg-blocks/random-ad/random-ad-main.js'


 
     },
     presets: ['@babel/preset-env', '@babel/preset-react'],
     output: {
         path: path.resolve(__dirname, 'admin/js/gutenberg-blocks'),
         filename: 'wpadcenter-gutenberg-[name].js'
     },
     module: {
         ...defaultConfig.module,
         rules: [
             ...defaultConfig.module.rules,
 
         ],
     }
 };
 