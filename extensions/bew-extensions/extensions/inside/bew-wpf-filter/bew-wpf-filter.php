<?php
/**
 * Extension Name: Bew Wpf Filter Extension
 * Description: Get compatibility for Themify product filter plugin.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Wpf_Filter extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewwpffilter';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-wpf-filter.png';
     $this->title = __( 'Bew Wpf Filter', 'briefcasewp-extras' );
     $this->desc  = __( 'Get compatibility for Themify product filter plugin', 'briefcasewp-extras' );
    }
	
	 /**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		      	
		add_action( 'init', array( $this, 'bew_wpf_setup' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_wpf_scripts' ) );
		
    }
  	
 	public function bew_wpf_setup() {
				
		// Deregister Wpf JS
		if( class_exists( 'WPF_Public' ) ) {
			add_action( 'wp_footer', function() {
				//wp_deregister_script( 'wpf' );	
			} );
		}
		
	}
	
	public function bew_wpf_scripts() {
		
		// Deregister Wpf JS
		if( class_exists( 'WPF_Public' ) ) {
			add_action( 'wp_footer', function() {
				//wp_deregister_script( 'wpf' );	
			} );
		}
	
		// Register wpf for briefcasewp compatibility
		if( class_exists( 'WPF_Public' ) ) {		
		wp_register_script( 'wpf', plugins_url( '/assets/js/wpf-public.js', __FILE__ ), array( 'jquery' ), false, true );	
		}
		
	}

}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewwpffilter_extension' );

/**
* Add Bew Wpf Filte extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewwpffilter_extension( $extensions ) {
  $extensions['bewwpffilter'] = 'Bew_Wpf_Filter';
  return $extensions;
}