<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_AI_Provider implements BEWIA_AI_Upsell_Provider_Interface {

	protected $engine;
	protected $client;
	protected $settings = array();

	public function __construct( $engine, $client = null ) {
		$this->engine = $engine;
		$this->client = $client;
	}

	public function get_recommendations( $settings, $context, $limit = 3 ) {
		$settings = BEWIA_AI_Upsell_Engine::normalize_settings( $settings );
		$this->settings = $settings;

		/**
		 * Future AI adapter hook.
		 *
		 * Providers can return:
		 * - array of WC_Product objects
		 * - array of product IDs
		 * - empty array when no AI recommendation is available
		 */
		$client = $this->client ? $this->client : new BEWIA_AI_Upsell_AI_Client( $settings );
		$payload = array( 'candidate_product_ids' => $this->engine->get_candidate_product_ids( $settings, $context ), 'context' => $this->sanitize_context( $context ) );
		$raw_results = $client->recommend( $payload );
		if ( is_wp_error( $raw_results ) ) { return array(); }
		$raw_results = isset( $raw_results['recommendations'] ) ? $raw_results['recommendations'] : $raw_results;

		return $this->normalize_results( $raw_results, $limit );
	}

	protected function normalize_results( $results, $limit ) {
		$products = array();

		if ( empty( $results ) || ! is_array( $results ) ) {
			return $products;
		}

		foreach ( $results as $result ) {
			if ( ! is_array( $result ) || empty( $result['product_id'] ) ) { continue; }
			$product_id = absint( $result['product_id'] );
			if ( ! in_array( $product_id, $this->engine->get_candidate_product_ids( $this->settings, array() ), true ) ) { continue; }
			$confidence = isset( $result['confidence'] ) ? (float) $result['confidence'] : 0.0;
			if ( $confidence < 0 || $confidence > 1 ) { continue; }
			$product = null;
			$product = wc_get_product( $product_id );

			if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				continue;
			}

			$products[] = array( 'product_id' => $product_id, 'confidence' => $confidence, 'provider_metadata' => isset( $result['provider_metadata'] ) && is_array( $result['provider_metadata'] ) ? $result['provider_metadata'] : array() );

			if ( count( $products ) >= absint( $limit ) ) {
				break;
			}
		}

		return $products;
	}

	protected function sanitize_context( $context ) {
		$allowed = array( 'cart_total', 'product_ids', 'category_ids', 'is_logged_in', 'customer_type', 'device_type', 'coupon_codes', 'checkout_elapsed_seconds', 'location_country' );
		return array_intersect_key( is_array( $context ) ? $context : array(), array_flip( $allowed ) );
	}
}
