<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bew_MyAccount_Bookings_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'myaccount_wc_bookings';
	}

	public function get_title() {
		return esc_html__( 'My Account WC Bookings', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-woo-myacount' ];
	}
	
	public function get_keywords() {
		return [ 'woocommerce', 'Bookings' , 'My Account' , 'Account' ];
	}

	protected function _register_controls(){

	}

	protected function render() {
		if ( ! is_user_logged_in() ) { return ''; }
		
		do_action('woocommerce_account_bookings_endpoint');
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Bew_MyAccount_Bookings_Widget());