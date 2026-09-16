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

class Woo_Customer_Details extends Base_Widget {

	public function get_name() {
		return 'woo-customer-details';
	}

	public function get_title() {
		return __( 'Customer Details', 'bew-extras' );
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
			'customer_details_section',
			[
				'label' => esc_html__( 'Customer Details', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
	
		$this->add_control(
			'customer_details_layout',
			[
				'label' 		=> __( 'Customer Details Layout', 'bew-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'inline',
				'options' 		=> [
					'inline' 	=> __( 'Inline', 'bew-extras' ),
					'stacked' 	=> __( 'Stacked', 'bew-extras' ),
				],
			]
		);
						
		$this->end_controls_section();
		// Heading
		$this->start_controls_section(
			'heading_style',
			[
				'label' => esc_html__( 'Heading', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'heading_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-customer-details .woocommerce-column__title',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-customer-details .woocommerce-column__title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'heading_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
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
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-customer-details .woocommerce-column__title' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		// Address
		$this->start_controls_section(
			'address_style_section',
			[
				'label' => esc_html__( 'Address', 'woocommerce-builder-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'address_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-customer-details address',
			]
		);
		$this->add_control(
			'address_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-customer-details address' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'address_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
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
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-customer-details address' => 'text-align: {{VALUE}}',
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
	
	protected function render() {
		
		global $wp;
		
		$settings = $this->get_settings_for_display();
		$customer_details_layout = $settings['customer_details_layout'];
		
		if( isset($wp->query_vars['order-received']) ){
			$bew_order_received = $wp->query_vars['order-received'];
		}else{
			$bew_order_received = $this->bew_get_last_order_id();
		}
		
		if( !$bew_order_received ){
			return;
		}
		
		$order = wc_get_order( $bew_order_received );
			
		$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
		if ( $show_customer_details ) {
			?>			
			<div class="bew-thankyou-customer-details <?php echo $customer_details_layout; ?>"> <?php 
			wc_get_template( 'order/order-details-customer.php', array( 'order' => $order ) );
			?>
			</div> <?php 
		}
	}

	protected function _content_template() {
		
	}
	
}

