<?php

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die();
}

// If class `Bew_Builder` doesn't exists yet.
if ( ! class_exists( 'Bew_Builder' ) ) {

	/**
	 * Sets up and initializes the plugin.
	 */
	class Bew_Builder {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since  1.0.0
		 * @access private
		 * @var    Bew_Builder
		 */
		private static $instance = null;

		/**
		 * Plugin base name
		 *
		 * @var string
		 */
		//public $plugin_name = null;

		/**
		 * Components
		 */
		public $module_loader;

		/**
		 * [$assets description]
		 * @var [type]
		 */
		public $assets;

		/**
		 * [$settings description]
		 * @var [type]
		 */
		public $settings;

		/**
		 * @var Bew_Builder_Templates_Post_Type
		 */
		public $templates;
		public $templates_manager;
		/**
		 * @var Bew_Builder_Config
		 */
		public $config;
		public $structures;
		/**
		 * @var Bew_Builder_API
		 */
		public $api;

		/**
		 * Sets up needed actions/filters for the plugin to initialize.
		 *
		 * @since 1.0.0
		 * @access public
		 * @return void
		 */
		public function __construct() {

			// Load files.
			add_action( 'init', array( $this, 'init' ), -999 );

		}

		/**
		 * Manually init required modules.
		 *
		 * @return void
		 */
		public function init() {

			$this->load_files();

			$this->config            = new Bew_Builder_Config();
			$this->assets            = new Bew_Builder_Assets();
			$this->api               = new Bew_Builder_API();
			$this->structures        = new Bew_Builder_Structures();

			if ( is_admin() ) {

				$this->templates_manager = new Bew_Builder_Templates_Manager();

			}

			do_action( 'bew-builder/init', $this );

		}

		/**
		 * Load required files
		 *
		 * @return void
		 */
		public function load_files() {

			// Global
			require BEW_EXTRAS_PATH . 'includes/bew-builder/includes/assets.php';
			require BEW_EXTRAS_PATH . 'includes/bew-builder/includes/config.php';
			require BEW_EXTRAS_PATH . 'includes/bew-builder/includes/api.php';
			require BEW_EXTRAS_PATH . 'includes/bew-builder/includes/utils.php';

			// Templates
			require BEW_EXTRAS_PATH . 'includes/bew-builder/includes/templates/manager.php';

			// Structures
			require BEW_EXTRAS_PATH . 'includes/bew-builder/includes/structures/manager.php';

		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor() {
			return defined( 'ELEMENTOR_VERSION' );
		}

		/**
		 * Check if theme has elementor
		 *
		 * @return boolean
		 */
		public function has_elementor_pro() {
			return defined( 'ELEMENTOR_PRO_VERSION' );
		}

		/**
		 * Get the template path.
		 *
		 * @return string
		 */
		public function template_path() {
			return apply_filters( 'bew-builder/template-path', 'bew-builder/' );
		}

		/**
		 * Returns path to template file.
		 *
		 * @return string|bool
		 */
		public function get_template( $name = null ) {

			$template = locate_template( $this->template_path() . $name );

			if ( ! $template ) {
				$template = BEW_EXTRAS_PATH . 'includes/bew-builder/templates/' . $name;
			}

			if ( file_exists( $template ) ) {
				return $template;
			} else {
				return false;
			}
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @access public
		 * @return Bew_Builder
		 */
		public static function get_instance() {
			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}
}

if ( ! function_exists( 'bew_builder' ) ) {

	/**
	 * Returns instanse of the plugin class.
	 *
	 * @since  1.0.0
	 * @return Bew_Builder
	 */
	function bew_builder() {
		return Bew_Builder::get_instance();
	}
}

bew_builder();
