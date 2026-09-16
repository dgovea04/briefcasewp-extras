<?php
/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 */
if ( ! class_exists( 'Bewaa' ) ) {

class Bewaa {

	protected $loader;
	protected $plugin_name;
	protected $version;

	public function __construct() {
		if ( defined( 'BEWAA_VERSION' ) ) {
			$this->version = BEWAA_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->plugin_name = 'bewaa';

		$this->load_dependencies();
		$this->set_locale();	
		$this->define_admin_hooks();		
		$this->define_public_hooks();
	}

	private function load_dependencies() {

		/**
		 * Plugin global functions
		 */
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/functions.php';

		if ( ! defined( 'BEWAA_AUTOCOMPLETE' ) ) {
			define( 'BEWAA_AUTOCOMPLETE', bewaa_autocomplete() );
		}

		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bewaa-loader.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'includes/class-bewaa-i18n.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/class-bewaa-admin.php';
		require_once plugin_dir_path( dirname( __FILE__ ) ) . 'public/class-bewaa-public.php';

		$this->loader = new Bewaa_Loader();

	}

	private function set_locale() {

		$plugin_i18n = new Bewaa_I18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	private function define_admin_hooks() {

		$plugin_admin = new Bewaa_Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_styles' );
		$this->loader->add_action( 'admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts' );

		/**
		* Add on Bew Tabs
		*/
		//$this->loader->add_action( 'admin_menu', $plugin_admin, 'admin_pages', 99 );		
		
		$this->loader->add_filter( 'bew_add_tabs', $plugin_admin, 'add_tab', 10 );
		$this->loader->add_action( 'bew_admin_tabs', $plugin_admin, 'settings', 99 );
		
		/**
		* Settings
		*/
		$this->loader->add_action( 'admin_init', $plugin_admin, 'settings_init' );

			$bewaa_coordinates = get_option( 'bewaa_coordinates', '' );
			if ( '1' === $bewaa_coordinates ) {
				$this->loader->add_action( 'woocommerce_admin_order_data_after_billing_address', $plugin_admin, 'admin_order_data_after_billing_address' );
				$this->loader->add_action( 'woocommerce_admin_order_data_after_shipping_address', $plugin_admin, 'admin_order_data_after_shipping_address' );
				$this->loader->add_action( 'woocommerce_process_shop_order_meta', $plugin_admin, 'process_shop_order_meta' );
			}

			/**
			 * Coordinates
			 */
			$this->loader->add_filter( 'lddfw_order_shipping_address_coordinates', $plugin_admin, 'order_shipping_address_coordinates', 10, 2 );
	}

	private function define_public_hooks() {

		$plugin_public = new Bewaa_Public( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

		// Save coordinates on order.
		$bewaa_coordinates = get_option( 'bewaa_coordinates', '' );
		if ( '1' === $bewaa_coordinates ) {
			$this->loader->add_action( 'woocommerce_checkout_update_order_meta', $plugin_public, 'update_checkout_fields' );
		}

		// Show the maps on checkout page.
		$bewaa_map = get_option( 'bewaa_initial_map', '' );
		if ( '1' === $bewaa_map ) {

			$bewaa_map_position = '1';

			$bewaa_map_position = get_option( 'bewaa_map_position', '' );

			if ( '2' === $bewaa_map_position ) {

				// Show map before form.
				$this->loader->add_action( 'woocommerce_before_checkout_billing_form', $plugin_public, 'billing_map', 10, 1 );
				$this->loader->add_action( 'woocommerce_before_checkout_shipping_form', $plugin_public, 'shipping_map', 10, 1 );

			} else {
				// Show map after form.
				$this->loader->add_action( 'woocommerce_after_checkout_billing_form', $plugin_public, 'billing_map', 10, 1 );
				$this->loader->add_action( 'woocommerce_after_checkout_shipping_form', $plugin_public, 'shipping_map', 10, 1 );
			}
		}
	}

	public function run() {
		$this->loader->run();
	}

	public function get_plugin_name() {
		return $this->plugin_name;
	}

	public function get_loader() {
		return $this->loader;
	}

	public function get_version() {
		return $this->version;
	}

}
}