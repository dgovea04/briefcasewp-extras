<?php
/**
 * Extension Name: Bew Blocks Builder Extension
 * Description: Bew Blocks Builder widgets.
 */

if( ! defined( 'ABSPATH' ) ) {
    return;
}

// Check if the abstract class exists. If not, don't do anything. 
// You can define this class after a hook such as plugins_loaded to be sure.
if( ! class_exists( 'Briefcasewp_Extension' ) ) { return; }

class Bew_Blocks extends Briefcasewp_Extension {
  
    public function __construct() {
     $this->id = 'bewblocks';
     $this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-blocks.png';
     $this->title = __( 'Bew Blocks', 'briefcasewp-extras' );
     $this->desc  = __( 'Create pages using Bew block builder widgets', 'briefcasewp-extras' );
    }
	
	/**
    * Load method used to create hooks to extend or apply new features
    * This method will be called only on active extensions
    */
    public function load() {
		      	
		//add_action( 'init', array( $this, 'bew_cart_setup' ) );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'bew_blocks_enqueue_styles' ] );
		add_action( 'elementor/frontend/after_register_scripts', [ $this, 'bew_blocks_scripts' ] );	
		
    }
  	
 	public function bew_blocks_setup() {
	}
	
	public function bew_blocks_enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';
		$direction_suffix = is_rtl() ? '-rtl' : '';	
		
		$briefcasewp_extras_styles = get_option('briefcasewp_extras_styles');
		
		if ($briefcasewp_extras_styles == ""){
			$bew_frontend_file_name = 'bew-extras-style' . $direction_suffix . $suffix . '.css';
			
			$bew_frontend_file_url = BEW_EXTRAS_ASSETS_URL . 'css/' . $bew_frontend_file_name;
			
			wp_enqueue_style(
				'bew-extras-style',
				$bew_frontend_file_url,
				[],
				BEW_EXTRAS_VERSION
			);
		}	
			
	}
	
	public function bew_blocks_scripts() {	

		
		$briefcasewp_extras_scripts = get_option('briefcasewp_extras_scripts');
		
		if ($briefcasewp_extras_scripts == ""){
			wp_register_script(
				'bew-extras-scripts',
				BEW_EXTRAS_URL . 'assets/js/bew-extras-scripts' . '.js',
				array( 'jquery' ),
				BEW_EXTRAS_VERSION,
				true
			);			
		}
		
	}
	
}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bewblocks_extension' );

/**
* Add Bew Blocks Builder extension by passing the id and the name of the class.
*
* @param  array $extensions
* @return array
*/
function ext_add_bewblocks_extension( $extensions ) {
  $extensions['bewblocks'] = 'Bew_Blocks';
  return $extensions;
}