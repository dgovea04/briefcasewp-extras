<?php
/**
 * Extension Name: Bew AI Smart Upsells Extension
 * Description: Rule-based AI smart upsells for WooCommerce checkout.
 */

if ( ! defined( 'ABSPATH' ) ) {
	return;
}

if ( ! class_exists( 'Briefcasewp_Extension' ) ) {
	return;
}

class Bew_AI_Smart_Upsells extends Briefcasewp_Extension {

	public function __construct() {
		$this->id    = 'bewaismartupsells';
		$this->image = BEW_EXTRAS_ASSETS_URL . 'img/bew-checkout.png';
		$this->title = __( 'Bew AI Smart Upsells', 'bew-extras' );
		$this->desc  = __( 'Rule-based smart upsell suggestions for WooCommerce checkout.', 'bew-extras' );
	}

	public function load() {
		// Runtime logic is loaded from the existing Woo Checkout module.
	}
}

add_filter( BEWXT_SLUG . '_extensions', 'ext_add_bew_ai_smart_upsells_extension' );

function ext_add_bew_ai_smart_upsells_extension( $extensions ) {
	$extensions['bewaismartupsells'] = 'Bew_AI_Smart_Upsells';
	return $extensions;
}
