<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_Rule_Provider implements BEWIA_AI_Upsell_Provider_Interface {

	protected $engine;

	public function __construct( $engine ) {
		$this->engine = $engine;
	}

	public function get_recommendations( $settings, $context, $limit = 3 ) {
		$settings       = BEWIA_AI_Upsell_Engine::normalize_settings( $settings );
		$candidate_ids  = $this->engine->get_candidate_product_ids( $settings, $context );
		$suppressed_ids = isset( $settings['suppressed_product_ids'] ) ? array_map( 'absint', (array) $settings['suppressed_product_ids'] ) : array();
		$scored         = array();
		$products       = array();

		if ( ! $this->engine->matches_rule_groups( $settings, $context ) ) {
			return array();
		}

		foreach ( $candidate_ids as $candidate_id ) {
			$candidate_id = absint( $candidate_id );

			if ( $candidate_id <= 0 || in_array( $candidate_id, $context['product_ids'], true ) || in_array( $candidate_id, $suppressed_ids, true ) ) {
				continue;
			}

			$product = wc_get_product( $candidate_id );

			if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
				continue;
			}

			$score = $this->engine->score_product( $candidate_id, $context, $settings );

			if ( $score <= -9999 ) {
				continue;
			}

			$scored[ $candidate_id ] = $score;
		}

		arsort( $scored );

		foreach ( array_keys( $scored ) as $candidate_id ) {
			$product = wc_get_product( $candidate_id );

			if ( ! $product ) {
				continue;
			}

			$products[] = $product;

			if ( count( $products ) >= absint( $limit ) ) {
				break;
			}
		}

		return $products;
	}
}
