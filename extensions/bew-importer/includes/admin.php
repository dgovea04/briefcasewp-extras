<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Bew Importer admin.
 *
 * Admin handler class is responsible for initializing Plugin in
 * WordPress admin.
 *
 * @since 1.0.0
 */
class AdminImporter {


	/**
	 * Plugin page.
	 *
	 * Holds slug for plugin page.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @var string
	 */
	private $plugin_page = 'bew-templates';

	/**
	 * Enqueue admin scripts.
	 *
	 * Registers all the admin scripts and enqueues them.
	 *
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_scripts() {
		wp_register_script(
			'bew-admin',
			BEW_EXTRAS_IMPORTER_ASSETS_URL . 'js/admin.js',
			[
				'jquery',
			],
			BEW_EXTRAS_VERSION,
			true
		);
	}

	/**
	 * Enqueue admin styles.
	 *
	 * Registers all the admin styles and enqueues them.
	 *
	 * Fired by `admin_enqueue_scripts` action.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function enqueue_styles() {
		wp_register_style(
			'bew-admin',
			BEW_EXTRAS_IMPORTER_ASSETS_URL . 'css/admin.css',
			[],
			BEW_EXTRAS_VERSION
		);
		
		wp_enqueue_style( 'bew-admin' );		
		wp_enqueue_style( 'wpb-fa', 'https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css' );
		
	}


	/**
	 * Admin footer text.
	 *
	 * Modifies the "Thank you" text displayed in the admin footer.
	 *
	 * Fired by `admin_footer_text` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param string $footer_text The content that will be printed.
	 *
	 * @return string The content that will be printed.
	 */
	public function admin_footer_text( $footer_text ) {
		$current_screen = get_current_screen();
		$is_plugin_screen = ( $current_screen && false !== strpos( $current_screen->id, $this->plugin_page ) );

		if ( $is_plugin_screen ) {
			$footer_text = esc_html__( 'Thanks for using Bew Importer Extension!', 'bew-extras' ).
			               ' <a href="https://briefcasewp.com/">'.esc_html__( 'Briefcasewp.com', 'bew-extras' ).'</a>';
		}

		return $footer_text;
	}

	/**
	 * Elementor dashboard widget links
	 *
	 * Adds links in Elementor dashboard widget.
	 *
	 * Fired by `elementor/admin/dashboard_overview_widget/footer_actions` filter.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @param array $additions_actions Elementor dashboard widget footer actions.
	 *
	 * @return array Elementor dashboard widget footer actions.
	 */
	public function dashboard_widget_links( $additions_actions ) {
		$additions_actions['bew-extras'] =
			 [
				'title' => __( 'Import templates', 'bew-extras' ),
				'link' => admin_url( 'admin.php?page=' .$this->plugin_page ),
			];

		return $additions_actions;
	}

	/**
	 * Admin constructor.
	 *
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function __construct() {
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_scripts' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_styles' ] );

		add_filter( 'admin_footer_text', [ $this, 'admin_footer_text' ], 30 );

		add_filter( 'elementor/admin/dashboard_overview_widget/footer_actions', [ $this, 'dashboard_widget_links' ] );
	}
}
