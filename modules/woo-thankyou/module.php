<?php
namespace BriefcasewpExtras\Modules\WooThankyou;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use Briefcase\Helper;
use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	
	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'woo-thankyou';
	}

	public function get_widgets() {
		return [
			'Woo_Thankyou_Order',
			'Woo_Order_Details',
			'Woo_Customer_Details',
			'Woo_Customer_Details_Extras',	
		];
	}
	
	public function register_controls(Controls_Stack $element) {
			
			$post_type = '';
			global $post;
			if(isset($post)){
				$post_type = get_post_type($post->ID);
				$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
			}
			if( ($post_type == 'elementor_library') && ( $bew_template_type == 'woo-thankyou') ){			
									
				$element->start_controls_section(
					'section_bew_thankyou',
					[
						'label' => __( 'Bew Thankyou', 'bew-extras' ),
						'tab' => Controls_Manager::TAB_LAYOUT,				
					]
				);

				$element->add_control(
					'bew_thankyou',
					[
						'label'	=> __( 'Enable Thankyou', 'bew-extras' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'On', 'bew-extras' ),
						'label_off' => __( 'Off', 'bew-extras' ),
						'return_value' => 'yes',
						'default' => '',
						'frontend_available' => true,
						'prefix_class' => 'bew-thankyou-',
						'description'	=> __( 'Enable Bew Thankyou page on this section', 'bew-extras' )
					]
				);
				
				$element->end_controls_section();
				
			}
	}
	
	public function thankyou_content(){
		
		$helper = new Helper();
		$bew_thankyou_page_id = $helper->get_woo_thankyou_template();
		if(!empty($bew_thankyou_page_id)){			
			echo Elementor\Plugin::instance()->frontend->get_builder_content( $bew_thankyou_page_id, $with_css = true );			
		}
	}
	
	function bew_thanksyou_skeleton(){		

	}
	
	private function add_actions() {
				
		// Add Bew Account Thankyou on section
		add_action( 'elementor/element/section/section_structure/after_section_end', [ $this, 'register_controls' ], 10, 3  );	
				
		// Add Bew Account Thankyou on container
		add_action( 'elementor/element/container/section_layout_items/after_section_end', [ $this, 'register_controls' ], 10, 3  );
		
		add_action( 'bew_thankyou_content', array($this,'thankyou_content') );

	}
			
	public function __construct() {
		parent::__construct();
		
		$this->add_actions();
		
		//require_once BEW_EXTRAS_PATH . 'modules/woo-thankyou/classes/bew-woo-thankyou.php';	
		
	
	}
	
}
