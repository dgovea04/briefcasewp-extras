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
		$ai_products        = $this->ai_provider->get_recommendations( $settings, $context, $limit );
		$rule_candidate_ids = $this->engine->get_candidate_product_ids( $settings, $context );
		$merged_ids         = $this->extract_product_ids( $ai_products );

		if ( ! empty( $rule_candidate_ids ) ) {
			$merged_ids = array_values( array_unique( array_merge( $merged_ids, array_map( 'absint', $rule_candidate_ids ) ) ) );
		}

		if ( empty( $merged_ids ) ) {
			return array();
		}

		$hybrid_settings                         = $settings;
		$hybrid_settings['candidate_product_ids'] = $merged_ids;

		return $this->rule_provider->get_recommendations( $hybrid_settings, $context, $limit );
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
