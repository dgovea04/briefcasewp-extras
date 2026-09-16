<?php
/**
 * Extension Name: Bew Orders Extension
 * Description: Bew Orders One page checkout and WhatsApp widget.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Orders extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'beworders';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-orders.png';
     $this->title = __( 'Bew Orders', 'briefcasewp-extras' );
     $this->desc  = __( 'One Page Checkout and WhatsApp orders', 'briefcasewp-extras' );
    }
	
	/**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		      	
		//add_action( 'init', array( $this, 'bew_cart_setup' ) );
		//add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_cart_scripts' ) );		
		
    }
  	
 	public function bew_orders_setup() {
	}
	
	public function bew_orders_scripts() {		
	}
	
}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_beworders_extension' );

/**
* Add Bew Orders extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_beworders_extension( $extensions ) {
  $extensions['beworders'] = 'Bew_Orders';
  return $extensions;
}