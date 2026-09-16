<?php
namespace BriefcasewpExtras\Modules\BewSingle;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Module extends Module_Base {

	public function __construct() {
		parent::__construct();

		$this->add_actions();
	}

	public function get_name() {
		return 'bew-single';
	}

	public function register_controls( Controls_Stack $element ) {
		
		$post_type = '';
		global $post;
		if(isset($post)){
			$post_type = get_post_type($post->ID);
			$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
		}
					
		//echo var_dump($post_type);
		//echo var_dump($bew_template_type);
						
		if( ($post_type == "product") || (($post_type == "elementor_library") && ( $bew_template_type == "woo-product" ) )  ){			
			
			$element->start_controls_section(
				'section_bew_single',
				[
					'label' => __( 'Bew Single Product', 'bew-extras' ),
					'tab' => Controls_Manager::TAB_LAYOUT,					
				]
			);

			$element->add_control(
				'bew_single_summary',
				[
					'label'	=> __( 'Enable Summary', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,				
					'description'	=> __( 'Enable Single Product Summary on this section', 'bew-extras' )
				]
			);
					
			$element->end_controls_section();
		
		}
	}

	private function add_actions() {

		// Add Bew Single on section
		add_action( 'elementor/element/column/section_custom_css/before_section_start', [ $this, 'register_controls' ] );	
		add_action( 'elementor/element/section/section_custom_css/before_section_start', [ $this, 'register_controls' ] );
		
		// Add Bew Single on container
		add_action( 'elementor/element/container/section_custom_css/before_section_start', [ $this, 'register_controls' ] );

		add_action( 'elementor/frontend/column/before_render', [ $this, 'bew_single_summary_start'] );
		add_action( 'elementor/frontend/column/after_render', [ $this, 'bew_single_summary_close'] );
	}
	
	public function bew_single_summary_start ( $element ) {
		$settings = $element->get_settings_for_display();
		
		if (isset($settings['bew_single_summary'])){
			if( $settings['bew_single_summary'] == 'yes' ) {
							
				//remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_sale_flash', 10 );
				//remove_action( 'woocommerce_before_single_product_summary', 'woocommerce_show_product_images', 20 );
				
				//do_action( 'woocommerce_before_single_product_summary' );
				
				$element->add_render_attribute( '_wrapper', [
					'class' => 'bew-summary',
					'data-bew_summary' => 'yes',
				] );
				
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_sharing', 50 );
				//remove_action( 'woocommerce_single_product_summary', 'woocommerce_breadcrumb', 2 );
				//add_filter( 'woocommerce_get_breadcrumb', '__return_false' );			

				//do_action( 'woocommerce_single_product_summary' );
				
				//values
				global $product;
				
				//if
				if(is_object($product)) {

					//generate data
					WC()->structured_data->generate_product_data($product);
					
				}
			
			}
		}
	}

	public function bew_single_summary_close ( $element ) {
		$settings = $element->get_settings_for_display();
		
		if (isset($settings['bew_single_summary'])){
			if( $settings['bew_single_summary'] == 'yes' ) {
				
				//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
				//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
				//remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20 );
				
				//do_action( 'woocommerce_after_single_product_summary' );
			
			}
		}
	}
		
	public function enqueue_styles() { }
		
	public function enqueue_scripts() { }	
	
}
