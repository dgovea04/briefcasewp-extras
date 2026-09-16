
jQuery( window ).on( 'load', function() {
	"use strict";
	// Bew always visible mode
	fullpagemenu();
} );

jQuery( document ).ajaxComplete( function() {
	"use strict";
	// Bew always visible mode
	fullpagemenu();
} );

// Make sure you run this code under Elementor..
jQuery( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/bew-fullpage.default', function() {
		"use strict";
	// Bew always visible mode
	fullpagemenu();
	});
	} );
	

/* ==============================================
WOOCOMMERCE ALWAYS VISIBLE MODE TOP
============================================== */	


function fullpagemenu() {
	
	// Hide Menu fullpage.				
	jQuery(window).scroll(function () {
		jQuery('#menu-fullpage').addClass('hide-logo');
	});	

};			
				