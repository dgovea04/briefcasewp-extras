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
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Shipping_Options extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-shipping-options';
	}

	public function get_title() {
		return __( 'Checkout Shipping Options', 'bew-extras' );
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
			'woo_checkout_shipping_options_title',
			[
				'label' => __( 'Section Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_steps',
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
			'woo_checkout_shipping_options_vertical_line',
			[
				'label'         => __( 'Vertical Line', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				   'label_on'      => __( 'Show', 'bew-extras' ),
				   'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'condition' => [
				   'woo_checkout_shipping_options_steps' => 'active'
				],
				'prefix_class' => 'steps-vertical-line-',
			]
		);

		$this->add_control(
            'woo_checkout_shipping_options_title_show',
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
		    'woo_checkout_shipping_options_title_text',
		    [
		        'label' 		=> __( 'Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Shipping Options', 'bew-extras' ) ,
                'condition' => [
                    'woo_checkout_shipping_options_title_show' => 'yes'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
			'woo_checkout_shipping_options_title_tag',
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
                    'woo_checkout_shipping_options_title_show' => 'yes'
                ],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_description_show',
			[
				'label'         => __( 'Show/Hide Description', 'briefcase-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_options_description_text',
			[
				'label' 		=> __( 'Text', 'briefcase-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( "Select shipping options below.", 'briefcase-extras' ) ,
				   'condition' 	=> [
					   'woo_checkout_shipping_options_description_show' => 'yes'
				   ],
				'dynamic' 		=> [
					'active' 		=> true,
				]
			]
		);
		
		
		$this->add_control(
            'woo_checkout_shipping_options_title_alignment',
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
                    'woo_checkout_shipping_options_title_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-shipping-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_shipping_options_form_review_section',
			[
				'label' => __( 'Multistep Review', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_options_form_review',
			[
				'label'         => __( 'Multistep Review', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_contact_show',
			[
				'label'         => __( 'Contact', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-shipping-options-contact-',
				'condition' => [				   
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_contact', [
				'label' => __( 'Contact text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Contact' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_options_contact_show' => 'yes',
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_ship_show',
			[
				'label'         => __( 'Ship To', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-shipping-options-ship-',
				'condition' => [				   
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_ship', [
				'label' => __( 'Ship To text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ship To' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_options_ship_show' => 'yes',
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_bill_show',
			[
				'label'         => __( 'Bill To', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-shipping-options-bill-',
				'condition' => [				   
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_bill', [
				'label' => __( 'Bill To text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Bill To' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_options_bill_show' => 'yes',
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);		

		$this->add_control(
			'woo_checkout_shipping_options_change', [
				'label' => __( 'Change text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Change' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_options_form_review' => 'yes'
				],
			]
		);			
				
		$this->end_controls_section();
		
		//Section general style
		$this->start_controls_section(
			'woo_checkout_shipping_options_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_general_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-shipping-options .bew-components-checkout-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_general_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-shipping-options .bew-components-checkout-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section title style
		$this->start_controls_section(
			'woo_checkout_shipping_options_title_style',
			[
				'label' => __( 'Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'woo_checkout_shipping_options_title_show' => 'yes'
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_options_title_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-shipping-title',
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_title_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_title_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section shipping options style
		$this->start_controls_section(
			'woo_checkout_shipping_options_style',
			[
				'label' => __( 'Shipping Options', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_options_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'woo_checkout_shipping_options_bg_color',
			[
				'label'     => __( 'Section Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_options_heading_table',
			[
				'label' => __( 'Table', 'bew-extras' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_shipping_options_table_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content .bew-checkout-review-shipping-table ul#shipping_method',
			]
		);
		
		
		$this->add_control(
			'woo_checkout_shipping_options_table_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content .bew-checkout-review-shipping-table ul#shipping_method' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_shipping_options_table_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content .bew-checkout-review-shipping-table ul#shipping_method' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_table_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content .bew-checkout-review-shipping-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_heading_content',
			[
				'label' => __( 'Content', 'bew-extras' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_shipping_options_content_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content ul#shipping_method li',
			]
		);
		
		
		$this->add_control(
			'woo_checkout_shipping_options_content_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content ul#shipping_method li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_shipping_options_content_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content ul#shipping_method li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_content_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-checkout-step__content ul#shipping_method li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		
		$this->end_controls_section();
		
		//section label style
		$this->start_controls_section(
			'woo_checkout_shipping_options_label_style',
			[
				'label' => __( 'Label', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_options_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label',
			]
		);


        $this->add_control(
			'woo_checkout_shipping_options_text_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();

		//section radio style
		$this->start_controls_section(
			'woo_checkout_shipping_options_radio_style',
			[
				'label' => __( 'Radio Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs(
			'woo_checkout_shipping_options_radio_separator',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'woo_checkout_shipping_options_radio_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_radio_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_bg_shipping_options_radio_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_shipping_options_radio_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_shipping_options_radio_checked',
			[
				'label'     => __( 'Checked', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_options_text_radio_checked',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'woo_checkout_shipping_options_radio_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_options_radio_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before, 
					 {{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_shipping_options_radio_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-shipping-options .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//Table totals footer controll		
		$this->start_controls_section(
			'woo_checkout_shipping_options_order_tfoot_style',
			[
				'label' => __( 'Order Totals', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_options_order_tfoot_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__label,
								{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__value',
			]
		);


        $this->add_control(
			'woo_checkout_shipping_options_order_tfoot_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__label,
					 {{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__value .amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_options_order_tfoot_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__label,
					{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__value' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_responsive_control(
        	'woo_checkout_shipping_options_order_tfoot_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
        $this->add_responsive_control(
        	'woo_checkout_shipping_options_order_tfoot_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_shipping_options_order_tfoot_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item',
		    ]
		);

		$this->add_responsive_control(
            'woo_checkout_order_tfoot_alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__label,
					 {{WRAPPER}} .bew-shipping-options .bew-components-totals-footer-item .bew-components-totals-item__value' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
	}
	
	function bew_review_form_block($name, $fill, $target, $content = '') {
		$settings = $this->get_settings_for_display();
		
		$shipping_form_change_text   = $settings['woo_checkout_shipping_options_change'];
		
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
				<a href="#" data-target="<?php esc_attr_e($target) ?>"><?php echo esc_html_x($shipping_form_change_text, 'Action to take in checkout steps form review', 'bew-extras') ?></a>
			</div>
		</div>
		<?php
	}
		
	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url( 'order-received' ) ) ) ) return;
		
		$settings = $this->get_settings_for_display();
				
		$checkout_shipping_steps   	 = $settings['woo_checkout_shipping_options_steps'];
		$shipping_description_show   = $settings['woo_checkout_shipping_options_description_show'];
		$shipping_description_text   = $settings['woo_checkout_shipping_options_description_text'];
		$shipping_title_show 	   	 = $settings['woo_checkout_shipping_options_title_show'];
		$shipping_title_tag		   	 = Utils::validate_html_tag( $settings['woo_checkout_shipping_options_title_tag'] );
		$shipping_section_title_text = $settings['woo_checkout_shipping_options_title_text']; 
		$shipping_form_review        = $settings['woo_checkout_shipping_options_form_review']; 
		$shipping_form_contact_text  = $settings['woo_checkout_shipping_options_contact'];
		$shipping_form_ship_text  	 = $settings['woo_checkout_shipping_options_ship'];
		$shipping_form_bill_text  	 = $settings['woo_checkout_shipping_options_bill'];		
				
		//Get shipping first option, ship_to_different_address checked 
		$ship_to_different_address = get_option( '_bew_ship_to_different_address' );
		
		// Add class if the product doesnt need shipping
		if (  false === WC()->cart->needs_shipping_address() ) {
			$this->add_render_attribute( '_wrapper', [
				'class' => 'dont-need-shipping-yes'
			] );			
		}
				
		?>
		<div class="bew-shipping-options">
			<?php 
			if($shipping_form_review == "yes"){ ?>
				<div class="bew-formReview">
					<div class="bew-formReview-info bew-formReview-contact">
					<?php
						$this->bew_review_form_block(esc_html_x($shipping_form_contact_text, 'Title in checkout steps form review.', 'bew-extras'), 'email', 'step-information');
					?>
					</div>
					<?php
						if( $ship_to_different_address == 'yes' ){ ?>
							<div class="bew-formReview-info bew-formReview-ship">
							<?php
								$this->bew_review_form_block(esc_html_x($shipping_form_ship_text , 'Title in checkout steps form review.', 'bew-extras'), 'address_ship', 'step-information');
							?>
							</div>
							<?php
						}
						else { ?>
							<div class="bew-formReview-info bew-formReview-bill">
							<?php
								$this->bew_review_form_block(esc_html_x($shipping_form_bill_text , 'Title in checkout steps form review.', 'bew-extras'), 'address_bill', 'step-information');
							?>
							</div>
							<?php
						}
					?>
				</div>
			<?php
			}
					
			if ( ( true === WC()->cart->needs_shipping_address() ) || Elementor\Plugin::instance()->editor->is_edit_mode() ) { ?>
			   
					<div class="bew-components-checkout-step bew-checkout-steps-<?php echo $checkout_shipping_steps; ?>">
						<?php if( 'yes' == $shipping_title_show){ ?>
							<div class="bew-checkout-step-heading">
							<<?php echo esc_attr( $shipping_title_tag ); ?> class="bew-checkout-step-title bew-shipping-title"><?php echo esc_html( $shipping_section_title_text ); ?></<?php echo esc_attr( $shipping_title_tag ); ?>>
							</div>
						<?php } ?>						
						
						<div class="bew-checkout-step-container bew-shipping-options">							
							
								<?php
								if('yes' == $shipping_description_show ){
								?>			
									<p class="bew-components-checkout-step__description"><?php echo esc_html( $shipping_description_text ); ?></p>
								<?php
								}
								?>
								<div class="bew-components-checkout-step__content">
									<?php
									if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) { 
											// Calc totals.
											WC()->cart->calculate_totals();
											?>
											<div class="bew-checkout-review-shipping-table">
												<table class="shop_table">											
													<?php echo wc_cart_totals_shipping_html(); ?>												
												</table>
											</div>
											<?php
									}else{
										if( is_checkout() ){               
											?>
											
											<div class="bew-checkout-review-shipping-table"></div>
											<?php           
										}
									}
									?>
								</div>
		  				</div>
					</div>
			<?php 	
			} ?>
				
		</div>		
		<?php
	}

	protected function _content_template() {
		
	}
	
}

