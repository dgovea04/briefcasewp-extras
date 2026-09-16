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
use WC_Session_Handler;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Cart_Totals extends Base_Widget {

	public function get_name() {
		return 'woo-cart-totals';
	}

	public function get_title() {
		return __( 'Woo Cart Totals', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-cart' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general', 'bew-checkout'  ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {

        // Cart Totals General
        $this->start_controls_section(
            'cart_totals_general',
            [
                'label' => esc_html__( 'General', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'cart_totals_general_loader',
			[
				'label' 		=> __( 'Loading', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'description' 	=> __( 'Enabled loading effect on update cart', 'bew-extras' ),	
			]
		);

		$this->add_control(
            'cart_totals_general_loader_type',
            [
                'label' => esc_html__( 'Loading Type', 'bew-extras' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'spinner',
				'label_block' => true,
				'prefix_class' => 'bew-cart-loader-type-',
                'options' => [
                    'spinner'    => esc_html__( 'Spinner', 'bew-extras' ),
                    'skeleton' => esc_html__( 'Skeleton', 'bew-extras' ),
                ],
				'condition' 	=> [
					'cart_totals_general_loader' => 'yes',						
				],					
            ]
        );
		
        $this->end_controls_section();
		
		//Heading controls		
		$this->start_controls_section(
			'section_heading',
			[
				'label' => __( 'Sections', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'section_heading_show_hide',
			[
				'label' 		=> __( 'Heading', 'elementor' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> 'block',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals h2' => 'display: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'section_heading_text',
			[
				'label' 		=> __( 'Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Cart Totals', 'bew-extras' ),
				'placeholder' 	=> __( 'Type here', 'bew-extras' ),
				'condition' 	=> [
					'section_heading_show_hide' => 'block'
				],
			]
		);

		$this->add_control(
			'section_sub_total_show_hide',
			[
				'label' 		=> __( 'Sub Total', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'flex',
				'default' 		=> 'flex',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .cart-subtotal' => 'display: {{VALUE}}',
				],
				'separator' 	=> 'before'
			]
		);

		$this->add_control(
			'section_shipping_show_hide',
			[
				'label' 		=> __( 'Shipping', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> 'block',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping' => 'display: {{VALUE}}',
				],
				'separator' 	=> 'before'
			]
		);

		$this->add_control(
			'section_shipping_custom_show_hide',
			[
				'label' 		=> __( 'Shipping Custom Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> [
					'section_shipping_show_hide' => 'block'
				],
			]
		);
		
		$this->add_control(
			'section_shipping_custom_text',
			[
                'label' => esc_html__( 'Custom Text', 'bew-extras' ),
                'type' => Controls_Manager::TEXTAREA,
				'ai' => [
					'type' => 'text',
				],
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'Enter your content', 'elementor' ),
				'default' => esc_html__( 'Add Your Custom Text Here', 'elementor' ),
				'condition' 	=> [
					'section_shipping_custom_show_hide' => 'yes',
					'section_shipping_show_hide' => 'block',
				],				
			]
		);	
			
		$this->add_control(
			'section_shipping_calculator_show_hide',
			[
				'label' 		=> __( 'Shipping Calculator', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> 'block',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-components-totals-item__description' => 'display: {{VALUE}}',
				],
				'condition' 	=> [
					'section_shipping_show_hide' => 'block'
				],
			]
		);

		$this->add_control(
			'section_shipping_options_show_hide',
			[
				'label' 		=> __( 'Shipping Options', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> 'block',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-components-shipping-rates-control' => 'display: {{VALUE}}',
				],
				'condition' 	=> [
					'section_shipping_show_hide' => 'block'
				],
			]
		);
				
		$this->add_control(
			'section_coupon_show_hide',
			[
				'label' 		=> __( 'Coupon Code', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> 'block',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-totals-coupon' => 'display: {{VALUE}} !important',
				],
				'separator' 	=> 'before'
			]
		);

		$this->add_control(
			'section_coupon_layout', [
				'label' => __( 'Coupon Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'collapse',
				'options' => [
					'collapse' => 'Collapse',
					'input' => 'Input',
				],
				'prefix_class' => 'bew-cart-coupon-layout-',
				'condition' 	=> [
					'section_coupon_show_hide' => 'block'
				],
			]
		);
	
		$this->add_control(
			'section_totals_show_hide',
			[
				'label' 		=> __( 'Totals', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'flex',
				'default' 		=> 'flex',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .order-total' => 'display: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'section_order_notes_show_hide',
			[
				'label' 		=> __( 'Order Notes', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'separator' 	=> 'before'
			]
		);
		
		$this->add_control(
			'section_checkout_show_hide',
			[
				'label' 		=> __( 'Proceed Button', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> 'block',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .wc-proceed-to-checkout a.checkout-button' => 'display: {{VALUE}} !important',
				],
				'separator' 	=> 'before'
			]
		);
		
		$this->add_control(
            'section_checkout_icon_show',
            [
                'label'         => __( 'Arrow Icon Button', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'bew-proceed-button-icon-show-',
            ]
        );
		
		
		$this->add_control(
			'section_checkout_bew_cart_pdf_show_hide',
			[
				'label' 		=> __( 'Bew Cart Pdf Button', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'block',
				'default' 		=> '',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals #bew-cart-pdf-button' => 'display: {{VALUE}} !important',
				],
				'separator' 	=> 'before'
			]
		);

		$this->end_controls_section();

        // Style tab
        $this->start_controls_section(
            'cart_totals_loader_style_section',
            [
                'label' => __( 'Loading', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' 	=> [
					'cart_totals_general_loader' => 'yes',
					'cart_totals_general_loader_type' => 'spinner',	 			
				],
            ]
        );
			$this->add_control(
			'cart_totals_loader_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'condition' 	=> [
					'cart_totals_general_loader' => 'yes',
					'cart_totals_general_loader_type' => 'spinner',					
				],
				'selectors' 	=> [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals.bew-cart-loader-type-spinner .blockUI.blockOverlay' => 'background-color: {{VALUE}} !important;',
				],
			]
		);
			
        $this->end_controls_section();

		// Cart totals section title
		$this->start_controls_section(
			'cart_totals_heading_style',
			[
				'label' 		=> __( 'Heading', 'bew-extras' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,
				'condition' 	=> [
					'section_heading_show_hide' => 'block'
				],
			]
		);
				
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cart_totals_heading_typography',
				'label' => __( 'Typography', 'elementor' ),
				'selector' => '{{WRAPPER}} .bew-cart-totals .bew-cart__totals-title',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'cart_totals_heading_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-cart-totals .bew-cart__totals-title',				
			]
		);

        $this->add_responsive_control(
            'cart_totals_heading_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bew-cart-totals .bew-cart__totals-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

		$this->add_responsive_control(
			'cart_totals_heading_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-cart__totals-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'cart_totals_heading_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-cart__totals-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'cart_totals_heading_alignment',
			[
				'label'		=> __( 'Alignment', 'elementor' ),
				'type' 		=>Controls_Manager::CHOOSE,
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
				'default' 	=> 'left',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .bew-cart__totals-title' => 'text-align: {{VALUE}}',
				]
			]
		);

		$this->end_controls_section();

		//Cart Table
		$this->start_controls_section(
			'style_cart_totals',
			[
				'label' 		=> __( 'Table', 'elementor' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' 			=> 'style_cart_totals_background',
				'label' 		=> __( 'Background', 'elementor' ),
				'types' 		=> [ 'classic', 'gradient' ],
				'selector' 		=> '{{WRAPPER}} .bew-cart-totals .shop_table',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'style_cart_totals_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-cart-totals .shop_table',
				'separator'		=> 'before'
			]
		);

        $this->add_responsive_control(
            'style_cart_totals_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bew-cart-totals .shop_table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
			
		$this->add_responsive_control(
			'style_cart_totals_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .shop_table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'style_cart_totals_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .shop_table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
				
		$this->end_controls_section();

		// Table Content controls		 
		$this->start_controls_section(
			'section_table_content',
			[
				'label' 		=> __( 'Table Content', 'bew-extras' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'table_content_tab',
			[ ]
		);

		$this->start_controls_tab(
			'table_content_label',
			[
				'label' 		=> __( 'Label', 'elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'table_content_label_typography',
				'label' 		=> __( 'Typography', 'elementor' ),
				'selector' 		=> '{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__label, {{WRAPPER}} .bew-cart-totals .bew-form-additional label',
			]
		);

		$this->add_control(
			'table_content_label_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__label, {{WRAPPER}} .bew-cart-totals .bew-form-additional label' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_content_label_color',
			[
				'label' 		=> __( 'Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__label, {{WRAPPER}} .bew-cart-totals .bew-form-additional label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
            'table_content_label_hover',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Hover', 'elementor' ),
                'separator' => 'before'
            ]
        );

		$this->add_control(
			'table_content_label_hover_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__label:hover, {{WRAPPER}} .bew-cart-totals .bew-form-additional label:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_content_label_hover_color',
			[
				'label' 		=> __( 'Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__label:hover, {{WRAPPER}} .bew-cart-totals .bew-form-additional label:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'table_content_value',
			[
				'label' 		=> __( 'Value', 'bew-extras' ),
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 			=> 'table_content_value_typography',
				'label' 		=> __( 'Typography', 'elementor' ),
				'selector' 		=> '{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__value, {{WRAPPER}} .bew-cart-totals .bew-components-totals-item__value .woocommerce-Price-amount.amount',
			]
		);

		$this->add_control(
			'table_content_value_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__value' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_content_value_color',
			[
				'label' 		=> __( 'Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__value  .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
            'table_content_value_hover',
            [
                'type' => Controls_Manager::HEADING,
                'label' => __( 'Hover', 'elementor' ),
                'separator' => 'before'
            ]
        );

		$this->add_control(
			'table_content_value_hover_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__value:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'table_content_value_hover_color',
			[
				'label' 		=> __( 'Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item__value:hover  .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'table_content_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-cart-totals .bew-components-totals-item',
				'separator'		=> 'before'
			]
		);

        $this->add_responsive_control(
            'table_content_border_radius',
            [
                'label' => __( 'Border Radius', 'elementor' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} .bew-cart-totals .bew-components-totals-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_responsive_control(
			'table_content_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'table_content_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		//Shipping 		
		$this->start_controls_section(
			'cart_shipping',
			[
				'label'			=> __( 'Shipping', 'bew-extras' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'cart_shipping_typography',
				'label' 	=> __( 'Typography', 'elementor' ),
				'selector' 	=> '{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping',
			]
		);

		$this->add_control(
			'cart_shipping_description_color',
			[
				'label'     => __( ' Description Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .woocommerce-shipping-totals .bew-components-totals-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bew-cart-totals .woocommerce-shipping-totals .bew-components-totals-item .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',					
				],
			]
		);

		$this->add_control(
            'cart_shipping_custom_text',
            [
                'label' => __( 'Shipping Custom Text', 'bew-extras' ),
                'type' => Controls_Manager::HEADING, 
				'separator'		=> 'before'
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'cart_shipping_custom_text_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-shipping-custom-text',
			]
		);

		$this->add_control(
			'cart_shipping_custom_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-shipping-custom-text' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'cart_shipping_custom_text_borders',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-shipping-custom-text',				
			]
		);

		$this->add_responsive_control(
			'cart_shipping_custom_text_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-shipping-custom-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'cart_shipping_custom_text_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-shipping-custom-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
            'cart_shipping_options_heading',
            [
                'label' => __( 'Shipping Options', 'bew-extras' ),
                'type' => Controls_Manager::HEADING, 
				'separator'		=> 'before'
            ]
        );

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'cart_shipping_options_label_typography',
				'label' 	=> __( 'Label Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-cart-totals ul#shipping_method li label',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'cart_shipping_options_amount_typography',
				'label' 	=> __( 'Amount Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-cart-totals ul#shipping_method li label',
			]
		);
		
		$this->add_control(
			'cart_shipping_rates_color',
			[
				'label'     => __( 'Radio Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .woocommerce-shipping-methods li input[type=radio]:checked + label:after' => 'background: {{VALUE}};',
					'{{WRAPPER}} .bew-cart-totals .bew-components-shipping-rates-control .woocommerce-shipping-methods li label:before' => 'border-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cart_shipping_rates_label_color',
			[
				'label'     => __( 'Label Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals ul#shipping_method li label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cart_shipping_rates_label_amount_color',
			[
				'label'     => __( 'Amount Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals ul#shipping_method li .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'cart_shipping_rates_borders',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-cart-totals .woocommerce-shipping-methods li:after',				
			]
		);

		$this->add_responsive_control(
			'cart_shipping_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-components-shipping-rates-control' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'cart_shipping_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-shipping .bew-components-shipping-rates-control' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();		
		
		// Apply coupon
        $this->start_controls_section(
            'cart_total_coupon_style',
            [
                'label' => __( 'Coupon Code', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_control(
                'cart_total_coupon_general_heading',
                [
                    'label' => __( 'General', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING,                    
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_total_coupon_general_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .bew-cart-totals .bew-components-totals-coupon .bew-order-review-coupon',
                ]
            );

            $this->add_control(
                'cart_total_coupon_general_color',
                [
                    'label' => __( 'Text Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-components-totals-coupon .bew-order-review-coupon' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_general_bg_color',
                [
                    'label' => __( 'Background Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-components-totals-coupon .bew-order-review-coupon' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );
			
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_total_coupon_general_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .bew-cart-totals .bew-components-totals-coupon:after',
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_general_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-components-totals-coupon .bew-cart_coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_general_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-components-totals-coupon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_button_heading',
                [
                    'label' => __( 'Button', 'elementor' ),
                    'type' => Controls_Manager::HEADING,                    
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_total_button_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button',
                ]
            );

			$this->start_controls_tabs( 'cart_total_coupon_button_tabs' );
			
			$this->start_controls_tab( 'cart_total_coupon_button_normal',
				[
					'label' => __( 'Normal', 'briefcase-extras' ),
				]
			);

            $this->add_control(
                'cart_total_coupon_button_color',
                [
                    'label' => __( 'Text Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_button_bg_color',
                [
                    'label' => __( 'Background Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );
			
			$this->end_controls_tab();

			$this->start_controls_tab( 'cart_total_coupon_button_hover',
				[
					'label' => __( 'Hover', 'briefcase-extras' ),
				]
			);

            $this->add_control(
                'cart_total_coupon_button_hover_color',
                [
                    'label' => __( 'Text Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_button_hover_bg_color',
                [
                    'label' => __( 'Background Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

			$this->add_control(
				'cart_total_coupon_button_border_color_hover',
				[
					'label' => __( 'Border Color', 'briefcase-extras' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'cart_total_coupon_button_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button:hover' => 'border-color: {{VALUE}}',
					],
				]
			);
			
			$this->end_controls_tab();

			$this->end_controls_tabs();
		
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_total_coupon_button_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button',
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_button_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_button_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_button_width',
                [
                    'label' => __( 'Button Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],                    
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon .button' => 'width: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );
			
            $this->add_control(
                'cart_total_coupon_inputbox_heading',
                [
                    'label' => __( 'Input Box', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_total_coupon_inputbox_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text, {{WRAPPER}} .bew-cart-totals .bew-cart_coupon label',
                ]
            );
			
            $this->add_control(
                'cart_total_coupon_inputbox_color',
                [
                    'label' => __( 'Input Box Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_total_coupon_inputbox_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text',
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_inputbox_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_inputbox_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_total_coupon_inputbox_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_inputbox_width',
                [
                    'label' => __( 'Input Box Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],                    
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text' => 'width: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

            $this->add_control(
                'cart_total_coupon_inputbox_height',
                [
                    'label' => __( 'Input Box Height', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],                    
                    'selectors' => [
                        '{{WRAPPER}} .bew-cart-totals .bew-cart_coupon input.input-text' => 'height: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();		

		//Total		
		$this->start_controls_section(
			'cart_total',
			[
				'label'			=> __( 'Total', 'bew-extras' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'cart_total_typography',
				'label' 	=> __( 'Typography', 'elementor' ),
				'selector' 	=> '{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item .woocommerce-Price-amount.amount, {{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item .bew-components-totals-item__label',
			]
		);

		$this->add_control(
			'cart_total_description_color',
			[
				'label'     => __( ' Description Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',					
				],
			]
		);

		$this->add_control(
			'cart_total_label_color',
			[
				'label'     => __( 'Label Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item .bew-components-totals-item__label' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'cart_total_rates_label_amount_color',
			[
				'label'     => __( 'Amount Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item .woocommerce-Price-amount.amount' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'cart_total_rates_borders',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item',				
			]
		);

		$this->add_responsive_control(
			'cart_total_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'cart_total_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-cart-totals .bew-components-totals-footer-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();			

		//Checkout Button		
		$this->start_controls_section(
			'checkout_button',
			[
				'label'			=> __( 'Proceed Button', 'bew-extras' ),
				'tab'   		=> Controls_Manager::TAB_STYLE,
				'condition' 	=> [
					'section_checkout_show_hide' => 'block'
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'checkout_button_typography',
				'label' 	=> __( 'Typography', 'elementor' ),
				'selector' 	=> '{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button',
			]
		);

		$this->start_controls_tabs(
			'cart_proceed_btn_tab',
			[ 'separator'		=> 'before' ]
		);

		$this->start_controls_tab(
			'cart_proceed_btn',
			[
				'label' 		=> __( 'Normal', 'elementor' ),
			]
		);

		$this->add_control(
			'checkout_button_color',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_button_bg',
			[
				'label'     => __( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'checkout_button_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button',
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'checkout_button_border_radius',
			[
				'label'         => __( 'Border Radius', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'cart_proceed_btn_hover',
			[
				'label' 		=> __( 'Hover', 'elementor' ),
			]
		);

		$this->add_control(
			'checkout_button_color_hover',
			[
				'label'     => __( 'Text Color', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'checkout_button_bg_hover',
			[
				'label'     => __( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button:hover' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'checkout_button_border_hover',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button:hover',
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'checkout_button_border_radius_hover',
			[
				'label'         => __( 'Border Radius', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
				
		$this->add_responsive_control(
			'checkout_button_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout a.checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout.bew-cart__submit-container a.checkout-button' => 'min-height:auto',
				],
				'separator'		=> 'before'
			]
		);

		$this->add_responsive_control(
			'checkout_button_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}}.elementor-widget-woo-cart-totals .bew-cart-totals .wc-proceed-to-checkout.bew-cart__submit-container' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'checkout_button_alignment',
			[
				'label'		=> __( 'Alignment', 'elementor' ),
				'type' 		=>Controls_Manager::CHOOSE,
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
				'default' 	=> 'left',
				'toggle'    => false,
				'selectors' => [
					'{{WRAPPER}} .wc-proceed-to-checkout.bew-cart__submit-container' => 'justify-content: {{VALUE}}',
				]
			]
		);
		
        $this->add_responsive_control(
            'checkout_button_width',
            [
                'label' => __( 'Button Width', 'bew-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 500,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} .wc-proceed-to-checkout.bew-cart__submit-container .checkout-button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
		
		$this->add_responsive_control(
			'checkout_button_icon_size',
			[
				'label' => __( 'Icon Size', 'bew-extras' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 10,
						'max' => 120,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cart-totals .bew-cart__submit-container .button::after' => 'font-size:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'section_checkout_icon_show' => 'yes',
				],			
			]
		);

		$this->end_controls_section();

	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$cart_total_text    		=  $settings['section_heading_text'];
		$cart_totals_loader  		=  $settings['cart_totals_general_loader'];
		$cart_totals_shipping_text  =  $settings['section_shipping_custom_text'];
		$cart_totals_order_notes    =  $settings['section_order_notes_show_hide'];
		
		
		if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			update_option( '_cart_total_text', $cart_total_text);
			update_option( '_cart_totals_loader', $cart_totals_loader);
			update_option( '_cart_totals_shipping_text', $cart_totals_shipping_text);
			update_option( '_cart_totals_order_notes', $cart_totals_order_notes);
		}
		
		//echo var_dump ($cart_items);
		
		if ( \WC()->cart->is_empty() ) {
				
		} else {
			?>
			<div class="bew-cart-totals">
				<?php include BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-cart-totals.php'; ?>
			</div>
			<?php
		}
		
	}
	
}

