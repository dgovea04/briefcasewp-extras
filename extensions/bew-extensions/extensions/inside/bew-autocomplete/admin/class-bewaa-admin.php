<?php

class Bewaa_Admin {

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}

	function add_tab($tabs) {

		$extra_tabs = array(
			'autocomplete' 		=> esc_html__( 'Autocomplete Address', 'bew-extras' ),
		);

		$tabs = array_merge($tabs, $extra_tabs);
	 
		return $tabs;
	}

	public function enqueue_styles() {

		$screen = get_current_screen();		
		if ( 'toplevel_page_bew-templates' === $screen->base ){			
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/bewaa-admin.css', array(), $this->version, 'all' );
			wp_enqueue_style( 'bewaa_chosen_css', plugin_dir_url( __FILE__ ) . 'css/chosen.min.css', array(), $this->version, 'all' );
		}

	}

	public function enqueue_scripts() {

		$screen = get_current_screen();
		if ( 'toplevel_page_bew-templates' === $screen->base ){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/bewaa-admin.js', array( 'jquery' ), $this->version, false );
			wp_enqueue_script( 'bewaa_chosen_js', plugin_dir_url( __FILE__ ) . 'js/chosen.jquery.min.js', array( 'jquery' ), $this->version, false );
		}

	}

	public function admin_pages() {
		// Addons submenu
		add_submenu_page( 
			'bew-templates', 
			'Autocomplete Address', 
			'Autocomplete Address', 
			'manage_options', 
			'bewaa-settings', 
			array( $this, 'settings' )
		);		

	}
	
	public function settings() {

		// Default variables.
		$settings_title = esc_html( __( 'Autocomplete Settings', 'bewaa' ) );

		// Get the current tab from the $_GET param.
		$current_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';

		 // Tabs array.
		 $tabs = array(
			 array(
				 'slug'  => '',
				 'label' => esc_html( __( 'Autocomplete settings', 'bewaa' ) ),
				 'title' => esc_html( __( 'Autocomplete settings', 'bewaa' ) ),
				 'url'   => '?page=bewaa-settings',
			 ),
		 );

		 foreach ( $tabs as $tab ) {
			 if ( $current_tab === $tab['slug'] ) {
				 $settings_title = $tab['title'];
				 break;
			 }
		 }

			?>
		<div id="box_woogrid_settings">
		<form action='options.php' method='post'>			
			<h2><?php esc_html_e( 'Autocomplete Address', 'bew-extras'); ?></h2>
			<?php

			if ( 1 < count( $tabs ) ) {
				?>
							<nav class="nav-tab-wrapper">
						<?php
						foreach ( $tabs as $tab ) {
							$url = ( '' !== $tab['slug'] ) ? 'admin.php?page=bewaa-settings&tab=' . esc_attr( $tab['slug'] ) : 'admin.php?page=bewaa-settings';
							echo '<a href="' . esc_html( admin_url( $url ) ) . '" class="nav-tab ' . ( $current_tab === $tab['slug'] ? 'nav-tab-active' : '' ) . '">' . esc_html( $tab['label'] ) . '</a>';
						}
						?>
							</nav>
						<?php
			}

			foreach ( $tabs as $tab ) {
				if ( '' === $current_tab ) {
					settings_fields( 'bewaa' );
					do_settings_sections( 'bewaa' );
					break;
				} elseif ( $current_tab === $tab['slug'] ) {
					settings_fields( $tab['slug'] );
					do_settings_sections( $tab['slug'] );
					break;
				}
			}

				submit_button();
			?>
		</form>
	</div>
		<?php
	}

	public function settings_init() {

		// Get settings tab.
		$tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : '';
		register_setting( 'bewaa', 'bewaa_google_api_key' );
		register_setting( 'bewaa', 'bewaa_shipping_autocomplete' );
		register_setting( 'bewaa', 'bewaa_billing_autocomplete' );
		register_setting( 'bewaa', 'bewaa_initial_map' );

		register_setting( 'bewaa', 'bewaa_map_position' );
		register_setting( 'bewaa', 'bewaa_center_map_latitude' );
		register_setting( 'bewaa', 'bewaa_center_map_longitude' );
		register_setting( 'bewaa', 'bewaa_restrictions' );
		register_setting( 'bewaa', 'bewaa_location_picker' );
		register_setting( 'bewaa', 'bewaa_location_picker_type' );
		register_setting( 'bewaa', 'bewaa_customer_location' );
		register_setting( 'bewaa', 'bewaa_coordinates' );
		register_setting( 'bewaa', 'bewaa_map_zoom' );
		register_setting( 'bewaa', 'bewaa_customer_location_auto_select' );

		if ( '' === $tab ) {

			// General Settings.
			add_settings_section(
				'bewaa_setting_section',
				'',
				'',
				'bewaa'
			);

			add_settings_field(
				'bewaa_google_api_key',
				__( 'Google API key', 'bewaa' ),
				array( $this, 'google_api_key' ),
				'bewaa',
				'bewaa_setting_section'
			);

			add_settings_field(
				'bewaa_billing_autocomplete',
				__( 'Billing address', 'bewaa' ),
				array( $this, 'bewaa_billing_autocomplete' ),
				'bewaa',
				'bewaa_setting_section'
			);
			add_settings_field(
				'bewaa_shipping_autocomplete',
				__( 'Shipping address', 'bewaa' ),
				array( $this, 'bewaa_shipping_autocomplete' ),
				'bewaa',
				'bewaa_setting_section'
			);
			add_settings_field(
				'bewaa_restrictions',
				__( 'Country restrictions', 'bewaa' ),
				array( $this, 'bewaa_restrictions' ),
				'bewaa',
				'bewaa_setting_section'
			);
			add_settings_field(
				'bewaa_initial_map',
				__( 'Map', 'bewaa' ),
				array( $this, 'bewaa_initial_map' ),
				'bewaa',
				'bewaa_setting_section'
			);

			add_settings_field(
				'bewaa_map_zoom',
				__( 'Map zoom', 'bewaa' ),
				array( $this, 'bewaa_map_zoom' ),
				'bewaa',
				'bewaa_setting_section'
			);

			add_settings_field(
				'bewaa_center_map',
				__( 'Center map', 'bewaa' ),
				array( $this, 'bewaa_center_map' ),
				'bewaa',
				'bewaa_setting_section'
			);

			add_settings_field(
				'bewaa_map_position',
				__( 'Map position on the page', 'bewaa' ),
				array( $this, 'bewaa_map_position' ),
				'bewaa',
				'bewaa_setting_section'
			);


			add_settings_field(
				'bewaa_location_picker',
				__( 'Location Picker', 'bewaa' ),
				array( $this, 'bewaa_location_picker' ),
				'bewaa',
				'bewaa_setting_section'
			);

			add_settings_field(
				'bewaa_location_picker_type',
				__( 'Location Picker update options', 'bewaa' ),
				array( $this, 'bewaa_location_picker_type' ),
				'bewaa',
				'bewaa_setting_section'
			);
			

			add_settings_field(
				'bewaa_coordinates',
				__( 'Coordinates', 'bewaa' ),
				array( $this, 'bewaa_coordinates' ),
				'bewaa',
				'bewaa_setting_section'
			);

			add_settings_field(
				'bewaa_customer_location',
				__( 'Customer location', 'bewaa' ),
				array( $this, 'bewaa_customer_location' ),
				'bewaa',
				'bewaa_setting_section'
			);

		}

		do_action( 'bewaa_settings' );
	}

	public function google_api_key() {
		?>
		<p>
			<input type='text' class='regular-text' name='bewaa_google_api_key' value='<?php echo esc_attr( get_option( 'bewaa_google_api_key', '' ) ); ?>'><br>
			<span class="description" id="bewaa-gooogle-api-key-description">
				<?php echo esc_html( __( 'Google Key for Places API, Maps JavaScript API, Maps Embed API, Geocoding API. ( Application restrictions: HTTP referrers )', 'bewaa' ) ); ?>
				<br><?php echo sprintf( esc_html( __( 'For more information on how to create the Google API key %1$sclick here%2$s.', 'bewaa' ) ),  '<a href="https://powerfulwp.com/docs/autocomplete-address-and-location-picker-for-woocommerce-premium/getting-started/general-settings/" target="_blank">', '</a>' ); ?>
			</span>
		</p>
		<?php
	}

	public function bewaa_billing_autocomplete() {
		?>
		<p>
			<input type='checkbox' class='regular-checkbox' name='bewaa_billing_autocomplete'  <?php echo checked( '1', get_option( 'bewaa_billing_autocomplete', '' ) ); ?> value='1' >
			 <?php echo esc_html( __( 'Enable autocomplete address for the billing address.', 'bewaa' ) ); ?>
		</p>
		<?php
	}

	public function bewaa_shipping_autocomplete() {
		?>
		<p>
			<input type='checkbox' class='regular-checkbox' name='bewaa_shipping_autocomplete' <?php echo checked( '1', get_option( 'bewaa_shipping_autocomplete', '' ) ); ?> value='1'>
			<?php echo esc_html( __( 'Enable autocomplete address for the shipping address.', 'bewaa' ) ); ?>
		</p>
		<?php
	}

	public function bewaa_location_picker() {
		$element = '';

		$element = '<input type="checkbox" class="regular-checkbox" name="bewaa_location_picker" ' . checked( '1', get_option( 'bewaa_location_picker', '' ), false ) . ' value="1">';

		?>
		<p>
			 <?php echo $element; ?>
			 <?php echo esc_html( __( 'Enable location picker on the map. ( map to address )', 'bewaa' ) ); ?>
		</p>
		<?php
	}

    public function bewaa_location_picker_type() {

		$bewaa_location_picker_type = get_option( 'bewaa_location_picker_type', '' );
		?>
			<select name="bewaa_location_picker_type" >
				<?php
				echo '<option value="1" ' . esc_attr( wc_selected( '1', $bewaa_location_picker_type ) ) . '>' . esc_html( __( 'Update address and location', 'bewaa' ) ) . '</option>';
				echo '<option value="2" ' . esc_attr( wc_selected( '2', $bewaa_location_picker_type ) ) . '>' . esc_html( __( 'Update only the location', 'bewaa' ) ) . '</option>';
				echo '<option value="3" ' . esc_attr( wc_selected( '3', $bewaa_location_picker_type ) ) . '>' . esc_html( __( 'Update address or/and location ( customer choice )', 'bewaa' ) ) . '</option>';
				?>
			</select><br>
		<?php

		?>
		<p>			
			<?php echo esc_html( __( 'Select which updates the location picker does. ( address or/and location coordinates ).', 'bewaa' ) ); ?>
		</p>
		<?php
    }

	public function bewaa_customer_location() {
		$element = '';

		$element = '<input type="checkbox" class="regular-checkbox" name="bewaa_customer_location" ' . checked( '1', get_option( 'bewaa_customer_location', '' ), false ) . ' value="1">';

		?>
		<p>			
			<?php echo $element; ?>
			<?php echo esc_html( __( 'Enable use of customer current location.', 'bewaa' ) ); ?>
		</p>
		<?php

		   echo '<p>
					<input type="checkbox" class="regular-checkbox" name="bewaa_customer_location_auto_select" ' . checked( '1', get_option( 'bewaa_customer_location_auto_select', '' ), false ) . ' value="1">
					' . esc_html( __( 'Enable auto-select the customer\'s current location.', 'bewaa' ) ) . '
				</p>';

		?>
		<?php
	}

	public function bewaa_coordinates() {
		$element = '';

		$element = '<input type="checkbox" class="regular-checkbox" name="bewaa_coordinates" ' . checked( '1', get_option( 'bewaa_coordinates', '' ), false ) . ' value="1">';

		?>
		<p>			 
			 <?php echo $element; ?>
			 <?php echo esc_html( __( 'Enable adding latitude and longitude to order with a link to show on the map.', 'bewaa' ) ); ?>
		</p>
		<?php
	}

    public function bewaa_map_zoom() {
		?>
			<p>
				<?php

					$bewaa_map_zoom = get_option( 'bewaa_map_zoom', '' );
					$bewaa_map_zoom = ( '' !== $bewaa_map_zoom ) ? $bewaa_map_zoom : 11 ;
					?>
						<select name="bewaa_map_zoom" >
							<?php
								for ($i = 0; $i <= 20; $i++) {
									echo '<option value="'.esc_attr( $i ).'" ' . esc_attr( wc_selected( $i , $bewaa_map_zoom ) ) . '>' . esc_attr( $i ) . '</option>';
								}
							?>
						</select><br>
					<?php

				?>
				
			</p>
		<?php
	}

	public function bewaa_map_position() {
		?>
			<p>
				<?php

					$bewaa_map_position = get_option( 'bewaa_map_position', '' );
					?>
						<select name="bewaa_map_position" >
							<?php
							echo '<option value="1" ' . esc_attr( wc_selected( '1', $bewaa_map_position ) ) . '>' . esc_html( __( 'Show map after address fields', 'bewaa' ) ) . '</option>';
							echo '<option value="2" ' . esc_attr( wc_selected( '2', $bewaa_map_position ) ) . '>' . esc_html( __( 'Show map before address fields', 'bewaa' ) ) . '</option>';
							?>
						</select><br>
					<?php

				?>
				<span><?php echo esc_html( __( 'Choose where to show the map on the checkout page.', 'bewaa' ) ); ?></span>
			</p>
		<?php
	}

	public function bewaa_initial_map() {
		?>
			<label for="bewaa_initial_map">
				<input type="checkbox" class="regular-checkbox" name="bewaa_initial_map" <?php echo checked( '1', get_option( 'bewaa_initial_map', '' ), false ) ?> value="1">
				<?php echo esc_html( __( 'Enable map.', 'bewaa' ) ); ?>
			</label>
		<?php
	}

	public function bewaa_center_map() {
		?>
			<p>
				<?php
					
					$bewaa_center_map_latitude = empty($bewaa_center_map_latitude) ? '40.730610' : get_option( 'bewaa_center_map_latitude', '' ) ;
					$bewaa_center_map_longitude = empty($bewaa_center_map_longitude) ? '-73.935242' : get_option( 'bewaa_center_map_longitude', '' ) ;						

					?>
						<?php echo esc_html( __( 'Latitude', 'bewaa' ) ); ?> <input type='text' class='medium-text' name='bewaa_center_map_latitude' value='<?php echo esc_attr( $bewaa_center_map_latitude ); ?>'>
						<?php echo esc_html( __( 'Longitude', 'bewaa' ) ); ?> <input type='text' class='medium-text' name='bewaa_center_map_longitude' value='<?php echo esc_attr( $bewaa_center_map_longitude ); ?>'>
					<br>
					<?php

				?>
				<span><?php echo sprintf( __( 'Enter latitude & longitude to center the map. You can find the coordinates of a place on <a target="_blank" href="%s">Google Maps</a>.', 'bewaa' ), esc_url( 'https://maps.google.com/' ) ); ?></span>
			</p>
		<?php
	}

	public function bewaa_restrictions() {
		?>
		<p>
		<?php

		$bewaa_restrictions = get_option( 'bewaa_restrictions', '' );
		$countries = WC()->countries->get_allowed_countries();
		?>
		<select multiple="multiple" name="bewaa_restrictions[]" data-placeholder="<?php esc_attr_e( 'Select country restrictions', 'woocommerce' ); ?>" class="chosen_select">
			<?php
				foreach ( $countries as $country_key  => $country ) {
					echo '<option value="' . esc_attr( $country_key ) . '"' . esc_attr( wc_selected( $country_key, $bewaa_restrictions ) ) . '>' . esc_html( esc_attr( $country ) ) . '</option>';
				}
			?>
		</select>
		<br>

 			<span><?php echo esc_html( __( 'Choose countries to limit the autocomplete search results, you can choose up to 5 countries.', 'bewaa' ) ); ?></span>
		</p>
		<?php
	}

	public function admin_order_data_after_billing_address( $order ) {
		$bewaa_billing_coordinates = get_post_meta( $order->get_id(), 'bewaa_billing_coordinates', true );
		$longitude                = '';
		$latitude                 = '';
		echo '<br class="clear" /> <div class="address">';
		if ( ! empty( $bewaa_billing_coordinates ) ) {
			if ( ! empty( $bewaa_billing_coordinates['latitude'] ) && ! empty( $bewaa_billing_coordinates['longitude'] ) ) {
				$latitude  = esc_attr( $bewaa_billing_coordinates['latitude'] );
				$longitude = esc_attr( $bewaa_billing_coordinates['longitude'] );
				$link      = 'https://www.google.com/maps/search/?api=1&query=' . $latitude . ',' . $longitude;
				echo ' <b>' . esc_html( __( 'Latitude:', 'bewaa' ) ) . '</b> <a href="' . esc_attr( $link ) . '" target="_blank">' . $latitude . '</a><br>';
				echo ' <b>' . esc_html( __( 'Longitude:', 'bewaa' ) ) . '</b> <a href="' . esc_attr( $link ) . '" target="_blank">' . $longitude . '</a>';
			}
		}
		echo '</div><div class="edit_address">';
		woocommerce_wp_text_input(
			array(
				'id'            => 'bewaa_billing_coordinates_latitude',
				'label'         => __( 'latitude', 'bewaa' ),
				'value'         => $latitude,
				'wrapper_class' => 'form-field-wide',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'            => 'bewaa_billing_coordinates_longitude',
				'label'         => __( 'longitude', 'bewaa' ),
				'value'         => $longitude,
				'wrapper_class' => 'form-field-wide',
			)
		);

		echo '</div>';
	}

	public function admin_order_data_after_shipping_address( $order ) {
		$bewaa_shipping_coordinates = get_post_meta( $order->get_id(), 'bewaa_shipping_coordinates', true );
		$longitude                 = '';
		$latitude                  = '';
		echo '<br class="clear" /> <div class="address">';
		if ( ! empty( $bewaa_shipping_coordinates ) ) {
			if ( ! empty( $bewaa_shipping_coordinates['latitude'] ) && ! empty( $bewaa_shipping_coordinates['longitude'] ) ) {
				$latitude  = esc_attr( $bewaa_shipping_coordinates['latitude'] );
				$longitude = esc_attr( $bewaa_shipping_coordinates['longitude'] );

				$link = 'https://www.google.com/maps/search/?api=1&query=' . $latitude . ',' . $longitude;
				echo ' <b>' . esc_html( __( 'Latitude:', 'bewaa' ) ) . '</b> <a href="' . esc_attr( $link ) . '" target="_blank">' . $latitude . '</a><br>';
				echo ' <b>' . esc_html( __( 'Longitude:', 'bewaa' ) ) . '</b> <a href="' . esc_attr( $link ) . '" target="_blank">' . $longitude . '</a>';
			}
		}
		echo '</div><div class="edit_address">';

		woocommerce_wp_text_input(
			array(
				'id'            => 'bewaa_shipping_coordinates_latitude',
				'label'         => __( 'latitude', 'bewaa' ),
				'value'         => $latitude,
				'wrapper_class' => 'form-field-wide',
			)
		);

		woocommerce_wp_text_input(
			array(
				'id'            => 'bewaa_shipping_coordinates_longitude',
				'label'         => __( 'longitude', 'bewaa' ),
				'value'         => $longitude,
				'wrapper_class' => 'form-field-wide',
			)
		);

		echo '</div>';

	}

	public function process_shop_order_meta( $order_id ) {

			// Shipping coordinates.
			$shipping_lat         = isset( $_POST['bewaa_shipping_coordinates_latitude'] ) ? sanitize_text_field( wp_unslash( $_POST['bewaa_shipping_coordinates_latitude'] ) ) : '';
			$shipping_lng         = isset( $_POST['bewaa_shipping_coordinates_longitude'] ) ? sanitize_text_field( wp_unslash( $_POST['bewaa_shipping_coordinates_longitude'] ) ) : '';
			$shipping_coordinates = '';

		if ( '' !== $shipping_lat && '' !== $shipping_lng ) {
			$shipping_coordinates = array(
				'latitude'  => $shipping_lat,
				'longitude' => $shipping_lng,
			);
		}
			update_post_meta( $order_id, 'bewaa_shipping_coordinates', $shipping_coordinates );

			// Billing coordinates.
			$billing_lat         = isset( $_POST['bewaa_billing_coordinates_latitude'] ) ? sanitize_text_field( wp_unslash( $_POST['bewaa_billing_coordinates_latitude'] ) ) : '';
			$billing_lng         = isset( $_POST['bewaa_billing_coordinates_longitude'] ) ? sanitize_text_field( wp_unslash( $_POST['bewaa_billing_coordinates_longitude'] ) ) : '';
			$billing_coordinates = '';

		if ( '' !== $billing_lat && '' !== $billing_lng ) {
			$billing_coordinates = array(
				'latitude'  => $billing_lat,
				'longitude' => $billing_lng,
			);
		}
			update_post_meta( $order_id, 'bewaa_billing_coordinates', $billing_coordinates );

	}

	public function order_shipping_address_coordinates( $order, $coordinates ) {
		$shipping_address_1 = $order->get_shipping_address_1();
		if ( '' !== $shipping_address_1 ) {
			$bewaa_shipping_coordinates = get_post_meta( $order->get_id(), 'bewaa_shipping_coordinates', true );
		} else {
			$bewaa_shipping_coordinates = get_post_meta( $order->get_id(), 'bewaa_billing_coordinates', true );
		}

		if ( ! empty( $bewaa_shipping_coordinates ) ) {
			if ( ! empty( $bewaa_shipping_coordinates['latitude'] ) && ! empty( $bewaa_shipping_coordinates['longitude'] ) ) {
				 $coordinates = $bewaa_shipping_coordinates['latitude'] . ',' . $bewaa_shipping_coordinates['longitude'];
			}
		}
		return $coordinates;
	}

}
