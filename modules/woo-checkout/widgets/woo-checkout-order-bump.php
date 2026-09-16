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

class Woo_Checkout_Order_Bump extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-order-bump';
	}

	public function get_title() {
		return __( 'Checkout Order Bump', 'bew-extras' );
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
			'woo_checkout_order_bump',
			[
				'label' => __( 'Order Bump', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_order_bump_layout', [
				'label' => __( 'Order Bump', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'box',
				'options' => [
					'box' => 'Box Bump',
				],				
			]
		);

		if($this->is_elementor_pro_installed()){
			$this->add_control(
				'woo_checkout_order_bump_product_id',
				[
					'label' => esc_html__( 'Product', 'bew-extras' ),
					'type' => Module::QUERY_CONTROL_ID,
					'options' => [],
					'label_block' => true,
					'autocomplete' => [
						'object' => Module::QUERY_OBJECT_POST,
						'query' => [
							'post_type' => [ 'product' ],
						],
					],
				]
			);
		} else {
			$this->add_control(
			'woo_checkout_order_bump_product_id',
			[
				'label' => esc_html__( 'Product ID', 'bew-extras' ),
				'type' => Controls_Manager::NUMBER,				
			]
		);
		}

		$this->add_control(
			'woo_checkout_order_bump_quantity',
			[
				'label' => esc_html__( 'Quantity', 'bew-extras' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 1,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_order_bump_heading',
			[
				'label' => __( 'Order Bump Heading', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'woo_checkout_order_bump_heading_show',
            [
                'label'         => __( 'Order Bump Heading', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',				
            ]
        );
		
		$this->add_control(
		    'woo_checkout_order_bump_heading_text',
		    [
		        'label' 		=> __( 'Heading Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Order bump text', 'bew-extras' ) ,
                'condition' => [
                    'woo_checkout_order_bump_heading_show' => 'yes'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
			'woo_checkout_order_bump_heading_tag',
			[
				'label' 	=> __( 'HTML Tag', 'elementor' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'h3',
				'options' 	=> [
					'h1'  => __( 'H1', 'bew-extras' ),
					'h2'  => __( 'H2', 'bew-extras' ),
					'h3'  => __( 'H3', 'bew-extras' ),
					'h4'  => __( 'H4', 'bew-extras' ),
					'h5'  => __( 'H5', 'bew-extras' ),
					'h6'  => __( 'H6', 'bew-extras' ),
				],
                'condition' => [
                    'woo_checkout_order_bump_heading_show' => 'yes'
                ],
			]
		);

		$this->add_control(
			'woo_checkout_order_bump_heading_alignment',
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
					'woo_checkout_order_bump_heading_show' => 'yes'
				],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob-heading .bew-checkout-ob-title' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_order_bump_content',
			[
				'label' => __( 'Order Bump Content', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
            'woo_checkout_order_bump_checked_show',
            [
                'label'         => __( 'Checkbox', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-ob-checkbox-',
            ]
        );

		$this->add_control(
            'woo_checkout_order_bump_title_show',
            [
                'label'         => __( 'Title', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-ob-title-',
            ]
        );
		
		$this->add_control(
		    'woo_checkout_order_bump_title_text',
		    [
		        'label' 		=> __( 'Title Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Yes, I want this!', 'bew-extras' ) ,
                'condition' => [
                    'woo_checkout_order_bump_title_show' => 'yes'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_responsive_control(
            'woo_checkout_order_bump_title_alignment',
            [
                'label' 	   => __( 'Title Alignment', 'elementor' ),
                'type' 		   => Controls_Manager::CHOOSE,
                'options' 	   => [
					'flex-start' 	=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 		=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'flex-end' 		=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
                'default' 	=> 'flex-start',
                'toggle' 	=> true,
                'condition' => [
                    'woo_checkout_order_bump_heading_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-checkout-ob .bew-ob-product-top .bew-ob-title-wrap' => 'justify-content: {{VALUE}};'					
                ],
            ]
        );

		$this->add_control(
            'woo_checkout_order_bump_price_show',
            [
                'label'         => __( 'Price', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-ob-price-',
            ]
        );

		$this->add_responsive_control(
            'woo_checkout_order_bump_price_alignment',
            [
                'label' 	   => __( 'Price Alignment', 'elementor' ),
                'type' 		   => Controls_Manager::CHOOSE,
                'options' 	   => [
					'flex-start' 	=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 		=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'flex-end' 		=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
                'default' 	=> 'flex-end',
                'toggle' 	=> true,
                'condition' => [
                    'woo_checkout_order_bump_heading_show' => 'yes',
					'woo_checkout_order_bump_price_show' => 'yes'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-checkout-ob .bew-ob-product-top .bew-ob-price' => 'justify-content: {{VALUE}};'					
                ],
            ]
        );
				
		$this->add_control(
            'woo_checkout_order_bump_product_title_show',
            [
                'label'         => __( 'Product Title', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-ob-product-title-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_bump_product_image_show',
            [
                'label'         => __( 'Product Image', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-ob-product-image-',
            ]
        );

		$this->add_control(
            'woo_checkout_order_bump_product_desc_show',
            [
                'label'         => __( 'Product Description', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'bew-ob-product-desc-',
            ]
        );
		
		$this->add_control(
		    'woo_checkout_order_bump_desc_text',
		    [
		        'label' 		=> __( 'Content Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXTAREA,
		        'default' 		=> __( 'Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry standard dummy text ever since the 1500s.', 'bew-extras' ),
                'condition' => [
                    'woo_checkout_order_bump_product_desc_show' => 'yes'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'woo_checkout_order_bump_order_multistep',
			[
				'label' => __( 'Multistep', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_order_bump_order_multistep_step', [
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
			'woo_checkout_order_bump_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'woo_checkout_order_bump_general_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-ob-product-wrap' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_table_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-checkout-ob .bew-ob-product-wrap',
		    ]
		);

		$this->add_responsive_control(
			'woo_checkout_order_bump_general_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-ob-product-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_bump_general_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-ob-product-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section heading style
		$this->start_controls_section(
			'woo_checkout_order_bump_heading_style',
			[
				'label' => __( 'Order Bump Heading', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,               
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_bump_heading_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-ob-heading .bew-checkout-ob-title',
			]
		);

		$this->add_control(
			'woo_checkout_order_bump_heading_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob-heading .bew-checkout-ob-title' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_order_bump_heading_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-ob-heading .bew-checkout-ob-title',
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_bump_heading_padding',
			[
				'label' => __( 'Padding', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob-heading .bew-checkout-ob-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_bump_heading_margin',
			[
				'label' => __( 'Margin', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob-heading .bew-checkout-ob-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section content style
		$this->start_controls_section(
			'woo_checkout_order_bump_content_style',
			[
				'label' => __( 'Order Bump Content', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,                
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_bump_title_typography',
				'label' 	=> __( 'Title Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top .bew-ob-title',				
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_bump_price_typography',
				'label' 	=> __( 'Price Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top .bew-ob-price',				
			]
		);

        $this->add_control(
			'woo_checkout_order_bump_title_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top' => 'color: {{VALUE}}',
				],
			]
		);
		
        $this->add_control(
			'woo_checkout_order_bump_title_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_bump_title_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top',
		    ]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_bump_title_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_bump_title_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-top' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_order_bump_content_images',
			[
				'label'     => __( 'Images', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'woo_checkout_order_bump_content_img_size',
			[
				'label' 		=> __( 'Image Size', 'bew-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' 	=> 10,
						'max' 	=> 200,
						'step' 	=> 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => '%',
					'size' => 40,
				],				
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-image' => 'flex: 0 0 {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_bump_content_img_border_type',
		        'label'     => __( 'Image Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-image img',
		    ]
		);
		
		$this->add_control(
			'woo_checkout_order_bump_content_img_border_radius',
			[
				'label' => __( 'Image Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
        	'woo_checkout_order_bump_content_img_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-image img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_responsive_control(
        	'woo_checkout_order_bump_content_img__margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-image img' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_control(
			'woo_checkout_order_bump_content_desc',
			[
				'label'     => __( 'Description', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_bump_content_typography',
				'label' 	=> __( 'Title Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-desc',
			]
		);
		
        $this->add_control(
			'woo_checkout_order_bump_content_desc_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-desc' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_bump_content_desc_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-desc',
		    ]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_bump_content_desc_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_bump_content_desc_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
            'woo_checkout_order_bump_content_desc_alignment',
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
                'selectors' => [
                    '{{WRAPPER}} .bew-checkout-ob .bew-checkout-ob-container .bew-ob-product-content .bew-ob-product-desc' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->end_controls_section();
		
	}

	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
		$settings = $this->get_settings_for_display();
		$rule_id = '';
		
		$order_bump_heading_show         = $settings['woo_checkout_order_bump_heading_show'];
		$order_bump_heading_text         = $settings['woo_checkout_order_bump_heading_text'];
		$order_bump_heading_tag			 = $settings['woo_checkout_order_bump_heading_tag'];
		$order_bump_title_text			 = $settings['woo_checkout_order_bump_title_text'];
		$order_bump_desc_text			 = $settings['woo_checkout_order_bump_desc_text'];
				
		if ( ! empty( $settings['woo_checkout_order_bump_product_id'] ) ) {
			$product_id = $settings['woo_checkout_order_bump_product_id'];
		} elseif ( wp_doing_ajax() && ! empty( $settings['woo_checkout_order_bump_product_id'] ) ) {
			// PHPCS - No nonce is required.
			$product_id = $_POST['post_id']; // phpcs:ignore WordPress.Security.NonceVerification.Missing
		} else {
			$product_id = get_queried_object_id();
		}
				
		if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			update_option( 'ob_product_id', $product_id);
			update_option( 'ob_title_text', $order_bump_title_text);
			update_option( 'ob_desc_text', $order_bump_desc_text);
		}
		
		$div_class = array(
			'bew-checkout-ob-shortcode',
			'bew-checkout-ob-shortcode-' . $rule_id,
		);
		$div_class = trim( implode( ' ', $div_class ) );
		
		$shortcode = '[bew_checkout_order_bump id="' . $rule_id . '" product_id="' . $product_id . '" ]';
		
		if( Elementor\Plugin::instance()->editor->is_edit_mode() ) { 
			$shortcode = do_shortcode( shortcode_unautop( $shortcode ) );
			
		} 

		//$product_id = get_option( 'ob_product_id');	
		//echo $product_id;
		//$frontend = Frontend::instance();
		?>
			
		<div class="bew-checkout-ob">
			
			<?php if( 'yes' == $order_bump_heading_show ){ ?>
				<div class="bew-checkout-ob-heading">
				<<?php echo esc_attr( $order_bump_heading_tag ); ?> class="bew-checkout-ob-title"><?php echo esc_html( $order_bump_heading_text  ); ?></<?php echo esc_attr( $order_bump_heading_tag ) ?>>				
				</div>
			<?php } ?>
			
			<div class="bew-checkout-ob-container">

				<div class="<?php echo esc_attr( $div_class ) ?>">
					<div class="bew-loading-wrap bew-disable">
						<div class="bew-loading">
							<div></div>
							<div></div>
							<div></div>
							<div></div>
						</div>
					</div>					
					<?php echo $shortcode; ?>
				</div>
			</div>
		</div>
		<?php
	}

	protected function content_template() {}
	
}

