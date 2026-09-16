<?php
namespace BriefcasewpExtras\Modules\WooCheckout\Widgets;

use Elementor;
use Elementor\Plugin;
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

class Woo_Checkout_Express_Request extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-express-request';
	}

	public function get_title() {
		return __( 'Checkout Express Buttons', 'bew-extras' );
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
			'express_buttons_content',
			[
				'label' => __( 'Express Buttons', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
            'express_buttons',
            [
                'label'         => __( 'Express Buttons', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'bew-extras' ),
                'label_off'     => __( 'Hide', 'bew-extras' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'express-buttons-show-',
            ]
		);
		
		$this->add_control(
		    'express_buttons_title',
		    [
		        'label' 		=> __( 'Title Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'Express checkout', 'bew-extras' ),
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->add_control(
		    'express_buttons_separator',
		    [
		        'label' 		=> __( 'Separator Text', 'bew-extras' ),
		        'type' 			=> Controls_Manager::TEXT,
		        'default' 		=> __( 'OR', 'bew-extras' ),
		        'dynamic' 		=> [
		            'active' 		=> true,
		        ]
		    ]
		);

		$this->end_controls_section();

        $this->start_controls_section(
            'express_buttons_style',
            array(
                'label' => __( 'Style', 'bew-extras' ),
                'tab' => Controls_Manager::TAB_STYLE,
            )
        );

            $this->add_group_control(
                Group_Control_Typography::get_type(),
                array(
                    'name'      => 'express_buttons_typography',
                    'label'     => __( 'Typography', 'bew-extras' ),
                    'selector'  => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
                )
            );

            $this->add_control(
                'express_buttons_text_color',
                [
                    'label' => __( 'Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_control(
                'express_buttons_color',
                [
                    'label' => __( 'Link Color', 'bew-extras' ),
                    'type' => Controls_Manager::COLOR,
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info a' => 'color: {{VALUE}}',
                    ],
                ]
            );

            $this->add_group_control(
                Group_Control_Border::get_type(),
                [
                    'name' => 'express_buttons_border',
                    'label' => __( 'Border', 'bew-extras' ),
                    'selector' => '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info',
                ]
            );

            $this->add_responsive_control(
                'express_buttons_border_radius',
                [
                    'label' => __( 'Border Radius', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'express_buttons_margin',
                [
                    'label' => __( 'Margin', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'express_buttons_padding',
                [
                    'label' => __( 'Padding', 'bew-extras' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-info' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
                ]
            );

            $this->add_responsive_control(
                'express_buttons_content_align',
                [
                    'label'        => __( 'Alignment', 'bew-extras' ),
                    'type'         => Controls_Manager::CHOOSE,
                    'options'      => [
                        'left'   => [
                            'title' => __( 'Left', 'bew-extras' ),
                            'icon'  => 'eicon-text-align-left',
                        ],
                        'center' => [
                            'title' => __( 'Center', 'bew-extras' ),
                            'icon'  => 'eicon-text-align-center',
                        ],
                        'right'  => [
                            'title' => __( 'Right', 'bew-extras' ),
                            'icon'  => 'eicon-text-align-right',
                        ],
                        'justify' => [
                            'title' => __( 'Justified', 'bew-extras' ),
                            'icon' => 'eicon-text-align-justify',
                        ],
                    ],
                    'default'   => 'left',
                    'selectors' => [
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info' => 'text-align: {{VALUE}}',
                        '{{WRAPPER}} .woocommerce-form-coupon-toggle .woocommerce-info::before' => 'position: static;margin-right:10px;',
                    ],
                ]
            );

        $this->end_controls_section();

    }

    protected function render() {		
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
        $settings = $this->get_settings_for_display();
		
		$express_buttons            = $settings['express_buttons'];
		$express_buttons_title      = $settings['express_buttons_title'];
		$express_buttons_separator  = $settings['express_buttons_separator'];
		
		?>
		<div class="bew-checkout-express-buttons bew-checkout-express-loading">
			<h2 class="bew-checkout-express_title"><?php esc_attr_e($express_buttons_title, 'bew-extras' ); ?></h2>
			<div class="bew-checkout-express_content">
				<div class="bew-checkout-express_skeleton">
					<div class="placeholder-line placeholder-line--animated"></div>
					<div class="placeholder-line placeholder-line--animated"></div>
					<div class="placeholder-line placeholder-line--animated"></div>
				</div>
				<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>				
				<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>				
			</div>
		</div>
		<div class="alternative-payment-separator bew-checkout-express-loading">
		  <span class="alternative-payment-separator_content"><?php esc_attr_e($express_buttons_separator, 'bew-extras' ); ?></span>
		</div>
		<?php
    }    

	protected function _content_template() {
		
	}
	
}

