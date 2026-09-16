<?php
namespace BriefcasewpExtras\Modules\WooAccount\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;   
use Elementor\Group_Control_Box_Shadow;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

class Woo_Register extends Base_Widget {

	public function get_name() {
		return 'woo-account-register';
	}

	public function get_title() {
		return __( 'Account Register', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-account' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general', 'bew-checkout' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
        $this->start_controls_section(
            'register_content',
            [
                'label' => esc_html__( 'Heading', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'register_heading',
			[
				'label' 		=> __( 'Heading', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-register-heading-show-'
			]
		);

		$this->add_control(
			'register_heading_text',
			[
				'label' 		=> __( 'Heading Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Register', 'woocommerce' ),
				'placeholder' 	=> __( 'Type your heading here', 'bew-extras' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],
				'condition' 	=> [
					'register_heading' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'register_content_login_account',
            [
                'label' => esc_html__( 'Login Account', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'register_login_account',
			[
				'label' 		=> __( 'Login Account', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$this->add_control(
            'register_login_account_position',
            [
                'label' => esc_html__( 'Position', 'bew-extras' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'after',
				'label_block' => true,
                'options' => [
                    'after' => esc_html__( 'After Form', 'bew-extras' ),
					'before'    => esc_html__( 'Before Form', 'bew-extras' ),                    
                ],
				'condition' 	=> [
					'register_login_account' => 'yes'
				],					
            ]
        );
		
		$this->add_control(
			'register_login_account_text',
			[
				'label' => esc_html__( 'Before Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Already have an account?", 'bew-extras' ),
				'condition' 	=> [
					'register_login_account' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'register_login_account_link_text',
			[
				'label' => esc_html__( 'Link Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Login", 'bew-extras' ),
				'condition' 	=> [
					'register_login_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'register_login_account_custom_link',
			[
				'label' 		=> __( 'Custom Link', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> [
					'register_login_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'register_login_account_link',
			[
				'label' => esc_html__( 'Link', 'elementor' ),
				'type' => Controls_Manager::URL,
				'dynamic' => [
					'active' => true,
				],
				'placeholder' => esc_html__( 'https://your-link.com', 'elementor' ),
				'default' => [
					'url' => '#',
				],
				'condition' 	=> [
					'register_login_account' => 'yes',
					'register_login_account_custom_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();
		
        $this->start_controls_section(
            'register_content_label',
            [
                'label' => esc_html__( 'Label', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'register_label',
			[
				'label' 		=> __( 'Label', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-register-label-show-'
			]
		);

		$this->add_control(
			'register_label_required',
			[
				'label' 		=> __( 'Label Required', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-register-label-required-show-',
				'condition' 	=> [
					'register_label' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'register_label_floating',
			[
				'label' 		=> __( 'Floating Label', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'prefix_class'  => 'label-inside-'
			]
		);
		
		$this->add_control(
			'register_label_user_text',
			[
				'label' 		=> __( 'Username Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Username', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'condition' 	=> [
					'register_label' => 'yes'
				],
			]
		);

		$this->add_control(
			'register_label_email_text',
			[
				'label' 		=> __( 'Email Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Email address', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'condition' 	=> [
					'register_label' => 'yes'
				],
			]
		);

		$this->add_control(
			'register_label_password_text',
			[
				'label' 		=> __( 'Password Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Password', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'condition' 	=> [
					'register_label' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'register_content_input',
            [
                'label' => esc_html__( 'Input', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'register_input_user_placeholder_text',
			[
				'label' 		=> __( 'Username Placeholder Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Username', 'bew-extras' ),	
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->add_control(
			'register_input_email_placeholder_text',
			[
				'label' 		=> __( 'Email Placeholder Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Email address', 'bew-extras' ),		
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);
		
		$this->add_control(
			'register_input_password_placeholder_text',
			[
				'label' 		=> __( 'Password Placeholder Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Password', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->add_control(
			'register_input_password_message_text',
			[
				'label' 		=> __( 'Password Message Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'A password will be sent to your email address.', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->add_control(
			'register_show_password_input',
			[
				'label' 		=> __( 'Show Password Input Icon', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$this->add_control(
			'register_policy',
			[
				'label' 		=> __( 'Privacy Policy', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-register-policy-show-'
			]
		);

		$this->add_control(
			'register_policy_extra_text',
			[
				'label' 		=> __( 'Extra Policy Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXTAREA,
				'placeholder' 		=> __( 'Enter your extra privacy policy text here', 'bew-extras' ),					
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'register_content_button',
            [
                'label' => esc_html__( 'Button', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'register_button_text',
			[
				'label' 		=> __( 'Button Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Register', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Headings', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'heading_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register h2',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register h2' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'heading_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'heading_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'heading_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'elementor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'elementor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'elementor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register h2' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Form style
		$this->start_controls_section(
			'form_register_style',
			[
				'label' => esc_html__( 'Form', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'form_register_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register',
			]
		);
		$this->add_control(
			'form_register_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'form_register_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'form_register_border',
				'selector' => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_control(
			'form_register_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'form_register_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register',
			]
		);
		$this->add_responsive_control(
			'form_register_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'form_register_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
		// label
		$this->start_controls_section(
			'label_style',
			[
				'label' => esc_html__( 'Label', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'label_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .form-row label',
			]
		);
		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .form-row label' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'label_required_color',
			[
				'label' => esc_html__( 'Required Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .form-row label .required' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'label_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'label_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'label_transform_fx_translate_toggle',
			[
				'label' => __( 'Transform', 'bew-extras' ),
				'type' => Controls_Manager::POPOVER_TOGGLE,
				'return_value' => 'yes',				
			]
		);
		$this->add_control(
			'label_floating_translate_y_before',
			[
				'label' => __( 'TranslateY Before', 'bew-extras' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em'],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => -50,
						'max' => 50,
						'step' => .1,
					],
				],
				'condition' => [
					'label_transform_fx_translate_toggle' => 'yes',					
				],
				'selectors' => [
					'{{WRAPPER}}.label-inside-yes .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row input + label' => 'transform: translateY({{SIZE}}{{UNIT}});'
				],
			]
		);
		$this->add_control(
			'label_floating_translate_y_after',
			[
				'label' => __( 'TranslateY After', 'bew-extras' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em'],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 50,
						'step' => 1,
					],
					'em' => [
						'min' => -50,
						'max' => 50,
						'step' => .1,
					],
				],
				'condition' => [
					'label_transform_fx_translate_toggle' => 'yes',					
				],
				'selectors' => [
					'{{WRAPPER}}.label-inside-yes .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row input:focus + label,
					 {{WRAPPER}}.label-inside-yes .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row.has-placeholder input:not(:placeholder-shown) + label,
					 {{WRAPPER}}.label-inside-yes .woocommerce-form-row.is-active label' => '--bew-tfx-translate-y: {{SIZE}}{{UNIT}};'
				],
			]
		);
		$this->add_control(
			'label_floating_scale',
			[
				'label' => __( 'Scale', 'bew-extras' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 5,
						'step' => .1,
					],					
				],
				'condition' => [
					'label_transform_fx_translate_toggle' => 'yes',					
				],
				'selectors' => [
					'{{WRAPPER}}.label-inside-yes .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row input:focus + label,
					 {{WRAPPER}}.label-inside-yes .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row.has-placeholder input:not(:placeholder-shown) + label,
					 {{WRAPPER}}.label-inside-yes .woocommerce-form-row.is-active label' => '--bew-tfx-scale: {{SIZE}};'
				],
			]
		);
		$this->add_responsive_control(
			'label_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row label' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Input Fields
		$this->start_controls_section(
			'input_style',
			[
				'label' => esc_html__( 'Input', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'input_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text',
			]
		);		
		$this->start_controls_tabs( 'tabs_input_style' );
		$this->start_controls_tab(
			'tab_input_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		$this->add_control(
			'input_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'selector' => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab(
			'tab_input_focus',
			[
				'label' => esc_html__( 'Focus', 'bew-extras' ),
			]
		);
		$this->add_control(
			'input_focus_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text:focus' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_focus_border',
				'selector' => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text:focus',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text:focus' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text:focus' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'input_focus_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text:focus',
			]
		);
		//
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			'input_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'input_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'input_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register .woocommerce-form-row:not(:nth-of-type(2))' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'input_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'elementor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'elementor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'elementor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register input.input-text' => 'text-align: {{VALUE}}',
				],
			]
		);		
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'policy_text_style',
			[
				'label' => esc_html__( 'Policy Text', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'policy_text_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text',
			]
		);
		$this->add_control(
			'policy_text_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'policy_link_text_color',
			[
				'label' => esc_html__( 'Link Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'policy_link_text_color_hover',
			[
				'label' => esc_html__( 'Link Hover Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'policy_text_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_responsive_control(
			'policy_text_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'policy_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .woocommerce-privacy-policy-text' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// button style
		$this->start_controls_section(
			'section_button_style',
			[
				'label' => esc_html__( 'Button', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'button_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register button',
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .bew-account-form-register button',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->start_controls_tabs( 'button_style_tabs' );
		
		$this->start_controls_tab( 'button_style_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-register button',
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'button_style_hover',
			[
				'label' => esc_html__( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'condition' => [
					'button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .bew-account-form-register button:hover',
			]
		);
		$this->add_control(
			'button_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.2,
				],
				'range' => [
					'px' => [
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button' => 'transition: all {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .bew-account-form-register button',	
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'button_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'button_size',
			[
				'label' 		=> __( 'Button Size', 'bew-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' 	=> 0,
						'max' 	=> 1000,
						'step' 	=> 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register button' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'button_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'elementor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'elementor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'elementor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => '',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register form.woocommerce-form-register  button' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// LoginAccount
		$this->start_controls_section(
			'register_login_account_style',
			[
				'label' => esc_html__( 'Login Account', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'register_login_account_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text',
			]
		);
		$this->add_control(
			'register_login_account_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'register_login_account_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'register_login_account_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'register_login_account_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'left' 		=> [
						'title' 	=> __( 'Left', 'elementor' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'elementor' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'right' 	=> [
						'title' 	=> __( 'Right', 'elementor' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'justify' => [
						'title' => __( 'Justified', 'elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text' => 'text-align: {{VALUE}}',
				],
			]
		);	
		$this->add_control(
			'register_login_account_link_heading',
			[
				'label' => __( 'Login Link', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
			]
		);		
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'register_login_account_link_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn',
			]
		);
		$this->start_controls_tabs( 'register_login_account_style_tabs' );
		
		$this->start_controls_tab( 'register_login_account_style_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		$this->add_control(
			'register_login_account_color_normal',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'register_login_account_style_hover',
			[
				'label' => esc_html__( 'Hover', 'bew-extras' ),
			]
		);
		$this->add_control(
			'register_login_account_color_hover',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'register_login_account_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::SLIDER,
				'default' => [
					'size' => 0.2,
				],
				'range' => [
					'px' => [
						'max' => 2,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn' => 'transition: all {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'register_login_account_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'register_login_account_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'register_login_account_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => \Elementor\Controls_Manager::CHOOSE,
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-register .bew-form_login-account-text .login_btn' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$register_heading_text     				     =  $settings['register_heading_text'];
		$register_label_user_text   				 =  $settings['register_label_user_text'];
		$register_label_email_text   				 =  $settings['register_label_email_text'];
		$register_label_password_text   			 =  $settings['register_label_password_text'];
		$register_input_user_placeholder_text        =  $settings['register_input_user_placeholder_text'];
		$register_input_email_placeholder_text       =  $settings['register_input_email_placeholder_text'];
		$register_input_password_placeholder_text    =  $settings['register_input_password_placeholder_text'];
		$register_input_password_message_text    	 =  $settings['register_input_password_message_text'];		
		$register_policy_extra_text   			 	 =  $settings['register_policy_extra_text'];
		$register_button_text   					 =  $settings['register_button_text'];
		$register_show_password_input   			 =  $settings['register_show_password_input'];
		
		$register_login_account   			         =  $settings['register_login_account'];
		$register_login_account_position   		 	 =  $settings['register_login_account_position'];
		$register_login_account_text   			 	 =  $settings['register_login_account_text'];
		$register_login_account_link_text   		 =  $settings['register_login_account_link_text']; 		
		$register_login_account_link   			 	 =  $settings['register_login_account_link']['url'] ?? null; 
		$login  									 =  "";
		
		if(!empty($register_input_user_placeholder_text) ){
			$has_placeholder_user = "has-placeholder";
		}
		if(!empty($register_input_email_placeholder_text) ){
			$has_placeholder_email = "has-placeholder";
		}
		if(!empty($register_input_password_placeholder_text) ){
			$has_placeholder_password = "has-placeholder";
		}
		if( $settings['register_login_account_custom_link'] ==  '' ){
			$login  = "login_btn";
		}
		
		if( !is_account_page() ){ ?>
			<div class="woocommerce"> <?php 
			do_action( 'woocommerce_before_customer_login_form' );
			//global $post;
			//update_option( '_bew_account_register', $post->ID);			
			
		}
		?>
		<?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
		<div class="bew-account-form-register">

			<h2><?php esc_html_e( $register_heading_text , 'woocommerce' ); ?></h2>

			<?php if( ($register_login_account == 'yes') && ($register_login_account_position == 'before') ){ ?>
				<p class="bew-form_login-account-text"><?php esc_html_e( $register_login_account_text , 'bew-extras' ); ?><a class="bew_btn_text <?php echo $login; ?>" href="<?php echo $register_login_account_link;?>"><?php esc_html_e( $register_login_account_link_text , 'bew-extras' ); ?></a></p>
			<?php } ?>

			<form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >
		
				<?php do_action( 'woocommerce_register_form_start' ); ?>
		
				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
		
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide <?php echo $has_placeholder_user; ?>">
						<input type="text" class="bew-woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" placeholder="<?php  echo $register_input_user_placeholder_text; ?>" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						<label for="reg_username"><?php esc_html_e( $register_label_user_text, 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
						
					</p>
		
				<?php endif; ?>
		
				<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide <?php echo $has_placeholder_email; ?>">
					<input type="email" class="bew-woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" placeholder="<?php  echo $register_input_email_placeholder_text; ?>" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
					<label for="reg_email"><?php esc_html_e( $register_label_email_text, 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
				
				</p>
		
				<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
		
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide <?php echo $has_placeholder_password; ?> show-eye-icon-<?php echo $register_show_password_input; ?>">
						<input type="password" class="bew-woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" placeholder="<?php  echo $register_input_password_placeholder_text; ?>" autocomplete="new-password" />
						<label for="reg_password"><?php esc_html_e( $register_label_password_text, 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>					
					</p>
		
				<?php else : ?>
		
					<p><?php esc_html_e( $register_input_password_message_text , 'woocommerce' ); ?></p>
		
				<?php endif; ?>
		
				<?php do_action( 'woocommerce_register_form' ); ?>
		
				<p class="woocommerce-FormRow form-row">
					<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
					<button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( $register_button_text, 'woocommerce' ); ?>"><?php esc_html_e( $register_button_text, 'woocommerce' ); ?></button>
				</p>
		
				<?php do_action( 'woocommerce_register_form_end' ); ?>
		
			</form>

			<?php if( ($register_login_account == 'yes') && ($register_login_account_position == 'after') ){ ?>
				<p class="bew-form_login-account-text"><?php esc_html_e( $register_login_account_text , 'bew-extras' ); ?><a class="bew_btn_text <?php echo $login; ?>" href="<?php echo $register_login_account_link;?>"><?php esc_html_e( $register_login_account_link_text , 'bew-extras' ); ?></a></p>
			<?php } ?>
				
		</div>
		<?php endif; 
		
		if( !is_account_page() ){ ?>
			</div> 	
			<?php do_action( 'woocommerce_after_customer_login_form' ); 		
		}		
	}
	
	protected function _content_template() {
		
	}

}
