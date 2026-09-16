<?php
namespace BriefcasewpExtras\Modules\WooCart\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;   
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Empty_Cart_Message extends Base_Widget {

	public function get_name() {
		return 'woo-empty-cart-message';
	}

	public function get_title() {
		return __( 'Empty Cart Message', 'briefcase-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-cart' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
            'empty_cart_content',
            [
                'label' => esc_html__( 'Empty Cart Message', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'custom_message',
			[
				'label' 		=> __( 'Custom Message', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$this->add_control(
			'custom_message_text',
			[
				'label' 		=> __( 'Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXTAREA,
				'default' 		=> __( 'Your cart is currently empty.', 'woocommerce' ),
				'placeholder' 	=> __( 'Your cart is currently empty.', 'woocommerce' ),
				'condition' 	=> [
					'custom_message' => 'yes'
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style',
			array(
				'label' => esc_html__( 'Style', 'briefcase-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'      => 'cart_empty_sms_typography',
				'label'     => esc_html__( 'Typography', 'briefcase-extras' ),
				'selector'  => '{{WRAPPER}} .cart-empty.woocommerce-info',
			)
		);
		
		$this->add_control(
			'cart_empty_sms_color',
			[
				'label'     => esc_html__( 'Color', 'briefcase-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-empty.woocommerce-info' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'cart_empty_sms_bg_color',
			[
				'label' => __( 'Background Color', 'briefcase-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .cart-empty.woocommerce-info' => 'background-color: {{VALUE}}',
				],
			]
		);
		   
		$this->add_responsive_control(
            'cart_empty_sms__padding',
            [
                'label' => __( 'Padding', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .cart-empty.woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
            'cart_empty_sms__margin',
            [
                'label' => __( 'Margin', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .cart-empty.woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );			
				
		$this->add_responsive_control(
			'text_align',
			[
				'label'        => esc_html__( 'Alignment', 'briefcase-extras' ),
				'type'         => Controls_Manager::CHOOSE,
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
				'selectors' => [
                    '{{WRAPPER}} .cart-empty' => 'text-align: {{VALUE}}',
                ],
			]
		);
		
		$this->end_controls_section();

	}
	
	function custom_wc_empty_cart_message($custom_message_text) {
	echo '<p class="cart-empty woocommerce-info">' . wp_kses_post( apply_filters( 'wc_empty_cart_message', __( $custom_message_text, 'bew-extras' ) ) ) . '</p>';
	}


	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$custom_message =  $settings['custom_message'];
		$custom_message_text =  $settings['custom_message_text'];
		
		if($custom_message == "yes"){
			$this->custom_wc_empty_cart_message($custom_message_text);
			
		} else{

			// @hooked wc_empty_cart_message
			do_action( 'woocommerce_cart_is_empty' );
			
		}

	}	
}