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

class Woo_Checkout_Coupon_Form extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-coupon-form';
	}

	public function get_title() {
		return __( 'Checkout Coupon Form', 'bew-extras' );
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
			'form_area_content',
			[
				'label' => __( 'Order Coupon', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_area_coupon_layout', [
				'label' => __( 'Coupon Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'collapse',
				'options' => [
					'collapse' => 'Collapse',
					'input' => 'Input',
				],
				'prefix_class' => 'order-coupon-layout-',
			]
		);
		
		$this->add_control(
            'form_area_coupon_icon_show',
            [
                'label'         => __( 'Icon', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-coupon-icon-',
            ]
		);
		
		$this->add_control(
		    'form_area_coupon_prefix_text',
		    [
		        'label' 		=> __( 'Prefix Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Have a coupon?', 'woocommerce' ),
                'condition' => [
                    'form_area_coupon_layout' => 'collapse'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
		    'form_area_coupon_link_text',
		    [
		        'label' 		=> __( 'Link Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Click here to enter your code', 'woocommerce'),
                'condition' => [
                    'form_area_coupon_layout' => 'collapse'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
            'form_area_coupon_description_text_show',
            [
                'label'         => __( 'Description', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-coupon-description-',
            ]
        );
		
		$this->add_control(
		    'form_area_coupon_description_text',
		    [
		        'label' 		=> __( 'Description Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'If you have a coupon code, please apply it below.', 'woocommerce' ),
				'condition' => [
                    'form_area_coupon_description_text_show' => 'yes'
                ],				
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
		    'form_area_coupon_placeholder_text',
		    [
		        'label' 		=> __( 'Placeholder Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Coupon code', 'woocommerce' ),			
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
		    'form_area_coupon_button_text',
		    [
		        'label' 		=> __( 'Button Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Apply coupon', 'woocommerce' ),			
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'form_area_multistep',
			[
				'label' => __( 'Multistep', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'form_area_multistep_step', [
				'label' => __( 'Step Location', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'default',
				'options' => [
					'default'			=> __( 'Default', 'bew-extras' ),
					'information'		=> __( 'Information', 'bew-extras' ),
					'shipping-option'	=> __( 'Shipping', 'bew-extras' ),
					'payment'			=> __( 'Payment', 'bew-extras' ),
				],
				'prefix_class' => 'step step-',
			]
		);

		$this->end_controls_section();

        // Heading
        $this->start_controls_section(
            'form_area_style',
            array(
                'label' => __( 'Style', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_area_typography',
                    'label'     => __( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
                )
            );
			
			$this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_link_typography',
                    'label'     => __( 'Link Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info a',
                )
            );

            $this->add_control(
                'form_area_text_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_color',
                [
                    'label' => __( 'Link Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_hover_color',
                [
                    'label' => __( 'Link Hover Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info a:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_icon_color',
                [
                    'label' => __( 'Left Icon Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info::before' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_area_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
                ]
            );

            $this->add_control(
                'form_area_top_border_color',
                [
                    'label' => __( 'Top Border Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'condition' => [
                        'form_area_border_border' => '',
                    ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-top-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_border_radius',
                [
                    'label' => __( 'Border Radius', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'form_area_background',
                    'label' => __( 'Background', 'bew-extras' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
                ]
            );

            $this->add_responsive_control(
                'form_area_margin',
                [
                    'label' => __( 'Margin', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_padding',
                [
                    'label' => __( 'Padding', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_area_content_align',
                [
                    'label'        => __( 'Alignment', 'bew-extras' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'bew-extras' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'bew-extras' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'bew-extras' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'bew-extras' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'   => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'text-align: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info::before' => 'position: static;margin-right:10px;',
                    ],
                ]
            );

        $this->end_controls_section();

        // Form
        $this->start_controls_section(
            'form_form_style',
            array(
                'label' => __( 'Form', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_box_typography',
                    'label'     => __( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon p',
                )
            );

            $this->add_control(
                'form_box_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon p' => 'color: {{VALUE}}',
                    ],
                ]
            );
			
			$this->add_control(
                'form_box_bg_color',
                [
                    'label' => __( 'Background', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-checkout-coupon .checkout_coupon.woocommerce-form-coupon' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_box_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon',
                ]
            );

            $this->add_responsive_control(
                'form_box_border_radius',
                [
                    'label' => __( 'Border Radius', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_box_margin',
                [
                    'label' => __( 'Margin', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'form_box_padding',
                [
                    'label' => __( 'Padding', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Input box
        $this->start_controls_section(
            'form_input_box_style',
            array(
                'label' => esc_html__( 'Input Box', 'woolentor-pros' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_control(
                'form_input_box_text_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon input.input-text' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_input_box_typography',
                    'label'     => esc_html__( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon input.input-text',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_input_box_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon input.input-text',
                ]
            );

            $this->add_responsive_control(
                'form_input_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );
            
            $this->add_responsive_control(
                'form_input_box_padding',
                [
                    'label' => esc_html__( 'Padding', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ]
                ]
            );
            
            $this->add_responsive_control(
                'form_input_box_margin',
                [
                    'label' => esc_html__( 'Margin', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                ]
            );

        $this->end_controls_section();

        // Submit button box
        $this->start_controls_section(
            'form_submit_button_style',
            array(
                'label' => esc_html__( 'Submit Button', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->start_controls_tabs('submit_button_style_tabs');
                
                $this->start_controls_tab(
                    'submit_button_normal_tab',
                    [
                        'label' => __( 'Normal', 'bew-extras' ),
                    ]
                );

                    $this->add_control(
                        'form_submit_button_text_color',
                        [
                            'label' => __( 'Color', 'bew-extras' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_background_color',
                        [
                            'label' => __( 'Background Color', 'bew-extras' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'form_submit_button_typography',
                            'label'     => esc_html__( 'Typography', 'bew-extras' ),
                            'selector'  => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_border',
                            'label' => __( 'Border', 'bew-extras' ),
                            'selector' => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_submit_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'bew-extras' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'form_submit_button_padding',
                        [
                            'label' => esc_html__( 'Padding', 'bew-extras' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );
                    
                    $this->add_responsive_control(
                        'form_submit_button_margin',
                        [
                            'label' => esc_html__( 'Margin', 'bew-extras' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                            'separator' => 'after'
                        ]
                    );

                $this->end_controls_tab();

                $this->start_controls_tab(
                    'submit_button_hover_tab',
                    [
                        'label' => __( 'Hover', 'bew-extras' ),
                    ]
                );
                    $this->add_control(
                        'form_submit_button_hover_color',
                        [
                            'label' => __( 'Color', 'bew-extras' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_hover_background_color',
                        [
                            'label' => __( 'Background Color', 'bew-extras' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_hover_border',
                            'label' => __( 'Border', 'bew-extras' ),
                            'selector' => '{{WRAPPER}} .checkout_coupon.woocommerce-form-coupon button.button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }

    protected function render() {		
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
        $settings = $this->get_settings_for_display();
		
		$coupon_multistep          = $settings['form_area_multistep_step'];
		$coupon_prefix_text        = $settings['form_area_coupon_prefix_text'];
		$coupon_link_text          = $settings['form_area_coupon_link_text'];
		$coupon_description_text   = $settings['form_area_coupon_description_text'];
		$coupon_placeholder_text   = $settings['form_area_coupon_placeholder_text'];
		$coupon_button_text        = $settings['form_area_coupon_button_text'];
		
		?>
		<div class="bew-checkout-coupon woocommerce">
		<?php
			
        if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
            wc_get_template( 'checkout/form-coupon.php', array( 'checkout' => WC()->checkout(),
																'coupon_prefix_text' => $coupon_prefix_text,
																'coupon_link_text' => $coupon_link_text,
																'coupon_description_text' => $coupon_description_text,
																'coupon_placeholder_text' => $coupon_placeholder_text,
																'coupon_button_text' => $coupon_button_text
														 ) ); 
        }else{
            if( is_checkout() ){
				if($coupon_multistep == "default"){
					wc_get_template( 'checkout/form-coupon.php', array( 'checkout' => WC()->checkout(),
																		'coupon_prefix_text' => $coupon_prefix_text,
																		'coupon_link_text' => $coupon_link_text,
																		'coupon_description_text' => $coupon_description_text,
																		'coupon_placeholder_text' => $coupon_placeholder_text,
																		'coupon_button_text' => $coupon_button_text
																 ) ); 
				} else {
					wc_get_template( 'checkout/bew-form-coupon.php', array( 'checkout' => WC()->checkout(),
																			'coupon_prefix_text' => $coupon_prefix_text,
																			'coupon_link_text' => $coupon_link_text,
																			'coupon_description_text' => $coupon_description_text,
																			'coupon_placeholder_text' => $coupon_placeholder_text,
																			'coupon_button_text' => $coupon_button_text
																	 ) ); 
				}
            }
        } ?>
		</div>
		<?php
    }    

	protected function _content_template() {
		
	}
	
}

