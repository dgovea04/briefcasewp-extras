<?php
/** Dependency-free Task 3 tests. Run: php tests/ai-upsells/test-hybrid-and-copy.php */
define( 'ABSPATH', __DIR__ );
define( 'HOUR_IN_SECONDS', 3600 );
$bewia_cache = array(); $bewia_filters = array();
function absint( $v ) { return abs( (int) $v ); }
function sanitize_key( $v ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $v ) ); }
function sanitize_text_field( $v ) { return trim( (string) $v ); }
function wp_strip_all_tags( $v ) { return strip_tags( (string) $v ); }
function is_wp_error( $v ) { return $v instanceof WP_Error; }
function apply_filters( $tag, $value ) { global $bewia_filters; return isset( $bewia_filters[ $tag ] ) ? call_user_func( $bewia_filters[ $tag ], $value ) : $value; }
function get_transient( $key ) { global $bewia_cache; return isset( $bewia_cache[ $key ] ) ? $bewia_cache[ $key ] : false; }
function set_transient( $key, $value, $expiration ) { global $bewia_cache; $bewia_cache[ $key ] = $value; return true; }
class WP_Error {}
class BEWIA_Test_Product { public $id; public function __construct( $id ) { $this->id = $id; } public function get_id() { return $this->id; } public function get_name() { return 'Widget <script>alert(1)</script>'; } }
class BEWIA_Test_Copy_Client { public $calls = 0; public function generate( $payload ) { $this->calls++; return array( 'title' => '<b>AI title</b>', 'description' => str_repeat( 'x', 300 ), 'cta' => 'Buy now', 'urgency' => '<i>Only today</i>' ); } }
function bewia_assert_task3( $ok, $message ) { if ( ! $ok ) { throw new RuntimeException( $message ); } }
require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-copy-generator.php';
use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Copy_Generator;
$client = new BEWIA_Test_Copy_Client(); $product = new BEWIA_Test_Product( 7 );
$generator = new BEWIA_AI_Upsell_Copy_Generator();
$settings = array( 'copy_client' => $client, 'fallback_title' => 'Manual title', 'fallback_description' => 'Manual description', 'button_text' => 'Add offer' );
$copy = $generator->generate( $product, array( 'cart_total' => 50, 'customer_type' => 'guest', 'device_type' => 'desktop' ), $settings );
bewia_assert_task3( $copy['title'] === 'AI title' && strlen( $copy['description'] ) === 240 && $copy['urgency'] === 'Only today', 'Generated copy is sanitized and bounded.' );
$copy_again = $generator->generate( $product, array( 'cart_total' => 50, 'customer_type' => 'guest', 'device_type' => 'desktop' ), $settings );
bewia_assert_task3( $client->calls === 1 && $copy_again === $copy, 'Copy cache prevents a second provider call.' );
$fallback = $generator->generate( $product, array(), array( 'fallback_title' => 'Manual <b>title</b>', 'fallback_description' => 'Manual text', 'button_text' => 'Add' ) );
bewia_assert_task3( $fallback['title'] === 'Manual title' && $fallback['cta'] === 'Add', 'Unavailable copy provider uses sanitized manual fallback.' );
echo "Hybrid and copy tests passed.\n";
