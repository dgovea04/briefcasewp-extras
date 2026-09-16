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

class Woo_Logout extends Base_Widget {

	public function get_name() {
		return 'woo-account-logout';
	}

	public function get_title() {
		return __( 'Account Logout', 'bew-extras' );
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
			'section_style',
			[
				'label' => esc_html__( 'Style', 'elementor' ),
				'tab' => Elementor\Controls_Manager::TAB_STYLE,
			]
		);
		
		$this->add_control(
			'product_title_color',
			[
				'label'     => esc_html__( 'Color', 'elementor' ),
				'type'      => Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bew-customer-logout a' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			Elementor\Group_Control_Typography::get_type(),
			[
				'name'      => 'typography',
				'label'     => esc_html__( 'Typography', 'elementor' ),
				'selector'  => '{{WRAPPER}} .bew-customer-logout a',
			]
		);
		
		$this->add_responsive_control(
			'align',
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
				'default'      => 'left',
				'selectors' => [
					'{{WRAPPER}} .bew-customer-logout' => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->end_controls_section();
	}

	protected function render() {
		
		foreach ( wc_get_account_menu_items() as $endpoint => $label ) :
			if( $endpoint == 'customer-logout' ):
			?>
			<div class="bew-customer-logout">
				<a href="<?php echo esc_url( wc_logout_url( wc_get_page_permalink( 'myaccount' ) ) ); ?>"><?php echo esc_html( $label ); ?></a>
			</div>
			<?php
			endif;
		endforeach;

	}
	
	protected function _content_template() {
		
	}

}
