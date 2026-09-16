<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Bew_Page_Document extends Bew_Document_Base {

	public function get_name() {
		return 'bew_page';
	}

	public static function get_title() {
		return __( 'Page', 'bew-extras' );
	}

	public function has_conditions() {
		return false;
	}

}