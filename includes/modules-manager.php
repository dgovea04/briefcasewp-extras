<?php
namespace BriefcasewpExtras;

use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Manager {
	/**
	 * @var Module_Base[]
	 */
	private $modules = [];

	public function __construct() {
		
	// Get Credentials for update the options.
	$options = get_site_option('briefcase-elementor-widgets_updater_options');
	$email= isset($options['email']) ? $options['email'] : '';		
	$check_password = !empty(isset($options['password'])) ? "yes" : "no";
		
	if ($check_password == "no"){
		//Check if is password is set.
		$option_bew_extras = get_site_option('bew_extras_options');
		$check_password_confirm = !empty(isset($option_bew_extras['confirm_password'])) ? "yes" : "no";
	}
	
	//if (($email != '' && $check_password == "yes") || $check_password_confirm == "yes"  ){
		$modules = [
			'mobile-first-design',
			'mobile-menu',
			'bew-scroll',
			'bew-effects',
			'bew-sticky',			
			'bew-single',	
		];
			
		if( $this->is_plugin_active_pro( 'elementor-pro/elementor-pro.php' ) ) {
			array_push($modules, 'bew-template');
		}
			
		$active_extensions = get_option( 'briefcasewp_active_extensions', array() );
		if( $active_extensions ) {
			// Check If Bew Oders extension is active.
			if (in_array("Bew_Orders", $active_extensions)) {
				array_push($modules, 'woo-orders');
			}
			
			// Check If Bew Cart & Checkout extension is active.
			if (in_array("Bew_Checkout", $active_extensions)) {
				array_push($modules, 'woo-cart' , 'woo-checkout', 'woo-thankyou', 'woo-account'  );
			}
			
		}
				
		foreach ( $modules as $module_name ) {
			$class_name = str_replace( '-', ' ', $module_name );

			$class_name = str_replace( ' ', '', ucwords( $class_name ) );

			$class_name = __NAMESPACE__ . '\\Modules\\' . $class_name . '\Module';

			/** @var Module_Base $class_name */
			if ( $class_name::is_active() ) {
				$this->modules[ $module_name ] = $class_name::instance();
			}
		}
	//}
	
	}

	function is_plugin_active_pro( $plugin ) {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}
	
	/**
	 * @param string $module_name
	 *
	 * @return Module_Base|Module_Base[]
	 */
	public function get_modules( $module_name ) {
		if ( $module_name ) {
			if ( isset( $this->modules[ $module_name ] ) ) {
				return $this->modules[ $module_name ];
			}

			return null;
		}

		return $this->modules;
	}
}
