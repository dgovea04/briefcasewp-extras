<?php
/** Focused, dependency-free contract tests for the real AI provider. */

define( 'ABSPATH', __DIR__ );
define( 'DAY_IN_SECONDS', 86400 );

$bewia_http_response = null;
$bewia_products = array();
$bewia_filters = array();

function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $value ) ); }
function sanitize_text_field( $value ) { return trim( (string) $value ); }
function esc_url_raw( $value ) { return filter_var( $value, FILTER_VALIDATE_URL ) ? $value : ''; }
function absint( $value ) { return abs( (int) $value ); }
function wp_json_encode( $value ) { return json_encode( $value ); }
function is_wp_error( $value ) { return $value instanceof WP_Error; }
function apply_filters( $tag, $value ) {
	global $bewia_filters;
	$args = func_get_args();
	if ( isset( $bewia_filters[ $tag ] ) ) { return call_user_func_array( $bewia_filters[ $tag ], array_slice( $args, 1 ) ); }
	return $value;
}
function wp_remote_post( $url, $args ) { global $bewia_http_response; return $bewia_http_response; }
function wp_remote_retrieve_response_code( $response ) { return isset( $response['response']['code'] ) ? $response['response']['code'] : 0; }
function wp_remote_retrieve_body( $response ) { return isset( $response['body'] ) ? $response['body'] : ''; }
function wc_get_product( $id ) { global $bewia_products; return isset( $bewia_products[ $id ] ) ? $bewia_products[ $id ] : false; }
function wc_get_product_status( $id ) { return 'publish'; }
class WP_Error { public $code; public $message; public function __construct( $code, $message = '' ) { $this->code = $code; $this->message = $message; } public function get_error_code() { return $this->code; } }
class WooCommerce {}
class BEWIA_Test_Product { public $id; public $purchasable = true; public function __construct( $id, $purchasable = true ) { $this->id = $id; $this->purchasable = $purchasable; } public function get_id() { return $this->id; } public function is_purchasable() { return $this->purchasable; } public function is_in_stock() { return true; } }
function bewia_assert( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }

require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/interface-bcwp-ai-upsell-provider.php';
require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ai-client.php';
require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ai-provider.php';
require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-rule-provider.php';
require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-engine.php';

use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_AI_Client;
use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_AI_Provider;
use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Engine;

$bewia_products = array( 10 => new BEWIA_Test_Product( 10 ), 11 => new BEWIA_Test_Product( 11, false ) );
$bewia_http_response = array( 'response' => array( 'code' => 200 ), 'body' => '{"recommendations":[{"product_id":10,"confidence":0.9,"reason":"bundle"}]}' );
$client = new BEWIA_AI_Upsell_AI_Client( array( 'endpoint' => 'https://ai.example.test', 'api_key' => 'secret', 'timeout' => 3 ) );
$provider = new BEWIA_AI_Upsell_AI_Provider( new BEWIA_AI_Upsell_Engine(), $client );
$results = $provider->get_recommendations( array( 'candidate_product_ids' => array( 10, 11 ), 'mode' => 'ai' ), array( 'product_ids' => array(), 'cart_total' => 20 ), 3 );
bewia_assert( count( $results ) === 1 && $results[0]['product_id'] === 10 && $results[0]['confidence'] === 0.9, 'AI recommendations are normalized and validated.' );

$bewia_http_response = new WP_Error( 'http_request_failed', 'timeout' );
$results = $provider->get_recommendations( array( 'candidate_product_ids' => array( 10 ), 'mode' => 'ai' ), array( 'product_ids' => array() ), 3 );
bewia_assert( empty( $results ), 'HTTP failures return no AI recommendations for Rules fallback.' );

$bewia_http_response = array( 'response' => array( 'code' => 200 ), 'body' => '{bad json' );
$results = $provider->get_recommendations( array( 'candidate_product_ids' => array( 10 ), 'mode' => 'ai' ), array( 'product_ids' => array() ), 3 );
bewia_assert( empty( $results ), 'Malformed JSON is rejected.' );

$client = new BEWIA_AI_Upsell_AI_Client( array( 'endpoint' => 'https://ai.example.test', 'api_key' => '' ) );
bewia_assert( is_wp_error( $client->recommend( array( 'candidate_product_ids' => array( 10 ) ) ) ), 'Missing credentials prevent requests.' );
echo "AI provider tests passed.\n";
