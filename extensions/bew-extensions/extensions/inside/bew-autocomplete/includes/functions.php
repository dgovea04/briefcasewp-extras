<?php

/**
 * Register the JavaScript for the public-facing side of the site.
 *
 * @since    1.0.0
 */
function bewaa_autocomplete() {
	$bewaa_billing_autocomplete  = get_option( 'bewaa_billing_autocomplete', '' );
	$bewaa_shipping_autocomplete = get_option( 'bewaa_shipping_autocomplete', '' );
	$bewaa_google_api_key        = get_option( 'bewaa_google_api_key', '' );
	$bewaa_initial_map           = get_option( 'bewaa_initial_map', '' );
	return ( ( '' !== $bewaa_google_api_key ) && ( '1' === $bewaa_initial_map || '1' === $bewaa_billing_autocomplete || '1' === $bewaa_shipping_autocomplete ) ) ? true : false;
}
