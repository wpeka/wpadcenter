/**
 * Component to select ad to load
 *
 * @package    Wpadcenter
 * @subpackage Wpadcenter/admin
 * @author     WPEka Club <support@wpeka.com>
 */

(function($){
	$( '#adsense-adunits button' ).click(
		function(e){
			e.preventDefault();
			var button = $( e.target );

			$.ajax(
				{
					url:ajaxurl,
					type:'POST',
					data : {
						action: 'adsense_load_adcode',
						_wpnonce: AdsenseGAPI.nonce,
						adunit:button.attr( 'data-unitid' )
					},
					success:function(data){

						button.text( 'Loaded' )
						$( '#wpadcenter-google-adsense-code' ).text( data.message );
					},
					error:function(request, status, error){
						alert( error );
					}

				}
			)

		}
	)
}(jQuery));
