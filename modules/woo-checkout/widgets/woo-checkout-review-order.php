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

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Review_Order extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-review-order';
	}

	public function get_title() {
		return __( 'Checkout Review Order', 'bew-extras' );
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
			'woo_checkout_order_review',
			[
				'label' => __( 'Review Order', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'woo_checkout_order_review_layout', [
				'label' => __( 'Review Order Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'table',
				'options' => [
					'table' => 'Table',
					'collapse' => 'Collapse',
					
				],				
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_order_review_heading',
			[
				'label' => __( 'Review Order Heading', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'woo_checkout_order_review_heading_show',
            [
                'label'         => __( 'Review Order Heading', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',				
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_collapse_heading_show',
            [
                'label'         => __( 'Show Heading on Desktop', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [
					'woo_checkout_order_review_layout' => 'collapse'
                ],
            ]
        );

		$this->add_control(
            'woo_checkout_order_review_collapse_heading_closed_mobile',
            [
                'label'         => __( 'Initially Closed on Mobile', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [                    
					'woo_checkout_order_review_heading_show' => 'yes'
                ],				
            ]
        );

		$this->add_control(
		    'woo_checkout_order_review_heading_text',
		    [
		        'label' 		=> __( 'Text', 'woocommerce' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Your order', 'woocommerce' ) ,
                'condition' => [
                    'woo_checkout_order_review_heading_show' => 'yes',
					'woo_checkout_order_review_layout' => 'table'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
		    'woo_checkout_order_review_heading_show_text',
		    [
		        'label' 		=> __( 'Text', 'woocommerce' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Show order summary', 'woocommerce' ) ,
                'condition' => [
                    'woo_checkout_order_review_heading_show' => 'yes',
					'woo_checkout_order_review_layout' => 'collapse'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
		    'woo_checkout_order_review_heading_hide_text',
		    [
		        'label' 		=> __( 'Text', 'woocommerce' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Hide order summary', 'woocommerce' ) ,
                'condition' => [
                    'woo_checkout_order_review_heading_show' => 'yes',
					'woo_checkout_order_review_layout' => 'collapse'
                ],
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);
		
		$this->add_control(
            'woo_checkout_order_review_heading_arrow_show',
            [
                'label'         => __( 'Show/Hide Arrow Title', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'condition' => [
                    'woo_checkout_order_review_heading_show' => 'yes',
					'woo_checkout_order_review_layout' => 'table'
                ],
            ]
        );

		$this->add_control(
			'woo_checkout_order_review_heading_tag',
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
                    'woo_checkout_order_review_heading_show' => 'yes',
					'woo_checkout_order_review_layout' => 'table'
                ],
			]
		);

		$this->add_responsive_control(
            'woo_checkout_order_review_heading_alignment',
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
                    'woo_checkout_order_review_heading_show' => 'yes',
					'woo_checkout_order_review_layout' => 'table'
                ],
                'selectors' => [
                    '{{WRAPPER}} .bew-order-review-collapse .bew-components-totals-item__value' => 'text-align: {{VALUE}};',
					'{{WRAPPER}} .bew-order-review-title #bew-order-summary' => 'text-align: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
            'woo_checkout_order_review_total_price_show',
            [
                'label'         => __( 'Show/Hide Total Price', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
            ]
        );

		$this->end_controls_section();

		$this->start_controls_section(
			'woo_checkout_order_review_content',
			[
				'label' => __( 'Review Order Content', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_checkout_order_review_order_titles',
			[
				'label'     => __( 'Order Titles', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
			]
		);
				
		$this->add_control(
            'woo_checkout_order_review_order_titles_show',
            [
                'label'         => __( 'Show/Hide Titles', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
            ]
        );

		$this->add_control(
		    'woo_checkout_order_review_table_th1',
		    [
		        'label' 		=> __( 'Product Column Heading', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Product', 'woocommerce' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ],
				'condition' => [
                    'woo_checkout_order_review_heading_show' => 'yes'
                ],
		    ]
		);

		$this->add_control(
		    'woo_checkout_order_review_table_th2',
		    [
		        'label' 		=> __( 'Price Column Heading', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Subtotal', 'woocommerce' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ],
				'condition' => [
                    'woo_checkout_order_review_heading_show' => 'yes'
                ],
		    ]
		);
		
		$this->add_control(
			'woo_checkout_order_review_list',
			[
				'label'     => __( 'Order Products', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);

		$this->add_control(
			'woo_checkout_order_review_list_layout', [
				'label' => __( 'Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'inline',
				'options' => [
					'inline' => 'Inline',
					'stacked' => 'Stacked',
				],
			]
		);
		
		$this->add_control(
            'woo_checkout_order_review_collapse',
            [
                'label'         => __( 'Collapse', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-products-collapse-',
				'condition' => [
					'woo_checkout_order_review_layout' => 'table'
                ],
				
            ]
        );

		$this->add_control(
            'woo_checkout_order_review_collapse_closed',
            [
                'label'         => __( 'Initially Closed', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [
                    'woo_checkout_order_review_layout' => 'table',
					'woo_checkout_order_review_collapse' => 'yes'
                ],				
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_collapse_mobile',
            [
                'label'         => __( 'Open on Mobile', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'bew-extras' ),
                'label_off'     => __( 'Off', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [
                    'woo_checkout_order_review_layout' => 'table',
					'woo_checkout_order_review_collapse' => 'yes'
                ],
				'prefix_class' => 'order-review-collapse-mobile-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_images_show',
            [
                'label'         => __( 'Show Images', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-images-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_qty_show',
            [
                'label'         => __( 'Show Quantity', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-qty-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_remove_show',
            [
                'label'         => __( 'Show Remove', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'order-review-remove-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_subtotal_show',
            [
                'label'         => __( 'Show Subtotal', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-subtotal-',
            ]
        );
		
		$this->add_control(
			'woo_checkout_order_review_shipping',
			[
				'label'     => __( 'Shipping', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_control(
            'woo_checkout_order_review_shipping_show',
            [
                'label'         => __( 'Show Shipping', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-shipping-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_shipping_description_show',
            [
                'label'         => __( 'Show Shipping Description', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'condition' => [
                    'woo_checkout_order_review_shipping_show' => 'yes'
                ],
				'prefix_class' => 'order-review-shipping-description-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_shipping_options_show',
            [
                'label'         => __( 'Show Shipping Options', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'condition' => [
                    'woo_checkout_order_review_shipping_show' => 'yes'
                ],
            ]
        );

		$this->add_control(
		    'woo_checkout_order_review_shipping_label_text',
		    [
		        'label' 		=> __( 'Label Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Shipping', 'bew-extras' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ],
				'condition' => [
                    'woo_checkout_order_review_shipping_show' => 'yes'
                ],
		    ]
		);
				
		$this->add_control(
			'woo_checkout_order_review_coupon',
			[
				'label'     => __( 'Order Coupon', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_control(
            'woo_checkout_order_review_coupon_show',
            [
                'label'         => __( 'Show/Hide Coupon', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-coupon-',
            ]
        );
		
		$this->add_control(
			'woo_checkout_order_review_coupon_layout', [
				'label' => __( 'Coupon Layout', 'bew-extras' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'collapse',
				'options' => [
					'collapse' => 'Collapse',
					'input' => 'Input',
				],
				'prefix_class' => 'order-review-coupon-layout-',
			]
		);
		
		$this->add_control(
		    'woo_checkout_order_review_coupon_title_text',
		    [
		        'label' 		=> __( 'Title Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Coupon Code?', 'bew-extras' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ],
				'condition' => [
                    'woo_checkout_order_review_coupon_layout' => 'collapse'
                ],
		    ]
		);

		$this->add_control(
		    'woo_checkout_order_review_coupon_button_text',
		    [
		        'label' 		=> __( 'Button Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Apply', 'woocommerce' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ],
		    ]
		);

		$this->add_control(
            'woo_checkout_order_review_coupon_button_arrow',
            [
                'label'         => __( 'Arrow on Mobile', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-coupon-arrow-',
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_coupon_label_show',
            [
                'label'         => __( 'Show/Hide Label', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-coupon-label-',
            ]
        );
		
		$this->add_control(
		    'woo_checkout_order_review_coupon_label_text',
		    [
		        'label' 		=> __( 'Label Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Enter Code', 'bew-extras' ) ,
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ],
				'condition' => [
                    'woo_checkout_order_review_coupon_label_show' => 'yes'
                ],
		    ]
		);

		$this->add_control(
            'woo_checkout_order_review_coupon_label_layout',
            [
                'label'         => __( 'Inside Label', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'condition' => [
                    'woo_checkout_order_review_coupon_label_show' => 'yes'
                ],
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_review_coupon_order',
            [
                'label'         => __( 'Move Coupon to bottom', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Yes', 'bew-extras' ),
                'label_off'     => __( 'No', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'move-coupon-to-bottom-',
            ]
        );		
		
		$this->add_control(
			'woo_checkout_order_review_totals',
			[
				'label'     => __( 'Order Totals', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_control(
            'woo_checkout_order_review_totals_show',
            [
                'label'         => __( 'Show/Hide Totals', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'order-review-totals-',
            ]
        );	

		$this->end_controls_section();

		//Section general style
		$this->start_controls_section(
			'woo_checkout_order_review_general_style',
			[
				'label' => __( 'General', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'woo_checkout_order_review_general_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-checkout-review-order' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_table_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-review-order-content .bew-woocommerce-checkout-review-order-table',
		    ]
		);

		$this->add_responsive_control(
			'woo_checkout_order_review_general_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout-review-order' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_review_general_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-checkout-review-order' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section header style
		$this->start_controls_section(
			'woo_checkout_order_review_title_style',
			[
				'label' => __( 'Review Order Heading', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,               
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_review_title_typography',
				'label' 	=> __( 'Toggle Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .order-summary-toggle-text',
				'condition' => [
					'woo_checkout_order_review_layout' => 'collapse'
                ],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_review_toggle_typography',
				'label' 	=> __( 'Title Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-order-review-title',
				'condition' => [
					'woo_checkout_order_review_layout' => 'table'
                ],
				
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_review_title_total_typography',
				'label' 	=> __( 'Total Price Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-order-review-collapse .total-title .amount',
				'condition' => [
					'woo_checkout_order_review_layout' => 'collapse'
                ],
			]
		);
		
        $this->add_control(
			'woo_checkout_order_review_title_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-checkout-review-order .bew-review-order-heading' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_review_title_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-review-order-heading',
		    ]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_review_title_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-review-order-heading' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_review_title_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-review-order-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		//section content style
		$this->start_controls_section(
			'woo_checkout_order_review_content_style',
			[
				'label' => __( 'Review Order Content', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,                
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_review_content_typography',
				'label' 	=> __( 'Title Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} #bew-checkout-review-order .bew-review-order-content',
			]
		);
		
        $this->add_control(
			'woo_checkout_order_review_title_content_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} #bew-checkout-review-order .bew-review-order-content' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_review_content_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} #bew-checkout-review-order .bew-review-order-content',
		    ]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_review_content_padding',
			[
				'label' => __( 'Padding', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #bew-checkout-review-order .bew-review-order-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_review_content_margin',
			[
				'label' => __( 'Margin', 'briefcase-extras' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} #bew-checkout-review-order .bew-review-order-content' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		//Table Header control		
		$this->start_controls_section(
			'woo_checkout_order_table_header_style',
			[
				'label' => __( 'Review Order Products Titles', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_thead_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-review-order-content .product-titles',
			]
		);


        $this->add_control(
			'woo_checkout_order_thead_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-review-order-content .product-titles' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_control(
			'woo_checkout_order_thead_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-review-order-content .product-titles' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
            'woo_checkout_order_thead_alignment',
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
                    '{{WRAPPER}} .bew-review-order-content .product-titles' => 'text-align: {{VALUE}};',
                ],
            ]
        );

        $this->add_responsive_control(
        	'woo_checkout_order_thead_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-review-order-content .product-titles' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->end_controls_section();

		/**
		 * Order Review List
		 */
		$this->start_controls_section(
			'woo_checkout_order_list_style',
			[
				'label' => __( 'Review Order Products Content', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'woo_checkout_order_products_general',
			[
				'label'     => __( 'Product List', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-summary-item' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_products_list_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-components-order-summary-item',
		    ]
		);
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-order-summary-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-order-summary-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_control(
			'woo_checkout_order_products_images',
			[
				'label'     => __( 'Images', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_img_size',
			[
				'label' 		=> __( 'Image width', 'bew-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' 	=> 10,
						'max' 	=> 500,
						'step' 	=> 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-summary-item__image > img, {{WRAPPER}} .bew-components-order-summary-item__image' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_products_list_img_height',
			[
				'label' 		=> __( 'Image Height', 'bew-extras' ),
				'type' 			=> Controls_Manager::SLIDER,
				'size_units' 	=> [ 'px', '%' ],
				'range' => [
					'px' => [
						'min' 	=> 10,
						'max' 	=> 500,
						'step' 	=> 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],				
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-summary-item__image > img, {{WRAPPER}} .bew-components-order-summary-item__image' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_products_list_img_border_type',
		        'label'     => __( 'Image Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-components-order-summary-item__image > img',
		    ]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_img_border_radius',
			[
				'label' => __( 'Image Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-order-summary-item__image > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_img_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-order-summary-item__image > img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_control(
			'woo_checkout_order_products_qty',
			[
				'label'     => __( 'Quantity', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_qty_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-order-summary-item__quantity',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_qty_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-summary-item__quantity' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_qty_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-summary-item__quantity' => 'background-color: {{VALUE}}',
				],
			]
		);
				
		$this->add_control(
			'woo_checkout_order_products_list_qty_size',
			[
				'label' 		=> __( 'Size', 'bew-extras' ),
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
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-summary-item__quantity' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_products_list_qty_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-components-order-summary-item__quantity',
		    ]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_qty_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-components-order-summary-item__quantity' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_qty_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-order-summary-item__quantity' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );		

		$this->add_control(
			'woo_checkout_order_products_description',
			[
				'label'     => __( 'Names', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_desc_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-product-name',
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_desc_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-product-name' => 'color: {{VALUE}}',
				],
			]
		);
		
        $this->add_responsive_control(
            'woo_checkout_order_products_name_width',
            [
                'label' => __( 'Name Width', 'briefcase-extras' ),
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
                    '{{WRAPPER}} .bew-components-product-name' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );

		$this->add_responsive_control(
        	'woo_checkout_order_products_name_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-product-name' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_responsive_control(
            'woo_checkout_order_products_name_alignment',
            [
                'label' 	   => __( 'Alignment', 'elementor' ),
                'type' 		   => Controls_Manager::CHOOSE,
                'options' 	   => [
					'flex-start' 		=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'flex-end' 	=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
                'default' 	=> 'flex-start',
                'toggle' 	=> true,
                'selectors' => [
                    '{{WRAPPER}} .bew-components-product-name' => 'align-self: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_control(
			'woo_checkout_order_products_price',
			[
				'label'     => __( 'Price', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_price_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-product-price .woocommerce-Price-amount.amount',
			]
		);
        
		
		$this->add_control(
			'woo_checkout_order_products_list_price_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-product-price .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
        	'woo_checkout_order_products_list_price_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-product-price' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_responsive_control(
            'woo_checkout_order_products_list_price_alignment',
            [
                'label' 	   => __( 'Alignment', 'elementor' ),
                'type' 		   => Controls_Manager::CHOOSE,
                'options' 	   => [
					'flex-start' 		=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'flex-end' 	=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
				],
                'default' 	=> 'flex-start',
                'toggle' 	=> true,
                'selectors' => [
                    '{{WRAPPER}} .bew-components-product-price' => 'align-self: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_control(
			'woo_checkout_order_products_subtotal',
			[
				'label'     => __( 'Subtotal', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_subtotal_typography_label',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-order-subtotal .bew-components-totals-item__label , {{WRAPPER}} .bew-components-order-subtotal .woocommerce-Price-amount.amount',
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_subtotal_typography_amount',
				'label' 	=> __( 'Amount Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-order-subtotal .bew-components-totals-item__value .woocommerce-Price-amount.amount',							]
		);

        $this->add_control(
			'woo_checkout_order_products_list_subtotal_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-subtotal .bew-components-totals-item__label , {{WRAPPER}} bew-components-order-subtotal .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_products_list_subtotal_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-order-subtotal' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_subtotal_padding',
        	[
        		'label' => __( 'Subtotal Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-order-subtotal' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_control(
			'woo_checkout_order_products_discount',
			[
				'label'     => __( 'Discount', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_title_discount_typography',
				'label' 	=> __( 'Title Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-discount .bew-components-totals-item__label',
				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_price_discount_typography',
				'label' 	=> __( 'Amount Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-discount .woocommerce-Price-amount.amount',				
			]
		);

        $this->add_control(
			'woo_checkout_order_products_list_discount_color',
			[
				'label'     => __( 'Amount Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-discount .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_discount_code_color',
			[
				'label'     => __( 'Code Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-discount .bew-components-chip' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_list_discount_code_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-discount .bew-components-chip' => 'background-color: {{VALUE}}',
				],
			]
		);
		
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'woo_checkout_order_products_list_discount_border',
                'label' => __( 'Border', 'briefcase-extras' ),
                'selector' => '{{WRAPPER}} .bew-components-totals-discount',
            ]
        );
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_discount_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-discount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_responsive_control(
        	'woo_checkout_order_products_list_discount_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-discount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_control(
			'woo_checkout_order_products_shipping',
			[
				'label'     => __( 'Shipping', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_shipping_typography_label',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-shipping .bew-components-totals-item__label , {{WRAPPER}} .bew-components-totals-shipping .woocommerce-Price-amount.amount',
				'separator' => 'before',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_shipping_typography_amount',
				'label' 	=> __( 'Amount Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-shipping .bew-components-totals-item__value .woocommerce-Price-amount.amount',				
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_shipping_des_typography',
				'label' 	=> __( 'Description Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-item__description',			
			]
		);

        $this->add_control(
			'woo_checkout_order_products_list_shipping_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-shipping .bew-components-totals-item__label , {{WRAPPER}} .bew-components-totals-shipping .woocommerce-Price-amount.amount' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_products_list_shipping_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-shipping' => 'background-color: {{VALUE}}',
				],
			]
		);
				
		$this->add_responsive_control(
        	'woo_checkout_order_products_list_shipping_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-shipping' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_responsive_control(
        	'woo_checkout_order_products_list_shipping_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-shipping' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		//section shipping options style
		$this->add_control(
			'woo_checkout_order_products_shipping_options',
			[
				'label'     => __( 'Shipping Options', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);

        $this->add_control(
			'woo_checkout_order_products_shipping_options_bg_color',
			[
				'label'     => __( 'Section Background Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_shipping_options_heading_table',
			[
				'label' => __( 'Table', 'bew-extras' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_order_products_shipping_options_table_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method',
			]
		);
		
		
		$this->add_control(
			'woo_checkout_order_products_shipping_options_table_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_products_shipping_options_table_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_products_shipping_options_table_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_products_shipping_options_heading_content',
			[
				'label' => __( 'Content', 'bew-extras' ),
				'type'  => Controls_Manager::HEADING,
			]
		);
				
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_order_products_shipping_options_content_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method li',
			]
		);
		
		
		$this->add_control(
			'woo_checkout_order_products_shipping_options_content_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'woo_checkout_order_products_shipping_options_content_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'woo_checkout_order_products_shipping_options_content_margin',
			[
				'label'         => __( 'Margin', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		//section label style
		$this->add_control(
			'woo_checkout_order_products_shipping_options_label',
			[
				'label'     => __( 'Label', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_shipping_options_label_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method li label',
			]
		);


        $this->add_control(
			'woo_checkout_order_products_shipping_options_label_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table ul#shipping_method li label' => 'color: {{VALUE}}',
				],
			]
		);

		//section radio style
		$this->add_control(
			'woo_checkout_order_products_shipping_options_radio',
			[
				'label'     => __( 'Radio Button', 'bew-extras' ),
				'type'      => Controls_Manager::HEADING,				
				'separator' => 'before',
			]
		);		

		$this->add_responsive_control(
			'woo_checkout_order_products_shipping_options_radio_padding',
			[
				'label'         => __( 'Padding', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before, 
					 {{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_products_shipping_options_radio_border_radius',
			[
				'label'         => __( 'Border Radius', 'bew-extras' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs(
			'woo_checkout_order_products_shipping_options_radio_separator',
			[
			
			]
		);

		$this->start_controls_tab(
			'woo_checkout_order_products_shipping_options_radio_normal',
			[
				'label'     => __( 'Normal', 'bew-extras' ),
			]
		);

		$this->add_control(
			'woo_checkout_order_products_shipping_options_radio_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_products_bg_shipping_options_radio_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'woo_checkout_order_products_shipping_options_radio_border',
				'label'         => __( 'Border', 'bew-extras' ),
				'selector'      => '{{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:before',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'woo_checkout_order_products_shipping_options_radio_checked',
			[
				'label'     => __( 'Checked', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'woo_checkout_order_products_shipping_options_text_radio_checked',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-checkout-review-shipping-table .woocommerce-shipping-methods label:after' => 'background: {{VALUE}}',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		
        //Table Coupon
		$this->start_controls_section(
			'woo_checkout_order_coupon_style',
			[
				'label' => __( 'Coupon', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
            'woo_checkout_order_products_coupon_general_heading',
            [
                'label' => __( 'Coupon General', 'briefcase-extras' ),
                'type' => Controls_Manager::HEADING,                
            ]
        );
		
		$this->add_control(
			'woo_checkout_order_products_list_coupon_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-coupon.bew-components-panel' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
        	'woo_checkout_order_products_list_coupon_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-coupon.bew-components-panel' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );

		$this->add_responsive_control(
        	'woo_checkout_order_products_list_coupon_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-coupon.bew-components-panel' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_products_list_coupon_border_type',
		        'label'     => __( 'Quantity Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-components-totals-coupon.bew-components-panel.has-border:after',
		    ]
		);			
		
        $this->add_control(
            'woo_checkout_order_products_coupon_title_heading',
            [
                'label' => __( 'Coupon Title', 'briefcase-extras' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_products_list_coupon_typography',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-order-review-coupon',				
			]
		);
		
        $this->add_control(
			'woo_checkout_order_products_list_coupon_color',
			[
				'label'     => __( 'Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-order-review-coupon' => 'color: {{VALUE}}',
				],
			]
		);
	
        $this->add_control(
            'woo_checkout_order_products_coupon_inputbox_heading',
            [
                'label' => __( 'Input Box', 'briefcase-extras' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'woo_checkout_order_products_coupon_inputbox_color',
            [
                'label' => __( 'Input Box Color', 'briefcase-extras' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon input.input-text' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'woo_checkout_order_products_coupon_inputbox_typography',
                'label'     => __( 'Typography', 'briefcase-extras' ),
                'selector'  => '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon input.input-text',
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'woo_checkout_order_products_coupon_inputbox_border',
                'label' => __( 'Border', 'briefcase-extras' ),
                'selector' => '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon input.input-text',
            ]
        );

        $this->add_responsive_control(
            'woo_checkout_order_products_coupon_inputbox_border_radius',
            [
                'label' => __( 'Border Radius', 'briefcase-extras' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon input.input-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'woo_checkout_order_products_coupon_inputbox_padding',
            [
                'label' => __( 'Padding', 'briefcase-extras' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon input.input-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height:auto',
                ],
            ]
        );
		
		$this->add_responsive_control(
            'woo_checkout_order_products_coupon_inputbox_margin',
            [
                'label' => __( 'Margin', 'briefcase-extras' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon input.input-text' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height:auto',
                ],
            ]
        );

        $this->add_responsive_control(
            'woo_checkout_order_products_coupon_inputbox_width',
            [
                'label' => __( 'Input Box Width', 'briefcase-extras' ),
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
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .form-row-first ' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
		
		$this->add_control(
            'woo_checkout_order_products_coupon_button_heading',
            [
                'label' => __( 'Coupon Button', 'briefcase-extras' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );		

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'      => 'woo_checkout_order_products_coupon_button_typography',
                'label'     => __( 'Typography', 'briefcase-extras' ),
                'selector'  => '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button',
            ]
        );

        $this->add_control(
            'woo_checkout_order_products_coupon_button_color',
            [
                'label' => __( 'Color', 'briefcase-extras' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_checkout_order_products_coupon_button_bg_color',
            [
                'label' => __( 'Background Color', 'briefcase-extras' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button' => 'background-color: {{VALUE}}; transition:0.4s',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'woo_checkout_order_products_coupon_button_border',
                'label' => __( 'Border', 'briefcase-extras' ),
                'selector' => '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button , .woocommerce-page {{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon__content form .form-row-last .button',
            ]
        );

        $this->add_responsive_control(
            'woo_checkout_order_products_coupon_button_border_radius',
            [
                'label' => __( 'Border Radius', 'briefcase-extras' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'woo_checkout_order_products_coupon_button_hover_color',
            [
                'label' => __( 'Hover Color', 'briefcase-extras' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button:hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'woo_checkout_order_products_coupon_button_hover_bg_color',
            [
                'label' => __( 'Hover Background Color', 'briefcase-extras' ),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button:hover' => 'background-color: {{VALUE}}; transition:0.4s',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'woo_checkout_order_products_coupon_hover_button_border',
                'label' => __( 'Border', 'briefcase-extras' ),                    
				'selector' => '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button:hover',
            ]
        );
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_coupon_button_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button, .woocommerce-page {{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon__content form .form-row-last .button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; height: auto;',
        		],
        	]
        );
		
		$this->add_responsive_control(
        	'woo_checkout_order_products_coupon_button_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .button, .woocommerce-page {{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon__content form .form-row-last .button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_responsive_control(
            'woo_checkout_order_products_coupon_button_width',
            [
                'label' => __( 'Button Width', 'briefcase-extras' ),
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
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .form-row-last' => 'width: {{SIZE}}{{UNIT}} !important;',
                ],
            ]
        );
		
		$this->add_responsive_control(
            'woo_checkout_order_products_coupon_button_alignment',
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
                'default' 	=> 'center',
                'toggle' 	=> true,
                'selectors' => [
                    '{{WRAPPER}} #bew-checkout-review-order .bew-components-totals-coupon .form-row-last .button' => 'text-align: {{VALUE}};',
                ],
            ]
        );		
		
		$this->end_controls_section();

		//Table totals footer control		
		$this->start_controls_section(
			'woo_checkout_order_tfoot_style',
			[
				'label' => __( 'Totals', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_tfoot_typography_label',
				'label' 	=> __( 'Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-footer-item .bew-components-totals-item__label,
								{{WRAPPER}} .bew-components-totals-footer-item .bew-components-totals-item__value',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' 		=> 'woo_checkout_order_tfoot_typography_amount',
				'label' 	=> __( 'Amount Typography', 'bew-extras' ),
				'selector' 	=> '{{WRAPPER}} .bew-components-totals-footer-item .bew-components-totals-item__value .woocommerce-Price-amount.amount',
			]
		);

        $this->add_control(
			'woo_checkout_order_tfoot_color',
			[
				'label'     => __( 'Text Color', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-footer-item .bew-components-totals-item__label,
					 {{WRAPPER}} .bew-components-totals-footer-item .bew-components-totals-item__value' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'woo_checkout_order_tfoot_bg_color',
			[
				'label'     => __( 'Background', 'bew-extras' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-components-totals-footer-item' => 'background-color: {{VALUE}}',
				],
			]
		);

        $this->add_responsive_control(
        	'woo_checkout_order_tfoot_padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-footer-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
        $this->add_responsive_control(
        	'woo_checkout_order_tfoot_margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-components-totals-footer-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_checkout_order_tfoot_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-components-totals-footer-item',
		    ]
		);

		$this->add_responsive_control(
            'woo_checkout_order_tfoot_alignment',
            [
                'label' 	   => __( 'Alignment', 'elementor' ),
                'type' 		   => Controls_Manager::CHOOSE,
                'options' 	   => [
					'flex-start' 		=> [
						'title' 	=> __( 'Left', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-left',
					],
					'center' 	=> [
						'title' 	=> __( 'Center', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-center',
					],
					'flex-end' 	=> [
						'title' 	=> __( 'Right', 'bew-extras' ),
						'icon' 		=> 'eicon-text-align-right',
					],
					'space-between' => [
						'title' => __( 'Justified', 'bew-extras' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
                'default' 	=> 'space-between',
                'toggle' 	=> true,
                'selectors' => [
                    '{{WRAPPER}} .bew-components-totals-footer-item' => 'justify-content: {{VALUE}};',
                ],
            ]
        );		

		$this->end_controls_section();

	}

	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
		$settings = $this->get_settings_for_display();
		
		$order_review_heading_show         = $settings['woo_checkout_order_review_heading_show'];
		$order_review_heading_cd_show      = $settings['woo_checkout_order_review_collapse_heading_show'];
		$order_review_heading_arrow_show   = $settings['woo_checkout_order_review_heading_arrow_show']; 
		$order_review_heading_price_show   = $settings['woo_checkout_order_review_total_price_show'];
		$order_review_heading_tag    	   = Utils::validate_html_tag( $settings['woo_checkout_order_review_heading_tag'] ?? "" );
		$order_review_heading_text   	   = $settings['woo_checkout_order_review_heading_text'];
		$order_review_order_titles_show    = $settings['woo_checkout_order_review_order_titles_show'];
		$order_review_table_th1    		   = $settings['woo_checkout_order_review_table_th1'];
		$order_review_table_th2    		   = $settings['woo_checkout_order_review_table_th2'];
		
		$order_review_coupon_label         = $settings['woo_checkout_order_review_coupon_label_layout'];
		$order_review_coupon_title_text    = $settings['woo_checkout_order_review_coupon_title_text'];
		$order_review_coupon_button_text   = $settings['woo_checkout_order_review_coupon_button_text'];
		$order_review_coupon_label_text    = $settings['woo_checkout_order_review_coupon_label_text'];
		
		$order_review_shipping_label_text  = $settings['woo_checkout_order_review_shipping_label_text'];
		
		$order_review_collapse             =  $settings['woo_checkout_order_review_collapse'];
		$order_review_collapse_mobile      =  $settings['woo_checkout_order_review_collapse_mobile'];
		
		$order_review_shipping_options     =  $settings['woo_checkout_order_review_shipping_options_show'];		
			
		$woo_checkout_order_review_layout  =  $settings['woo_checkout_order_review_layout'];		
		$order_review_collapse_closed      =  $settings['woo_checkout_order_review_collapse_closed'];
		$order_review_collapse_closed      =  $settings['woo_checkout_order_review_collapse_closed'];
		
		$order_review_collapse_heading_closed_mobile =  $settings['woo_checkout_order_review_collapse_heading_closed_mobile'];
		
		$show_order_summary 			   =  $settings['woo_checkout_order_review_heading_show_text'];
		$hide_order_summary 			   =  $settings['woo_checkout_order_review_heading_hide_text'];
		$order_review_list_layout          =  $settings['woo_checkout_order_review_list_layout'];
		
		$show_summary_mobile = '';
		$show_summary = '';
		
		if ( ($order_review_collapse_mobile == 'yes') && ($woo_checkout_order_review_layout == 'table') ) { 
			$show_summary_mobile = "show-summary-mobile";
		}
		
		if ( ($order_review_collapse_heading_closed_mobile == 'yes') && ($woo_checkout_order_review_layout == 'collapse') ) { 
			$show_summary_mobile = "order-review-mobile-closed";
		}
		
		if ( ($order_review_collapse_closed == '') && ($woo_checkout_order_review_layout == 'table') ) { 
			$show_summary = "show-summary";
		}
				
		if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			update_option( '_order_review_coupon_label', $order_review_coupon_label );
			update_option( '_order_review_coupon_title_text', $order_review_coupon_title_text );
			update_option( '_order_review_coupon_button_text', $order_review_coupon_button_text );
			update_option( '_order_review_coupon_label_text', $order_review_coupon_label_text );
			update_option( '_order_review_shipping_options', $order_review_shipping_options );
			update_option( '_order_review_shipping_label_text', $order_review_shipping_label_text );
		}			
		
		if( is_null( WC()->cart ) ) {
			include_once WC_ABSPATH . 'includes/wc-cart-functions.php';
			include_once WC_ABSPATH . 'includes/class-wc-cart.php';
			wc_load_cart();
		}
				
		?>
		<div id= "bew-checkout-review-order" class="woocommerce-checkout-review-order <?php echo $show_summary;?> <?php echo $show_summary_mobile;?> bew-order-review-<?php echo $woo_checkout_order_review_layout;?>  show-arrow-<?php echo $order_review_heading_arrow_show; ?> show-heading-collapse-<?php echo $order_review_heading_cd_show; ?> product-list-layout-<?php echo $order_review_list_layout; ?>"> 
			<div class="bew-review-order-heading">

				<?php 
				if( 'yes' == $order_review_heading_show ){ ?>
					<<?php echo esc_attr( $order_review_heading_tag ); ?> class="bew-order-review-title">			
					<div id= "bew-order-summary" class="bew-components-panel__button">
					<span class="wc-block-components-order-summary__button-text"><?php echo esc_html( $order_review_heading_text ); ?></span>
					<?php if( 'collapse' == $woo_checkout_order_review_layout ){ ?>
					<i class="icon-bag"></i>
					<span class="order-summary-toggle-text order-summary-toggle-text-show"><?php esc_html_e( $show_order_summary , 'bew-extras' ); ?></span>
					<span class="order-summary-toggle-text order-summary-toggle-text-hide"><?php esc_html_e( $hide_order_summary  , 'bew-extras' ); ?></span>
					<?php }  ?>
					</div> 
					</<?php echo esc_attr( $order_review_heading_tag ); ?>>
				<?php }
				if( 'yes' == $order_review_heading_price_show ) { ?>
					<div class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value total-title"><?php wc_cart_totals_order_total_html(); ?></div>
				<?php
				}  ?>
			</div>
			<div class="bew-review-order-content">
				<?php if( 'yes' == $order_review_order_titles_show ){ ?>
						<div class="product-titles">
							<div class="product-name"><?php esc_html_e( $order_review_table_th1 , 'woocommerce' ); ?></div>
							<div class="product-total"><?php esc_html_e( $order_review_table_th2 , 'woocommerce' ); ?></div>
						</div>
				<?php } ?>	
				<?php include BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-review-order.php'; ?>
			</div>
		
		</div>
		
		<script type="text/javascript">
			jQuery(function($){
				$( '#billing_state, #billing_city, #billing_postcode' ).on( 'change', function() {
					$( document.body ).trigger( 'update_checkout' );
				} )
			})
		</script>
		
		<?php
	}

	protected function _content_template() {
		
	}
	
}

