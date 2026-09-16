<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Main Plugin Class. Register new elementor widget.
 */
class Plugin_builder {

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->add_actions();
	}


	/**
	 * Add Actions
	 */
	private function add_actions() {
		add_action( 'elementor/widgets/widgets_registered', [ $this, 'on_widgets_registered' ] );

		add_action( 'elementor/frontend/after_register_styles', function() {
		  wp_dequeue_style( 'elementor-post-'. get_the_ID() );

		  wp_enqueue_script(
			'bew-extras-help',
			BEW_EXTRAS_URL . 'extensions/bew-builder/assets/js/script' . '.js',
			['jquery'],
			BEW_EXTRAS_VERSION,
			true
		);
		
		});

	}

	/**
	 * On Widgets Registered
	 */
	public function on_widgets_registered() {
		$this->enqueue();
		$this->includes();
		$this->categories();		
		$this->register_widget();


		//new Page_Settings();

	}


	/**
	 * Enqueue styles and scripts.
	 */
	private function enqueue() {

		add_action( 'elementor/frontend/after_enqueue_scripts', function() {

			wp_enqueue_style( 'bew-elementor-style', get_theme_file_uri( '/include/elementor/assets/css/style.css' ), array( 'elementor-editor' ) );

			if ( 'yes' !== get_option( 'elementor_expert_user', '' ) ) {
				wp_enqueue_style( 'bew-elementor-limit', get_theme_file_uri( '/include/elementor/assets/css/limit.css' ), array( 'elementor-editor' ) );
			}
			

		} );

	}
	
	 /**
	 * Categories
	 */
	private function categories() {

		$elements = \Elementor\Plugin::instance()->elements_manager;

		$title = esc_html__( 'Bew Blocks', 'briefcasewp-extras' );
		
		$elements->add_category( 'bew-block', [
			'title' => $title,
			'icon' => 'eicon-font',
		] );

	}
	
	/**
	 * Includes
	 */
	private function includes() {

		//require __DIR__ . '/page-settings.php';

			require __DIR__ . '/widgets/bew-widget.php';
			require __DIR__ . '/widgets/bew-controls.php';
			require __DIR__ . '/widgets/bew-panels.php';
			require __DIR__ . '/widgets/bew-render.php';

		// Blocks	

			require __DIR__ . '/widgets/blocks/cover/base.php';
			require __DIR__ . '/widgets/blocks/categories/base.php';
			require __DIR__ . '/widgets/blocks/content/base.php';
			require __DIR__ . '/widgets/blocks/faq/base.php';
			require __DIR__ . '/widgets/blocks/subscribe/base.php';
			require __DIR__ . '/widgets/blocks/feature/base.php';
			require __DIR__ . '/widgets/blocks/textual_feature/base.php';
		
		if( class_exists( 'WooCommerce' ) ) { 
			require __DIR__ . '/widgets/blocks/product_tabs/base.php';	
		}		
	}

	/**
	 * Register Widgets.
	 */
	private function register_widget() {
		$manager = \Elementor\Plugin::instance()->widgets_manager;

		$widgets = [
			'BriefcasewpExtras\Widgets\Bew_Cover',
			'BriefcasewpExtras\Widgets\Bew_Categories',
			'BriefcasewpExtras\Widgets\Bew_Content',
			'BriefcasewpExtras\Widgets\Bew_Faq',
			'BriefcasewpExtras\Widgets\Bew_Subscribe',
			'BriefcasewpExtras\Widgets\Bew_Feature',
			'BriefcasewpExtras\Widgets\Bew_Textual_Feature',
		];
		
		if( class_exists( 'WooCommerce' ) ) { 
			array_push($widgets, 'BriefcasewpExtras\Widgets\Bew_Product_Tabs');
		}

		foreach ($widgets as $widget) {
			$manager->register_widget_type( new $widget );
		}
	}

}


new Plugin_builder();
