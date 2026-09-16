<?php
/**
 * Extension Name: Bew Fullpage Extension
 * Description: Get compatibility for Themify product filter plugin.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Fullpage extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewfullpage';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-fullpage.png';
     $this->title = __( 'Bew Fullpage', 'briefcasewp-extras' );
     $this->desc  = __( 'Fullpage scrolling websites on elementor with the new bew fullpage widget.', 'briefcasewp-extras' );
    }
	
	 /**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		
		// Deregister elementor swiper 		
		add_action( 'elementor/frontend/after_register_scripts', function() {
			wp_deregister_script( 'swiper' );	
		} );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'bew_fullpage_styles' ) );
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'bew_fullpage_scripts' ) );
			
    }
  		
	public function bew_fullpage_styles() {
		
		// Add Fullpage Styles
		wp_enqueue_style( 'bew-fullpage', plugins_url( '/assets/css/bew-fullpage-total.css', __FILE__ ), array());
		
	}
	
	public function bew_fullpage_scripts() {
		
		// Add Fullpage Scripts		
		wp_register_script( 'jquery-slimscroll', plugins_url( '/assets/fullpage/js/jquery.slimscroll.min.js', __FILE__ ), [ 'jquery'], false, true );
		wp_register_script( 'jquery-easings', plugins_url( '/assets/fullpage/js/jquery.easings.min.js', __FILE__ ), [ 'jquery'], false, true);
		wp_register_script( 'jquery-pseudo', plugins_url( '/assets/fullpage/js/jquery.pseudo.js', __FILE__ ), [ 'jquery'], false, true );
		wp_register_script( 'bew-fullpage', plugins_url( '/assets/js/bew-fullpage.js', __FILE__ ), [ 'jquery'], false, true );		
		
		if(	wp_script_is( 'bew-extras-scripts', 'registered')  && (get_option('briefcasewp_extras_scripts') == 0 ) ) {		
			wp_register_script( 'swiper', plugins_url( '/assets/libs/minified/third-party/js/swiper.min.js', __FILE__ ), array( 'jquery', 'bew-extras-scripts'), '5.4.1', true);			
		}else{
			wp_register_script( 'swiper', plugins_url( '/assets/libs/minified/third-party/js/swiper.min.js', __FILE__ ), array( 'jquery'), '5.4.1', true);
		}
				
		if( get_option('fullpage_parallax') == 0 ) { 
		 wp_register_script( 'scrolloverflow', plugins_url( '/assets/fullpage/js/scrolloverflow.min.js', __FILE__ ), [ 'jquery'], false, true );
		 wp_register_script( 'jquery-fullpage', plugins_url( '/assets/fullpage/js/fullpage.js', __FILE__ ), [ 'jquery-slimscroll', 'jquery-easings', 'scrolloverflow'], false, true );
		}		
	
		wp_register_script( 'fullpage-menu', plugins_url( '/assets/js/fullpage-menu.js', __FILE__ ), [ 'jquery'], false, true );	
		
	}

}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewfullpage_extension' );

/**
* Add Bew Wpf Filte extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewfullpage_extension( $extensions ) {
  $extensions['bewfullpage'] = 'Bew_Fullpage';
  return $extensions;
}