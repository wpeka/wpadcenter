/**
 * Javascript file for admin section.
 *
 * @link  https://wpadcenter.com/
 * @since 1.0.0
 *
 * @package Wpadcenter
 */
(function ($) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */
	

	
	$(document).ready(function(){
		if( undefined !== wpadcenter_render_metaboxes ){

			let metaboxes = [];
			let ad_types = {};

			let ad_meta_relation= wpadcenter_render_metaboxes[0];

			let current_ad_type=wpadcenter_render_metaboxes[1];

			for (let [key, val] of Object.entries(ad_meta_relation)) {

				ad_types[key] = val.active_meta_box;
				metaboxes.push(...val.active_meta_box);

			}


			metaboxes = _.uniq(metaboxes);

			change_active_metaboxes( current_ad_type );


			$('#ad-type').change(function () {
					const selected_ad = $("#ad-type :selected").val();
					change_active_metaboxes(selected_ad);		
		
				}
			);

			
			function change_active_metaboxes(selected_ad){

				const active_metaboxes = ad_types[selected_ad];
				metaboxes.forEach((metabox) => {
					if (active_metaboxes.includes(metabox)) {
	
						// Show this metabox and return
						$(`#${metabox}`).show();
	
					}
					else {
						// Hide this metabox
						$(`#${metabox}`).hide();
	
					}
				});

			}
			
		}
		
	});



})(jQuery);
