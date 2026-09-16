<?php
namespace BriefcasewpExtras\Modules\WooOrders\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Source_Base;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Repeater;
use BriefcasewpExtras\Base\Base_Widget;
use WC_Checkout;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Orders extends Base_Widget {
	
	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );		
		
		// Enabled OPC ans Wao on Woo orders for elementor editor.
		if(Elementor\Plugin::instance()->editor->is_edit_mode()){
			add_filter( 'is_bewwao_checkout', function( $is_wao ) {
				return true;
			} );
			add_filter( 'is_bewopc_checkout', function( $is_opc ) {
				return true;
			} );
			add_filter( 'is_bew_woo_checkout', function( $is_bwco ) {
				return false;
			} );
		}

	}

	public function get_name() {
		return 'bew-woo-orders';
	}

	public function get_title() {
		return __( 'Woo Orders', 'briefcase-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general'];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_woo_order_cart',
			[
				'label' 		=> __( 'Woo Order Cart', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_responsive_control(
			'position',
			[
				'label' 		=> __( 'Position', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .woo-header-cart' => 'text-align: {{VALUE}};',
				],
			]
		);
						
		$this->add_control(
            'woo_orders_icon_type',
            [
                'label' => __( 'Icon Type', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
					'cart' => [
						'title' => __( 'Shopping Cart', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-cart',
					],
					'bag' => [
						'title' => __( 'Shopping Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-bag',
					],
					'basket' => [
						'title' => __( 'Shopping Basket', 'briefcase-elementor-widgets' ),
						'icon' => 'fa fa-shopping-basket',
					],
					'line-handbag' => [
						'title' => __( 'Shopping Line Hand Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-handbag',
					],
					'line-bag' => [
						'title' => __( 'Shopping Line Bag', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-bag',
					],
					'line-basket' => [
						'title' => __( 'Shopping Line Basket', 'briefcase-elementor-widgets' ),
						'icon' => 'icon-basket',
					],
				],
				'default' => 'cart',
				
            ]
        );
		
		$this->add_control(
			'woo_orders_cart_style',
			[
				'label' 		=> __( 'Cart Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'a',
				'options' 		=> [
					'a' 			=> __( 'Circle', 'briefcase-elementor-widgets' ),
					'b' 			=> __( 'Square', 'briefcase-elementor-widgets' ),
					'c' 			=> __( 'Minimalist', 'briefcase-elementor-widgets' ),
					'd' 			=> __( 'Custom', 'briefcase-elementor-widgets' ),
				],
			]
		);
			
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_menu_cart',
			[
				'label' 		=> __( 'Woo Orders', 'briefcase-elementor-widgets' ),
			]
		);

		
		$this->add_responsive_control(
			'position',
			[
				'label' 		=> __( 'Position', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::CHOOSE,
				'options' 		=> [
					'left' => [
						'title' => __( 'Left', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'briefcase-elementor-widgets' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart' => 'text-align: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_style',
			[
				'label' 		=> __( 'Order Cart Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'cart-sidebar',
				'options' 		=> [					
					'cart-sidebar' 			=> __( 'Sidebar', 'briefcase-elementor-widgets' ),					
				],
			]
		);
				
		$this->add_control(
			'bew_woo_quantity_cart_show',
			[
				'label' 		=> __( 'Qty Increment Buttons', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-woo-quantity-cart-',
			]
		);			
		
		$this->add_control(
			'heading_cart_sidebar',
			[
				'label' => __( 'Sidebar', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'sidebar_cart_text',
			[
				'label' => __( 'Heading Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'My Order', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'My Order', 'briefcase-elementor-widgets' ),				
			]
		);
		
			
		$this->add_control(
			'sidebar_close_text',
			[
				'label' => __( 'Close Text', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Close', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'Close', 'briefcase-elementor-widgets' ),				
			]
		);
				

		$this->add_control(
			'bew_mini_cart_checkout',
			[
				'label' 		=> __( 'Bew Order Type ', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'bew-whatsapp',
				'options' 		=> [					
					'bew-whatsapp' 		=> __( 'WhatsApp', 'briefcase-elementor-widgets' ),
					'bew-bc' 			=> __( 'Button Checkout', 'briefcase-elementor-widgets' ),
					'bew-opc' 			=> __( 'One Page Checkout', 'briefcase-elementor-widgets' ),
				],				
			]
		);		
				
		$this->add_control(
			'heading_whatsapp_button',
			[
				'label' => __( 'WhatsApp', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-whatsapp',
				]
			]
		);
		
		$this->add_control(
			'whatsapp_button_number',
			[
				'label' => __( 'WhatsApp Number', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( '', 'briefcase-elementor-widgets' ),
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-whatsapp',
				]
			]
		);	
		
		$this->add_control(
			'whatsapp_button_message',
			[
				'label' => __( 'WhatsApp Message', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'I want to buy ', 'briefcase-elementor-widgets' ),
				'placeholder' => __( 'I want to buy', 'briefcase-elementor-widgets' ),
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-whatsapp',
				]
			]
		);
		
		$this->add_control(
			'whatsapp_customer_show',
			[
				'label' 		=> __( 'Display Customer Details', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-woo-whatsapp-customer-',
				'condition' => [
					'bew_mini_cart_checkout' => ['bew-whatsapp','bew-opc']
				]
			]
		);
		
		$this->add_control(
			'whatsapp_button_show',
			[
				'label' 		=> __( 'Display WhatsApp Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'bew-woo-whatsapp-button-',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-whatsapp',
				]
			]
		);
		
		$this->add_control(
			'heading_checkout_buttons',
			[
				'label' => __( 'Checkout Buttons', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-bc',
				]
			]
		);
						
		$this->add_control(
            'bew_woo_mini_cart_buttons_layout',
            [
                'label' => __( 'Buttons Layout', 'briefcase-elementor-widgets' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'inline' => [
                        'title' => __( 'Inline', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-h',
                    ],
                    'stacked' => [
                        'title' => __( 'Stacked', 'briefcase-elementor-widgets' ),
                        'icon' => 'fa fa-arrows-v',
                    ]
                ],
                'default' => 'inline',                
				'prefix_class' => 'bew-woo-mini-cart-buttons-',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-bc',
				]
                
            ]
        );
		
		$this->add_control(
			'button_view_cart_show',
			[
				'label' 		=> __( 'Display View Cart Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',	
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-bc',
				]
			]
		);

		$this->add_control(
			'button_checkout_show',
			[
				'label' 		=> __( 'Display Checkout Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-bc',
				]
			]
		);
		
		$this->add_control(
			'button_cart_pdf_show',
			[
				'label' 		=> __( 'Display Cart Pdf Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-bc',
				]
			]
		);
		
		$this->add_control(
			'heading_bew_opc',
			[
				'label' => __( 'One Page Checkout', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
				'condition' => [
					'bew_mini_cart_checkout' => 'bew-opc',
				]
			]
		);
		
		$this->end_controls_section();
				
		$this->start_controls_section(
			'section_style_general',
			[
				'label' => __( 'General Style', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'woo_orders_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_orders_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-menu-cart' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_style_woo_orders_icon',
			[
				'label' => __( 'Cart Icon', 'briefcase-elementor-widgets' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_responsive_control(
			'woo_orders_size',
			[
				'label' => __( 'Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 6,
						'max' => 300,
					],
				],
				'default' => [
					'size' => 22,
					],
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .woo-orders-header-cart i.fa, {{WRAPPER}} .woo-orders-header-cart i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
			
		$this->start_controls_tabs( 'tabs_icon_style' );

		$this->start_controls_tab(
			'tab_woo_orders_icon_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'woo_orders_icon_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart i' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_icon_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-menucart' => 'background: {{VALUE}};',
				],
				
			]
		);
						
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_woo_orders_icon__hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);

		$this->add_control(
			'woo_orders_icon_color_hover',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart i:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_icon_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-menucart:hover' => 'background: {{VALUE}};',
				],
				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();		
		
		
		
		$this->add_control(
			'woo_orders_icon_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-menucart' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'woo_orders_icon_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .woo-orders-header-cart .woo-orders-menucart',
				'separator' => 'before',
							
			]
		);
			
		
		$this->add_control(
			'woo_orders_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-menucart' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_woo_orders_quantity',
			[
				'label' 		=> __( 'Cart Quantity', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'woo_orders_quantity_position_top',
			[
				'label' => __( 'Top', 'elementor' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity' => 'top: {{VALUE}}px',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_quantity_position_right',
			[
				'label' => __( 'Right', 'elementor' ) . ' (px)',
				'type' => Controls_Manager::NUMBER,				
				'selectors' => [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity' => 'right: {{VALUE}}px',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_quantity_color',
			[
				'label' 		=> __( 'Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_quantity_bg_color',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .woo-orders-header-cart span:before' => 'border-right-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_arrow_before_span',
			[
				'label' 		=> __( 'Display Arrow', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'woo_orders_cart_style' => [ 'b', 'd'],					
					],
			]
		);
		
		$this->add_responsive_control(
			'woo_orders_arrow_size',
			[
				'label' => __( 'Size (%)', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 75,
					'unit' => '%',
				],
				'tablet_default' => [
					'unit' => '%',
				],
				'mobile_default' => [
					'unit' => '%',
				],
				'size_units' => [ '%' ],
				'range' => [
					'%' => [
						'min' => 60,
						'max' => 100,
					],
				],
				'condition' => [
					'arrow_before_span' => [ 'yes'],
					'woo_orders_cart_style' => [ 'b', 'd'],					
					],
				'selectors' => [
					'{{WRAPPER}} .woo-arrow span:before' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
				
		$this->add_control(
			'woo_orders_text_after_span',
			[
				'label' 		=> __( 'Display Text', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'condition' => [
					'woo_orders_cart_style' => [ 'c', 'd'],					
					],
			]
		);
		
		$this->add_control(
			'woo_orders_text_after_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woo-item span:after' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
					'text_after_span' => [ 'yes'],
					'woo_orders_cart_style' => [ 'c', 'd'],	
					],				
			]
		);
		
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'woo_orders_quantity_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity',
				'separator' => 'before',
				
			]
		);
		
		$this->add_control(
			'woo_orders_quantity_border_radius',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'woo_orders_quantity_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'woo_orders_quantity_typo',
				'selector' 		=> '{{WRAPPER}} .woo-orders-header-cart .woo-orders-cart-quantity',
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_bew_woo_mini_cart',
			[
				'label' 		=> __( 'Mini Cart', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
			]
		);
				
		$this->add_responsive_control(
			'bew_woo_mini_cart_width',
			[
				'label' => __( 'Width', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 1,
						'max' => 1000,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .bew-woo-mini-cart .bew-woo-mini-cart-sidebar' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_width_color_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .cart-sidebar' => 'background-color: {{VALUE}};',
				],
			]
		);
				
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bew_woo_mini_cart_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .cart-sidebar',
			]
		);
			
		$this->add_control(
			'heading_bew_woo_mini_cart_products_list',
			[
				'label' => __( 'Product List', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bew_woo_mini_cart_products_list_typography',				
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .owp-grid-wrap .owp-grid.content h3, {{WRAPPER}} .bew-woo-mini-cart .shopping-cart .owp-grid-wrap .owp-grid.content',
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_image_size',
			[
				'label' => __( 'Image Size', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 50,
						'max' => 300,
					],
				],				
				'size_units' => [ 'px'],	
				'selectors' => [
					'{{WRAPPER}} .bew-woo-mini-cartt .shopping-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid.thumbnail img' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bew_woo_mini_cart_img_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid.thumbnail img',
				
			]
		);
		
			$this->add_control(
			'bew_woo_mini_cart_border_radius_img',
			[
				'label' => __( 'Border Radius', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid.thumbnail img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_amount_color',
			[
				'label' 		=> __( 'Amount Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .woocommerce-mini-cart .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_products_remove',
			[
				'label' 		=> __( 'Remove Button Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content ul.cart_list li .owp-grid-wrap .owp-grid a.remove' => 'color: {{VALUE}}; border-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_products_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content' => 'background-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_products_padding',
			[
				'label' 		=> __( 'Padding Product', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_products_margin',
			[
				'label' 		=> __( 'Margin Products', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content ul' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_bew_woo_mini_cart_products_total',
			[
				'label' => __( 'Total', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bew_woo_mini_cart_products_total_typography',				
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .total strong, {{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .total .amount',
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_amount_total_color',
			[
				'label' 		=> __( 'Amount Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .woocommerce-mini-cart__total .amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_total_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .woocommerce-mini-cart__total' => 'background-color: {{VALUE}};',					
				],
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_total_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .total' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_total_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .total' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'heading_bew_woo_mini_cart_products_button',
			[
				'label' => __( 'Buttons', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'bew_woo_mini_cart_products_button_typography',				
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .buttons .button',
			]
		);
				
		$this->add_control(
			'bew_woo_mini_cart_buttons_bg',
			[
				'label' 		=> __( 'Background', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,				
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .woocommerce-mini-cart__buttons' => 'background-color: {{VALUE}};',					
				],
			]
		);

		$this->add_control(
			'bew_woo_mini_cart_button_display',
			[
				'label' 		=> __( 'Display View Cart Button', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'Show', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Hide', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',				
			]
		);
		
		$this->add_control(
			'heading_bew_woo_mini_cart_button',
			[
				'label' => __( 'View Cart', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
				
		$this->start_controls_tabs( 'tabs_button_cart_style' );

		$this->start_controls_tab(
			'tab_bew_woo_mini_cart_button_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .button:first-child' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .button:first-child' => 'background: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_bew_woo_mini_cart_button_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_color_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .button:first-child:hover' => 'color: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_background_color_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .button:first-child:hover' => 'background-color: {{VALUE}};',
				],
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);

			
		$this->add_control(
			'bew_woo_mini_cart_button_border_cart_hover',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'bew_woo_mini_cart_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .button:first-child:hover' => 'border-color: {{VALUE}};',
				],				
			]
		);
		
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bew_woo_mini_cart_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .button:first-child',
				'condition' => [
					'button_cart_show' => 'yes',					
					],
			]
		);
		
		$this->add_control(
			'heading_bew_woo_mini_cart_button_checkout',
			[
				'label' => __( 'Checkout', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,							
			]
		);
		
		$this->start_controls_tabs( 'tabs_button_checkout_style' );

		$this->start_controls_tab(
			'tab_bew_woo_mini_cart_button_checkout_normal',
			[
				'label' => __( 'Normal', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_checkout_color',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .checkout' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_background_checkout_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .checkout' => 'background: {{VALUE}};',
				],				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'bew_woo_mini_cart_tab_button_checkout_hover',
			[
				'label' => __( 'Hover', 'briefcase-elementor-widgets' ),
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_color_checkout_hover',
			[
				'label' 		=> __( 'Text Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .checkout:hover' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'bew_woo_mini_cart_button_background_color_checkout_hover',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .checkout:hover' => 'background-color: {{VALUE}};',
				],				
			]
		);

			
		$this->add_control(
			'bew_woo_mini_cart_button_border_checkout_hover',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'bew_woo_mini_cart_checkout_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .checkout:hover' => 'border-color: {{VALUE}};',
				],				
			]
		);
				
		$this->end_controls_tab();

		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'bew_woo_mini_cart_checkout_border',
				'label' => __( 'Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .bew-woo-mini-cart .shopping-cart-content .buttons .checkout',								
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_button_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .buttons' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'bew_woo_mini_cart_button_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-mini-cart .shopping-cart .shopping-cart-content .buttons' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
			
		
		$this->start_controls_section(
			'section_cart_sidebar',
			[
				'label' 		=> __( 'Cart Sidebar', 'briefcase-elementor-widgets' ),
				'tab' 			=> Controls_Manager::TAB_STYLE,
				'condition' => [
					'bew_woo_mini_cart_style' => 'cart-sidebar',					
				],
			]
		);
		
		$this->add_control(
			'heading_cart_sidebar_style',
			[
				'label' => __( 'Header', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->add_control(
			'title_cart_sidebar_color',
			[
				'label' 		=> __( 'Title Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .cart-sidebar .shopping-cart-header .heading--add-small' => 'color: {{VALUE}};',
				],
			]
		);
			
		$this->add_control(
			'cart_sidebar_color_bg',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .cart-sidebar .shopping-cart-header' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_sidebar_header_padding',
			[
				'label' 		=> __( 'Padding', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .cart-sidebar .shopping-cart-header' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_sidebar_header_margin',
			[
				'label' 		=> __( 'Margin', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .cart-sidebar .shopping-cart-header' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'cart_sidebar_header_border',
				'label' => __( 'Header Border', 'briefcase-elementor-widgets' ),
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .cart-sidebar .shopping-cart-header',
			]
		);
		
		$this->add_control(
			'cart_sidebar_inline_content_style',
			[
				'label' 		=> __( 'Content Inline Style', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'default' 		=> 'yes',
				'label_on' 		=> __( 'On', 'briefcase-elementor-widgets' ),
				'label_off' 	=> __( 'Off', 'briefcase-elementor-widgets' ),
				'return_value' 	=> 'yes',
				'prefix_class' => 'content-inline-',
			]
		);
		
		$this->end_controls_section();
		
	}
	
	// This function creates the Order On WhatsApp button API URL grabing the cart contents.	
	public function bew_get_cart_contents_callback(){
		
		$settings = $this->get_settings(); 
				
		$whatsapp_button_number = $settings['whatsapp_button_number'];
		$whatsapp_button_message = $settings['whatsapp_button_message'];
		
		$has_cart = is_a( WC()->cart, 'WC_Cart' );
		
		if ( $has_cart ) {
			$items= WC()->cart->get_cart();
		}
	
		if($whatsapp_button_message== '') $whatsapp_button_message= "I want to buy->";

		else $message= $whatsapp_button_message;

		$currency= get_option('woocommerce_currency');

        foreach($items as $item ) { 
            $_product =  wc_get_product( $item['product_id']);     
            $product_name= $_product->get_name();
            $qty= $item['quantity'];            
            $price= $item['line_subtotal'];
            $product_url= get_post_permalink($item['product_id']);
        
        	$message.= "Product Name: ".$product_name."\r\nQuantity: ".$qty."\r\nPrice: ".$price." ".$currency."\r\nUrl: ".$product_url."\r\n";
        }
        
        $message.="Thank You";
        
        $url = 'https://api.whatsapp.com/send?phone='.$whatsapp_button_number.'&text='.urlencode($message);

		return $url;
		
    }
		
	public static function sidebar_mini_cart() {
		if ( function_exists( 'wc_get_template' ) ) {
			global $product;
			// Get sidebar-mini-cart template part
			wc_get_template( 'cart/sidebar-mini-cart.php');
		}
	}
	
	// Creates the WooCommerce link for the widget
	public function bew_woominicart() {
		
		$settings = $this->get_settings();
	
		$view_cart = '';
		$view_checkout = '';
		$view_cart_pdf = '';
		$mini_cart_style = $settings['bew_woo_mini_cart_style'];
		$mini_cart_checkout = $settings['bew_mini_cart_checkout'];
		$your_cart = $settings['sidebar_cart_text'];
		$checkout_show = $settings['button_checkout_show'];
		$viewcart_show = $settings['button_view_cart_show'];
		$cartpdf_show = $settings['button_cart_pdf_show'];		
		$close = $settings['sidebar_close_text'];
		
		$wbn = $settings['whatsapp_button_number'];
		$wbm = $settings['whatsapp_button_message'];	
				 
		// Display View Cart and Checkout button 
					
			if ( ('yes' == $viewcart_show) && ('bew-bc' == $mini_cart_checkout) ) {
				$view_cart = 'show_view_cart';
			}
			
			if ( ('yes' == $checkout_show) && ('bew-bc' == $mini_cart_checkout) ) {
				$view_checkout = 'show_checkout';
			}
			
			if ( ('yes' == $cartpdf_show) && ('bew-bc' == $mini_cart_checkout)) {
				$view_cart_pdf = 'show_cart_pdf';
			}
				
		if( class_exists( 'WooCommerce' ) ) { 	
		$woo = WC()->cart;
													
			// Display WhatsApp Button
			$url_w = $this->bew_get_cart_contents_callback();
		
			if(is_null($woo)){ 
						
			} else {
	
			$count = WC()->cart->cart_contents_count;
						
			if($count == 0 ){
				$view_bew_whatsapp = 'hide-bew-whatsapp';
			} else{
				$view_bew_whatsapp = '';
			}
		
			
				// Mini Cart WooCommerce			
				?>	

					<div class="bew-woo-mini-cart">		
						<div class="bew-woo-mini-<?php echo esc_attr( $mini_cart_style );  ?> bew-woo-order-<?php echo esc_attr( $mini_cart_checkout );?> ">
							<div class="<?php echo esc_attr( $mini_cart_style ); ?> woocommerce shopping-cart <?php echo esc_attr( $view_cart ); ?> <?php echo esc_attr( $view_checkout ); ?> <?php echo esc_attr( $view_cart_pdf ); ?>">
								<?php 
								if($mini_cart_style == 'cart-sidebar'){
								?>
								<div class="shopping-cart-header">								
								  <h3 class="heading--add-small"><?php echo $your_cart; ?></h3>
								  <button class="woo-orders-btn-close btn" type="button">
									<span><?php echo $close; ?></span>
								  </button>
								</div>
								
								<?php 	
								}
								?>
								<div class="shopping-cart-content-sidebar">
								<?php self::sidebar_mini_cart(); ?>
								</div>
								<div class="user-information woocommerce <?php echo esc_attr( $view_bew_whatsapp ); ?>">
																		
									<?php
									$checkout = new WC_Checkout();	
									
									if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 ) { // WPCS: input var ok, CSRF ok.

										wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );
										wc_clear_notices();

									} else {

										$non_js_checkout = ! empty( $_POST['woocommerce_checkout_update_totals'] ); // WPCS: input var ok, CSRF ok.

										if ( wc_notice_count( 'error' ) === 0 && $non_js_checkout ) {
											wc_add_notice( __( 'The order totals have been updated. Please confirm your order by pressing the "Place order" button at the bottom of the page.', 'woocommerce' ) );
										}
										
										if($mini_cart_checkout == 'bew-opc'){ ?>										
											<form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
										<?php 
										} ?>
										
										
												<?php if ( $checkout->get_checkout_fields() ) { ?>

													<h4><?php esc_html_e( 'Customer details', 'briefcase-elementor-widgets' ); ?></h4>									
													<div class="woocommerce-billing-fields__field-wrapper">
														<?php
																						
														$fields = $checkout->get_checkout_fields( 'billing' );

														foreach ( $fields as $key => $field ) {
															woocommerce_form_field( $key, $field, $checkout->get_value( $key ) );
														}
														?>
													</div>	

												<?php }
												
										if($mini_cart_checkout == 'bew-opc'){ ?>
										
												<h3 id="order_review_heading"><?php esc_html_e( 'Payment Details', 'briefcase-elementor-widgets' ); ?></h3>
												
												<div id="order_review" class="woocommerce-checkout-review-order">
													<?php do_action( 'woocommerce_checkout_order_review' ); ?>
												</div>

											</form>
																				
											<div class="bew-opc">bew-opc</div>
										<?php 
										} 
										
									}
									?>

								</div> <?php
								if($mini_cart_checkout == 'bew-whatsapp'){ ?>
									<div class="bew-whatsapp-order <?php echo esc_attr( $view_bew_whatsapp ); ?>">
										<a href="<?php echo $url_w; ?> " class="bewwhatsapp_button" id="bewwhatsapp_button" target="_blank"><i class="ion-social-whatsapp-outline" aria-hidden="true"></i> Send my Order</a>
									</div>
									<div class="bew-wao">bew-wao</div> 
									<?php 
								}
								?>
							</div>		
						</div>
					</div>
				<?php 
	
			}	
		}
		
		// JS for update the quantity data
		wc_enqueue_js( "
		
		jQuery( document ).ready( function() {
			
			var wbn = '$wbn';
			var wbm = '$wbm';
			var user_info = ' Client: ';
			var count = '$count'; 
			
			jQuery('.woocommerce-billing-fields__field-wrapper input').each(function() {						
				var id = jQuery(this).attr('id').replace('billing_', '');
				var value = jQuery(this).val();				
				var input_values = id + '=' + value;
				
				user_info += (input_values + ' ');				
				
			});
			
				user_info += ' Thank You';	
			
			jQuery('.bewwhatsapp_button').each(function(){
				this.href = this.href.replace('whatsapp-number', wbn).replace('I+want+to+buy', wbm).replace('user+info', user_info);
			});
			
			jQuery('.woocommerce-billing-fields__field-wrapper input').on('blur', function(){

				user_info= 'Client: ';
				
				jQuery('.woocommerce-billing-fields__field-wrapper input').each(function() {						
					var id = jQuery(this).attr('id').replace('billing_', '');
					var value = jQuery(this).val();				
					var input_values = id + '=' + value;
					
					user_info += (input_values + ' ');				
				
				});
				
					user_info += ' Thank You';	
				
				jQuery('.bewwhatsapp_button').each(function(){
			
				this.href = this.href.replace('whatsapp-number', wbn).replace('I+want+to+buy', wbm).replace(/\Client:.*/, user_info);
				
				});
				
					
			});						
						
			jQuery(document.body ).on('added_to_cart removed_from_cart wc_fragments_refreshed',function() {
				
				jQuery('.bewwhatsapp_button').each(function(){
					this.href = this.href.replace('whatsapp-number', wbn).replace('I+want+to+buy', wbm).replace('user+info', user_info);
				});
				
				setTimeout(function() {
					if( jQuery('.bew-empty-cart').length ){				
						jQuery('.bew-whatsapp-order').addClass('hide-bew-whatsapp'); 
						jQuery('.user-information').addClass('hide-bew-whatsapp');
						
					}else {					
						jQuery('.bew-whatsapp-order').removeClass('hide-bew-whatsapp');
						jQuery('.user-information').removeClass('hide-bew-whatsapp');						
					}					
				}, 50);
				jQuery(document.body).trigger('update_cart');				
				jQuery(document.body).trigger('update_checkout');
			
			});
					
		} );
		
				
		" );
		
	}
				
	protected function render() {
		
		$settings = $this->get_settings(); 
		$cart = $settings['woo_orders_cart_style'];	
		$icon = $settings['woo_orders_icon_type'];			
		$arrow = $settings['woo_orders_arrow_before_span'];
		$item = $settings['woo_orders_text_after_span'];
		$mini_cart_style = $settings['bew_woo_mini_cart_style'];
		$wc_session = '';
		
		if (is_shop() && Elementor\Plugin::instance()->editor->is_edit_mode()) {				
			
			if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
					
			} else{
				$post_id = $_GET['post'];
				update_option( '_bew_woo_order_shop_id', $post_id );
				
			}			
		}			
				
		// Icon type
		if ( 'cart' == $icon) {
			$icon_type = 'fa fa-shopping-cart';
		}
		if ( 'bag' == $icon) {
			$icon_type = 'fa fa-shopping-bag';
		}
		if ( 'basket' == $icon) {
			$icon_type = 'fa fa-shopping-basket';
		}
		if ( 'line-handbag' == $icon) {
			$icon_type = 'icon-handbag';
		}
		if ( 'line-bag' == $icon) {
			$icon_type = 'icon-bag';
		}
		if ( 'line-basket' == $icon) {
			$icon_type = 'icon-basket';
		}
					
		// If quantity after span
		$wrap_classes = array( );
		if ( 'yes' == $arrow and 'b' == $cart) {
			$wrap_classes[] = 'woo-arrow';
		}
		if ( 'yes' == $arrow and 'd' == $cart) {
			$wrap_classes[] = 'woo-arrow';
		}
										
		// If quantity before span					
		if ( 'yes' == $item and 'c' == $cart) {
			$wrap_classes[] = 'woo-item';
		}					
		if ( 'yes' == $item and 'd' == $cart) {
			$wrap_classes[] = 'woo-item';
		}			
									
		$wrap_classes = implode( ' ', $wrap_classes );		
										
		// cart style options					
		if ( 'a' == $cart ) {
			$type_classes = 'circle';
		}
		elseif ( 'b' == $cart ) {
			$type_classes = 'square';
		}
		elseif ( 'c' == $cart ) {
			$type_classes = 'minimalist';
		}
		elseif ( 'd' == $cart ) {
			$type_classes = 'custom';
		}
		
		if($mini_cart_style == 'cart-sidebar'){ 
			$url = "";
		} else {			
			$url = wc_get_cart_url();			
		}
			
		$count = WC()->cart->cart_contents_count;
			
		// Float Cart 
		?>
		<div class="bew-woo-orders-cart">
			<div class="woo-orders-header-cart <?php echo esc_attr( $wrap_classes  ); ?>" data-icon="<?php echo esc_attr( $icon_type ); ?>" data-type="<?php echo esc_attr( $type_classes ); ?>">
				<a class="woo-orders-menucart <?php echo esc_attr( $type_classes ); ?>" href=" <?php echo $url?>" title="View your shopping cart">				
				<i class="<?php echo esc_attr( $icon_type ); ?>"></i> 
				<span class="woo-orders-cart-quantity <?php echo esc_attr( $type_classes ); ?>"><?php echo $count ?></span>				
				</a>
			</div>
		</div>
		<?php 
				
		$this->bew_woominicart();

		if (isset(WC()->session)) {
			if (!WC()->session->has_session()) {
				$wc_session = "no-session";
		   }
		}
		
		wp_localize_script( 'woo-general', 'woo_orders_vars', array(
							'ajaxurl'          => admin_url( 'admin-ajax.php' ),
							'nonce'            => wp_create_nonce( 'bewwoo-security' ),
							'is_mini_cart' 	   => 'active',
							'icon_type' 	   => $icon_type,
							'type_classes' 	   => $type_classes,
							'wc_session' 	   => $wc_session
						)
		);
				
	}

}
