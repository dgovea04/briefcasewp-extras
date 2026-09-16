<?php

require_once BEW_EXTRAS_PATH.'includes/theme-builder/conditions/briefcasewp.php';
require_once BEW_EXTRAS_PATH.'includes/theme-builder/documents/briefcasewp.php';

use Elementor\TemplateLibrary\Source_Local;
use ElementorPro\Modules\ThemeBuilder\Documents\Briefcasewp;
use ElementorPro\Plugin;
use ElementorPro\Modules\ThemeBuilder\Documents\Theme_Document;
use Elementor\Core\Documents_Manager;
use ElementorPro\Modules\ThemeBuilder\Classes\Locations_Manager;


// Register new Elementor Type	
Plugin::elementor()->documents->register_document_type( 'briefcasewp', Briefcasewp::get_class_full_name() );
Source_Local::add_template_type( 'briefcasewp' );

function parse_condition( $condition ) {
	list ( $type, $name, $sub_name, $sub_id ) = array_pad( explode( '/', $condition ), 4, '' );

	return compact( 'type', 'name', 'sub_name', 'sub_id' );
}

function bewcs_get_document( $post_id ) {
		$document = null;

		try {
			$document = Plugin::elementor()->documents->get( $post_id );
		} catch ( \Exception $e ) {}

		if ( ! empty( $document ) && ! $document instanceof Theme_Document ) {
			$document = null;
		}

		return $document;
}

function bewcs_add_more_types($settings){
  $post_id = get_the_ID();
  $document = bewcs_get_document( $post_id );

  if ( ! $document || !array_key_exists('theme_builder', $settings)) {
		return $settings;
	}
  
  $new_types=['briefcasewp'=>Briefcasewp::get_properties()];
  $add_settings=['theme_builder' => ['types' =>$new_types]];
  if (!array_key_exists('briefcasewp', $settings['theme_builder']['types'])) $settings = array_merge_recursive($settings, $add_settings);
  return $settings;
}

add_filter( 'elementor_pro/editor/localize_settings', 'bewcs_add_more_types' );


function bewcs_register_elementor_locations( $elementor_theme_manager ) {

	$elementor_theme_manager->register_location(
		'briefcasewp',
		[
			'label' => __( 'Briefcasewp', 'bew-extras' ),
			'multiple' => true,
			'edit_in_content' => true,
		]
	);

}
add_action( 'elementor/theme/register_locations', 'bewcs_register_elementor_locations' );

function bewcs_save_condition() {
	
	$my_id = get_the_ID();	
	$document = bewcs_get_document( $my_id );		
	$saved_conditions = $document->get_main_meta( '_elementor_conditions' );
	$conditions = [];

	if ( is_array( $saved_conditions ) ) {
		foreach ( $saved_conditions as $condition ) {
			$conditions[] = parse_condition( $condition );
		}
	}
	
	$aa = $conditions[0]["sub_name"];
	if($aa == 'not_found404'){
	$my_selection = esc_html('woo-shop');	
	}

	
	update_post_meta( $my_id, 'briefcase_template_layout', $my_selection); 		

}
//add_action( 'elementor/editor/after_save', 'bewcs_save_condition' );

