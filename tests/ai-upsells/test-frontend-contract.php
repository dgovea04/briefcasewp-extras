<?php
/** Dependency-free Task 6 contract test. Run: php tests/ai-upsells/test-frontend-contract.php */

$root = dirname( __DIR__, 2 );
$js   = file_get_contents( $root . '/modules/woo-checkout/assets/js/bcwp-ai-upsell.js' );
$php  = file_get_contents( $root . '/modules/woo-checkout/widgets/class-bcwp-elementor-ai-upsell.php' );
$css  = file_get_contents( $root . '/modules/woo-checkout/assets/css/bcwp-ai-upsell.css' );

function bewia_frontend_assert( $condition, $message ) {
	if ( ! $condition ) {
		throw new RuntimeException( $message );
	}
}

bewia_frontend_assert( strpos( $js, 'scroll_depth: getScrollDepth()' ) !== false, 'Scroll depth is sent.' );
bewia_frontend_assert( strpos( $js, 'checkout_started_at: checkoutStartedAt' ) !== false, 'Checkout start time is sent.' );
bewia_frontend_assert( strpos( $js, 'function safeText' ) !== false && strpos( $js, 'safeText(copy.urgency)' ) !== false, 'Generated copy and urgency are escaped.' );
bewia_frontend_assert( strpos( $js, 'bewiaRequestInFlight' ) !== false && strpos( $js, 'checkoutRefreshToken++' ) !== false, 'Checkout refreshes are deduplicated.' );
bewia_frontend_assert( strpos( $js, "action: 'bcwp_track_ai_upsell_rendered'" ) !== false, 'Rendered events remain wired.' );
bewia_frontend_assert( strpos( $js, "action: 'bcwp_dismiss_ai_upsell'" ) !== false && strpos( $js, "action: 'bcwp_accept_ai_upsell'" ) !== false, 'Dismiss and add-to-cart actions remain wired.' );
bewia_frontend_assert( strpos( $js, 'offer_id: $widget.data(\'bewiaOfferId\')' ) !== false && strpos( $js, 'offer_signature: $widget.data(\'bewiaOfferSignature\')' ) !== false, 'The emitted offer identity is sent when accepting.' );
bewia_frontend_assert( strpos( $js, "'updated_checkout'" ) !== false && strpos( $js, "'added_to_cart'" ) !== false, 'WooCommerce checkout events remain wired.' );
bewia_frontend_assert( strpos( $php, 'bewia_render_static_preview' ) !== false && strpos( $php, 'bewia_should_render_static_preview' ) !== false, 'Elementor preview remains static.' );
bewia_frontend_assert( strpos( $css, 'bewia-ai-smart-upsells__urgency' ) !== false, 'Urgency has a frontend style hook.' );

echo "Frontend contract test passed.\n";
