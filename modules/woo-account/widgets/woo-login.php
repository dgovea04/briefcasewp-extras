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

class Woo_Login extends Base_Widget {

	public function get_name() {
		return 'woo-account-login';
	}

	public function get_title() {
		return __( 'Account Login', 'bew-extras' );
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
            'login_content',
            [
                'label' => esc_html__( 'Heading', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'login_heading',
			[
				'label' 		=> __( 'Heading', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-login-heading-show-'
			]
		);

		$this->add_control(
			'login_heading_text',
			[
				'label' 		=> __( 'Heading Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Login', 'woocommerce' ),
				'placeholder' 	=> __( 'Type your heading here', 'bew-extras' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],
				'condition' 	=> [
					'login_heading' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'login_content_create_account',
            [
                'label' => esc_html__( 'Create Account', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'login_create_account',
			[
				'label' 		=> __( 'Create Account', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);

		$this->add_control(
            'login_create_account_position',
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
					'login_create_account' => 'yes'
				],					
            ]
        );
		
		$this->add_control(
			'login_create_account_text',
			[
				'label' => esc_html__( 'Before Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Don't have an account?", 'bew-extras' ),
				'condition' 	=> [
					'login_create_account' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'login_create_account_link_text',
			[
				'label' => esc_html__( 'Link Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Create an account", 'bew-extras' ),
				'condition' 	=> [
					'login_create_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'login_create_account_custom_link',
			[
				'label' 		=> __( 'Custom Link', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> [
					'login_create_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'login_create_account_link',
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
					'login_create_account' => 'yes',
					'login_create_account_custom_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'login_content_label',
            [
                'label' => esc_html__( 'Label', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'login_label',
			[
				'label' 		=> __( 'Label', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-login-label-show-'
			]
		);

		$this->add_control(
			'login_label_required',
			[
				'label' 		=> __( 'Label Required', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-login-label-required-show-',
				'condition' 	=> [
					'login_label' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'login_label_floating',
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
			'login_label_user_text',
			[
				'label' 		=> __( 'Username Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'default' 		=> __( 'Username or email address', 'woocommerce' ),
				'condition' 	=> [
					'login_label' => 'yes'
				],
			]
		);

		$this->add_control(
			'login_label_password_text',
			[
				'label' 		=> __( 'Password Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'default' 		=> __( 'Password', 'woocommerce' ),
				'condition' 	=> [
					'login_label' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'login_content_input',
            [
                'label' => esc_html__( 'Input', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'login_input_user_placeholder_text',
			[
				'label' 		=> __( 'Username Placeholder Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Username or email address', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->add_control(
			'login_input_password_placeholder_text',
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
			'login_show_password_input',
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
			'login_rememberme_heading',
			[
				'label' => __( 'Remember Me', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		
		$this->add_control(
			'login_rememberme',
			[
				'label' 		=> __( 'Remember Me', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-login-rememberme-show-'
			]
		);

		$this->add_control(
			'login_rememberme_text',
			[
				'label' 		=> __( 'Remember Me Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Remember me', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'condition' 	=> [
					'login_rememberme' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'login_content_button',
            [
                'label' => esc_html__( 'Button', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'login_button_text',
			[
				'label' 		=> __( 'Button Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Log in', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'login_content_lost_password',
            [
                'label' => esc_html__( 'Lost Password', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'login_lost_password',
			[
				'label' 		=> __( 'Lost Password', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-login-lost-password-show-'
			]
		);

		$this->add_control(
			'login_lost_password_link_page',
			[
				'label' 		=> __( 'Link to Lost Password URL', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);

		$this->add_control(
			'login_lost_password_text',
			[
				'label' 		=> __( 'Lost Password Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Lost your password?', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],
				'condition' 	=> [
					'login_lost_password' => 'yes'
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
				'selector'  => '{{WRAPPER}} .bew-account-form-login h2',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login h2' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-login h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login h2' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Form style
		$this->start_controls_section(
			'form_login_style',
			[
				'label' => esc_html__( 'Form', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'form_login_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login',
			]
		);
		$this->add_control(
			'form_login_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'form_login_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'form_login_border',
				'selector' => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_control(
			'form_login_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'form_login_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login',
			]
		);
		$this->add_responsive_control(
			'form_login_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'form_login_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row label',
			]
		);
		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row label' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'label_required_color',
			[
				'label' => esc_html__( 'Required Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row label .required' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}.label-inside-yes .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row input + label' => 'transform: translateY({{SIZE}}{{UNIT}});'
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
					'{{WRAPPER}}.label-inside-yes .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row input:focus + label,
					 {{WRAPPER}}.label-inside-yes .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row.has-placeholder input:not(:placeholder-shown) + label,
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
					'{{WRAPPER}}.label-inside-yes .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row input:focus + label,
					 {{WRAPPER}}.label-inside-yes .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row.has-placeholder input:not(:placeholder-shown) + label,
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row label' => 'text-align: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text',
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
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text' => 'color: {{VALUE}}',
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:-webkit-autofill, {{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:-webkit-autofill:hover, {{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:-webkit-autofill:focus' => '-webkit-text-fill-color: {{VALUE}} !important;',
				],
			]
		);
		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:-webkit-autofill, {{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:-webkit-autofill:hover, {{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:-webkit-autofill:focus' => '-webkit-box-shadow: 0 0 0px 1000px {{VALUE}} inset !important;',
					
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'selector' => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:focus' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_focus_border',
				'selector' => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:focus',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:focus' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:focus' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text:focus',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-row:not(:nth-of-type(2))' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login input.input-text' => 'text-align: {{VALUE}}',
				],
			]
		);		
		
		$this->end_controls_section();

		//Checkbox		
		$this->start_controls_section(
			'checkbox_toggle',
			[
				'label' => __( 'Checkbox', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'checkbox_toggle_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme span',
			]
		);

		$this->add_control(
			'checkbox_toggle_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme span' => 'color: {{VALUE}} !important',
				],
			]
		);
		
		$this->add_control(
			'checkbox_size',
			[
				'label' 		=> __( 'Checkbox Size', 'bew-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' 	=> 10,
						'max' 	=> 100,
						'step' 	=> 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme input[type=checkbox]:checked:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'checkbox_color',
			[
				'label'     => __( 'Checkbox Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme input[type=checkbox]:checked:before' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'checkbox_background_color',
			[
				'label'     => __( 'Checkbox Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme input[type=checkbox]:checked' => 'background-color: {{VALUE}} !important',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'checkbox_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme input[type=checkbox]',
			]
		);

        $this->add_control(
			'checkbox_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme input[type=checkbox]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'checkbox_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_control(
			'checkbox_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->add_responsive_control(
			'checkbox_align',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login .woocommerce-form-login__rememberme' => 'text-align: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-login button',
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
					'{{WRAPPER}} .bew-account-form-login button' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login button' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-login button',
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
					'{{WRAPPER}} .bew-account-form-login button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login button:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-login button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .bew-account-form-login button:hover',
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
					'{{WRAPPER}} .bew-account-form-login button' => 'transition: all {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .bew-account-form-login button',	
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-login form.woocommerce-form-login button' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
		
		// LostPassword
		$this->start_controls_section(
			'lost_password_style',
			[
				'label' => esc_html__( 'Lost Password', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'lost_password_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-login .lost_password a',
			]
		);
		$this->start_controls_tabs( 'lost_password_style_tabs' );
		
		$this->start_controls_tab( 'lost_password_style_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		$this->add_control(
			'lost_password_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .lost_password a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'lost_password_style_hover',
			[
				'label' => esc_html__( 'Hover', 'bew-extras' ),
			]
		);
		$this->add_control(
			'lost_password_color_hover',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .lost_password a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'lost_password_transition',
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
					'{{WRAPPER}} .bew-account-form-login .lost_password a' => 'transition: all {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'lost_password_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .lost_password' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'lost_password_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .lost_password' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'lost_password_text_align',
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
					'{{WRAPPER}} .bew-account-form-login .lost_password' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// CreateAccount
		$this->start_controls_section(
			'create_account_style',
			[
				'label' => esc_html__( 'Create Account', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'create_account_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text',
			]
		);
		$this->add_control(
			'create_account_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'create_account_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'create_account_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'create_account_align',
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
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text' => 'text-align: {{VALUE}}',
				],
			]
		);	
		$this->add_control(
			'create_account_link',
			[
				'label' => __( 'Account Link', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
			]
		);		
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'create_account_link_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn',
			]
		);
		$this->start_controls_tabs( 'create_account_style_tabs' );
		
		$this->start_controls_tab( 'create_account_style_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		$this->add_control(
			'create_account_color_normal',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_tab();
		$this->start_controls_tab( 'create_account_style_hover',
			[
				'label' => esc_html__( 'Hover', 'bew-extras' ),
			]
		);
		$this->add_control(
			'create_account_color_hover',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'create_account_transition',
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
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn' => 'transition: all {{SIZE}}s',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
		$this->add_responsive_control(
			'create_account_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'create_account_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'create_account_text_align',
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
					'{{WRAPPER}} .bew-account-form-login .bew-form_create-account-text .register_btn' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$login_heading_text     				 =  $settings['login_heading_text'];
		$login_label_user_text   				 =  $settings['login_label_user_text'];
		$login_label_password_text   			 =  $settings['login_label_password_text'];
		$login_input_user_placeholder_text       =  $settings['login_input_user_placeholder_text'];
		$login_input_password_placeholder_text   =  $settings['login_input_password_placeholder_text'];
		$login_rememberme_text   				 =  $settings['login_rememberme_text'];
		$login_button_text   					 =  $settings['login_button_text'];
		$login_lost_password_text   			 =  $settings['login_lost_password_text'];
		$login_lost_password_link_page   		 =  $settings['login_lost_password_link_page'];
		$login_show_password_input   			 =  $settings['login_show_password_input']; 
				
		$login_create_account   			     =  $settings['login_create_account'];
		$login_create_account_position   		 =  $settings['login_create_account_position'];
		$login_create_account_text   			 =  $settings['login_create_account_text'];
		$login_create_account_link_text   		 =  $settings['login_create_account_link_text']; 		
		$login_create_account_link   			 =  $settings['login_create_account_link']['url'] ?? null; 
		$register                                =  "";
		
		
		if(!empty($login_input_user_placeholder_text) ){
			$has_placeholder_user = "has-placeholder";
		}
		if(!empty($login_input_password_placeholder_text) ){
			$has_placeholder_password = "has-placeholder";
		}
		if( $settings['login_create_account_custom_link'] ==  '' ){
			$register  = "register_btn";
		}
		
		if( !is_account_page() ){
			do_action( 'woocommerce_before_customer_login_form' );
		}
			?>
			<div class="bew-account-form-login active">

				<h2><?php esc_html_e( $login_heading_text , 'woocommerce' ); ?></h2>
				
				<?php if( ($login_create_account == 'yes') && ($login_create_account_position == 'before') ){ ?>
					<p class="bew-form_create-account-text"><?php esc_html_e( $login_create_account_text , 'bew-extras' ); ?><a class="bew_btn_text <?php echo $register; ?>" href="<?php echo $login_create_account_link;?>"><?php esc_html_e( $login_create_account_link_text , 'bew-extras' ); ?></a></p>
				<?php } ?>
				
				<form class="woocommerce-form woocommerce-form-login login" method="post">
			
					<?php do_action( 'woocommerce_login_form_start' ); ?>
			
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide <?php echo $has_placeholder_user; ?>">
						<input type="text" class="bew-woocommerce-Input woocommerce-Input--text input-text" name="username" id="username" placeholder="<?php  echo $login_input_user_placeholder_text; ?>" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
						<label for="username"><?php esc_html_e( $login_label_user_text, 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					</p>
					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide <?php echo $has_placeholder_password; ?> show-eye-icon-<?php echo $login_show_password_input; ?>">
						<input class="bew-woocommerce-Input woocommerce-Input--text input-text" type="password" name="password" placeholder="<?php  echo $login_input_password_placeholder_text; ?>" id="password" autocomplete="current-password" />
						<label for="password"><?php esc_html_e( $login_label_password_text, 'woocommerce' ); ?>&nbsp;<span class="required">*</span></label>
					</p>
			
					<?php do_action( 'woocommerce_login_form' ); ?>
					<p class="form-row remember-forgot">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
							<input class="woocommerce-form__input woocommerce-form__input-checkbox" name="rememberme" type="checkbox" id="rememberme" value="forever" /> <span><?php esc_html_e( $login_rememberme_text, 'woocommerce' ); ?></span>
						</label>
						<span class="woocommerce-LostPassword lost_password <?php echo ($login_lost_password_link_page == 'yes') ? '' : 'lost_password_btn'; ?>">
							<a href="<?php echo ($login_lost_password_link_page == 'yes') ? esc_url( wp_lostpassword_url() ) : ''; ?>"><?php esc_html_e( $login_lost_password_text , 'woocommerce' ); ?></a>
						</span>
					</p>
					<p class="form-row">
						<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
						<button type="submit" class="woocommerce-button button woocommerce-form-login__submit" name="login" value="<?php esc_attr_e( $login_button_text, 'woocommerce' ); ?>"><?php esc_html_e( $login_button_text, 'woocommerce' ); ?></button>
					</p>					
			
					<?php do_action( 'woocommerce_login_form_end' ); ?>
			
				</form>
				
				<?php if( ($login_create_account == 'yes') && ($login_create_account_position == 'after') ){ ?>
					<p class="bew-form_create-account-text"><?php esc_html_e( $login_create_account_text , 'bew-extras' ); ?><a class="bew_btn_text <?php echo $register; ?>" href="<?php echo $login_create_account_link;?>"><?php esc_html_e( $login_create_account_link_text , 'bew-extras' ); ?></a></p>
				<?php } ?>
			</div>
			<?php

	}
	
	protected function _content_template() {
		
	}

}
