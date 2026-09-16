<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_Engine {

	protected $provider_instances = array();
	protected $last_provider_source = 'rules';

	public static function normalize_settings( $settings = array() ) {
		if ( ! is_array( $settings ) ) {
			$settings = array();
		}

		$normalized = array(
			'enable_ai'                   => ( isset( $settings['enable_ai'] ) && 'yes' === $settings['enable_ai'] ) ? 'yes' : '',
			'mode'                        => isset( $settings['mode'] ) ? sanitize_key( (string) $settings['mode'] ) : 'rules',
			'layout'                      => isset( $settings['layout'] ) ? sanitize_key( (string) $settings['layout'] ) : 'card',
			'candidate_product_ids'       => array(),
			'required_cart_category_ids'  => array(),
			'exclude_cart_products'       => ( ! isset( $settings['exclude_cart_products'] ) || 'yes' === $settings['exclude_cart_products'] ) ? 'yes' : '',
			'minimum_cart_total'          => isset( $settings['minimum_cart_total'] ) ? (float) $settings['minimum_cart_total'] : 0.0,
			'maximum_cart_total'          => isset( $settings['maximum_cart_total'] ) ? (float) $settings['maximum_cart_total'] : 0.0,
			'customer_status'             => 'any',
			'device_type'                 => 'any',
			'maximum_product_price_ratio' => isset( $settings['maximum_product_price_ratio'] ) ? (float) $settings['maximum_product_price_ratio'] : 1.0,
			'priority_scores'             => array(),
			'suppressed_product_ids'      => array(),
			'show_dismiss'                => ( isset( $settings['show_dismiss'] ) && 'yes' === $settings['show_dismiss'] ) ? 'yes' : '',
			'success_message'             => isset( $settings['success_message'] ) ? sanitize_text_field( (string) $settings['success_message'] ) : '',
			'collapse_delay_ms'           => isset( $settings['collapse_delay_ms'] ) ? absint( $settings['collapse_delay_ms'] ) : 1200,
			'fallback_title'              => isset( $settings['fallback_title'] ) ? sanitize_text_field( (string) $settings['fallback_title'] ) : '',
			'fallback_description'        => isset( $settings['fallback_description'] ) ? sanitize_textarea_field( (string) $settings['fallback_description'] ) : '',
			'button_text'                 => isset( $settings['button_text'] ) ? sanitize_text_field( (string) $settings['button_text'] ) : '',
			'provider_source'             => isset( $settings['provider_source'] ) ? sanitize_key( (string) $settings['provider_source'] ) : '',
		);

		$candidate_ids = array();

		if ( isset( $settings['candidate_product_ids'] ) ) {
			$candidate_ids = self::normalize_ids_static( $settings['candidate_product_ids'] );
		} elseif ( isset( $settings['bewia_candidate_product_ids'] ) ) {
			$candidate_ids = self::normalize_ids_static( $settings['bewia_candidate_product_ids'] );
		}

		$priority_scores = array();

		if ( isset( $settings['priority_scores'] ) && is_array( $settings['priority_scores'] ) ) {
			$priority_scores = self::sanitize_priority_scores_static( $settings['priority_scores'] );
		} elseif ( isset( $settings['bewia_priority_scores'] ) && is_array( $settings['bewia_priority_scores'] ) ) {
			$priority_scores = self::sanitize_priority_scores_static( $settings['bewia_priority_scores'] );
		}

		$normalized['priority_scores'] = $priority_scores;
		$normalized['candidate_product_ids'] = array_values( array_unique( array_merge( $candidate_ids, array_map( 'absint', array_keys( $priority_scores ) ) ) ) );
		$normalized['required_cart_category_ids'] = isset( $settings['required_cart_category_ids'] ) ? self::normalize_ids_static( $settings['required_cart_category_ids'] ) : array();
		$normalized['suppressed_product_ids'] = isset( $settings['suppressed_product_ids'] ) ? self::normalize_ids_static( $settings['suppressed_product_ids'] ) : array();

		if ( isset( $settings['customer_status'] ) ) {
			$customer_status = sanitize_key( (string) $settings['customer_status'] );
			$normalized['customer_status'] = in_array( $customer_status, array( 'any', 'logged_in', 'guest' ), true ) ? $customer_status : 'any';
		}

		if ( isset( $settings['device_type'] ) ) {
			$device_type = sanitize_key( (string) $settings['device_type'] );
			$normalized['device_type'] = in_array( $device_type, array( 'any', 'desktop', 'mobile' ), true ) ? $device_type : 'any';
		}

		return $normalized;
	}

	public function get_cart_context( $frontend_signals = array(), $current_timestamp = null ) {
		$current_timestamp = null === $current_timestamp ? time() : absint( $current_timestamp );
		$frontend_signals  = self::normalize_frontend_signals( $frontend_signals, $current_timestamp );
		$context = array(
			'cart_total'               => 0.0,
			'product_ids'              => array(),
			'category_ids'             => array(),
			'is_logged_in'             => is_user_logged_in(),
			'customer_type'            => is_user_logged_in() ? 'new' : 'guest',
			'device_type'              => wp_is_mobile() ? 'mobile' : 'desktop',
			'coupon_codes'             => array(),
			'checkout_elapsed_seconds' => 0,
			'location_country'         => '',
			'scroll_depth'             => $frontend_signals['scroll_depth'],
		);

		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return $context;
		}

		$wc = WC();

		if ( ! $wc || empty( $wc->cart ) || ! is_object( $wc->cart ) ) {
			return $context;
		}

		$context['cart_total'] = (float) $wc->cart->get_total( 'edit' );
		$cart_items            = $wc->cart->get_cart();
		$category_ids          = array();

		if ( method_exists( $wc->cart, 'get_applied_coupons' ) ) {
			$context['coupon_codes'] = self::normalize_coupon_codes( $wc->cart->get_applied_coupons() );
		}

		if ( ! empty( $wc->customer ) && is_object( $wc->customer ) && method_exists( $wc->customer, 'get_billing_country' ) ) {
			$context['location_country'] = self::normalize_country_code( $wc->customer->get_billing_country() );
		}

		if ( $context['is_logged_in'] && function_exists( 'get_current_user_id' ) && function_exists( 'wc_get_customer_order_count' ) ) {
			$context['customer_type'] = wc_get_customer_order_count( get_current_user_id() ) > 0 ? 'returning' : 'new';
		}

		$checkout_started_at = self::get_checkout_started_at( $wc, $frontend_signals['checkout_started_at'], $current_timestamp );

		if ( $checkout_started_at > 0 ) {
			$context['checkout_elapsed_seconds'] = max( 0, $current_timestamp - $checkout_started_at );
		}

		if ( empty( $cart_items ) || ! is_array( $cart_items ) ) {
			return $context;
		}

		foreach ( $cart_items as $cart_item ) {
			if ( empty( $cart_item['product_id'] ) ) {
				continue;
			}

			$product_id = absint( $cart_item['product_id'] );

			if ( $product_id <= 0 ) {
				continue;
			}

			$context['product_ids'][] = $product_id;

			$product_category_ids = function_exists( 'wc_get_product_term_ids' ) ? wc_get_product_term_ids( $product_id, 'product_cat' ) : array();

			if ( is_array( $product_category_ids ) ) {
				$category_ids = array_merge( $category_ids, array_map( 'absint', $product_category_ids ) );
			}
		}

		$context['product_ids']  = array_values( array_unique( array_filter( $context['product_ids'] ) ) );
		$context['category_ids'] = array_values( array_unique( array_filter( $category_ids ) ) );

		return $context;
	}

	public function get_best_upsell( $settings = array(), $frontend_signals = array() ) {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
			return null;
		}

		$settings      = self::normalize_settings( $settings );
		$context       = $this->get_cart_context( $frontend_signals );
		$products      = $this->get_recommendations( 1, $settings, $context );
		return ! empty( $products ) ? $products[0] : null;
	}

	public static function normalize_frontend_signals( $signals, $current_timestamp = null ) {
		$current_timestamp = null === $current_timestamp ? time() : absint( $current_timestamp );
		$normalized        = array(
			'scroll_depth'         => 0,
			'checkout_started_at' => 0,
		);

		if ( ! is_array( $signals ) ) {
			return $normalized;
		}

		if ( isset( $signals['scroll_depth'] ) && is_numeric( $signals['scroll_depth'] ) ) {
			$scroll_depth = (int) $signals['scroll_depth'];

			if ( $scroll_depth >= 0 && $scroll_depth <= 100 ) {
				$normalized['scroll_depth'] = $scroll_depth;
			}
		}

		if ( isset( $signals['checkout_started_at'] ) && is_numeric( $signals['checkout_started_at'] ) ) {
			$checkout_started_at = (int) $signals['checkout_started_at'];

			if ( $checkout_started_at > 0 && $checkout_started_at <= $current_timestamp && $checkout_started_at >= ( $current_timestamp - DAY_IN_SECONDS ) ) {
				$normalized['checkout_started_at'] = $checkout_started_at;
			}
		}

		return $normalized;
	}


	public function score_product( $product_id, $context, $settings ) {
		$product_id = absint( $product_id );
		$settings   = self::normalize_settings( $settings );

		if ( $product_id <= 0 || ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
			return -9999;
		}

		$product = wc_get_product( $product_id );

		if ( ! $product ) {
			return -9999;
		}

		$score                = 0.0;
		$cart_total           = isset( $context['cart_total'] ) ? (float) $context['cart_total'] : 0.0;
		$cart_category_ids    = isset( $context['category_ids'] ) && is_array( $context['category_ids'] ) ? array_map( 'absint', $context['category_ids'] ) : array();
		$product_category_ids = function_exists( 'wc_get_product_term_ids' ) ? wc_get_product_term_ids( $product_id, 'product_cat' ) : array();
		$product_category_ids = is_array( $product_category_ids ) ? array_map( 'absint', $product_category_ids ) : array();
		$product_price        = (float) $product->get_price();
		$maximum_price_ratio  = isset( $settings['maximum_product_price_ratio'] ) ? (float) $settings['maximum_product_price_ratio'] : 1.0;
		$minimum_total        = isset( $settings['minimum_cart_total'] ) ? (float) $settings['minimum_cart_total'] : 0.0;
		$priority_map         = $this->bewia_get_priority_map( $settings );
		$matched_categories   = array_intersect( $product_category_ids, $cart_category_ids );

		if ( $cart_total > 0 && $product_price > 0 && $maximum_price_ratio > 0 ) {
			$configured_ratio = $product_price / max( $cart_total, 1 );

			if ( $configured_ratio > $maximum_price_ratio ) {
				return -9999;
			}

			if ( $configured_ratio <= max( 0.1, $maximum_price_ratio * 0.6 ) ) {
				$score += 10;
			} elseif ( $configured_ratio <= max( 0.15, $maximum_price_ratio * 0.85 ) ) {
				$score += 4;
			}
		}

		if ( ! empty( $matched_categories ) ) {
			$score += 20;
			$score += count( $matched_categories ) * 5;
		}

		if ( $minimum_total > 0 && $cart_total >= $minimum_total ) {
			$score += 15;
		}

		if ( $cart_total > 0 && $product_price > 0 ) {
			$price_ratio = $product_price / max( $cart_total, 1 );

			if ( $price_ratio > 1 ) {
				$score -= 25;
			} elseif ( $price_ratio > 0.75 ) {
				$score -= 12;
			} elseif ( $price_ratio <= 0.35 ) {
				$score += 8;
			}
		}

		if ( isset( $priority_map[ $product_id ] ) ) {
			$score += (float) $priority_map[ $product_id ];
		}

		return (float) apply_filters( 'bewia_ai_upsell_score', $score, $product_id, $context, $settings, $product );
	}

	public function format_upsell_response( $product ) {
		if ( ! $product || ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) {
			return array();
		}

		$image_html = '';

		if ( method_exists( $product, 'get_image' ) ) {
			$image_html = $product->get_image( 'woocommerce_thumbnail' );
		}

		return array(
			'product_id'        => absint( $product->get_id() ),
			'title'             => wp_strip_all_tags( $product->get_name() ),
			'price_html'        => wp_kses_post( $product->get_price_html() ),
			'image_html'        => wp_kses_post( $image_html ),
			'short_description' => wp_kses_post( $product->get_short_description() ),
			'add_to_cart_text'  => wp_strip_all_tags( $product->add_to_cart_text() ),
		);
	}

	public function get_recommendations( $limit = 3, $settings = array(), $context = null ) {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
			return array();
		}

		$settings      = self::normalize_settings( $settings );
		$context       = is_array( $context ) ? $context : $this->get_cart_context();
		$provider      = $this->resolve_provider( $settings );

		if ( ! $provider ) {
			return array();
		}

		$results = $provider->get_recommendations( $settings, $context, $limit );

		if ( ! empty( $results ) ) {
			$this->last_provider_source = isset( $settings['mode'] ) ? sanitize_key( (string) $settings['mode'] ) : 'rules';
			return $results;
		}

		if ( $this->should_fallback_to_rules( $settings ) ) {
			$rule_provider = $this->get_rule_provider();

			if ( $rule_provider ) {
				$this->last_provider_source = 'rules_fallback';
				return $rule_provider->get_recommendations( $settings, $context, $limit );
			}
		}

		return array();
	}

	public function get_last_provider_source() {
		return sanitize_key( (string) $this->last_provider_source );
	}

	public function get_candidate_product_ids( $settings, $context ) {
		$candidate_ids = array();

		if ( isset( $settings['candidate_product_ids'] ) ) {
			$candidate_ids = $this->bewia_normalize_ids( $settings['candidate_product_ids'] );
		} elseif ( isset( $settings['bewia_candidate_product_ids'] ) ) {
			$candidate_ids = $this->bewia_normalize_ids( $settings['bewia_candidate_product_ids'] );
		}

		if ( empty( $candidate_ids ) ) {
			$candidate_ids = $this->bewia_get_fallback_candidate_ids( $context );
		}

		$candidate_ids = array_values( array_unique( array_filter( array_map( 'absint', $candidate_ids ) ) ) );

		return apply_filters( 'bewia_ai_upsell_candidate_ids', $candidate_ids, $settings, $context );
	}

	public function matches_rule_groups( $settings, $context ) {
		$cart_total                 = isset( $context['cart_total'] ) ? (float) $context['cart_total'] : 0.0;
		$cart_category_ids          = isset( $context['category_ids'] ) && is_array( $context['category_ids'] ) ? array_map( 'absint', $context['category_ids'] ) : array();
		$required_cart_category_ids = isset( $settings['required_cart_category_ids'] ) ? $this->bewia_normalize_ids( $settings['required_cart_category_ids'] ) : array();
		$minimum_cart_total         = isset( $settings['minimum_cart_total'] ) ? (float) $settings['minimum_cart_total'] : 0.0;
		$maximum_cart_total         = isset( $settings['maximum_cart_total'] ) ? (float) $settings['maximum_cart_total'] : 0.0;
		$customer_status            = isset( $settings['customer_status'] ) ? sanitize_key( (string) $settings['customer_status'] ) : 'any';
		$required_device_type       = isset( $settings['device_type'] ) ? sanitize_key( (string) $settings['device_type'] ) : 'any';
		$is_logged_in               = ! empty( $context['is_logged_in'] );
		$device_type                = isset( $context['device_type'] ) ? sanitize_key( (string) $context['device_type'] ) : 'desktop';

		if ( ! empty( $required_cart_category_ids ) && empty( array_intersect( $required_cart_category_ids, $cart_category_ids ) ) ) {
			return false;
		}

		if ( $minimum_cart_total > 0 && $cart_total < $minimum_cart_total ) {
			return false;
		}

		if ( $maximum_cart_total > 0 && $cart_total > $maximum_cart_total ) {
			return false;
		}

		if ( 'logged_in' === $customer_status && ! $is_logged_in ) {
			return false;
		}

		if ( 'guest' === $customer_status && $is_logged_in ) {
			return false;
		}

		if ( 'any' !== $required_device_type && $required_device_type !== $device_type ) {
			return false;
		}

		return (bool) apply_filters( 'bewia_ai_upsell_rule_match', true, $settings, $context );
	}

	protected function resolve_provider( $settings ) {
		$mode          = isset( $settings['mode'] ) ? sanitize_key( (string) $settings['mode'] ) : 'rules';
		$provider_key  = in_array( $mode, array( 'rules', 'ai', 'hybrid' ), true ) ? $mode : 'rules';
		$class_map     = array(
			'rules'  => __NAMESPACE__ . '\BEWIA_AI_Upsell_Rule_Provider',
			'ai'     => __NAMESPACE__ . '\BEWIA_AI_Upsell_AI_Provider',
			'hybrid' => __NAMESPACE__ . '\BEWIA_AI_Upsell_Hybrid_Provider',
		);
		$class_name    = isset( $class_map[ $provider_key ] ) ? $class_map[ $provider_key ] : $class_map['rules'];
		$class_name    = apply_filters( 'bewia_ai_upsell_provider_class', $class_name, $provider_key, $settings, $this );
		$this->last_provider_source = $provider_key;

		if ( empty( $class_name ) || ! class_exists( $class_name ) ) {
			return null;
		}

		if ( ! isset( $this->provider_instances[ $class_name ] ) ) {
			$this->provider_instances[ $class_name ] = new $class_name( $this );
		}

		return $this->provider_instances[ $class_name ];
	}

	protected function get_rule_provider() {
		$class_name = apply_filters(
			'bewia_ai_upsell_rule_provider_class',
			__NAMESPACE__ . '\BEWIA_AI_Upsell_Rule_Provider',
			$this
		);

		if ( empty( $class_name ) || ! class_exists( $class_name ) ) {
			return null;
		}

		if ( ! isset( $this->provider_instances[ $class_name ] ) ) {
			$this->provider_instances[ $class_name ] = new $class_name( $this );
		}

		return $this->provider_instances[ $class_name ];
	}

	protected function should_fallback_to_rules( $settings ) {
		$mode = isset( $settings['mode'] ) ? sanitize_key( (string) $settings['mode'] ) : 'rules';

		return in_array( $mode, array( 'ai', 'hybrid' ), true );
	}

	private function bewia_get_fallback_candidate_ids( $context ) {
		$candidate_map = array();
		$product_ids   = isset( $context['product_ids'] ) && is_array( $context['product_ids'] ) ? $context['product_ids'] : array();

		foreach ( $product_ids as $product_id ) {
			$product = wc_get_product( $product_id );

			if ( ! $product ) {
				continue;
			}

			$this->bewia_add_candidate_scores( $candidate_map, (array) $product->get_upsell_ids(), 5 );
			$this->bewia_add_candidate_scores( $candidate_map, (array) $product->get_cross_sell_ids(), 4 );

			if ( function_exists( 'wc_get_related_products' ) ) {
				$related_ids = wc_get_related_products( $product_id, 6 );
				$this->bewia_add_candidate_scores( $candidate_map, (array) $related_ids, 2 );
			}
		}

		foreach ( $product_ids as $product_id ) {
			unset( $candidate_map[ $product_id ] );
		}

		arsort( $candidate_map );

		return array_keys( $candidate_map );
	}

	private function bewia_get_priority_map( $settings ) {
		$priority_map = array();

		if ( isset( $settings['priority_scores'] ) && is_array( $settings['priority_scores'] ) ) {
			$priority_map = $settings['priority_scores'];
		} elseif ( isset( $settings['bewia_priority_scores'] ) && is_array( $settings['bewia_priority_scores'] ) ) {
			$priority_map = $settings['bewia_priority_scores'];
		}

		$normalized = array();

		foreach ( $priority_map as $product_id => $score ) {
			$product_id = absint( $product_id );

			if ( $product_id <= 0 ) {
				continue;
			}

			$normalized[ $product_id ] = (float) $score;
		}

		return $normalized;
	}

	private function bewia_normalize_ids( $raw_ids ) {
		return self::normalize_ids_static( $raw_ids );
	}

	private static function normalize_ids_static( $raw_ids ) {
		if ( is_string( $raw_ids ) ) {
			$raw_ids = preg_split( '/[\s,]+/', $raw_ids );
		}

		if ( ! is_array( $raw_ids ) ) {
			return array();
		}

		return array_values( array_unique( array_filter( array_map( 'absint', $raw_ids ) ) ) );
	}

	private static function sanitize_priority_scores_static( $priority_scores ) {
		$sanitized = array();

		foreach ( $priority_scores as $product_id => $score ) {
			$product_id = absint( $product_id );

			if ( $product_id <= 0 ) {
				continue;
			}

			$sanitized[ $product_id ] = (float) $score;
		}

		return $sanitized;
	}

	private function bewia_add_candidate_scores( &$candidate_map, $ids, $score ) {
		foreach ( $ids as $id ) {
			$id = absint( $id );

			if ( $id <= 0 ) {
				continue;
			}

			if ( ! isset( $candidate_map[ $id ] ) ) {
				$candidate_map[ $id ] = 0;
			}

			$candidate_map[ $id ] += (int) $score;
		}
	}
	private static function normalize_coupon_codes( $coupon_codes ) {
		if ( ! is_array( $coupon_codes ) ) {
			return array();
		}

		return array_values( array_unique( array_filter( array_map( 'sanitize_key', $coupon_codes ) ) ) );
	}

	private static function normalize_country_code( $country ) {
		$country = strtoupper( sanitize_key( (string) $country ) );

		return preg_match( '/^[A-Z]{2}$/', $country ) ? $country : '';
	}

	private static function get_checkout_started_at( $wc, $frontend_checkout_started_at, $current_timestamp ) {
		$checkout_started_at = 0;

		if ( ! empty( $wc->session ) && is_object( $wc->session ) && method_exists( $wc->session, 'get' ) ) {
			$session_value = $wc->session->get( 'bewia_checkout_started_at', 0 );

			if ( is_numeric( $session_value ) ) {
				$session_value = (int) $session_value;

				if ( $session_value > 0 && $session_value <= $current_timestamp && $session_value >= ( $current_timestamp - DAY_IN_SECONDS ) ) {
					$checkout_started_at = $session_value;
				}
			}
		}

		if ( $checkout_started_at <= 0 && $frontend_checkout_started_at > 0 ) {
			$checkout_started_at = $frontend_checkout_started_at;

			if ( ! empty( $wc->session ) && is_object( $wc->session ) && method_exists( $wc->session, 'set' ) ) {
				$wc->session->set( 'bewia_checkout_started_at', $checkout_started_at );
			}
		}

		return $checkout_started_at;
	}
}
