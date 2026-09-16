<?php
namespace BriefcasewpExtras;

use Elementor;


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

final class Bew_controls {
	
	/**
	 * Constructor
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 */
	public function __construct() {

		add_action( 'elementor/init', [ $this, 'init' ] );

	}
	
	public function init() {

		// Include plugin files
		$this->includes();

		// Register controls
		add_action( 'elementor/controls/controls_registered', [ $this, 'register_controls' ] );

	}

	public function includes() {

		require_once( BEW_EXTRAS_PATH . '/includes/controls/choose-imagery.php' );		

	}

	public function register_controls() {

		$controls_manager = \Elementor\Plugin::$instance->controls_manager;
		$controls_manager->register_control( 'choose-imagery', new Control_Choose_Imagery() );		

	}

}
