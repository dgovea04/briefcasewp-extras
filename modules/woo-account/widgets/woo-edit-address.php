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
use WC_Shortcode_My_Account;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Edit_Address extends Base_Widget {

	public function get_name() {
		return 'woo-extra-edit-address';
	}

	public function get_title() {
		return __( 'Account Address', 'bew-extras' );
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
		// Message
		$this->start_controls_section(
			'message_style',
			[
				'label' => esc_html__( 'Message', 'woocommerce-builder-elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'message_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .dtwcbe-form-edit-address > p',
			]
		);
		$this->add_control(
			'message_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .dtwcbe-form-edit-address > p' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'message_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options'      => [
					'left'   => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'fa fa-align-center',
					],
					'right'  => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .dtwcbe-form-edit-address > p' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// header
		$this->start_controls_section(
			'header_style',
			[
				'label' => esc_html__( 'Header', 'woocommerce-builder-elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'header_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-Address-title, {{WRAPPER}} .woocommerce-Address-title h3',
			]
		);
		$this->add_control(
			'header_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Address-title h3' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'header_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bew-extras' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-Address-title' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// address
		$this->start_controls_section(
			'address_style',
			[
				'label' => esc_html__( 'Address', 'woocommerce-builder-elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'address_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} address',
			]
		);
		$this->add_control(
			'address_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} address' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'address_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'bew-extras' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} address' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		
			if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'woocommerce-builder-elementor'); }
			global $wp;
			$type = '';
			
			if( isset($wp->query_vars['edit-address']) ){
				$type = $wp->query_vars['edit-address'];
			}else{
				$type = wc_edit_address_i18n( sanitize_title( $type ), true );
			}
			echo '<div class="dtwcbe-form-edit-address">';
			WC_Shortcode_My_Account::edit_address( $type );
			echo '</div>';
	}
	
	protected function _content_template() {
		
	}

}
