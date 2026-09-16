<?php
/**
 * Extension Name: Bew Autocomplete
 * Description: Autocomplete Address.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Autocomplete extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewautocomplete';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-autocomplete.png';
     $this->title = __( 'Bew Autocomplete', 'briefcasewp-extras' );
     $this->desc  = __( 'Add Autocomplete Address feature to checkout page', 'briefcasewp-extras' );
    }
	
	/**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
				    
		/**
		 * Begins execution of the plugin.
		 */
		 function run_bewaa(){
			$plugin = new Bewaa();
			$plugin->run();
		}

		/**
		 * The core plugin class that is used to define internationalization,
		 * admin-specific hooks, and public-facing site hooks.
		 */
		require plugin_dir_path( __FILE__ ) . 'includes/class-bewaa.php';
		run_bewaa();
		
    }
  	
 	public function bew_autocomplete_setup() {
	}
	
	public function bew_autocomplete_enqueue_styles() {			
	}
	
	public function bew_autocomplete_scripts() {	
	}
	
}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewautocomplete_extension' );

/**
* Add Bew Autocomplete extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewautocomplete_extension( $extensions ) {
  $extensions['bewautocomplete'] = 'Bew_Autocomplete';
  return $extensions;
}