<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_AI_Provider implements BEWIA_AI_Upsell_Provider_Interface {

	protected $engine;

	public function __construct( $engine ) {
		$this->engine = $engine;
	}

	public function get_recommendations( $settings, $context, $limit = 3 ) {
		$settings = BEWIA_AI_Upsell_Engine::normalize_settings( $settings );

		/**
		 * Future AI adapter hook.
		 *
		 * Providers can return:
		 * - array of WC_Product objects
		 * - array of product IDs
		 * - empty array when no AI recommendation is available
		 */
		$raw_results = apply_filters( 'bewia_ai_upsell_ai_recommendations', array(), $settings, $context, $limit, $this->engine );

		return $this->normalize_results( $raw_results, $limit );
	}

	protected function normalize_results( $results, $limit ) {
		$products = array();

		if ( empty( $results ) || ! is_array( $results ) ) {
			return $products;
		}

		foreach ( $results as $result ) {
			$product = null;

			if ( is_object( $result ) && method_exists( $result, 'get_id' ) ) {
				$product = $result;
			} elseif ( $result ) {
				$product = wc_get_product( absint( $result ) );
			}

			if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
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
