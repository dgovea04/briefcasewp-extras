<?php
/**
 * Focused contract tests for checkout context normalization.
 *
 * Run: php tests/ai-upsells/test-context.php
 */

define( 'ABSPATH', __DIR__ );
define( 'DAY_IN_SECONDS', 86400 );

$bewia_test_logged_in = false;
$bewia_test_mobile    = false;
$bewia_test_user_id   = 0;
$bewia_test_orders    = 0;
$bewia_test_wc        = null;
$bewia_test_terms     = array();

function sanitize_key( $value ) {
	return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $value ) );
}

function sanitize_text_field( $value ) {
	return trim( (string) $value );
}

function sanitize_textarea_field( $value ) {
	return trim( (string) $value );
}

function absint( $value ) {
	return abs( (int) $value );
}

function is_user_logged_in() {
	global $bewia_test_logged_in;
	return $bewia_test_logged_in;
}

function wp_is_mobile() {
	global $bewia_test_mobile;
	return $bewia_test_mobile;
}

function get_current_user_id() {
	global $bewia_test_user_id;
	return $bewia_test_user_id;
}

function wc_get_customer_order_count( $user_id ) {
	global $bewia_test_orders;
	return $user_id > 0 ? $bewia_test_orders : 0;
}

function wc_get_product_term_ids( $product_id, $taxonomy ) {
	global $bewia_test_terms;
	return isset( $bewia_test_terms[ $product_id ] ) ? $bewia_test_terms[ $product_id ] : array();
}

function WC() {
	global $bewia_test_wc;
	return $bewia_test_wc;
}

class WooCommerce {}

class BEWIA_Test_Session {
	public $values = array();

	public function get( $key, $default = null ) {
		return array_key_exists( $key, $this->values ) ? $this->values[ $key ] : $default;
	}

	public function set( $key, $value ) {
		$this->values[ $key ] = $value;
	}
}

class BEWIA_Test_Cart {
	public $total = '0';
	public $items = array();
	public $coupons = array();

	public function get_total( $context ) {
		return $this->total;
	}

	public function get_cart() {
		return $this->items;
	}

	public function get_applied_coupons() {
		return $this->coupons;
	}
}

class BEWIA_Test_Customer {
	public $country = '';

	public function get_billing_country() {
		return $this->country;
	}
}

class BEWIA_Test_WC {
	public $cart;
	public $session;
	public $customer;
}

function bewia_assert_same( $expected, $actual, $message ) {
	if ( $expected !== $actual ) {
		throw new RuntimeException( $message . '\nExpected: ' . var_export( $expected, true ) . '\nActual: ' . var_export( $actual, true ) );
	}
}

function bewia_reset_context() {
	global $bewia_test_logged_in, $bewia_test_mobile, $bewia_test_user_id, $bewia_test_orders, $bewia_test_wc, $bewia_test_terms;
	$bewia_test_logged_in = false;
	$bewia_test_mobile    = false;
	$bewia_test_user_id   = 0;
	$bewia_test_orders    = 0;
	$bewia_test_terms     = array();
	$bewia_test_wc         = new BEWIA_Test_WC();
	$bewia_test_wc->cart     = new BEWIA_Test_Cart();
	$bewia_test_wc->session  = new BEWIA_Test_Session();
	$bewia_test_wc->customer = new BEWIA_Test_Customer();
}

require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-engine.php';

use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Engine;

bewia_reset_context();
$context = ( new BEWIA_AI_Upsell_Engine() )->get_cart_context( array(), 1000 );
bewia_assert_same( 0.0, $context['cart_total'], 'An empty cart has a zero total.' );
bewia_assert_same( array(), $context['product_ids'], 'An empty cart has no product IDs.' );
bewia_assert_same( array(), $context['coupon_codes'], 'An empty cart has no coupon codes.' );
bewia_assert_same( 'guest', $context['customer_type'], 'An anonymous customer is a guest.' );
bewia_assert_same( 0, $context['checkout_elapsed_seconds'], 'No checkout start time has zero elapsed seconds.' );

bewia_reset_context();
$bewia_test_logged_in       = true;
$bewia_test_user_id         = 18;
$bewia_test_orders          = 2;
$bewia_test_mobile          = true;
$bewia_test_wc->cart->total = '42.50';
$bewia_test_wc->cart->items = array( array( 'product_id' => 12 ), array( 'product_id' => 21 ), array( 'product_id' => 12 ) );
$bewia_test_wc->cart->coupons = array( 'SAVE10', 'save10', 'WELCOME' );
$bewia_test_wc->customer->country = 'us';
$bewia_test_terms = array( 12 => array( 4, 8 ), 21 => array( 8, 10 ) );
$context = ( new BEWIA_AI_Upsell_Engine() )->get_cart_context( array( 'scroll_depth' => '87.9', 'checkout_started_at' => 640 ), 1000 );
bewia_assert_same( array( 12, 21 ), $context['product_ids'], 'Cart product IDs are unique.' );
bewia_assert_same( array( 4, 8, 10 ), $context['category_ids'], 'Cart categories are unique.' );
bewia_assert_same( array( 'save10', 'welcome' ), $context['coupon_codes'], 'Coupon codes are normalized and unique.' );
bewia_assert_same( 'returning', $context['customer_type'], 'A logged-in customer with orders is returning.' );
bewia_assert_same( 'mobile', $context['device_type'], 'Mobile state is represented coarsely.' );
bewia_assert_same( 'US', $context['location_country'], 'Country is normalized to its coarse ISO code.' );
bewia_assert_same( 87, $context['scroll_depth'], 'Scroll depth is bounded to an integer percentage.' );
bewia_assert_same( 360, $context['checkout_elapsed_seconds'], 'Elapsed time is derived server-side from a valid start time.' );
$context = ( new BEWIA_AI_Upsell_Engine() )->get_cart_context( array(), 1000 );
bewia_assert_same( 360, $context['checkout_elapsed_seconds'], 'A persisted checkout start time remains available without client signals.' );

bewia_reset_context();
$context = ( new BEWIA_AI_Upsell_Engine() )->get_cart_context( array( 'scroll_depth' => '-5', 'checkout_started_at' => 'not-a-time' ), 1000 );
bewia_assert_same( 0, $context['scroll_depth'], 'Malformed scroll depth is ignored.' );
bewia_assert_same( 0, $context['checkout_elapsed_seconds'], 'Malformed checkout start time is ignored.' );

bewia_reset_context();
$context = ( new BEWIA_AI_Upsell_Engine() )->get_cart_context( array( 'scroll_depth' => 101, 'checkout_started_at' => 1001 ), 1000 );
bewia_assert_same( 0, $context['scroll_depth'], 'Scroll depth above 100 is ignored.' );
bewia_assert_same( 0, $context['checkout_elapsed_seconds'], 'A future checkout start time is ignored.' );

$context = ( new BEWIA_AI_Upsell_Engine() )->get_cart_context( array( 'checkout_started_at' => 1 ), 90000 );
bewia_assert_same( 0, $context['checkout_elapsed_seconds'], 'A checkout start time older than one day is ignored.' );

echo "Context tests passed.\n";
