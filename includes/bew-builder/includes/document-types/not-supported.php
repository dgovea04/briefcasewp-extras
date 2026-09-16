<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Bew_Builder_Not_Supported extends Elementor\Modules\Library\Documents\Not_Supported {

	public function get_name() {
		return 'bew-builder-not-supported';
	}

	public static function get_properties() {
		$properties = parent::get_properties();

		$properties['cpt'] = array( bew_builder()->templates->post_type );

		return $properties;
	}

	public function save_template_type() {
		// Do nothing.
	}
}
