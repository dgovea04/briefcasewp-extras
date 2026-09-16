<?php
namespace BriefcasewpExtras\Modules\WooOrders;

use BriefcasewpExtras\Base\Module_Base;
use BriefcasewpExtras\Modules\WooOrders\Classes;
use BriefcasewpExtras\Modules\WooOrders\Classes\Bew_Opc;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	
	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'woo-orders';
	}

	public function get_widgets() {
		return [
			'Woo_Orders',			
		];
	}
	
	public function bew_init_cart() {

		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}

	}
	
	public function __construct() {
		parent::__construct();
		
		// Enabled OPC ans Wao on Woo orders for elementor editor.
		//if(is_admin()){
		//	add_filter( 'is_bewwao_checkout', function( $is_wao ) {
		//		return true;
		//	} );
		//	add_filter( 'is_bewopc_checkout', function( $is_opc ) {
		//		return true;
		//	} );
		// }
				
		require_once BEW_EXTRAS_PATH . 'modules/woo-orders/classes/bew-opc.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-orders/classes/bew-wao.php';
		
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'bew_init_cart' ] );
	
	}

}
