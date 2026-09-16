<?php
namespace BriefcasewpExtras\Modules\WooCart;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use BriefcasewpExtras\Helper;
use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	
	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'woo-cart';
	}

	public function get_widgets() {
		return [
			'Woo_Cart_Table',
			'Woo_Cart_Totals',
			'Woo_Return_To_Shop',
			'Woo_Cross_Sells',
			'Woo_Empty_Cart_Message',
		];
	}
	
	public function register_controls( Controls_Stack $element ) {
			
			$post_id  = '';
			global $post;
			if(isset($post)){
				$post_id = $post->ID;
			}
			$cart_id = wc_get_page_id( 'cart' );
						
			if($post_id != $cart_id ){			
			return;
			}
			
			$element->start_controls_section(
				'section_bew_cart',
				[
					'label' => __( 'Bew Cart', 'bew-extras' ),
					'tab' => Controls_Manager::TAB_LAYOUT,				
				]
			);

			$element->add_control(
				'bew_cart',
				[
					'label'	=> __( 'Enable Bew Cart', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'prefix_class' => 'bew-cart-empty-section bew-cart-',
					'description'	=> __( 'Enable Bew Cart on this section.', 'bew-extras' )
				]
			);
			
			$element->add_control(
				'bew_cart_loading',
				[
					'label'	=> __( 'Enable Loading', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'frontend_available' => true,
					'description'	=> __( 'Enable Bew Cart loading effect.', 'bew-extras' ),
					'condition' => [
						'bew_cart' => 'yes',
					],
				]
			);
			
			$element->add_responsive_control(
				'bew_cart_loading_layout',
				[
					'label' 		=> __( 'Loading Layout', 'bew-extras' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'one' => [
							'title' => __( 'One Column', 'bew-extras' ),
							'icon'  => 'eicon-section',
						],
						'two' => [
							'title' => __( 'Two Columns', 'bew-extras' ),
							'icon'  => 'eicon-column',
						],					
					],
					'default' 		=> 'two',
					'condition' => [
						'bew_cart' => 'yes',
						'bew_cart_loading' => 'yes',
					],
				]
			);

			//$element->add_control(
			//	'bew_cart_preview_loading',
			//	[
			//		'label'	=> __( 'Preview Loading', 'bew-extras' ),
			//		'type' => Controls_Manager::SWITCHER,
			//		'label_on' => __( 'On', 'bew-extras' ),
			//		'label_off' => __( 'Off', 'bew-extras' ),
			//		'return_value' => 'yes',
			//		'prefix_class' => 'preview-skeleton-',
			//		'default' => '',
			//		'description'	=> __( 'Enable preview loading only on editor.', 'bew-extras' ),
			//		'condition' => [
			//			'bew_cart' => 'yes',
			//			'bew_cart_loading' => 'yes',
			//		],
			//	]
			//);

            $element->add_responsive_control(
                'bew_cart_skeleton_padding',
                [
                    'label' => __( 'Loading Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.bew-cart.bew-skeleton' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; ',
                    ],
					'condition' => [
						'bew_cart' => 'yes',
						'bew_cart_loading' => 'yes',
					],
                ]
            );

            $element->add_responsive_control(
                'bew_cart_skeleton_margin',
                [
                    'label' => __( 'Loading Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.bew-cart.bew-skeleton' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
					'condition' => [
						'bew_cart' => 'yes',
						'bew_cart_loading' => 'yes',
					],
                ]
            );
			
			$element->add_control(
				'bew_cart_update_loading',
				[
					'label'	=> __( 'Enabled Update Loading', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'description' 	=> __( 'Enabled loading effect on update cart for the current section', 'bew-extras' ),	
					'condition' => [
						'bew_cart' => '',
					],
				]
			);
			
			$element->add_control(
				'bew_cart_update_type',
				[
					'label' => esc_html__( 'Update Loading Type', 'bew-extras' ),
					'type' => Controls_Manager::SELECT,
					'default' => 'spinner',
					'label_block' => true,
					'prefix_class' => 'bew-cart-loader-type-',
					'options' => [
						'spinner'    => esc_html__( 'Spinner', 'bew-extras' ),
						'skeleton' => esc_html__( 'Skeleton', 'bew-extras' ),
					],
					'condition' 	=> [
						'bew_cart' => '',
						'bew_cart_update_loading' => 'yes',						
					],					
				]
			);
			
			$element->end_controls_section();
	}
		
	public function bew_cart_start ( $element ) {
		$settings = $element->get_settings_for_display();

		if(isset($settings['bew_cart'])){
			if( (($settings['bew_cart'] ?? null) == 'yes' ) && (($settings['bew_cart_loading'] ?? null) == 'yes' ) ) {
				
			$helper = new Helper();
			$bew_cart_empty_page_id = $helper->get_woo_cart_empty_template();
			
				if ( ( is_cart() ) ) {				
					
					if ( \WC()->cart->is_empty() ) {
						if( !empty($bew_cart_empty_page_id) ) {
						echo $this->bew_cart_skeleton_empty($element);
						}
					} else{
						echo $this->bew_cart_skeleton($element);
					}	
												
				}		
			}
			
			// Handle cart actions.
			if( ($settings['bew_cart']?? null) == 'yes' ) {	
				if ( \WC()->cart->is_empty() ) {					
					wc_get_template( 'cart/cart-empty.php');
				} else {
					echo '<div class="bew-cart-wrap woocommerce">';	
				}				
			}
		
		}
	}

	public function bew_cart_close( $element ) {
		
		$settings = $element->get_settings_for_display();
		if(isset($settings['bew_cart'])){
			// Handle cart actions.
			if( $settings['bew_cart'] ?? null == 'yes' ) {	
				if ( \WC()->cart->is_empty() ) {				
				} else {
					echo '</div>';	
				}				
			}
		}
		
	}
	
	function bew_cart_skeleton($element){
		$settings = $element->get_settings_for_display();
		
		if(isset($settings['bew_cart_loading_layout'])){
			if( $settings['bew_cart_loading_layout'] == 'one' ) {
				$bew_cart_skeleton = "bew-cart-skeleton-one";
			} else {
				$bew_cart_skeleton = "bew-cart-skeleton-two";
			}
		}
		
		?>
		<section class="elementor-element elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section <?php echo esc_attr( $bew_cart_skeleton  ); ?>" data-element_type="section" data-settings="{&quot;bew_cart&quot;:&quot;yes&quot;}">
			<div class="elementor-container elementor-column-gap-default">
				<div class="bew-skeleton bew-components-sidebar-layout bew-cart bew-cart--is-loading bew-cart--skeleton" aria-hidden="true">
					<div class="bew-components-main bew-cart__main">
						<h2><span></span></h2>
						<table class="bew-cart-items">
							<thead>
								<tr class="bew-cart-items__header">
									<th class="bew-cart-items__header-image"><span></span></th>
									<th class="bew-cart-items__header-product"><span></span></th>
									<th class="bew-cart-items__header-quantity"><span></span></th>
									<th class="bew-cart-items__header-total"><span></span></th>
								</tr>
							</thead>
							<tbody>
								<tr class="bew-cart-items__row">
									<td class="bew-cart-item__image">
										<div><img loading="lazy" src="" width="1" height="1"></div>
									</td>
									<td class="bew-cart-item__product">
										<div class="bew-cart-item__product-name"></div>
										<div class="bew-cart-item__product-metadata"></div>
									</td>
									<td class="bew-cart-item__quantity">
									<div class="bew-components-quantity-selector">
										<input class="bew-components-quantity-selector__input" type="number" step="1" min="0" value="1">
										<button class="bew-components-quantity-selector__button bew-components-quantity-selector__button--minus">－</button>
										<button class="bew-components-quantity-selector__button bew-components-quantity-selector__button--plus">＋</button>
									</div>
									</td>
									<td class="bew-cart-item__total">
										<div class="bew-cart-item__price"></div>
									</td>
								</tr>
								<tr class="bew-cart-items__row">
									<td class="bew-cart-item__image">
										<div><img loading="lazy" src="" width="1" height="1"></div>
									</td>
									<td class="bew-cart-item__product">
										<div class="bew-cart-item__product-name"></div>
										<div class="bew-cart-item__product-metadata"></div>
									</td>
									<td class="bew-cart-item__quantity">
									<div class="bew-components-quantity-selector">
										<input class="bew-components-quantity-selector__input" type="number" step="1" min="0" value="1">
										<button class="bew-components-quantity-selector__button bew-components-quantity-selector__button--minus">－</button>
										<button class="bew-components-quantity-selector__button bew-components-quantity-selector__button--plus">＋</button>
									</div>
									</td>
									<td class="bew-cart-item__total">
										<div class="bew-cart-item__price"></div>
									</td>
								</tr>
								<tr class="bew-cart-items__row">
									<td class="bew-cart-item__image">
										<div><img loading="lazy" src="" width="1" height="1"></div>
									</td>
									<td class="bew-cart-item__product">
										<div class="bew-cart-item__product-name"></div>
										<div class="bew-cart-item__product-metadata"></div>
									</td>
									<td class="bew-cart-item__quantity">
									<div class="bew-components-quantity-selector">
										<input class="bew-components-quantity-selector__input" type="number" step="1" min="0" value="1">
										<button class="bew-components-quantity-selector__button bew-components-quantity-selector__button--minus">－</button>
										<button class="bew-components-quantity-selector__button bew-components-quantity-selector__button--plus">＋</button>
									</div>
									</td>
									<td class="bew-cart-item__total">
										<div class="bew-cart-item__price"></div>
									</td>
								</tr>
							</tbody>
						</table>
					</div>
					<div class="bew-components-sidebar bew-cart__sidebar">
						<div class="components-card"></div>
					</div>
				</div>
			</div>
		</section>
	<?php

	}

	function bew_cart_skeleton_empty($element){
	
		?>
		<section class="elementor-element elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section bew-cart-empty-section" data-element_type="section" data-settings="{&quot;bew_cart&quot;:&quot;yes&quot;}">
			<div class="elementor-container elementor-column-gap-default">
				<div class="bew-skeleton bew-components-empty-layout bew-cart bew-cart--is-loading bew-cart--skeleton" aria-hidden="true">
					<div class="bew-components-main bew-cart__main">
						<div class="bew-cart-image">
							<div><img loading="lazy" src="" width="1" height="1"></div>
						</div>
						<h2><span></span></h2>
						<div class="bew-cart-button">
							<div class="bew-components-button-content"></div>
						</div>
					</div>
				</div>
			</div>
		</section>
	<?php

	}

	public function cart_empty_content(){
		
		$helper = new Helper();
		$bew_cart_empty_page_id = $helper->get_woo_cart_empty_template();
		$with_css = true;
		
		if(!empty($bew_cart_empty_page_id)){	
			echo '<div class="bew-cart-is-empty-template">';
			echo Elementor\Plugin::instance()->frontend->get_builder_content( $bew_cart_empty_page_id,$with_css  );
			echo '</div>';
		}
	}

	public function body_class_cart( $classes ){
				
        if ( is_cart() ) {
			$classes[] = 'bew-cart';
			$classes[] = 'bew-woocommerce-builder';	
			if ( \WC()->cart->is_empty() ) {
			
			$classes[] = 'bew-cart-is-empty';
			
			}
		}			
		
        return $classes;
    }
	
	private function add_actions() {
		
		// Add Bew Cart on section
		add_action( 'elementor/element/section/section_structure/after_section_end', [ $this, 'register_controls' ], 10, 3  );	
		add_action( 'elementor/frontend/section/before_render', [ $this, 'bew_cart_start'] );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'bew_cart_close'] );
		
		// Add Bew Cart on container
		add_action( 'elementor/element/container/section_layout_additional_options/after_section_end', [ $this, 'register_controls' ], 10, 3  );	
		add_action( 'elementor/frontend/container/before_render', [ $this, 'bew_cart_start'] );
		add_action( 'elementor/frontend/container/after_render', [ $this, 'bew_cart_close'] );
		
		add_action( 'bew_cart_empty_content', array($this,'cart_empty_content') );
		add_filter( 'body_class', [ $this, 'body_class_cart'] );
	}
				
	public function __construct() {
		parent::__construct();
		
		if(is_admin()){
			add_filter( 'is_bew_woo_cart', function( $is_bwc ) {
				return true;
			} );
		}
				
		require_once BEW_EXTRAS_PATH . 'modules/woo-cart/classes/bew-woo-cart.php';
				
		$this->add_actions();
	}

}
