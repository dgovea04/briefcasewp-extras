<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class BEWIA_AI_Upsell_Ajax {

	public function __construct() {
		add_action( 'wp_ajax_bcwp_get_ai_upsell', array( $this, 'get_ai_upsell' ) );
		add_action( 'wp_ajax_nopriv_bcwp_get_ai_upsell', array( $this, 'get_ai_upsell' ) );
		add_action( 'wp_ajax_bcwp_accept_ai_upsell', array( $this, 'accept_ai_upsell' ) );
		add_action( 'wp_ajax_nopriv_bcwp_accept_ai_upsell', array( $this, 'accept_ai_upsell' ) );
		add_action( 'wp_ajax_bcwp_dismiss_ai_upsell', array( $this, 'dismiss_ai_upsell' ) );
		add_action( 'wp_ajax_nopriv_bcwp_dismiss_ai_upsell', array( $this, 'dismiss_ai_upsell' ) );
		add_action( 'wp_ajax_bcwp_track_ai_upsell_rendered', array( $this, 'track_ai_upsell_rendered' ) );
		add_action( 'wp_ajax_nopriv_bcwp_track_ai_upsell_rendered', array( $this, 'track_ai_upsell_rendered' ) );
	}

	public function get_ai_upsell() {
		$this->bewia_verify_nonce();

		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'WooCommerce is not available.', 'bew-extras' ),
				),
				400
			);
		}

		$settings = $this->bewia_get_server_settings_for_request();
		$settings['suppressed_product_ids'] = array_values( array_unique( array_merge( $settings['suppressed_product_ids'], $this->bewia_get_suppressed_product_ids() ) ) );
		$engine   = new BEWIA_AI_Upsell_Engine();
		$product  = $engine->get_best_upsell( $settings, $this->bewia_get_frontend_signals_from_post() );

		if ( ! $product ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'No upsell product found.', 'bew-extras' ),
				),
				404
			);
		}
		$offer_id = sha1( BEWIA_AI_Upsell_Analytics::get_session_id() . '|' . $settings['campaign_key'] . '|' . ( isset( $settings['variant_id'] ) ? $settings['variant_id'] : '' ) . '|' . $product->get_id() );
		$offer_signature = $this->bewia_sign_offer( $offer_id );
		BEWIA_AI_Upsell_Experiments::register_emitted_offer( BEWIA_AI_Upsell_Analytics::get_session_id(), array( 'product_id' => $product->get_id(), 'campaign_key' => $settings['campaign_key'], 'variant_id' => isset( $settings['variant_id'] ) ? $settings['variant_id'] : '', 'offer_id' => $offer_id, 'offer_signature' => $offer_signature ) );

		if ( class_exists( __NAMESPACE__ . '\BEWIA_AI_Upsell_Analytics' ) ) {
			$analytics_data                 = $this->bewia_build_analytics_data( $settings, $product->get_id() );
			$analytics_data['event_window'] = 300;
			BEWIA_AI_Upsell_Analytics::log_event( 'shown', $analytics_data );
		}

		wp_send_json_success(
			array(
				'product'         => $engine->format_upsell_response( $product ),
				'provider_source' => $engine->get_last_provider_source(),
				'campaign_key'    => $settings['campaign_key'],
				'variant_id'      => isset( $settings['variant_id'] ) ? $settings['variant_id'] : '',
				'offer_id'        => $offer_id,
				'offer_signature' => $offer_signature,
			)
		);
	}

	public function accept_ai_upsell() {
		$this->bewia_verify_nonce();

		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'WooCommerce is not available.', 'bew-extras' ),
				),
				400
			);
		}

		$wc = WC();

		if ( ! $wc || empty( $wc->cart ) || ! is_object( $wc->cart ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Cart is not available.', 'bew-extras' ),
				),
				400
			);
		}

		$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
		$quantity   = isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : 1;

		if ( $product_id <= 0 ) {
			$this->bewia_log_failed_event( 0 );
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Invalid product.', 'bew-extras' ),
				),
				400
			);
		}

		if ( $quantity <= 0 ) {
			$quantity = 1;
		}

		$settings = $this->bewia_get_settings_from_post();
		if ( ! BEWIA_AI_Upsell_Experiments::is_emitted_offer_valid( BEWIA_AI_Upsell_Analytics::get_session_id(), array( 'product_id' => $product_id, 'campaign_key' => $settings['campaign_key'], 'variant_id' => isset( $settings['variant_id'] ) ? $settings['variant_id'] : '' ) ) ) {
			$this->bewia_log_failed_event( $product_id, $settings );
			wp_send_json_error( array( 'message' => esc_html__( 'This upsell offer is no longer valid.', 'bew-extras' ) ), 403 );
		}
		$settings['suppressed_product_ids'] = array_values( array_unique( array_merge( $settings['suppressed_product_ids'], $this->bewia_get_suppressed_product_ids() ) ) );

		if ( $this->bewia_product_exists_in_cart( $product_id ) && ( ! isset( $settings['exclude_cart_products'] ) || 'yes' === $settings['exclude_cart_products'] ) ) {
			$this->bewia_suppress_product( $product_id );
			$this->bewia_log_failed_event( $product_id, $settings );
			$this->bewia_send_rotated_response( $settings, esc_html__( 'This upsell is already in the cart.', 'bew-extras' ), 409 );
		}

		$product = wc_get_product( $product_id );

		if ( ! $product || ! $product->is_purchasable() || ! $product->is_in_stock() ) {
			$this->bewia_suppress_product( $product_id );
			$this->bewia_log_failed_event( $product_id );
			$this->bewia_send_rotated_response( $settings, esc_html__( 'This product cannot be added.', 'bew-extras' ), 400 );
		}

		$offer_identity = BEWIA_AI_Upsell_Analytics::normalize_offer_identity( array(
			'product_id' => $product_id,
			'mode' => $settings['mode'],
			'provider_source' => $settings['provider_source'],
			'campaign_key' => $settings['campaign_key'],
			'variant_id' => isset( $settings['variant_id'] ) ? $settings['variant_id'] : '',
			'session_id' => BEWIA_AI_Upsell_Analytics::get_session_id(),
			'offer_id' => sha1( BEWIA_AI_Upsell_Analytics::get_session_id() . '|' . $settings['campaign_key'] . '|' . ( isset( $settings['variant_id'] ) ? $settings['variant_id'] : '' ) . '|' . $product_id ),
		) );
		$cart_item_key = $wc->cart->add_to_cart( $product_id, $quantity, 0, array(), array( '_bewia_offer_identity' => $offer_identity ) );

		if ( ! $cart_item_key ) {
			$this->bewia_suppress_product( $product_id );
			$this->bewia_log_failed_event( $product_id );
			$this->bewia_send_rotated_response( $settings, esc_html__( 'Unable to add product to cart.', 'bew-extras' ), 400 );
		}

		$this->bewia_suppress_product( $product_id );

		if ( class_exists( __NAMESPACE__ . '\BEWIA_AI_Upsell_Analytics' ) ) {
			BEWIA_AI_Upsell_Analytics::log_event(
				'accepted',
				$this->bewia_build_analytics_data( $settings, $product_id, (float) $product->get_price() * (float) $quantity )
			);
		}

		wp_send_json_success(
			array(
				'message'    => esc_html__( 'Upsell added to cart.', 'bew-extras' ),
				'product_id' => $product_id,
				'quantity'   => $quantity,
				'fragments'  => $this->bewia_get_cart_fragments(),
				'cart_hash'  => $wc->cart->get_cart_hash(),
			)
		);
	}

	public function dismiss_ai_upsell() {
		$this->bewia_verify_nonce();

		$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
		$settings   = $this->bewia_get_settings_from_post();

		if ( $product_id > 0 ) {
			$this->bewia_suppress_product( $product_id );
		}

		if ( class_exists( __NAMESPACE__ . '\BEWIA_AI_Upsell_Analytics' ) && $product_id > 0 ) {
			BEWIA_AI_Upsell_Analytics::log_event(
				'dismissed',
				$this->bewia_build_analytics_data( $settings, $product_id )
			);
		}

		$settings['suppressed_product_ids'] = array_values( array_unique( array_merge( $settings['suppressed_product_ids'], $this->bewia_get_suppressed_product_ids() ) ) );
		$this->bewia_send_rotated_response( $settings, esc_html__( 'Offer dismissed.', 'bew-extras' ), 200 );
	}

	public function track_ai_upsell_rendered() {
		$this->bewia_verify_nonce();

		$product_id = isset( $_POST['product_id'] ) ? absint( wp_unslash( $_POST['product_id'] ) ) : 0;
		$settings   = $this->bewia_get_settings_from_post();

		if ( $product_id > 0 && class_exists( __NAMESPACE__ . '\BEWIA_AI_Upsell_Analytics' ) ) {
			$analytics_data                 = $this->bewia_build_analytics_data( $settings, $product_id );
			$analytics_data['event_window'] = 300;
			BEWIA_AI_Upsell_Analytics::log_event( 'rendered', $analytics_data );
		}

		wp_send_json_success( array( 'tracked' => true ) );
	}

	private function bewia_verify_nonce() {
		$nonce = isset( $_POST['nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['nonce'] ) ) : '';

		if ( empty( $nonce ) || ! wp_verify_nonce( $nonce, 'bewia_ai_smart_upsells' ) ) {
			wp_send_json_error(
				array(
					'message' => esc_html__( 'Security check failed.', 'bew-extras' ),
				),
				403
			);
		}
	}

	private function bewia_get_settings_from_post() {
		$raw_settings = array();

		if ( isset( $_POST['settings'] ) ) {
			$raw_settings = wp_unslash( $_POST['settings'] );
		}

		if ( is_string( $raw_settings ) ) {
			$decoded = json_decode( $raw_settings, true );
			$raw_settings = is_array( $decoded ) ? $decoded : array();
		}

		if ( ! is_array( $raw_settings ) ) {
			$raw_settings = array();
		}

		$settings = BEWIA_AI_Upsell_Engine::normalize_settings( $raw_settings );
		$variant = BEWIA_AI_Upsell_Experiments::assign_variant( $settings['campaign_key'], '', $settings['variants'] );
		return $this->bewia_apply_variant( $settings, $variant );
	}

	/**
	 * Resolve offer eligibility from trusted configuration, never from mutable POST data.
	 * Presentation/context settings may still come from the widget request.
	 */
	private function bewia_get_server_settings_for_request() {
		$server = class_exists( __NAMESPACE__ . '\BEWIA_AI_Upsell_Analytics' ) ? BEWIA_AI_Upsell_Analytics::get_saved_settings() : array();
		$server = BEWIA_AI_Upsell_Engine::normalize_settings( is_array( $server ) ? $server : array() );

		$variant = BEWIA_AI_Upsell_Experiments::assign_variant( $server['campaign_key'], '', $server['variants'] );
		return $this->bewia_apply_variant( $server, $variant );
	}

	private function bewia_sign_offer( $offer_id ) {
		$secret = function_exists( 'wp_salt' ) ? wp_salt( 'auth' ) : ( defined( 'AUTH_KEY' ) ? AUTH_KEY : 'bewia-offer' );
		return hash_hmac( 'sha256', (string) $offer_id, $secret );
	}

	private function bewia_get_frontend_signals_from_post() {
		return array(
			'scroll_depth'         => isset( $_POST['scroll_depth'] ) ? wp_unslash( $_POST['scroll_depth'] ) : null,
			'checkout_started_at' => isset( $_POST['checkout_started_at'] ) ? wp_unslash( $_POST['checkout_started_at'] ) : null,
		);
	}

	private function bewia_send_rotated_response( $settings, $message = '', $status_code = 200 ) {


		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'wc_get_product' ) ) {
			wp_send_json_error(
				array(
					'message' => $message ? $message : esc_html__( 'Upsell rotation is unavailable right now.', 'bew-extras' ),
				),
				400
			);
		}

		$engine  = new BEWIA_AI_Upsell_Engine();
		$product = $engine->get_best_upsell( $settings );

		if ( $product ) {
			wp_send_json_success(
				array(
					'message' => $message,
					'product' => $engine->format_upsell_response( $product ),
				),
				$status_code
			);
		}

		wp_send_json_error(
			array(
				'message' => $message ? $message : esc_html__( 'No upsell product found.', 'bew-extras' ),
			),
			404
		);
	}

	private function bewia_build_analytics_data( $settings = array(), $product_id = 0, $revenue = null ) {
		$settings = BEWIA_AI_Upsell_Engine::normalize_settings( is_array( $settings ) ? $settings : array() );

		return array(
			'product_id' => absint( $product_id ),
			'session_id' => BEWIA_AI_Upsell_Analytics::get_session_id(),
			'cart_total' => BEWIA_AI_Upsell_Analytics::get_cart_total(),
			'layout'     => isset( $settings['layout'] ) ? sanitize_text_field( (string) $settings['layout'] ) : '',
			'mode'       => isset( $settings['mode'] ) ? sanitize_text_field( (string) $settings['mode'] ) : '',
			'provider_source' => isset( $settings['provider_source'] ) ? sanitize_text_field( (string) $settings['provider_source'] ) : '',
			'confidence' => isset( $settings['confidence'] ) ? (float) $settings['confidence'] : null,
			'customer_type' => isset( $settings['customer_type'] ) ? sanitize_key( $settings['customer_type'] ) : '',
			'device_type' => isset( $settings['device_type'] ) ? sanitize_key( $settings['device_type'] ) : '',
			'country' => isset( $settings['country'] ) ? sanitize_text_field( $settings['country'] ) : '',
			'campaign_key' => isset( $settings['campaign_key'] ) ? sanitize_key( $settings['campaign_key'] ) : '',
			'variant_id' => isset( $settings['variant_id'] ) ? sanitize_key( $settings['variant_id'] ) : '',
			'offer_id' => $product_id ? sha1( BEWIA_AI_Upsell_Analytics::get_session_id() . '|' . ( isset( $settings['campaign_key'] ) ? $settings['campaign_key'] : '' ) . '|' . ( isset( $settings['variant_id'] ) ? $settings['variant_id'] : '' ) . '|' . $product_id ) : '',
			'revenue'    => null === $revenue ? null : (float) $revenue,
		);
	}

	private function bewia_apply_variant( $settings, $variant ) {
		if ( empty( $variant ) ) { return $settings; }
		$settings['variant_id'] = $variant['variant_id'];
		if ( ! empty( $variant['product_ids'] ) ) { $settings['candidate_product_ids'] = $variant['product_ids']; }
		$settings['layout'] = $variant['layout'];
		foreach ( array( 'title' => 'fallback_title', 'description' => 'fallback_description', 'cta' => 'button_text' ) as $copy_key => $setting_key ) { if ( isset( $variant['copy'][ $copy_key ] ) ) { $settings[ $setting_key ] = $variant['copy'][ $copy_key ]; } }
		return BEWIA_AI_Upsell_Engine::normalize_settings( $settings );
	}

	private function bewia_log_failed_event( $product_id = 0, $settings = array() ) {
		if ( class_exists( __NAMESPACE__ . '\BEWIA_AI_Upsell_Analytics' ) ) {
			BEWIA_AI_Upsell_Analytics::log_event( 'failed', $this->bewia_build_analytics_data( $settings, $product_id ) );
		}
	}

	private function bewia_get_cart_fragments() {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return array();
		}

		$wc = WC();

		if ( ! $wc || empty( $wc->cart ) || ! is_object( $wc->cart ) ) {
			return array();
		}

		$fragments = array(
			'cart_count' => $wc->cart->get_cart_contents_count(),
			'cart_total' => wp_kses_post( $wc->cart->get_cart_total() ),
		);

		if ( function_exists( 'woocommerce_mini_cart' ) ) {
			ob_start();
			woocommerce_mini_cart();
			$mini_cart = ob_get_clean();

			$fragments['fragments'] = apply_filters(
				'woocommerce_add_to_cart_fragments',
				array(
					'div.widget_shopping_cart_content' => '<div class="widget_shopping_cart_content">' . $mini_cart . '</div>',
				)
			);
		}

		return $fragments;
	}

	private function bewia_get_suppressed_product_ids() {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return array();
		}

		$wc = WC();

		if ( ! $wc || ! isset( $wc->session ) || ! is_object( $wc->session ) || ! method_exists( $wc->session, 'get' ) ) {
			return array();
		}

		$suppressed = $wc->session->get( 'bewia_suppressed_product_ids', array() );

		if ( is_string( $suppressed ) ) {
			$suppressed = preg_split( '/[\s,]+/', $suppressed );
		}

		return is_array( $suppressed ) ? array_values( array_unique( array_filter( array_map( 'absint', $suppressed ) ) ) ) : array();
	}

	private function bewia_suppress_product( $product_id ) {
		$product_id = absint( $product_id );

		if ( $product_id <= 0 || ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return;
		}

		$wc = WC();

		if ( ! $wc || ! isset( $wc->session ) || ! is_object( $wc->session ) || ! method_exists( $wc->session, 'set' ) ) {
			return;
		}

		$suppressed   = $this->bewia_get_suppressed_product_ids();
		$suppressed[] = $product_id;
		$wc->session->set( 'bewia_suppressed_product_ids', array_values( array_unique( array_filter( array_map( 'absint', $suppressed ) ) ) ) );
	}

	private function bewia_product_exists_in_cart( $product_id ) {
		if ( ! class_exists( 'WooCommerce' ) || ! function_exists( 'WC' ) ) {
			return false;
		}

		$wc = WC();

		if ( ! $wc || empty( $wc->cart ) || ! is_object( $wc->cart ) ) {
			return false;
		}

		foreach ( $wc->cart->get_cart() as $cart_item ) {
			if ( isset( $cart_item['product_id'] ) && absint( $cart_item['product_id'] ) === absint( $product_id ) ) {
				return true;
			}
		}

		return false;
	}
}

new BEWIA_AI_Upsell_Ajax();
