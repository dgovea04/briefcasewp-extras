<?php
/** Focused contract tests for stable campaign assignment. */

define( 'ABSPATH', __DIR__ );

function sanitize_key( $value ) { return preg_replace( '/[^a-z0-9_-]/', '', strtolower( (string) $value ) ); }
function sanitize_text_field( $value ) { return trim( (string) $value ); }
function wp_strip_all_tags( $value ) { return strip_tags( (string) $value ); }
function absint( $value ) { return abs( (int) $value ); }
function bewia_experiment_assert( $condition, $message ) { if ( ! $condition ) { throw new RuntimeException( $message ); } }

class BEWIA_Experiment_Test_Session {
	public $values = array();
	public function get( $key, $default = null ) { return array_key_exists( $key, $this->values ) ? $this->values[ $key ] : $default; }
	public function set( $key, $value ) { $this->values[ $key ] = $value; }
}

class BEWIA_Experiment_Test_WC { public $session; }
$bewia_experiment_wc = new BEWIA_Experiment_Test_WC();
$bewia_experiment_wc->session = new BEWIA_Experiment_Test_Session();
function WC() { global $bewia_experiment_wc; return $bewia_experiment_wc; }
class WooCommerce {}

require_once dirname( __DIR__, 2 ) . '/modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-experiments.php';

use BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Experiments;

$variants = array(
	array( 'variant_id' => 'control', 'product_ids' => array( 10 ), 'layout' => 'card', 'copy' => array( 'title' => 'Control' ) ),
	array( 'variant_id' => 'challenger', 'product_ids' => array( 20 ), 'layout' => 'minimal', 'copy' => array( 'title' => 'Try this' ) ),
);
$first = BEWIA_AI_Upsell_Experiments::assign_variant( 'spring-sale', 'session-123', $variants );
$second = BEWIA_AI_Upsell_Experiments::assign_variant( 'spring-sale', 'session-123', $variants );
bewia_experiment_assert( $first === $second, 'The same campaign/session must keep the same variant.' );

$persisted = BEWIA_AI_Upsell_Experiments::assign_variant( 'spring-sale', '', $variants );
$persisted_again = BEWIA_AI_Upsell_Experiments::assign_variant( 'spring-sale', '', $variants );
bewia_experiment_assert( $persisted === $persisted_again, 'An empty session ID must use persisted WooCommerce session assignment.' );

$invalid = BEWIA_AI_Upsell_Experiments::normalize_variants( array( array( 'variant_id' => '../bad', 'product_ids' => array( '0', '15' ), 'layout' => 'invalid', 'copy' => array( 'title' => '<b>Safe</b>' ) ) ) );
bewia_experiment_assert( count( $invalid ) === 1 && $invalid[0]['variant_id'] === 'bad' && $invalid[0]['product_ids'] === array( 15 ) && $invalid[0]['layout'] === 'card' && $invalid[0]['copy']['title'] === 'Safe', 'Invalid variant fields must be normalized safely.' );
bewia_experiment_assert( BEWIA_AI_Upsell_Experiments::assign_variant( '', 'session-123', $variants ) === array(), 'Disabled experiments must not assign a variant.' );

BEWIA_AI_Upsell_Experiments::register_emitted_offer( 'session-123', array( 'product_id' => 10, 'campaign_key' => 'spring-sale', 'variant_id' => 'control' ) );
bewia_experiment_assert( BEWIA_AI_Upsell_Experiments::is_emitted_offer_valid( 'session-123', array( 'product_id' => 10, 'campaign_key' => 'spring-sale', 'variant_id' => 'control' ) ), 'A product emitted for the same session/campaign/variant is accepted.' );
bewia_experiment_assert( ! BEWIA_AI_Upsell_Experiments::is_emitted_offer_valid( 'session-123', array( 'product_id' => 20, 'campaign_key' => 'spring-sale', 'variant_id' => 'control' ) ), 'A product not emitted for the session is rejected server-side.' );

echo "Experiment tests passed.\n";
