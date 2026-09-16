<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;
if ( ! defined( 'ABSPATH' ) ) { exit; }

class BEWIA_AI_Upsell_Copy_Generator {
	const PROMPT_VERSION = '1';
	const MAX_TITLE = 80;
	const MAX_DESCRIPTION = 240;
	const MAX_CTA = 40;
	const MAX_URGENCY = 80;

	public function generate( $product, array $context, array $settings ) {
		$fallback = $this->fallback( $product, $settings );
		if ( ! is_object( $product ) || ! method_exists( $product, 'get_id' ) ) { return $fallback; }
		$key = $this->cache_key( $product, $context, $settings );
		if ( function_exists( 'get_transient' ) ) {
			$cached = get_transient( $key );
			if ( is_array( $cached ) ) { return $this->sanitize( array_merge( $fallback, $cached ) ); }
		}
		$client = apply_filters( 'bewia_ai_upsell_copy_client', null, $settings, $product, $context );
		if ( ! $client ) { $client = isset( $settings['copy_client'] ) && is_object( $settings['copy_client'] ) ? $settings['copy_client'] : null; }
		if ( ! $client || ! method_exists( $client, 'generate' ) ) { return $fallback; }
		$result = $client->generate( array( 'product' => array( 'id' => absint( $product->get_id() ), 'name' => $this->product_name( $product ) ), 'context' => $this->context_bucket( $context ) ) );
		if ( is_wp_error( $result ) || ! is_array( $result ) ) { return $fallback; }
		$result = $this->sanitize( array_merge( $fallback, $result ) );
		if ( function_exists( 'set_transient' ) ) { set_transient( $key, $result, HOUR_IN_SECONDS ); }
		return $result;
	}

	protected function fallback( $product, $settings ) {
		return $this->sanitize( array( 'title' => isset( $settings['fallback_title'] ) ? $settings['fallback_title'] : ( is_object( $product ) && method_exists( $product, 'get_name' ) ? $product->get_name() : 'Recommended for your order' ), 'description' => isset( $settings['fallback_description'] ) ? $settings['fallback_description'] : '', 'cta' => isset( $settings['button_text'] ) ? $settings['button_text'] : 'Add this offer', 'urgency' => '' ) );
	}
	protected function product_name( $product ) { return method_exists( $product, 'get_name' ) ? sanitize_text_field( $product->get_name() ) : ''; }
	protected function sanitize( $copy ) {
		$limits = array( 'title' => self::MAX_TITLE, 'description' => self::MAX_DESCRIPTION, 'cta' => self::MAX_CTA, 'urgency' => self::MAX_URGENCY );
		foreach ( $limits as $key => $limit ) { $value = isset( $copy[ $key ] ) ? wp_strip_all_tags( (string) $copy[ $key ] ) : ''; $copy[ $key ] = function_exists( 'mb_substr' ) ? mb_substr( trim( $value ), 0, $limit ) : substr( trim( $value ), 0, $limit ); }
		return array_intersect_key( $copy, $limits );
	}
	protected function context_bucket( $context ) { return array( 'customer_type' => isset( $context['customer_type'] ) ? sanitize_key( $context['customer_type'] ) : 'guest', 'device_type' => isset( $context['device_type'] ) ? sanitize_key( $context['device_type'] ) : 'desktop', 'cart_total_bucket' => floor( (float) ( isset( $context['cart_total'] ) ? $context['cart_total'] : 0 ) / 25 ) ); }
	protected function cache_key( $product, $context, $settings ) { $locale = function_exists( 'determine_locale' ) ? determine_locale() : ( function_exists( 'get_locale' ) ? get_locale() : 'en_US' ); return 'bewia_copy_' . md5( absint( $product->get_id() ) . '|' . $locale . '|' . serialize( $this->context_bucket( $context ) ) . '|' . self::PROMPT_VERSION ); }
}
