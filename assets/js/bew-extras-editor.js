var $j = jQuery.noConflict();

$j( window ).on( 'load', function() {
	"use strict";
	//Bew templates
	bewextrastemplate();
} );

$j( document ).ready( function() {
	"use strict";
	//Bew templates
	bewextrastemplate();
} );

$j( document ).ajaxComplete( function() {
	"use strict";
	//Bew templates
	bewextrastemplate();
} );

/* ==============================================
Bew Extras Templates
============================================== */	

function bewextrastemplate() {
	
	// Add activate bew on templates pop up	
	var bew_template   		= document.querySelector(".elementor-template-library-template-bewtemplates");						
	var url        			= window.location.protocol + "//" + window.location.host; 
	var pluginUrl    		= url + '/wp-admin/plugins.php';
	var bew_activate     	= "yes";
	
	//if(bew_activate  === "yes"){	
	//$j("#elementor-template-library-modal" ).addClass("briefcasewp-active");
	//}	
	
					$( '#elementor-panel-footer-responsive' ).on( 'click', function() {

					console.log("hola");	
				} );
};

