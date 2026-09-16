<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Bew_Block_Document extends Bew_Document_Base {

	public function get_name() {
		return 'bew_block';
	}

	public static function get_title() {
		return __( 'Block', 'bew-extras' );
	}

	public function has_conditions() {
		return false;
	}

}