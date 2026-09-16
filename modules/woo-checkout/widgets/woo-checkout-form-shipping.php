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
use WC_Shipping_Zones;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Form_Shipping extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-form-shipping';
	}

	public function get_title() {
		return __( 'Checkout Form Shipping', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-checkout' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general', 'bew-internation-phone-js' ];
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
			'woo_checkout_shipping_title',
			[
				'label' => __( 'Section Title', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			]
		);
				
		$this->add_control(
			'woo_checkout_shipping_steps',
			[
				'label'         => __( 'Checkout Steps', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'On', 'briefcase-extras' ),
				'label_off'     => __( 'Off', 'briefcase-extras' ),
				'return_value'  => 'active',
				'default'       => 'active',
			]
		);

		$this->add_control(
			'woo_checkout_shipping_vertical_line',
			[
				'label'         => __( 'Vertical Line', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				   'label_on'      => __( 'Show', 'bew-extras' ),
				   'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'condition' => [
					   'woo_checkout_shipping_steps' => 'active'
				],
				'prefix_class' => 'steps-vertical-line-',
			]
		);
		
		$this->add_control(
            'woo_checkout_shipping_title_show',
            [
                'label'         => __( 'Title', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );
		
		$this->add_control(
		    'woo_checkout_shipping_title_text',
		    [
		        'label' 		=> __( 'Text', 'woocommerce' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Shipping Address', 'woocommerce' ),
                'condition' 	=> [
                    'woo_checkout_shipping_title_show' => 'yes'
                ],
				'label_block'   => true,
		        'dynamic' 		=> [
		            'active' 	=> true,
		        ]
		    ]
		);

		$this->add_control(
			'woo_checkout_shipping_title_tag',
			[
				'label' 	=> __( 'HTML Tag', 'elementor' ),
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
                    'woo_checkout_shipping_title_show' => 'yes'
                ],
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_description_show',
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
			'woo_checkout_shipping_description_text',
			[
				'label' 		=> __( 'Text', 'briefcase-extras' ),
				'type' 			=> Controls_Manager::TEXTAREA,
				'default' 		=> __( "Enter the physical address where you want us to deliver your order.", 'briefcase-extras' ) ,
				'condition' 	=> [
					'woo_checkout_shipping_description_show' => 'yes'
				],
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				]
			]
		);

		$this->add_control(
            'woo_checkout_shipping_title_alignment',
            [
                'label' 	   => __( 'Alignment', 'elementor' ),
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
                    'woo_checkout_shipping_title_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-shipping-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_content_section',
			[
				'label' => __( 'Shipping Fields', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'heading_woo_checkout_default_checked',
			[
				'label' => __( 'Ship to Different Address', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_control(
			'woo_checkout_default_checked_show',
			[
				'label'	 	=> __( 'Show Checkbox', 'bew-extras' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'label_on' 	=> __( 'Yes', 'elementor' ),
				'label_off' => __( 'No', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
			'woo_checkout_default_checked',
			[
				'label'	 	=> __( 'Default Checked', 'bew-extras' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'label_on' 	=> __( 'Yes', 'elementor' ),
				'label_off' => __( 'No', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
			]
		);
		
		$this->add_control(
            'woo_checkout_shipping_toggle_caption',
            [
                'label' 		=> __( 'Toggle Caption', 'bew-extras' ),
                'type' 			=> Controls_Manager::TEXT,
                'default' 		=> __( 'Ship to a different address?', 'woocommerce' ),
                'separator' 	=> 'after',
				'label_block'   => true,
                'dynamic' 		=> [
                    'active' 	=> true,
                ]
            ]
        );
		
		$this->add_control(
			'heading_woo_checkout_default_checkedb',
			[
				'label' => __( 'Use Address for Billing', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
			]
		);
		
		$this->add_control(
			'woo_checkout_default_checkedb_show',
			[
				'label'	 	=> __( 'Show Checkbox', 'bew-extras' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'label_on' 	=> __( 'Yes', 'elementor' ),
				'label_off' => __( 'No', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);		
		
		$this->add_control(
			'woo_checkout_default_checkedb',
			[
				'label'	 	=> __( 'Default Checked', 'bew-extras' ),
				'type' 		=> Controls_Manager::SWITCHER,
				'label_on' 	=> __( 'Yes', 'elementor' ),
				'label_off' => __( 'No', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
			]
		);
		
		$this->add_control(
            'woo_checkout_shipping_toggle_captionb',
            [
                'label' 		=> __( 'Toggle Caption', 'bew-extras' ),
                'type' 			=> Controls_Manager::TEXT,
                'default' 		=> __( 'Use same address for billing', 'woocommerce' ),
                'separator' 	=> 'after',
				'label_block'   => true,
                'dynamic' 		=> [
                    'active' 	=> true,
                ]
            ]
        );
		
		$this->add_control(
			'woo_checkout_shipping_field_editor',
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
			'woo_checkout_billing_field_edito_notice',
			[
				'raw' => __( 'IMPORTANT: To apply all changes from fields editor, save and reload this template.', 'bew-extras' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition' => [
					'woo_checkout_shipping_field_editor' => 'yes',
				],
			]
		);

		$repeater = new Repeater();

		$repeater->add_control(
			'shipping_input_label', 
			[
				'label' 	=> __( 'Input Label', 'woocommerce' ),
				'type' 		=> Controls_Manager::TEXT,
				'default' 	=> __( 'New Section' , 'woocommerce' ),
				'label_block' 	=> true,
			]
		);
		
		$repeater->add_control(
			'shipping_input_label_hide',
			[
				'label'         => __( 'Hide Label', 'briefcase-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'briefcase-extras' ),
				'label_off'     => __( 'No', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => '',			
			]
		);	
			
		$repeater->add_control(
			'shipping_input_label_layout',
			[
				'label'         => __( 'Floating Label', 'briefcase-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'briefcase-extras' ),
				'label_off'     => __( 'no', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'separator' => 'after',
			]
		);	

		$repeater->add_control(
			'shipping_input_class', 
			[
				'label' 	=> __( 'Class Name', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'form-row-wide',
				'options' 	=> [
					'form-row-first' 	=> 'form-row-first',
					'form-row-last' 	=> 'form-row-last',
					'form-row-wide' 	=> 'form-row-wide',
				],
			]
		);

		$repeater->add_control(
			'shipping_input_name', 
			[
				'label' 		=> __( 'Field Name', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->bew_checkout_fields_name('shipping'),
				'default' => '',
				'label_block' 	=> true,
			]
		);

		$repeater->add_control(
			'shipping_input_field_key_custom', 
			[
				'label' => esc_html__( 'Custom Key', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'customkey' . rand( 111, 999 ), 'bew-extras' ),
				'label_block' => true,
				'description' => __( 'Use an unique custom key name( dont use spaces, accents or special characters' , 'bew-extras' ),
				'condition'=>[
					'shipping_input_name'=>'shipping_custom_field',
				],
			]
		);
	
		$repeater->add_control(
			'shipping_input_type', 
			[
				'label' 	=> __( 'Input Type', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT2,
				'default' 	=> 'text',
				'options' 	=> [
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
					'info-text'			=> __( 'Information Box', 'bew-extras' ),
				],
			]
		);

		$repeater->add_control(
			'shipping_input_info_text', 
			[
				'label' => __( 'Information Box', 'bew-extras' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => __( 'Enter text here' , 'bew-extras' ),
				'label_block' => true,
				'description' => __( 'Enter the infrmation you want to display on the information box', 'bew-extras' ),
				'condition'=>[             
				   'shipping_input_type'=>'info-text',
				],
			]
		);
		
		$repeater->add_control(
			'shipping_input_options', 
			[
				'label' 	=> __( 'Options', 'bew-extras' ),
				'type' 		=> Controls_Manager::TEXTAREA,
				'default' 	=> implode( PHP_EOL, [ __( 'Option 1', 'bew-extras' ), __( 'Option 2', 'bew-extras' ), __( 'Option 3', 'bew-extras' ) ] ),
				'label_block' 	=> true,
				'conditions' 	=> [
					'relation' 	=> 'or',
					'terms' 	=> [
						[
							'name' 		=> 'shipping_input_type',
							'operator' 	=> '==',
							'value' 	=> 'select',
						],
						[
							'name' 		=> 'shipping_input_type',
							'operator' 	=> '==',
							'value' 	=> 'radio',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'shipping_input_options_layout', [
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
							'name' => 'shipping_input_type',
							'operator' => '==',
							'value' => 'select',
						],
						[
							'name' => 'shipping_input_type',
							'operator' => '==',
							'value' => 'radio',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'shipping_input_options_layout_type', 
			[
				'label' 	=> __( 'Options Type', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'radio',
				'options' 	=> [
					'radio' 	=> 'Radio',
					'button' 	=> 'Button',
				],
				'condition'=>[
					'shipping_input_type'=>'radio',
				],
			]
		);
		
		$repeater->add_control(
			'shipping_input_conditional',
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
			'shipping_input_connector',
			[
				'label'         => __( 'Connect to shipping methods', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'bew-extras' ),
				'label_off'     => __( 'no', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'separator' => 'before',
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'shipping_input_type',
							'operator' => '==',
							'value' => 'select',
						],
						[
							'name' => 'shipping_input_type',
							'operator' => '==',
							'value' => 'radio',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'shipping_input_connector_first_option', 
			[
				'label' => __( 'First Option', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->bew_checkout_shipping_methods(),
				'label_block' => true,
				'condition'=>[
					'shipping_input_connector'=>'yes',
				],
				'description' => __( 'Connect the first option from radio options with a shipping method' , 'bew-extras' ),
			]
		);

		$repeater->add_control(
			'shipping_input_connector_second_option', 
			[
				'label' => __( 'Second Option', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->bew_checkout_shipping_methods(),
				'label_block' => true,
				 'condition'=>[
					'shipping_input_connector'=>'yes',
				],
				'description' => __( 'Connect the second option from radio options with a shipping method' , 'bew-extras' ),
			]
		);

		$repeater->add_control(
			'shipping_input_connector_third_option', 
			[
				'label' => __( 'Third Option', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->bew_checkout_shipping_methods(),
				'label_block' => true,
				 'condition'=>[
					'shipping_input_connector'=>'yes',
				],
				'description' => __( 'Connect the third option from radio options with a shipping method' , 'bew-extras' ),
			]
		);
		
		$repeater->add_control(
			'shipping_input_superior_field', 
			[
				'label' => __( 'Superior Field', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->bew_checkout_fields_name_custom('shipping'),
				'label_block' => true,
				 'condition'=>[
					'shipping_input_conditional'=>'yes',
				],
				'description' => __( 'If you need to choose a recent created custom field, save an reload the page, to get the custom field on the list' , 'bew-extras' ),
			]
		);
		
		$repeater->add_control(
			'shipping_input_superior_field_option', 
			[
				'label' => __( 'Superior Field Options', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Enter option' , 'bew-extras' ),
				'label_block' => true,
				'description' => __( 'Enter the option from superior field selected for your conditional display', 'bew-extras' ),
				'condition'=>[             
				   'shipping_input_superior_field!'=>'',
				],
			]
		);
		
		$repeater->add_control(
			'shipping_input_placeholder', 
			[
				'label' 		=> __( 'Placeholder', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Placeholder' , 'bew-extras' ),
				'label_block' 	=> true,
			]
		);

		$repeater->add_control(
			'shipping_input_autocomplete', 
			[
				'label' 		=> __( 'Autocomplete Value', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Given value' , 'bew-extras' ),
				'label_block' 	=> true,
			]
		);

		$repeater->add_control(
			'shipping_input_required',
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
			'shipping_input_intl_phone',
			[
				'label'         => __( 'Intl Phone', 'briefcase-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'briefcase-extras' ),
				'label_off'     => __( 'no', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition' 	=> [
					'shipping_input_name' => 'shipping_phone',
				],
			]
		);
		
		$repeater->add_control(
			'shipping_input_hide',
			[
				'label'         => __( 'Hide Field', 'briefcase-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'briefcase-extras' ),
				'label_off'     => __( 'no', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition' 	=> [
					'shipping_input_name' => ['shipping_country','shipping_state'],
				],
			]
		);

		$repeater->add_control(
			'shipping_input_show_email',
			[
				'label'         => esc_html__( 'Show in Email', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
				'label_off'     => esc_html__( 'No', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition'=>[
					'shipping_input_name' => 'shipping_custom_field',
				],
			]
		);

		$repeater->add_control(
			'shipping_input_show_order',
			[
				'label'         => esc_html__( 'Show in Order Detail Page', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
				'label_off'     => esc_html__( 'No', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition'=>[
					'shipping_input_name' => 'shipping_custom_field',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_form_items',
			[
				'label' 		=> __( '', 'bew-extras' ),
				'type' 			=> Controls_Manager::REPEATER,
				'fields' 		=> $repeater->get_controls(),
				'default' 		=> $this->bew_checkout_fields( 'shipping' ),
				'title_field' 	=> '{{{ shipping_input_label }}}',
				'condition'=>[
					'woo_checkout_shipping_field_editor'=>'yes',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'woo_checkout_shipping_form_review_section',
			[
				'label' => __( 'Multistep Review', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_form_review',
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
			'woo_checkout_shipping_contact_show',
			[
				'label'         => __( 'Contact', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-shipping-contact-',
				'condition' => [				  
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_contact', [
				'label' => __( 'Contact text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Contact' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_contact_show' => 'yes',
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_ship_show',
			[
				'label'         => __( 'Ship To', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-shipping-ship-',
				'condition' => [				  
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_ship', [
				'label' => __( 'Ship To text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Ship To' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_ship_show' => 'yes',
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_bill_show',
			[
				'label'         => __( 'Bill To', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'briefcase-extras' ),
				'label_off'     => __( 'Hide', 'briefcase-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'prefix_class' => 'review-shipping-bill-',
				'condition' => [				  
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_bill', [
				'label' => __( 'Bill To text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Bill To' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_bill_show' => 'yes',
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);		

		$this->add_control(
			'woo_checkout_shipping_change', [
				'label' => __( 'Change text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Change' , 'bew-extras' ),
				'placeholder' => __( 'Custom text here' , 'bew-extras' ),
				'label_block' => true,	
				'condition' => [
				   'woo_checkout_shipping_form_review' => 'yes'
				],
			]
		);			
				
		$this->end_controls_section();		

		$this->start_controls_section(
			'woo_checkout_shipping_error_section',
			[
				'label' => __( 'Error', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_error_required_text',
			[
				'label' 		=> __( ' Required Error Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'This information is required.', 'bew-extras' ),					
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				]
			]
		);

		$this->add_control(
			'woo_checkout_shipping_error_validation_text',
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
				
		$this->end_controls_section();
	
		//Section general style
		$this->start_controls_section(
			'woo_checkout_shipping_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_general_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-form-shipping .bew-components-checkout-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_general_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-form-shipping .bew-components-checkout-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();	

		//Section title style
		$this->start_controls_section(
			'woo_checkout_shipping_title_style',
			[
				'label' 	=> __( 'Title', 'bew-extras' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
                'condition' => [
                    'woo_checkout_shipping_title_show' => 'yes'
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_title_typography',
				'label' 	=> __( 'Title Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-shipping-title',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_title_typography',
				'label' 	=> __( 'Description Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-checkout-step__description',
			]
		);

		$this->end_controls_section();

		/**
		 * Input Label Color
		 */
		$this->start_controls_section(
			'woo_checkout_shipping_style',
			[
				'label' => __( 'Labels', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_label_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content label',
			]
		);


        $this->add_control(
			'woo_checkout_shipping_label_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content label' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
        	'woo_checkout_shipping_label_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' 	=> Controls_Manager::DIMENSIONS,
        		'size_units' 	=> [ 'px', '%', 'em' ],
        		'selectors' 	=> [
        			'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_control(
			'woo_checkout_shipping_label_inside_translate',
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
					'{{WRAPPER}} .bew-checkout-step-container.bew-shipping .form-row.label-inside-yes label' => 'transform: translateY({{SIZE}}{{UNIT}});',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_label_line_hight',
			[
				'label' 		=> __( 'Line Height', 'bew-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' 	=> 20,
						'max' 	=> 100,
						'step' 	=> 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' 	=> [
					'unit' 	=> 'px',
					'size' 	=> 25,
				],
				'selectors' => [
					'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content label' => 'line-height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_shipping_input_style',
			[
				'label' => __( 'Input Fields', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_input_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input, 
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select,
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection,								
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content option,
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea',
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_color',
			[
				'label'     => __( 'Input Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input, 
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection .select2-selection__rendered,	
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content option,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_placeholder_color',
			[
				'label'     => __( 'Placeholder Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input::-webkit-input-placeholder, 
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content option::-webkit-input-placeholder,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea::-webkit-input-placeholder ' => 'color: {{VALUE}} !important;',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_background_color',
			[
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input, 
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection,	
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content option,
								 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_input_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input, 
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select,
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection,
								{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea',
			]
		);

        $this->add_control(
			'woo_checkout_shipping_input_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input, 
					 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select,
					 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection,
					 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_border_color_focus',
			[
				'label'     => __( 'Focus Border Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-shipping input:focus, 
								 {{WRAPPER}} .bew-shipping select:focus,
								 {{WRAPPER}} .bew-shipping .select2-selection:focus,
								 {{WRAPPER}} .bew-shipping option:focus,
								 {{WRAPPER}} .bew-shipping textarea:focus' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_input_border_width_focus',
			[
				'label' => __( 'Focus Border Width', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-shipping input:focus, 
					 {{WRAPPER}} .bew-shipping select:focus,
					 {{WRAPPER}} .bew-shipping .select2-selection:focus,
					 {{WRAPPER}} .bew-shipping textarea:focus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-shipping .bew-components-checkout-step__content input, 
					 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content select,
					 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content .select2-selection,
					 {{WRAPPER}} .bew-shipping .bew-components-checkout-step__content textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_input_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-shipping .form-row' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',					
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_wide_width',
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
					'{{WRAPPER}} .bew-shipping .form-row.form-row-wide' => 'width: {{SIZE}}{{UNIT}};',
				],			
			]
		);
			
		$this->add_control(
			'woo_checkout_shipping_input_first_width',
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
					'{{WRAPPER}} .bew-shipping .form-row.form-row-first' => 'width: {{SIZE}}{{UNIT}};',
				],			
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_last_width',
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
					'{{WRAPPER}} .bew-shipping .form-row.form-row-last' => 'width: {{SIZE}}{{UNIT}};',
				],			
			]
		);

		$this->add_control(
			'woo_checkout_shipping_input_height',
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
					'{{WRAPPER}} .bew-shipping input, 
					 {{WRAPPER}} .bew-shipping select,
					 {{WRAPPER}} .bew-shipping .select2-container,
					 {{WRAPPER}} .bew-shipping .select2-selection,
					 {{WRAPPER}} .bew-shipping textarea' => 'height: {{SIZE}}{{UNIT}};',
				],			
			]
		);
		
		$this->end_controls_section();	

		//Checkbox		
		$this->start_controls_section(
			'woo_checkout_shipping_toggle',
			[
				'label' => __( 'Checkbox', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_toggle_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-checkout-step .shipping-checkbox-area-b .shipping-checkbox-input-b[type=checkbox]+.shipping-checkbox-caption, 
				                {{WRAPPER}} .bew-components-checkout-step .shipping-checkbox-area .shipping-checkbox-input[type=checkbox]+.shipping-checkbox-caption',
			]
		);

		$this->add_control(
			'woo_checkout_shipping_toggle_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shipping-checkbox-caption' => 'color: {{VALUE}} !important',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_toggle_size',
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
					'{{WRAPPER}} .shipping-checkbox-area .shipping-checkbox-input[type=checkbox]:checked:before , {{WRAPPER}} .bew-shipping .shipping-checkbox-input-b[type=checkbox]:checked:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_checkbox_color',
			[
				'label'     => __( 'Checkbox Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .shipping-checkbox-area .shipping-checkbox-input[type=checkbox]:checked:before , {{WRAPPER}} .bew-shipping .shipping-checkbox-input-b[type=checkbox]:checked:before' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_checkbox_background_color',
			[
				'label'     => __( 'Checkbox Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .shipping-checkbox-area .shipping-checkbox-input[type=checkbox]:checked , {{WRAPPER}} .bew-shipping .shipping-checkbox-input-b[type=checkbox]:checked' => 'background-color: {{VALUE}} !important',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_checkbox_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-components-checkout-step .shipping-checkbox-area-b .shipping-checkbox-input-b[type=checkbox] , {{WRAPPER}} .bew-components-checkout-step .shipping-checkbox-area .shipping-checkbox-input[type=checkbox]',
			]
		);

        $this->add_control(
			'woo_checkout_shipping_checkbox_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shipping-checkbox-area .shipping-checkbox-input[type=checkbox] , {{WRAPPER}} .bew-shipping .shipping-checkbox-input-b[type=checkbox]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_checkbox_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .shipping-checkbox-area .shipping-checkbox-input[type=checkbox] , {{WRAPPER}} .bew-shipping .shipping-checkbox-input-b[type=checkbox]:checked' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		
		$this->end_controls_section();
		
		//Radio	
		$this->start_controls_section(
			'woo_checkout_shipping_radio',
			[
				'label' => __( 'Radio', 'bew-extras' ),
				'tab' 	=> Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_radio_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-checkout-step .form-row .bew-input-radio label',
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_text_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row .bew-input-radio label' => 'color: {{VALUE}} !important',
				],
			]
		);
		
		$this->start_controls_tabs(
			'woo_checkout_shipping_radio_separator',
			[
				'separator' => 'before'
			]
		);

		$this->start_controls_tab(
			'woo_checkout_shipping_radio_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_normal_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:before' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_bg_shipping_radio_normal_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_shipping_radio_checked',
			[
				'label'     => __( 'Checked', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_radio_checked_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_radio_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:before',
			]
		);

        $this->add_control(
			'woo_checkout_shipping_radio_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_radio_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_radio_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-checkout-step .form-row:not(.option-type-button) .bew-input-radio label:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_heading',
			[
				'label' => __( 'Button Type', 'bew-extras' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);

		$this->start_controls_tabs( 'woo_checkout_shipping_radio_button_tabs' );

		$this->start_controls_tab(
			'woo_checkout_shipping_radio_button_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_icon_color',
			[
				'label' => __( 'Icon Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_shipping_radio_button_checked',
			[
				'label'     => __( 'Checked', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_shipping_radio_button_color_checked',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio input[type=radio]:checked + label' => 'color: {{VALUE}}',
				],
			]
		);


		$this->add_control(
			'woo_checkout_shipping_radio_button_color_bg_checked',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio input[type=radio]:checked + label' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_border_color_checked',
			[
				'label' => __( 'Border Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'woo_checkout_shipping_radio_button_border_border!' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio input[type=radio]:checked + label' => 'border-color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_icon_color_checked',
			[
				'label' => __( 'Icon Color', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio input[type=radio]:checked + label:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_shipping_radio_button_icon_bg_color_checked',
			[
				'label' => __( 'Icon Background', 'briefcase-elementor-widgets' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio input[type=radio]:checked + label:before' => 'background: {{VALUE}};',
				],
				
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();
		
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_radio_button_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label',
			]
		);

        $this->add_control(
			'woo_checkout_shipping_radio_border_button_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_radio_button_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_shipping_radio_button_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-checkout-step .form-row.option-type-button .bew-input-radio label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$options[ 'shipping_phone' ] = 'Phone';						
		$options[ 'shipping_custom_field'] = 'Custom';
		
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
	
	function bew_checkout_shipping_methods() {
		if ( is_admin() ) {
			WC()->session = new \WC_Session_Handler();
			WC()->session->init();
		}
		
		$available_methods = WC()->shipping()->get_shipping_methods();
		$options = [];
			
		foreach ( $available_methods as $key => $method  ) {			
			$options[ sanitize_title($key) ] = esc_attr( $method->get_method_title() );
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

		$fields = [];
		foreach ( $get_fields[ $section ] as $key => $field ) {
			$fields[] = [
				"{$section}_input_label" 		=> $field['label'],
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

	function bew_review_form_block($name, $fill, $target, $content = '') {
		$settings = $this->get_settings_for_display();
		
		$shipping_form_change_text   = $settings['woo_checkout_shipping_change'];
		
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
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
		$settings = $this->get_settings_for_display();
		
		//echo var_dump($this->bew_checkout_fields_name( 'shipping' ));
		//echo var_dump($this->bew_checkout_fields( 'shipping' ));
		
		$checkout_shipping_steps   = $settings['woo_checkout_shipping_steps'];
		$shipping_description_show = $settings['woo_checkout_shipping_description_show'];
		$shipping_description_text = $settings['woo_checkout_shipping_description_text'];		
		$shipping_form_items       = $settings['woo_checkout_shipping_form_items'];		
		$shipping_title_show       = $settings['woo_checkout_shipping_title_show'];
		$payment_title_tag	 	   = Utils::validate_html_tag( $settings['woo_checkout_shipping_title_tag'] );
		$shipping_title_text       = $settings['woo_checkout_shipping_title_text'];
		$shipping_toggle_caption   = $settings['woo_checkout_shipping_toggle_caption'];		
		$shipping_toggle_captionb  = $settings['woo_checkout_shipping_toggle_captionb'];
		$field_editor  	           = $settings['woo_checkout_shipping_field_editor'];	
		$error_required  	       = $settings['woo_checkout_shipping_error_required_text'];
		$error_validation  	       = $settings['woo_checkout_shipping_error_validation_text'];
		
		$shipping_form_review        = $settings['woo_checkout_shipping_form_review']; 
		$shipping_form_contact_text  = $settings['woo_checkout_shipping_contact'];
		$shipping_form_ship_text  	 = $settings['woo_checkout_shipping_ship'];
		$shipping_form_bill_text  	 = $settings['woo_checkout_shipping_bill'];	
		
		$required  = "";
		
		$fopt = "";
		$sopt = "";
		$topt = "";
		$store = "";
		$delivery = "";
		$intl_phone = '';
		$siconnector = '';
				
		if ( ( true === WC()->cart->needs_shipping_address() ) || Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			
			$default_checked_show      = $settings['woo_checkout_default_checked_show'];			
			$default_checked           = $settings['woo_checkout_default_checked'];			
			$default_checkedb          = $settings['woo_checkout_default_checkedb'];
			$default_checkedb_show     = $settings['woo_checkout_default_checkedb_show'];
			
		}else{
			$default_checked_show      = '';
			$default_checked           = '';			
			$default_checkedb          = '';
			$default_checkedb_show     = '';
		}
		
		$checked = $default_checked == 'yes' ? 'checked' : '';
		$checkedb = $default_checkedb == 'yes' ? 'checked' : '';
		
		//Save shipping first option, ship_to_different_address checked
		update_option( '_bew_ship_to_different_address', $default_checked );	

		// Get checkout object.
		$checkout = WC()->checkout();
		
		// Add class if the product doesnt need shipping
		if (  false === WC()->cart->needs_shipping_address() ) {
			$this->add_render_attribute( '_wrapper', [
				'class' => 'dont-need-shipping-yes'
			] );			
		}

		//echo var_dump($shipping_form_items);
		//echo var_dump($this->bew_checkout_fields_name());
		//echo var_dump($this->bew_checkout_fields());
		//echo var_dump(WC()->checkout->get_checkout_fields());

		//echo var_dump(get_option( '_bew_checkout_fields', [] ));
		//echo var_dump($shipping_form_items);		
		
		//echo var_dump($get_fields['shipping_state']['required']) ;
		
		
		$get_fields = WC()->checkout->get_checkout_fields('shipping');
		//echo var_dump($get_fields['shipping_state']) ;
		
		if( $field_editor  == 'yes' ){
					
			$shipping_fields = [];
			foreach( $shipping_form_items as $item ) {
			
				$has_placeholder = empty($item['shipping_input_placeholder']) ? '' : ' has-placeholder';
				
				$shipping_input_class = [];
				if (is_array( $item['shipping_input_class'] )){
					$shipping_input_class = $item['shipping_input_class'];			
				}else {	
					$shipping_input_class[] = $item['shipping_input_class'];
				}
				
				if($item['shipping_input_intl_phone'] == 'yes'){
					
					if( $item['shipping_input_label_layout'] == 'yes'){
						$intl_phone 	 = ($item['shipping_input_intl_phone'] ?? null) . " is-active";
					} else {
						$intl_phone 	 = ($item['shipping_input_intl_phone'] ?? null);	
					}
					
				}	
				
				if ( ($item['shipping_input_name'] == 'shipping_first_name') || ($item['shipping_input_name'] == 'shipping_last_name') || ($item['shipping_input_name'] == 'shipping_company') || ($item['shipping_input_name'] == 'shipping_phone') ){	
					array_push($shipping_input_class, "label-inside-" . $item['shipping_input_label_layout'] . " label-hide-" . $item['shipping_input_label_hide'] . " intl-phone-" . $intl_phone . $has_placeholder );
				}else{ 
					array_push($shipping_input_class, "address-field label-inside-" . $item['shipping_input_label_layout'] . " label-hide-" . $item['shipping_input_label_hide'] . " input-hide-" . $item['shipping_input_hide'] . $has_placeholder);
				}

				$fkey = $item['shipping_input_name'];
				
				if( $item['shipping_input_name'] == 'shipping_custom_field' ){
					$fkey = 'shipping_'.$item['shipping_input_field_key_custom'];
				}
				
				if( ($item['shipping_input_name'] == 'shipping_country') || ($item['shipping_input_name'] == 'shipping_address_1') ||($item['shipping_input_name'] == 'shipping_address_2') ||
					($item['shipping_input_name'] == 'shipping_city') || ($item['shipping_input_name'] == 'shipping_state') || ($item['shipping_input_name'] == 'shipping_postcode') ){
					$rfield = $item['shipping_input_name'];
					$required = $get_fields[$rfield]['required'];  
				} else {
					$required = $item['shipping_input_required'] == 'yes' ? true : false; 
				}
				
				if( $item['shipping_input_name'] == 'shipping_state' && ($get_fields['shipping_state']['country'] == "GB" )   ){
					$label = $get_fields['shipping_state']['label'];  
				} else {
					$label = sanitize_text_field( __( $item['shipping_input_label'], 'woocommerce' ));
				}
				
				//Connect with shipping method option
				if ( !empty($item['shipping_input_connector']) ) {
					$siconnector = $item['shipping_input_connector'];
				}
				
				if ( !empty($item['shipping_input_connector_first_option']) ) {
					$fopt = $item['shipping_input_connector_first_option'];
				}
				if ( !empty($item['shipping_input_connector_second_option']) ) {
					$sopt = $item['shipping_input_connector_second_option'];
				}
				if ( !empty($item['shipping_input_connector_third_option']) ) {
					$topt = $item['shipping_input_connector_third_option'];
				}
							
				$shipping_fields[ sanitize_text_field( $fkey ) ] = 
					[
						'label'			=> $label,
						'type'			=> $item['shipping_input_type'],
						'required'		=> $required,
						'class'			=> $shipping_input_class,
						'autocomplete'	=> sanitize_text_field( $item['shipping_input_autocomplete'] ), 
						'placeholder'	=> sanitize_text_field( $item['shipping_input_placeholder'] ), 
						'priority'		=> 10,
					];
					
					if( $item['shipping_input_name'] == 'shipping_state' ){
						$shipping_fields[$fkey]['country_field'] 	= $get_fields['shipping_state']['country_field'] ?? null;
						$shipping_fields[$fkey]['country'] 		 	= $get_fields['shipping_state']['country'] ?? null;
					}
					
					if( $item['shipping_input_conditional'] == 'yes'){
						$shipping_fields[$fkey]['conditional']    		 = $item['shipping_input_conditional'] ?? null;
						$shipping_fields[$fkey]['superior_field'] 		 = sanitize_title($item['shipping_input_superior_field'] ?? ""); 
						$shipping_fields[$fkey]['superior_field_option'] = sanitize_title($item['shipping_input_superior_field_option'] ?? "");
					}
					
					if( $item['shipping_input_intl_phone'] == 'yes'){
						$shipping_fields[$fkey]['intl_phone']    		 = $item['shipping_input_intl_phone'] ?? null;
					}
					
					if( ($item['shipping_input_type'] == 'select' )  || ($item['shipping_input_type'] == 'radio') ){
						
							if ( isset( $item['shipping_input_options'] ) ) {
								//echo var_dump($item['shipping_input_options']);
								$options = explode( "\n", $item['shipping_input_options'] );
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
							$shipping_fields[$fkey]['option_layout'] 		= $item['shipping_input_options_layout']; 
							$shipping_fields[$fkey]['option_type'] 		    = $item['shipping_input_options_layout_type']; 
							$shipping_fields[$fkey]['options']        		= isset( $new_options ) ? $new_options : '';
							$shipping_fields[$fkey]['default']        		= $default;	
							
					}
					
					if( $item['shipping_input_name'] == 'shipping_custom_field' ){

							if ( isset( $item['shipping_input_options'] ) ) {
								//echo var_dump($item['billing_input_options']);
								$options = explode( "\n", $item['shipping_input_options'] );
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
							
							//echo var_dump($new_options);
						
							$shipping_fields[$fkey]['custom']         		 = true;
							$shipping_fields[$fkey]['type']           		 = $item['shipping_input_type'];
							$shipping_fields[$fkey]['show_in_email']  		 = $item['shipping_input_show_email'] == 'yes' ? true : '';
							$shipping_fields[$fkey]['show_in_order']  		 = $item['shipping_input_show_order'] == 'yes' ? true : '';
							$shipping_fields[$fkey]['conditional']    		 = $item['shipping_input_conditional'] ?? null;
							$shipping_fields[$fkey]['superior_field'] 		 = sanitize_title($item['shipping_input_superior_field'] ?? ""); 
							$shipping_fields[$fkey]['superior_field_option'] = sanitize_title($item['shipping_input_superior_field_option'] ?? "");
							$shipping_fields[$fkey]['info_text'] 			 = $item['shipping_input_info_text'] ?? "";
							
							if( ($item['shipping_input_type'] == 'select' )  || ($item['shipping_input_type'] == 'radio') ){
								
								$shipping_fields[$fkey]['option_layout'] 		= $item['shipping_input_options_layout'];
								$shipping_fields[$fkey]['option_type'] 		    = $item['shipping_input_options_layout_type']; 
								$shipping_fields[$fkey]['options']        		= isset( $new_options ) ? $new_options : '';
								$shipping_fields[$fkey]['default']        		= $default;
														
							}
							
					}
			
			}
					
			// Send Fields to regenerate				
			if( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				$_bew_checkout_fields = get_option( '_bew_checkout_fields', [] );
				if (is_array( $_bew_checkout_fields)){
					$_bew_checkout_fields['shipping'] = $shipping_fields;
					update_option( '_bew_checkout_fields', $_bew_checkout_fields );
				} else {
					update_option( '_bew_checkout_fields',  [] );
				}
				update_option( '_bew_checkout_fields_shipping', 'bew_fields_shipping');
			}
		
		}else{
			 delete_option( '_bew_checkout_fields_shipping' );
		}

		//echo var_dump($shipping_fields);
		//$get_fields = WC()->checkout->get_checkout_fields();
		//echo var_dump($get_fields) ;
		
		$helper = new Helper();
		
		?>
		<div id="bew-shipping">
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
							<div class="bew-formReview-info bew-formReview-ship ">
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
					
						<?php if( 'yes' == $shipping_title_show ): ?>
							<div class="bew-checkout-step-heading">
							<<?php echo esc_attr( $payment_title_tag ); ?> class="bew-checkout-step-title bew-shipping-title"><?php echo esc_html( $shipping_title_text ); ?></<?php echo esc_attr( $payment_title_tag ); ?>>
							
							</div>
						<?php endif; ?>
						<div class="bew-woo-checkout">bew-woo-checkout</div>
						<div class="bew-cst">bew-cst</div> 
					
					<p id="shipping-checkbox" class="shipping-checkbox-area <?php echo "ship-tda-" . $default_checked_show ?><?php echo " ship-tda-checked-" . $default_checked ?>">
						<label class="shipping-checkbox-label">
							<input id="shipping-checkbox-input" class="shipping-checkbox-input" type="checkbox" name="ship_to_different_address" value="1" <?php echo $checked; ?>> <span class="shipping-checkbox-caption"><?php echo esc_html( $shipping_toggle_caption ); ?></span>
						</label>
					</p>
					<h3 id="ship-to-different-address">
						<label class="woocommerce-form__label woocommerce-form__label-for-checkbox checkbox">
							<input id="ship-to-different-address-checkbox" class="woocommerce-form__input woocommerce-form__input-checkbox input-checkbox" <?php checked( apply_filters( 'woocommerce_ship_to_different_address_checked', 'shipping' === get_option( 'woocommerce_ship_to_destination' ) ? 1 : 0 ), 1 ); ?> type="checkbox" name="ship_to_different_address" value="1" /> <span><?php esc_html_e( 'Ship to a different address?', 'woocommerce' ); ?></span>
						</label>
					</h3>
					
					<div class="bew-checkout-step-container bew-shipping">
						<?php
						if('yes' == $shipping_description_show ){
						?>				
							<p class="bew-components-checkout-step__description"><?php echo esc_html( $shipping_description_text ); ?></p>
						<?php
						}
						?>
						<?php do_action( 'woocommerce_before_checkout_shipping_form', $checkout ); ?>
						<div class="bew-components-checkout-step__content">
						
						<?php
						if( $field_editor   == 'yes' ){
							
							foreach ( $shipping_fields as $key => $field ) {
								$helper->bew_woocommerce_form_field( $key, $field, WC()->checkout->get_value( $key ) );
							}
						}else{
							
							$fields = WC()->checkout->get_checkout_fields( 'shipping' );
							foreach ( $fields as $key => $field ) {
								woocommerce_form_field( $key, $field, WC()->checkout->get_value( $key ) );
							}
							
						}
						
					?>
						</div>
						<?php do_action( 'woocommerce_after_checkout_shipping_form', $checkout ); ?>
						
						<?php if( 'yes' == $default_checkedb_show ): ?>
						<p id="shipping-checkbox-b" class="shipping-checkbox-area-b">
							<label class="shipping-checkbox-label">
								<input id="shipping-checkbox-input-b" class="shipping-checkbox-input-b" type="checkbox" name="use-address-for-billing" value="1" <?php echo $checkedb; ?>> <span class="shipping-checkbox-caption"><?php echo esc_html( $shipping_toggle_captionb ); ?></span>
							</label>
						</p>
						<?php endif; ?>
						
					</div>
				</div>
			<?php 
		    } 
			?>
		
		</div>	
	<?php 
		
		$options = get_option( '_bew_shipping_options');		
		if( !empty($options) ){
			foreach ($options as $key => $val) {
				 $varname = $key;
				 $$varname = $key;			 
			}
		}
		// Enqueue checkout JS
		wp_localize_script( 'bew-checkout',
			'checkoutShipping',
			array(
				'default_checked'  => $default_checked,
				'default_checkedb' => $default_checkedb,
				'error_shipping_required'   => $error_required,
				'error_shipping_validation' => $error_validation,
				'bsconnect'    => $siconnector,
				'store'    	   => $store,
				'delivery' 	   => $delivery,
				'fopt' 		   => $fopt,
				'sopt'         => $sopt,
				'topt'         => $topt,
			) );

	}

	protected function _content_template() {
		
	}
	
}

