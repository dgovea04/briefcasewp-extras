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

class Woo_Navigation extends Base_Widget {

	public function get_name() {
		return 'woo-navigation';
	}

	public function get_title() {
		return __( 'Account Navigation', 'bew-extras' );
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
			'section_content_woo_navigation',
			[
				'label' => __( 'Account Navigation', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'woo_navigation_layout',
			[
				'label' 	=> __( 'Layout', 'bew-extras' ),
				'type' 		=> Controls_Manager::SELECT,
				'default' 	=> 'vertical',
				'options' 	=> [
					'vertical'  => __( 'Vertical', 'bew-extras' ),
					'horizontal'  => __( 'Horizontal', 'bew-extras' ),
				],				
			]
		);
		
		$this->end_controls_section();
	
		$this->start_controls_section(
			'section_style_woo_navigation',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'woo_navigation_text_typography',
				'selector' => '{{WRAPPER}} .bew-account-navigation a',
			]
		);
		
		$this->add_control(
			'woo_navigation_text_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-navigation a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_navigation_bg_color',
			[
				'label' => esc_html__( 'Background', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-navigation a' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_navigation_active_color',
			[
				'label' => esc_html__( 'Active Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-navigation .is-active a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'woo_navigation_bg_active_color',
			[
				'label' => esc_html__( 'Background', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-account-navigation .is-active a' => 'background-color: {{VALUE}}',
				],
			]
		);
				
		$this->add_group_control(
		    Group_Control_Border::get_type(),
		    [
		        'name'      => 'woo_navigation_border_type',
		        'label'     => __( 'Border', 'bew-extras' ),
		        'selector'  => '{{WRAPPER}} .bew-account-navigation li',
		    ]
		);
		
		$this->add_control(
			'woo_navigation_border_radius',
			[
				'label' => __( 'Border Radius', 'bew-extras' ),
				'type' 	=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', '%', 'em' ],
				'selectors' 	=> [
					'{{WRAPPER}} .bew-account-navigation li' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
        	'woo_navigation__padding',
        	[
        		'label' => __( 'Padding', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-account-navigation a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_responsive_control(
        	'woo_navigation__margin',
        	[
        		'label' => __( 'Margin', 'bew-extras' ),
        		'type' => Controls_Manager::DIMENSIONS,
        		'size_units' => [ 'px', '%', 'em' ],
        		'selectors' => [
        			'{{WRAPPER}} .bew-account-navigation li' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
        		],
        	]
        );
		
		$this->add_responsive_control(
			'woo_navigation_alignment',
			[
				'label' => esc_html__( 'Alignment', 'elementor' ),
				'type' => Elementor\Controls_Manager::CHOOSE,
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
					'{{WRAPPER}} .bew-account-navigation.vertical li, {{WRAPPER}} .bew-account-navigation.horizontal ul ' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
		
		$settings = $this->get_settings();
		
		$woo_navigation_layout   = $settings['woo_navigation_layout'];
		
		
		?>
		<div class="bew-account-navigation <?php echo $woo_navigation_layout; ?>">
		<?php
			do_action( 'woocommerce_account_navigation' );
		?>
		</div>
		<?php
	}
	
	protected function _content_template() {
		
	}

}
