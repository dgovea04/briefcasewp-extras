<?php
namespace BriefcasewpExtras\Modules\WooAccount\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;   
use Elementor\Group_Control_Box_Shadow;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Extra_Endpoint extends Base_Widget {

	public function get_name() {
		return 'woo-extra-endpoint';
	}

	public function get_title() {
		return __( 'Account Extra Endpoint', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-account' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general', 'bew-checkout' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_extra_endpoint',
			[
				'label' => esc_html__( 'Extra Key', 'woocommerce-builder-elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'extra_key',
			[
				'label' => esc_html__( 'Extra key', 'woocommerce-builder-elementor' ),
				'type' => Elementor\Controls_Manager::TEXT,
				'placeholder' => esc_html__( 'Enter only the extra key. e.g. extra-key . It will be used for "woocommerce_account_extra-key_endpoint" ', 'woocommerce-builder-elementor' ),
				'default' => 'bookings',
			]
		);
		
		$this->end_controls_section();
		
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( is_account_page() ) {
			if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'woocommerce-builder-elementor'); }
			
			do_action('woocommerce_account_'.$settings['extra_key'].'_endpoint');
			
	    } else {
	    	echo 'woocommerce_account_'.$settings['extra_key'].'_endpoint';
	    }
	}
	
	protected function _content_template() {
		
	}

}
