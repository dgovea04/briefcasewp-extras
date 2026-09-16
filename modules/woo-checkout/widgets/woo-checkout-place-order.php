<?php
namespace BriefcasewpExtras\Modules\WooCheckout\Widgets;

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

class Woo_Checkout_Place_Order extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-place-order';
	}

	public function get_title() {
		return __( 'Checkout Place Order', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-checkout' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'woo_checkout_place_order_content',
			[
				'label' => __( 'Place Order Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
            'woo_checkout_place_order_button_show',
            [
                'label'         => __( 'Place Order Button', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

		$this->add_control(
            'woo_checkout_place_order_button_hide_on_desktop',
            [
                'label'         => __( 'Hide on Desktop', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [
					   'woo_checkout_place_order_button_show' => 'yes'
				],
            ]
        );
		
		$this->add_control(
            'woo_checkout_place_order_button_hide_on_mobile',
            [
                'label'         => __( 'Hide on Mobile', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [
					   'woo_checkout_place_order_button_show' => 'yes'
				],
            ]
        );
		
		$this->add_control(
		    'woo_checkout_place_order_button_text',
		    [
		        'label' 		=> __( 'Place Order Button Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Place order', 'bew-extras' ) ,
				'condition' => [
					   'woo_checkout_place_order_button_show' => 'yes'
				],
		        'dynamic' 		=> [
		            'active' 	=> true,
		        ]
		    ]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'woo_checkout_return_cart_content',
			[
				'label' => __( 'Return to Cart', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
            'woo_checkout_return_cart_show',
            [
                'label'         => __( 'Return to Cart', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );


		$this->add_control(
		    'woo_checkout_return_cart_text',
		    [
		        'label' 		=> __( 'Return to Cart Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Return to Cart', 'bew-extras' ) ,			
		        'dynamic' 		=> [
		            'active' 	=> true,
		        ]

		    ]
		);

		$this->end_controls_section();

		//section general style
		$this->start_controls_section(
			'woo_checkout_place_order_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'woo_checkout_place_order_general_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_place_order_general_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_place_order_general_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'woo_checkout_place_order_general_alignment',
            [
                'label' 	   => __( 'Alignment', 'elementor' ),
                'type' 		   => Controls_Manager::CHOOSE,
                'options' 	   => [
                    'flex-start	' 		=> [
                        'title' 	=> __( 'Left', 'elementor' ),
                        'icon' 		=> 'eicon-text-align-left',
                    ],
                    'center' 	=> [
                        'title' 	=> __( 'Center', 'elementor' ),
                        'icon' 		=> 'eicon-text-align-center',
                    ],
                    'flex-end' 	=> [
                        'title' 	=> __( 'Right', 'elementor' ),
                        'icon' 		=> 'eicon-text-align-right',
                    ],
                ],
                'default' 	=> '',
                'toggle' 	=> true,
                'selectors' => [
                    '{{WRAPPER}} #bew-place-order .bew-checkout__actions' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
		
		//section button style
		$this->start_controls_section(
			'woo_checkout_place_order_btn_style',
			[
				'label' => __( 'Order Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_place_order_btn_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button',
			]
		);
		
        $this->add_responsive_control(
            'woo_checkout_place_order_btn_width',
            [
                'label' => __( 'Width', 'bew-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 1000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );		

		$this->start_controls_tabs(
			'woo_checkout_place_order_btn_separator',			
		);

		$this->start_controls_tab(
			'woo_checkout_place_order_btn_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_place_order_btn_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_place_order_btn_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_place_order_btn_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_place_order_btn_hover',
			[
				'label'     => __( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_place_order_btn_text_color_hover',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_place_order_btn_color_hover',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_place_order_btn_border_hover',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button:hover',
			]
		);

        $this->add_control(
            'woo_checkout_place_order_btn_border_hover_transition',
            [
                'label' 	=> __( 'Transition Duration', 'bew-extras' ),
                'type' 		=> Controls_Manager::SLIDER,
                'range' 	=> [
                    'px' 	=> [
                        'max' 	=> 3,
                        'step' 	=> 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button:hover' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'woo_checkout_place_order_btn_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_place_order_btn_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_place_order_btn_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-place-order-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		//section button style
		$this->start_controls_section(
			'woo_checkout_return_cart_style',
			[
				'label' => __( 'Return to Cart', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_return_cart_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button',
			]
		);

		$this->start_controls_tabs(
			'woo_checkout_return_cart_separator',
		);

		$this->start_controls_tab(
			'woo_checkout_return_cart_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_return_cart_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_return_cart_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_return_cart_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_return_cart_hover',
			[
				'label'     => __( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_return_cart_text_color_hover',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_return_cart_color_hover',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_return_cart_border_hover',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button:hover',
			]
		);

        $this->add_control(
            'woo_checkout_return_cart_border_hover_transition',
            [
                'label' 	=> __( 'Transition Duration', 'bew-extras' ),
                'type' 		=> Controls_Manager::SLIDER,
                'range' 	=> [
                    'px' 	=> [
                        'max' 	=> 3,
                        'step' 	=> 0.1,
                    ],
                ],
                'selectors' => [
                    '{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button:hover' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'woo_checkout_return_cart_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_return_cart_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_return_cart_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #bew-place-order .bew-checkout__actions .bew-components-checkout-return-to-cart-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
	}
	
	function bew_woocommerce_checkout_place_order() {
		
		$settings = $this->get_settings_for_display();
				
		$return_cart_show        = $settings['woo_checkout_return_cart_show'];
		$return_cart_text   	 = $settings['woo_checkout_return_cart_text'];
		$place_order_button_show = $settings['woo_checkout_place_order_button_show'];
		$place_order_button_text = $settings['woo_checkout_place_order_button_text'];

		// The template name. 
		$template_name = 'checkout/place-order.php'; 

		// default args
		$args =  array(
               'checkout'           => WC()->checkout(),
			   'return_cart_show'   => $return_cart_show,
			   'return_cart_text'   => apply_filters( 'woocommerce_return_cart_text', __( $return_cart_text, 'woocommerce' ) ),
			   'place_order_button_show'  => $place_order_button_show,
               'place_order_button_text'  => apply_filters( 'woocommerce_order_button_text', __( $place_order_button_text, 'woocommerce' ) ),
            );
		
		// default template
		$template_path = ''; // use default which is usually "woocommerce"
		
		// default path (look in plugin file!)
		$default_path = BEW_EXTRAS_PATH . '/woocommerce/';
						
		//wc_get_template( 'checkout/place-order.php' ); 
		wc_get_template($template_name, $args, $template_path, $default_path);
    }

	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
					
		$settings = $this->get_settings_for_display();
		
		$hide_on_desktop         = $settings['woo_checkout_place_order_button_hide_on_desktop'];
		$hide_on_mobile          = $settings['woo_checkout_place_order_button_hide_on_mobile'];
		
		if ( !wp_is_mobile() && $hide_on_desktop == "yes" ) return;
		if ( wp_is_mobile() && ($hide_on_mobile == "yes" ) ) return;
		
		if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			
			$this->bew_woocommerce_checkout_place_order();
			
		}else{
					
			if( is_checkout() ){ 
			
				$this->bew_woocommerce_checkout_place_order(); 
				
			}
		}			
		
	}

	protected function _content_template() {
		
	}
	
}
