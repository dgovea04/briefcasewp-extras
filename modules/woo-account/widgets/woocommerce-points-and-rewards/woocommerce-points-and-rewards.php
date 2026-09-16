<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bew_MyAccount_WC_Points_Rewards_My_Points_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'myaccount_wc_points_rewards_my_points';
	}

	public function get_title() {
		return esc_html__( 'My Account WC Points and Rewards', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-woo-myacount' ];
	}
	
	public function get_keywords() {
		return [ 'woocommerce', 'Points and Rewards' , 'Points' , 'Rewards' ];
	}

	protected function _register_controls(){

	}

	protected function render() {
		do_action( 'woocommerce_account_points-and-rewards_endpoint');
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Bew_MyAccount_WC_Points_Rewards_My_Points_Widget());