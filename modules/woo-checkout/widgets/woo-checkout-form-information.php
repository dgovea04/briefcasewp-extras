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
use BriefcasewpExtras\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Form_Information extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-form-information';
	}

	public function get_title() {
		return __( 'Checkout Form Contact Information', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-checkout' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general', 'bew-checkout' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	public function get_style_depends() {
		if ( Icons_Manager::is_migration_allowed() ) {
			return [ 'elementor-icons-fa-solid' ];
		}
		return [];
	}
	
	protected function _register_controls() {

	$this->start_controls_section(
		'woo_checkout_information_title',
		[
			'label' => __( 'Section Title', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);
	
	$this->add_control(
        'woo_checkout_information_steps',
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
		'woo_checkout_information_vertical_line',
		[
			'label'         => __( 'Vertical Line', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
               'label_on'      => __( 'Show', 'bew-extras' ),
               'label_off'     => __( 'Hide', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => 'yes',
			'condition' => [
                   'woo_checkout_information_steps' => 'active'
            ],
			'prefix_class' => 'steps-vertical-line-',
		]
	);
	
	$this->add_control(
        'woo_checkout_information_title_show',
        [
            'label'         => __( 'Title', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => __( 'Show', 'bew-extras' ),
            'label_off'     => __( 'Hide', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
        ]
    );
	
	$this->add_control(
	    'woo_checkout_information_title_text',
	    [
	        'label' 		=> __( 'Text', 'bew-extras' ),
	        'type' 			=> Controls_Manager::TEXT,
	        'default' 		=> __( 'Contact Information', 'woocommerce' ),
            'condition' 	=> [
                'woo_checkout_information_title_show' => 'yes'
            ],
			'label_block'   => true,
	        'dynamic' 		=> [
	            'active'    => true,
	        ]
	    ]
	);

	$this->add_control(
		'woo_checkout_information_title_tag',
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
                'woo_checkout_information_title_show' => 'yes'
            ],
		]
	);
	
	$this->add_control(
        'woo_checkout_information_description_show',
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
	    'woo_checkout_information_description_text',
	    [
	        'label' 		=> __( 'Text', 'bew-extras' ),
	        'type' 			=> Controls_Manager::TEXT,
	        'default' 		=> __( "We'll use this email to send you details and updates about your order.", 'bew-extras' ),
            'condition' 	=> [
                'woo_checkout_information_description_show' => 'yes'
            ],
			'label_block'   => true,
	        'dynamic' 		=> [
	            'active' 	=> true,
	        ]
	    ]
	);

	$this->add_control(
        'woo_checkout_information_title_alignment',
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
                'woo_checkout_information_title_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .bew-information-title' => 'text-align: {{VALUE}};',
            ],
        ]
    );

	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_content_section',
		[
			'label' => __( 'Contact Information Fields', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);
	
	$repeater = new Repeater();

	$repeater->add_control(
		'billing_input_label', [
			'label' => __( 'Input Label', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'New Section' , 'bew-extras' ),
			'label_block' => true,			
		]
	);

	$repeater->add_control(
		'billing_input_label_hide',
		[
			'label'         => __( 'Hide Label', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'Yes', 'bew-extras' ),
			'label_off'     => __( 'No', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => '',			
		]
	);	
	
	$repeater->add_control(
		'billing_input_label_layout',
		[
			'label'         => __( 'Floating Label', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'yes', 'bew-extras' ),
			'label_off'     => __( 'no', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => 'yes',
			'separator' => 'after',
		]
	);	

	$repeater->add_control(
		'billing_input_class', [
			'label' => __( 'Class Name', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'form-row-wide',
			'options' => [
				'form-row-first' => 'form-row-first',
				'form-row-last' => 'form-row-last',
				'form-row-wide' => 'form-row-wide',
			],
		]
	);
	
	$repeater->add_control(
		'billing_input_name', [
			'label' => __( 'Field Name', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'options' => $this->bew_checkout_fields_name(),
			'default' => '',
			'label_block' => true,
		]
	);
	
	$repeater->add_control(
        'billing_input_field_key_custom', 
        [
            'label' => esc_html__( 'Custom Key', 'bew-extras' ),
            'type' => Controls_Manager::TEXT,
            'default' => esc_html__( 'customkey' , 'bew-extras' ),
            'label_block' => true,
			'description' => __( 'Use an unique custom key name( dont use spaces, accents or special characters' , 'bew-extras' ),
            'condition'=>[
                'billing_input_name'=>'billing_custom_field',
            ],
        ]
    );

	$repeater->add_control(
		'billing_input_type', [
			'label' => __( 'Input Type', 'bew-extras' ),
			'type' => Controls_Manager::SELECT2,
			'default' => 'text',
			'options' => [
				'textarea'			=> __( 'Textarea', 'bew-extras' ),
				'checkbox'			=> __( 'Checkbox', 'bew-extras' ),
				'text'				=> __( 'Text', 'bew-extras' ),
				'password'			=> __( 'Password', 'bew-extras' ),
				'date'				=> __( 'Date', 'bew-extras' ),
				'number'			=> __( 'Number', 'bew-extras' ),
				'email'				=> __( 'Email', 'bew-extras' ),
				'url'				=> __( 'Url', 'bew-extras' ),
				'tel'				=> __( 'Tel', 'bew-extras' ),
				'select'			=> __( 'Select', 'bew-extras' ),
				'radio'				=> __( 'Radio', 'bew-extras' ),
			],
		]
	);

	$repeater->add_control(
		'billing_input_options', [
			'label' => __( 'Options', 'bew-extras' ),
			'type' => Controls_Manager::TEXTAREA,
			'default' => implode( PHP_EOL, [ __( 'Option-1', 'bew-extras' ), __( 'Option-2', 'bew-extras' ), __( 'Option-3', 'bew-extras' ) ] ),
			'label_block' => true,
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => 'billing_input_type',
						'operator' => '==',
						'value' => 'select',
					],
					[
						'name' => 'billing_input_type',
						'operator' => '==',
						'value' => 'radio',
					],
				],
			],
			'description' => __( 'Enter the options one per line' , 'bew-extras' ),
		]
	);

	$repeater->add_control(
		'billing_input_options_layout', [
			'label' => __( 'Options Layout', 'bew-extras' ),
			'type' => Controls_Manager::CHOOSE,
			'options' => [
				'vertical' => [
					'title' => __( 'Vertical', 'bew-extras' ),
					'icon'  => 'eicon-ellipsis-v',
				],
				'horizontal' => [
					'title' => __( 'Horizontal', 'bew-extras' ),
					'icon'  => 'feicon-ellipsis-h',
				],
			],
			'default' 		=> '',
			'conditions' => [
				'relation' => 'or',
				'terms' => [
					[
						'name' => 'billing_input_type',
						'operator' => '==',
						'value' => 'select',
					],
					[
						'name' => 'billing_input_type',
						'operator' => '==',
						'value' => 'radio',
					],
				],
			],
		]
	);

	$repeater->add_control(
		'billing_input_placeholder', [
			'label' => __( 'Placeholder', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Placeholder' , 'bew-extras' ),
			'label_block' => true,
		]
	);

	$repeater->add_control(
		'billing_input_autocomplete', [
			'label' => __( 'Autocomplete Value', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Given value' , 'bew-extras' ),
			'label_block' => true,
		]
	);

	$repeater->add_control(
		'billing_input_required',
		[
			'label'         => __( 'Required', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'yes', 'bew-extras' ),
			'label_off'     => __( 'no', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => 'yes',
		]
	);

	$this->add_control(
		'woo_checkout_information_form_items',
		[
			'label' => __( '', 'bew-extras' ),
			'type' => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => $this->bew_checkout_fields(),
			'title_field' => '{{{ billing_input_label }}}',
		]
	);

	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_information_bew_account_section',
		[
			'label' => __( 'User Account', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
		'woo_checkout_information_bew_account_login_heading',
		[
			'label'     => __( 'Login User Account', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,
		]
	);
	
	$this->add_control(
        'woo_checkout_information_bew_account_login',
        [
            'label'         => esc_html__( 'Login User Account', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'On', 'bew-extras' ),
            'label_off'     => esc_html__( 'Off', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',           
        ]
    );

	$this->add_control(
		'woo_checkout_information_bew_account_login_type', [
			'label' => __( 'Login Account Layout', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'link',
			'options' => [
				'link'     => 'Link',
				'checkbox' => 'Checkbox',				
			],
			'condition'=>[
                'woo_checkout_information_bew_account_login' => 'yes',
            ],
		]
	);

	$this->add_control(
		'woo_checkout_information_bew_account_login_text',
		[
			'label' 		=> __( 'Before Login Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Already have an account?', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition'=>[
                'woo_checkout_information_bew_account_login' => 'yes',
				'woo_checkout_information_bew_account_login_type' => 'link'
            ],
		]
	);

	$this->add_control(
		'woo_checkout_information_bew_account_login_link',
		[
			'label' 		=> __( 'Login Link Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Log in.', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition'=>[
                'woo_checkout_information_bew_account_login' => 'yes',
				'woo_checkout_information_bew_account_login_type' => 'link'
            ],
		]
	);

	$this->add_control(
        'woo_checkout_information_bew_account_login_field_layout',
        [
            'label'         => esc_html__( 'Inside Layout', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
			'condition'=>[
                'woo_checkout_information_bew_account_login_type' => 'checkbox',
            ],
        ]
    );

	$this->add_control(
		'woo_checkout_information_bew_account_create_heading',
		[
			'label'     => __( 'Create User Account', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,
		]
	);

	$this->add_control(
        'woo_checkout_information_bew_account_create',
        [
            'label'         => esc_html__( 'Create User Account', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'On', 'bew-extras' ),
            'label_off'     => esc_html__( 'Off', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',           
        ]
    );

	$this->add_control(
		'woo_checkout_information_bew_account_create_type', [
			'label' => __( 'Create Account Layout', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'checkbox',
			'options' => [
				'checkbox' => 'Checkbox',
				'input'     => 'Input',								
			],
			'condition'=>[
                'woo_checkout_information_bew_account_create' => 'yes',
            ],
		]
	);

	$this->add_control(
		'woo_checkout_information_bew_account_create_label',
		[
			'label' 		=> __( 'Label Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Create an account', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition'=>[
                'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'input'
            ],
		]
	);

	$this->add_control(
        'woo_checkout_information_bew_account_create_field_layout',
        [
            'label'         => esc_html__( 'Inside Layout', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
			'condition'=>[
				'woo_checkout_information_bew_account_create' => 'yes',
                'woo_checkout_information_bew_account_create_type' => 'checkbox'
            ],
        ]
    );
		
	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_error_email_section',
		[
			'label' => __( 'Error', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
		'woo_checkout_information_error_required_text',
		[
			'label' 		=> __( 'Required Error Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'This information is required.', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			]
		]
	);
	
	$this->add_control(
		'woo_checkout_error_email_validation_text',
		[
			'label' 		=> __( 'Validation Email Error Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Enter a valid email address', 'bew-extras' ),
			'label_block'   => true,			
			'dynamic' 		=> [
				'active' 	=> true,
			]
		]
	);
			
	$this->end_controls_section();				

	//Section general style
	$this->start_controls_section(
		'woo_checkout_information_general_style',
		[
			'label' => __( 'General', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_general_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}}.elementor-widget-woo-checkout-form-information .bew-components-checkout-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_general_margin',
		[
			'label' => __( 'Margin', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}}.elementor-widget-woo-checkout-form-information .bew-components-checkout-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->end_controls_section();	

	//section title style
	$this->start_controls_section(
		'woo_checkout_information_title_style',
		[
			'label' => __( 'Title', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
               'condition' => [
                   'woo_checkout_information_title_show' => 'yes'
            ],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_title_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' 	=> '{{WRAPPER}} .bew-information-title',
		]
	);

    $this->add_control(
		'woo_checkout_billing_title_color',
		[
			'label'     => __( 'Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-heading .bew-information-title' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'          => 'woo_checkout_information_title_border',
			'label'         => __( 'Border', 'bew-extras' ),
			'selector'      => '{{WRAPPER}} .bew-checkout-step-heading .bew-information-title',
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_information_title_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-heading .bew-information-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_title_margin',
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

	/**
	 * Input Label Color
	 */
	$this->start_controls_section(
		'woo_checkout_information_style',
		[
			'label' => __( 'Labels', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_label_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' 	=> '{{WRAPPER}} .bew-information label',
		]
	);


    $this->add_control(
		'woo_checkout_information_label_color',
		[
			'label'     => __( 'Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-information label' => 'color: {{VALUE}}',
			],
		]
	);

    $this->add_control(
       	'woo_checkout_information_label_padding',
       	[
       		'label' => __( 'Padding', 'bew-extras' ),
       		'type' => Controls_Manager::DIMENSIONS,
       		'size_units' => [ 'px', '%', 'em' ],
       		'selectors' => [
       			'{{WRAPPER}} .bew-information label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
       		],
       	]
    );

	$this->add_control(
		'woo_checkout_information_label_inside_translate',
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
				'{{WRAPPER}} .bew-checkout-step-container.bew-information .form-row.label-inside-yes label, 
				 {{WRAPPER}} .woocommerce-account-fields .form-row.label-inside-yes label:not(.checkbox), 
				 {{WRAPPER}} .bew-information .bew-components-checkout-step__content .form-row.label-inside-yes label, 
				 {{WRAPPER}} .bew-account-fields .create-account.label-inside-yes label' => 'transform: translateY({{SIZE}}{{UNIT}});',
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_label_line_hight',
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
				'size' => '',
			],
			'selectors' => [
				'{{WRAPPER}} .bew-information label' => 'line-height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->end_controls_section();

	/**
	 * Input
	 */
	$this->start_controls_section(
		'woo_checkout_information_input_style',
		[
			'label' => __( 'Input Fields', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_input_typographyrs',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .bew-information input, 
							{{WRAPPER}} .bew-information select,
							{{WRAPPER}} .bew-information .select2-selection,					
							{{WRAPPER}} .bew-information option,
							{{WRAPPER}} .bew-information textarea
							{{WRAPPER}} .woocommerce-account-fields.bew-account input',
		]
	);

	$this->add_control(
		'woo_checkout_information_input_color',
		[
			'label'     => __( 'Input Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-information input, 
							 {{WRAPPER}} .bew-information select,
							 {{WRAPPER}} .bew-information .select2-selection,
							 {{WRAPPER}} .bew-information option,
							 {{WRAPPER}} .bew-information textarea,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_placeholder_color',
		[
			'label'     => __( 'Placeholder Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-information input::-webkit-input-placeholder, 
							 {{WRAPPER}} .bew-information select::-webkit-input-placeholder,
							 {{WRAPPER}} .bew-information .select2-selection::-webkit-input-placeholder,
							 {{WRAPPER}} .bew-information option::-webkit-input-placeholder,
							 {{WRAPPER}} .bew-information textarea::-webkit-input-placeholder,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_input_background_color',
		[
			'label'     => __( 'Background Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-information input, 
							 {{WRAPPER}} .bew-information select,
							 {{WRAPPER}} .bew-information .select2-selection,
							 {{WRAPPER}} .bew-information option,
							 {{WRAPPER}} .bew-information textarea,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'background-color: {{VALUE}}',
			],
		]
	);

    $this->add_group_control(
		Group_Control_Border::get_type(),
			[
			'name' 		=> 'woo_checkout_information_input_border',
			'label' 	=> __( 'Border', 'bew-extras' ),
			'separator' => 'before',
			'selector' => '{{WRAPPER}} .bew-information input, 
							{{WRAPPER}} .bew-information select,
							{{WRAPPER}} .bew-information .select2-selection,
							{{WRAPPER}} .bew-information textarea,
							{{WRAPPER}} .woocommerce-account-fields.bew-account input',
		]
	);

    $this->add_control(
		'woo_checkout_information_input_border_radius',
		[
			'label' => __( 'Border Radius', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-information input, 
				 {{WRAPPER}} .bew-information select,
				 {{WRAPPER}} .bew-information .select2-selection,
				 {{WRAPPER}} .bew-information textarea,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_input_border_color_focus',
		[
			'label'     => __( 'Focus Border Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-information input:focus, 
							 {{WRAPPER}} .bew-information select:focus,
							 {{WRAPPER}} .bew-information .select2-selection:focus,
							 {{WRAPPER}} .bew-information option:focus,
							 {{WRAPPER}} .bew-information textarea:focus,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input:focus' => 'border-color: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_input_border_width_focus',
		[
			'label' => __( 'Focus Border Width', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-information input:focus, 
				 {{WRAPPER}} .bew-information select:focus,
				 {{WRAPPER}} .bew-information .select2-selection:focus,
				 {{WRAPPER}} .bew-information textarea:focus,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_information_input_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-information input, 
				 {{WRAPPER}} .bew-information select,
				 {{WRAPPER}} .bew-information .select2-selection,
				 {{WRAPPER}} .bew-information textarea,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_input_margin',
		[
			'label' => __( 'Margin', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-information .form-row, {{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->add_control(
		'woo_checkout_information_input_wide_width',
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
				'{{WRAPPER}} .bew-information .form-row.form-row-wide, {{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row.form-row-wide' => 'width: {{SIZE}}{{UNIT}};',
			],			
		]
	);
		
	$this->add_control(
		'woo_checkout_information_input_first_width',
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
				'{{WRAPPER}} .bew-information .form-row.form-row-first, {{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row.form-row-first' => 'width: {{SIZE}}{{UNIT}};',
			],			
		]
	);

	$this->add_control(
		'woo_checkout_information_input_last_width',
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
				'{{WRAPPER}} .bew-information .form-row.form-row-last, {{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row.form-row-last' => 'width: {{SIZE}}{{UNIT}};',
			],			
		]
	);
	
	$this->add_control(
		'woo_checkout_information_input_height',
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
				'{{WRAPPER}} .bew-information input, 
				 {{WRAPPER}} .bew-information select,
				 {{WRAPPER}} .bew-information .select2-container,
				 {{WRAPPER}} .bew-information .select2-selection,
				 {{WRAPPER}} .bew-information textarea,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'height: {{SIZE}}{{UNIT}};',
			],			
		]
	);

	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_information_account',
		[
			'label' => __( 'User Account', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',			
			],
		]
	);
	
	$this->add_control(
		'woo_checkout_information_account_general',
		[
			'label'     => __( 'General', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',			
			],
		]
		
	);

	$this->add_responsive_control(
		'woo_checkout_information_account_general_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',			
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_information_account_general_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',			
			],
		]
	);	
	
	$this->add_control(
		'woo_checkout_information_account_create',
		[
			'label'     => __( 'Create User Account', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,
			'separator' => 'before',
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',			
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_account_create_checkbox',
		[
			'label'     => __( 'Checkbox', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,			
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'checkbox',			
			],
		]
	);
	
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_account_create_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .woocommerce-form__label-for-checkbox span',
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'checkbox',			
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_account_create_color',
		[
			'label'     => __( 'Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .woocommerce-form__label-for-checkbox span' => 'color: {{VALUE}}',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'checkbox',			
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_account_create_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .woocommerce-form__label-for-checkbox span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'checkbox',			
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_information_account_create_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .woocommerce-form__label-for-checkbox span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'checkbox',			
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_account_create_title',
		[
			'label'     => __( 'Title', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,			
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'input',			
			],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_account_create_title_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .bew-account-input-title span',
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'input',		
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_account_create_title_color',
		[
			'label'     => __( 'Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .bew-account-input-title span' => 'color: {{VALUE}}',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'input',			
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_account_create_title_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .bew-account-input-title span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'input',			
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_information_account_create_title_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .bew-account-input-title span' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => 'input',			
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_toggle_size',
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
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]:checked:before' => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]' => 'font-size: calc({{SIZE}}{{UNIT}} + 2px );',
			],
		]
	);
		
	$this->add_control(
		'woo_checkout_information_checkbox_color',
		[
			'label'     => __( 'Checkbox Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]:checked:before' => 'color: {{VALUE}} !important',
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_checkbox_background_color',
		[
			'label'     => __( 'Checkbox Background Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]:checked' => 'background-color: {{VALUE}} !important',
			],
		]
	);

    $this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name' 		=> 'woo_checkout_information_checkbox_border',
			'label' 	=> __( 'Border', 'bew-extras' ),
			'separator' => 'before',
			'selector' 	=> '{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]',
		]
	);

    $this->add_control(
		'woo_checkout_information_checkbox_border_radius',
		[
			'label' => __( 'Border Radius', 'bew-extras' ),
			'type' 	=> Controls_Manager::DIMENSIONS,
			'size_units' 	=> [ 'px', '%', 'em' ],
			'selectors' 	=> [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_checkbox_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' 	=> Controls_Manager::DIMENSIONS,
			'size_units' 	=> [ 'px', '%', 'em' ],
			'selectors' 	=> [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_checkbox_margin',
		[
			'label' => __( 'Margin', 'bew-extras' ),
			'type' 	=> Controls_Manager::DIMENSIONS,
			'size_units' 	=> [ 'px', '%', 'em' ],
			'selectors' 	=> [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .input-checkbox[type=checkbox]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
			],
		]
	);
		
	$this->add_control(
		'woo_checkout_information_account_password',
		[
			'label'     => __( 'Password', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,				
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);
	
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_account_password_strength_typography',
			'label' 	=> __( 'Strength Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-strength',
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_information_account_password_hint_typography',
			'label' 	=> __( 'Hint Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-hint',
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);

	$this->add_control(
		'woo_checkout_information_account_password_strength_color',
		[
			'label'     => __( 'Strength Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-strength' => 'color: {{VALUE}}',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);
	
	$this->add_control(
		'woo_checkout_information_account_password_hint_color',
		[
			'label'     => __( 'Hint Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-hint' => 'color: {{VALUE}}',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_information_account_password_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_information_account_password_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
			'condition' => [
				'woo_checkout_information_bew_account_create' => 'yes',
				'woo_checkout_information_bew_account_create_type' => [ 'checkbox', 'input' ],			
			],
		]
	);
	
	$this->end_controls_section();

	}
	
	function bew_checkout_fields_name( $section = 'billing' ) {
		if( !function_exists( 'WC' ) ) return [];

		if ( is_admin() ) {
			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
		}

		$get_fields = WC()->checkout->get_checkout_fields();
		
		$options = [];
		foreach ( $get_fields[ $section ] as $key => $field ) {
				$options[ $key ] = $field['label'];
		}
		
		$options[ 'billing_custom_field'] = 'Custom';
			
		return $options;
	}
	
	function bew_checkout_fields( $section = 'billing' ) {
		if( !function_exists( 'WC' ) ) return [];

		if ( is_admin() ) {
			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
		}

		$get_fields = WC()->checkout->get_checkout_fields();		
				
		$fields = [];
		foreach ( $get_fields[ $section ] as $key => $field ) {
			
			if ($key == 'billing_email'){
				$fields[] = [
					"{$section}_input_label" 		=> $field['label'],
					"{$section}_input_name" 		=> $key,
					"{$section}_input_required" 	=> isset( $field['required'] ) ? $field['required'] : false,
					"{$section}_input_type" 		=> isset( $field['type'] ) ? $field['type'] : 'text',
					"{$section}_input_class" 		=> $field['class'] ,
					"{$section}_input_autocomplete" => isset( $field['autocomplete'] ) ? $field['autocomplete'] : '' ,
					"{$section}_input_placeholder"	=> isset( $field['placeholder'] ) ? $field['placeholder'] : '' ,
				];
			}
		}
		
		return $fields;
	}

	function bew_login_i_url( $redirect = '', $force_reauth = false ) {
		$login_url = get_permalink( wc_get_page_id( 'myaccount'  )); 
		
		if ( ! empty( $redirect ) ) {
			$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
		}
	 
		if ( $force_reauth ) {
			$login_url = add_query_arg( 'reauth', '1', $login_url );
		}

		return apply_filters( 'bew_login_i_url', $login_url, $redirect, $force_reauth );
	}

	protected function render() {
	if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
			
		$settings = $this->get_settings();		
				
		$checkout_information_steps     = $settings['woo_checkout_information_steps'];
		$information_description_show   = $settings['woo_checkout_information_description_show'];
		$information_description_text   = $settings['woo_checkout_information_description_text'];
		$information_form_items 	    = $settings['woo_checkout_information_form_items'];
		$information_title_show 	    = $settings['woo_checkout_information_title_show'];
		$payment_title_tag  	        = Utils::validate_html_tag( $settings['woo_checkout_information_title_tag'] );
		$information_title_text 	    = $settings['woo_checkout_information_title_text'];
		$bew_account_login   	        = $settings['woo_checkout_information_bew_account_login'];
		$bew_account_login_type   	    = $settings['woo_checkout_information_bew_account_login_type'];		
		$bew_account_login_field_layout = $settings['woo_checkout_information_bew_account_login_field_layout'];
		$bew_account_create   	        = $settings['woo_checkout_information_bew_account_create'];
		$bew_account_create_type   	    = $settings['woo_checkout_information_bew_account_create_type']; 
		$bew_account_create_input_label = $settings['woo_checkout_information_bew_account_create_label'];
		$bew_account_create_field_layout = $settings['woo_checkout_information_bew_account_create_field_layout'];
		$error_required  	            = $settings['woo_checkout_information_error_required_text'];
		$error_email_validation  	    = $settings['woo_checkout_error_email_validation_text'];
		$account_text  	                = $settings['woo_checkout_information_bew_account_login_text'];
		$account_login_text  	        = $settings['woo_checkout_information_bew_account_login_link'];
				
		//custom acccount class									
		$bew_account_create_class = [ 'custom_class'=>  "label-inside-" . $bew_account_create_field_layout];
									
		//echo var_dump($this->bew_checkout_fields());
		//echo var_dump(WC()->checkout->get_checkout_fields());

		//echo var_dump(WC()->checkout->get_checkout_fields());
		//echo var_dump(get_option( '_bew_checkout_fields', [] ));	
		
		$is_reg_req = '';
				
		$information_fields = [];
		foreach( $information_form_items as $item ) {
		
			$information_input_class = $item['billing_input_class'];
			$has_placeholder = empty($item['billing_input_placeholder']) ? '' : ' has-placeholder';
			
			array_push($item['billing_input_class'], "label-inside-" . $item['billing_input_label_layout'] . " label-hide-" . $item['billing_input_label_hide'] . $has_placeholder );

			$fkey = $item['billing_input_name'];
			
			if( $item['billing_input_name'] == 'billing_custom_field' ){
                $fkey = 'billing_'.$item['billing_input_field_key_custom'];
            }
						
			$information_fields[ sanitize_text_field(  $fkey ) ] = 
		        [
		            'label'			=> sanitize_text_field( __( $item['billing_input_label'], 'woocommerce' )),
		            'type'			=> $item['billing_input_type'],
		            'required'		=> $item['billing_input_required'] == 'yes' ? true : false,
		            'class'			=> is_array( $item['billing_input_class'] ) ? $item['billing_input_class'] : explode( ' ', $item['billing_input_class'] ),
		            'autocomplete'	=> sanitize_text_field( $item['billing_input_autocomplete'] ), 
		            'placeholder'	=> sanitize_text_field( $item['billing_input_placeholder'] ),
		            'priority'		=> 10,
		        ];

				if( ($item['billing_input_type'] == 'select' )  || ($item['billing_input_type'] == 'radio') ){
						
						if ( isset( $item['billing_input_options'] ) ) {
							//echo var_dump($item['billing_input_options']);
							$options = explode( "\n", $item['billing_input_options'] );
							//echo var_dump($options);
							$new_options = [];
							$i = 0;
							foreach ( $options as $option ) {
								$new_options[ strtolower( sanitize_title($option) ) ] = $option;
									
								if($i == 0) {
									$default = strtolower( sanitize_title($option) );
								}
							$i++;
							}
								
						}
													
						//echo var_dump($default);
						$billing_fields[$fkey]['option_layout'] 		= $item['billing_input_options_layout']; 
						$billing_fields[$fkey]['options']        		= isset( $new_options ) ? $new_options : '';
						$billing_fields[$fkey]['default']        		= $default;
				}
				
				if( $item['billing_input_name'] == 'billing_custom_field' ){
					
						if ( isset( $item['billing_input_options'] ) ) {
							//echo var_dump($item['billing_input_options']);
							$options = explode( "\n", $item['billing_input_options'] );
							//echo var_dump($options);
							$new_options = [];
							foreach ( $options as $option ) {
								$new_options[ strtolower( $option ) ] = $option;
							}							
						}
						
						//echo var_dump($new_options);
						
						$billing_fields[$fkey]['custom']         		= true;
						$billing_fields[$fkey]['type']           		= $item['billing_input_type'];
						$billing_fields[$fkey]['show_in_email']  		= $item['billing_input_show_email'] == 'yes' ? true : '';
						$billing_fields[$fkey]['show_in_order']  		= $item['billing_input_show_order'] == 'yes' ? true : '';
						$billing_fields[$fkey]['conditional']    		= $item['billing_input_conditional']; 
						$billing_fields[$fkey]['superior_field'] 		= sanitize_title($item['billing_input_superior_field']); 
						$billing_fields[$fkey]['superior_field_option'] = sanitize_title($item['billing_input_superior_field_option']);
						
						if( ($item['billing_input_type'] == 'select' )  || ($item['billing_input_type'] == 'radio') ){
						
							$billing_fields[$fkey]['option_layout'] 		= $item['billing_input_options_layout'];
							$billing_fields[$fkey]['options']        		= isset( $new_options ) ? $new_options : '';
							$billing_fields[$fkey]['default']        		= $default;
							
						}
						
                }
		// Send Fields to regenerate
				
		if( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			$_bew_checkout_fields = get_option( '_bew_checkout_fields', [] );
			if (is_array( $_bew_checkout_fields)){
				$_bew_checkout_fields['information'] = $information_fields;
				update_option( '_bew_checkout_fields', $_bew_checkout_fields );
			} else {
				update_option( '_bew_checkout_fields',  [] );
			}
			update_option( '_bew_checkout_fields_information', 'bew_fields_information');	
		}		
		
		}
		
		//echo var_dump(get_option( '_bew_checkout_fields'));		
		//$get_fields = WC()->checkout->get_checkout_fields();
		//echo var_dump($get_fields) ;
		
		$helper = new Helper();
		
		?>
		<div class="bew-components-checkout-step bew-checkout-steps-<?php echo $checkout_information_steps; ?>">
			<?php if( 'yes' == $information_title_show ): ?>
				<div class="bew-checkout-step-heading">
				<<?php echo esc_attr( $payment_title_tag ); ?> class="bew-checkout-step-title bew-information-title"><?php echo esc_html( $information_title_text ); ?></<?php echo esc_attr( $payment_title_tag ) ?>>
				<div class="bew-woo-checkout">bew-woo-checkout</div>
				</div>
			<?php endif; ?>
			<div class="bew-checkout-step-container bew-information">
				<?php
				if('yes' == $information_description_show ){
				?>	
					<p class="bew-components-checkout-step__description"><?php echo esc_html( $information_description_text ); ?></p>
				<?php
				}
				?>
				<div class="bew-components-checkout-step__content">
					<?php

					foreach ( $information_fields as $key => $field ) {
						$helper->bew_woocommerce_form_field( $key, $field, WC()->checkout->get_value( $key ) );
					}				
					?>
				</div>
			</div>
		</div>
		
		<?php
		
		$checkout = new \WC_Checkout;
					
		if($bew_account_login == "yes"){
			
			if ( ! is_user_logged_in() || (Elementor\Plugin::instance()->editor->is_edit_mode()) ) {
					
				if ( $bew_account_login_type == "link" ) {
				?>		
					<div class="bew-account-type-link">
						<div class="bew-components-checkout-step__heading-content">
							<span class="account-before"><?php esc_attr_e($account_text, 'bew-extras' ); ?> </span>
							<a class="account-link" href="<?php echo esc_url($this->bew_login_i_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e($account_login_text, 'bew-extras' ); ?>">
							<i aria-hidden="true" class="icon-user"></i>
								<?php esc_attr_e($account_login_text, 'bew-extras' ); ?>
							</a>
						</div>					
					</div>
				<?php	
				}
			};	
		}

		if($bew_account_create == "yes"){
						
			if ( (! is_user_logged_in() && $checkout->is_registration_enabled()) || (Elementor\Plugin::instance()->editor->is_edit_mode() && $checkout->is_registration_enabled()) ) {
				
				if ( $checkout->is_registration_required() ) {
					$is_reg_req = 'is-reg-req';
				}
				
									
				?>			
					<div class="bew-account-fields woocommerce-account-fields bew-account bew-checkout-steps-<?php echo $checkout_information_steps; ?> bew-account-type-<?php echo $bew_account_create_type; ?>">
						<div class="bew-checkout-step-container">							
							<?php
							if ( $bew_account_create_type == "input" ) { ?>	
							
								<label class="bew-account-input-title">
									<span><?php esc_html_e( $bew_account_create_input_label , 'bew-extras' ); ?></span>
								</label>
							
							<?php } else {
								
							 if ( ! $checkout->is_registration_required() ) { ?>

								<p class="form-row form-row-wide create-account create-account-checkbox">
									<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
										<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
									</label>
								</p>

							<?php } 
								
							} ?>

							<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

							<?php if ( $checkout->get_checkout_fields( 'account' ) ) { ?>							
								
									<div class="create-account <?php echo $is_reg_req; ?>">
										<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) : 
											
											if ($bew_account_create_field_layout == 'yes') {
												$field ['label'] = __( 'Password', 'woocommerce' );	
												?>
												<?php $field = $field +$bew_account_create_class;
											}											
											//echo var_dump($field);
											$helper->bew_woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
										<?php endforeach; ?>
										<div class="clear"></div>
									</div>

							<?php } ?>

							<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
							
						</div>
					</div>
			<?php				
			}
		}		

		// Enqueue checkout JS
		wp_localize_script( 'bew-checkout',
			'checkoutInformation',
			array(
				'error_information_required'   => $error_required,
				'error_email_validation' => $error_email_validation,			
			) );

	}

	protected function _content_template() {
		
	}
	
}

