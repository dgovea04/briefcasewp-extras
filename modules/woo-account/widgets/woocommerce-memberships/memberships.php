<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Bew_MyAccount_Memberships_Widget extends \Elementor\Widget_Base {

	public function get_name() {
		return 'myaccount_wc_memberships';
	}

	public function get_title() {
		return esc_html__( 'WC Memberships: My Account Memberships', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-woo-myacount' ];
	}
	
	public function get_keywords() {
		return [ 'woocommerce', 'Memberships' , 'My Account' , 'Account' ];
	}

	protected function _register_controls(){

	}

	protected function render() {
		if( is_account_page() && class_exists('WC_Memberships_Members_Area') ){
			ob_start();
			require_once BEW_PATH . '/includes/plugins-support/woocommerce-memberships/class-wc-memberships-members-area.php';
			$output_members_area = new Bew_WC_Memberships_Members_Area();
			$output_members_area->output_members_area();
			echo ob_get_clean();
		}
	}

}

\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Bew_MyAccount_Memberships_Widget());