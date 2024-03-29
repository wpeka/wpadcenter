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
 
         adtypes:'./src/gutenberg-blocks/ad-types/ad-types-main.js'


 
     },
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
 