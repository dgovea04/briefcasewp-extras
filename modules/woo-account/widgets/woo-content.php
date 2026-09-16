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

class Woo_Content extends Base_Widget {

	public function get_name() {
		return 'woo-content';
	}

	public function get_title() {
		return __( 'Account Content', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-account' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		$this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			'link_color',
			[
				'label' => esc_html__( 'Link Color', 'bew-extras' ),
				'type' => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}}',
			]
		);
		
		$this->add_responsive_control(
			'alignment',
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
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
	}

	function custom_my_account_orders( $args ) {
		// Set the post per page
		$args['limit'] = 3;

		return $args;
	}

	protected function render() {
		
		add_filter( 'woocommerce_my_account_my_orders_query', array( $this, 'custom_my_account_orders'), 10, 1 );
		
		?>
		<div class="bew-account-content">
		<?php			
			do_action( 'woocommerce_account_content' );
		?>
		</div>
		<?php
	}
	
	protected function _content_template() {
		
	}

}
