<?php
namespace BriefcasewpExtras\Modules\WooCheckout\AIUpsells;
if ( ! defined( 'ABSPATH' ) ) { exit; }
class BEWIA_AI_Upsell_AI_Client {
	protected $settings;
	public function __construct( $settings = array() ) { $this->settings = is_array( $settings ) ? $settings : array(); }
	public function recommend( array $payload ) {
		$stored = function_exists( 'get_option' ) ? get_option( 'bewia_ai_upsell_settings', array() ) : array();
		if ( is_array( $stored ) ) { $this->settings = array_merge( $stored, $this->settings ); }
		if ( defined( 'BEWIA_AI_UPSELL_API_KEY' ) && empty( $this->settings['ai_api_key'] ) && empty( $this->settings['api_key'] ) ) { $this->settings['ai_api_key'] = BEWIA_AI_UPSELL_API_KEY; }
		$endpoint = isset( $this->settings['ai_endpoint'] ) ? $this->settings['ai_endpoint'] : ( isset( $this->settings['endpoint'] ) ? $this->settings['endpoint'] : '' );
		$key = isset( $this->settings['ai_api_key'] ) ? $this->settings['ai_api_key'] : ( isset( $this->settings['api_key'] ) ? $this->settings['api_key'] : '' );
		$model = isset( $this->settings['ai_model'] ) ? sanitize_text_field( $this->settings['ai_model'] ) : '';
		$endpoint = esc_url_raw( $endpoint );
		if ( ! $endpoint || ! $key ) { return new \WP_Error( 'bewia_ai_credentials_missing', 'AI credentials are not configured.' ); }
		if ( $model ) { $payload['model'] = $model; }
		$timeout = max( 1, min( 10, absint( isset( $this->settings['ai_timeout'] ) ? $this->settings['ai_timeout'] : ( isset( $this->settings['timeout'] ) ? $this->settings['timeout'] : 5 ) ) ) );
		$response = wp_remote_post( $endpoint, array( 'timeout' => $timeout, 'headers' => array( 'Content-Type' => 'application/json', 'Authorization' => 'Bearer ' . $key ), 'body' => wp_json_encode( $payload ) ) );
		if ( is_wp_error( $response ) ) { return $response; }
		$body = wp_remote_retrieve_body( $response );
		if ( strlen( $body ) > 1048576 ) { return new \WP_Error( 'bewia_ai_response_too_large', 'AI response exceeds the maximum size.' ); }
		$code = wp_remote_retrieve_response_code( $response );
		if ( $code < 200 || $code >= 300 ) { return new \WP_Error( 'bewia_ai_http_error', 'AI provider returned an unsuccessful response.' ); }
		$data = json_decode( $body, true );
		return is_array( $data ) ? $data : new \WP_Error( 'bewia_ai_invalid_json', 'AI provider returned invalid JSON.' );
	}
}
