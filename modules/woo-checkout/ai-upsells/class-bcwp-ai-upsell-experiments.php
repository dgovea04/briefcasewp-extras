<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;

if ( ! defined( 'ABSPATH' ) ) { exit; }

class BEWIA_AI_Upsell_Experiments {
	const SESSION_KEY = 'bewia_ai_upsell_variants';
	const OFFER_KEY = 'bewia_ai_upsell_emitted_offers';

	public static function normalize_variants( $variants ) {
		if ( ! is_array( $variants ) ) { return array(); }
		$normalized = array();
		foreach ( $variants as $variant ) {
			if ( ! is_array( $variant ) ) { continue; }
			$id = sanitize_key( isset( $variant['variant_id'] ) ? $variant['variant_id'] : '' );
			if ( '' === $id ) { continue; }
			$products = array_filter( array_map( 'absint', (array) ( isset( $variant['product_ids'] ) ? $variant['product_ids'] : array() ) ) );
			$layout = isset( $variant['layout'] ) && in_array( sanitize_key( $variant['layout'] ), array( 'card', 'checkbox', 'minimal' ), true ) ? sanitize_key( $variant['layout'] ) : 'card';
			$copy = array();
			if ( isset( $variant['copy'] ) && is_array( $variant['copy'] ) ) {
				foreach ( array( 'title', 'description', 'cta', 'urgency' ) as $key ) {
					if ( isset( $variant['copy'][ $key ] ) ) { $text = function_exists( 'wp_strip_all_tags' ) ? wp_strip_all_tags( (string) $variant['copy'][ $key ] ) : strip_tags( (string) $variant['copy'][ $key ] ); $copy[ $key ] = sanitize_text_field( $text ); }
				}
			}
			$normalized[] = array( 'variant_id' => $id, 'product_ids' => array_values( array_unique( $products ) ), 'layout' => $layout, 'copy' => $copy );
		}
		return $normalized;
	}

	public static function assign_variant( $campaign_key, $session_id, $variants ) {
		$campaign_key = sanitize_key( $campaign_key );
		$variants = self::normalize_variants( $variants );
		if ( '' === $campaign_key || empty( $variants ) ) { return array(); }
		$session_id = sanitize_text_field( (string) $session_id );
		$stored = self::get_session_assignments();
		if ( '' === $session_id && isset( $stored[ $campaign_key ]['session_id'] ) ) { $session_id = $stored[ $campaign_key ]['session_id']; }
		if ( '' === $session_id ) { $session_id = md5( uniqid( 'bewia_', true ) ); }
		if ( isset( $stored[ $campaign_key ]['variant_id'] ) ) {
			foreach ( $variants as $variant ) { if ( $variant['variant_id'] === $stored[ $campaign_key ]['variant_id'] ) { return $variant; } }
		}
		$index = hexdec( substr( hash( 'sha256', $campaign_key . '|' . $session_id ), 0, 8 ) ) % count( $variants );
		$assigned = $variants[ $index ];
		$stored[ $campaign_key ] = array( 'session_id' => $session_id, 'variant_id' => $assigned['variant_id'] );
		self::set_session_assignments( $stored );
		return $assigned;
	}

	public static function register_emitted_offer( $session_id, $offer ) {
		$session_id = sanitize_text_field( (string) $session_id );
		$offer = is_array( $offer ) ? $offer : array();
		if ( '' === $session_id || empty( $offer['product_id'] ) ) { return false; }
		$offers = self::get_session_offers();
		$key = self::offer_key( $offer );
		$offers[ $key ] = array(
			'product_id' => absint( $offer['product_id'] ),
			'campaign_key' => sanitize_key( isset( $offer['campaign_key'] ) ? $offer['campaign_key'] : '' ),
			'variant_id' => sanitize_key( isset( $offer['variant_id'] ) ? $offer['variant_id'] : '' ),
		);
		self::set_session_offers( $offers );
		return true;
	}

	public static function is_emitted_offer_valid( $session_id, $offer ) {
		$session_id = sanitize_text_field( (string) $session_id );
		$offer = is_array( $offer ) ? $offer : array();
		if ( '' === $session_id || empty( $offer['product_id'] ) ) { return false; }
		$offers = self::get_session_offers();
		$key = self::offer_key( $offer );
		return isset( $offers[ $key ] ) && absint( $offers[ $key ]['product_id'] ) === absint( $offer['product_id'] );
	}

	private static function offer_key( $offer ) {
		return sanitize_key( isset( $offer['campaign_key'] ) ? $offer['campaign_key'] : '' ) . '|' . sanitize_key( isset( $offer['variant_id'] ) ? $offer['variant_id'] : '' ) . '|' . absint( isset( $offer['product_id'] ) ? $offer['product_id'] : 0 );
	}
	private static function get_session_offers() {
		if ( function_exists( 'WC' ) && class_exists( 'WooCommerce' ) ) { $wc = WC(); if ( $wc && isset( $wc->session ) && method_exists( $wc->session, 'get' ) ) { $value = $wc->session->get( self::OFFER_KEY, array() ); return is_array( $value ) ? $value : array(); } }
		return array();
	}
	private static function set_session_offers( $value ) {
		if ( function_exists( 'WC' ) && class_exists( 'WooCommerce' ) ) { $wc = WC(); if ( $wc && isset( $wc->session ) && method_exists( $wc->session, 'set' ) ) { $wc->session->set( self::OFFER_KEY, $value ); } }
	}

	private static function get_session_assignments() {
		if ( function_exists( 'WC' ) && class_exists( 'WooCommerce' ) ) { $wc = WC(); if ( $wc && isset( $wc->session ) && is_object( $wc->session ) && method_exists( $wc->session, 'get' ) ) { $value = $wc->session->get( self::SESSION_KEY, array() ); return is_array( $value ) ? $value : array(); } }
		return array();
	}
	private static function set_session_assignments( $value ) {
		if ( function_exists( 'WC' ) && class_exists( 'WooCommerce' ) ) { $wc = WC(); if ( $wc && isset( $wc->session ) && is_object( $wc->session ) && method_exists( $wc->session, 'set' ) ) { $wc->session->set( self::SESSION_KEY, $value ); } }
	}
}
