<?php
/**
 * Extension Name: Bew Delivery Extension
 * Description: Get compatibility for Order Delivery Date and Time plugin.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Delivery extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewdelivery';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-delivery.png';
     $this->title = __( 'Bew Delivery', 'bew-extras' );
     $this->desc  = __( 'Get compatibility for Order Delivery Date and Time plugin', 'bew-extras' );
    }
	
	 /**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		      	
		add_action( 'init', array( $this, 'bew_thw_setup' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_thw_scripts' ) );
		
    }
  	
 	public function bew_thw_setup() {
				
		// Deregister  JS
		if( class_exists( 'THWDTP_Public' ) ) {
			add_action( 'wp_footer', function() {
				//wp_deregister_script( 'wpf' );	
			} );
		}
		
	}
	
	public function bew_thw_scripts() {
		
		// Deregister  JS
		if( class_exists( 'THWDTP_Public' ) ) {
			add_action( 'wp_footer', function() {
				//wp_deregister_script( 'wpf' );	
			} );
		}
	
		// Register wpf for briefcasewp compatibility
		if( class_exists( 'THWDTP_Public' ) ) {		
		wp_register_script( 'thwdtp-public-script', plugins_url( '/assets/js/thwdtp-public.min.js', __FILE__ ), array( 'jquery' ), false, true );	
		}
		
	}

}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewdelivery_extension' );

/**
* Add Bew Wpf Filte extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewdelivery_extension( $extensions ) {
  $extensions['bewdelivery'] = 'Bew_Delivery';
  return $extensions;
}