<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_Hybrid_Provider implements BEWIA_AI_Upsell_Provider_Interface {

	protected $engine;
	protected $ai_provider;
	protected $rule_provider;

	public function __construct( $engine ) {
		$this->engine        = $engine;
		$this->ai_provider   = new BEWIA_AI_Upsell_AI_Provider( $engine );
		$this->rule_provider = new BEWIA_AI_Upsell_Rule_Provider( $engine );
	}

	public function get_recommendations( $settings, $context, $limit = 3 ) {
		$settings           = BEWIA_AI_Upsell_Engine::normalize_settings( $settings );
		$ai_products        = $this->ai_provider->get_recommendations( $settings, $context, max( $limit, 50 ) );
		$rule_candidate_ids = $this->engine->get_candidate_product_ids( $settings, $context );
		$ai_scores          = $this->extract_ai_scores( $ai_products );
		$merged_ids         = array_keys( $ai_scores );

		if ( ! empty( $rule_candidate_ids ) ) {
			$merged_ids = array_values( array_unique( array_merge( $merged_ids, array_map( 'absint', $rule_candidate_ids ) ) ) );
		}

		if ( empty( $merged_ids ) ) {
			return array();
		}

		$hybrid_settings                         = $settings;
		$hybrid_settings['candidate_product_ids'] = $merged_ids;

		$ranked = array();
		if ( ! $this->engine->matches_rule_groups( $settings, $context ) ) { return array(); }
		foreach ( $merged_ids as $product_id ) {
			$rule_score = $this->engine->score_product( $product_id, $context, $settings );
			if ( $rule_score <= -9999 ) { continue; }
			$ranked[ $product_id ] = $rule_score + ( 25 * ( isset( $ai_scores[ $product_id ] ) ? $ai_scores[ $product_id ] : 0 ) );
		}
		arsort( $ranked, SORT_NUMERIC );
		$products = array();
		foreach ( array_keys( $ranked ) as $product_id ) {
			$product = wc_get_product( $product_id );
			if ( $product ) { $products[] = $product; }
			if ( count( $products ) >= absint( $limit ) ) { break; }
		}
		return $products;
	}

	protected function extract_ai_scores( $products ) {
		$scores = array();
		foreach ( (array) $products as $product ) {
			if ( is_array( $product ) && ! empty( $product['product_id'] ) ) {
				$scores[ absint( $product['product_id'] ) ] = max( 0, min( 1, (float) ( isset( $product['confidence'] ) ? $product['confidence'] : 0 ) ) );
			} elseif ( is_object( $product ) && method_exists( $product, 'get_id' ) ) {
				$scores[ absint( $product->get_id() ) ] = 0;
			}
		}
		return array_filter( $scores, 'absint' );
	}

	protected function extract_product_ids( $products ) {
		$product_ids = array();

		if ( empty( $products ) || ! is_array( $products ) ) {
			return $product_ids;
		}

		foreach ( $products as $product ) {
			if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) {
				continue;
			}

			$product_ids[] = absint( $product->get_id() );
		}

		return array_values( array_unique( array_filter( $product_ids ) ) );
	}
}
