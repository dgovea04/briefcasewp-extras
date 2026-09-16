<?php
namespace BriefcasewpExtras\Modules\WooCart\Widgets;

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

class Woo_Cross_Sells extends Base_Widget {

	public function get_name() {
		return 'woo-cross-sells';
	}

	public function get_title() {
		return __( 'Woo Cross Sells', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-cart' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}
	
	protected function _register_controls() {
		
		$this->start_controls_section(
			'section_cross_sells_heading',
			[
				'label' => __( 'Heading', 'elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'show_heading',
			[
				'label' => __( 'Heading', 'elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_off' => __( 'Hide', 'elementor' ),
				'label_on' => __( 'Show', 'elementor' ),
				'default' => 'yes',
				'return_value' => 'yes',
				'prefix_class' => 'show-cross-sell-heading-',
			]
		);

		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_cross_sells',
			[
				'label' => __( 'Cross Sells', 'bew-extras' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		
		$this->add_control(
			'limit',
			[
				'label' => __( 'Limit', 'bew-extras' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 12,
			]
		);
		
		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'woocommerce' ),
				'type' => Controls_Manager::NUMBER,
				'prefix_class' => 'bew-products-columns%s-',
				'default' => 4,
				'min' => 1,
				'max' => 12,
			]
		);
		
		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order by', 'woocommerce' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'rand',
				'options' => [
					'rand' => __( 'Random', 'woocommerce' ),
					'date' => __( 'Publish Date', 'woocommerce' ),
					'modified' => __( 'Modified Date', 'woocommerce' ),
					'title' => __( 'Alphabetic', 'woocommerce' ),
					'popularity' => __( 'Popularity', 'woocommerce' ),
					'rating' => __( 'Rate', 'woocommerce' ),
					'price' => __( 'Price', 'woocommerce' ),
				],
			]
		);
		
		$this->add_control(
			'order',
			[
				'label' => _x( 'Order', 'Sorting order', 'woocommerce' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'desc' => __( 'DESC', 'woocommerce' ),
					'asc' => __( 'ASC', 'woocommerce' ),
				],
			]
		);
		
		$this->end_controls_section();
		
		$this->start_controls_section(
			'section_heading_style',
			[
				'label' => __( 'Heading', 'elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
				
		$this->add_control(
			'heading_color',
			[
				'label' => __( 'Color', 'elementor' ),
				'type' => Controls_Manager::COLOR,				
				'selectors' => [
					'{{WRAPPER}} .bew-cross-sell .cross-sells > h2' => 'color: {{VALUE}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);
		
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'heading_typography',
				'selector' => '{{WRAPPER}} .bew-cross-sell .cross-sells > h2',
				'condition' => [
					'show_heading!' => '',
				],
			]
		);
		
		$this->add_responsive_control(
			'heading_text_align',
			[
				'label' => __( 'Text Align', 'elementor' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'elementor' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'elementor' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'elementor' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bew-cross-sell .cross-sells > h2' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);
		
		$this->add_responsive_control(
			'heading_spacing',
			[
				'label' => __( 'Spacing', 'elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .bew-cross-sell .cross-sells > h2' => 'margin-bottom: {{SIZE}}{{UNIT}}',
				],
				'condition' => [
					'show_heading!' => '',
				],
			]
		);
		
		$this->end_controls_section();
		
		parent::_register_controls();
		
	}

	/**
	 * Render widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
				
		if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			
			ob_start();
			
			woocommerce_cross_sell_display( $settings['limit'], $settings['columns'], $settings['orderby'], $settings['order'] ); 
					
			$my_cross_sell = ob_get_clean();			
			
			if(!empty($my_cross_sell) ){
				
				// Astra compatibility for ajax calls on shop customization.
				$current_theme = wp_get_theme(get_template());
				if($current_theme == 'Astra'){
					do_action( 'bew_astra_shop_layout' );
				}
				
				?>
				<div class="woocommerce bew-cross-sell">		
				<?php echo $my_cross_sell; ?>
				</div>
				<?php	
			} else {
				?>
				<div class="woocommerce bew-cross-sell bew-cross-sell-empty ">		
				<span>The products on the cart don't have to cross-sells, this message only shows on Elementor editor.</span>
				</div>
				<?php				
			}
			
		}else{
			
			if( is_cart() ){               
				
				?>
				<div class="woocommerce bew-cross-sell">		
				<?php woocommerce_cross_sell_display( $settings['limit'], $settings['columns'], $settings['orderby'], $settings['order'] ); ?>
				</div>
				<?php
				
			}
			
		}		
			
	}
		
}