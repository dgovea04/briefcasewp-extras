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
use BriefcasewpExtras\Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Form_Billing extends Base_Widget {
	
	public function __construct( $data = [], $args = null ) {
	    parent::__construct( $data, $args );		
		
		// Enabled OPC ans Wao on Woo orders for elementor editor.
		if(Elementor\Plugin::instance()->editor->is_edit_mode()){
			add_filter( 'is_bew_woo_checkout', function( $is_bwco ) {
				return true;
			} );
			add_filter( 'is_bewopc_checkout', function( $is_opc ) {
				return false;
			} );
			
			add_filter( 'is_bewwao_checkout', function( $is_wao ) {
				return false;
			} );
		}

	}

	public function get_name() {
		return 'woo-checkout-form-billing';
	}

	public function get_title() {
		return __( 'Checkout Form Billing', 'bew-extras' );
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
	
	protected function _register_controls() {

	$this->start_controls_section(
		'woo_checkout_shipping_title',
		[
			'label' => __( 'Title', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);
	
	$this->add_control(
        'woo_checkout_billing_steps',
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
		'woo_checkout_billing_vertical_line',
		[
			'label'         => __( 'Vertical Line', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			   'label_on'      => __( 'Show', 'bew-extras' ),
			   'label_off'     => __( 'Hide', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => 'yes',
			'condition' => [
			   'woo_checkout_billing_steps' => 'active'
			],
			'prefix_class' => 'steps-vertical-line-',
		]
	);
	
	$this->add_control(
        'woo_checkout_billing_title_show',
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
	    'woo_checkout_billing_title_text',
	    [
	        'label' 		=> __( 'Text', 'bew-extras' ),
	        'type' 			=> Controls_Manager::TEXT,
	        'default' 		=> __( 'Billing Address', 'woocommerce' ) ,
            'condition' 	=> [
                'woo_checkout_billing_title_show' => 'yes'
            ],
			'label_block'   => true,
	        'dynamic' 		=> [
	            'active' 	=> true,
	        ]
	    ]
	);

	$this->add_control(
		'woo_checkout_billing_title_tag',
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
                'woo_checkout_billing_title_show' => 'yes'
            ],
		]
	);
	
	$this->add_control(
        'woo_checkout_billing_description_show',
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
	    'woo_checkout_billing_description_text',
	    [
	        'label' 		=> __( 'Text', 'bew-extras' ),
	        'type' 			=> Controls_Manager::TEXT,
	        'default' 		=> __( "Enter the address that matches your card or payment method.", 'bew-extras' ) ,
            'condition' 	=> [
                'woo_checkout_billing_description_show' => 'yes'
            ],
			'label_block'   => true,
	        'dynamic' 		=> [
	            'active' 	=> true,
	        ]
	    ]
	);

	$this->add_responsive_control(
        'woo_checkout_billing_title_alignment',
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
                'woo_checkout_billing_title_show' => 'yes'
            ],
            'selectors' => [
                '{{WRAPPER}} .bew-billing-title' => 'text-align: {{VALUE}};',
            ],
        ]
    );

	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_content_section',
		[
			'label' => __( 'Billing Fields', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_field_editor',
		[
			'label'	 	=> __( 'Field Editor', 'bew-extras' ),
			'type' 		=> Controls_Manager::SWITCHER,
			'label_on' 	=> __( 'Yes', 'elementor' ),
			'label_off' => __( 'No', 'elementor' ),
			'return_value' 	=> 'yes',
			'default' 		=> 'yes',
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_field_editor_notice',
		[
			'raw' => __( 'IMPORTANT: To apply all changes from fields editor, save and reload this template.', 'bew-extras' ),
			'type' => Controls_Manager::RAW_HTML,
			'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
			'condition' => [
				'woo_checkout_billing_field_editor' => 'yes',
			],
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
            'default' => esc_html__( 'customkey' . rand( 111, 999 ), 'bew-extras' ),
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
			'type' => Controls_Manager::SELECT,
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
			'separator' => 'before',
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
					'icon'  => 'eicon-ellipsis-h',
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
		'billing_input_conditional',
		[
			'label'         => __( 'Conditional Field', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'yes', 'bew-extras' ),
			'label_off'     => __( 'no', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => '',
			'separator' => 'before',
		]
	);

	$repeater->add_control(
		'billing_input_superior_field', 
		[
			'label' => __( 'Superior Field', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'default' => '',
			'options' => $this->bew_checkout_fields_name_custom(),
			'label_block' => true,
			 'condition'=>[
                'billing_input_conditional'=>'yes',
            ],
			'description' => __( 'If you need to choose a recent created custom field, save an reload the page, to get the custom field on the list' , 'bew-extras' ),
		]
	);
	
	$repeater->add_control(
		'billing_input_superior_field_option', 
		[
			'label' => __( 'Superior Field Options', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Enter option' , 'bew-extras' ),
			'label_block' => true,
			'description' => __( 'Enter the option from superior field selected for your conditional display', 'bew-extras' ),
			'condition'=>[             
			   'billing_input_superior_field!'=>'',
            ],
		]
	);

	$repeater->add_control(
		'billing_input_placeholder', 
		[
			'label' => __( 'Placeholder', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Placeholder' , 'bew-extras' ),
			'label_block' => true,
		]
	);

	$repeater->add_control(
		'billing_input_autocomplete', 
		[
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

	$repeater->add_control(
		'billing_input_intl_phone',
		[
			'label'         => __( 'Intl Phone', 'briefcase-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'yes', 'briefcase-extras' ),
			'label_off'     => __( 'no', 'briefcase-extras' ),
			'return_value'  => 'yes',
			'default'       => '',
			'condition' 	=> [
				'billing_input_name' => 'billing_phone',
			],
		]
	);
	
	$repeater->add_control(
		'billing_input_hide',
		[
			'label'         => __( 'Hide Field', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
			'label_on'      => __( 'yes', 'bew-extras' ),
			'label_off'     => __( 'no', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => '',
			'condition' 	=> [
			'billing_input_name' => [ 'billing_country', 'billing_state' ],
            ],
		]
	);

    $repeater->add_control(
        'billing_input_show_email',
        [
            'label'         => esc_html__( 'Show in Email', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => '',
            'condition'=>[
                'billing_input_name' => 'billing_custom_field',
            ],
        ]
    );

    $repeater->add_control(
        'billing_input_show_order',
        [
            'label'         => esc_html__( 'Show in Order Detail Page', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => '',
            'condition'=>[
                'billing_input_name' => 'billing_custom_field',
            ],
        ]
    );

	$this->add_control(
		'woo_checkout_billing_form_items',
		[
			'label' => __( '', 'bew-extras' ),
			'type' => Controls_Manager::REPEATER,
			'fields' => $repeater->get_controls(),
			'default' => $this->bew_checkout_fields(),
			'title_field' => '{{{ billing_input_label }}}',
			'condition'=>[
               'woo_checkout_billing_field_editor'=>'yes',
            ],
		]
	);

	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_billing_bew_account_section',
		[
			'label' => __( 'User Account', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
		'woo_checkout_billing_bew_account_login_heading',
		[
			'label'     => __( 'Login User Account', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,
		]
	);
	
	$this->add_control(
        'woo_checkout_billing_bew_account_login',
        [
            'label'         => esc_html__( 'Login User Account', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => '',           
        ]
    );

	$this->add_control(
		'woo_checkout_billing_bew_account_login_type', [
			'label' => __( 'Login Account Layout', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'link',
			'options' => [
				'link'     => 'Link',
				'checkbox' => 'Checkbox',				
			],
			'condition'=>[
                'woo_checkout_billing_bew_account_login' => 'yes',
            ],
		]
	);

	$this->add_control(
		'woo_checkout_billing_bew_account_login_text',
		[
			'label' 		=> __( 'Before Login Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Already have an account?', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition'=>[
                'woo_checkout_billing_bew_account_login' => 'yes',
				'woo_checkout_billing_bew_account_login_type' => 'link'
            ],
		]
	);

	$this->add_control(
		'woo_checkout_billing_bew_account_login_link',
		[
			'label' 		=> __( 'Login Link Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Log in.', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition'=>[
                'woo_checkout_billing_bew_account_login' => 'yes',
				'woo_checkout_billing_bew_account_login_type' => 'link'
            ],
		]
	);

	$this->add_control(
        'woo_checkout_billing_bew_account_login_field_layout',
        [
            'label'         => esc_html__( 'Inside Layout', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
			'condition'=>[
                'woo_checkout_billing_bew_account_login_type' => 'checkbox',
            ],
        ]
    );

	$this->add_control(
		'woo_checkout_billing_bew_account_create_heading',
		[
			'label'     => __( 'Create User Account', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,
		]
	);

	$this->add_control(
        'woo_checkout_billing_bew_account_create',
        [
            'label'         => esc_html__( 'Create User Account', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'On', 'bew-extras' ),
            'label_off'     => esc_html__( 'Off', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => '',           
        ]
    );

	$this->add_control(
		'woo_checkout_billing_bew_account_create_type', [
			'label' => __( 'Create Account Layout', 'bew-extras' ),
			'type' => Controls_Manager::SELECT,
			'default' => 'checkbox',
			'options' => [
				'checkbox' => 'Checkbox',
				'input'    => 'Input',								
			],
			'condition'=>[
                'woo_checkout_billing_bew_account_create' => 'yes',
            ],
		]
	);

	$this->add_control(
        'woo_checkout_billing_bew_account_create_field_layout',
        [
            'label'         => esc_html__( 'Inside Layout', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => 'yes',
			'condition'=>[
                'woo_checkout_billing_bew_account_create' => 'yes',
				'woo_checkout_billing_bew_account_create_type' => 'checkbox'				
            ],
        ]
    );
		
	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_billing_error_section',
		[
			'label' => __( 'Error', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_error_required_text',
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
		'woo_checkout_billing_error_validation_text',
		[
			'label' 		=> __( 'Validation Error Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'This information is not a valid', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			]
		]
	);

	$this->add_control(
		'woo_checkout_billing_error_mail_validation_text',
		[
			'label' 		=> __( 'Email Validation Error Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Invalid email address.', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			]
		]
	);
			
	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_billing_fast_checkout_section',
		[
			'label' => __( 'Fast Checkout', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);

	$this->add_control(
        'woo_checkout_billing_fast_checkout_name',
        [
            'label'         => esc_html__( 'Full Name', 'bew-extras' ),
            'type'          => Controls_Manager::SWITCHER,
            'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
            'label_off'     => esc_html__( 'No', 'bew-extras' ),
            'return_value'  => 'yes',
            'default'       => '',           
        ]
    );
	
	$this->add_control(
		'woo_checkout_billing_fast_checkout_full_name',
		[
			'label' 		=> __( 'Full Name Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'Name', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition' => [
                'woo_checkout_billing_fast_checkout_name' => 'yes'
            ],
		]
	);

	$this->add_control(
		'woo_checkout_billing_fast_checkout_first_name',
		[
			'label' 		=> __( 'First Name Text', 'bew-extras' ),
			'type' 			=> Controls_Manager::TEXT,
			'default' 		=> __( 'First Name', 'bew-extras' ),					
			'label_block'   => true,
			'dynamic' 		=> [
				'active' 	=> true,
			],
			'condition' => [
                'woo_checkout_billing_fast_checkout_name' => 'yes'
            ],
		]
	);
			
	$this->end_controls_section();
	
	//Section general style
	$this->start_controls_section(
		'woo_checkout_billing_general_style',
		[
			'label' => __( 'General', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_general_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}}.elementor-widget-woo-checkout-form-billing .bew-components-checkout-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_general_margin',
		[
			'label' => __( 'Margin', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}}.elementor-widget-woo-checkout-form-billing .bew-components-checkout-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->end_controls_section();	
		
	//Section title style
	$this->start_controls_section(
		'woo_checkout_billing_title_style',
		[
			'label' => __( 'Title', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
            'condition' => [
                   'woo_checkout_billing_title_show' => 'yes'
            ],
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_title_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' 	=> '{{WRAPPER}} .bew-checkout-step-heading .bew-billing-title',
		]
	);

    $this->add_control(
		'woo_checkout_billing_title_color',
		[
			'label'     => __( 'Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-heading .bew-billing-title' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'          => 'woo_checkout_billing_title_border',
			'label'         => __( 'Border', 'bew-extras' ),
			'selector'      => '{{WRAPPER}} .bew-checkout-step-heading .bew-billing-title',
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_billing_title_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-heading .bew-billing-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_title_margin',
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
		'woo_checkout_billing_style',
		[
			'label' => __( 'Labels', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_label_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' 	=> '{{WRAPPER}} .bew-billing label:not(.radio)',
		]
	);

    $this->add_control(
		'woo_checkout_billing_label_color',
		[
			'label'     => __( 'Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-billing label:not(.radio)' => 'color: {{VALUE}}',
			],
		]
	);

    $this->add_control(
		'woo_checkout_billing_label_required_color',
		[
			'label'     => __( 'Required Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-billing label:not(.radio) .required' => 'color: {{VALUE}}',
			],
		]
	);
	
    $this->add_responsive_control(
       	'woo_checkout_billing_label_padding',
       	[
       		'label' => __( 'Padding', 'bew-extras' ),
       		'type' => Controls_Manager::DIMENSIONS,
       		'size_units' => [ 'px', '%', 'em' ],
       		'selectors' => [
       			'{{WRAPPER}} .bew-billing label:not(.radio)' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
       		],
       	]
    );
	
    $this->add_responsive_control(
       	'woo_checkout_billing_label_margin',
       	[
       		'label' => __( 'Padding', 'bew-extras' ),
       		'type' => Controls_Manager::DIMENSIONS,
       		'size_units' => [ 'px', '%', 'em' ],
       		'selectors' => [
       			'{{WRAPPER}} .bew-billing label:not(.radio)' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
       		],
       	]
    );

	$this->add_control(
		'woo_checkout_billing_label_inside_translate',
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
				'{{WRAPPER}} .bew-checkout-step-container.bew-billing .form-row.label-inside-yes label, 
				 {{WRAPPER}} .woocommerce-account-fields .form-row.label-inside-yes label:not(.checkbox), 
				 {{WRAPPER}} .bew-billing .bew-components-checkout-step__content .form-row.label-inside-yes label, 
				 {{WRAPPER}} .bew-account-fields .create-account.label-inside-yes label' => 'transform: translateY({{SIZE}}{{UNIT}});',
			],
		]
	);

	$this->add_control(
		'woo_checkout_billing_label_line_hight',
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
				'{{WRAPPER}} .bew-billing label:not(.radio)' => 'line-height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$this->end_controls_section();

	/**
	 * Input
	 */
	$this->start_controls_section(
		'woo_checkout_billing_input_style',
		[
			'label' => __( 'Input Fields', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_input_typographyrs',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .bew-billing input, 
							{{WRAPPER}} .bew-billing select,
							{{WRAPPER}} .bew-billing .select2-selection,					
							{{WRAPPER}} .bew-billing option,
							{{WRAPPER}} .bew-billing textarea,
							{{WRAPPER}} .woocommerce-account-fields.bew-account input',
		]
	);

	$this->add_control(
		'woo_checkout_billing_input_color',
		[
			'label'     => __( 'Input Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-billing input, 
							 {{WRAPPER}} .bew-billing select,
							 {{WRAPPER}} .bew-billing .select2-selection,
							 {{WRAPPER}} .bew-billing option,
							 {{WRAPPER}} .bew-billing textarea,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'color: {{VALUE}}',
			],
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_placeholder_color',
		[
			'label'     => __( 'Placeholder Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-billing input::-webkit-input-placeholder, 
							 {{WRAPPER}} .bew-billing select::-webkit-input-placeholder,
							 {{WRAPPER}} .bew-billing .select2-selection::-webkit-input-placeholder,
							 {{WRAPPER}} .bew-billing option::-webkit-input-placeholder,
							 {{WRAPPER}} .bew-billing textarea::-webkit-input-placeholder,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input::-webkit-input-placeholder' => 'color: {{VALUE}} !important;',
			],
		]
	);

	$this->add_control(
		'woo_checkout_billing_input_background_color',
		[
			'label'     => __( 'Background Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .bew-billing input, 
							 {{WRAPPER}} .bew-billing select,
							 {{WRAPPER}} .bew-billing .select2-selection,
							 {{WRAPPER}} .bew-billing option,
							 {{WRAPPER}} .bew-billing textarea,
							 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'background-color: {{VALUE}}',
			],
		]
	);

    $this->add_group_control(
		Group_Control_Border::get_type(),
			[
			'name' 		=> 'woo_checkout_billing_input_border',
			'label' 	=> __( 'Border', 'bew-extras' ),
			'separator' => 'before',
			'selector' => '{{WRAPPER}} .bew-billing input, 
							{{WRAPPER}} .bew-billing select,
							{{WRAPPER}} .bew-billing .select2-selection,
							{{WRAPPER}} .bew-billing textarea,
							{{WRAPPER}} .woocommerce-account-fields.bew-account input',
		]
	);

    $this->add_control(
		'woo_checkout_billing_input_border_radius',
		[
			'label' => __( 'Border Radius', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-billing input, 
				 {{WRAPPER}} .bew-billing select,
				 {{WRAPPER}} .bew-billing .select2-selection,
				 {{WRAPPER}} .bew-billing textarea,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_control(
		'woo_checkout_billing_input_border_color_focus',
		[
			'label'     => __( 'Focus Border Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-billing input:focus, 
				 {{WRAPPER}} .bew-billing select:focus,
				 {{WRAPPER}} .bew-billing .select2-selection:focus,
				 {{WRAPPER}} .bew-billing option:focus,
				 {{WRAPPER}} .bew-billing textarea:focus,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input:focus' => 'border-color: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_input_border_width_focus',
		[
			'label' => __( 'Focus Border Width', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-billing input:focus, 
				 {{WRAPPER}} .bew-billing select:focus,
				 {{WRAPPER}} .bew-billing .select2-selection:focus,
				 {{WRAPPER}} .bew-billing textarea:focus,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_input_padding',
		[
			'label' => __( 'Padding', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-billing input, 
				 {{WRAPPER}} .bew-billing select,
				 {{WRAPPER}} .bew-billing .select2-selection,
				 {{WRAPPER}} .bew-billing textarea,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_input_margin',
		[
			'label' => __( 'Margin', 'bew-extras' ),
			'type' => Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em' ],
			'selectors' => [
				'{{WRAPPER}} .bew-billing .form-row, {{WRAPPER}} .woocommerce-account-fields.bew-account .form-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_input_wide_width',
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
				'{{WRAPPER}} .bew-billing .form-row.form-row-wide' => 'width: {{SIZE}}{{UNIT}};',
			],			
		]
	);
		
	$this->add_control(
		'woo_checkout_billing_input_first_width',
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
				'{{WRAPPER}} .bew-billing .form-row.form-row-first' => 'width: {{SIZE}}{{UNIT}};',
			],			
		]
	);

	$this->add_control(
		'woo_checkout_billing_input_last_width',
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
				'{{WRAPPER}} .bew-billing .form-row.form-row-last' => 'width: {{SIZE}}{{UNIT}};',
			],			
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_input_height',
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
				'{{WRAPPER}} .bew-billing input, 
				 {{WRAPPER}} .bew-billing select,
				 {{WRAPPER}} .bew-billing .select2-container,
				 {{WRAPPER}} .bew-billing .select2-selection,
				 {{WRAPPER}} .bew-billing textarea,
				 {{WRAPPER}} .woocommerce-account-fields.bew-account input' => 'height: {{SIZE}}{{UNIT}};',
			],			
		]
	);
	
	$this->end_controls_section();
	
	//section radio style
	$this->start_controls_section(
		'woo_checkout_billing_options_radio_style',
		[
			'label' => __( 'Radio Button', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_options_label_typography',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label',
		]
	);

	$this->add_control(
		'woo_checkout_billing_options_label_color',
		[
			'label'     => __( 'Label Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'woo_checkout_billing_options_radio_border_radius',
		[
			'label'         => __( 'Border Radius', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->start_controls_tabs(
		'woo_checkout_billing_options_radio_separator',
		[
			'separator' => 'before'
		]
	);

	$this->start_controls_tab(
		'woo_checkout_billing_options_radio_normal',
		[
			'label'     => __( 'Normal', 'bew-extras' ),
		]
	);

	$this->add_control(
		'woo_checkout_billing_options_radio_color',
		[
			'label'     => __( 'Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio  label:before' => 'border-color: {{VALUE}}',
			],
		]
	);

	$this->add_control(
		'woo_checkout_bg_billing_options_radio_color',
		[
			'label'     => __( 'Background', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label:before' => 'background-color: {{VALUE}}',
			],
		]
	);

	$this->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'          => 'woo_checkout_billing_options_radio_border',
			'label'         => __( 'Border', 'bew-extras' ),
			'selector'      => '{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label:before',
		]
	);

	$this->end_controls_tab();

	$this->start_controls_tab(
		'woo_checkout_billing_options_radio_checked',
		[
			'label'     => __( 'Checked', 'bew-extras' ),
		]
	);
		
	$this->add_control(
		'woo_checkout_billing_options_text_radio_checked',
		[
			'label'     => __( 'Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label:after' => 'background: {{VALUE}}',
			],
		]
	);

	$this->end_controls_tab();
	$this->end_controls_tabs();	
	
	$this->add_responsive_control(
		'woo_checkout_billing_options_radio_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .bew-checkout-step-container .form-row .bew-input-radio label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->end_controls_section();

	$this->start_controls_section(
		'woo_checkout_billing_account',
		[
			'label' => __( 'User Account', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_STYLE,
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_account_general',
		[
			'label'     => __( 'General', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,				
			'condition' => [
				'woo_checkout_bew_account_type' => [ 'checkbox', 'input' ],			
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_account_general_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_billing_account_general_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);	
	
	$this->add_control(
		'woo_checkout_billing_account_title',
		[
			'label'     => __( 'Title', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,				
			'condition' => [
				'woo_checkout_bew_account_type' => [ 'checkbox', 'input' ],			
			],
		]
	);
	
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_account_title_typographyrs',
			'label' 	=> __( 'Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account-title',
		]
	);

	$this->add_control(
		'woo_checkout_billing_account_title_color',
		[
			'label'     => __( 'Input Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account-titlet' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_account_title_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_billing_account_title_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$this->add_control(
		'woo_checkout_billing_account_password',
		[
			'label'     => __( 'Password', 'bew-extras' ),
			'type'      => Controls_Manager::HEADING,				
			'condition' => [
				'woo_checkout_bew_account_type' => [ 'checkbox', 'input' ],			
			],
		]
	);
	
	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_account_password_strength_typography',
			'label' 	=> __( 'Strength Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-strength',
		]
	);

	$this->add_group_control(
		Group_Control_Typography::get_type(),
		[
			'name' 		=> 'woo_checkout_billing_account_password_hint_typography',
			'label' 	=> __( 'Hint Typography', 'bew-extras' ),
			'selector' => '{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-hint',
		]
	);

	$this->add_control(
		'woo_checkout_billing_account_password_strength_color',
		[
			'label'     => __( 'Strength Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-strength' => 'color: {{VALUE}}',
			],
		]
	);
	
	$this->add_control(
		'woo_checkout_billing_account_password_hint_color',
		[
			'label'     => __( 'Hint Text Color', 'bew-extras' ),
			'type'      => Controls_Manager::COLOR,
			'selectors' => [
							'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .woocommerce-password-hint' => 'color: {{VALUE}}',
			],
		]
	);

	$this->add_responsive_control(
		'woo_checkout_billing_account_password_padding',
		[
			'label'         => __( 'Padding', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	
	$this->add_responsive_control(
		'woo_checkout_billing_account_password_margin',
		[
			'label'         => __( 'Margin', 'bew-extras' ),
			'type'          => Controls_Manager::DIMENSIONS,
			'size_units'    => [ 'px', '%' ],
			'selectors'     => [
				'{{WRAPPER}} .woocommerce-account-fields.bew-account .create-account .form-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	
	function bew_checkout_fields_name_custom( $section = 'billing' ) {
		
		$get_fields = get_option( '_bew_checkout_fields',  [] );
		
		$options = [];
		foreach ( $get_fields as $_fields ) {
			
			if( count( $_fields ) > 0 ) {
				foreach ( $get_fields[ $section ] as $key => $field ) {
				$options[ $key ] = $field['label'];
				}
			}
			
		}
					
		return $options;
	}
		
	function bew_checkout_fields( $section = 'billing' ) {
		if( !function_exists( 'WC' ) ) return [];

		if ( is_admin() ) {
			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
		}

		$get_fields = WC()->checkout->get_checkout_fields();
		
		//echo var_dump($get_fields );
				
		$fields = [];
		foreach ( $get_fields[ $section ] as $key => $field ) {
			$fields[] = [
				"{$section}_input_label" 		=> $field['label'] ?? null,
				"{$section}_input_name" 		=> $key,
				"{$section}_input_required" 	=> isset( $field['required'] ) ? $field['required'] : false,
				"{$section}_input_type" 		=> isset( $field['type'] ) ? $field['type'] : 'text',
				"{$section}_input_class" 		=> $field['class'] ?? null,
				"{$section}_input_autocomplete" => isset( $field['autocomplete'] ) ? $field['autocomplete'] : '' ,
				"{$section}_input_placeholder"	=> isset( $field['placeholder'] ) ? $field['placeholder'] : '' ,
			];
		}
		
		return $fields;
	}
	
	function bew_login_url( $redirect = '', $force_reauth = false ) {
		$login_url = get_permalink( wc_get_page_id( 'myaccount'  )); 
		
		if ( ! empty( $redirect ) ) {
			$login_url = add_query_arg( 'redirect_to', urlencode( $redirect ), $login_url );
		}
	 
		if ( $force_reauth ) {
			$login_url = add_query_arg( 'reauth', '1', $login_url );
		}

		return apply_filters( 'bew_login_url', $login_url, $redirect, $force_reauth );
	}

	protected function render() {
	if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
	
		$settings = $this->get_settings();		
				
		$checkout_billing_steps   		 = $settings['woo_checkout_billing_steps'];
		$billing_description_show 		 = $settings['woo_checkout_billing_description_show'];
		$billing_description_text 		 = $settings['woo_checkout_billing_description_text'];
		$billing_form_items 	  		 = $settings['woo_checkout_billing_form_items'];
		$billing_title_show 	 	 	 = $settings['woo_checkout_billing_title_show'];
		$billing_title_tag  	  		 = Utils::validate_html_tag( $settings['woo_checkout_billing_title_tag'] );
		$billing_title_text 	  		 = $settings['woo_checkout_billing_title_text'];		
		$bew_account_login   	         = $settings['woo_checkout_billing_bew_account_login'];
		$bew_account_login_type   	     = $settings['woo_checkout_billing_bew_account_login_type'];		
		$bew_account_login_field_layout  = $settings['woo_checkout_billing_bew_account_login_field_layout'];
		$bew_account_create   	         = $settings['woo_checkout_billing_bew_account_create'];
		$bew_account_create_type   	     = $settings['woo_checkout_billing_bew_account_create_type'];
		$bew_account_create_field_layout = $settings['woo_checkout_billing_bew_account_create_field_layout'];
		$field_editor  	          		 = $settings['woo_checkout_billing_field_editor'];	
		$error_required  	      		 = $settings['woo_checkout_billing_error_required_text'];
		$error_validation  	      		 = $settings['woo_checkout_billing_error_validation_text'];
		$error_mail_validation  	     = $settings['woo_checkout_billing_error_mail_validation_text'];
		$account_text  	          		 = $settings['woo_checkout_billing_bew_account_login_text'];
		$account_login_link  	  		 = $settings['woo_checkout_billing_bew_account_login_link'];
		
		$fast_checkout_name              = $settings['woo_checkout_billing_fast_checkout_name'];
		$data_bill_name                  = $settings['woo_checkout_billing_fast_checkout_full_name'];
		$data_bill_first_name            = $settings['woo_checkout_billing_fast_checkout_first_name'];
		
		$intl_phone = '';
		
		//custom acccount class									
		$bew_account_create_class = [ 'custom_class'=>  "label-inside-" . $bew_account_create_field_layout];
		
		// Get checkout object.
		$checkout = WC()->checkout();
		
		// Add class if the product doesnt need shipping
		if (  false === WC()->cart->needs_shipping_address() ) {
			$this->add_render_attribute( '_wrapper', [
				'class' => 'dont-need-shipping-yes'
			] );			
		}
		
		//echo var_dump($billing_form_items);
		//echo var_dump($this->bew_checkout_fields_name());
		//echo var_dump($this->bew_checkout_fields());
		//echo var_dump(WC()->checkout->get_checkout_fields());

		//echo var_dump(get_option( '_bew_checkout_fields', [] ));
		//echo var_dump($billing_form_items);
		
		$get_fields = WC()->checkout->get_checkout_fields('billing');
		
		if( $field_editor  == 'yes' ){
			
			$billing_fields = [];
			foreach( $billing_form_items as $item ) {
	
				$has_placeholder = empty($item['billing_input_placeholder']) ? '' : ' has-placeholder';
			
				$billing_input_class = [];
				if (is_array( $item['billing_input_class'] )){
					$billing_input_class = $item['billing_input_class'];			
				}else {
					$billing_input_class[] = $item['billing_input_class'];	
				}
								
				if($item['billing_input_intl_phone'] == 'yes'){
					
					if( $item['billing_input_label_layout'] == 'yes'){
						$intl_phone 	 = ($item['billing_input_intl_phone'] ?? null) . " is-active";
					} else {
						$intl_phone 	 = ($item['billing_input_intl_phone'] ?? null);	
					}
					
				}
										
				if ( ($item['billing_input_name'] == 'billing_first_name') || ($item['billing_input_name'] == 'billing_last_name') || ($item['billing_input_name'] == 'billing_company') || ($item['billing_input_name'] == 'billing_phone') || ($item['billing_input_name'] == 'billing_email') ){				
					array_push($billing_input_class, "label-inside-" . $item['billing_input_label_layout'] . " label-hide-" . $item['billing_input_label_hide'] . " intl-phone-" . $intl_phone . $has_placeholder  );	
				}else{
					array_push($billing_input_class, "address-field label-inside-" . $item['billing_input_label_layout'] . " label-hide-" . $item['billing_input_label_hide'] . " input-hide-" . $item['billing_input_hide'] . $has_placeholder );
				}
				
				$fkey = $item['billing_input_name'];
				
				if( $item['billing_input_name'] == 'billing_custom_field' ){
					$fkey = 'billing_'.$item['billing_input_field_key_custom'];
				}
				
				if( ($item['billing_input_name'] == 'billing_country') || ($item['billing_input_name'] == 'billing_address_1') ||($item['billing_input_name'] == 'billing_address_2') ||
					($item['billing_input_name'] == 'billing_city') || ($item['billing_input_name'] == 'billing_state') || ($item['billing_input_name'] == 'billing_postcode') ){
					$rfield = $item['billing_input_name'];
					$required = $get_fields[$rfield]['required'];  
				} else {
					$required = $item['billing_input_required'] == 'yes' ? true : false; 
				}
				
				if( $item['billing_input_name'] == 'billing_state' && ($get_fields['billing_state']['country'] == "GB" )   ){
					$label = $get_fields['billing_state']['label'];  
				} else {
					$label = sanitize_text_field( __( $item['billing_input_label'], 'woocommerce' ));
				}
				
				$billing_fields[ sanitize_text_field( $fkey ) ] = 
					[
						'label'			=> $label,
						'type'			=> $item['billing_input_type'],
						'required'		=> $required,
						'class'			=> $billing_input_class,
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
			}
			
			// Send Fields to regenerate										
			if( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				$_bew_checkout_fields = get_option( '_bew_checkout_fields', [] );
				if (is_array( $_bew_checkout_fields)){
					$_bew_checkout_fields['billing'] = $billing_fields;
					update_option( '_bew_checkout_fields', $_bew_checkout_fields );
				} else {
					update_option( '_bew_checkout_fields',  [] );
				}
				update_option( '_bew_checkout_fields_billing', 'bew_fields_billing');
			}
			
		}else{
			 delete_option( '_bew_checkout_fields_billing' );
		}
				
		//echo var_dump($billing_fields);
		//$get_fields = WC()->checkout->get_checkout_fields();
		//echo var_dump($get_fields) ;
		
		$helper = new Helper();
		
		//$order = wc_get_order( 3865);
		//echo var_dump(get_post_meta( $order->get_id(), 'billing_customkeyns', true ));
		//echo '<p><strong>'.__('Shipping Phone').':</strong> ' . '<span style=" display: block; margin: 5px 0 0 0; ">' .get_post_meta( $order->get_id(), 'billing_customkeyns', true ) . '</span>'. '</p>';
				
		?>
		<div class="bew-components-checkout-step bew-checkout-steps-<?php echo $checkout_billing_steps; ?>">
			<?php if( 'yes' == $billing_title_show ): ?>
				<div class="bew-checkout-step-heading">
				<<?php echo esc_attr( $billing_title_tag ); ?> class="bew-checkout-step-title bew-billing-title"><?php echo esc_html( $billing_title_text ); ?></<?php echo esc_attr( $billing_title_tag ) ?>>
				
				</div>
			<?php endif; ?>
			<div class="bew-woo-checkout">bew-woo-checkout</div>
			<div class="bew-cst">bew-cst</div> 
			<div class="bew-checkout-step-container bew-billing express-checkout-name-<?php echo $fast_checkout_name; ?>" data-bill-name= "<?php echo esc_attr_e($data_bill_name, 'bew-extras' ); ?>" data-bill-first-name= "<?php echo esc_attr_e($data_bill_first_name, 'bew-extras' ); ?>" >
				<?php
				if('yes' == $billing_description_show ){
				?>	
					<p class="bew-components-checkout-step__description"><?php echo esc_html( $billing_description_text ); ?></p>
				<?php
				}
				?>
				<?php do_action( 'woocommerce_before_checkout_billing_form', $checkout ); ?>
				<div class="bew-components-checkout-step__content">
					<?php
					if( $field_editor   == 'yes' ){
						
						foreach ( $billing_fields as $key => $field ) {
							$helper->bew_woocommerce_form_field( $key, $field, WC()->checkout->get_value( $key ) );						
						}						
						
					}else{
						
						$fields = WC()->checkout->get_checkout_fields( 'billing' );
                        foreach ( $fields as $key => $field ) {
                            woocommerce_form_field( $key, $field, WC()->checkout->get_value( $key ) );
                        }
						
					}
									
					?>
				</div>
				<?php do_action( 'woocommerce_after_checkout_billing_form', $checkout ); ?>
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
							<span class="account-before"><?php esc_attr_e($account_text, 'bew-extras' ); ?></span>
							<a class="account-link" href="<?php echo esc_url($this->bew_login_url( get_permalink() ) ); ?>" alt="<?php esc_attr_e($account_login_link, 'bew-extras' ); ?>">
							<i aria-hidden="true" class="far fa-user"></i>
								<?php esc_attr_e($account_login_link, 'bew-extras' ); ?>
							</a>
						</div>		
					</div>
				<?php	
				} 
			}
		}

		if($bew_account_create == "yes"){

			if ( (! is_user_logged_in() && $checkout->is_registration_enabled()) || (Elementor\Plugin::instance()->editor->is_edit_mode() && $checkout->is_registration_enabled()) ) {			
					
				if($bew_account_create_type == "checkbox" ) {
				?>			
					<div class="bew-account-fields woocommerce-account-fields bew-account bew-account-type-checkbox bew-checkout-steps-<?php echo $checkout_billing_steps; ?>">
						<div class="bew-checkout-step-container">
							<?php if ( ! $checkout->is_registration_required() ) { ?>

								<p class="form-row form-row-wide create-account">
									<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
										<input class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" id="createaccount" <?php checked( ( true === $checkout->get_value( 'createaccount' ) || ( true === apply_filters( 'woocommerce_create_account_default_checked', false ) ) ), true ); ?> type="checkbox" name="createaccount" value="1" /> <span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
									</label>
								</p>

							<?php }?>

							<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

							<?php if ( $checkout->get_checkout_fields( 'account' ) ) { ?>

								<div class="create-account">
									<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) :
									
										if ($bew_account_create_field_layout == 'yes') {
											$field ['label'] = __( 'Password', 'woocommerce' );	
											?>
											<?php $field = $field +$bew_account_create_class;
										}
										
										$helper->bew_woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
										
									<?php endforeach; ?>
									<div class="clear"></div>
								</div>

							<?php } ?>

							<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
						
						</div>
					</div>
				<?php
				} elseif($bew_account_type == "input" ) {
				?>			
					<div class=" bew-account-fields woocommerce-account-fields bew-account bew-account-type-input bew-checkout-steps-<?php echo $checkout_billing_steps; ?>">
						<?php if ( ! $checkout->is_registration_required() ) { ?>
								
								<div class="create-account create-account-title">
										
									<span><?php esc_html_e( 'Create an account?', 'woocommerce' ); ?></span>
										
								</div>

								<?php do_action( 'woocommerce_before_checkout_registration_form', $checkout ); ?>

								<?php if ( $checkout->get_checkout_fields( 'account' ) ) { ?>

									<div class="create-account">
										<?php foreach ( $checkout->get_checkout_fields( 'account' ) as $key => $field ) :
										
											if ($bew_account_create_field_layout == 'yes') {
												$field ['label'] = __( 'Password', 'woocommerce' );	
												?>
												<?php $field = $field +$bew_account_create_class;
											}
											
											$helper->bew_woocommerce_form_field( $key, $field, $checkout->get_value( $key ) ); ?>
											
										<?php endforeach; ?>
										<div class="clear"></div>
									</div>

								<?php } ?>

							<?php do_action( 'woocommerce_after_checkout_registration_form', $checkout ); ?>
							
						<?php } ?>
					</div>
				<?php
				}
			}
		}

		// Enqueue checkout JS
		wp_localize_script( 'bew-checkout',
			'checkoutBilling',
			array(
				'error_billing_required'   => $error_required,
				'error_billing_validation' => $error_validation,
				'error_billing_mail_validation' => $error_mail_validation,
			) );		

	}

	protected function _content_template() {
		
	}
	
}
