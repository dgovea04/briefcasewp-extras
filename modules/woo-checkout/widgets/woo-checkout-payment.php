<?php
namespace BriefcasewpExtras\Modules\WooCheckout\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;   
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Payment extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-payment';
	}

	public function get_title() {
		return __( 'Checkout Payment', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-checkout' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' , 'bew-extras-scripts' ];
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [ 'elementor-icons-fa-solid' ];
		}
		return [];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		
		$this->start_controls_section(
			'woo_checkout_payment_section_title',
			[
				'label' => __( 'Section Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_payment_steps',
			[
				'label'         => __( 'Checkout Steps', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'On', 'bew-extras' ),
				'label_off'     => __( 'Off', 'bew-extras' ),
				'return_value'  => 'active',
				'default'       => 'active',
			]
		);

		$this->add_control(
			'woo_checkout_payment_vertical_line',
			[
				'label'         => __( 'Vertical Line', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition' => [
                    'woo_checkout_payment_steps' => 'active'
                ],
				'prefix_class' => 'steps-vertical-line-',
			]
		);
		
		$this->add_control(
            'woo_checkout_payment_title_show',
            [
                'label'         => __( 'Title', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );
		
		//order_button_text
		$this->add_control(
		    'woo_checkout_payment_section_title_text',
		    [
		        'label' 		=> __( 'Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Payment Methods', 'bew-extras' ) ,
                'condition' => [
                    'woo_checkout_payment_title_show' => 'yes'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
			'woo_checkout_payment_title_tag',
			[
				'label' 	=> __( 'HTML Tag', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'h3',
				'options' 	=> [
					'h1'  => __( 'H1', 'bew-extras' ),
					'h2'  => __( 'H2', 'bew-extras' ),
					'h3'  => __( 'H3', 'bew-extras' ),
					'h4'  => __( 'H4', 'bew-extras' ),
					'h5'  => __( 'H5', 'bew-extras' ),
					'h6'  => __( 'H6', 'bew-extras' ),
				],
                'condition' => [
                    'woo_checkout_payment_title_show' => 'yes'
                ],
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_description_show',
			[
				'label'         => __( 'Show/Hide Description', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'bew-extras' ),
				'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_description_text',
			[
				'label' 		=> __( 'Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( "Select payment options below.", 'bew-extras' ) ,
				   'condition' 	=> [
					   'woo_checkout_payment_description_show' => 'yes'
				   ],
				'dynamic' 		=> [
					'active' 		=> true,
				]
			]
		);

		$this->add_responsive_control(
            'woo_checkout_payment_title_alignment',
            [
                'label' 	   => __( 'Alignment', 'bew-extras' ),
                'type' 		   => Controls_Manager::CHOOSE,
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
                'toggle' 	=> true,
                'condition' => [
                    'woo_checkout_payment_title_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-payment-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_payment_methods_content',
			[
				'label' => __( 'Payment Methods', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_methods_layout',
			[
				'label' 	=> __( 'Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'tabs',
				'options' 	=> [
					'tabs'  => __( 'Tabs', 'bew-extras' ),
					'radio'  => __( 'Radio', 'bew-extras' ),
					'checkbox'  => __( 'Checkbox', 'bew-extras' ),					
				],				
			]
		);

		$this->add_control(
			'woo_checkout_payment_methods_radio_layout',
			[
				'label' 	=> __( 'Radio Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'bubble',
				'options' 	=> [
					'bubble'  => __( 'Bubble', 'bew-extras' ),
					'boxes'   => __( 'Boxes', 'bew-extras' ),
					'radio-button'  => __( 'Button', 'bew-extras' ),
				],
				'condition' => [				   
				   'woo_checkout_payment_methods_layout' => 'radio'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_methods_tabs_layout',
			[
				'label' 	=> __( 'Tab Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'standard-button',
				'options' 	=> [
					'standard-button'  => __( 'Standard', 'bew-extras' ),
					'tab-button'   => __( 'Button', 'bew-extras' ),
				],
				'condition' => [				   
				   'woo_checkout_payment_methods_layout' => 'tabs'
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_methods_show',
			[
				'label'         => __( 'Hide Method Label', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'On', 'bew-extras' ),
				'label_off'     => __( 'Off', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'prefix_class' => 'hide-method-label-',
			]
		);

		$this->add_control(
			'woo_checkout_payment_methods_description',
			[
				'label'         => __( 'Hide Description', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'On', 'bew-extras' ),
				'label_off'     => __( 'Off', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'prefix_class' => 'hide-method-description-',
			]
		);

		$this->add_control(
			'woo_checkout_payment_methods_paypal',
			[
				'label'         => __( 'Hide Paypal Description', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'On', 'bew-extras' ),
				'label_off'     => __( 'Off', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'prefix_class' => 'hide-paypal-description-',
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_methods_paypal_layout',
			[
				'label' 	=> __( 'Paypal Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'text',
				'options' 	=> [
					'text'  => __( 'Text', 'bew-extras' ),
					'img'   => __( 'Image', 'bew-extras' ),
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_methods_stripe_layout',
			[
				'label' 	=> __( 'Stripe Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'text',
				'options' 	=> [
					'text'  => __( 'Text', 'bew-extras' ),
					'img'   => __( 'Image', 'bew-extras' ),
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_methods_label_inside',
			[
				'label'         => __( 'Label Inside Layout', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'On', 'bew-extras' ),
				'label_off'     => __( 'Off', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',				
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_payment_form_review_section',
			[
				'label' => __( 'Multistep Review', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);		
				
		$this->add_control(
			'woo_checkout_payment_form_review',
			[
				'label'         => __( 'Multistep Review', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'bew-extras' ),
				'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'separator'     => 'before',
			]
		);

		$this->add_control(
			'woo_checkout_payment_contact_show',
			[
				'label'         => __( 'Contact', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-payment-contact-',
				'condition' => [				   
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_contact', [
				'label' => __( 'Contact text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Contact' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_payment_contact_show' => 'yes',
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_ship_show',
			[
				'label'         => __( 'Ship To', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-payment-ship-',
				'condition' => [				   
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_ship', [
				'label' => __( 'Ship To text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ship To' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_payment_ship_show' => 'yes',
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_bill_show',
			[
				'label'         => __( 'Bill To', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-payment-bill-',
				'condition' => [				   
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_bill', [
				'label' => __( 'Bill To text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Bill To' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_payment_bill_show' => 'yes',
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_method_show',
			[
				'label'         => __( 'Method', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-payment-method-',
				'condition' => [				   
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);		

		$this->add_control(
			'woo_checkout_payment_method', [
				'label' => __( 'Method text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Method' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_payment_method_show' => 'yes',
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);			

		$this->add_control(
			'woo_checkout_payment_change', [
				'label' => __( 'Change text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Change' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_payment_form_review' => 'yes'
				],
			]
		);		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'woo_checkout_payment_methods_button',
			[
				'label' => __( 'Order Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		//order_button
		$this->add_control(
            'woo_checkout_order_button_show',
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
		    'woo_checkout_order_button_text',
		    [
		        'label' 		=> __( 'Order Button Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Place order', 'bew-extras' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
            'woo_checkout_order_button_icon_show',
            [
                'label'         => __( 'Arrow Icon Button', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'bew-order-button-icon-show-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_privacy_policy',
            [
                'label'         => __( 'Privacy Policy Text', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-order-privacy-policy-show-',
            ]
        );
		
		$this->add_control(
			'woo_checkout_privacy_policy_position',
			[
				'label' 	=> __( 'Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'privacy-bottom',
				'options' 	=> [
					'privacy-top'  => __( 'Top', 'bew-extras' ),
					'privacy-bottom'  => __( 'Bottom', 'bew-extras' ),					
				],
				'condition' => [
                    'woo_checkout_privacy_policy' => 'yes'
                ],
			]
		);	
				
		$this->add_responsive_control(
            'woo_checkout_order_button_alignment',
            [
                'label' 	   => __( 'Alignment', 'bew-extras' ),
                'type' 		   => Controls_Manager::CHOOSE,
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
                'default' 	=> 'center',
                'toggle' 	=> true,
                'condition' => [
                    'woo_checkout_order_button_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-payment .place-order .button' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		//Section general style
		$this->start_controls_section(
			'woo_checkout_payment_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'woo_checkout_payment_general_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-payment .bew-components-checkout-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_payment_general_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-payment .bew-components-checkout-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		//Section title style
		$this->start_controls_section(
			'woo_checkout_payment_title_style',
			[
				'label' => __( 'Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'woo_checkout_payment_title_show' => 'yes'
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_payment_title_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-payment-title',
			]
		);

        $this->add_control(
			'woo_checkout_payment_title_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-step-heading .bew-payment-title' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_payment_title_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-step-heading .bew-payment-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_payment_title_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-step-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//Input Payment Methods
		$this->start_controls_section(
			'woo_checkout_pm_style',
			[
				'label' => __( 'Payment Methods', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'woo_checkout_pm_bg_color',
			[
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods, {{WRAPPER}} .bew-payment #payment .payment_methods' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods, {{WRAPPER}} .bew-payment #payment .payment_methods' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs('payment_methods');

		$this->start_controls_tab(
		    'woo_checkout_pm_titles',
		    [
		        'label' => __( 'Titles', 'bew-extras' ),
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'tabs'
                ],
		    ]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'tabs'
                ],
				'selector' 	=> '{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .bew-components-tabs__item .bew-components-tabs__item-content',
			]
		);

        $this->add_control(
			'woo_checkout_pm_text_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'tabs'
                ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .bew-components-tabs__item .bew-components-tabs__item-content' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_text_active_color',
			[
				'label'     => __( 'Active Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'tabs'
                ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .bew-components-tabs__item .bew-components-tabs__item-content.active' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
		    'woo_checkout_pm_label',
		    [
		        'label' => __( 'Label', 'bew-extras' ),
				'condition' => [
                    'woo_checkout_payment_methods_layout' => [ 'radio', 'checkbox' ],
                ],
		    ]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_label_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-payment #payment ul.payment_methods li.wc_payment_method > label',
				'condition' => [
                    'woo_checkout_payment_methods_layout' => [ 'radio', 'checkbox' ],
                ],
			]
		);

        $this->add_control(
			'woo_checkout_pm_label_text_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment ul.payment_methods li.wc_payment_method > label' => 'color: {{VALUE}}',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => [ 'radio', 'checkbox' ],
                ],
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_label_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment ul.payment_methods li.wc_payment_method > label' => 'background-color: {{VALUE}}',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => [ 'radio', 'checkbox' ],
                ],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_label_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-payment #payment ul.payment_methods li.wc_payment_method > label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => [ 'radio', 'checkbox' ],
                ],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_label_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-payment #payment ul.payment_methods li.wc_payment_method > label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => [ 'radio', 'checkbox' ],
                ],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
		    'woo_checkout_pm_checkbox',
		    [
		        'label' => __( 'Checkbox', 'bew-extras' ),
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'checkbox'
                ],
		    ]
		);

        $this->add_control(
			'woo_checkout_pm_checkbox_color',
			[
				'label'     => __( 'Box Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .checkbox #payment .payment_methods input[type="radio"] + label:before' => 'border-color: {{VALUE}}',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'checkbox'
                ],
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_checkbox_active_color',
			[
				'label'     => __( 'Box Checked Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .checkbox #payment .payment_methods input[type="radio"]:checked + label:before' => 'border-color: {{VALUE}} ',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'checkbox'
                ],
			]
		);

		$this->add_control(
			'woo_checkout_pm_checkbox_bg_color',
			[
				'label'     => __( 'Box Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .checkbox #payment .payment_methods input[type="radio"] + label:before' => 'background-color: {{VALUE}} ',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'checkbox'
                ],
			]
		);

		$this->add_control(
			'woo_checkout_pm_checkbox_checked_color',
			[
				'label'     => __( 'Check Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .checkbox #payment .payment_methods input[type="radio"]:checked + label:before' => 'color: {{VALUE}};',
				],
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'checkbox'
                ],
			]
		);
		
		$this->end_controls_tab();

		$this->start_controls_tab(
		    'woo_checkout_pm_contents',
		    [
		        'label' => __( 'Contents', 'bew-extras' ),
		    ]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_content_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .tab-content .bew-components-tabs__content,
								{{WRAPPER}} .bew-payment #payment .payment_methods .payment_box',				
			]
		);

        $this->add_control(
			'woo_checkout_pm_content_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .tab-content .bew-components-tabs__content,
					 {{WRAPPER}} .bew-payment #payment .payment_methods .payment_box' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_content_bg_color',
			[
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .tab-content .bew-components-tabs__content, 
					 {{WRAPPER}} .bew-payment #payment .payment_methods .payment_box' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .bew-payment #payment .payment_methods .payment_box::before' => 'border-bottom-color: {{VALUE}} !important;'
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
				'name' 		=> 'woo_checkout_pm_content_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .tab-content .bew-components-tabs__content, 
								{{WRAPPER}} .bew-payment #payment .payment_methods .payment_box:not(.payment_method_stripe),
								{{WRAPPER}} .bew-payment #payment .wc-credit-card-form',
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_pm_content_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .tab-content .bew-components-tabs__content,
					 {{WRAPPER}} .bew-payment #payment .payment_methods .payment_box' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_content_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-payment #payment .bew-components-checkout-payment-methods .tab-content .bew-components-tabs__content,
					 {{WRAPPER}} .bew-payment #payment .payment_methods .payment_box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		//Radio	
		$this->start_controls_section(
			'woo_checkout_pm_radio',
			[
				'label' => __( 'Radio', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'radio'
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_radio_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-payment .radio #payment ul.payment_methods li.wc_payment_method > label',
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_text_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio #payment ul.payment_methods li.wc_payment_method > label' => 'color: {{VALUE}} !important',
				],
			]
		);
		
		$this->start_controls_tabs(
			'woo_checkout_pm_radio_separator',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'woo_checkout_pm_radio_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_normal_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:before' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_bg_pm_radio_normal_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_pm_radio_checked',
			[
				'label'     => __( 'Checked', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_radio_checked_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_radio_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:before',
			]
		);

        $this->add_control(
			'woo_checkout_pm_radio_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_radio_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_radio_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .radio:not(radio-button) #payment ul.payment_methods li.wc_payment_method > label:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_heading',
			[
				'label' => __( 'Button Type', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'woo_checkout_shipping_radio_button_tabs' );

		$this->start_controls_tab(
			'woo_checkout_pm_radio_button_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_icon_color',
			[
				'label' => __( 'Icon Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_pm_radio_button_checked',
			[
				'label'     => __( 'Checked', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_radio_button_color_checked',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > input[type=radio]:checked + label' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'woo_checkout_pm_radio_button_color_bg_checked',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > input[type=radio]:checked + label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_border_color_checked',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'woo_checkout_pm_radio_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > input[type=radio]:checked + label' => 'border-color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_icon_color_checked',
			[
				'label' => __( 'Icon Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > input[type=radio]:checked + label:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_pm_radio_button_icon_bg_color_checked',
			[
				'label' => __( 'Icon Background', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > input[type=radio]:checked + label:before' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_radio_button_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label',
			]
		);

        $this->add_control(
			'woo_checkout_pm_radio_border_button_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_radio_button_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_radio_button_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .radio.radio-button #payment ul.payment_methods li.wc_payment_method > label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		/**
		 * Tab Button
		 */

		$this->start_controls_section(
			'woo_checkout_pm_tabs',
			[
				'label' => __( 'Tabs', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
				'condition' => [
                    'woo_checkout_payment_methods_layout' => 'tabs'
                ],
			]
		);

		$this->add_control(
			'woo_checkout_pm___heading',
			[
				'label' => __( 'Button Type', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'woo_checkout_shipping_tab_button_tabs' );

		$this->start_controls_tab(
			'woo_checkout_pm_tab_button_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_pm_tab_button_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_tab_button_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_tab_button_icon_color',
			[
				'label' => __( 'Icon Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_pm_tab_button_checked',
			[
				'label'     => __( 'Active', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_tab_button_color_active',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content.active' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'woo_checkout_pm_tab_button_color_bg_active',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content.active' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_tab_button_border_color_active',
			[
				'label' => __( 'Border Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'woo_checkout_pm_tab_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content.active' => 'border-color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_pm_tab_button_icon_color_active',
			[
				'label' => __( 'Icon Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content.active:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_pm_tab_button_icon_bg_color_active',
			[
				'label' => __( 'Icon Background', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content.active:before' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_pm_tab_button_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content',
			]
		);

        $this->add_control(
			'woo_checkout_pm_tab_border_button_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_tab_button_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_tab_button_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-payment .tabs.tab-button #payment ul.bew-components-tabs__list li>.bew-components-tabs__item-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		/**
		 * Stripe Labels
		 */
		$this->start_controls_section(
			'woo_checkout_payment_stripe_style',
			[
				'label' => __( 'Stripe', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_stripe_label',
			[
				'label'     => __( 'Input Label', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_stripe_label_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-payment #wc-stripe-cc-form label:not(.radio)',
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_label_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-payment #wc-stripe-cc-form label:not(.radio)' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_payment_stripe_label_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment #wc-stripe-cc-form label:not(.radio)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_payment_stripe_label_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment #wc-stripe-cc-form label:not(.radio)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_label_inside_translate',
			[
				'label' => __( 'TranslateY', 'bew-extras' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'em' => [
						'min' => 0,
						'max' => 100,
					],
				],	
				'selectors' => [
					'{{WRAPPER}} .bew-payment-methods.label-inside-yes #wc-stripe-cc-form label' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_label_line_hight',
			[
				'label' => __( 'Line Height', 'bew-extras' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' => 20,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 25,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-payment #wc-stripe-cc-form label:not(.radio)' => 'line-height: {{SIZE}}{{UNIT}};',
				],
				'separator' => 'after',
			]
		);

		/**
		 * Stripe Input Fields
		 */

		$this->add_control(
			'woo_checkout_payment_stripe_input_field',
			[
				'label'     => __( 'Input Field', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_payment_stripe_input_typographyrs',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' => '{{WRAPPER}} .bew-payment input, 
								{{WRAPPER}} .bew-payment select,
								{{WRAPPER}} .bew-payment .select2-selection,					
								{{WRAPPER}} .bew-payment option,
								{{WRAPPER}} .bew-payment textarea,
								{{WRAPPER}} .bew-payment .wc-stripe-elements-field',
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_input_color',
			[
				'label'     => __( 'Input Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-payment input, 
								 {{WRAPPER}} .bew-payment select,
								 {{WRAPPER}} .bew-payment .select2-selection,
								 {{WRAPPER}} .bew-payment option,
								 {{WRAPPER}} .bew-payment textarea,
								 {{WRAPPER}} .bew-payment .wc-stripe-elements-field' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_stripe_placeholder_color',
			[
				'label'     => __( 'Placeholder Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-payment input::-webkit-input-placeholder, 
								 {{WRAPPER}} .bew-payment select::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-payment .select2-selection::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-payment option::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-payment textarea::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-payment .wc-stripe-elements-field::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_input_background_color',
			[
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-payment input, 
								 {{WRAPPER}} .bew-payment select,
								 {{WRAPPER}} .bew-payment .select2-selection,
								 {{WRAPPER}} .bew-payment option,
								 {{WRAPPER}} .bew-payment textarea,
								 {{WRAPPER}} .bew-payment .wc-stripe-elements-field' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
				[
				'name' 		=> 'woo_checkout_stripe_payment_input_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .bew-paymentccinput, 
								{{WRAPPER}} .bew-paymentcselect,
								{{WRAPPER}} .bew-payment .select2-selection,
								{{WRAPPER}} .bew-payment textarea,
								{{WRAPPER}} .bew-payment .wc-stripe-elements-field',
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_input_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment input, 
					 {{WRAPPER}} .bew-payment select,
					 {{WRAPPER}} .bew-payment .select2-selection,
					 {{WRAPPER}} .bew-payment textarea,
					 {{WRAPPER}} .bew-payment .wc-stripe-elements-field' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_payment_stripe_input_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment input, 
					 {{WRAPPER}} .bew-payment select,
					 {{WRAPPER}} .bew-payment .select2-selection,
					 {{WRAPPER}} .bew-payment textarea,
					 {{WRAPPER}} .bew-payment .wc-stripe-elements-field' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_payment_stripe_input_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .form-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_stripe_input_wide_width',
			[
				'label'     => __( 'Form Row Wide Width', 'bew-extras' ),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
						],
						'%' => [
							'min' => 10,
							'max' => 100,
						],
					],				
				'size_units' => [ 'px' , '%'],
				'default' => [
					'unit' => '%',
					'size' => 100,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .form-row.form-row-wide' => 'width: {{SIZE}}{{UNIT}};',
				],			
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_stripe_input_first_width',
			[
				'label'     => __( 'Form Row First Width', 'bew-extras' ),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
						],
						'%' => [
							'min' => 10,
							'max' => 100,
						],
					],				
				'size_units' => [ 'px' , '%'],
				'default' => [
					'unit' => '%',
					'size' => 47,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .form-row.form-row-first' => 'width: {{SIZE}}{{UNIT}};',
				],			
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_input_last_width',
			[
				'label'     => __( 'Form Row Last Width', 'bew-extras' ),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
						],
						'%' => [
							'min' => 10,
							'max' => 100,
						],
					],				
				'size_units' => [ 'px' , '%'],
				'default' => [
					'unit' => '%',
					'size' => 47,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .form-row.form-row-last' => 'width: {{SIZE}}{{UNIT}};',
				],			
			]
		);
		
		$this->add_control(
			'woo_checkout_payment_stripe_input_height',
			[
				'label'     => __( 'Height', 'bew-extras' ),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
						],
						'%' => [
							'min' => 10,
							'max' => 100,
						],
					],				
				'size_units' => [ 'px' , '%'],
				'selectors' => [
					'{{WRAPPER}} .bew-payment input:not(#wc-stripe-new-payment-method), 
					 {{WRAPPER}} .bew-payment select,
					 {{WRAPPER}} .bew-payment .select2-selection,
					 {{WRAPPER}} .bew-payment textarea,
					 {{WRAPPER}} .bew-payment .wc-payment-form .wc-stripe-elements-field' => 'height: {{SIZE}}{{UNIT}};',
				],			
			]
		);

		$this->add_control(
			'woo_checkout_payment_stripe_input_position',
			[
				'label'     => __( 'Top Position', 'bew-extras' ),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
						'px' => [
							'min' => 0,
							'max' => 150,
						],
						'%' => [
							'min' => 10,
							'max' => 100,
						],
					],				
				'size_units' => [ 'px' , '%'],
				'selectors' => [
					'{{WRAPPER}} .bew-payment .wc-stripe-elements-field .__PrivateStripeElement' => 'top: {{SIZE}}{{UNIT}} !important;',
				],			
			]
		);
		
		$this->end_controls_section();

		//section button
		$this->start_controls_section(
			'woo_checkout_payment_btn_style',
			[
				'label' => __( 'Order Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_payment_btn_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .form-row.place-order #place_order',
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_btn_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto;',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_pm_btn_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_btn_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'woo_checkout_pm_btn_separator',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'woo_checkout_pm_btn_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_pm_btn_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_btn_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_pm_btn_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} #payment .form-row.place-order #place_order',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_pm_btn_hover',
			[
				'label'     => __( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_pm_btn_text_color_hover',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_pm_btn_color_hover',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #payment .form-row.place-order #place_order:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_pm_btn_border_hover',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} #payment .form-row.place-order #place_order:hover',
			]
		);

        $this->add_control(
            'woo_checkout_pm_btn_border_hover_transition',
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
                    '{{WRAPPER}} #payment .form-row.place-order #place_order:hover' => 'transition-duration: {{SIZE}}s',
                ],
            ]
        );

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		
		//section footer style
		$this->start_controls_section(
			'woo_checkout_payment_footer_style',
			[
				'label' => __( 'Terms and Conditions', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_payment_footer_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .form-row.place-order .woocommerce-terms-and-conditions-wrapper',
			]
		);

		$this->add_control(
			'woo_checkout_pm_footer_content_color',
			[
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-row.place-order' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'woo_checkout_pm_footer_content_text_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .form-row.place-order .woocommerce-terms-and-conditions-wrapper' => 'color: {{VALUE}}',
					'{{WRAPPER}} .form-row.place-order .woocommerce-terms-and-conditions-wrapper a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_footer_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .form-row.place-order .woocommerce-terms-and-conditions-wrapper' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_pm_footert_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .form-row.place-order .woocommerce-terms-and-conditions-wrapper' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}
		
	function bew_woocommerce_checkout_payment() {
		
		$settings = $this->get_settings_for_display();
		
		$payment_order_button_show    = $settings['woo_checkout_order_button_show'];
		$payment_order_button_text    = $settings['woo_checkout_order_button_text'];
		$payment_methods_layout       = $settings['woo_checkout_payment_methods_layout'];
		$payment_methods_radio_layout = $settings['woo_checkout_payment_methods_radio_layout'];
		$payment_methods_paypal_layout = $settings['woo_checkout_payment_methods_paypal_layout'];
		$payment_methods_stripe_layout = $settings['woo_checkout_payment_methods_stripe_layout'];
		$policy_position 		      = $settings['woo_checkout_privacy_policy_position'];	
				
		if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			update_option( '_payment_order_button', $payment_order_button_show );
			update_option( '_payment_order_button_text', $payment_order_button_text );
			update_option( '_payment_order_policy_position', $policy_position );
			update_option( '_payment_order_methods_layout', $payment_methods_layout );
			update_option( '_payment_order_methods_radio_layout', $payment_methods_radio_layout );
			update_option( '_payment_order_methods_paypal_layout', $payment_methods_paypal_layout );
			update_option( '_payment_order_methods_stripe_layout', $payment_methods_stripe_layout );
		}
		
        if ( WC()->cart->needs_payment() ) {
            $available_gateways = WC()->payment_gateways()->get_available_payment_gateways();
            WC()->payment_gateways()->set_current_gateway( $available_gateways );
        } else {
            $available_gateways = array();
        }

		// The template name. 
		$template_name = 'checkout/payment.php'; 

		// default args
		$args =  array(
                'checkout'           => WC()->checkout(),
                'available_gateways' => $available_gateways,
				'payment_methods_layout' => $payment_methods_layout,
				'payment_methods_radio_layout' => $payment_methods_radio_layout,
				'payment_order_button'  => $payment_order_button_show,					
                'bew_order_button_text'  => __( $payment_order_button_text, 'woocommerce' ),	
            );
		
		// default template
		$template_path = ''; // use default which is usually "woocommerce"
		
		// default path (look in plugin file!)
		$default_path = BEW_EXTRAS_PATH . '/woocommerce/';
						
		//wc_get_template( 'checkout/payment.php' ); 
		wc_get_template($template_name, $args, $template_path, $default_path);
    }

	function bew_review_form_block($name, $fill, $target, $content = '') {
		$settings = $this->get_settings_for_display();
		
		$payment_methods_form_change_text   = $settings['woo_checkout_payment_change'];
		
		?>
		<div class="bew-formReview-block">
			<div class="bew-formReview-inner">
				<div class="bew-formReview-title">
					<?php echo esc_html_x($name, 'Title in checkout steps form review.', 'bew-extras') ?>
				</div>
				<div class="bew-formReview-content" data-fill="<?php esc_attr_e($fill) ?>">
					<?php echo $content; ?>
				</div>
			</div>
			<div class="bew-formReview-action">
				<a href="#" data-target="<?php esc_attr_e($target) ?>"><?php echo esc_html_x($payment_methods_form_change_text, 'Action to take in checkout steps form review', 'bew-extras') ?></a>
			</div>
			
		</div>
		<?php
	}

	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
			
			$settings = $this->get_settings_for_display();
			
			$checkout_payment_steps   	 		= $settings['woo_checkout_payment_steps'];			
			$payment_title_show 				= $settings['woo_checkout_payment_title_show'];
			$payment_title_tag 					= Utils::validate_html_tag( $settings['woo_checkout_payment_title_tag'] );
			$payment_section_title_text 		= $settings['woo_checkout_payment_section_title_text'];			
			$payment_order_button_show 			= $settings['woo_checkout_order_button_show'];
			$payment_order_button_text 			= $settings['woo_checkout_order_button_text'];
		    $payment_description_show       	= $settings['woo_checkout_payment_description_show'];			
			$payment_description_text   		= $settings['woo_checkout_payment_description_text'];
			$payment_methods_layout         	= $settings['woo_checkout_payment_methods_layout'];
			$payment_methods_tabs_layout        = $settings['woo_checkout_payment_methods_tabs_layout'];	
			$payment_methods_radio_layout       = $settings['woo_checkout_payment_methods_radio_layout'];				
			$payment_methods_form_review    	= $settings['woo_checkout_payment_form_review'];
			$payment_methods_form_contact_text  = $settings['woo_checkout_payment_contact'];
			$payment_methods_form_ship_text  	= $settings['woo_checkout_payment_ship'];
			$payment_methods_form_bill_text  	= $settings['woo_checkout_payment_bill'];
			$payment_methods_form_method_text  	= $settings['woo_checkout_payment_method']; 
			$payment_methods_label_inside   	= $settings['woo_checkout_payment_methods_label_inside'];
			
			// Get shipping first option, ship_to_different_address checked 
			$ship_to_different_address = get_option( '_bew_ship_to_different_address' );
			
			// Check if shipping methods are added
			$shipping_needed = WC()->cart->needs_shipping();
		
			// Save page Id for elementor editor on custom templates
			if( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				global $post;
				$post_id = $post->ID;
				update_option( '_bew_checkout_id', $post_id );									
			}
			
			?>
			<div class="bew-payment-methods label-inside-<?php echo $payment_methods_label_inside;?>">
				<?php 
				if($payment_methods_form_review == "yes"){ ?>
					<div class="bew-formReview">
						
						<div class="bew-formReview-info bew-formReview-contact">
						<?php
							$this->bew_review_form_block(esc_html_x($payment_methods_form_contact_text, 'Title in checkout steps form review.', 'bew-extras'), 'email', 'step-information');
						?>
						</div>
						<?php
							if( $ship_to_different_address == 'yes' ){							
								if( $shipping_needed ){
									?>
									<div class="bew-formReview-info bew-formReview-ship">
									<?php
										$this->bew_review_form_block(esc_html_x($payment_methods_form_ship_text , 'Title in checkout steps form review.', 'bew-extras'), 'address_ship', 'step-information');
									?>
									</div>
									<?php
								}
							}
							else {  ?>
								<div class="bew-formReview-info bew-formReview-bill">
								<?php
									$this->bew_review_form_block(esc_html_x($payment_methods_form_bill_text, 'Title in checkout steps form review.', 'bew-extras'), 'address_bill', 'step-information');
								?>
								</div>
								<?php
								if( $shipping_needed ){
									?>
									<div class="bew-formReview-info bew-formReview-ship">
									<?php
										$this->bew_review_form_block(esc_html_x($payment_methods_form_ship_text, 'Title in checkout steps form review.', 'bew-extras'), 'address_ship', 'step-shipping-option');
									?>
									</div>
									<?php
								}
								
							}

							if( $shipping_needed ){
								?>
								<div class="bew-formReview-info bew-formReview-method">
								<?php
									$this->bew_review_form_block(esc_html_x($payment_methods_form_method_text, 'Title in checkout steps form review.', 'bew-extras'), 'method', 'step-shipping-option');
								?>
								</div>
								<?php
							}
						?>
					</div>
				<?php
				}
					// Added action to get it before titles
					if ( ! wp_doing_ajax() ) {
						do_action( 'woocommerce_review_order_before_payment' );
					}
					?>
					<div class="bew-components-checkout-step bew-checkout-steps-<?php echo $checkout_payment_steps; ?>">
						<?php if( 'yes' == $payment_title_show ){ ?>
							<div class="bew-checkout-step-heading">
							<<?php echo esc_attr( $payment_title_tag ); ?> class="bew-checkout-step-title  bew-payment-title"><?php echo esc_html( $payment_section_title_text ); ?></<?php echo esc_attr( $payment_title_tag ); ?>>
							</div>
						<?php } ?>
						
						<div class="bew-checkout-step-container bew-payment">
							<?php
							if('yes' == $payment_description_show ){
							?>			
								<p class="bew-components-checkout-step__description"><?php echo esc_html( $payment_description_text ); ?></p>
							<?php
							}
							?>
						
							<div class="bew-components-checkout-step__content <?php echo esc_html( $payment_methods_layout ); ?> <?php echo esc_html( $payment_methods_tabs_layout ); ?> <?php echo esc_html( $payment_methods_radio_layout ); ?>">					
								<?php
								if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {           
										$this->bew_woocommerce_checkout_payment();         
								}else{
									if( is_checkout() ){               
										$this->bew_woocommerce_checkout_payment();              
									}
								}
								?>					
							</div>
						</div>
					</div>
				
			</div>
			<?php
			
	}

	protected function _content_template() {
		
	}
	
}
