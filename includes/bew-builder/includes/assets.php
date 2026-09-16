<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    BriefcaseWP
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Bew_Builder_Assets' ) ) {

	/**
	 * Define Bew_Builder_Assets class
	 */
	class Bew_Builder_Assets {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			add_action( 'elementor/editor/before_enqueue_scripts', array( $this, 'editor_scripts' ), 0 );
			add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'editor_styles' ) );
			add_action( 'elementor/editor/footer', array( $this, 'print_templates' ) );
			add_action( 'elementor/preview/enqueue_styles', array( $this, 'preview_styles' ) );

		}

		public function suffix() {
			return defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		}

		/**
		 * Load preview assets
		 *
		 * @return void
		 */
		public function preview_styles() {

			wp_enqueue_style(
				'bew-builder-preview',
				BEW_EXTRAS_URL . 'includes/bew-builder/assets/css/preview.css',
				array(),
				BEW_EXTRAS_VERSION
			);

		}

		/**
		 * Enqueue elemnetor editor scripts
		 *
		 * @return void
		 */
		public function editor_scripts() {

			wp_enqueue_script(
				'bew-builder-editor',
				BEW_EXTRAS_URL . 'includes/bew-builder/assets/js/editor' . $this->suffix() . '.js',
				array( 'jquery', 'underscore', 'backbone-marionette' ),
				BEW_EXTRAS_VERSION,
				true
			);

			wp_localize_script( 'bew-builder-editor', 'BewBuilderData', apply_filters(
				'bew-builder/assets/editor/localize',
				array(
					'libraryButton' => '',
					'modalRegions'  => $this->get_modal_regions(),
					'license'       => array(
						'activated' => true,
						'link'      => '',
					),
				)
			) );

		}

		/**
		 * Returns modal regions
		 * @return [type] [description]
		 */
		public function get_modal_regions() {

			if ( defined( 'ELEMENTOR_VERSION' ) && version_compare( ELEMENTOR_VERSION, '2.1.0-beta1', '>=' ) ) {
				return array(
					'modalHeader'  => '.dialog-header',
					'modalContent' => '.dialog-message',
				);
			} else {
				return array(
					'modalContent' => '.dialog-message',
					'modalHeader'  => '.dialog-widget-header',
				);
			}

		}

		/**
		 * Enqueue elemnetor editor-related styles
		 *
		 * @return void
		 */
		public function editor_styles() {

			wp_enqueue_style(
				'bew-builder-editor',
				BEW_EXTRAS_URL . 'includes/bew-builder/assets/css/editor.css',
				array(),
				BEW_EXTRAS_VERSION
			);

		}

		/**
		 * Prints editor templates
		 *
		 * @return void
		 */
		public function print_templates() {

			foreach ( glob( BEW_EXTRAS_PATH . 'includes/bew-builder/templates/editor/*.php' ) as $file ) {
				$name = basename( $file, '.php' );
				ob_start();
				include $file;
				printf( '<script type="text/html" id="tmpl-bew-%1$s">%2$s</script>', $name, ob_get_clean() );
			}

		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
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
