<?php
/**
 * Extension Name: Bew Fast Checkout Extension
 * Description: Bew Woocommerce Fast Checkout widgets.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Fast_Checkout extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewfastcheckout';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-fast-checkout.png';
     $this->title = __( 'Bew Fast Checkout', 'briefcasewp-extras' );
     $this->desc  = __( 'Woocommerce Fast Checkout', 'briefcasewp-extras' );
    }
	
  /**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		      	
		//add_action( 'init', array( $this, 'bew_cart_setup' ) );
		//add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_cart_scripts' ) );
		//require BEW_EXTRAS_PATH . 'extensions/bew-extensions/extensions/inside/bew-cart/includes/modules-manager.php';	
		//add_action( 'bew_cart_load', array( $this, 'bew_cart_load_modules'), 10, 1);
		
    }
  	
 	public function bew_checkout_setup() {
	}
	
	public function bew_checkout_scripts() {		
	}
	
}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewfastcheckout_extension' );

/**
* Add Fast Checkout extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewfastcheckout_extension( $extensions ) {
  $extensions['bewfastcheckout'] = 'Bew_Fast_Checkout';
  return $extensions;
}