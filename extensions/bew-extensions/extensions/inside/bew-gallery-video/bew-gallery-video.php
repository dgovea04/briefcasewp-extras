<?php
/**
 * Extension Name: Bew Gallery Video Extension
 * Description: Embed video on woocommerce gallery.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Gallery_Video extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewgalleryvideo';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-gallery-video.png';
     $this->title = __( 'Bew Gallery Video', 'briefcasewp-extras' );
     $this->desc  = __( 'Embed video on woocommerce gallery', 'briefcasewp-extras' );	
    }
	
	 /**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		
		if ( is_admin() ) {		
			require_once( BEW_EXTRAS_EXTENSIONS_PATH . '/inside/bew-gallery-video/inc/admin/admin.php' );
		} else {
			//require_once( BEW_EXTRAS_EXTENSIONS_PATH . '/inside/bew-gallery-video/inc/front/front.php' );
		}
		add_action( 'init', array( $this, 'bew_gallery_setup' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_gallery_scripts' ) );
		
    }
  	
 	public function bew_gallery_setup() {
		
	}
	
	public function bew_gallery_scripts() {
	
		// Register script				
		//wp_register_script( 'wpf', plugins_url( '/assets/js/wpf-public.js', __FILE__ ), array( 'jquery' ), false, true );	

		
	}

}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewgalleryvideo_extension' );

/**
* Add Bew Gallery Video extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewgalleryvideo_extension( $extensions ) {
  $extensions['bewgalleryvideo'] = 'Bew_Gallery_Video';
  return $extensions;
}