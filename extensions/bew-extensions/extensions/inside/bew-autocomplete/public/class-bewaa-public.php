<?php

class Bewaa_Public {

	private $plugin_name;
	private $version;
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

	}

	public function enqueue_styles() {

		if ( is_checkout() ) {
			if ( true === BEWAA_AUTOCOMPLETE ) {
				wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bewaa-public.css', array(), $this->version, 'all' );
			}
		}

	}

	public function enqueue_scripts() {

		// Check if is checkout page.
		if ( is_checkout() ) {
			if ( true === BEWAA_AUTOCOMPLETE ) {

					$bewaa_google_api_key 		 = get_option( 'bewaa_google_api_key', '' );
					$bewaa_billing_autocomplete  = get_option( 'bewaa_billing_autocomplete', '' );
					$bewaa_shipping_autocomplete = get_option( 'bewaa_shipping_autocomplete', '' );
					$bewaa_map 					 = get_option( 'bewaa_initial_map', '' );

					$bewaa_restrictions 		 = '';
					$bewaa_location_picker		 = '';
					$bewaa_coordinates			 = '';
					$bewaa_customer_location 	 = '';
					$bewaa_center_map_latitude   = '';
					$bewaa_center_map_longitude  = '';
					$bewaa_location_picker_type  = '';
					$bewaa_customer_location_auto_select = '';
					$bewaa_map_zoom = '';

					$bewaa_restrictions 		 = get_option( 'bewaa_restrictions', '' );
					$bewaa_location_picker		 = get_option( 'bewaa_location_picker', '' );
					$bewaa_coordinates			 = get_option( 'bewaa_coordinates', '' );
					$bewaa_customer_location 	 = get_option( 'bewaa_customer_location', '' );
					$bewaa_center_map_latitude   = get_option( 'bewaa_center_map_latitude', '' );
					$bewaa_center_map_longitude  = get_option( 'bewaa_center_map_longitude', '' );
					$bewaa_location_picker_type  = get_option( 'bewaa_location_picker_type', '' );
					$bewaa_map_zoom				 = get_option( 'bewaa_map_zoom', '' );
					$bewaa_customer_location_auto_select = get_option( 'bewaa_customer_location_auto_select', '' );

					// Set map zoom.
					$bewaa_map_zoom = '' !== $bewaa_map_zoom ? $bewaa_map_zoom : 11;

					// Set center map coordinates.
					if ( ! is_numeric( $bewaa_center_map_latitude ) || ! is_numeric( $bewaa_center_map_longitude ) ){
						$bewaa_center_map_latitude   = '40.730610';
						$bewaa_center_map_longitude  = '-73.935242';
					}

					if ( '' !== $bewaa_google_api_key ) {
						wp_enqueue_script( 'bewaa-googleapis', 'https://maps.googleapis.com/maps/api/js?key=' . $bewaa_google_api_key . '&libraries=places&v=weekly', array( 'jquery' ), $this->version, false );
					}

					wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bewaa-public.js', array( 'jquery' ), $this->version, false );
					wp_localize_script( $this->plugin_name, 'bewaa_autocomplete',
					array( 'bewaa_billing' => esc_js( $bewaa_billing_autocomplete ),
					'bewaa_shipping' => esc_js( $bewaa_shipping_autocomplete ),
					'bewaa_map' => esc_js( $bewaa_map ),
					'bewaa_restrictions' => $bewaa_restrictions,
					'bewaa_location_picker' => esc_js( $bewaa_location_picker ),
					'bewaa_coordinates' => esc_js( $bewaa_coordinates ),
					'bewaa_customer_location' => esc_js( $bewaa_customer_location ),
					'bewaa_select_address_text' => esc_js( __( 'Select address', 'bewaa' ) ),
					'bewaa_address_selected_text' => esc_js( __( 'Address selected', 'bewaa' ) ),
					'bewaa_center_map_latitude' => esc_js( $bewaa_center_map_latitude ),
					'bewaa_center_map_longitude' => esc_js( $bewaa_center_map_longitude ),
					'bewaa_location_picker_type' => esc_js( $bewaa_location_picker_type ),
					'bewaa_select_location_text' => esc_js( __( 'Select location', 'bewaa' ) ),
					'bewaa_location_selected_text' => esc_js( __( 'Location selected', 'bewaa' ) ),
					'bewaa_map_zoom' => esc_js( $bewaa_map_zoom ),
					'bewaa_customer_location_auto_select' => esc_js( $bewaa_customer_location_auto_select ),
					) );
				}
		}
	}

	public function billing_map( $checkout ) {
		$bewaa_map = get_option( 'bewaa_initial_map', '' );
		if ( '1' === $bewaa_map ){
			echo '<div id="bewaa_billing_map"></div>';
		}

		$bewaa_coordinates = get_option( 'bewaa_coordinates', '' );
		if ( '1' === $bewaa_coordinates ){
			echo '<div class="bewaa_coordinates">';
			if ( '1' === $bewaa_map ){
				echo esc_html( __( "Location:" , 'bewaa' ) ) . '<br> <span class="bewaa_text_left">' . esc_html( __( "Latitude:" , 'bewaa' ) ) . '<em id="bewaa_billing_lat"></em></span><span>' . esc_html( __( "Longitude:" , 'bewaa' ) ) . '<em id="bewaa_billing_lng"></em></span>';
			}

			echo '<input type="hidden" id="bewaa_billing_lng_input" name="bewaa_billing_lng_input" value="">
				<input type="hidden" id="bewaa_billing_lat_input" name="bewaa_billing_lat_input" value="">
			</div>' ;
		}


	}

	public function shipping_map( $checkout ) {
		$bewaa_map = get_option( 'bewaa_initial_map', '' );
		if ( '1' === $bewaa_map ){
			echo '<div id="bewaa_shipping_map"></div>';
		}

		$bewaa_coordinates = get_option( 'bewaa_coordinates', '' );
		if ( '1' === $bewaa_coordinates ){
			echo '<div class="bewaa_coordinates">';
			if ( '1' === $bewaa_map ){
				echo esc_html( __( "Location:" , 'bewaa' ) ) . '<br> <span class="bewaa_text_left">' . esc_html( __( "Latitude:" , 'bewaa' ) ) . '<em id="bewaa_shipping_lat"></em></span><span>' . esc_html( __( "Longitude:" , 'bewaa' ) ) . '<em id="bewaa_shipping_lng"></em></span>';
			}

			echo '<input type="hidden" id="bewaa_shipping_lng_input" name="bewaa_shipping_lng_input" value="">
					<input type="hidden" id="bewaa_shipping_lat_input" name="bewaa_shipping_lat_input" value="">
			</div>' ;
		}

	}

	public function update_checkout_fields( $order_id ) {
		$bewaa_coordinates = get_option( 'bewaa_coordinates', '' );
		if ( '1' === $bewaa_coordinates && isset( $_POST['bewaa_billing_lng_input'] ) ) {

			$shipping_lat = isset( $_POST['bewaa_shipping_lat_input'] ) ? sanitize_text_field( wp_unslash( $_POST['bewaa_shipping_lat_input'] ) ) : '';
			$shipping_lng = isset( $_POST['bewaa_shipping_lng_input'] ) ? sanitize_text_field( wp_unslash( $_POST['bewaa_shipping_lng_input'] ) ) : '';
			$billing_lat  = isset( $_POST['bewaa_billing_lat_input'] )  ? sanitize_text_field( wp_unslash( $_POST['bewaa_billing_lat_input'] ) )  : '';
			$billing_lng  = isset( $_POST['bewaa_billing_lng_input'] )  ? sanitize_text_field( wp_unslash( $_POST['bewaa_billing_lng_input'] ) )  : '';

			if ( empty( $_POST['ship_to_different_address'] ) || wc_ship_to_billing_address_only() ){
				$shipping_lat = $billing_lat;
				$shipping_lng = $billing_lng;
			}

			if ( '' !== $shipping_lat && '' !== $shipping_lng ) {
				update_post_meta( $order_id, 'bewaa_shipping_coordinates', array(
					'latitude'	=> $shipping_lat,
					'longitude'	=> $shipping_lng,
				));
			}

			if ( '' !== $billing_lat && '' !== $billing_lng ) {
				update_post_meta( $order_id, 'bewaa_billing_coordinates', array(
					'latitude'	=> $billing_lat,
					'longitude'	=> $billing_lng,
				));
			}

		}
	}

}
