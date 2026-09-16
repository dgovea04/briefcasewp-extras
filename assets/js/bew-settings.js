var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	// Bew Settings
	bewsettings();
} );

/* ==============================================
Bew Settings Manager
============================================== */	

function bewsettings() {
					
		// Event set default checkout fields
		$j(".reset-default-checkout").click(function(){			
					
			//Send the ajax request			
			var data = {
			'action': 'default_bew_checkout_fields', //the function in php functions to call
			'set_bew_checkout_fields':'yes'		
			};		
			//send data to the php file admin-ajax which was stored in the variable ajaxurl
			$j.post(admintEndAjax.ajaxurl, data, function( response ) {
				console.log(response );
				$j(".reset-default-checkout").addClass('done').text('Done');  
			});	
		
		});
	
};
