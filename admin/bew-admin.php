<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * The class is responsible for initializing the importer extensions. The
 * class registers and all the components required to run the extensions.
 *
 * @since 1.0.0
 */
final class Bew_Admin {

	/**
	 * Instance.
	 *
	 * Holds the extension instance.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @var extension
	 */
	public static $instance = null;

	/**
	 * Admin.
	 *
	 * Holds the extension admin.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Admin
	 */
	public $admin;

	/**
	 * Instance.
	 *
	 * Ensures only one instance of the class is loaded or can be loaded.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @return extension An instance of the class.
	 */
	public static function instance() {
		if ( is_null( self::$instance ) ) {
			self::$instance = new self();

			/**
			 * admin loaded.
			 *
			 * Fires when extension was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'bew-admin/loaded' );
		}

		return self::$instance;
	}
	
	/**
	 * Init.
	 *
	 * Initialize extension.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function init() {
		if ( is_admin() ){
			
			require_once( BEW_EXTRAS_PATH  .'/admin/includes/admin.php' );
			
			$this->admin = new Admin();
			
			do_action( 'bew-admin/init' );
		}
	}

	/**
	 * extension constructor.
	 *
	 * Initializing extension.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function __construct() {
		//$this->register_autoloader();

		add_action( 'init', [ $this, 'init' ], 0 );
	}
}

Bew_Admin::instance();