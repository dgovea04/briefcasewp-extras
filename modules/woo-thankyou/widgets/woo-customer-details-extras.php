<?php
namespace BriefcasewpExtras\Modules\WooThankyou\Widgets;

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

class Woo_Customer_Details_Extras extends Base_Widget {

	public function get_name() {
		return 'woo-customer-details-extras';
	}

	public function get_title() {
		return __( 'Customer Details Extras', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-thankyou' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'customer_details_extras',
			[
				'label' => esc_html__( 'Customer Extras Details', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'customer_details_extras_information',
			[
				'label' 		=> __( 'Customer Information', 'bew-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'label_block'   => true,
				'default' 		=> 'billing_first_name',
				'options'		=> $this->bew_get_list_fields(),
			]
		);
		
		$this->add_control(
			'customer_details_extras_information_layout',
			[
				'label' 		=> __( 'Customer Information', 'bew-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'label_block'   => true,
				'default' 		=> 'inline',
				'options' => [
					'flex'		=> __( 'Inline', 'bew-extras' ),
					'block'		=> __( 'Stacked', 'bew-extras' ),
				],
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras' => 'display: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'customer_details_extras_before',
			[
				'label'     => esc_html__( 'Before Text', 'bew-extras' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'Thank you,', 'bew-extras' ),
				'placeholder' => esc_html__( 'Thank you,', 'bew-extras' ),
			]
		);
	
		$this->add_control(
			'customer_details_extras_after',
			[
				'label'     => esc_html__( 'After Text', 'bew-extras' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default'   => esc_html__( 'Your order has been received.', 'bew-extras' ),
				'placeholder' => esc_html__( 'Your order has been received.', 'bew-extras' ),
			]
		);
			
						
		$this->end_controls_section();
		
		// customer_details_extras
		$this->start_controls_section(
			'customer_details_extras_style',
			[
				'label' => esc_html__( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'customer_details_extras_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-thankyou-customer-details-extras',
			]
		);
		
		$this->add_control(
			'customer_details_extras_text_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'customer_details_extras_text_padding',
			[
				'label'         => __( 'Padding', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],				
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_text_margin',
			[
				'label'         => __( 'Margin', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras' => 'justify-content: {{VALUE}};',
				],
			]
		);		
		$this->end_controls_section();

		// customer_details_extras_information
		$this->start_controls_section(
			'customer_details_extras_information_style',
			[
				'label' => esc_html__( 'Customer Information', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'customer_details_extras_information_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-information',
			]
		);
		
		$this->add_control(
			'customer_details_extras_before_text_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-information' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'customer_details_extras_before_text_padding',
			[
				'label'         => __( 'Padding', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],				
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-information' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_before_text_margin',
			[
				'label'         => __( 'Margin', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-information' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_before_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-information' => 'justify-content: {{VALUE}};',
				],
			]
		);		
		$this->end_controls_section();
		
		// customer_details_extras_before
		$this->start_controls_section(
			'customer_details_extras_before_style',
			[
				'label' => esc_html__( 'Text Before', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'customer_details_extras_before_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-before',
			]
		);
		
		$this->add_control(
			'customer_details_extras_before_text_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-before' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'customer_details_extras_before_text_padding',
			[
				'label'         => __( 'Padding', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],				
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_before_text_margin',
			[
				'label'         => __( 'Margin', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_before_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-before' => 'justify-content: {{VALUE}};',
				],
			]
		);		
		$this->end_controls_section();

		// customer_details_extras_after
		$this->start_controls_section(
			'customer_details_extras_after_style',
			[
				'label' => esc_html__( 'Text After', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'customer_details_extras_after_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-after',
			]
		);
		
		$this->add_control(
			'customer_details_extras_after_text_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-after' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'customer_details_extras_after_text_padding',
			[
				'label'         => __( 'Padding', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],				
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_after_text_margin',
			[
				'label'         => __( 'Margin', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'customer_details_extras_after_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Controls_Manager::CHOOSE,
				'options'      => [
					'flex-start'   => [
						'title' => esc_html__( 'Left', 'elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => esc_html__( 'Right', 'elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-customer-details-extras .woocommerce-customer-details-after' => 'justify-content: {{VALUE}};',
				],
			]
		);		
		$this->end_controls_section();						
		
	}
	
	function bew_get_last_order_id(){
		global $wpdb;
		$statuses = array_keys(wc_get_order_statuses());
		$statuses = implode( "','", $statuses );
	
		// Getting last Order ID (max value)
		$results = $wpdb->get_col( "
			SELECT MAX(ID) FROM {$wpdb->prefix}posts
			WHERE post_type LIKE 'shop_order'
			AND post_status IN ('$statuses')
			" );
			return reset($results);
	}

	function bew_get_list_fields(){
		
		$get_fields = get_option( '_bew_checkout_fields',  [] );
		
		$options = [];
		foreach ( $get_fields as $section => $_fields ) {
			
			if( count( $_fields ) > 0 ) {
				foreach ( $_fields as $key => $_field ) {	
				$options[ $key ] = $_field['label'];
				}
			}			
		}
							
		return $options;
	}
	
	protected function render() {
		global $wp;
		
		$settings = $this->get_settings_for_display();
		$extra_information  = $settings['customer_details_extras_information']; 
		$before_text        = $settings['customer_details_extras_before']; 
		$after_text         = $settings['customer_details_extras_after'];
				
		if( isset($wp->query_vars['order-received']) ){
			$bew_order_received = $wp->query_vars['order-received'];
		}else{
			$bew_order_received = $this->bew_get_last_order_id();
		}

		$order = wc_get_order( $bew_order_received );		
		$order_data = $order->get_data(); // The Order data
				
		$ei = explode('_', $extra_information, 2);
		$extra_information = $order_data[$ei[0]][$ei[1]];
		
		//$order_billing_first_name = $order_data['billing']['first_name'];		
		//echo var_dump($extra_information);
		
		?>			
		<div class="bew-thankyou-customer-details-extras"> <?php 
			wc_get_template( 'order/order-details-customer-details-extras.php', array( 'order' => $order, 'extra_information' => $extra_information, 'before_text' => $before_text, 'after_text' => $after_text, ) );
		?>
		</div> <?php 		
	}

	protected function _content_template() {
		
	}
	
}

