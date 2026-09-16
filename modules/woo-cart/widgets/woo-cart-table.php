<?php
namespace BriefcasewpExtras\Modules\WooCart\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;   
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Background;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Cart_Table extends Base_Widget {

	public function get_name() {
		return 'woo-cart-table';
	}

	public function get_title() {
		return __( 'Woo Cart Table', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-cart' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general', 'bew-checkout', 'woo-shop','imagesloaded', 'slick-carousel' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {

        // Cart Table General
        $this->start_controls_section(
            'cart_table_general',
            [
                'label' => esc_html__( 'General', 'bew-extras' ),
            ]
        );

		$this->add_control(
			'cart_table_general_loader',
			[
				'label' 		=> __( 'Loading', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'On', 'elementor' ),
				'label_off' 	=> __( 'Off', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'prefix_class' => 'bew-cart-loader-',
				'description' 	=> __( 'Enabled loading effect on update cart', 'bew-extras' ),	
			]
		);

		$this->add_control(
            'cart_table_general_loader_type',
            [
                'label' => esc_html__( 'Loading Type', 'bew-extras' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'spinner',
				'label_block' => true,
				'prefix_class' => 'bew-cart-loader-type-',
                'options' => [
                    'spinner'    => esc_html__( 'Spinner', 'bew-extras' ),
                    'skeleton' => esc_html__( 'Skeleton', 'bew-extras' ),
                ],
				'condition' 	=> [
					'cart_table_general_loader' => 'yes',						
				],					
            ]
        );
		
        $this->end_controls_section();
		
        // Cart Table Row Content
        $this->start_controls_section(
            'cart_content',
            [
                'label' => esc_html__( 'Cart Products Table', 'bew-extras' ),
            ]
        );
		
		$this->add_control(
			'cart_title',
			[
				'label' 		=> __( 'Cart Title', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> 'yes',
				'prefix_class'  => 'bew-cart-title-show-'
			]
		);

		$this->add_control(
			'cart_title_text_icon',
			[
				'label' => esc_html__( 'Icon', 'elementor' ),
				'type' => Controls_Manager::ICONS,
				'fa4compatibility' => 'icon',
				'skin' => 'inline',
				'label_block' => false,
			]
		);

		$this->add_control(
			'cart_title_text_before',
			[
				'label' 		=> __( 'Title Before Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Your Cart', 'bew-extras' ),
				'placeholder' 	=> __( 'Type your title here', 'bew-extras' ),
				'condition' 	=> [
					'cart_title' => 'yes'
				],
			]
		);

		$this->add_control(
			'cart_title_text_after',
			[
				'label' 		=> __( 'Title After Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'placeholder' 	=> __( 'Type your title here', 'bew-extras' ),
				'condition' 	=> [
					'cart_title' => 'yes'
				],
			]
		);

		$this->add_control(
			'cart_title_quantity',
			[
				'label' 		=> __( 'Cart Quantity', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'inline',
				'default' 		=> 'inline',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-cart-table .bew-components-title span' => 'display: {{VALUE}}',
				],
				'condition' 	=> [
					'cart_title' => 'yes'
				],
			]
		);
		
		$this->add_responsive_control(
			'cart_table_heading',
			[
				'label' 		=> __( 'Product Table Heading', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'selectors_dictionary' => [
					'yes' => 'flex',
					'' => 'none',
				],				
				'default' 		=> 'yes',
				'selectors' 	=> [
					'{{WRAPPER}} .bew-woo-cart-table .shop_table .bew-cart-items-titles' => 'display: {{VALUE}}',
				],
				'separator' 	=> 'before'
			]
		);
            
            $repeater = new Repeater();
            $repeater->add_control(
                'table_items',
                [
                    'label' => esc_html__( 'Table Item', 'bew-extras' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'remove',
                    'options' => [
                        'remove'    => esc_html__( 'Remove', 'bew-extras' ),
                        'thumbnail' => esc_html__( 'Image', 'bew-extras' ),
                        'name'      => esc_html__( 'Title', 'bew-extras' ),
                        'price'     => esc_html__( 'Price', 'bew-extras' ),
                        'quantity'  => esc_html__( 'Quantity', 'bew-extras' ),
                        'subtotal'  => esc_html__( 'Subtotal', 'bew-extras' ),
                        'customadd' => esc_html__( 'Custom', 'bew-extras' ),
                    ],
                ]
            );

            $repeater->add_control(
                'table_heading_title', 
                [
                    'label' => esc_html__( 'Heading Title', 'bew-extras' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Product title' , 'bew-extras' ),
                    'label_block' => true,
                ]
            );

			$repeater->add_control(
				'table_custom_content',
				[
                    'label' => esc_html__( 'Custom Content', 'bew-extras' ),
                    'type' => Controls_Manager::TEXTAREA,
					'ai' => [
						'type' => 'text',
					],
					'dynamic' => [
						'active' => true,
					],
					'placeholder' => esc_html__( 'Enter your content', 'elementor' ),
					'default' => esc_html__( 'Add Your Custom Text Here', 'elementor' ),
					'condition' 	=> [
						'table_items' => 'customadd',					
					],					
				]
			);			
			
			$repeater->add_responsive_control(
                'table_cell_layout',
                [
                    'label'        => __( 'Layout', 'elementor' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'horizontal'   => [
                            'title' => __( 'horizontal', 'elementor' ),
                            'icon'  => 'eicon-ellipsis-h',
                        ],
                        'vertical' => [
                            'title' => __( 'vertical', 'elementor' ),
                            'icon'  => 'eicon-ellipsis-v',
                        ],
                    ],
					'devices' => [ 'desktop', 'tablet', 'mobile' ],
                ]
            );
			
			$repeater->add_responsive_control(
                'colspan', 
                [
                    'label' => esc_html__( 'Column Span', 'bew-extras' ),
                    'type' => Controls_Manager::NUMBER,
                    'default' => '1',					
                ]
            );

            $repeater->add_responsive_control(
                'table_cell_width_title',
                [
                    'label' => esc_html__( 'Heading Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
					'default' => [
						'unit' => 'px',				
					],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart .bew-cart-items-titles .product-element{{CURRENT_ITEM}}' => 'flex-basis: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $repeater->add_responsive_control(
                'table_cell_width',
                [
                    'label' => esc_html__( 'Column Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 0,
                            'max' => 1000,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],
					'default' => [
						'unit' => 'px',				
					],
                    'selectors' => [
						'{{WRAPPER}} .shop_table.shop_table_responsive.cart .bew-cart-items-content .woocommerce-cart-form__cart-item .product-element{{CURRENT_ITEM}}' => 'flex-basis: {{SIZE}}{{UNIT}}; width: {{SIZE}}{{UNIT}};',	
                    ],
                ]
            );			

            $repeater->add_responsive_control(
                'horizontal_position',
                [
                    'label' => esc_html__( 'Horizontal Position', 'bew-extras' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'center',
                    'options' => [
                        'flex-start'    => esc_html__( 'Start', 'bew-extras' ),
                        'center' 		=> esc_html__( 'Center', 'bew-extras' ),
                        'flex-end'      => esc_html__( 'End', 'bew-extras' ),
                    ],					
					'selectors' => [
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart .bew-cart-items-content .woocommerce-cart-form__cart-item .product-element{{CURRENT_ITEM}}' => 'justify-content: {{VALUE}}',
                    ],
                ]
            );
			
            $repeater->add_responsive_control(
                'vertical_position',
                [
                    'label' => esc_html__( 'Vertical Position', 'bew-extras' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'center',
                    'options' => [
                        'flex-start'    => esc_html__( 'Top', 'bew-extras' ),
                        'center' 		=> esc_html__( 'Middle', 'bew-extras' ),
                        'flex-end'      => esc_html__( 'Bottom', 'bew-extras' ),
                    ],					
					'selectors' => [
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart .bew-cart-items-content .woocommerce-cart-form__cart-item .product-element{{CURRENT_ITEM}}' => 'align-self: {{VALUE}}',
                    ],
                ]
            );
	
			$repeater->add_control(
                'remove_layout',
                [
                    'label' => esc_html__( 'Remove Layout', 'bew-extras' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'icon',
                    'options' => [                        
                        'icon' => esc_html__( 'Icon', 'bew-extras' ),
						'text' => esc_html__( 'Text', 'bew-extras' ),
						'both' => esc_html__( 'Both', 'bew-extras' ),
                    ],
					'condition' 	=> [
						'table_items' => 'remove',					
					],					
                ]
            );
	
			$repeater->add_control(
				'hide_element_mobile',
				[
					'label' 		=> __( 'Hide on Mobile', 'bew-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'On', 'elementor' ),
					'label_off' 	=> __( 'Off', 'elementor' ),
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 	=> [
						'table_items' => ['remove', 'price', 'subtotal' ],
					],					
				]
			);
			
			$repeater->add_control(
				'absolute_qty',
				[
					'label' 		=> __( 'Absolute Position on Mobile', 'bew-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'Show', 'elementor' ),
					'label_off' 	=> __( 'Hide', 'elementor' ),
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 	=> [
						'table_items' => 'quantity',
					],
				]
			);
			
			$repeater->add_control(
				'float_right_qty',
				[
					'label' 		=> __( 'Float Right Qty on Mobile', 'bew-extras' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'label_on' 		=> __( 'Show', 'elementor' ),
					'label_off' 	=> __( 'Hide', 'elementor' ),
					'return_value' 	=> 'yes',
					'default' 		=> '',
					'condition' 	=> [
						'table_items' => 'quantity',
					],
				]
			);

			$repeater->add_control(
				'text_before_qty',
				[
					'label' 		=> __( 'Text Before Qty', 'bew-extras' ),
					'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( '' , 'bew-extras' ),
                    'label_block' => true,
					'condition' 	=> [
						'table_items' => 'quantity',
					],
				]
			);

			$repeater->add_control(
				'text_after_qty',
				[
					'label' 		=> __( 'Text After Qty', 'bew-extras' ),
					'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( '' , 'bew-extras' ),
                    'label_block' => true,
					'condition' 	=> [
						'table_items' => 'quantity',
					],
				]
			);
			
			$repeater->add_responsive_control(
				'show_remove_qty',
				[
					'label' 		=> __( 'Show Remove', 'bew-extras' ),
					'type' 			=> Controls_Manager::SELECT,
                    'options' => [
                        'yes'    => esc_html__( 'Yes', 'bew-extras' ),
                        'no' => esc_html__( 'No', 'bew-extras' ),
                    ],
					'default' => 'yes',
					'condition' 	=> [
						'table_items' => 'quantity',
					],
				]
			);

			$repeater->add_control(
                'remove_layout_qty',
                [
                    'label' => esc_html__( 'Mobile Remove Layout', 'bew-extras' ),
                    'type' => Controls_Manager::SELECT,
                    'default' => 'text',
					'label_block' => true,
                    'options' => [
                        'text'    => esc_html__( 'Text', 'bew-extras' ),
                        'icon' => esc_html__( 'Icon', 'bew-extras' ),
                    ],
					'condition' 	=> [
						'table_items' => 'quantity',						
					],					
                ]
            );

            $repeater->add_control(
                'remove_text', 
                [
                    'label' => esc_html__( 'Remove Text', 'bew-extras' ),
                    'type' => Controls_Manager::TEXT,
                    'default' => esc_html__( 'Remove Item' , 'bew-extras' ),
                    'label_block' => true,
					'condition' 	=> [
						'table_items' => 'quantity',						
						'remove_layout_qty' => 'text',
					],
                ]
            );			
			
			$repeater->add_responsive_control(
                'table_cell_align',
                [
                    'label'        => __( 'Alignment', 'elementor' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.shop_table_responsive.cart .bew-cart-items-content .woocommerce-cart-form__cart-item .product-element{{CURRENT_ITEM}}' => 'text-align: {{VALUE}} !important; justify-content: {{VALUE}} !important;',						
                    ],		
                ]
            );

            $this->add_control(
                'table_item_list',
                [
                    'label' => __( 'Product Table Items', 'bew-extras' ),
                    'type' => Controls_Manager::REPEATER,
                    'fields' => $repeater->get_controls(),
                    'default' => [                       
                        [
                            'table_items'           => 'thumbnail',
                            'table_heading_title'   => esc_html__( 'Image', 'woocommerce' ), 
							'table_cell_width'      => [
														'size' => '100',
														'unit' => 'px'
														],
                        ],
                        [
                            'table_items'           => 'name',
                            'table_heading_title'   => esc_html__( 'Product', 'woocommerce' ),
							'colspan'               => '2',
                        ],                       
                        [
                            'table_items'           => 'quantity',
                            'table_heading_title'   => esc_html__( 'Quantity', 'woocommerce' ),
                        ],
                        [
                            'table_items'           => 'subtotal',
                            'table_heading_title'   => esc_html__( 'Total', 'woocommerce' ),
                        ],
                    ],
                    'title_field' => '{{{ table_heading_title }}}',
                ]
            );

        $this->end_controls_section();

		$this->start_controls_section(
			'section_content_quantity',
			[
				'label' => __( 'Quantity', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ajax_delay_qty',
			[
				'label' 		=> __( 'Quantity Update Delay', 'bew-extras' ),
				'type' 			=> Controls_Manager::NUMBER,
				'default' 		=> 	500,
				'description' 	=> __( 'Set up the delay for ajax quantity update on miliseconds', 'bew-extras' ),								
			]
		);
		
		$this->end_controls_section();

		// Bottom Sections	
		$this->start_controls_section(
			'section_content_bottom_sections',
			[
				'label' => __( 'Cart Actions', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
				
		$this->add_control(
			'coupon_show_hide',
			[
				'label' 		=> __( 'Coupon Area', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',				
			]
		);

		$this->add_control(
			'coupon_button_name',
			[
				'label' 		=> __( 'Button Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Apply coupon', 'bew-extras' ),
				'placeholder' 	=> __( 'Type your title here', 'bew-extras' ),
				'condition' 	=> [
					'coupon_show_hide' => 'yes'
				],
			]
		);

		$this->add_control(
			'coupon_button_placeholder',
			[
				'label' 		=> __( 'Placeholder Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Coupon code', 'bew-extras' ),
				'placeholder' 	=> __( 'Type your title here', 'bew-extras' ),
				'condition' 	=> [
					'coupon_show_hide' => 'yes'
				],
			]
		);

		$this->add_control(
			'update_cart_show_hide',
			[
				'label' 		=> __( 'Update Button', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'separator'		=> 'before'
			]
		);

		$this->add_control(
			'update_cart_button_name',
			[
				'label' 		=> __( 'Button Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Update Cart', 'bew-extras' ),
				'placeholder' 	=> __( 'Type your title here', 'bew-extras' ),
				'condition' 	=> [
					'update_cart_show_hide' => 'yes'
				],
			]
		);

		$this->add_control(
			'checkout_show_hide',
			[
				'label' 		=> __( 'Proceed to Checkout', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'separator'		=> 'before'
			]
		);

		$this->add_control(
			'checkout_button_name',
			[
				'label' 		=> __( 'Button Text', 'bew-extras' ),
				'type' 			=> Controls_Manager::TEXT,
				'default' 		=> __( 'Proceed to Checkout', 'bew-extras' ),
				'placeholder' 	=> __( 'Type your title here', 'bew-extras' ),
				'condition' 	=> [
					'checkout_show_hide' => 'yes'
				],
			]
		);

		$this->add_control(
			'subtotal_show_hide',
			[
				'label' 		=> __( 'Subtotal', 'bew-extras' ),
				'type' 			=> Controls_Manager::SWITCHER,
				'label_on' 		=> __( 'Show', 'elementor' ),
				'label_off' 	=> __( 'Hide', 'elementor' ),
				'return_value' 	=> 'yes',
				'default' 		=> '',
				'separator'		=> 'before'
			]
		);

		$this->end_controls_section();

        // Style tab
        $this->start_controls_section(
            'cart_table_loader_style_section',
            [
                'label' => __( 'Loading', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
				'condition' 	=> [
					'cart_table_general_loader' => 'yes',
					'cart_table_general_loader_type' => 'spinner',					
				],
            ]
        );
			
			$this->add_control(
				'cart_table_loader_bg_color',
				[
					'label' 		=> __( 'Background Color', 'elementor' ),
					'type' 			=> Controls_Manager::COLOR,
					'condition' 	=> [
						'cart_table_general_loader' => 'yes',
						'cart_table_general_loader_type' => 'spinner',					
					],
					'selectors' 	=> [
						'{{WRAPPER}}.elementor-widget-woo-cart-table.bew-cart-loader-type-spinner .blockUI.blockOverlay' => 'background-color: {{VALUE}} !important;',
					],
				]
			);
			
        $this->end_controls_section();
		
        $this->start_controls_section(
            'cart_title_style_section',
            [
                'label' => __( 'Cart Title', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
			$this->add_control(
				'title_text_color',
				[
					'label' => __( 'Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .bew-components-title' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'title_typography',
					'label'     => __( 'Typography', 'elementor' ),
					'selector'  => '{{WRAPPER}} .bew-components-title',
				]
			);
			
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
				'name' 		=> 'cart_title_background',
				'label' 	=> __( 'Background', 'elementor' ),
				'types' 	=> [ 'classic', 'gradient' ],
				'selector' 	=> '{{WRAPPER}} .bew-components-title',
				]
			);

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'title_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .bew-components-title',
                ]
            );
			
			 $this->add_responsive_control(
                'title_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-components-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-components-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .bew-components-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'title_text_align',
                [
                    'label'        => __( 'Alignment', 'elementor' ),
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
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .bew-components-title' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();
		
		 $this->start_controls_section(
            'cart_heading_style_section',
            [
                'label' => __( 'Table Heading', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
			$this->add_control(
				'heading_text_color',
				[
					'label' => __( 'Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element' => 'color: {{VALUE}}',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'heading_typography',
					'label'     => __( 'Typography', 'elementor' ),
					'selector'  => '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element',
				]
			);
			
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
				'name' 		=> 'heading_background',
				'label' 	=> __( 'Background', 'elementor' ),
				'types' 	=> [ 'classic', 'gradient' ],
				'selector' 	=> '{{WRAPPER}} table .bew-cart-items-titles',
				]
			);

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'heading_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element',
                ]
            );

            $this->add_responsive_control(
                'heading_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
			
            $this->add_responsive_control(
                'heading_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_text_align_name',
                [
                    'label'        => __( 'Name Alignment', 'bew-extras' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-name' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_text_align_price',
                [
                    'label'        => __( 'Price Alignment', 'bew-extras' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-price' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_text_align_quantity',
                [
                    'label'        => __( 'Quantity Alignment', 'bew-extras' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-quantity' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

            $this->add_responsive_control(
                'heading_text_align_subtotal',
                [
                    'label'        => __( 'Subtotal Alignment', 'bew-extras' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-subtotal' => 'text-align: {{VALUE}}',
                    ],
                ]
            );
	
        $this->end_controls_section();

        // Cart Table Content
        $this->start_controls_section(
            'cart_content_style_section',
            [
                'label' => __( 'Table Content', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            
			$this->add_group_control(
				Group_Control_Background::get_type(),
				[
				'name' 		=> 'table_cell_background',
				'label' 	=> __( 'Background', 'elementor' ),
				'types' 	=> [ 'classic', 'gradient' ],
				'selector' 	=> '{{WRAPPER}} .shop_table.cart .cart_item',
				]
			);
			
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'table_cell_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart .cart_item, {{WRAPPER}} .shop_table.cart .cart_item:last-child',
                ]
            );

            $this->add_responsive_control(
                'table_cell_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_cell_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',						
                    ],					
                ]
            );

            $this->add_responsive_control(
                'table_cell_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',						
                    ],
                ]
            );

            $this->add_responsive_control(
                'table_cell_text_align',
                [
                    'label'        => __( 'Alignment', 'elementor' ),
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
                    'default'      => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element' => 'text-align: {{VALUE}}',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Image
        $this->start_controls_section(
            'cart_product_image_style',
            [
                'label' => __( 'Product Image', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'product_image_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail a',
                ]
            );

            $this->add_responsive_control(
                'product_image_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail a , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail a img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );
			
			$this->add_responsive_control(
                'product_image_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );

            $this->add_responsive_control(
                'product_image_width',
                [
                    'label' => __( 'Image Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],                   
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail img' => 'width: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-thumbnail img' => 'max-width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

        $this->end_controls_section();

        // Product Title
        $this->start_controls_section(
            'cart_product_title_style',
            [
                'label' => __( 'Product Title', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'cart_item_style_tabs' );

                // Product Title Normal Style
                $this->start_controls_tab( 
                    'product_title_normal',
                    [
                        'label' => __( 'Normal', 'elementor' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_title_color',
                        [
                            'label' => __( 'Color', 'elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name a' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'cart_product_title_typography',
                            'label'     => __( 'Typography', 'elementor' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name a',
                        ]
                    );

                $this->end_controls_tab();

                // Product Title Hover Style
                $this->start_controls_tab( 
                    'product_title_hover',
                    [
                        'label' => __( 'Hover', 'elementor' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_product_title_hover_color',
                        [
                            'label' => __( 'Color', 'elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name:hover' => 'color: {{VALUE}}',
                                '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name a:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();

            $this->add_responsive_control(
                'product_title_height',
                [
                    'label' => __( 'Height', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 500,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],                   
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
			
			$this->add_responsive_control(
                'product_title_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );

			$this->add_responsive_control(
                'product_title_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-name' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );
			// Product Title variations
			$this->add_control(
				'cart_product_title_variation_heading',
				[
					'label' => __( 'Variations', 'bew-extras' ),
					'type' => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

            $this->start_controls_tabs( 'cart_product_title_variation_tabs' );

            $this->start_controls_tab( 
                'cart_product_title_variation_label',
                [
                    'label' => __( 'Label', 'elementor' ),
                ]
            );
			$this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_product_title_variation_label_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .product-name dl.variation dt',
                ]
            );			
            $this->add_control(
                'cart_product_title_variation_label_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .product-name dl.variation dt' => 'color: {{VALUE}}',
                     ],
                ]
            );

            $this->end_controls_tab();
			
            $this->start_controls_tab( 
                'cart_product_title_variation_value',
                [
                    'label' => __( 'Value', 'bew-extras' ),
                ]
            );
			$this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_product_title_variation_value_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .product-name dl.variation dd',
                ]
            );                    
            $this->add_control(
                'cart_product_title_variation_value_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .product-name dl.variation dd' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->end_controls_tab();
            $this->end_controls_tabs();
			
			$this->add_responsive_control(
                'cart_product_title_variation_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .product-name dl.variation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );

			$this->add_responsive_control(
                'cart_product_title_variation_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .product-name dl.variation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );
 
       $this->end_controls_section();

        // Product Price
        $this->start_controls_section(
            'cart_product_price_style',
            [
                'label' => __( 'Product Price', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'cart_product_price_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-price' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_product_price_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-price',
                ]
            );

			$this->add_responsive_control(
                'product_price_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );

			$this->add_responsive_control(
                'product_price_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-price' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
                    ],
                ]
            );
		
        $this->end_controls_section();
		
		// Product Quantity
        $this->start_controls_section(
            'cart_product_quantity_style',
            [
                'label' => __( 'Product Quantity', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
		
            $this->add_control(
                'cart_product_quantity_general',
                [
                    'label' => __( 'General', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING,                    
                ]
            );			

			$this->add_control(
				'cart_product_quantity_container_background_color',
				[
					'label' 		=> __( 'Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity' => 'background: {{VALUE}};',
					],					
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'cart_product_quantity_general_border',
					'label' => __( 'Border', 'elementor' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity',					
				]
			);

			$this->add_control(
				'cart_product_quantity_general_border_radius',
				[
					'label' => __( 'Border Radius', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_general_padding',
				[
					'label' => __( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],						
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_general_margin',
				[
					'label' => __( 'Margin', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],					
				]
			);

            $this->add_control(
                'cart_product_quantity_container',
                [
                    'label' => __( 'Qty Box', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING,
					'separator' => 'before',
                ]
            );

			$this->add_control(
				'cart_product_quantity_container_background_color',
				[
					'label' 		=> __( 'Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .bew-quantity' => 'background: {{VALUE}};',
					],					
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'cart_product_quantity_container_border',
					'label' => __( 'Border', 'elementor' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .bew-quantity',					
				]
			);

			$this->add_control(
				'cart_product_quantity_container_border_radius',
				[
					'label' => __( 'Border Radius', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .bew-quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_container_padding',
				[
					'label' => __( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .bew-quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
					],						
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_container_margin',
				[
					'label' => __( 'Margin', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .bew-quantity' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
					],					
				]
			);
			
			$this->add_control(
                'cart_product_quantity_elements',
                [
                    'label' => __( 'Quantity', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING, 
					'separator' => 'before',
                ]
            );
		
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 			=> 'cart_product_quantity_typo',
					'selector' 		=> '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus,
					{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value',
				]
			);
			
			$this->start_controls_tabs( 'tabs_cart_product_quantity_style' );

			$this->start_controls_tab(
				'tab_cart_product_quantity_normal',
				[
					'label' => __( 'Normal', 'elementor' ),
				]
			);
			
			$this->add_control(
				'cart_product_quantity_color',
				[
					'label' 		=> __( 'Quantity Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_control(
				'cart_product_quantity_background_color',
				[
					'label' 		=> __( 'Quantity Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty, {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'background: {{VALUE}};',
					],					
				]
			);

			$this->add_control(
				'cart_product_quantity_color_mp',
				[
					'label' 		=> __( '(-/+) Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_control(
				'cart_product_quantity_background_color_mp',
				[
					'label' 		=> __( '(-/+) Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => 'background: {{VALUE}};',
					],					
				]
			);
				
					
			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_cart_product_quantity_hover',
				[
					'label' => __( 'Hover', 'elementor' ),
				]
			);
			
			$this->add_control(
				'cart_product_quantity_color_hover',
				[
					'label' 		=> __( 'Quantity Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty:hover,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value:hover' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_control(
				'cart_product_quantity_background_color_hover',
				[
					'label' 		=> __( 'Quantity Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty:hover,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value:hover' => 'background-color: {{VALUE}};' ,						
					],					
				]
			);

			$this->add_control(
				'cart_product_quantity_color_hover_mp',
				[
					'label' 		=> __( '(-/+) Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus:hover , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus:hover' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_control(
				'cart_product_quantity_background_color_hover_mp',
				[
					'label' 		=> __( '(-/+) Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus:hover , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus:hover' => 'background-color: {{VALUE}};',						
					],					
				]
			);
				
			$this->add_control(
				'cart_product_quantity_hover_border_color',
				[
					'label' => __( 'Quantity Border Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'cart_product_quantity_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty:hover, {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value:hover' => 'border-color: {{VALUE}};' ,					
					],					
				]
			);
			
			$this->add_control(
				'cart_product_quantity_hover_border_color_mp',
				[
					'label' => __( '(-/+) Border Color', 'elementor' ),
					'type' => Controls_Manager::COLOR,
					'condition' => [
						'cart_product_quantity_border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty:hover,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus:hover , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus:hover,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value:hover' => 'border-color: {{VALUE}};' ,					
					],					
				]
			);

			$this->end_controls_tab();

			$this->end_controls_tabs();
						
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'cart_product_quantity_border',
					'label' => __( 'Border', 'elementor' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value',					
				]
			);
			
			$this->add_responsive_control(
			'cart_product_quantity_border_width_minus',
				[
				'label' => __( 'Border Width Minus Box', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'cart_product_quantity_border_border!' => '',
				],
			]
			);
			
			$this->add_responsive_control(
			'cart_product_quantity_border_width_plus',
				[
				'label' => __( 'Border Width Plus Box', 'bew-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'selectors' => [
					'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
				'condition' => [
					'cart_product_quantity_border_border!' => '',
				],
			]
			);
			
			$this->add_control(
				'cart_product_quantity_border_radius',
				[
					'label' => __( 'Border Radius', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					
				]
			);
			$this->add_responsive_control(
				'cart_product_quantity_size_width',
				[
					'label' => __( 'Quantity Box Width', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 6,
							'max' => 300,
						],
					],				
					'size_units' => [ 'px'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'width: {{SIZE}}{{UNIT}};',
					],
					
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_size_height',
				[
					'label' => __( 'Quantity Box Height', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 6,
							'max' => 300,
						],
					],				
					'size_units' => [ 'px'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty,{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'line-height: {{SIZE}}{{UNIT}};'
					],
					
				]
			);
			
			$this->add_responsive_control(
				'control_cart_product_quantity_width',
				[
					'label' => __( '(-/+) Box Width', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 6,
							'max' => 300,
						],
					],				
					'size_units' => [ 'px'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => 'width: {{SIZE}}{{UNIT}};',
					],
					
				]
			);
			
			$this->add_responsive_control(
				'control_cart_product_quantity_height',
				[
					'label' => __( '(-/+) Box Height', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 6,
							'max' => 300,
						],
					],				
					'size_units' => [ 'px'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => 'height: {{SIZE}}{{UNIT}}; min-height: {{SIZE}}{{UNIT}};',
					],
					
				]
			);
						
			$this->add_responsive_control(
				'cart_product_quantity_size',
				[
					'label' => __( 'Quantity (-/+) Size', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],				
					'size_units' => [ 'px'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => 'font-size: {{SIZE}}{{UNIT}};',
					],
					
				]
			);

			$this->add_responsive_control(
				'cart_product_quantity_height',
				[
					'label' => __( 'Quantity (-/+) Height', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],				
					'size_units' => [ 'px', 'em'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .minus , {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .plus' => ' line-height: {{SIZE}}{{UNIT}};',
					],
					
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_separation',
				[
					'label' => __( 'Quantity Box Separation', 'bew-extras' ),
					'type' => Controls_Manager::SLIDER,
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 100,
						],
					],				
					'size_units' => [ 'px'],	
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .bew-quantity .qty' => 'margin-left: {{SIZE}}{{UNIT}} !important ; margin-right: {{SIZE}}{{UNIT}} !important;',
					],
					
				]
			);
			
			$this->add_control(
                'cart_product_quantity_remove',
                [
                    'label' => __( 'Remove Link', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 			=> 'cart_product_quantity_remove_typo',
					'selector' 		=> '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty .remove-link',
				]
			);
			
			$this->add_control(
				'cart_product_quantity_remove_color',
				[
					'label' 		=> __( 'Text Color', 'elementor' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty .remove-link' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cart_product_quantity_remove_icon_color',
				[
					'label' 		=> __( 'Icon Color', 'elementor' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty .ti-trash' => 'color: {{VALUE}};',
					],
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_remove_padding',
				[
					'label' => __( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty, {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty .ti-trash' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
					],						
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_remove_margin',
				[
					'label' => __( 'Margin', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty, {{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-remove-qty .ti-trash' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
					],					
				]
			);
			//Quantity Text
			$this->add_control(
                'cart_product_quantity_text',
                [
                    'label' => __( 'Quantity Text', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING, 
					'separator' => 'before',
                ]
            );
			
            $this->start_controls_tabs( 'cart_product_quantity_text_tabs' );

            $this->start_controls_tab( 
                'cart_product_quantity_text_label',
                [
                    'label' => __( 'Label', 'elementor' ),
                ]
            );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 			=> 'cart_product_quantity_text_label_typo',
					'selector' 		=> '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .label',
					
				]
			);			
			$this->add_control(
				'cart_product_quantity_text_label_color',
				[
					'label' 		=> __( 'Color', 'elementor' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .label' => 'color: {{VALUE}};',
					],
				]
			);
			$this->end_controls_tab();
			
            $this->start_controls_tab( 
                'cart_product_quantity_text_value',
                [
                    'label' => __( 'Value', 'bew-extras' ),
                ]
            );
			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 			=> 'cart_product_quantity_text_value_typo',
					'selector' 		=> '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value',
					
				]
			);			
			$this->add_control(
				'cart_product_quantity_text_value_color',
				[
					'label' 		=> __( 'Color', 'elementor' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content .value' => 'color: {{VALUE}};',
					],
				]
			);
			
            $this->end_controls_tab();
			$this->end_controls_tabs();
			
			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' => 'cart_product_quantity_text_border',
					'label' => __( 'Border', 'elementor' ),
					'placeholder' => '1px',
					'default' => '1px',
					'selector' => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content',					
				]
			);
			$this->add_responsive_control(
				'cart_product_quantity_text_padding',
				[
					'label' => __( 'Padding', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],						
				]
			);
			
			$this->add_responsive_control(
				'cart_product_quantity_text_margin',
				[
					'label' => __( 'Margin', 'elementor' ),
					'type' => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-quantity .product-quantity-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],					
				]
			);			
        $this->end_controls_section();

        // Product Price Total
        $this->start_controls_section(
            'cart_product_subtotal_price_style',
            [
                'label' => __( 'Total Price', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_product_subtotal_price_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-subtotal .woocommerce-Price-amount.amount',
                ]
            );
            
			$this->add_control(
                'cart_product_subtotal_price_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-subtotal .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_subtotal_price_bg_color',
                [
                    'label' => __( 'Background', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-subtotal .woocommerce-Price-amount.amount' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
			
			$this->add_responsive_control(
                'cart_product_subtotal_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-subtotal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-subtotal' => 'padding-right: {{RIGHT}}{{UNIT}} !important;',
					],
                ]
            );	

			$this->add_responsive_control(
                'cart_product_subtotal_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-subtotal' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',						
					],
                ]
            );				

        $this->end_controls_section();
		
        // Product Remove
        $this->start_controls_section(
            'cart_product_remove_style',
            [
                'label' => __( 'Remove', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_product_remove_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a',
                ]
            );
			
            $this->add_control(
                'cart_product_remove_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a' => 'color: {{VALUE}} !important;',
                    ],
                ]
            );

			$this->add_control(
				'cart_product_remove_background_color',
				[
					'label' 		=> __( 'Background Color', 'bew-extras' ),
					'type' 			=> Controls_Manager::COLOR,
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a' => 'background: {{VALUE}};',
					],					
				]
			);
			
			$this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_product_remove_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a',
                ]
            );

            $this->add_responsive_control(
                'cart_product_remove_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_remove_width_container',
                [
                    'label' => __( 'Remove Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],                   
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a' => 'width: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_remove_height_container',
                [
                    'label' => __( 'Remove Height', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],                   
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a' => 'height: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_remove_size_icon',
                [
                    'label' => __( 'Remove Icon Size', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 100,
                        ],
                    ],                   
                    'selectors' => [
						'{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove a .ti-close' => 'font-size: {{SIZE}}{{UNIT}};',
                    ],
                ]
            );
			
			$this->add_responsive_control(
                'cart_product_remove_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-remove' => 'padding-right: {{RIGHT}}{{UNIT}};',
					],
                ]
            );

			$this->add_responsive_control(
                'cart_product_remove_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-remove' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',	
					],
                ]
            );				

        $this->end_controls_section();

        // Product Custom Data
        $this->start_controls_section(
            'cart_product_custom_data_style',
            [
                'label' => __( 'Custom Data', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_product_custom_data_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-customdata',
                ]
            );
            
			$this->add_control(
                'cart_product_custom_data_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-customdata' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_product_custom_data_bg_color',
                [
                    'label' => __( 'Background', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-customdata' => 'background-color: {{VALUE}}',
                    ],
                ]
            );
			
			$this->add_responsive_control(
                'cart_product_custom_data_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-customdata' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-titles .product-element.product-subtotal' => 'padding-right: {{RIGHT}}{{UNIT}} !important;',
					],
                ]
            );	

			$this->add_responsive_control(
                'cart_product_custom_data_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .cart_item .product-element.product-customdata' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; min-height: auto !important;',						
					],
                ]
            );				

        $this->end_controls_section();

        // Cart Actions
        $this->start_controls_section(
            'cart_product_actions_style',
            [
                'label' => __( 'Cart Actions', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
            $this->add_control(
                'cart_product_actions_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .cart-actions' => 'color: {{VALUE}}',
                    ],
                ]
            );

			$this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_product_actions_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .cart-actions',
                ]
            );

            $this->add_responsive_control(
                'cart_product_actions_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .cart-actions' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

			$this->add_responsive_control(
                'cart_product_actions_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .cart-actions .actions' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
                ]
            );

			$this->add_responsive_control(
                'cart_product_actions_margin',
                [
                    'label' => __( 'Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .cart-actions .actions' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',	
					],
                ]
            );				
			
			$this->add_responsive_control(
                'product_actions_text_align',
                [
                    'label'        => __( 'Alignment', 'elementor' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .cart-actions' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );

        $this->end_controls_section();

        // Update cart
        $this->start_controls_section(
            'cart_update_button_style',
            [
                'label' => __( 'Update Cart Button', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

            $this->start_controls_tabs( 'cart_update_style_tabs' );

                // Product Title Normal Style
                $this->start_controls_tab( 
                    'cart_update_button_normal',
                    [
                        'label' => __( 'Normal', 'elementor' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_update_button_color',
                        [
                            'label' => __( 'Color', 'elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_update_button_bg_color',
                        [
                            'label' => __( 'Background Color', 'elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button' => 'background-color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Typography::get_type(),
                        [
                            'name'      => 'cart_update_button_typography',
                            'label'     => __( 'Typography', 'elementor' ),
                            'selector'  => '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button',
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_update_button_border',
                            'label' => __( 'Border', 'elementor' ),
							'selector' => '{{WRAPPER}} .woocommerce .shop_table.cart .bew-cart-items-content .actions .bew-update-cart-button.button, 
								   .woocommerce-page #content {{WRAPPER}} .cart .bew-cart-items-content .actions .bew-update-cart-button.button, 
								   .woocommerce-page {{WRAPPER}} .cart .bew-cart-items-content .actions .bew-update-cart-button.button',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_border_radius',
                        [
                            'label' => __( 'Border Radius', 'elementor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

				$this->end_controls_tab();

				// Product Title Hover Style
				$this->start_controls_tab( 
                    'cart_update_button_hover',
                    [
                        'label' => __( 'Hover', 'elementor' ),
                    ]
                );
                    
                    $this->add_control(
                        'cart_update_button_hover_color',
                        [
                            'label' => __( 'Color', 'elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button:hover' => 'color: {{VALUE}}',
                            ],
                        ]
                    );

                    $this->add_control(
                        'cart_update_button_hover_bg_color',
                        [
                            'label' => __( 'Background Color', 'elementor' ),
                            'type' => Controls_Manager::COLOR,
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                            ],
                        ]
                    );

                    $this->add_group_control(
                        Group_Control_Border::get_type(),
                        [
                            'name' => 'cart_update_button_hover_border',
                            'label' => __( 'Border', 'elementor' ),
                            'selector' => '{{WRAPPER}} .woocommerce .shop_table.cart .bew-cart-items-content .actions .bew-update-cart-button.button:hover, 
								   .woocommerce-page #content {{WRAPPER}} .cart .bew-cart-items-content .actions .bew-update-cart-button.button:hover, 
								   .woocommerce-page {{WRAPPER}} .cart .bew-cart-items-content .actions .bew-update-cart-button.button:hover',
                        ]
                    );

                    $this->add_responsive_control(
                        'cart_update_button_hover_border_radius',
                        [
                            'label' => __( 'Border Radius', 'elementor' ),
                            'type' => Controls_Manager::DIMENSIONS,
                            'size_units' => [ 'px', 'em', '%' ],
                            'selectors' => [
                                '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                            ],
                        ]
                    );

                $this->end_controls_tab();

            $this->end_controls_tabs();
			
			$this->add_responsive_control(
                'cart_update_button_padding',
                    [
                        'label' => __( 'Padding', 'elementor' ),
                        'type' => Controls_Manager::DIMENSIONS,
                        'size_units' => [ 'px', 'em', '%' ],
                        'selectors' => [
                            '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                        ],
                    ]
            );
					
			$this->add_responsive_control(
				'cart_update_button_margin',
				[
					'label' 		=> __( 'Margin', 'elementor' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%', 'em' ],
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions > .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

        $this->end_controls_section();

        // Apply coupon
        $this->start_controls_section(
            'cart_coupon_style',
            [
                'label' => __( 'Apply Coupon', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );


            $this->add_control(
                'cart_coupon_button_heading',
                [
                    'label' => __( 'Button', 'elementor' ),
                    'type' => Controls_Manager::HEADING,                    
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_coupon_button_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button',
                ]
            );

            $this->add_control(
                'cart_coupon_button_color',
                [
                    'label' => __( 'Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_button_bg_color',
                [
                    'label' => __( 'Background Color', 'elementor' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_button_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .woocommerce .shop_table.cart .bew-cart-items-content .actions .coupon .button, 
								   .woocommerce-page #content {{WRAPPER}} .cart .bew-cart-items-content .actions .coupon .button, 
								   .woocommerce-page {{WRAPPER}} .cart .bew-cart-items-content .actions .coupon .button',
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_button_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );


            $this->add_control(
                'cart_coupon_button_hover_color',
                [
                    'label' => __( 'Hover Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button:hover' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_button_hover_bg_color',
                [
                    'label' => __( 'Hover Background Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_hover_button_border',
                    'label' => __( 'Border', 'elementor' ),                    
					'selector' => '{{WRAPPER}} .woocommerce .shop_table.cart .bew-cart-items-content .actions .coupon .button:hover, 
								   .woocommerce-page #content {{WRAPPER}} .cart .bew-cart-items-content .actions .coupon .button:hover, 
								   .woocommerce-page {{WRAPPER}} .cart .bew-cart-items-content .actions .coupon .button:hover',
                ]
            );
			
			$this->add_responsive_control(
				'cart_coupon_button_padding',
				[
					'label' 		=> __( 'Padding', 'elementor' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%', 'em' ],
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
					'separator' => 'before'
				]
			);

			$this->add_responsive_control(
				'cart_coupon_button_margin',
				[
					'label' 		=> __( 'Margin', 'elementor' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%', 'em' ],
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
					],
				]
			);

			$this->add_responsive_control(
                'cart_coupon_button_align',
                [
                    'label'        => __( 'Alignment', 'elementor' ),
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
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon .button' => 'text-align: {{VALUE}} !important',
                    ],
                ]
            );

            $this->add_control(
                'cart_coupon_inputbox_heading',
                [
                    'label' => __( 'Input Box', 'bew-extras' ),
                    'type' => Controls_Manager::HEADING,
                    'separator' => 'before',
                ]
            );

            $this->add_control(
                'cart_coupon_inputbox_color',
                [
                    'label' => __( 'Input Box Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                [
                    'name'      => 'cart_coupon_inputbox_typography',
                    'label'     => __( 'Typography', 'elementor' ),
                    'selector'  => '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text',
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'cart_coupon_inputbox_border',
                    'label' => __( 'Border', 'elementor' ),
                    'selector' => '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text',
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_border_radius',
                [
                    'label' => __( 'Border Radius', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'cart_coupon_inputbox_padding',
                [
                    'label' => __( 'Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );
			
			$this->add_responsive_control(
				'cart_coupon_inputbox_margin',
				[
					'label' 		=> __( 'Margin', 'elementor' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%', 'em' ],
					'selectors' 	=> [
						'{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

            $this->add_control(
                'cart_coupon_inputbox_width',
                [
                    'label' => __( 'Input Box Width', 'bew-extras' ),
                    'type' => Controls_Manager::SLIDER,
                    'size_units' => [ 'px', '%' ],
                    'range' => [
                        'px' => [
                            'min' => 10,
                            'max' => 200,
                            'step' => 1,
                        ],
                        '%' => [
                            'min' => 0,
                            'max' => 200,
                        ],
                    ],                    
                    'selectors' => [
                        '{{WRAPPER}} .shop_table.cart .bew-cart-items-content .actions .coupon input.input-text' => 'width: {{SIZE}}{{UNIT}} !important;',
                    ],
                ]
            );

        $this->end_controls_section();
		
		// Checkout Button
		$this->start_controls_section(
			'section_checkout_button',
			[
				'label' => __( 'Checkout Button', 'bew-extras' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'checkout_button_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button' => 'color: {{VALUE}} !important',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'checkout_button_typography',
				'label'     => __( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button',
			]
		);

		$this->add_control(
			'checkout_button_bg',
			[
				'label'     => __( 'Background', 'elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button' => 'background: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' 		=> 'checkout_button_border',
				'label' 	=> __( 'Border', 'elementor' ),
				'selector' => '{{WRAPPER}} .woocommerce .shop_table.cart .bew-cart-items-content .actions.actions .bew-proceed-to-checkout .button.checkout-button, 
								   .woocommerce-page #content {{WRAPPER}} .cart .bew-cart-items-content .actions.actions .bew-proceed-to-checkout .button.checkout-button, 
								   .woocommerce-page {{WRAPPER}} .cart .bew-cart-items-content div.actions.actions .bew-proceed-to-checkout .button.checkout-button',
			]
		);

		$this->add_responsive_control(
			'checkout_button_border_radius',
			[
				'label' 		=> __( 'Border Radius', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' 		=> 'checkout_button_box_shadow',
				'label' 	=> __( 'Box Shadow', 'elementor' ),
				'selector' 	=> '{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button',
			]
		);

		$this->add_responsive_control(
			'checkout_button_padding',
			[
				'label' 		=> __( 'Padding', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'checkout_button_margin',
			[
				'label' 		=> __( 'Margin', 'elementor' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-proceed-to-checkout .button.checkout-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();		
		
    }
	
    protected function render() {
		
		$settings  = $this->get_settings_for_display();
		
		$cart_title_text_icon      =  $settings['cart_title_text_icon'];
		$cart_title_text_before    =  $settings['cart_title_text_before'];
		$cart_title_text_after     =  $settings['cart_title_text_after'];
		$coupon_show_hide          =  $settings['coupon_show_hide'];
		$coupon_button_name        =  $settings['coupon_button_name'];
		$coupon_button_placeholder =  $settings['coupon_button_placeholder'];
		$update_cart_show_hide     =  $settings['update_cart_show_hide'];
		$update_cart_button_name   =  $settings['update_cart_button_name'];
		$checkout_show_hide        =  $settings['checkout_show_hide'];
		$subtotal_show_hide        =  $settings['subtotal_show_hide'];
		$checkout_button_name      =  $settings['checkout_button_name'];		
		$ajax_delay_qty      	   =  $settings['ajax_delay_qty'];
		$cart_table_loader         =  $settings['cart_table_general_loader'];
		
        $cartitem = ( isset( $settings['table_item_list'] ) ? $settings['table_item_list'] : array() );
		$cart_custom_data          = '';
		//echo var_dump($settings['table_item_list']);
		// Wrap classes
		$wrap_classes 	= array( 'bew-woo-cart-table woocommerce-cart-form__contents');
		
		$wrap_classes[]  = 'bew-coupon-show-' . $coupon_show_hide;
		$wrap_classes[]  = 'bew-update-cart-show-' . $update_cart_show_hide;
		$wrap_classes[]  = 'bew-checkout-show-'. $checkout_show_hide;
		$wrap_classes[]  = 'bew-subtotal-show-'. $subtotal_show_hide;
		
		// Check if woocommerce booking is active
		if (class_exists( 'WC_Bookings' ) ) {
			$wrap_classes[]  = 'woocommerce-booking';
		}
		
		// Get Dynamic tag for Custom Field on Cart table.
		$param = $this->get_settings( 'table_item_list' );
		
		foreach (  $param as $item ) {
			if ($item['table_items'] == "customadd") {
				//echo '<dd>' . $item['table_custom_content'] . '</dd>';
				//echo '<dd>' . $item['__dynamic__']['table_custom_content'] . '</dd>';
				
				$string = $item['__dynamic__']['table_custom_content'] ?? null;
				$array = explode(" ", $string);
				$array2 = $array[2] ?? null;
				$array3 = explode("=", $array2);
				
				$newstring = substr(($array3[1] ?? null), 0,  -5);
				$cart_custom_data = substr($newstring, 1 );				
			}				
		}
		
		
				
		$wrap_classes = implode( ' ', $wrap_classes );
		
        ?>
		<div class="<?php echo $wrap_classes; ?>"> 
		<?php
		    if( class_exists('\WC_Shortcode_Cart') ){
				Bew_Shortcode_Cart::output( $atts = array(), $cartitem,$cart_title_text_icon, $cart_title_text_before, $cart_title_text_after, $coupon_button_name, $coupon_button_placeholder, $update_cart_button_name, $checkout_button_name, $cart_table_loader,$cart_custom_data );
			}
		?>
		</div>			
		<?php	
				
		// send var for ajax call
		wp_localize_script( 'bew-checkout', 'bewcart_vars', array(
				'ajax_url'         		  => admin_url( 'admin-ajax.php' ),
				'cart_update'         	  => __( 'Cart updated.', 'woocommerce' ),
				'qty_delay'         	  => $ajax_delay_qty,
			)
		);
    }
	
	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon' );
	}
}

	/**
	 * Cart Shortcode
	 *
	 * Used on the cart page, the cart shortcode displays the cart contents and interface for coupon codes and other cart bits and pieces.
	 *
	 * @package WooCommerce/Shortcodes/Cart
	 * @version 2.3.0
	 */
if( class_exists('\WC_Shortcode_Cart') ){
	class Bew_Shortcode_Cart extends \WC_Shortcode_Cart{
		/**
		 * Output the cart shortcode.
		 */
		public static function output( $atts = '', $cartitem = [], $cart_title_text_icon = [], $cart_title_text_before = '', $cart_title_text_after = '', $coupon_button_name = '', $coupon_button_placeholder = '', $update_cart_button_name = '', $checkout_button_name = '', $cart_table_loader = '' , $cart_custom_data = '' ) {
						
			// Constants.
			wc_maybe_define_constant( 'WOOCOMMERCE_CART', true );

			$atts        = shortcode_atts( array(), $atts, 'woocommerce_cart' );
			$nonce_value = wc_get_var( $_REQUEST['woocommerce-shipping-calculator-nonce'], wc_get_var( $_REQUEST['_wpnonce'], '' ) ); // @codingStandardsIgnoreLine.

			// Update Shipping. Nonce check uses new value and old value (woocommerce-cart). @todo remove in 4.0.
			if ( ! empty( $_POST['calc_shipping'] ) && ( wp_verify_nonce( $nonce_value, 'woocommerce-shipping-calculator' ) || wp_verify_nonce( $nonce_value, 'woocommerce-cart' ) ) ) { // WPCS: input var ok.
				self::calculate_shipping();

				// Also calc totals before we check items so subtotals etc are up to date.
				\WC()->cart->calculate_totals();
			}

			// Check cart items are valid.
			do_action( 'woocommerce_check_cart_items' );

			// Calc totals.
			\WC()->cart->calculate_totals();			
						
			if ( \WC()->cart->is_empty() ) {
				//wc_get_template( 'cart/cart-empty.php');
			} else {
				if( file_exists( BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-cart-table.php' ) ){
					include BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-cart-table.php';
				}
			}
			
		}
	}
}
