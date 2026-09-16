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

class Woo_Checkout_Form_Login extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-form-login';
	}

	public function get_title() {
		return __( 'Checkout Form Login', 'bew-extras' );
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
			'woo_checkout_form_login',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_form_login_layout',
			[
				'label' 	=> __( 'Layout', 'elementor' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'default',
				'options' 	=> [
					'default'  => __( 'Default', 'bew-extras' ),
					'collapse'  => __( 'Collapse', 'bew-extras' ),
					'input'  => __( 'Input', 'bew-extras' ),
					'link'  => __( 'Link', 'bew-extras' ),
				],
			]
		);
		
		$this->add_control(
            'woo_checkout_form_login_icon_show',
            [
                'label'         => __( 'Show/Hide Icon', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'checkout-login-icon-show-',
				'condition' => [
                    'woo_checkout_form_login_layout' => 'collapse'
                ],
            ]
        );
		
		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_form_login_input',
			[
				'label' => __( 'Input', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'woo_checkout_form_login_input_label_show',
            [
                'label'         => __( 'Show/Hide Label', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'checkout-login-label-show-',
            ]
        );

		$this->add_control(
            'woo_checkout_form_login_input_label_inside',
            [
                'label'         => __( 'Inside Label', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'checkout-login-inside-label-',
				'condition' => [
                    'woo_checkout_form_login_input_label_show' => 'yes'
                ],
            ]
        );			

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_form_login_submit_button',
			[
				'label' => __( 'Submit Button', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'woo_checkout_form_login_button_icon_show',
            [
                'label'         => __( 'Show/Hide Icon', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'checkout-login-submit-button-icon-show-',
            ]
        );

		$this->end_controls_section();

        // Heading
        $this->start_controls_section(
            'form_area_style',
            array(
                'label' => __( 'General Style', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_area_typography',
                    'label'     => __( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-info',
                )
            );
			
			$this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_area_typography_link',
                    'label'     => __( 'Link Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-info a',
                )
            );

            $this->add_control(
                'form_area_text_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_color',
                [
                    'label' => __( 'Link Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info a.showlogin' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_color_bg',
                [
                    'label' => __( 'Link Background Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info a.showlogin' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_hover_color',
                [
                    'label' => __( 'Link Hover Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info a.showlogin:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_link_hover_color_bg',
                [
                    'label' => __( 'Link Hover Background Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info a.showlogin:hover' => 'background-color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'form_area_icon_color',
                [
                    'label' => __( 'Left Icon Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info::before' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_area_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .woocommerce-info',
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
                        '{{WRAPPER}} .woocommerce-info' => 'border-top-color: {{VALUE}}',
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
                        '{{WRAPPER}} .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Background::get_type(),
                [
                    'name' => 'form_area_background',
                    'label' => __( 'Background', 'bew-extras' ),
                    'types' => [ 'classic', 'gradient' ],
                    'selector' => '{{WRAPPER}} .woocommerce-info',
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
                    'default'   => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'text-align: {{VALUE}}',
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
                    'selector'  => '{{WRAPPER}} .login.woocommerce-form-login p',
                )
            );

            $this->add_control(
                'form_box_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .login.woocommerce-form-login p' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_box_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .login.woocommerce-form-login',
                ]
            );

            $this->add_responsive_control(
                'form_box_border_radius',
                [
                    'label' => __( 'Border Radius', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .login.woocommerce-form-login' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .login.woocommerce-form-login' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .login.woocommerce-form-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Input box
        $this->start_controls_section(
            'form_input_box_style',
            array(
                'label' => esc_html__( 'Input Box', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );
            
            $this->add_control(
                'form_input_box_text_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'color: {{VALUE}}',
                    ]
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_input_box_typography',
                    'label'     => esc_html__( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .login.woocommerce-form-login input.input-text',
                )
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'form_input_box_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .login.woocommerce-form-login input.input-text',
                ]
            );

            $this->add_responsive_control(
                'form_input_box_border_radius',
                [
                    'label' => esc_html__( 'Border Radius', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%'],
                    'selectors' => [
                        '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
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
                        '{{WRAPPER}} .login.woocommerce-form-login input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                    'separator' => 'after',
                ]
            );

        $this->end_controls_section();

        // Remember and Forgot
        $this->start_controls_section(
            'form_remember_forgot_style',
            array(
                'label' => esc_html__( 'Remember && Forgot Password', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'form_remember_forgot_text_typography',
                    'label'     => esc_html__( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .bew-checkout-form-login .woocommerce-form-login .form-row.remember-forgot span',
                )
            );			
			            
            $this->add_control(
                'form_remember_forgot_text_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .bew-checkout-form-login .woocommerce-form-login .form-row .woocommerce-form__label-for-checkbox span' => 'color: {{VALUE}}',
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
                        '{{WRAPPER}} .bew-checkout-form-login .woocommerce-form-login .form-row.remember-forgot' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                        '{{WRAPPER}} .bew-checkout-form-login .woocommerce-form-login .form-row.remember-forgot' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_background_color',
                        [
                            'label' => __( 'Background Color', 'bew-extras' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        array(
                            'name'      => 'form_submit_button_typography',
                            'label'     => esc_html__( 'Typography', 'bew-extras' ),
                            'selector'  => '{{WRAPPER}} .login.woocommerce-form-login button.button',
                        )
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_border',
                            'label' => __( 'Border', 'bew-extras' ),
                            'selector' => '{{WRAPPER}} .login.woocommerce-form-login button.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'form_submit_button_border_radius',
                        [
                            'label' => esc_html__( 'Border Radius', 'bew-extras' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%'],
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                                '{{WRAPPER}} .login.woocommerce-form-login button.button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'form_submit_button_hover_background_color',
                        [
                            'label' => __( 'Background Color', 'bew-extras' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .login.woocommerce-form-login button.button:hover' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'form_submit_button_hover_border',
                            'label' => __( 'Border', 'bew-extras' ),
                            'selector' => '{{WRAPPER}} .login.woocommerce-form-login button.button:hover',
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

        $this->end_controls_section();

    }
	
	
	function editor_default_woocommerce_login_form() { 
		?>
        <div class="woocommerce-form-login-toggle">
            <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Returning customer?', 'woocommerce' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Click here to login', 'woocommerce' ) . '</a>', 'notice' ); ?>
        </div>
        <?php
        woocommerce_login_form(
            array(
                'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
                'redirect' => wc_get_checkout_url(),
                'hidden'   => true,
            )
        );
	
	}

	function editor_woocommerce_login_form() { 
		?>
        <div class="woocommerce-form-login-toggle">
            <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Have an account?', 'bew-extras' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Sign in here', 'bew-extras' ) . '</a>', 'notice' ); ?>
        </div>
        <?php
        woocommerce_login_form(
            array(
                'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
                'redirect' => wc_get_checkout_url(),
                'hidden'   => true,
            )
        );
	
	}

	function bew_woocommerce_login_form() { 
		?>
        <div class="woocommerce-form-login-toggle">
            <?php wc_print_notice( apply_filters( 'woocommerce_checkout_login_message', esc_html__( 'Have an account?', 'bew-extras' ) ) . ' <a href="#" class="showlogin">' . esc_html__( 'Sign in here', 'bew-extras' ) . '</a>', 'notice' ); ?>
        </div>
        <?php
        woocommerce_login_form(
            array(
                'message'  => esc_html__( 'If you have shopped with us before, please enter your details below. If you are a new customer, please proceed to the Billing section.', 'woocommerce' ),
                'redirect' => wc_get_checkout_url(),
                'hidden'   => true,
            )
        );
	
	}

    protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
        $settings = $this->get_settings_for_display();
		
		$woo_checkout_form_login_layout = $settings['woo_checkout_form_login_layout'];
		?>
		<div class="bew-checkout-form-login initial-hide <?php echo "layout-".$woo_checkout_form_login_layout; ?>">
		<?php
			if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				if( $woo_checkout_form_login_layout == 'default' ){
					$this->editor_default_woocommerce_login_form();
				} else {
					$this->editor_woocommerce_login_form();
				}
			}else{
				if( is_checkout() ){
					if( $woo_checkout_form_login_layout == 'default' ){
						woocommerce_checkout_login_form();
					} else {
						if ( is_user_logged_in() || 'no' === get_option( 'woocommerce_enable_checkout_login_reminder' ) ) {
							return;
						}				
						$this->bew_woocommerce_login_form();
					}
				}
			}
		?>	
		</div>
        <?php
    }

	protected function _content_template() {
		
	}
	
}

