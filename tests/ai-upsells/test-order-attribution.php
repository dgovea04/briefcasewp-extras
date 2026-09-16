<?php
/** Dependency-free Task 5 tests. Run: php tests/ai-upsells/test-order-attribution.php */
define( 'ABSPATH', __DIR__ );
function absint( $v ) { return abs( (int) $v ); }
function sanitize_key( $v ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $v ) ); }
function sanitize_text_field( $v ) { return trim( (string) $v ); }
function wp_parse_args( $args, $defaults = array() ) { return array_merge( $defaults, is_array( $args ) ? $args : array() ); }
function add_action() {}
function bewia_task5_assert( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }
require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php';
use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Analytics;

$identity = BEWIA_AI_Upsell_Analytics::normalize_offer_identity( array( 'product_id' => 7, 'mode' => 'AI', 'campaign_key' => 'spring', 'variant_id' => 'A', 'confidence' => 1.4 ) );
bewia_task5_assert( $identity['product_id'] === 7 && $identity['mode'] === 'ai' && $identity['confidence'] == 1.0, 'Offer identity is normalized and confidence is bounded.' );
bewia_task5_assert( BEWIA_AI_Upsell_Analytics::is_confirmable_order_status( 'completed' ), 'Completed orders are confirmable.' );
bewia_task5_assert( ! BEWIA_AI_Upsell_Analytics::is_confirmable_order_status( 'failed' ) && ! BEWIA_AI_Upsell_Analytics::is_confirmable_order_status( 'refunded' ), 'Failed and refunded orders are not confirmable.' );
$items = array(
	array( 'product_id' => 7, 'identity' => array( 'product_id' => 7 ), 'line_total' => '12.50' ),
	array( 'product_id' => 9, 'identity' => array( 'product_id' => 7 ), 'line_total' => '4.25' ),
);
$lines = BEWIA_AI_Upsell_Analytics::get_confirmable_offer_lines( $items );
bewia_task5_assert( count( $lines ) === 2 && (float) $lines[0]['revenue'] === 12.5, 'Attribution uses actual line totals and preserves multiple offers.' );
bewia_task5_assert( BEWIA_AI_Upsell_Analytics::get_confirmable_offer_lines( array( array( 'product_id' => 7, 'identity' => array(), 'line_total' => 9 ) ) ) === array(), 'Missing offer metadata is ignored.' );
bewia_task5_assert( BEWIA_AI_Upsell_Analytics::build_attribution_match( array( 'session_id' => 's1', 'campaign_key' => 'spring', 'variant_id' => 'A', 'offer_id' => 'offer-1' ) ) === 's1|spring|a|offer-1', 'Attribution key contains session, campaign, variant and offer identity.' );
bewia_task5_assert( BEWIA_AI_Upsell_Analytics::is_confirmed_revenue_event( array( 'event' => 'accepted', 'revenue' => 12.5, 'order_id' => null ) ) === false, 'Accepted revenue is estimated, not confirmed.' );
bewia_task5_assert( BEWIA_AI_Upsell_Analytics::is_confirmed_revenue_event( array( 'event' => 'accepted', 'revenue' => 12.5, 'order_id' => 44 ) ), 'Only an accepted event linked to an order is confirmed.' );
echo "Order attribution tests passed.\n";
