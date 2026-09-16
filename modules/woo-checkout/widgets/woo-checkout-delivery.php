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
use ElementorPro\Modules\QueryControl\Module;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Delivery extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-delivery';
	}

	public function get_title() {
		return __( 'Checkout Delivery', 'bew-extras' );
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

	public static function is_elementor_pro_installed() {
		$file_path = 'elementor-pro/elementor-pro.php';		
		return is_plugin_active( $file_path  );
	}
	
protected function _register_controls() {

		$this->start_controls_section(
			'woo_checkout_delivery_title',
			[
				'label' => __( 'Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_steps',
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
			'woo_checkout_delivery_vertical_line',
			[
				'label'         => __( 'Vertical Line', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				   'label_on'      => __( 'Show', 'bew-extras' ),
				   'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'condition' => [
				   'woo_checkout_delivery_steps' => 'active'
				],
				'prefix_class' => 'steps-vertical-line-',
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_title_show',
			[
				'label'         => __( 'Show/Hide Title', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'bew-extras' ),
				'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_title_text',
			[
				'label' 		=> __( 'Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Delivery Date', 'bew-extras' ) ,
				'condition' 	=> [
					'woo_checkout_delivery_title_show' => 'yes'
				],
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				]
			]
		);

		$this->add_control(
			'woo_checkout_delivery_title_tag',
			[
				'label' 	=> __( 'HTML Tag', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'h2',
				'options' 	=> [
					'h1'  => __( 'H1', 'bew-extras' ),
					'h2'  => __( 'H2', 'bew-extras' ),
					'h3'  => __( 'H3', 'bew-extras' ),
					'h4'  => __( 'H4', 'bew-extras' ),
					'h5'  => __( 'H5', 'bew-extras' ),
					'h6'  => __( 'H6', 'bew-extras' ),
				],
				'condition' => [
					'woo_checkout_delivery_title_show' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_description_show',
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
			'woo_checkout_delivery_description_text',
			[
				'label' 		=> __( 'Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( "Choose delivery date.", 'bew-extras' ) ,
				'condition' 	=> [
					'woo_checkout_delivery_description_show' => 'yes'
				],
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				]
			]
		);

		$this->add_responsive_control(
			'woo_checkout_delivery_title_alignment',
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
					'woo_checkout_delivery_title_show' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .bew-delivery-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'woo_checkout_delivery',
			[
				'label' => __( 'Delivery', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_delivery_layout', [
				'label' => __( 'Delivery Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'boxs',
				'options' => [
					'boxs' => 'Delivery Boxes',
				],				
			]
		);
		
		$this->add_control(
		    'woo_checkout_delivery_day_off_text',
		    [
		        'label' 		=> __( 'Day Off Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Not available', 'bew-extras' ),
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
		    'woo_checkout_delivery_today_text',
		    [
		        'label' 		=> __( 'Today Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Today', 'bew-extras' ),
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
		    'woo_checkout_delivery_tomorrow_text',
		    [
		        'label' 		=> __( 'Tomorrow Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Tomorrow', 'bew-extras' ),
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
		    'woo_checkout_delivery_more_text',
		    [
		        'label' 		=> __( 'More Dates Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'More Dates', 'bew-extras' ),
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);		
		$this->end_controls_section();

		//Section general style
		$this->start_controls_section(
			'woo_checkout_delivery_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_general_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_delivery_table_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-checkout-delivery',
		    ]
		);

		$this->add_responsive_control(
			'woo_checkout_delivery_general_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_delivery_general_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section heading style
		$this->start_controls_section(
			'woo_checkout_delivery_heading_style',
			[
				'label' => __( 'Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,               
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_delivery_title_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-delivery .bew-delivery-title',
			]
		);

		$this->add_control(
			'woo_checkout_delivery_title_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .bew-delivery-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_title_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .bew-delivery-title',
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_delivery_title_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .bew-delivery-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_delivery_title_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .bew-delivery-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section button style
		$this->start_controls_section(
			'woo_checkout_delivery_content_button_style',
			[
				'label' => __( 'Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,                
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_delivery_button_day_typography',
				'label' 	=> __( 'Day Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .day',				
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_delivery_button_date_typography',
				'label' 	=> __( 'Date Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .status',				
			]
		);

        $this->add_responsive_control(
            'woo_checkout_delivery_date_height',
            [
                'label' => __( 'Height', 'bew-extras' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
				'default' => [
					'unit' => 'px',
					'size' => 68,
				],
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
                    '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );	
		
		$this->start_controls_tabs(
			'woo_checkout_delivery_button_separator',			
		);

		$this->start_controls_tab(
			'woo_checkout_delivery_button_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_delivery_button_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_delivery_button_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_button_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_delivery_button_hover',
			[
				'label'     => __( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_button_text_color_hover',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_delivery_button_bg_color_hover',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_button_border_hover',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button:hover',
			]
		);

		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'woo_checkout_delivery_button_active',
			[
				'label'     => __( 'Active', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_button_text_color_active',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .schedule-select.option-selected button' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_delivery_button_bg_color_active',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .schedule-select.option-selected button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_button_border_active',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .schedule-select.option-selected button',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_control(
			'woo_checkout_delivery_button_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_delivery_title_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_delivery_title_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section time style
		$this->start_controls_section(
			'woo_checkout_delivery_content_time_style',
			[
				'label' => __( 'Time Slot', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,                
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_delivery_time_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item',
			]
		);

		$this->start_controls_tabs(
			'woo_checkout_delivery_time_separator',			
		);

		$this->start_controls_tab(
			'woo_checkout_delivery_time_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_delivery_time_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_delivery_time_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_time_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_delivery_time_hover',
			[
				'label'     => __( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_time_text_color_hover',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_delivery_time_bg_color_hover',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item:hover' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_time_border_hover',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-itemn:hover',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_delivery_time_active',
			[
				'label'     => __( 'Active', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_delivery_time_text_color_active',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item.active' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_delivery_time_bg_color_active',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item.active' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_delivery_time_border_active',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-itemn.active',
			]
		);

		$this->end_controls_tab();		
		$this->end_controls_tabs();

		$this->add_control(
			'woo_checkout_delivery_time_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_delivery_time_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_delivery_time_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'woo_checkout_delivery_time_alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .bew-checkout-delivery .calendar-buttons .time-slots-container .list-item' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
		
	}

	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
		$settings = $this->get_settings_for_display();

		$checkout_delivery_steps   		 = $settings['woo_checkout_delivery_steps'];
		$delivery_description_show 		 = $settings['woo_checkout_delivery_description_show'];
		$delivery_description_text 		 = $settings['woo_checkout_delivery_description_text'];

		$delivery_title_show 	 	 	 = $settings['woo_checkout_delivery_title_show'];
		$delivery_title_tag  	  		 = Utils::validate_html_tag( $settings['woo_checkout_delivery_title_tag'] );
		$delivery_title_text 	  		 = $settings['woo_checkout_delivery_title_text'];	
		
		$delivery_day_off_text		   = $settings['woo_checkout_delivery_day_off_text'];
		$delivery_today_text		   = $settings['woo_checkout_delivery_today_text'];
		$delivery_tomorrow_text		   = $settings['woo_checkout_delivery_tomorrow_text'];
		$delivery_more_text		  	   = $settings['woo_checkout_delivery_more_text'];

		?>
		<div class="bew-components-checkout-step bew-checkout-steps-<?php echo $checkout_delivery_steps; ?>">			
			<div class="bew-checkout-delivery">
				<?php if( 'yes' == $delivery_title_show ): ?>
					<div class="bew-checkout-step-heading">
					<<?php echo esc_attr( $delivery_title_tag ); ?> class="bew-checkout-step-title bew-delivery-title"><?php echo esc_html( $delivery_title_text ); ?></<?php echo esc_attr( $delivery_title_tag ) ?>>
					</div>
				<?php endif; ?>
				
				<div class=" bew-checkout-step-container bew-checkout-delivery-container">
					<?php if('yes' == $delivery_description_show ){ ?>	
						<p class="bew-components-checkout-step__description"><?php echo esc_html( $delivery_description_text ); ?></p>
					<?php } ?>
					<div class="calendar-buttons">
																			
						<div day-off-txt ="<?php echo esc_html( $delivery_day_off_text  ); ?>" class="today schedule-select option-select">									
							<button type="button" data-toggle="collapse" class="shortcut-date-text">
								<p class="day"><?php echo esc_html( $delivery_today_text  ); ?></p> 
								<p class="status"><?php echo esc_html( $delivery_day_off_text  ); ?></p>            
							</button>						
							<div class="collapse time-slots-container">
								<div class="time-slots"></div>
							</div>										
						</div>
										
						<div day-off-txt ="<?php echo esc_html( $delivery_day_off_text  ); ?>" class="tomorrow schedule-select option-select">									
							<button type="button" data-toggle="collapse"  class="shortcut-date-text">
								<p class="day"><?php echo esc_html( $delivery_tomorrow_text  ); ?></p>
								<p class="status"><?php esc_html_e( 'Date' , 'bew-extras' ); ?></p>  
												
							</button>						
							<div class="time-slots-container collapse">
								<div class="time-slots"></div> 
							</div>
						</div> 
											
						<div data-more-txt ="<?php echo esc_html( $delivery_more_text  ); ?>"  class="next-day schedule-select option-select" id="calendar-input-button">
							<div class="flatpickr">									
								<button type="button" data-toggle="collapse" class="shortcut-date-text">											
									<div class="nextday-container">													
										<i class="icon ion-calendar"></i>
										<div class="schedule-content">																	
											<p class="date-name"><?php echo esc_html( $delivery_more_text  ); ?></p>
											<p class="date-time-slot"></p>
										</div>
									</div>
								</button>
								<input type="text" placeholder="Select Date.." data-input> <!-- input is mandatory -->
								<div class="time-slots-container next-day-collapse">
									<div class="time-slots"></div> 
								</div>											
							</div>
						</div>					
							
					</div>
												
				</div>

			</div>
		</div>
		<?php
	}

	protected function content_template() {}
	
}

