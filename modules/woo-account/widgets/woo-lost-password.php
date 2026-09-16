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

class Woo_Lost_Password extends Base_Widget {

	public function get_name() {
		return 'woo-account-lost-password';
	}

	public function get_title() {
		return __( 'Account Lost Password', 'bew-extras' );
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
            'lost_password_content',
            [
                'label' => esc_html__( 'Heading', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'lost_password_heading',
			[
				'label' 		=> __( 'Heading', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-lost-password-heading-show-'
			]
		);

		$this->add_control(
			'lost_password_heading_text',
			[
				'label' 		=> __( 'Heading Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Lost Password', 'bew-extras' ),
				'placeholder' 	=> __( 'Type your heading here', 'bew-extras' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],
				'condition' 	=> [
					'lost_password_heading' => 'yes'
				],
			]
		);

		$this->end_controls_section();
				
        $this->start_controls_section(
            'lost_password_content',
            [
                'label' => esc_html__( 'Description', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'lost_password_description',
			[
				'label' 		=> __( 'Description', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-lost-password-description-show-'
			]
		);

		$this->add_control(
			'lost_password_description_text',
			[
				'label' 		=> __( 'Description Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXTAREA,
				'default' 		=> __( 'Lost your password? Please enter your username or email address. You will receive a link to create a new password via email.' , 'woocommerce' ),
				'placeholder' 	=> __( 'Type your description here', 'bew-extras' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],
				'condition' 	=> [
					'lost_password_description' => 'yes'
				],
			]
		);

		$this->end_controls_section();
		
        $this->start_controls_section(
            'lost_password_content_label',
            [
                'label' => esc_html__( 'Label', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'lost_password_label',
			[
				'label' 		=> __( 'Label', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-lost-password-label-show-'
			]
		);

		$this->add_control(
			'lost_password_label_required',
			[
				'label' 		=> __( 'Label Required', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-lost-password-label-required-show-',
				'condition' 	=> [
					'login_label' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'lost_password_label_floating',
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
			'lost_password_label_user_text',
			[
				'label' 		=> __( 'Username Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
				'default' 		=> __( 'Username or email', 'woocommerce' ),
				'condition' 	=> [
					'lost_password_label' => 'yes'
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'lost_password_content_input',
            [
                'label' => esc_html__( 'Input', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'lost_password_input_user_placeholder_text',
			[
				'label' 		=> __( 'Username Placeholder Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Username or email', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'lost_password_content_button',
            [
                'label' => esc_html__( 'Button', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'lost_password_button_text',
			[
				'label' 		=> __( 'Button Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Reset password', 'woocommerce' ),
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				],				
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'lost_password_content_create_account',
            [
                'label' => esc_html__( 'Create Account', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'lost_password_create_account',
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
			'lost_password_create_account_text',
			[
				'label' => esc_html__( 'Before Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Don't have an account?", 'bew-extras' ),
				'condition' 	=> [
					'lost_password_create_account' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'lost_password_create_account_link_text',
			[
				'label' => esc_html__( 'Link Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Create an account", 'bew-extras' ),
				'condition' 	=> [
					'lost_password_create_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'lost_password_create_account_custom_link',
			[
				'label' 		=> __( 'Custom Link', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> [
					'lost_password_create_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'lost_password_create_account_link',
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
					'lost_password_create_account' => 'yes',
					'lost_password_create_account_custom_link' => 'yes',
				],
			]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'lost_password_content_login_account',
            [
                'label' => esc_html__( 'Login Account', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'lost_password_login_account',
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
			'lost_password_login_account_text',
			[
				'label' => esc_html__( 'Before Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Already have an account?", 'bew-extras' ),
				'condition' 	=> [
					'lost_password_login_account' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'lost_password_login_account_link_text',
			[
				'label' => esc_html__( 'Link Text', 'elementor' ),
				'type' => Controls_Manager::TEXT,
				'dynamic' => [
					'active' => true,
				],
				'default' => esc_html__( "Login", 'bew-extras' ),
				'condition' 	=> [
					'lost_password_login_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'lost_password_login_account_custom_link',
			[
				'label' 		=> __( 'Custom Link', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'condition' 	=> [
					'lost_password_login_account' => 'yes'
				],
			]
		);
		$this->add_control(
			'lost_password_login_account_link',
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
					'lost_password_login_account' => 'yes',
					'lost_password_login_account_custom_link' => 'yes',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password h2',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password h2' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password h2' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password h2' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password h2' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Form style
		$this->start_controls_section(
			'form_lost_style',
			[
				'label' => esc_html__( 'Form', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'form_lost_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword',
			]
		);
		$this->add_control(
			'form_lost_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'form_lost_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'form_lost_border',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_control(
			'form_lost_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'form_lost_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword',
			]
		);
		$this->add_responsive_control(
			'form_lost_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'form_lost_margin',
			[
				'label' => esc_html__( 'Margin', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row label',
			]
		);
		$this->add_control(
			'label_color',
			[
				'label' => esc_html__( 'Label Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row label' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'label_required_color',
			[
				'label' => esc_html__( 'Required Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row label .required' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}}.label-inside-yes .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row input + label' => 'transform: translateY({{SIZE}}{{UNIT}});'
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
					'{{WRAPPER}}.label-inside-yes .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row input:focus + label,
					 {{WRAPPER}}.label-inside-yes .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row.has-placeholder input:not(:placeholder-shown) + label,
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
					'{{WRAPPER}}.label-inside-yes .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row input:focus + label,
					 {{WRAPPER}}.label-inside-yes .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row.has-placeholder input:not(:placeholder-shown) + label,
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword .woocommerce-form-row label' => 'text-align: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_border',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'input_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'input_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text:focus' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'input_focus_border',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text:focus',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'input_focus_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text:focus' => 'border-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'input_focus_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text:focus' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'input_focus_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text:focus',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword input.input-text' => 'text-align: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password button',
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
					'{{WRAPPER}} .bew-account-form-lost-password button' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password button' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password button',
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
					'{{WRAPPER}} .bew-account-form-lost-password button:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password button:hover' => 'background-color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password button:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'button_box_shadow_hover',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password button:hover',
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
					'{{WRAPPER}} .bew-account-form-lost-password button' => 'transition: all {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();

		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'button_border',
				'selector' => '{{WRAPPER}} .bew-account-form-lost-password button',	
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password button' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password form.woocommerce-ResetPassword button' => 'text-align: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text',
			]
		);
		$this->add_control(
			'create_account_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'create_account_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'flex-start' 		=> [
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text' => 'justify-content: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text .register_btn' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text .register_btn:hover' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text .register_btn' => 'transition: all {{SIZE}}s',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text .register_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_create-account-text .register_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text',
			]
		);
		$this->add_control(
			'register_login_account_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'register_login_account_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
				'options' 	   => [
					'flex-start' 		=> [
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text' => 'justify-content: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text .login_btn',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text .login_btn' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text .login_btn:hover' => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text .login_btn' => 'transition: all {{SIZE}}s',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text .login_btn' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .bew-account-form-lost-password .bew-form_login-account-text .login_btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

	}

	protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$lost_password_heading_text     				= $settings['lost_password_heading_text']; 
		$lost_password_description_text     			= $settings['lost_password_description_text']; 
		$lost_password_label_user_text   				= $settings['lost_password_label_user_text'];
		$lost_password_input_user_placeholder_text      = $settings['lost_password_input_user_placeholder_text'];	
		$lost_password_button_text   					= $settings['lost_password_button_text'];
		
		$lost_password_create_account					= $settings['lost_password_create_account'];
		$lost_password_create_account_text				= $settings['lost_password_create_account_text'];
		$lost_password_create_account_link_text			= $settings['lost_password_create_account_link_text'];
		$lost_password_create_account_link 				= $settings['lost_password_create_account_link']['url'] ?? null;

		
		$lost_password_login_account 					= $settings['lost_password_login_account'];
		$lost_password_login_account_text				= $settings['lost_password_login_account_text'];
		$lost_password_login_account_link_text			= $settings['lost_password_login_account_link_text'];
		$lost_password_login_account_link   			=  $settings['lost_password_login_account_link']['url'] ?? null;
		
		$register  = "";
		$login  = "";
		$has_placeholder_user = "";
		
		if( $settings['lost_password_create_account_custom_link'] ==  '' ){
			$register  = "register_btn";
		}
		if( $settings['lost_password_login_account_custom_link'] ==  '' ){
			$login  = "login_btn";
		}
		if(!empty($lost_password_input_user_placeholder_text) ){
			$has_placeholder_user = "has-placeholder";
		}
		
			?>
			<div class="bew-account-form-lost-password">

				<h2><?php esc_html_e( $lost_password_heading_text , 'woocommerce' ); ?></h2>
				
				<?php do_action( 'woocommerce_before_lost_password_form' ); ?>
				
				<form method="post" class="woocommerce-ResetPassword lost_reset_password">

					<p><?php echo apply_filters( 'woocommerce_lost_password_message', esc_html__( $lost_password_description_text , 'woocommerce' ) ); ?></p><?php // @codingStandardsIgnoreLine ?>

					<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
						<label for="user_login"><?php esc_html_e( $lost_password_label_user_text, 'woocommerce' ); ?></label>
						<input class="woocommerce-Input woocommerce-Input--text input-text" type="text" name="user_login" id="user_login" placeholder="<?php  echo $lost_password_input_user_placeholder_text; ?>" autocomplete="username" />
					</p>

					<div class="clear"></div>

					<?php do_action( 'woocommerce_lostpassword_form' ); ?>

					<p class="woocommerce-form-row form-row">
						<input type="hidden" name="wc_reset_password" value="true" />
						<button type="submit" class="woocommerce-Button button" value="<?php esc_attr_e( $lost_password_button_text, 'woocommerce' ); ?>"><?php esc_html_e( $lost_password_button_text, 'woocommerce' ); ?></button>
					</p>

					<?php wp_nonce_field( 'lost_password', 'woocommerce-lost-password-nonce' ); ?>

				</form>
				
				<?php do_action( 'woocommerce_after_lost_password_form' ); ?>
	
				<div class="bew-account-links">
				
				<?php if( $lost_password_create_account == 'yes' ){ ?>					
					<div class="bew-form_create-account-text"><?php esc_html_e( $lost_password_create_account_text , 'bew-extras' ); ?><a class="bew_btn_text <?php echo $register; ?>" href="<?php echo $lost_password_create_account_link;?>"><?php esc_html_e( $lost_password_create_account_link_text , 'bew-extras' ); ?></a></div>
				<?php } ?>
				
				<?php if( $lost_password_login_account == 'yes' ){ ?>
					<div class="bew-form_login-account-text"><?php esc_html_e( $lost_password_login_account_text , 'bew-extras' ); ?><a class="bew_btn_text <?php echo $login; ?>" href="<?php echo $lost_password_login_account_link;?>"><?php esc_html_e( $lost_password_login_account_link_text , 'bew-extras' ); ?></a></div>
				<?php } ?>
				
				</div>	

			</div>
			<?php

	}
	
	protected function _content_template() {
		
	}

}
