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

class Woo_Checkout_Form_Additional extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-form-additional';
	}

	public function get_title() {
		return __( 'Checkout Form Additional', 'bew-extras' );
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
			'woo_checkout_form_additional_title',
			[
				'label' => __( 'Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_steps',
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
			'woo_checkout_form_additional_vertical_line',
			[
				'label'         => __( 'Vertical Line', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				   'label_on'      => __( 'Show', 'bew-extras' ),
				   'label_off'     => __( 'Hide', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => 'yes',
				'condition' => [
				   'woo_checkout_form_additional_steps' => 'active'
				],
				'prefix_class' => 'steps-vertical-line-',
			]
		);	
		$this->add_control(
            'woo_checkout_form_additional_title_show',
            [
                'label'         => __( 'Show/Hide Title', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );
		//order_button_text
		$this->add_control(
		    'woo_checkout_form_additional_title_text',
		    [
		        'label' 		=> __( 'Text', 'woocommerce' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Additional information', 'woocommerce' ) ,
                'condition' => [
                    'woo_checkout_form_additional_title_show' => 'yes'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
			'woo_checkout_form_additional_title_tag',
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
                    'woo_checkout_form_additional_title_show' => 'yes'
                ],
			]
		);
		
		$this->add_control(
			'woo_checkout_form_additional_description_show',
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
			'woo_checkout_form_additional_description_text',
			[
				'label' 		=> __( 'Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( "Enter additional information for your order.", 'bew-extras' ) ,
				'condition' 	=> [
					'woo_checkout_form_additional_description_show' => 'yes'
				],
				'label_block'   => true,
				'dynamic' 		=> [
					'active' 	=> true,
				]
			]
		);
		$this->add_control(
            'woo_checkout_form_additional_title_alignment',
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
                    'woo_checkout_form_additional_title_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-pm-title' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_form_additional_content_section',
			[
				'label' => __( 'Additional Fields', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_additional_content_field_editor',
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
			'woo_checkout_additional_content_field_editor_notice',
			[
				'raw' => __( 'IMPORTANT: To apply all changes from fields editor, save and reload this template.', 'bew-extras' ),
				'type' => Controls_Manager::RAW_HTML,
				'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				'condition' => [
					'woo_checkout_additional_content_field_editor' => 'yes',
				],
			]
		);	

		$repeater = new Repeater();

		$repeater->add_control(
			'order_input_layout', [
				'label' => __( 'Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'layout-checkbox',
				'options' => [
					'layout-checkbox' => 'Checkbox',
					'layout-input'    => 'Input',
				],
				'condition'=>[             
				   'order_input_name' => 'order_comments',
				],
			]
		);
		
		$repeater->add_control(
			'order_input_label', [
				'label' => __( 'Input Label', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'New Section' , 'bew-extras' ),
				'label_block' => true,
				'separator' => 'after',
			]
		);

		$repeater->add_control(
			'order_input_label_hide',
			[
				'label'         => __( 'Hide Label', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Yes', 'bew-extras' ),
				'label_off'     => __( 'No', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);

		$repeater->add_control(
			'order_input_label_layout',
			[
				'label'         => __( 'Floating Label', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'bew-extras' ),
				'label_off'     => __( 'no', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);

		$repeater->add_control(
			'order_input_class', [
				'label' => __( 'Class Name', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'form-row-wide',
				'options' => [
					'form-row-first' => 'form-row-first',
					'form-row-last' => 'form-row-last',
					'form-row-wide' => 'form-row-wide',
				],
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);

		$repeater->add_control(
			'order_input_name', [
				'label' => __( 'Field Name', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'options' => $this->bew_checkout_fields_name('order'),
				'default' => 'order_custom_field',
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'order_input_field_key_custom', 
			[
				'label' => esc_html__( 'Custom Key', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'customkey' . rand( 111, 999 ), 'bew-extras' ),
				'label_block' => true,
				'description' => __( 'Use an unique custom key name( dont use spaces, accents or special characters' , 'bew-extras' ),
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);
		
		$repeater->add_control(
			'order_input_type', [
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
			'order_input_options', [
				'label' => __( 'Options', 'bew-extras' ),
				'type' => Controls_Manager::TEXTAREA,
				'default' => implode( PHP_EOL, [ __( 'Option 1', 'bew-extras' ), __( 'Option 2', 'bew-extras' ), __( 'Option 3', 'bew-extras' ) ] ),
				'label_block' => true,
				'conditions' => [
					'relation' => 'or',
					'terms' => [
						[
							'name' => 'order_input_type',
							'operator' => '==',
							'value' => 'select',
						],
						[
							'name' => 'order_input_type',
							'operator' => '==',
							'value' => 'radio',
						],
					],
				],
				'description' => __( 'Enter the options one per line' , 'bew-extras' ),
			]
		);

		$repeater->add_control(
			'order_input_options_layout', [
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
							'name' => 'order_input_type',
							'operator' => '==',
							'value' => 'select',
						],
						[
							'name' => 'order_input_type',
							'operator' => '==',
							'value' => 'radio',
						],
					],
				],
			]
		);

		$repeater->add_control(
			'order_input_conditional',
			[
				'label'         => __( 'Conditional Field', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'bew-extras' ),
				'label_off'     => __( 'no', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);

		$repeater->add_control(
			'order_input_superior_field', 
			[
				'label' => __( 'Superior Field', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => '',
				'options' => $this->bew_checkout_fields_name_custom('order'),
				'label_block' => true,
				 'condition'=>[
					'order_input_conditional'=>'yes',
				],
			]
		);

		$repeater->add_control(
			'order_input_superior_field_option', 
			[
				'label' => __( 'Superior Field Options', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Enter option' , 'bew-extras' ),
				'label_block' => true,
				'description' => __( 'Enter the option from superior field selected for your conditional display', 'bew-extras' ),
				'condition'=>[             
				   'order_input_superior_field!'=>'',
				],
			]
		);
	
		$repeater->add_control(
			'order_input_placeholder', [
				'label' => __( 'Placeholder', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Placeholder' , 'bew-extras' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'order_input_autocomplete', [
				'label' => __( 'Autocomplete Value', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Given value' , 'bew-extras' ),
				'label_block' => true,
			]
		);

		$repeater->add_control(
			'order_input_required',
			[
				'label'         => __( 'Required', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'yes', 'bew-extras' ),
				'label_off'     => __( 'no', 'bew-extras' ),
				'return_value'  => 'yes',
				'default'       => '',
			]
		);

		$repeater->add_control(
			'order_input_show_email',
			[
				'label'         => esc_html__( 'Show in Email', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
				'label_off'     => esc_html__( 'No', 'bew-extras' ),
				'return_value'  => true,
				'default'       => '',
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);

		$repeater->add_control(
			'order_input_show_order',
			[
				'label'         => esc_html__( 'Show in Order Detail Page', 'bew-extras' ),
				'type'          => Controls_Manager::SWITCHER,
				'label_on'      => esc_html__( 'Yes', 'bew-extras' ),
				'label_off'     => esc_html__( 'No', 'bew-extras' ),
				'return_value'  => true,
				'default'       => '',
				'condition'=>[
					'order_input_name' => 'order_custom_field',
				],
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_items',
			[
				'label' => __( '', 'bew-extras' ),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => $this->bew_checkout_fields( 'order' ),
				'title_field' => '{{{ order_input_label }}}',
				'condition'=>[
				   'woo_checkout_additional_content_field_editor'=>'yes',
				],
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'woo_checkout_form_additional_multistep',
			[
				'label' => __( 'Multistep', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_multistep_step', [
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

		//Section general style
		$this->start_controls_section(
			'woo_checkout_form_additional_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'woo_checkout_form_additional_general_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-form-additional .bew-components-checkout-step' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_form_additional_general_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}}.elementor-widget-woo-checkout-form-additional .bew-components-checkout-step' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();	

		//section title style
		$this->start_controls_section(
			'woo_checkout_form_additional_title_style',
			[
				'label' => __( 'Title', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [
                    'woo_checkout_form_additional_title_show' => 'yes'
                ],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_shipping_title_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-pm-title',
				'condition' => [
                    'woo_checkout_form_additional_title_show' => 'yes'
                ],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_form_additional_checkbox',
			[
				'label' => __( 'Checkbox', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,                
			]
		);
		
		$this->add_control(
			'woo_checkout_form_additional_checkbox_color',
			[
				'label'     => __( 'Checkbox Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__input[type=checkbox]:checked:before' => 'color: {{VALUE}};',
				],
				
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_checkbox_background_color',
			[
				'label'     => __( 'Checkbox Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
								'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__input[type=checkbox]:checked:before' => 'background-color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_form_additional_checkbox_size',
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
					'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__input[type=checkbox]:checked:before' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_form_additional_checkbox_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' 	=> '{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__input[type=checkbox]',
			]
		);

        $this->add_control(
			'woo_checkout_form_additional_checkbox_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__input[type=checkbox]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_form_additional_checkbox_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_form_additional_checkbox_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();

		/**
		 * Label
		 */
		$this->start_controls_section(
			'woo_checkout_form_additional_style',
			[
				'label' => __( 'Label', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_form_additional_checkbox_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__label',
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_checkbox_text_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__label' => 'color: {{VALUE}};',
				],
			]
		);


        $this->add_responsive_control(
        	'woo_checkout_form_additional_label_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__label' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

        $this->add_responsive_control(
        	'woo_checkout_form_additional_label_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-form-additional .bew-checkout__add-note .bew-components-checkbox .bew-components-checkbox__label' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->end_controls_section();

		/**
		 * Input
		 */
		$this->start_controls_section(
			'woo_checkout_form_additional_input_style',
			[
				'label' => __( 'Input Fields', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_form_additionalorder_input_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' => '{{WRAPPER}} .bew-form-additional .form-row input, 
								{{WRAPPER}} .bew-form-additional .form-row select, 
								{{WRAPPER}} .bew-form-additional .form-row option,
								{{WRAPPER}} .bew-form-additional .form-row textarea',
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_input_color',
			[
				'label'     => __( 'Input Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .form-row input, 
					 {{WRAPPER}} .bew-form-additional .form-row select, 
					 {{WRAPPER}} .bew-form-additional .form-row option,
					 {{WRAPPER}} .bew-form-additional .form-row textarea' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_form_additional_input_background_color',
			[
				'label'     => __( 'Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .form-row input, 
					 {{WRAPPER}} .bew-form-additional .form-row select, 
					 {{WRAPPER}} .bew-form-additional .form-row option,
					 {{WRAPPER}} .bew-form-additional .form-row textarea' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'woo_checkout_form_additional_input_border',
				'label' 	=> __( 'Border', 'bew-extras' ),
				'separator' => 'before',
				'selector' => '{{WRAPPER}} .bew-form-additional .form-row input, 
								{{WRAPPER}} .bew-form-additional .form-row select,
								{{WRAPPER}} .bew-form-additional .form-row textarea',
			]
		);

        $this->add_control(
			'woo_checkout_form_additional_input_border_radius',
			[
				'label' => __( 'Border Redius', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .form-row input, 
					 {{WRAPPER}} .bew-form-additional .form-row select,
					 {{WRAPPER}} .bew-form-additional .form-row textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_form_additional_input_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .form-row input, 
					 {{WRAPPER}} .bew-form-additional .form-row select,
					 {{WRAPPER}} .bew-form-additional .form-row textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_form_additional_input_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-form-additional .form-row input, 
					 {{WRAPPER}} .bew-form-additional .form-row select,
					 {{WRAPPER}} .bew-form-additional .form-row textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$options[ 'order_custom_field'] = 'Custom';
			
		return $options;
	}
	
	function bew_checkout_fields_name_custom( $section = 'billing' ) {
		
		$get_fields = get_option( '_bew_checkout_fields',  [] );
		
		$options = [];
		foreach ( $get_fields as $_fields ) {
						
			if( count( $_fields ) > 0 ) {
				
				if( isset($get_fields[ $section ])) {
					foreach ( $get_fields[ $section ] as $key => $field ) {						
							$options[ $key ] = $field['label'];
					}
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

	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
		$settings = $this->get_settings_for_display();

		$checkout_order_steps   		 			= $settings['woo_checkout_form_additional_steps'];
		$order_description_show 		 			= $settings['woo_checkout_form_additional_description_show'];
		$order_description_text 		 			= $settings['woo_checkout_form_additional_description_text'];		
		$form_additional_items     					= $settings['woo_checkout_form_additional_items'];
		$woo_checkout_form_additional_title_show    = $settings['woo_checkout_form_additional_title_show'];
		$woo_checkout_form_additional_title_text	= $settings['woo_checkout_form_additional_title_text'];
		$woo_checkout_form_additional_title_tag		= Utils::validate_html_tag( $settings['woo_checkout_form_additional_title_tag'] ); 
		$field_editor  	          					= $settings['woo_checkout_additional_content_field_editor'];
		$order_layout                               = '';
		
		// Get checkout object.
		$checkout = WC()->checkout();
		
		if( $field_editor  == 'yes' ){

			$order_fields = [];
			foreach( $form_additional_items as $item ) {
				
				$has_placeholder = empty($item['order_input_placeholder']) ? '' : ' has-placeholder';
				
				$order_input_class = [];
				if (is_array( $item['order_input_class'] )){
					$order_input_class = $item['order_input_class'];			
				}else {
					$order_input_class[] = $item['order_input_class'];	
				}
							
				array_push($order_input_class, "label-inside-" . $item['order_input_label_layout'] . " label-hide-" . $item['order_input_label_hide'] . " type-" . $item['order_input_type'] );			

				$fkey = $item['order_input_name'];
				
				if( $item['order_input_name'] == 'order_custom_field' ){
					$fkey = 'order_'.$item['order_input_field_key_custom'];
				}
				
				$order_fields[ sanitize_text_field( $fkey ) ] = 
					[
						'label'			=> sanitize_text_field( __( $item['order_input_label'], 'woocommerce' )),
						'type'			=> $item['order_input_type'],
						'required'		=> $item['order_input_required'] == 'yes' ? true : false,
						'class'			=> $order_input_class,
						'autocomplete'	=> sanitize_text_field( $item['order_input_autocomplete'] ), 
						'placeholder'	=> sanitize_text_field( $item['order_input_placeholder'] ), 
						'priority'		=> 10,
					];

					if( ($item['order_input_type'] == 'select' )  || ($item['order_input_type'] == 'radio') ){
						
							if ( isset( $item['order_input_options'] ) ) {
								//echo var_dump($item['order_input_options']);
								$options = explode( "\n", $item['order_input_options'] );
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
							$order_fields[$fkey]['option_layout'] 		= $item['order_input_options_layout']; 
							$order_fields[$fkey]['options']        		= isset( $new_options ) ? $new_options : '';
							$order_fields[$fkey]['default']        		= $default;
					}
					
					if( $item['order_input_name'] == 'order_custom_field' ){
						
							if ( isset( $item['order_input_options'] ) ) {
								//echo var_dump($item['billing_input_options']);
								$options = explode( "\n", $item['order_input_options'] );
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
					
							$order_fields[$fkey]['custom']         		= true;
							$order_fields[$fkey]['type']           		= $item['order_input_type'];
							$order_fields[$fkey]['show_in_email']  		= $item['order_input_show_email'];
							$order_fields[$fkey]['show_in_order']  		= $item['order_input_show_order'];
							$order_fields[$fkey]['conditional']    		= $item['order_input_conditional']; 
							$order_fields[$fkey]['superior_field'] 		= $item['order_input_superior_field']; 
							$order_fields[$fkey]['superior_field_option'] = $item['order_input_superior_field_option'];							
							
							if( ($item['order_input_type'] == 'select' )  || ($item['order_input_type'] == 'radio') ){
								
								$order_fields[$fkey]['option_layout'] 		= $item['order_input_options_layout']; 
								$order_fields[$fkey]['options']        		= isset( $new_options ) ? $new_options : '';
								$order_fields[$fkey]['default']        		= $default;
								
							}
					}
					
					//Save layout
					if(isset($item['order_input_layout'])){
						$order_layout = $item['order_input_layout'];					
					}
					
			}

			// Send Fields to regenerate										
			if( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				$_bew_checkout_fields = get_option( '_bew_checkout_fields', [] );
				if (is_array( $_bew_checkout_fields)){
					$_bew_checkout_fields['order'] = $order_fields;
					update_option( '_bew_checkout_fields', $_bew_checkout_fields );
				} else {
					update_option( '_bew_checkout_fields',  [] );
				}
				update_option( '_bew_checkout_fields_order', 'bew_fields_order');
			}

		}else{
			 delete_option( '_bew_checkout_fields_order' );
		}
		
		//$get_fields = get_option( '_bew_checkout_fields',  [] );
		//echo var_dump($get_fields);
		//$aa  = $this->bew_checkout_fields_name_custom();
		//echo var_dump($aa);
		//$get_fields = WC()->checkout->get_checkout_fields();
		//echo var_dump($get_fields) ;
		//echo var_dump($this->bew_checkout_fields( 'order' ));
		//echo var_dump($this->bew_checkout_fields( 'order' ));

		$helper = new Helper();
		
		$label_text =  'Add a note to your order';
		?>
		
		<div class="bew-components-checkout-step bew-checkout-steps-<?php echo $checkout_order_steps; ?>">
			<?php do_action( 'woocommerce_before_order_notes', $checkout ); ?>
			<?php if( 'yes' == $woo_checkout_form_additional_title_show ): ?>
				<div class="bew-checkout-step-heading">
				<<?php echo esc_attr( $woo_checkout_form_additional_title_tag ); ?> class="bew-checkout-step-title bew-pm-title"><?php echo esc_html( $woo_checkout_form_additional_title_text ); ?></<?php echo esc_attr( $woo_checkout_form_additional_title_tag ) ?>>
				
				</div>
			<?php endif; ?>
			<div class="bew-form-additional <?php echo $order_layout ?>" id="order-notes">			
				<div class="bew-checkout-step-container bew-components-checkout-step__container">
				
					<?php
					if('yes' == $order_description_show ){
					?>	
						<p class="bew-components-checkout-step__description"><?php echo esc_html( $order_description_text ); ?></p>
					<?php
					}
					?>
					<div class="bew-components-checkout-step__content">
						<div class="bew-checkout__add-note">
							<?php if($order_layout == "layout-checkbox"){?>
								<label class="bew-components-checkbox" for="checkbox-control-1">
									<input id="checkbox-control-1" class="bew-components-checkbox__input" type="checkbox">
									<span class="bew-components-checkbox__label"><?php esc_html_e( $label_text, 'woocommerce' ); ?><</span>
								</label>
							<?php }
							if( $field_editor   == 'yes' ){
																
								foreach ( $order_fields as $key => $field ) {
									
									//Get Order comment from Cart option							
									$cart_items = WC()->cart->get_cart();
									
									foreach ($cart_items as $cart_item_key => $cart_item) {
										$aa  = $cart_item['order_comments'];
									}
									
									if (!empty($aa)){
										$value = $aa;									
									} else {
										$value = WC()->checkout->get_value( $key );
									}
									
									$helper->bew_woocommerce_form_field( $key, $field, $value );									
								}						
								
							}else{
								
								$fields = WC()->checkout->get_checkout_fields( 'order' );
								foreach ( $fields as $key => $field ) {
									woocommerce_form_field( $key, $field, WC()->checkout->get_value( $key ) );
								}
								
							}
							?>
						</div>
					</div>
				</div>		
			</div>
			<?php do_action( 'woocommerce_after_order_notes', $checkout ); ?>
		</div>
		
		<script type="text/javascript">
			jQuery(function($){
				
               	$('.bew-components-checkbox').on('click',function () {
               	    if ($('.bew-components-checkbox__input').is(':checked')) {
               	       	$('#order_comments_field').show();
               	    }else{
               	      	$('#order_comments_field').hide();
               	    }
               	});
					
						
			 })
		</script>
		<?php
	}

	protected function _content_template() {
		
	}
	
}

