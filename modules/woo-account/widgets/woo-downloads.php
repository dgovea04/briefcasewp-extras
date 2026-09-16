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

class Woo_Downloads extends Base_Widget {

	public function get_name() {
		return 'woo-downloads';
	}

	public function get_title() {
		return __( 'Account Downloads', 'bew-extras' );
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
			'section_heading_style',
			[
				'label' => esc_html__( 'Headings', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'heading_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .woocommerce-table--order-downloads thead th',
			]
		);
		$this->add_control(
			'heading_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-downloads thead th' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'heading_border',
				'selector' => '{{WRAPPER}} .woocommerce-table--order-downloads thead th',
				'exclude' => [ 'color' ],
			]
		);
		
		$this->add_control(
			'heading_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-downloads thead th' => 'border-color: {{VALUE}}',
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
					'{{WRAPPER}} .woocommerce-table--order-downloads thead th' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			'heading_text_align',
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
				'default'      => '',
				'selectors' => [
					'{{WRAPPER}} .woocommerce-table--order-downloads thead th' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();

		// Table style
		$this->start_controls_section(
			'section_table_style',
			[
				'label' => esc_html__( 'Table Style', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'table_border',
				'selector' => '{{WRAPPER}} tbody tr td',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_control(
			'table_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} tbody tr td' => 'border-color: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'table_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} tbody tr td td' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'table_text_align',
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
					'{{WRAPPER}} tbody tr td td' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// product style
		$this->start_controls_section(
			'section_product_name_style',
			[
				'label' => esc_html__( 'Product Name', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'product_name_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .download-product a',
			]
		);
		$this->add_control(
			'product_name_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-product a' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// Downloads remaining
		$this->start_controls_section(
			'section_download_remaining_style',
			[
				'label' => esc_html__( 'Downloads remaining', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'download_remaining_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .download-remaining',
			]
		);
		$this->add_control(
			'download_remaining_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-remaining' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// expires
		$this->start_controls_section(
			'section_expires_style',
			[
				'label' => esc_html__( 'Expires', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'expires_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .download-expires',
			]
		);
		$this->add_control(
			'expires_color',
			[
				'label' => esc_html__( 'Color', 'elementor' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-expires' => 'color: {{VALUE}}',
				],
			]
		);
		$this->end_controls_section();

		// download-file style
		$this->start_controls_section(
			'section_download_file_style',
			[
				'label' => esc_html__( 'Download', 'bew-extras' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'download_file_typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file',
			]
		);
		$this->add_group_control(
			Elementor\Group_Control_Border::get_type(),
			[
				'name' => 'download_file_button_border',
				'selector' => '{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file',
				'exclude' => [ 'color' ],
			]
		);
		$this->add_responsive_control(
			'download_file_button_padding',
			[
				'label' => esc_html__( 'Padding', 'elementor' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		/////
		$this->start_controls_tabs( 'download_file_button_style_tabs' );
		
		$this->start_controls_tab( 'download_file_button_style_normal',
			[
				'label' => esc_html__( 'Normal', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'download_file_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'download_file_button_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'download_file_button_border_color',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'download_file_button_radius',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->start_controls_tab( 'download_file_button_style_hover',
			[
				'label' => esc_html__( 'Hover', 'bew-extras' ),
			]
		);
		
		$this->add_control(
			'download_file_button_text_color_hover',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'download_file_button_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'download_file_button_border_color_hover',
			[
				'label' => esc_html__( 'Border Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file:hover' => 'border-color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'download_file_button_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_control(
			'download_file_button_transition',
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
					'{{WRAPPER}} .download-file a.woocommerce-MyAccount-downloads-file' => 'transition: all {{SIZE}}s',
				],
			]
		);
		
		$this->end_controls_tab();
		
		$this->end_controls_tabs();
		$this->end_controls_section();
	}

	protected function render() {
		
		do_action('woocommerce_account_downloads_endpoint');
		
	}
	
	protected function _content_template() {
		
	}

}
