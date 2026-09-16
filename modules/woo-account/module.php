<?php
namespace BriefcasewpExtras\Modules\WooAccount;

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
		return 'woo-account';
	}

	public function get_widgets() {
		return [
			'Woo_Dashboard',
			'Woo_Downloads',
			'Woo_Extra_Endpoint',
			'Woo_Edit_Account',
			'Woo_Edit_Address',
			'Woo_Login',
			'Woo_Logout',
			'Woo_Register',
			'Woo_Orders',
			'Woo_Navigation',
			'Woo_Content',
			'Woo_Lost_Password',
		];
	}
	
	public function register_controls( Controls_Stack $element ) {
			
			$post_type = '';
			global $post;
			if(isset($post)){
				$post_type = get_post_type($post->ID);		
				$bew_template_type = get_post_meta($post->ID, 'briefcase_template_layout', true);
			}
			if( (is_account_page()) || (($post_type == 'elementor_library') && ( $bew_template_type == 'woo-account')) ){			
									
				$element->start_controls_section(
					'section_bew_account',
					[
						'label' => __( 'Bew Account', 'bew-extras' ),
						'tab' => Controls_Manager::TAB_LAYOUT,				
					]
				);

				$element->add_control(
					'bew_account',
					[
						'label'	=> __( 'Enable Account', 'bew-extras' ),
						'type' => Controls_Manager::SWITCHER,
						'label_on' => __( 'On', 'bew-extras' ),
						'label_off' => __( 'Off', 'bew-extras' ),
						'return_value' => 'yes',
						'default' => '',
						'frontend_available' => true,
						'prefix_class' => 'bew-account-',
						'description'	=> __( 'Enable Bew Account page on this section', 'bew-extras' )
					]
				);
				
				$element->end_controls_section();
				
			}
	}
	
	public function account_content(){
		
		$helper = new Helper();
		$bew_account_page_id = $helper->get_woo_account_template();
		if(!empty($bew_account_page_id)){
			$withcss = true;
			echo Elementor\Plugin::instance()->frontend->get_builder_content( $bew_account_page_id,$withcss);
			
		}
	}
	
	public function account_login(){
		
		$helper = new Helper();
		$bew_login_page_id = $helper->get_woo_login_template();
		if(!empty($bew_login_page_id)){
			$withcss = true;
			echo Elementor\Plugin::instance()->frontend->get_builder_content( $bew_login_page_id,$withcss  );
			
		}
	}
	
	function bew_account_skeleton(){		

	}
	
	function redirect_register() {
	  $id = get_option( '_bew_account_register');
	  
	  if ( $id && is_page($id) && is_user_logged_in() ) {
		  wp_redirect( get_permalink( get_option('woocommerce_myaccount_page_id')) );
		  die();
	  }

	}

	function custom_redirection_after_registration( $redirection_url ){
		// Change the redirection Url
		$redirection_url = get_permalink( wc_get_page_id( 'myaccount' ) ); // Account Page

		return $redirection_url; // Always return something
	}

	public function body_class_account( $classes ){
        if ( is_account_page()) {
			$classes[] = 'bew-account';
			$classes[] = 'bew-woocommerce-builder';
        }
		if ( is_account_page() && !is_user_logged_in()) {
			$classes[] = 'bew-account-login-page';
        }
        return $classes;
    }
	
	private function add_actions() {
				

		// Add Bew Account on section
		add_action( 'elementor/element/section/section_structure/after_section_end', [ $this, 'register_controls' ], 10, 3  );	
				
		// Add Bew Account on container
		add_action( 'elementor/element/container/section_layout_items/after_section_end', [ $this, 'register_controls' ], 10, 3  );
		
		add_filter( 'body_class', [ $this, 'body_class_account'] );
		add_action( 'bew_account_content', array($this,'account_content') );
		add_action( 'bew_login_content', array($this,'account_login') );
		//add_action( 'wp', array($this,'redirect_register')  );
		add_filter( 'woocommerce_registration_redirect', array($this,'custom_redirection_after_registration'), 10, 1 );
		
	}
			
	public function __construct() {
		parent::__construct();
		
		$this->add_actions();
		
		//require_once BEW_EXTRAS_PATH . 'modules/woo-account/classes/bew-woo-account.php';	
		
	
	}
	
}
