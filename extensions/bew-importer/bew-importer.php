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
final class Bew_Importer {

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
	 * Importer.
	 *
	 * Handles import page & import process
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var Importer
	 */
	public $importer;
	
	/**
	 * Instance.
	 *
	 * Ensures only one instance of the extension class is loaded or can be loaded.
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
			 * extension loaded.
			 *
			 * Fires when extension was fully loaded and instantiated.
			 *
			 * @since 1.0.0
			 */
			do_action( 'bew-importer/loaded' );
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
			require_once( BEW_EXTRAS_PATH  .'/extensions/bew-importer/includes/importer.php' );
			
			$this->importer = new Importer();
		}
			
		/**
		 * extension init.
		 *
		 * Fires on extension init, after extension has finished loading but
		 * before any headers are sent.
		 *
		 * @since 1.0.0
		 */
		do_action( 'bew-importer/init' );
	}

	/**
	 * Register autoloader.
	 *
	 * Autoloader loads all the classes needed to run the extension.
	 *
	 * @since 1.0.0
	 * @access private
	 */
	private function register_autoloader() {
		/** @noinspection PhpIncludeInspection */
		require BEW_EXTRAS_PATH . 'extensions/bew-importer/includes/autoloader.php';

		Autoloader::run();
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

Bew_Importer::instance();