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

class Woo_Orders extends Base_Widget {

	public function get_name() {
		return 'woo-account-orders';
	}

	public function get_title() {
		return __( 'Account orders', 'bew-extras' );
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
			'section_content_woo_orders',
			[
				'label' => __( 'Orders', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'woo_orders_dashboard',
            [
                'label'         => __( 'Show Only on Dashboard', 'bew-extras' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => __( 'On', 'elementor' ),
                'label_off'     => __( 'Off', 'elementor' ),
                'return_value'  => 'yes',
                'default'       => '',
				'prefix_class' => 'show-only-dashboard-',
            ]
        );
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => esc_html__( 'Headings', 'elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'heading_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .shop_table thead th',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shop_table thead th' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'heading_border',
				'selector' => '{{WRAPPER}} .shop_table thead th',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'heading_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .shop_table thead th' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}} .shop_table thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'heading_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'bew-extras' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .shop_table thead th' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
		// Rows style
		$this->start_controls_section(
			'section_row_style',
			[
				'label' => esc_html__( 'Row Style', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'row_border',
				'selector' => '{{WRAPPER}} .woocommerce-orders-table__row',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_control(
			'row_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'row_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'row_text_align',
			[
				'label'        => esc_html__( 'Alignment', 'elementor' ),
				'type'         => Elementor\Controls_Manager::CHOOSE,
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
				'prefix_class' => 'elementor%s-align-',
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row td' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Order style
		$this->start_controls_section(
			'section_order_style',
			[
				'label' => esc_html__( 'Order', 'woocommerce' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'order_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-number a',
			]
		);
		$this->add_control(
			'order_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-number a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Date style
		$this->start_controls_section(
			'section_date_style',
			[
				'label' => esc_html__( 'Date', 'woocommerce' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'date_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-date',
			]
		);
		$this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-date' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// Status style
		$this->start_controls_section(
			'section_status_style',
			[
				'label' => esc_html__( 'Status', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'status_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-status',
			]
		);
		$this->add_control(
			'status_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-status' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// total style
		$this->start_controls_section(
			'section_total_style',
			[
				'label' => esc_html__( 'Total', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'total_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-total',
			]
		);
		$this->add_control(
			'total_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-total' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();
		
		// actions style
		$this->start_controls_section(
			'section_actions_style',
			[
				'label' => esc_html__( 'Actions', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'actions_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a',
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'actions_button_border',
				'selector' => '{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_responsive_control(
			'actions_button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		/////
		$this->start_controls_tabs( 'actions_button_style_tabs' );
		
		$this->start_controls_tab( 'actions_button_style_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'actions_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'actions_button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'actions_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'actions_button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'actions_button_style_hover',
			[
				'label' => esc_html__( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'actions_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'actions_button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'actions_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'actions_button_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'actions_button_transition',
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
					'{{WRAPPER}} .woocommerce-orders-table__row .woocommerce-orders-table__cell-order-actions a' => 'transition: all {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		$this->end_controls_section();
	}
	
	function custom_my_account_orders( $args ) {
		// Set the post per page
		$args['limit'] = 3;

		return $args;
	}

	protected function render() {
		
			global $wp;
			if ( ! is_user_logged_in() ) { return esc_html__('You need first to be logged in', 'bew-extras'); }
			
			$current_endpoint = WC()->query->get_current_endpoint();		
			if($current_endpoint === ''){
				$current_endpoint = 'dashboard';
			}
			
			add_filter( 'woocommerce_my_account_my_orders_query', array( $this, 'custom_my_account_orders'), 10, 1 );
			
			if( isset($wp->query_vars['orders']) ){
				$value = $wp->query_vars['orders']; ?>
				<div class="orders-endpoint orders">
					<?php do_action( 'woocommerce_account_orders_endpoint', $value ); ?>				
				</div>
				<?php 
			}elseif( isset($wp->query_vars['view-order']) ){
				$myaccount_url = get_permalink();
				do_action('bew_woocommerce_account_view_order_backorder',$myaccount_url);
				$value = $wp->query_vars['view-order'];
				?>
				<div class="orders-endpoint <?php echo $current_endpoint; ?> ">
					<?php do_action( 'woocommerce_account_view-order_endpoint', $value );	?>				
				</div>
				<?php 
				
			}else{
				$value = '';
				?>
				<div class="orders-endpoint <?php echo $current_endpoint; ?> ">
					<?php do_action( 'woocommerce_account_orders_endpoint', $value ); ?>				
				</div>
				<?php 
				
			}
			
			

	}
	
	protected function _content_template() {
		
	}

}
