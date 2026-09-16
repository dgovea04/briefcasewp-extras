<?php

/**
 * Elementor configuration file.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

define( 'ELEMENTOR_BRIEFCASEWPEXTRAS__FILE__', __FILE__ );

/**
 * Load BriefcasewpExtras
 */
function briefcasewpextras_load() {

	// Notice if the Elementor is not active
	if ( ! did_action( 'elementor/loaded' ) ) {
		add_action( 'admin_notices', 'bew_elementor_fail_load' );
		return;
	}

	// PHP Version
	if ( ! version_compare( PHP_VERSION, '5.6', '>=' ) ) {
		add_action( 'admin_notices', 'bew_fail_php_version' );
		return;
	}

	// Check version required
	$elementor_version_required = '2.0.0';
	if ( ! version_compare( ELEMENTOR_VERSION, $elementor_version_required, '>=' ) ) {
		add_action( 'admin_notices', 'bew_elementor_fail_load_out_of_date' );
		return;
	}

	// Require the main plugin file
	require BEW_EXTRAS_PATH . '/extensions/bew-builder/plugin.php';
}
add_action( 'after_setup_theme', 'briefcasewpextras_load' );



function bew_elementor_fail_load() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php esc_html_e( 'You need to install and activate Elementor page builder plugin to use this plugin.', 'briefcasewp-extras' ); ?></p>
	</div>
	<?php
}


function bew_fail_php_version() {
	$message = esc_html__( 'Briefcasewp Extras requires PHP version 5.6+. Please contact your host provide to update PHP.', 'briefcasewp-extras' );
	$html_message = sprintf( '<div class="error">%s</div>', wpautop( $message ) );
	echo wp_kses_post( $html_message );
}


function bew_elementor_fail_load_out_of_date() {
	?>
	<div class="notice notice-error is-dismissible">
		<p><?php esc_html_e( 'Your Elementor plugin is old. Please update it to the most recent version.', 'briefcasewp-extras' ); ?></p>
	</div>
	<?php
}
