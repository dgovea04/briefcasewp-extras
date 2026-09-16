<?php
namespace BriefcasewpExtras\Modules\WooThankyou\Widgets;

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

class Woo_Thankyou_Order extends Base_Widget {

	public function get_name() {
		return 'woo-thankyou-order';
	}

	public function get_title() {
		return __( 'Thank you Order', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-thankyou' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {
		$this->start_controls_section(
			'order_received_section',
			[
				'label' => esc_html__( 'Order Received', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'order_received_text',
			[
				'label'     => esc_html__( 'Order Received Message', 'bew-extras' ),
				'type'      => Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Thank you. Your order has been received.', 'bew-extras' ),
				'placeholder' => esc_html__( 'Thank you. Your order has been received.', 'bew-extras' ),
			]
		);
	
		$this->add_control(
			'order_received_layout',
			[
				'label' 		=> __( 'Order Received layout', 'bew-extras' ),
				'type' 			=> Controls_Manager::SELECT,
				'default' 		=> 'ticket',
				'options' 		=> [
					'ticket' 			=> __( 'Ticket', 'bew-extras' ),
					'inline' 			=> __( 'Inline', 'bew-extras' ),
				],
			]
		);

		$this->add_control(
            'order_received_order_number',
            [
                'label'         => __( 'Order Number', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-number-',
            ]
        );
		
		$this->add_control(
            'order_received_order_date',
            [
                'label'         => __( 'Date', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-date-',
            ]
        );

		$this->add_control(
            'order_received_order_email',
            [
                'label'         => __( 'Email', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-email-',
            ]
        );

		$this->add_control(
            'order_received_order_total',
            [
                'label'         => __( 'Total', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-total-',
            ]
        );		

		$this->add_control(
            'order_received_order_payment',
            [
                'label'         => __( 'Payment Method', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'Show', 'elementor' ),
                'label_off'     => __( 'Hide', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => 'yes',
				'prefix_class' => 'show-order-payment-',
            ]
        );		
						
		$this->end_controls_section();
		
		// order_received text style
		$this->start_controls_section(
			'order_received_text_style',
			[
				'label' => esc_html__( 'Order Received Message', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'order_received_text_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-thankyou-order-received',
			]
		);
		
		$this->add_control(
			'order_received_text_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-thankyou-order-received' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'order_received_text_padding',
			[
				'label'         => __( 'Padding', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .woocommerce-thankyou-order-received' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'order_received_text_margin',
			[
				'label'         => __( 'Margin', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .woocommerce-thankyou-order-received' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'order_received_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-thankyou-order-received' => 'text-align: {{VALUE}};',
				],
			]
		);		
		$this->end_controls_section();
						
		$this->start_controls_section(
			'order_received_content_style',
			[
				'label' => esc_html__( 'Order Received Content', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);		
		$this->add_control(
			'order_received_content_background_color',
			[
				'label' 		=> __( 'Background Color', 'briefcase-elementor-widgets' ),
				'type' 			=> Controls_Manager::COLOR,
				'selectors' 	=> [
					'{{WRAPPER}} .bew-thankyou-order ul.order_details' => 'background: {{VALUE}};',
					'{{WRAPPER}} .bew-thankyou-order.ticket ul.order_details:before, .bew-thankyou-order.ticket ul.order_details:after' => 'background: -webkit-linear-gradient(transparent 0, transparent 0),-webkit-linear-gradient(135deg, {{VALUE}} 33.33%, transparent 33.33%),-webkit-linear-gradient(45deg, {{VALUE}} 33.33%, transparent 33.33%); background-size: 0 100%, 16px 16px, 16px 16px;',
				
				],
				
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_received_content_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-thankyou-order ul.order_details',
			]
		);
		$this->add_control(
			'order_received_content_border_radius',
			[
				'label'         => __( 'Border Radius', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order ul.order_details' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'order_received_content_padding',
			[
				'label'         => __( 'Padding', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'separator' => 'before',
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order ul.order_details' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);		
		$this->add_responsive_control(
			'order_received_content_margin',
			[
				'label'         => __( 'Margin', 'elementor' ),
				'type'          => Controls_Manager::DIMENSIONS,
				'size_units'    => [ 'px', '%' ],
				'selectors'     => [
					'{{WRAPPER}} .bew-thankyou-order ul.order_details' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_section();
		
		// order_received label style
		$this->start_controls_section(
			'order_received_overview_style',
			[
				'label' => esc_html__( 'Label', 'woocommerce-builder-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'order_received_overview_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} ul.order_details li',
			]
		);
		$this->add_control(
			'order_received_overview_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.order_details li' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'          => 'order_received_overview_border',
				'label'         => __( 'Border', 'elementor' ),
				'selector'      => '{{WRAPPER}} .bew-thankyou-order ul.order_details li',
			]
		);
		$this->add_responsive_control(
			'order_received_overview_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.order_details li' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
		$this->start_controls_section(
			'order_received_details_style',
			[
				'label' => esc_html__( 'Details', 'woocommerce-builder-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'order_received_details_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} ul.order_details li strong',
			]
		);
		$this->add_control(
			'order_received_details_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} ul.order_details li strong' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'order_received_details_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} ul.order_details li strong' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}
	
	function bew_get_last_order_id(){
			
		$last_order_id = wc_get_orders(array('limit' => 1, 'return' => 'ids')); // Get last Order ID (array)
		return (string) reset($last_order_id);
	}
	
	protected function render() {
		global $wp;
		
		$settings = $this->get_settings_for_display();
		$order_received_text = $settings['order_received_text']; 
		$order_received_layout = $settings['order_received_layout'];
		
		if( isset($wp->query_vars['order-received']) ){
			$bew_order_received = $wp->query_vars['order-received'];
		}else{
			$bew_order_received = $this->bew_get_last_order_id();			
		}

		$order = wc_get_order( $bew_order_received );
		?>
		
		<div class="bew-thankyou-order <?php echo $order_received_layout; ?>">
		
		<?php 

		if ( $order ) :

			do_action( 'woocommerce_before_thankyou', $order->get_id() ); ?>
		
			<?php if ( $order->has_status( 'failed' ) ) : ?>
		
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed"><?php esc_html_e( 'Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction. Please attempt your purchase again.', 'woocommerce' ); ?></p>
				
				<p class="woocommerce-notice woocommerce-notice--error woocommerce-thankyou-order-failed-actions">
					<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php esc_html_e( 'Pay', 'woocommerce' ); ?></a>
					<?php if ( is_user_logged_in() ) : ?>
						<a href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>" class="button pay"><?php esc_html_e( 'My account', 'woocommerce' ); ?></a>
					<?php endif; ?>
				</p>
		
			<?php else : ?>
		
				<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( $order_received_text , 'woocommerce' ), $order ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		      
				<ul class="woocommerce-order-overview woocommerce-thankyou-order-details order_details">
		
					<li class="woocommerce-order-overview__order order">
						<?php esc_html_e( 'Order number:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_order_number(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
		
					<li class="woocommerce-order-overview__date date">
						<?php esc_html_e( 'Date:', 'woocommerce' ); ?>
						<strong><?php echo wc_format_datetime( $order->get_date_created() ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
		
					<?php if ( is_user_logged_in() && $order->get_user_id() === get_current_user_id() && $order->get_billing_email() ) : ?>
						<li class="woocommerce-order-overview__email email">
							<?php esc_html_e( 'Email:', 'woocommerce' ); ?>
							<strong><?php echo $order->get_billing_email(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
						</li>
					<?php endif; ?>
		
					<li class="woocommerce-order-overview__total total">
						<?php esc_html_e( 'Total:', 'woocommerce' ); ?>
						<strong><?php echo $order->get_formatted_order_total(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></strong>
					</li>
		
					<?php if ( $order->get_payment_method_title() ) : ?>
						<li class="woocommerce-order-overview__payment-method method">
							<?php esc_html_e( 'Payment method:', 'woocommerce' ); ?>
							<strong><?php echo wp_kses_post( $order->get_payment_method_title() ); ?></strong>
						</li>
					<?php endif; ?>
		
				</ul>
		
			<?php endif; ?>
			
			<?php do_action( 'woocommerce_thankyou_' . $order->get_payment_method(), $order->get_id() ); ?>
			<?php //do_action( 'woocommerce_thankyou', $order->get_id() ); ?>
			
		<?php else : ?>
		
			<p class="woocommerce-notice woocommerce-notice--success woocommerce-thankyou-order-received"><?php echo apply_filters( 'woocommerce_thankyou_order_received_text', esc_html__( $order_received_text , 'woocommerce' ), null ); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></p>
		
		<?php endif; ?>
		
		</div>
		<?php		
	}

	protected function _content_template() {
		
	}
	
}

