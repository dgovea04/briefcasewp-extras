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

class Woo_Order_Details extends Base_Widget {

	public function get_name() {
		return 'woo-order-details';
	}

	public function get_title() {
		return __( 'Order Details', 'bew-extras' );
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
			'order_details_section',
			[
				'label' => esc_html__( 'Order Details', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
            'order_details_heading',
            [
                'label'         => __( 'Heading', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-details-heading-',
            ]
        );

		$this->add_control(
            'order_details_order_titles',
            [
                'label'         => __( 'Titles', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-details-titles-',
            ]
        );

		$this->add_control(
            'order_details_order_items',
            [
                'label'         => __( 'Items', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-details-items-',
            ]
        );

		$this->add_control(
            'order_details_order_footer',
            [
                'label'         => __( 'Footer', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-details-footer-',
            ]
        );

		$this->add_control(
            'order_details_order_custom',
            [
                'label'         => __( 'Custom Fields', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-details-custom-',
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
				'selector'  => '{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'heading_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'background: {{VALUE}}',
				],
				
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'heading_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title',
			]
		);
		$this->add_responsive_control(
			'heading_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'heading_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .woocommerce-order-details .woocommerce-order-details__title' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// order general
		$this->start_controls_section(
			'order_general_style',
			[
				'label' => esc_html__( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'order_general_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table, {{WRAPPER}} .bew-thankyou-order-details table.shop_table' => 'background: {{VALUE}}',
				],				
			]
		);		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_general_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table, {{WRAPPER}} .bew-thankyou-order-details table.shop_table',
			]
		);
		$this->add_control(
			'order_general_border_radius',
			[
				'label'         => __( 'Border Radius', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
            'order_general_width_label',
            [
                'label' => __( 'Label Width', 'briefcase-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-table--order-details thead tr th:first-child, {{WRAPPER}} .woocommerce-table--order-details tbody tr td:first-child,
					 {{WRAPPER}} .woocommerce-table--order-details tfoot tr th:first-child' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'order_general_width_values',
            [
                'label' => __( 'Value Width', 'briefcase-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} .woocommerce-table--order-details thead tr th:last-child, {{WRAPPER}} .woocommerce-table--order-details tbody tr td:last-child,
					 {{WRAPPER}} .woocommerce-table--order-details tfoot tr th:last-child' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
		$this->add_responsive_control(
			'order_general_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'order_general_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		
		$this->end_controls_section();	
		
		// order_item
		$this->start_controls_section(
			'order_item_style',
			[
				'label' => esc_html__( 'Order Item', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'order_item_titles',
			[
				'label' => __( 'Titles', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'porder_item_titles_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-table--order-details thead th',
			]
		);
		$this->add_control(
			'order_item_titles_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details thead th' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'order_item_titles_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table thead, {{WRAPPER}} .bew-thankyou-order-details table.shop_table thead' => 'background: {{VALUE}}',
				],				
			]
		);		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_item_titles_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table thead tr th, {{WRAPPER}} .bew-thankyou-order-details table.shop_table thead tr th',
			]
		);
		$this->add_responsive_control(
			'order_item_titles_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],				
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table thead tr th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'order_item_titles_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table thead tr th' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'order_item_content',
			[
				'label' => __( 'Content', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',				
			]
		);
		
		$this->start_controls_tabs( 'order_item_style_tabs' );
		//product_name
		$this->start_controls_tab( 'product_name_style',
			[
				'label' => esc_html__( 'Product Name', 'bew-extras' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'product_name_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-table--order-details .order_item .product-name',
			]
		);
		$this->add_control(
			'product_name_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details .order_item .product-name a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_name_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details .order_item .product-name' => 'background: {{VALUE}}',
				],				
			]
		);
		$this->add_control(
			'product_quantity_color',
			[
				'label' => esc_html__( 'Quantity Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details .order_item .product-name .product-quantity' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		//product_total
		$this->start_controls_tab( 'product_total_style',
			[
				'label' => esc_html__( 'Product Total', 'bew-extras' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'product_total_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-table--order-details .order_item .product-total .amount',
			]
		);
		$this->add_control(
			'product_total_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details .order_item .product-total .amount' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'product_total_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details .order_item .product-total' => 'background: {{VALUE}}',
				],				
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_item_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table td, {{WRAPPER}} .bew-thankyou-order-details table.shop_table td',
			]
		);
		$this->add_responsive_control(
			'order_item_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'order_item_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table td' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);			
		$this->end_controls_section();
		
		// Total
		$this->start_controls_section(
			'total_style_section',
			[
				'label' => esc_html__( 'Total', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->start_controls_tabs( 'total_style_tabs' );
		//total label
		$this->start_controls_tab( 'total_label_style',
			[
				'label' => esc_html__( 'Label', 'bew-extras' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'total_label_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-table--order-details tfoot th',
			]
		);
		$this->add_control(
			'total_label_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details tfoot th' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'total_label_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details tfoot th' => 'background: {{VALUE}}',
				],				
			]
		);
		$this->end_controls_tab();
		//total
		$this->start_controls_tab( 'total_style',
			[
				'label' => esc_html__( 'Total', 'bew-extras' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'total_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-table--order-details tfoot td, {{WRAPPER}} .woocommerce-table--order-details tfoot td .woocommerce-Price-amount',
			]
		);
		$this->add_control(
			'total_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details tfoot td' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'total_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-details tfoot td' => 'background: {{VALUE}}',
				],				
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_total_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot td, {{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot td,
									.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot th, {{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot th',
			]
		);
		$this->add_responsive_control(
			'order_total_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot tr th, {{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot tr td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'order_total_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.shop_table tfoot tr' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->end_controls_section();
		
		//Order Custom fields
		$this->start_controls_section(
			'order_custom_fields_section',
			[
				'label' => esc_html__( 'Custom Fields', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs( 'order_custom_fields_style_tabs' );
		
		//Custom fields Labels
		$this->start_controls_tab( 'order_custom_fields_label_style',
			[
				'label' => esc_html__( 'Label', 'bew-extras' ),
			]
		);		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'order_custom_fields_label_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields th, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:first-child',
			]
		);
		$this->add_control(
			'order_custom_fields_label_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields th, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:first-child' => 'color: {{VALUE}}',
				],
			]
		);		
		$this->add_control(
			'order_custom_fields_label_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields th, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:first-child' => 'background: {{VALUE}}',
				],				
			]
		);
		$this->end_controls_tab();
		
		//Custom fields Value
		$this->start_controls_tab( 'custom_fields_value_style',
			[
				'label' => esc_html__( 'Value', 'bew-extras' ),
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'order_custom_fields_value_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}}  .bew-thankyou-order-details table.woocommerce-table--custom-fields td, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:last-child',
			]
		);
		$this->add_control(
			'order_custom_fields_value_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .bew-thankyou-order-details table.woocommerce-table--custom-fields td, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:last-child' => 'color: {{VALUE}}',
				],
			]
		);		
		$this->add_control(
			'order_custom_fields_value_bg_color',
			[
				'label' 		=> __( 'Background Color', 'elementor' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' => [
					'.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields td, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:last-child' => 'background: {{VALUE}}',
				],				
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_custom_fields_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '.woocommerce {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields',
			]
		);
		$this->add_control(
			'order_custom_fields_border_radius',
			[
				'label'         => __( 'Border Radius', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
            'order_custom_fields_width_label',
            [
                'label' => __( 'Label Width', 'briefcase-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} .bew-thankyou-order-details .woocommerce-table--custom-fields tr th, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:first-child' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
        $this->add_responsive_control(
            'order_custom_fields_width_values',
            [
                'label' => __( 'Value Width', 'briefcase-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 10,
                        'max' => 2000,
                        'step' => 1,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],                    
                'selectors' => [
                    '{{WRAPPER}} .bew-thankyou-order-details .woocommerce-table--custom-fields tr td, {{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields.thwdtp-custom-fields tr td:last-child' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
		$this->add_responsive_control(
			'order_custom_fields_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'order_custom_fields_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order-details table.woocommerce-table--custom-fields' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);	
		
		$this->end_controls_section();
	}

		
	function bew_get_last_order_id(){
		$last_order_id = wc_get_orders(array('limit' => 1, 'return' => 'ids')); // Get last Order ID (array)  

		return (string) reset($last_order_id);
	}	
	
	protected function render() {
		
			global $wp;
			
			if( isset($wp->query_vars['order-received']) ){
				$bew_order_received = $wp->query_vars['order-received'];
			}else{
				$bew_order_received = $this->bew_get_last_order_id();
			}
			
			if( !$bew_order_received ){
				return;
			}
			
			$order = wc_get_order( $bew_order_received );
			$order_id = $order->get_id();
			
			
			if ( ! $order = wc_get_order( $order_id ) ) {
				return;
			}
			
			$order_items           = $order->get_items( apply_filters( 'woocommerce_purchase_order_item_types', 'line_item' ) );
			$show_purchase_note    = $order->has_status( apply_filters( 'woocommerce_purchase_note_order_statuses', array( 'completed', 'processing' ) ) );
			$show_customer_details = is_user_logged_in() && $order->get_user_id() === get_current_user_id();
			$downloads             = $order->get_downloadable_items();
			$show_downloads        = $order->has_downloadable_item() && $order->is_download_permitted();
			
			if ( $show_downloads ) {
				wc_get_template( 'order/order-downloads.php', array( 'downloads' => $downloads, 'show_title' => true ) );
			}
			?>
			<div class="bew-thankyou-order-details">
			<section class="woocommerce-order-details">
				<?php do_action( 'woocommerce_order_details_before_order_table', $order ); ?>
			
				<h2 class="woocommerce-order-details__title"><?php esc_html_e( 'Order details', 'woocommerce' ); ?></h2>
			
				<table class="woocommerce-table woocommerce-table--order-details shop_table order_details">
			
					<thead>
						<tr>
							<th class="woocommerce-table__product-name product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
							<th class="woocommerce-table__product-table product-total"><?php esc_html_e( 'Total', 'woocommerce' ); ?></th>
						</tr>
					</thead>
			
					<tbody>
						<?php
						do_action( 'woocommerce_order_details_before_order_table_items', $order );
			
						foreach ( $order_items as $item_id => $item ) {
							$product = $item->get_product();
			
							wc_get_template(
								'order/order-details-item.php',
								array(
									'order'              => $order,
									'item_id'            => $item_id,
									'item'               => $item,
									'show_purchase_note' => $show_purchase_note,
									'purchase_note'      => $product ? $product->get_purchase_note() : '',
									'product'            => $product,
								)
							);
						}
			
						do_action( 'woocommerce_order_details_after_order_table_items', $order );
						?>
					</tbody>
			
					<tfoot>
						<?php
						foreach ( $order->get_order_item_totals() as $key => $total ) {
							?>
								<tr>
									<th scope="row"><?php echo esc_html( $total['label'] ); ?></th>
									<td><?php echo ( 'payment_method' === $key ) ? esc_html( $total['value'] ) : wp_kses_post( $total['value'] ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></td>
								</tr>
								<?php
						}
						?>
						<?php if ( $order->get_customer_note() ) : ?>
							<tr>
								<th><?php esc_html_e( 'Note:', 'woocommerce' ); ?></th>
								<td><?php echo wp_kses_post( nl2br( wptexturize( $order->get_customer_note() ) ) ); ?></td>
							</tr>
						<?php endif; ?>
					</tfoot>
				</table>
			
				<?php do_action( 'woocommerce_order_details_after_order_table', $order ); ?>
			</section>
			</div>
			
			<?php
	}

	protected function _content_template() {
		
	}
	
}

