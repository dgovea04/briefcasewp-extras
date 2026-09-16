<?php
namespace BriefcasewpExtras;

use Elementor;
use Elementor\Plugin;
use WC_Checkout;
use WP_Error;
use WC_Validation;
use WC_AJAX;

function is_bew_woo_checkout( $post_id = null ) {
	
		static $foo_called = false;
		if ($foo_called) return;

		$foo_called = true;

		// If no post_id specified try getting the post_id
		if ( empty( $post_id ) ) {	
				global $post;
				
				if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])){					
						// Try to get the post ID from the URL in case this function is called before init
						$schema = is_ssl() ? 'https://' : 'http://';
						//$url = explode('?', $_SERVER["HTTP_REFERER"] . $_SERVER["REQUEST_URI"] );
						$url = $_SERVER["HTTP_REFERER"];
						$post_id = url_to_postid( $url );					
						//echo "holaA" . $post_id;
						if($post_id != 0){
							update_option( '_bew_checkout_woo_id', $post_id );
						}					
					
				} else{
					
					$sveruri = explode('/', $_SERVER["REQUEST_URI"] );
					
					if (is_multisite()) {
						$sveruri = $sveruri[2];						
					} else {
						$sveruri = $sveruri[1];						
					}
										
					$current_page = get_page_by_path($sveruri);

					if(isset($current_page->ID)) {
						$post_id = $current_page->ID;
					}
					//echo "holaA" . $post_id;
										
				}

				
		}
		//echo "holaA" . $post_id;					
		// If still no post_id return straight away
		if ( empty( $post_id )) {
			
			$is_bwco = false;
					

		} else {
			//echo "holaA" . BewWco::$shortcode_page_id;
			if ( 0 == BewWco::$shortcode_page_id ) {
				$post_to_check = ! empty( $post ) ? $post : get_post( $post_id );
				
				if ( false !== stripos( $post_to_check->post_content, 'elementor-template' ) ) {
					preg_match('~="(.*?)"]~',  $post_to_check->post_content, $output);
					//echo $output[1]; 
					$post_id = $output[1];
					$post_to_check = ! empty( $post ) ? $post : get_post( $post_id );
				} 				
		
				//echo "holaA" . var_dump($post_to_check);
				BewWco::check_for_bew_woo_checkout( $post_to_check );
				
			}
			//echo "holaA" . $post_id;
			// Compare IDs
			if ( $post_id == BewWco::$shortcode_page_id || ( 'yes' == get_post_meta( $post_id, '_bewwco', true ) ) ) {
				$is_bwco = true;					
			} else {
				$is_bwco = false;				
			}
			
		}
		//echo "is_bwco " . var_dump($is_bwco);										
		return apply_filters( 'is_bew_woo_checkout', $is_bwco );
}

class BewWco{
	private static $_instance = null;
	
	static $shortcode_page_id = 0;

	static $add_scripts = false;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {
				
		// Checks if a queried page contains the woo checkout widgets shortcode, needs to happen after the "template_redirect"
		add_action( 'the_posts', array( $this,  'bew_ensure_bwco_shortcode_page_id_is_set' ), 10, 2 );
		
		$this->add_actions();
		$this->add_ajax_events();		
	}
	
	public static function bew_ensure_bwco_shortcode_page_id_is_set( $posts, $query ) {

		// Return straight away if there are no posts or if its a secondary query
		if ( empty( $posts ) || ! $query->is_main_query() ) {
			return $posts;
		}

		if ( 0 == self::$shortcode_page_id ) {
			foreach ( $posts as $post ) {
				if ( ( false !== stripos( $post->post_content, 'bew-woo-checkout' ) ) || ( 'yes' == get_post_meta( $post->ID, '_bewwco', true ) ) ) {
					self::$add_scripts = true;
					self::$shortcode_page_id = $post->ID;
					break;
				}
			}
		}
		
		return $posts;
	}
		
	public static function check_for_bew_woo_checkout( $post_to_check ) {
			
		if ( false !== stripos( $post_to_check->post_content, 'bew-woo-checkout' ) ) {
			self::$add_scripts = true;
			self::$shortcode_page_id = $post_to_check->ID;
			
			$contains_shortcode = true;			
		} else {
			$contains_shortcode = false;			
		}		
				
		return $contains_shortcode;
	}
	
	function bew_order_fragments_split_shipping($order_fragments) {

		ob_start();
		$this->bew_woocommerce_order_review_shipping_split();
		$bew_woocommerce_order_review_shipping_split = ob_get_clean();

		$order_fragments['.bew-checkout-review-shipping-table'] = $bew_woocommerce_order_review_shipping_split;

		return $order_fragments;

	}
		
	function bew_order_fragments_review_order($order_fragments) {

		ob_start();
		$this->bew_woocommerce_order_review_order();
		$bew_woocommerce_order_review_order = ob_get_clean();

		$order_fragments['.bew-woocommerce-checkout-review-order-table'] = $bew_woocommerce_order_review_order;

		return $order_fragments;

	}

	function bew_order_fragments_order_bump($order_fragments) {

		ob_start();
		$this->bew_woocommerce_order_bump();
		$bew_woocommerce_order_bump = ob_get_clean();

		$order_fragments['.bew-checkout-ob-container'] = $bew_woocommerce_order_bump;

		return $order_fragments;

	}

	// We'll get the template that just has the shipping options that we need for the new table
	function bew_woocommerce_order_review_shipping_split( $deprecated = false ) {
		
		if( file_exists( BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-shipping-order-review.php' ) ){
			include BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-shipping-order-review.php';
		}
		//wc_get_template( 'checkout/shipping-order-review.php', array( 'checkout' => WC()->checkout() ) );
	}
	
	// We'll get the template bew review order
	function bew_woocommerce_order_review_order( $deprecated = false ) {
		
		if( file_exists( BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-review-order.php' ) ){
			include BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-review-order.php';
		}
	}

	// We'll get the template bew order bump
	function bew_woocommerce_order_bump( $deprecated = false ) {
		
		if( file_exists( BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-order-bump.php' ) ){
			include BEW_EXTRAS_PATH . 'includes/bew-woo-templates/bew-order-bump.php';
		}
	}
	
	public static function bew_redirect_wc( $atts ) {
		global $wp;

		// Check cart class is loaded or abort.
		if ( is_null( WC()->cart ) ) {
			return;
		}

		// Backwards compatibility with old pay and thanks link arguments.
		if ( isset( $_GET['order'] ) && isset( $_GET['key'] ) ) { // WPCS: input var ok, CSRF ok.
			wc_deprecated_argument( __CLASS__ . '->' . __FUNCTION__, '2.1', '"order" is no longer used to pass an order ID. Use the order-pay or order-received endpoint instead.' );

			// Get the order to work out what we are showing.
			$order_id = absint( $_GET['order'] ); // WPCS: input var ok.
			$order    = wc_get_order( $order_id );

			if ( $order && $order->has_status( 'pending' ) ) {
				$wp->query_vars['order-pay'] = absint( $_GET['order'] ); // WPCS: input var ok.
			} else {
				$wp->query_vars['order-received'] = absint( $_GET['order'] ); // WPCS: input var ok.
			}
		}

		// Handle checkout actions.
		if ( ! empty( $wp->query_vars['order-pay'] ) ) {
			
			self::order_pay( $wp->query_vars['order-pay'] );
			
		} elseif ( isset( $wp->query_vars['order-received'] ) ) {
			
			self::order_received( $wp->query_vars['order-received'] );
			
		}
	}

	private static function order_pay( $order_id ) {

		do_action( 'before_woocommerce_pay' );

		$order_id = absint( $order_id );

		// Pay for existing order.
		if ( isset( $_GET['pay_for_order'], $_GET['key'] ) && $order_id ) { // WPCS: input var ok, CSRF ok.
			try {
				$order_key = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : ''; // WPCS: input var ok, CSRF ok.
				$order     = wc_get_order( $order_id );

				// Order or payment link is invalid.
				if ( ! $order || $order->get_id() !== $order_id || ! hash_equals( $order->get_order_key(), $order_key ) ) {
					throw new Exception( __( 'Sorry, this order is invalid and cannot be paid for.', 'woocommerce' ) );
				}

				// Logged out customer does not have permission to pay for this order.
				if ( ! current_user_can( 'pay_for_order', $order_id ) && ! is_user_logged_in() ) {
					echo '<div class="woocommerce-info">' . esc_html__( 'Please log in to your account below to continue to the payment form.', 'woocommerce' ) . '</div>';
					woocommerce_login_form(
						array(
							'redirect' => $order->get_checkout_payment_url(),
						)
					);
					return;
				}

				// Add notice if logged in customer is trying to pay for guest order.
				if ( ! $order->get_user_id() && is_user_logged_in() ) {
					// If order has does not have same billing email then current logged in user then show warning.
					if ( $order->get_billing_email() !== wp_get_current_user()->user_email ) {
						wc_print_notice( __( 'You are paying for a guest order. Please continue with payment only if you recognize this order.', 'woocommerce' ), 'error' );
					}
				}

				// Logged in customer trying to pay for someone else's order.
				if ( ! current_user_can( 'pay_for_order', $order_id ) ) {
					throw new Exception( __( 'This order cannot be paid for. Please contact us if you need assistance.', 'woocommerce' ) );
				}

				// Does not need payment.
				if ( ! $order->needs_payment() ) {
					/* translators: %s: order status */
					throw new Exception( sprintf( __( 'This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.', 'woocommerce' ), wc_get_order_status_name( $order->get_status() ) ) );
				}

				// Ensure order items are still stocked if paying for a failed order. Pending orders do not need this check because stock is held.
				if ( ! $order->has_status( wc_get_is_pending_statuses() ) ) {
					$quantities = array();

					foreach ( $order->get_items() as $item_key => $item ) {
						if ( $item && is_callable( array( $item, 'get_product' ) ) ) {
							$product = $item->get_product();

							if ( ! $product ) {
								continue;
							}

							$quantities[ $product->get_stock_managed_by_id() ] = isset( $quantities[ $product->get_stock_managed_by_id() ] ) ? $quantities[ $product->get_stock_managed_by_id() ] + $item->get_quantity() : $item->get_quantity();
						}
					}

					foreach ( $order->get_items() as $item_key => $item ) {
						if ( $item && is_callable( array( $item, 'get_product' ) ) ) {
							$product = $item->get_product();

							if ( ! $product ) {
								continue;
							}

							if ( ! apply_filters( 'woocommerce_pay_order_product_in_stock', $product->is_in_stock(), $product, $order ) ) {
								/* translators: %s: product name */
								throw new Exception( sprintf( __( 'Sorry, "%s" is no longer in stock so this order cannot be paid for. We apologize for any inconvenience caused.', 'woocommerce' ), $product->get_name() ) );
							}

							// We only need to check products managing stock, with a limited stock qty.
							if ( ! $product->managing_stock() || $product->backorders_allowed() ) {
								continue;
							}

							// Check stock based on all items in the cart and consider any held stock within pending orders.
							$held_stock     = wc_get_held_stock_quantity( $product, $order->get_id() );
							$required_stock = $quantities[ $product->get_stock_managed_by_id() ];

							if ( ! apply_filters( 'woocommerce_pay_order_product_has_enough_stock', ( $product->get_stock_quantity() >= ( $held_stock + $required_stock ) ), $product, $order ) ) {
								/* translators: 1: product name 2: quantity in stock */
								throw new Exception( sprintf( __( 'Sorry, we do not have enough "%1$s" in stock to fulfill your order (%2$s available). We apologize for any inconvenience caused.', 'woocommerce' ), $product->get_name(), wc_format_stock_quantity_for_display( $product->get_stock_quantity() - $held_stock, $product ) ) );
							}
						}
					}
				}

				WC()->customer->set_props(
					array(
						'billing_country'  => $order->get_billing_country() ? $order->get_billing_country() : null,
						'billing_state'    => $order->get_billing_state() ? $order->get_billing_state() : null,
						'billing_postcode' => $order->get_billing_postcode() ? $order->get_billing_postcode() : null,
					)
				);
				WC()->customer->save();

				$available_gateways = WC()->payment_gateways->get_available_payment_gateways();

				if ( count( $available_gateways ) ) {
					current( $available_gateways )->set_current();
				}

				wc_get_template(
					'checkout/form-pay.php',
					array(
						'order'              => $order,
						'available_gateways' => $available_gateways,
						'order_button_text'  => apply_filters( 'woocommerce_pay_order_button_text', __( 'Pay for order', 'woocommerce' ) ),
					)
				);

			} catch ( Exception $e ) {
				wc_print_notice( $e->getMessage(), 'error' );
			}
		} elseif ( $order_id ) {

			// Pay for order after checkout step.
			$order_key = isset( $_GET['key'] ) ? wc_clean( wp_unslash( $_GET['key'] ) ) : ''; // WPCS: input var ok, CSRF ok.
			$order     = wc_get_order( $order_id );

			if ( $order && $order->get_id() === $order_id && hash_equals( $order->get_order_key(), $order_key ) ) {

				if ( $order->needs_payment() ) {

					wc_get_template( 'checkout/order-receipt.php', array( 'order' => $order ) );

				} else {
					/* translators: %s: order status */
					wc_print_notice( sprintf( __( 'This order&rsquo;s status is &ldquo;%s&rdquo;&mdash;it cannot be paid for. Please contact us if you need assistance.', 'woocommerce' ), wc_get_order_status_name( $order->get_status() ) ), 'error' );
				}
			} else {
				wc_print_notice( __( 'Sorry, this order is invalid and cannot be paid for.', 'woocommerce' ), 'error' );
			}
		} else {
			wc_print_notice( __( 'Invalid order.', 'woocommerce' ), 'error' );
		}

		do_action( 'after_woocommerce_pay' );
	}	
	
	/**
	 * Show the thanks page.
	 *
	 * @param int $order_id Order ID.
	 */
	private static function order_received( $order_id = 0 ) {
		$order = false;

		// Get the order.
		$order_id  = apply_filters( 'woocommerce_thankyou_order_id', absint( $order_id ) );
		$order_key = apply_filters( 'woocommerce_thankyou_order_key', empty( $_GET['key'] ) ? '' : wc_clean( wp_unslash( $_GET['key'] ) ) ); // WPCS: input var ok, CSRF ok.

		if ( $order_id > 0 ) {
			$order = wc_get_order( $order_id );
			if ( ! $order || ! hash_equals( $order->get_order_key(), $order_key ) ) {
				$order = false;
			}
		}

		// Empty awaiting payment session.
		unset( WC()->session->order_awaiting_payment );

		// In case order is created from admin, but paid by the actual customer, store the ip address of the payer
		// when they visit the payment confirmation page.
		if ( $order && $order->is_created_via( 'admin' ) ) {
			$order->set_customer_ip_address( WC_Geolocation::get_ip_address() );
			$order->save();
		}

		// Empty current cart.
		wc_empty_cart();

		wc_get_template( 'checkout/thankyou.php', array( 'order' => $order ) );
	}
	
	function override_default_address_checkout_fields( $address_fields ) {
   
		$address_fields['address_1']['placeholder'] = '';
		$address_fields['address_2']['label'] = '';
		
		
		$fields['billing']['billing_address_2']['label'] = 'Apartment, suite, etc.';
		
		return $address_fields;
	}

	function my_woocommerce_form_field( $field ) {
		return preg_replace(
			'#<p class="form-row (.*?)"(.*?)>(.*?)</p>#',
			'<div class="form-row $1 "$2>$3</div>',
			$field
		);
	}
	
	public function bew_regenerate_fields( $fields ) {
				
		global $post;
		if( has_shortcode( $post->post_content, 'woocommerce_checkout' ) ) return $fields;
		//if( stripos( $post->post_content, 'bew-wao' ) ) return $fields;
		
		$_checkout_fields = get_option( '_bew_checkout_fields', [] );
		
		//echo var_dump($_checkout_fields);
		
		if( empty($_checkout_fields) ) return $fields;
					
		// Apply Custom fields
		//$fields = $_checkout_fields ;
		
		// Create custom fields for sections.
		
		//check if bew_fields_billing is active
		$bf_billing = get_option( '_bew_checkout_fields_billing');
		
		//check if bew_fields_information is active
		$bf_information = get_option( '_bew_checkout_fields_information');
		
		//check if bew_fields_shipping is active
		$bf_shipping = get_option( '_bew_checkout_fields_shipping');
		
		//check if bew_fields_order is active
		$bf_order = get_option( '_bew_checkout_fields_order');

		//echo var_dump($_checkout_fields);		
		foreach ( $_checkout_fields as $section => $_fields ) {
						
			if(${"bf_" . $section}  == "bew_fields_". $section ){
				
				if($section == 'information'){
					$section = 'billing';
				}
				
				foreach ( $_fields as $key => $_field ) {
				  
				  //label			  
				  $fields[$section][$key]['label'] = $_field['label'];
				  
				  //required
				  //fields that change dynamically based on the chosen country of a user (address 1, address 2, city, state, postcode) cannot have custom required rules			  
				  if( ($key == $section . '_country') || ($key == $section . '_address_1') || ($key == $section . '_address_2') || 
					  ($key == $section . '_city') || ($key == $section . '_state') || ($key == $section . '_postcode')   ){
					 
				  } else {					
					$fields[$section][$key]['required'] = $_field['required'];  
				  }
							  
				}
			}
			
		}
		
		//echo var_dump($fields);		
		//$fields['billing']['billing_first_name']['label'] = 'testing';
		
		// Get list of fields 
		foreach ( $_checkout_fields as $section => $_fields ) {
			
			if( count( $_fields ) > 0 ) {
				foreach ( $_fields as $key => $_field ) {					
					$bew_fields[] = $key;													
				}
			}
		}
				
		$wc_fields = $this->bew_wc_fields();
		
		//update_option( '_bew_checkout_fields_validate', $bew_fields );
				
		//echo var_dump($wc_fields);
		//echo var_dump($_checkout_fields);
		//echo var_dump($bew_fields);
		//$get_fields = WC()->checkout->get_checkout_fields();
		//echo var_dump($get_fields) ;
		
		if ( !is_admin()) {
			
			foreach ( $wc_fields as $section => $_fields ) {
				
				if( count( $_fields ) > 0 ) {
					// Unset delete fields
					foreach ( $_fields as $_field ) {							
						if( !in_array( $_field, $bew_fields ) ) {						
							unset( $fields[ $section ][ $_field ] );
						}
					}
				}
			}
		}
				
		return $fields;
	}
	
	public function bew_regenerate_fields_validate( $fields ) {
				
		global $post;
		if( has_shortcode( $post->post_content, 'woocommerce_checkout' ) ) return $fields;
		//if( stripos( $post->post_content, 'bew-wao' ) ) return $fields;
	
		$_checkout_fields = get_option( '_bew_checkout_fields', [] );
						
		if( empty($_checkout_fields) ) return $fields;
								
		foreach ( $_checkout_fields as $section => $_fields ) {
			
			if( count( $_fields ) > 0 ) {

				foreach ( $_fields as $_field ) {
					
					$bew_fields[] = $_field["{$section}_input_name"] ;
					
					if($section == 'information'){
						$section = 'billing';
						$fields[ 'information' ][ $_field["{$section}_input_name"] ] = [
						'label'			=> $_field["{$section}_input_label"],
						'required'		=> $_field["{$section}_input_required"],
						'class'			=> $_field["{$section}_input_class"],
						'autocomplete'	=> $_field["{$section}_input_autocomplete"],
						'type'			=> $_field["{$section}_input_type"],
						];	
					} else {
						
						$fields[ $section ][ $_field["{$section}_input_name"] ] = [
						'label'			=> $_field["{$section}_input_label"],
						'required'		=> $_field["{$section}_input_required"],
						'class'			=> $_field["{$section}_input_class"],
						'autocomplete'	=> $_field["{$section}_input_autocomplete"],
						'type'			=> $_field["{$section}_input_type"],
						];	
						
					}										
													
				}
			}
		}
				
		$wc_fields = $this->bew_wc_fields();
				
		//echo var_dump($wc_fields);
		//echo var_dump($_checkout_fields);
		//echo var_dump($bew_fields);
		//echo var_dump($fields);
		//$get_fields = WC()->checkout->get_checkout_fields();
		//echo var_dump($get_fields) ;
			
			foreach ( $wc_fields as $section => $_fields ) {
				
				if( count( $_fields ) > 0 ) {
					// Unset delete fields
					foreach ( $_fields as $_field ) {							
						if( !in_array( $_field, $bew_fields ) ) {						
							unset( $fields[ $section ][ $_field ] );
						}
					}
				}
			}
						
		return $fields;
	}
		
	public function bew_address_fields( $checkout_fields ) {

		$checkout_fields['billing']['billing_address_2']['label'] = 'Apartment, suite, etc.';
		$checkout_fields['shipping']['shipping_address_2']['label'] = 'Apartment, suite, etc.';
		
		return $checkout_fields;
	}
	
	public function bew_save_additional_fields( $order, $data ) {

		$posted = $_POST;

		unset( $posted['woocommerce-process-checkout-nonce'] );
		unset( $posted['_wp_http_referer'] );
		
		$wc_fields = $this->bew_wc_fields();
		$default_fields = array_merge( $wc_fields['billing'], $wc_fields['shipping'], $wc_fields['order'] );
					
		foreach ( $posted as $key => $value ) {
			if( !in_array( $key, $default_fields ) ) {
				$order->update_meta_data( $key, sanitize_text_field( $value ) );
			}
		}
	}
	
	function bew_wc_fields( $section = '' ) {
		$fields = [
			'billing' => [ 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_country', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_phone', 'billing_email' ],
			'shipping' => [ 'shipping_first_name', 'shipping_last_name', 'shipping_company', 'shipping_country', 'shipping_address_1', 'shipping_address_2', 'shipping_city', 'shipping_state', 'shipping_postcode' ],
			'order' => [ 'order_comments' ]
		];

		if( $section != '' && isset( $fields[ $section ] ) ) {
			return apply_filters( 'bew_wc_fields', $fields[ $section ] );
		}

		return apply_filters( 'bew_wc_fields', $fields );
	}
		
	/**
	 * Display field value on the order edit page
	 */	
	function bew_checkout_field_display_admin_order_meta($order){
		echo '<p><strong>'.__('Shipping Phone').':</strong> ' . '<span style=" display: block; margin: 5px 0 0 0; ">' .get_post_meta( $order->get_id(), 'shipping_phone', true ) . '</span>'. '</p>';
	}
		
	function bew_review_order_shipping_address() {
		
		$state_code = WC()->customer->get_shipping_state();		
		$country_code = WC()->customer->get_shipping_country();
		$state_name   = WC()->countries->get_states($country_code)[$state_code];	
		$country_name   = WC()->countries->countries[ $country_code ];
				
		$shipping_address = $state_name . ", " . $country_name; 
		
		if ( $shipping_address ) {
			// Translators: $s shipping destination.
			printf( esc_html__( 'Shipping to %s.', 'woocommerce' ) . ' ', '<span>' . esc_html( $shipping_address ) . '</span>' );
		} else {
			echo wp_kses_post( apply_filters( 'woocommerce_shipping_estimate_html', __( 'Shipping options will be updated during checkout.', 'woocommerce' ) ) );
		}
	}
		
	function is_bewwco($is_bew_woo_checkout) {
		$is_bewwco = "yes";
		echo $is_bewwco;
		return $is_bewwco;
	}
	
	
	/**
	 * Validate multi-step checkout fields.
	 *
	 * @since 2.1.0
	 */
	public function bew_validate_checkout_callback() {
		$posted_data = isset($_POST['posted_data'])?$_POST['posted_data']:array();
		
		$_checkout_fields = get_option( '_bew_checkout_fields_validate', [] );
				
		$WC_Checkout = new WC_Checkout();
        $errors = new WP_Error();
		
		//Get custom bew checkout fields to validate
		add_filter( 'woocommerce_checkout_fields', [ $this, 'bew_regenerate_fields_validate' ]);
		
		$html = '';
		
        ////////////////////////////////////////
        $skipped = array();
        $data = array(
            'terms' => (int) isset($posted_data['terms']),
            'createaccount' => (int) !empty($posted_data['createaccount']),
            'payment_method' => isset($posted_data['payment_method']) ? wc_clean($posted_data['payment_method']) : '',
            'shipping_method' => isset($posted_data['shipping_method']) ? wc_clean($posted_data['shipping_method']) : '',
            'ship_to_different_address' => !empty($posted_data['ship_to_different_address']) && !wc_ship_to_billing_address_only(),
            'woocommerce_checkout_update_totals' => isset($posted_data['woocommerce_checkout_update_totals']),
			'use_address_for_billing' => isset($posted_data['use_address_for_billing']),
        );
		            
        foreach ($WC_Checkout->get_checkout_fields() as $fieldset_key => $fieldset) {
            if (isset($data['ship_to_different_address'])) {
                if ('shipping' === $fieldset_key && (!$data['ship_to_different_address'] || !WC()->cart->needs_shipping_address() )) {
                    continue;
                }
            }

            if (isset($data['createaccount'])) {
                if ('account' === $fieldset_key && ( is_user_logged_in() || (!$WC_Checkout->is_registration_required() && empty($data['createaccount']) ) )) {
                    continue;
                }
            }
            foreach ($fieldset as $key => $field) {
                $type = sanitize_title(isset($field['type']) ? $field['type'] : 'text' );

                switch ($type) {
                    case 'checkbox' :
                        $value = isset($posted_data[$key]) ? 1 : '';
                        break;
                    case 'multiselect' :
                        $value = isset($posted_data[$key]) ? implode(', ', wc_clean($posted_data[$key])) : '';
                        break;
                    case 'textarea' :
                        $value = isset($posted_data[$key]) ? wc_sanitize_textarea($posted_data[$key]) : '';
                        break;
                    default :
                        $value = isset($posted_data[$key]) ? wc_clean($posted_data[$key]) : '';
                        break;
                }

                $data[$key] = apply_filters('woocommerce_process_checkout_' . $type . '_field', apply_filters('woocommerce_process_checkout_field_' . $key, $value));
            }
        }

        if (in_array('shipping', $skipped) && ( WC()->cart->needs_shipping_address() || wc_ship_to_billing_address_only() )) {
            foreach ($this->get_checkout_fields('shipping') as $key => $field) {
                $data[$key] = isset($data['billing_' . substr($key, 9)]) ? $data['billing_' . substr($key, 9)] : '';
            }
        }

        //////////////////////////////////////////////////
        foreach ($WC_Checkout->get_checkout_fields() as $fieldset_key => $fieldset) {
			
			//$html.= var_dump("hola". $data['use_address_for_billing']);	
			
			if ($data['use_address_for_billing'] === true){
				
				// Check shipping and contact fields								
				if($fieldset_key == 'billing')
				continue;				
				
			} else {
				// Check shipping, contact and billing fields				
			
			}
			
			//$html.= var_dump($information);
          	//$html.= var_dump($fieldset);		
			//$html.= var_dump($WC_Checkout->get_checkout_fields()); 
			
            if (isset($data['ship_to_different_address'])) {
								
                if ('shipping' === $fieldset_key && (!$data['ship_to_different_address'] || !WC()->cart->needs_shipping_address() )) {
                    continue;						
                }
            }

            if (isset($data['createaccount'])) {
                if ('account' === $fieldset_key && ( is_user_logged_in() || (!$WC_Checkout->is_registration_required() && empty($data['createaccount']) ) )) {
                    continue;
                }
            }
			
			
				
			// add email information on shipping fields
			//$fieldset = $fieldset;
			
			//$html.= var_dump($fieldset);			
            foreach ($fieldset as $key => $field) {
                if (!isset($data[$key])) {
                    continue;
                }
                $required = !empty($field['required']);
                $format = array_filter(isset($field['validate']) ? (array) $field['validate'] : array() );
                $field_label = isset($field['label']) ? $field['label'] : '';
										
					switch ($fieldset_key) {
						case 'shipping' :
							/* translators: %s: field name */
							$field_label = sprintf(__('Shipping %s', 'bew-extras'), $field_label);
							break;
						case 'billing' :
							/* translators: %s: field name */
							$field_label = sprintf(__('Billing %s', 'bew-extras'), $field_label);
							break;
						case 'information' :
							/* translators: %s: field name */
							$field_label = sprintf(__('Contact %s', 'bew-extras'), $field_label);
							break;
					}
				
                if (in_array('postcode', $format)) {
                    $country = isset($data[$fieldset_key . '_country']) ? $data[$fieldset_key . '_country'] : WC()->customer->{"get_{$fieldset_key}_country"}();
											
                    $data[$key] = wc_format_postcode($data[$key], $country);						

                    if ('' !== $data[$key] && !WC_Validation::is_postcode($data[$key], $country)) {
                        $errors->add('validation', sprintf(__('%s is not a valid postcode / ZIP.', 'bew-extras'), '<strong>' . esc_html($field_label) . '</strong>'));
                    }
                }

                if (in_array('phone', $format)) {
                    $data[$key] = wc_format_phone_number($data[$key]);

                    if ('' !== $data[$key] && !WC_Validation::is_phone($data[$key])) {
                        /* translators: %s: phone number */
                        $errors->add('validation', sprintf(__('%s is not a valid phone number.', 'bew-extras'), '<strong>' . esc_html($field_label) . '</strong>'));
                    }
                }

                if (in_array('email', $format) && '' !== $data[$key]) {
                    $data[$key] = sanitize_email($data[$key]);

                    if (!is_email($data[$key])) {
                        /* translators: %s: email address */
                        $errors->add('validation', sprintf(__('%s is not a valid email address.', 'bew-extras'), '<strong>' . esc_html($field_label) . '</strong>'));
                        continue;
                    }
                }

                if ($required && '' === $data[$key]) {
                    /* translators: %s: field name */
                    $errors->add('required-field', apply_filters('woocommerce_checkout_required_field_notice', sprintf(__('%s is a required field.', 'briefcase-extras'), '<strong>' . esc_html($field_label) . '</strong>'), $field_label));
                }
            }
        }
            
        $valid = TRUE;
        if ($errors->get_error_messages()) {
            $valid = FALSE;
            $html = '<div class="woocommerce-NoticeGroup woocommerce-NoticeGroup-checkout"><ul class="woocommerce-error" role="alert">';
            foreach ($errors->get_error_messages() as $message) {
                $html.='<li>' . $message . '</li>';
            }
                $html.='</ul></div>';
            }
            
        wp_send_json(array("valid"=>$valid,"html"=>$html));
        wp_die();
    }

    public function get_checkout_fields( $order = false ){
      
        $needs_shipping = true;		
		$fields = get_option( '_bew_checkout_fields', [] );

        return $fields;
    }

    public function get_order_id($order){
        $order_id = false;
        if( version_compare( WOOCOMMERCE_VERSION, '2.3.0', '>=' ) ){
            $order_id = $order->get_id();
        }else{
            $order_id = $order->id;
        }
        return $order_id;
    }

    public function get_option_value( $field, $value ){
        $type = isset( $field['type'] ) ? $field['type'] : false;
        if( $type === 'select' || $type === 'radio' ){
            $options = isset( $field['options'] ) ? $field['options'] : array();			
            if( is_array( $options ) ){                
                $value = ( isset( $options[$value] ) ? $options[$value] : '' );
            }
        }
        return $value;
    }

    public function is_custom_field( $field ){
        $status = false;
        if( is_array( $field ) ){
            if( isset( $field['custom'] ) && $field['custom'] === true ){
                $status = true;
            }
        }
        return $status;
    }

    public function bew_show_custom_fields_in_email( $ofields, $sent_to_admin, $order ){
        
        $custom_fields    = array();
        $checkout_fields  = $this->get_checkout_fields();

        foreach ( $checkout_fields as $section => $fields ) {
			foreach( $fields as $key => $field ) {

				if( isset( $field['show_in_email'] ) && $field['show_in_email'] ){

					$order_id   = $this->get_order_id($order);
					$value      = get_post_meta( $order_id, $key, true );
					
					if( $value ){
						$label = isset( $field['label'] ) && $field['label'] ? $field['label'] : $key;
						$label = esc_attr( $label );
						$value = $this->get_option_value( $field, $value );
						
						$custom_field = array();
						$custom_field['label'] = $label;
						$custom_field['value'] = $value;

						$custom_fields[$key] = $custom_field;
					}

				}

			}
		}

        return array_merge( $ofields, $custom_fields );
    }
	
    public function bew_order_details_after_order_table( $order ){

        $order_id        = $this->get_order_id( $order );
        $checkout_fields = $this->get_checkout_fields( $order );

        if( is_array( $checkout_fields ) && !empty( $checkout_fields ) ){

            $output_data = '';
			
			foreach ( $checkout_fields as $section => $fields ) {
			
				if( count( $fields ) > 0 ) {

					foreach( $fields as $key => $field ){     

						if( $this->is_custom_field( $field ) && isset( $field['show_in_order'] ) && $field['show_in_order'] ){
														
							$value = $order->get_meta($key);				
							
							if( $value ){
								
								$label = ( isset( $field['label'] ) && $field['label'] ? $field['label'] : $key );

								$label = esc_attr( $label );

								$value = $this->get_option_value( $field, $value );								
								
								if( is_account_page() ){
									if( apply_filters( 'bew_view_order_customer_details_table_view', true ) ){
										$output_data .= '<tr><th>'. $label .':</th><td>'. $value .'</td></tr>';
									}else{
										$output_data .= '<br/><dt>'. $label .':</dt><dd>'. $value .'</dd>';
									}
								}else{
									if( apply_filters( 'bew_thankyou_customer_details_table_view', true )){
										$output_data .= '<tr><th>'. $label .':</th><td>'. $value .'</td></tr>';
									}else{
										$output_data .= '<br/><dt>'. $label .':</dt><dd>'. $value .'</dd>';
									}
								}
							}
						}
					}
				}
			
            }
            
            if( $output_data ){
                do_action( 'bew_order_details_before_custom_fields_table', $order ); 
                ?>
                    <table class="woocommerce-table woocommerce-table--custom-fields shop_table custom-fields">
                        <?php echo $output_data; ?>
                    </table>
                <?php
                do_action( 'bew_order_details_after_custom_fields_table', $order ); 
            }

        }
    }

    public function bew_order_details_after_billing_address( $order ){

        $order_id        = $this->get_order_id( $order );
        $checkout_fields = $this->get_checkout_fields( $order );
		
		$checkout_fields_billing  = $checkout_fields['billing'];
				
        if( is_array( $checkout_fields_billing ) && !empty( $checkout_fields_billing ) ){

            $output_data = '';
					
			foreach( $checkout_fields_billing as $key => $field ){     
				if( count( $field ) > 0 ) {
					if( $this->is_custom_field( $field ) && isset( $field['show_in_order'] ) && $field['show_in_order'] ){							

						$value = $order->get_meta($key);
															
						if( $value ){
									
							$label = ( isset( $field['label'] ) && $field['label'] ? $field['label'] : $key );
							$label = esc_attr( $label );
							$value = $this->get_option_value( $field, $value );							
									
							$output_data .= '<tr><th>'. $label .'</th><td>: '. $value .'</td></tr>';
									
						}
					}
				}
			}			
            
            if( $output_data ){               
                ?>	
				<h3><?php  printf( esc_html__( 'Custom Billing Data', 'bew-extras' )); ?></h3>
				<table class="woocommerce-table--custom-fields custom-fields">
                    <?php echo $output_data; ?>
				</table>
                <?php               
            }

        }
    }

	public function bew_order_details_after_shipping_address( $order ){

        $order_id        = $this->get_order_id( $order );
        $checkout_fields = $this->get_checkout_fields( $order );
		
		$checkout_fields_shipping  = $checkout_fields['shipping'];
				
        if( is_array( $checkout_fields_shipping ) && !empty( $checkout_fields_shipping ) ){

            $output_data = '';
				
			foreach( $checkout_fields_shipping as $key => $field ){     
				if( count( $field ) > 0 ) {
					if( $this->is_custom_field( $field ) && isset( $field['show_in_order'] ) && $field['show_in_order'] ){							

						$value = $order->get_meta($key);
															
						if( $value ){
									
							$label = ( isset( $field['label'] ) && $field['label'] ? $field['label'] : $key );
							$label = esc_attr( $label );
							$value = $this->get_option_value( $field, $value );								
									
							$output_data .= '<tr><th>'. $label .'</th><td>: '. $value .'</td></tr>';
									
						}
					}
				}
			}
	            
            if( $output_data ){               
                ?>	
				<h3><?php  printf( esc_html__( 'Custom Shipping Data', 'bew-extras' )); ?></h3>
                <table class="woocommerce-table--custom-fields custom-fields">
                    <?php echo $output_data; ?>
                </table>
                <?php               
            }
        }
    }

	public function add_ajax_events() {
		$ajax_events = array(
			'bew_get_variation'      => true,
			'bew_add_to_cart'        => true,
			'bew_remove_form_cart'   => true,
			'bew_us_add_all_to_cart' => true,
			'bew_us_set_session'     => true,
		);
		foreach ( $ajax_events as $ajax_event => $nopriv ) {
			add_action( 'wp_ajax_woocommerce_' . $ajax_event, array( $this, $ajax_event ) );
			if ( $nopriv ) {
				add_action( 'wp_ajax_nopriv_woocommerce_' . $ajax_event, array( $this, $ajax_event ) );
			}
			// WC AJAX can be used for frontend ajax requests.
			add_action( 'wc_ajax_' . $ajax_event, array( $this, $ajax_event ) );
		}
	}
	
	public function bew_add_to_cart() {
		if ( empty( $_REQUEST['bew_us_product_id'] ) && empty( $_REQUEST['bew_ob_product_id'] ) ) {
			wp_die();
		}
		$notices = WC()->session->get( 'wc_notices', array() );
			
		if ( ! empty( $notices['error'] ) ) {
			wp_send_json( array( 'error' => true, 'message' => wc_print_notices( true ) ) );
		}
		if ( ! empty( $notices['success'] ) ) {
			unset( $notices['success'] );
			WC()->session->set( 'wc_notices', $notices );
		}
		WC_AJAX::get_refreshed_fragments();
		die();
	}
	
	public function bew_remove_form_cart() {
		$cart_item_key = isset( $_POST['cart_item_key'] ) ? wc_clean( wp_unslash( $_POST['cart_item_key'] ) ) : '';
		if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
			WC_AJAX::get_refreshed_fragments();
		} else {
			$product_id   = isset( $_POST['product_id'] ) ? sanitize_text_field( wp_unslash( $_POST['product_id'] ) ) : 0;
			$product_type = isset( $_POST['product_type'] ) ? sanitize_text_field( wp_unslash( $_POST['product_type'] ) ) : '';
			if ( $product_id && $product_type ) {
				foreach ( WC()->cart->get_cart() as $key => $item ) {
					if ( isset( $item[$product_type]) && ( $product_id == $item['product_id'] || $product_id == $item['variation_id'] ) ) {
						$cart_item_key = $key;
					}
				}
				if ( $cart_item_key && false !== WC()->cart->remove_cart_item( $cart_item_key ) ) {
					WC_AJAX::get_refreshed_fragments();
				}
			}
			$notices = WC()->session->get( 'wc_notices', array() );
			if ( ! empty( $notices['error'] ) ) {
				wp_send_json( array( 'error' => true, 'message' => wc_print_notices( true ) ) );
			}
			wp_send_json_error();
		}
		die();
	}

	public function bew_us_add_all_to_cart() {
		$data   = isset( $_POST['bew_us_alltc'] ) ? wc_clean( $_POST['bew_us_alltc'] ) : array();
		$result = array(
			'status'  => 'error',
			'message' => '',
		);
		if ( empty( $data ) ) {
			$result['message'] = __( 'Not found data', 'checkout-upsell-funnel-for-woo' );
			wp_send_json( $result );
			wp_die();
		}
		$request = wc_clean($_REQUEST);
		$post    = wc_clean($_POST);
		foreach ( $data as $i => $pd_data ) {
			$arg            = array_column( $pd_data, 'value', 'name' );
			$_REQUEST       = array_merge( $request, $arg );
			$_POST          = array_merge( $post, $arg );
			$product_id     = apply_filters( 'woocommerce_add_to_cart_product_id', absint( $arg['product_id'] ?? 0 ) );
			$adding_to_cart = wc_get_product( $product_id );
			if ( ! $adding_to_cart ) {
				continue;
			}
			$product_type   = $adding_to_cart->get_type();
			$quantity       = empty( $arg['quantity'] ) ? 1 : wc_stock_amount( wp_unslash( $arg['quantity'] ) );
			$product_status = get_post_status( $product_id );
			$variation_id   = absint( $arg['variation_id'] ?? 0 );
			$variations     = array();
			foreach ( $arg as $k => $v ) {
				$check = strpos( $k, 'attribute_' );
				if ( $check === 0 ) {
					$variations[ $k ] = $v;
				}
			}
			if ( 'variable' === $product_type || 'variation' === $product_type ) {
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity, $variation_id, $variations );
				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity, $variation_id, $variations ) && 'publish' === $product_status ) {
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				}
			} else {
				$passed_validation = apply_filters( 'woocommerce_add_to_cart_validation', true, $product_id, $quantity );
				if ( $passed_validation && false !== WC()->cart->add_to_cart( $product_id, $quantity ) && 'publish' === $product_status ) {
					do_action( 'woocommerce_ajax_added_to_cart', $product_id );
				}
			}
		}
		$notices = WC()->session->get( 'wc_notices', array() );
		if ( ! empty( $notices['error'] ) ) {
			$result['message'] = wc_print_notices( true );
			wp_send_json( $result );
		}
		if ( ! empty( $notices['success'] ) ) {
			unset( $notices['success'] );
			WC()->session->set( 'wc_notices', $notices );
		}
		WC_AJAX::get_refreshed_fragments();
		die();
	}

	public function bew_us_set_session() {
		if ( ! isset( $_POST['time_pause'] ) && ! isset( $_POST['time_end'] ) ) {
			wp_die();
		}
		if ( ! empty( $_POST['time_pause'] ) ) {
			WC()->session->set( 'bew_us_time_pause', 1 );
			wp_send_json( array( 'status' => 'success' ) );
		}
		$error = isset( $_POST['error_message'] ) ? wp_kses_post( wp_unslash( $_POST['error_message'] ) ) : '';
		if ( $error ) {
			wc_add_notice( $error, 'error' );
		}
		if ( ! empty( $_POST['time_end'] ) ) {
			WC()->session->set( 'bew_us_time_end', current_time( 'timestamp' ) );
			WC()->session->set( 'bew_us_time_pause', '' );
			wp_send_json( array( 'status' => 'success' ) );
		}
		wp_die();
	}

	public function bew_ob_shortcode_init() {
		add_shortcode( 'bew_checkout_order_bump', array( $this, 'bew_checkout_order_bump' ) );
	}

	public function bew_checkout_order_bump( $atts ) {
				
		extract( shortcode_atts( array(
			'id'         => '',
			'product_id' => '',
		), $atts ) );

		$product_id = intval($product_id);

//		if ( ! $id ) {
//			return false;
//		}

		if ( ! $product_id || ! ( $product = wc_get_product( $product_id ) ) ) {
			return false;
		}
		if ( ! $product->is_in_stock() ) {
			return false;
		}

		$ob_in_cart = $this->bew_get_pd_qty_in_cart( $product_id, 'bew_ob_product', $id );
		//echo "dc" . $ob_in_cart;
		if ( $ob_in_cart ) {
			$product_qty    = $ob_in_cart;
			$cart_item_info = $this->get_cart_item( $product_id, 'bew_ob_product', $id );
			//echo var_dump($cart_item_info);
			$cart_item_data = '';
			if ( ! empty( $cart_item_info['product_id'] ) ) {
				$cart_item_data .= 'data-added_id=' . $cart_item_info['product_id'] . ' ';
			}
			if ( ! empty( $cart_item_info['cart_item_key'] ) ) {
				$cart_item_data .= 'data-cart_item_key=' . $cart_item_info['cart_item_key'] . ' ';
			}
			if ( ! empty( $cart_item_info['variation'] ) ) {
				foreach ( $cart_item_info['variation'] as $attr_name => $attr_value ) {
					$cart_item_data .= ' data-' . $attr_name . '=' . $attr_value . '';
				}
			}
		} else {
			$in_cart     = $this->bew_get_pd_qty_in_cart( $product_id );
			$product_qty = 1;
			if ( $product->is_sold_individually() ) {
				if ( $in_cart ) {
					return false;
				}
				$product_qty = $product_qty ? 1 : 0;
			}
		}

		//$in_cart     = $this->bew_get_pd_qty_in_cart( $product_id );
		//$product_qty = 1;
		if ( $product->is_sold_individually() ) {
			//if ( $in_cart ) {
			//	return false;
			//}
			$product_qty = $product_qty ? 1 : 0;
		}

		if ( ! $product_qty ) {
			return false;
		}
		
		$ob_title        = get_option('ob_title_text');
		$ob_image        = true;
		$ob_content      = get_option('ob_desc_text');
		$ob_pd_class     = array( 'bew-product bew-ob-product-wrap' );
		$ob_pd_class[]   = $ob_in_cart ? 'bew-ob-product-wrap-checked bew-product-wrap-checked' : '';
		$ob_pd_class[]   = is_rtl() ? 'bew-ob-product-wrap-rtl' : '';
		$ob_pd_class     = trim( implode( ' ', $ob_pd_class ) );
		ob_start();
		?>
        <div class="<?php echo esc_attr( $ob_pd_class ); ?>"
             data-product_id="<?php echo esc_attr( $product_id ); ?>" <?php echo ! empty( $cart_item_data ) ? esc_attr( $cart_item_data ) : ''; ?>>
            <div class="bew-ob-product-top">
                <div class="bew-ob-title-wrap">
					<?php
					echo wp_kses_post( apply_filters( 'bew_ob_checkbox_html', '<span class="bew-ob-checkbox"></span>' ) );
					if ( $ob_title ) {
						?>
                        <div class="bew-ob-title">
							<?php echo wp_kses_post( $ob_title ); ?>
                        </div>
						<?php
					}
					?>
                </div>
                <div class="bew-ob-price">
					<?php
					echo $product->get_price_html();
					?>
                </div>
            </div>
            <div class="bew-ob-product-content">
				<div class="bew-ob-product-title-wrap">
					<?php echo $product->get_name(); ?>
				</div>
                <div class="bew-ob-product-desc-wrap">
			        <?php
			        if ( $ob_image ) {
				        ?>
                        <div class="bew-ob-product-image">
					        <?php
					        $product_img = $product->get_image( 'woocommerce_thumbnail' );
					        echo wp_kses_post( $product_img );
					        ?>
                        </div>
				        <?php
			        }
			        if ( $ob_content ) {
				        ?>
                        <div class="bew-ob-product-desc"><?php echo $ob_content?></div>
				        <?php
			        }
			        
					$product_type = $product->get_type();
					
					switch($product_type){
						
						case 'simple':						
							echo $this->bew_ob_simple_add_to_cart( $product, $product_qty, $id );							
						break;

						case 'variable':						
							echo $this->bew_ob_variable_add_to_cart( $product, $product_qty, $id );							
						break;		
					}
					
			        ?>
                </div>
            </div>
        </div>
		<?php
		$html = ob_get_clean();
		//$html = str_replace( '{product_name}', $product->get_name(), $html );
		//$html = str_replace( '{ob_content}',$this->get_ob_content($index,$ob_content, $product), $html );
		return $html;
	}

	public static function get_cart_item( $product_id, $type = '', $rule_id = '' ) {
		
		if ( ! $type || ! $product_id || \WC()->cart->is_empty() ) {
			return 0;
		}
		$cart_item = array();
		foreach ( \WC()->cart->get_cart() as $k => $item ) {
			//if ( empty( $item[ $type ] ) ) {
			//	continue;
			//}
			//if ( $rule_id && ! empty( $item[ $type ]['rule_id'] ) && $rule_id != $item[ $type ]['rule_id'] ) {
			//	continue;
			//}
			//if ( ! $rule_id && ! empty( $item[ $type ]['product_id'] ) && $product_id != $item[ $type ]['product_id'] ) {
			//	continue;
			//}
			$item_product_id   = $item['product_id'] ?? 0;
			$item_variation_id = $item['variation_id'] ?? 0;
			if ( $product_id == $item_product_id || $item_variation_id == $product_id ) {
				$cart_item['cart_item_key'] = $k;
				$cart_item['product_id']    = $item_variation_id ?: $product_id;
				if ( $item_variation_id ) {
					$cart_item['variation'] = $item['variation'];
				}
				break;
			}
		}

		return $cart_item;
	}
	
	public function bew_get_pd_qty_in_cart( $product_id, $type = '', $rule_id = '' ) {
		if ( \WC()->cart->is_empty() ) {			
			return 0;
		}
		$in_cart = 0;
		foreach ( \WC()->cart->get_cart() as $k => $cart_item ) {
				
			//echo var_dump($cart_item);
				
			//if ( $type ) {
				//if ( empty( $cart_item[ $type ] ) ) {
			//		continue;
			//	}
			//	if ( $rule_id && ! empty( $cart_item[ $type ]['rule_id'] ) && $rule_id != $cart_item[ $type ]['rule_id'] ) {
			//		continue;
			//	}
			//	if ( ! $rule_id && ! empty( $cart_item[ $type ]['product_id'] ) && $product_id != $cart_item[ $type ]['product_id'] ) {
			//		continue;
			//	}
			// }
			$item_variation_id = $cart_item['variation_id'] ?? 0;
			$item_product_id   = $cart_item['product_id'] ?? 0;
			if ( $product_id == $item_product_id || $item_variation_id == $product_id ) {				
				$in_cart += $cart_item['quantity'] ?? 0;
				
			}
			
		}
		//echo "cart " . $in_cart;
		return $in_cart;
	}

	public function bew_ob_simple_add_to_cart( $product, $product_qty, $rule_id ) {
		$product_id = $product->get_id();
		?>
        <div class="bew-ob-cart-form" data-product_id="<?php echo esc_attr( $product_id ); ?>">
            <input type="hidden" name="quantity" value="<?php echo esc_attr( $product_qty ); ?>"/>
            <input type="hidden" name="add-to-cart" class="bew-add-to-cart" value=""/>
            <input type="hidden" name="product_id" class="bew-product_id" value=""/>
            <input type="hidden" name="variation_id" class="variation_id" value="0"/>
            <input type="hidden" name="bew_ob_product_id" class="bew_ob_product_id" value="1"/>
            <input type="hidden" name="bew_ob_info[rule_id]" class="bew_ob_rule_id" value="<?php echo esc_attr( $rule_id ); ?>"/>
        </div>
		<?php
	}

	public function bew_ob_variable_add_to_cart( $product, $product_qty, $rule_id) {
		$attributes = $product->get_variation_attributes();
		if ( empty( $attributes ) ) {
			return;
		}
		$product_id          = $product->get_id();
		$product_name        = $product->get_name();
		$variation_count     = count( $product->get_children() );
		$get_variations      = $variation_count <= apply_filters( 'woocommerce_ajax_variation_threshold', 30, $product );
		$selected_attributes = $product->get_default_attributes();
		if ( $get_variations ) {
			$available_variations = $product->get_available_variations();
			if ( empty( $available_variations ) ) {
				return;
			}
			$variations_json = wp_json_encode( $available_variations );
			$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
		} else {
			$variations_attr = false;
		}
		?>
        <div class="bew-ob-cart-form bew-cart-form-swatches bew-cart-form-variable" data-product_id="<?php echo esc_attr( $product_id ); ?>"
             data-product_name="<?php echo esc_attr( $product_name ); ?>"
             data-variation_count="<?php echo esc_attr( $variation_count ); ?>"
             data-product_variations="<?php echo esc_attr( $variations_attr ); ?>">
            <div class="bew-swatches-wrap-wrap">
				<?php
				foreach ( $attributes as $attribute_name => $options ) {
					$selected = $selected_attributes[ $attribute_name ] ?? $product->get_variation_default_attribute( $attribute_name );
					echo sprintf( '<div class="bew-swatches-wrap"><div class="bew-swatches-value value" data-selected="%s">' ,esc_attr($selected));
					wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcuf_ob_dropdown_variation_attribute_options', array(
						'options'                 => $options,
						'attribute'               => $attribute_name,
						'product'                 => $product,
						'selected'                => $selected,
						'class'                   => 'bew-attribute-options',
						'viwpvs_swatches_disable' => 1,
					), $attribute_name, $product ) );
					echo sprintf( '</div></div>' );
				}
				?>
            </div>
            <div class="single_variation_wrap">
                <div class="woocommerce-variation single_variation"></div>
                <div class="woocommerce-variation-add-to-cart variations_button">
                    <input type="hidden" name="quantity" value="<?php echo esc_attr( $product_qty ); ?>"/>
                    <input type="hidden" name="add-to-cart" class="bew-add-to-cart" value=""/>
                    <input type="hidden" name="product_id" class="bew-product_id" value=""/>
                    <input type="hidden" name="variation_id" class="variation_id" value="0"/>
                    <input type="hidden" name="bew_ob_product_id" class="bew_ob_product_id" value="1"/>
                    <input type="hidden" name="bew_ob_info[rule_id]" class="bew_ob_rule_id" value="<?php echo esc_attr( $rule_id ); ?>"/>
                </div>
            </div>
        </div>
		<?php
	}

	public function bew_ob_variation_add_to_cart( $product, $product_qty, $rule_id) {
		$product_id    = $product->get_id();
		$product_name  = $product->get_name();
		$pd_parent_ids = $product->get_parent_id();
		$attributes    = $product->get_attributes();
		if ( empty( $attributes ) ) {
			return;
		}
		$count_value = 0;
		foreach ( $attributes as $attribute_name => $options ) {
			if ( $options ) {
				$count_value ++;
			}
		}
		$div_class = array( 'bew-swatches-wrap-wrap' );
		if ( $count_value < count( $attributes ) ) {
			$product_parent = wc_get_product( $pd_parent_ids );
			$parent_attr    = $product_parent->get_variation_attributes();
		} else {
			$div_class[] = 'bew-disable';
		}
		$div_class = implode( ' ', $div_class );
		?>
        <div class="bew-ob-cart-form bew-cart-form-swatches" data-product_id="<?php echo esc_attr( $product_id ); ?>"
             data-product_name="<?php echo esc_attr( $product_name ); ?>">
            <div class="<?php echo esc_attr( $div_class ) ?>">
				<?php
				foreach ( $attributes as $attribute_name => $options ) {
					if ( $options ) {
						$name = 'attribute_' . sanitize_title( $attribute_name );
						?>
                        <div class="bew-swatches-wrap bew-disable">
                            <input type="hidden" id="<?php echo esc_attr( sanitize_title( $attribute_name ) ); ?>" class="bew-attribute-options"
                                   name="<?php echo esc_attr( $name ) ?>" data-attribute_name="<?php echo esc_attr( $name ); ?>"
                                   value="<?php echo esc_attr( $options ); ?>">
                        </div>
						<?php
					} else {
						$attribute   = wc_attribute_label( $attribute_name, $product_parent ?? $product );
						$options     = $parent_attr[ $attribute_name ] ?? $parent_attr[ $attribute ] ?? $options;
						$attribute_t = isset( $parent_attr[ $attribute_name ] ) ? $attribute_name : $attribute;
						echo sprintf( '<div class="bew-swatches-wrap"><div class="bew-swatches-value value">' );
						wc_dropdown_variation_attribute_options( apply_filters( 'vi_wcuf_ob_dropdown_variation_attribute_options', array(
							'options'                 => $options,
							'attribute'               => $attribute_t,
							'product'                 => $product_parent ?? '',
							'class'                   => 'bew-attribute-options',
							'viwpvs_swatches_disable' => 1,
						), $attribute_name, $product ) );
						echo sprintf( '</div></div>' );
					}
				}
				?>
            </div>
            <div class="single_variation_wrap">
                <div class="woocommerce-variation single_variation"></div>
                <div class="woocommerce-variation-add-to-cart variations_button">
                    <input type="hidden" name="quantity" value="<?php echo esc_attr( $product_qty ); ?>"/>
                    <input type="hidden" name="add-to-cart" class="bew-add-to-cart" value=""/>
                    <input type="hidden" name="product_id" class="bew-product_id" value=""/>
                    <input type="hidden" name="variation_id" class="variation_id" value="<?php echo esc_attr( $product_id ); ?>"/>
                    <input type="hidden" name="bew_ob_product_id" class="bew_ob_product_id" value="1"/>
                    <input type="hidden" name="bew_ob_info[rule_id]" class="bew_ob_rule_id" value="<?php echo esc_attr( $rule_id ); ?>"/>
                </div>
            </div>
        </div>
		<?php
	}
	public function body_class_checkout( $classes ){
        if ( ( is_checkout() && !is_wc_endpoint_url('order-received') ) ) {
			$classes[] = 'bew-checkout';
			$classes[] = 'bew-woocommerce-builder';			
		} else {
			 if (is_wc_endpoint_url('order-received') ) {
				$classes[] = 'bew-checkout-order-received';
				$classes[] = 'bew-woocommerce-builder';	
			 }
		}			
		
        return $classes;
    }	
	
	public function bew_review_order_before_cart_contents( ){
		add_filter( 'woocommerce_cart_item_product', 'my_cart_item_product_filter', 20 );
    }

	public function bew_review_order_after_cart_contents( ){
		add_filter( 'woocommerce_cart_item_product', 'my_cart_item_product_filter', 20 );
    }

	private static function is_blocksy_active() {

		// Basic detection
		if (function_exists('blocksy_render_view_e')) {
			return true;
		}

		// Fallback check
		$theme = wp_get_theme();
		return strpos(strtolower($theme->get('Name')), 'blocksy') !== false;
	}

	public function myplugin_disable_blocksy_checkout_override() {
		if (! function_exists('is_checkout') || ! is_checkout() || is_order_received_page()) {
			return;
		}
		
		// Optional: ensure Blocksy is active (extra safety)
		if (! self::is_blocksy_active()) {
			return;
		}
		
		remove_all_actions('woocommerce_review_order_before_cart_contents');
		remove_all_actions('woocommerce_review_order_after_cart_contents');
	}
			
	private function add_actions() {
			
		//Create Order Bump Shortcode
		add_action( 'init', array( $this, 'bew_ob_shortcode_init' ) );
		
		// Add Custom fields on Admin Order
		if ( is_admin()) {	
			add_action( 'woocommerce_admin_order_data_after_shipping_address', [ $this, 'bew_checkout_field_display_admin_order_meta'], 10, 1 );
			add_action( 'woocommerce_admin_order_data_after_billing_address', [ $this, 'bew_order_details_after_billing_address' ], 20, 1);
			add_action( 'woocommerce_admin_order_data_after_shipping_address', [ $this, 'bew_order_details_after_shipping_address' ], 20, 1);
			
			//add_action( 'woocommerce_admin_order_data_after_order_details', [ $this, 'bew_order_details_after_shipping_address' ], 20, 1);
			
			//if( Elementor\Plugin::instance()->editor->is_edit_mode() ) {
				add_action( 'woocommerce_order_details_after_order_table', [ $this, 'bew_order_details_after_order_table' ], 20, 1);
			//}
		}
					
			//add_action( 'woocommerce_review_order_before_cart_contents', [ $this, 'bew_review_order_before_cart_contents'], 10);			
			//add_action( 'woocommerce_review_order_after_cart_contents', [ $this, 'bew_review_order_after_cart_contents'], 10);
		
		if( is_bew_woo_checkout()) { 
							
			add_filter( 'woocommerce_email_order_meta_fields', [ $this, 'bew_show_custom_fields_in_email' ], 10, 3 );
			add_action( 'woocommerce_order_details_after_order_table', [ $this, 'bew_order_details_after_order_table' ], 20, 1);
		
			add_action( 'woocommerce_checkout_create_order', [ $this, 'bew_save_additional_fields' ], 10, 2);
			
			// Checkout validation
			add_action( 'wp_ajax_bew_validate_checkout', array( $this, 'bew_validate_checkout_callback' ) );
			add_action( 'wp_ajax_nopriv_bew_validate_checkout', array( $this, 'bew_validate_checkout_callback' ) );
					
			//echo var_dump("hola");
			add_filter( 'is_bewopc_checkout', function( $is_opc ) {
				return false;
			} );
			
			add_filter( 'is_bewwao_checkout', function( $is_wao ) {
				return false;
			} );
			
			if ( !is_admin()) {			
			//add_filter( 'bew_woocommerce_form_field', [ $this, 'my_woocommerce_form_field' ] );
			add_filter( 'woocommerce_checkout_fields', [ $this, 'bew_regenerate_fields' ]);
			add_filter( 'woocommerce_checkout_fields', [ $this, 'bew_address_fields' ]);
			}
			
			//add_filter( 'woocommerce_default_address_fields' , [ $this, 'override_default_address_checkout_fields' ], 20, 1 );
			
			// hook into the fragments in AJAX and add our new table to the group
			add_filter('woocommerce_update_order_review_fragments', [ $this, 'bew_order_fragments_split_shipping' ], 10, 1);
			
			// hook into the fragments in AJAX and add our new review order
			add_filter('woocommerce_update_order_review_fragments', [ $this, 'bew_order_fragments_review_order' ], 10, 1);

			// hook into the fragments in AJAX and add our new order bump
			add_filter('woocommerce_update_order_review_fragments', [ $this, 'bew_order_fragments_order_bump' ], 10, 1);

			//Wp hook for templates for each page
			//add_action( 'template_redirect', [ $this, 'bew_redirect_wc' ] );	
			
			add_action( 'bew_order_received', array( $this, 'bew_redirect_wc'), 21 );
			
			//Use customer shipping address 
			add_action( 'bew_review_order_shipping', [ $this, 'bew_review_order_shipping_address' ] );		
		
			add_action( 'is_bew_woo_checkout', array( $this, 'is_bewwco'), 10, 2 );
			
			//Disabled add to cart message only on bew checkout
			add_filter( 'wc_add_to_cart_message_html', '__return_false' );
						
			add_filter( 'body_class', [ $this, 'body_class_checkout'] );	
			
			
			// Fix blocksy review order changes
			add_action('wp', [ $this, 'myplugin_disable_blocksy_checkout_override'], 100);

		}
	
	}

}
BewWco::instance();