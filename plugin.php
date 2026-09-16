<?php
namespace BriefcasewpExtras;

use Elementor;	
use Elementor\Utils;
use Elementor\Core\Settings\Manager as SettingsManager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main class plugin
 */
class Plugin {

	/**
	 * @var Plugin
	 */
	private static $_instance;

	/**
	 * @var Manager
	 */
	private $_modules_manager;

	public $admin;	

	public function get_version() {
		return BEW_EXTRAS_VERSION;
	}

	public function get_plugin_name() {
		return BEW_EXTRAS_NAME;
	}

	/**
	 * Throw error on object clone
	 *
	 * The whole idea of the singleton design pattern is that there is a single
	 * object therefore, we don't want the object to be cloned.
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __clone() {
		// Cloning instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'bew-extras' ), '1.0.0' );
	}

	/**
	 * Disable unserializing of the class
	 *
	 * @since 1.0.0
	 * @return void
	 */
	public function __wakeup() {
		// Unserializing instances of the class is forbidden
		_doing_it_wrong( __FUNCTION__, __( 'Cheatin&#8217; huh?', 'bew-extras' ), '1.0.0' );
	}

	/**
	 * @return \Elementor\Plugin
	 */
	public static function elementor() {
		return \Elementor\Plugin::$instance;
	}

	/**
	 * @return Plugin
	 */
	public static function instance() {
		if ( is_null( self::$_instance ) ) {
			self::$_instance = new self();
		}
		return self::$_instance;
	}

	private function _includes() {
		
		require( BEW_EXTRAS_PATH . 'admin/bew-admin.php' );
		require BEW_EXTRAS_PATH  . 'includes/modules-manager.php';
		require BEW_EXTRAS_PATH  . 'includes/bew-manager.php';
		require BEW_EXTRAS_PATH  . 'includes/frontend.php';
		require BEW_EXTRAS_PATH  . 'includes/helper.php';
		require( BEW_EXTRAS_PATH . 'extensions/bew-importer/bew-importer.php' );
		require( BEW_EXTRAS_PATH . 'extensions/bew-extensions/bew-extensions.php' );

		require( BEW_EXTRAS_PATH . 'includes/bew-builder/bew-builder.php' );
		
		// Add templates to library	
				
		$active_extensions = get_option( 'briefcasewp_active_extensions', array() );
		if( $active_extensions ) {
			// Check If Bew Blocks Builder extension is active.
			if (in_array("Bew_Blocks", $active_extensions)) {				
				require( BEW_EXTRAS_PATH  . 'extensions/bew-builder/bew-builder.php' );
			}
		}
		
		require( BEW_EXTRAS_PATH  . 'includes/theme-builder/inc/bew-templates-for-elementor.php' );

	}

	public function autoload( $class ) {
		if ( 0 !== strpos( $class, __NAMESPACE__ ) ) {
			return;
		}

		$filename = strtolower(
			preg_replace(
				[ '/^' . __NAMESPACE__ . '\\\/', '/([a-z])([A-Z])/', '/_/', '/\\\/' ],
				[ '', '$1-$2', '-', DIRECTORY_SEPARATOR ],
				$class
			)
		);
		$filename = BEW_EXTRAS_PATH . $filename . '.php';

		if ( is_readable( $filename ) ) {
			include( $filename );
		}
	}
	
	public function enqueue_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';
		$direction_suffix = is_rtl() ? '-rtl' : '';	
					
	}
	
	public function enqueue_widgets_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';
		$direction_suffix = is_rtl() ? '-rtl' : '';	
		
		$briefcasewp_extras_styles = get_option('briefcasewp_extras_styles');
	
			$bew_frontend_file_name = 'bew-extras-widgets-style' . $direction_suffix . $suffix .  '.css';
			
			$bew_frontend_file_url = BEW_EXTRAS_ASSETS_URL . 'css/' . $bew_frontend_file_name;
			
			wp_enqueue_style(
				'bew-extras-widgets',
				$bew_frontend_file_url,
				[],
				BEW_EXTRAS_VERSION
			);
			
			wp_enqueue_style(
				'bew-snackbar',
				BEW_EXTRAS_URL . 'assets/css/snackbar.min.css',
				[],
				BEW_EXTRAS_VERSION
			);
			
			wp_enqueue_style(
				'bew-internation-phone-css',
				BEW_EXTRAS_URL . 'assets-vendor/intl-tel-input/css/intlTelInput.min.css',
				[],
				BEW_EXTRAS_VERSION
			);
			
			
	}

	public function enqueue_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '';
						
			wp_enqueue_script(
				'bew-extras-widgets',
				BEW_EXTRAS_URL . 'assets/js/bew-extras-widgets' . $suffix . '.js',
				array( 'jquery' ),
				BEW_EXTRAS_VERSION,
				true
			);
			
			wp_enqueue_script(
				'bew-snackbar',
				BEW_EXTRAS_URL . 'assets/js/snackbar.min.js',
				array( 'jquery' ),
				BEW_EXTRAS_VERSION,
				true
			);
						
	}

	public function enqueue_editor_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		
		wp_enqueue_script(
			'bew-extras-editor',
			BEW_EXTRAS_URL . 'assets/js/bew-extras-editor' . $suffix . '.js',
			[],
			BEW_EXTRAS_VERSION,
			true
		);
	
	}	
	
	public function enqueue_editor_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';

		$direction_suffix = is_rtl() ? '-rtl' : '';

		wp_enqueue_style(
			'bew-extras-editor',
			BEW_EXTRAS_URL . 'assets/css/bew-extras-editor' . $direction_suffix . '.css',
			[
			  'elementor-editor',
			],
			BEW_EXTRAS_VERSION
		);
		
		$ui_theme = SettingsManager::get_settings_managers( 'editorPreferences' )->get_model()->get_settings( 'ui_theme' );

		if ( 'light' !== $ui_theme ) {
			$ui_theme_media_queries = 'all';

			if ( 'auto' === $ui_theme ) {
				$ui_theme_media_queries = '(prefers-color-scheme: dark)';
			}

			wp_enqueue_style(
				'bew-extras-editor-dark-mode',
				BEW_EXTRAS_URL . 'assets/css/bew-extras-editor-dark-mode' . '.css',
				[
				  'elementor-editor',
				  'bew-builder-editor'
				],
				BEW_EXTRAS_VERSION,
				$ui_theme_media_queries
			);
		}
	}
	
	public function register_frontend_scripts() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';	
		
		if( class_exists( 'WooCommerce' ) ) { 
			wp_register_script(
				'bew-checkout',
				BEW_EXTRAS_URL . 'assets/js/bew-checkout.js',
				[
					'jquery',
				],
				BEW_EXTRAS_VERSION,
				true
			);
			wp_register_script(
				'bew-internation-phone-js',
				BEW_EXTRAS_URL . 'assets-vendor/intl-tel-input/js/intlTelInput-jquery.js	',
				[
					'jquery',
				],
				BEW_EXTRAS_VERSION,
				true
			);
		}
		
										
			wp_register_script(
				'bew-swiper',
				BEW_EXTRAS_URL . 'assets/js/swiper.min.js',
				array( 'jquery' ),
				BEW_EXTRAS_VERSION,
				true
			);
	}

	function add_bew_widget_categories( $elements_manager ) {

		$elements_manager->add_category(
			'bew-extras',
			[
				'title' => __( 'Briefcasewp Extras', 'bew-extras' ),
				'icon' => 'font',
			],
			1
		);
			
		$elements_manager->add_category(
			'bew-extras-cart',
			[
				'title' => __( 'Bew Woo Cart', 'bew-extras' ),
				'icon' => 'font',
			],
			1
		);
			
		$elements_manager->add_category(
			'bew-extras-checkout',
			[
				'title' => __( 'Bew Woo Checkout', 'bew-extras' ),
				'icon' => 'font',
			],
			1
		);
			
		$elements_manager->add_category(
			'bew-extras-account',
			[
				'title' => __( 'Bew Woo My Account', 'bew-extras' ),
				'icon' => 'font',
			],
			1
		);
			
		$elements_manager->add_category(
			'bew-extras-thankyou',
			[
				'title' => __( 'Bew Woo Thank You', 'bew-extras' ),
				'icon' => 'font',
			],
			1
		);

	}

	public function bew_extras_init() {
		$this->_modules_manager = new Manager();
		do_action( 'bew_elementor/init' );
	}	
	
	public function register_controls() {			
		
		// Define dir
		$dir = BEW_EXTRAS_PATH .'includes/controls/';			

		// Array of new widgets			
		$build_controls = apply_filters( 'bew_controls', array(			
			'choose_imagery' => $dir .'choose_imagery.php',
			'transform' => $dir .'transform.php',
			
		) );
		
		// Load files
		foreach ( $build_controls as $control_filename ) {
			include $control_filename;
		}
	}
		
	public function bew_templates_scripts() {	
		require_once BEW_EXTRAS_PATH .'includes/theme-builder/templates/bew-templates.php';	
	}
			
	public function bew_elementor_init(){		
		//load templates types				
		require_once BEW_EXTRAS_PATH .'includes/theme-builder/init.php';
	}
	
	public function bew_builder_init(){		
					
		require_once BEW_EXTRAS_PATH .'includes/bew-builder/bew-builder.php';
	}


    public function body_class( $classes ){
        $post_type = get_post_type();
        if( $post_type == 'elementor_library' ){
            $classes[] = 'woocommerce';
            $classes[] = 'woocommerce-page';
            $classes[] = 'bew-woocommerce-builder';
        }
        return $classes;
    }
	
	private function setup_hooks() {
		
		add_action( 'elementor/init', [ $this, 'bew_extras_init' ] );	
		add_action( 'elementor/editor/after_enqueue_styles', [ $this, 'enqueue_editor_styles' ] );
		add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'enqueue_editor_scripts' ) );
		add_action( 'elementor/preview/enqueue_styles', [ $this, 'enqueue_editor_scripts' ] );
		add_action( 'elementor/frontend/before_enqueue_scripts', [ $this, 'enqueue_frontend_scripts' ] );
		add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'enqueue_styles' ] );
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'enqueue_widgets_styles' ) );	
		add_action( 'elementor/frontend/after_register_styles', array( $this, 'bew_enqueue_libs' ) );	
		add_action( 'elementor/frontend/after_register_scripts', array( $this, 'register_frontend_scripts' ) );	
		add_filter( 'body_class', array($this, 'body_class') );
	}

	function add_this_script_footer() { 
	?>
	<script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>
	<?php 
	}

	public function bew_woocommerce_custom_template() {
		
		//Check if briefcase custom template option is enabled		
				
		// get path for templates used in loop
		add_filter( 'wc_get_template_part', function( $template, $slug, $name ) 
		{ 
			if ( $name ) {
				$path = plugin_dir_path( __FILE__ ) . WC()->template_path() . "{$slug}-{$name}.php";    
			} else {
				$path = plugin_dir_path( __FILE__ ) . WC()->template_path() . "{$slug}.php";    
			}
						
			return file_exists( $path ) ? $path : $template;
			
		}, 10, 3 );
		
		// get path for all other templates.
		add_filter( 'woocommerce_locate_template', function( $template, $template_name, $template_path ) 
		{
				
			// Check if Bew mini cart styles is disabled, so use default woocommerce template
			//$woo_bew_mini_cart = get_option('woo_bew_cart');
			$woo_bew_checkout  = '';
			
			// Check if Bew Extension is enabled
			$active_extensions = get_option( 'briefcasewp_active_extensions', array() );
			
			if( $active_extensions ) {
				
				// Check If Bew Cart & Checkout extension is active.
				if (in_array("Bew_Checkout", $active_extensions)) {
					$woo_bew_checkout = "active";
				}
				
			}
				
			if(  (($template_name == 'checkout/payment.php') && ($woo_bew_checkout != "active" )) ||
				 (($template_name == 'cart/cart-shipping.php') && ($woo_bew_checkout != "active" )) ||	
				 (($template_name == 'cart/cart-empty.php') && ($woo_bew_checkout != "active" )) ||
				 (($template_name == 'global/form-login.php') && ($woo_bew_checkout != "active" )) ||
				 (($template_name == 'myaccount/navigation.php') && ($woo_bew_checkout != "active" )) ||
				 (($template_name == 'checkout/form-coupon.php') && ($woo_bew_checkout != "active" )) 
			   ) {			
				// UNCOMMENT FOR @DEBUGGING
				//echo '<pre>';
				//echo 'template: ' . $template . '<br/>';
				//echo 'template_name: ' . $template_name . '<br/>';
				//echo 'template_path: ' . $template_path . '<br/>';
				//echo '</pre>';
				
				$path = '';	
				
			}else {	
			
				$path = plugin_dir_path( __FILE__ ) . $template_path . $template_name;
				
			}		
		 
			return file_exists( $path ) ? $path : $template;
		
		}, 10, 3 );
		
	}

	public function bew_enqueue_libs() {	
		
		if( class_exists( 'WooCommerce' ) ) {
			
			//  Enqueue Bew Icons: pe-icon-7-stroke, Ionicons, Themify-icons
			wp_enqueue_style(
				'bew-extras-font-icons',
				BEW_EXTRAS_URL . 'assets/libs/minified/bew-font-icons/css/bew-font-icons.min.css',
				[],
				BEW_EXTRAS_VERSION
			);
						
		}
	}

	public function maybe_init_cart() {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );

		if ( ! $has_cart ) {
			$session_class = apply_filters( 'woocommerce_session_handler', 'WC_Session_Handler' );
			WC()->session = new $session_class();
			WC()->session->init();
			WC()->cart = new \WC_Cart();
			WC()->customer = new \WC_Customer( get_current_user_id(), true );
		}
	}
	
	public function register_wc_hooks() {
		wc()->frontend_includes();
	}

	function _is_elementor_pro_installed() {
		$file_path = 'elementor-pro/elementor-pro.php';
		$installed_plugins = get_plugins();

		return isset( $installed_plugins[ $file_path ] );
	}

	function is_plugin_active_bew( $plugin ) {
		return in_array( $plugin, (array) get_option( 'active_plugins', array() ) );
	}
	
	/**
	 * Plugin constructor.
	 */
	private function __construct() {
		spl_autoload_register( [ $this, 'autoload' ] );

		$this->_includes();
		$this->setup_hooks();

		//if ( is_admin() ) {
		//	$this->admin = new Bew_Admin();
		//}
		
		
		// Custom Templates
		add_action( 'init', array( $this, 'bew_woocommerce_custom_template' ) );
		
		// Register controls
		add_action( 'elementor/elements/categories_registered', [ $this, 'add_bew_widget_categories' ] );
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );
		if( $this->is_plugin_active_bew( 'elementor-pro/elementor-pro.php' ) ) {
		add_action( 'elementor_pro/init', [ $this, 'bew_elementor_init' ] );
		}
		add_action( 'elementor/editor/footer', [ $this, 'bew_templates_scripts' ] );	
		
		add_action( 'elementor/editor/before_enqueue_scripts', [ $this, 'maybe_init_cart' ] );
		add_action( 'init', [ $this, 'register_wc_hooks' ], 5 );
		// On Editor - register Woocommerce frontend hooks - before the Editor init
		//add_action( 'admin_action_elementor', [ $this, 'register_wc_hooks' ], 5 );
		
		//add_action('wp_footer', [ $this, 'add_this_script_footer' ] );
		
	}
}

if ( ! defined( 'BEW_EXTRAS_TESTS' ) ) {
	// In tests we run the instance manually.
	Plugin::instance();
}