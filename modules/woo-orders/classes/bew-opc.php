<?php
namespace BriefcasewpExtras;

function is_bewopc_checkout( $post_id = null ) {			

		// If no post_id specified try getting the post_id
		if ( empty( $post_id ) ) {	
				global $post;
				
				if (isset($_SERVER['HTTP_X_REQUESTED_WITH'])){					
						// Try to get the post ID from the URL in case this function is called before init
						$schema = is_ssl() ? 'https://' : 'http://';
						$url = explode('?', $_SERVER["HTTP_REFERER"] . $_SERVER["REQUEST_URI"] );
						//echo "holaB" . var_dump($url);
						$post_id = url_to_postid( $url[0] );					
				
						if($post_id != 0){
							update_option( '_bew_checkout_opc_id', $post_id );
						}					
					
				} else{
					
					$current_page = get_page_by_path($_SERVER['REQUEST_URI']);
            
					if(isset($current_page->ID)) {
						$post_id = $current_page->ID;
					}
					//echo "holaA" . $post_id;
										
				}

				
		}
		//echo "final" . $post_id;
		//$post_id = 2089;
		
		// If still no post_id return straight away
		if ( empty( $post_id )) {

			$is_opc = false;

		} else {

			if ( 0 == Bew0pc::$shortcode_page_id ) {
				$post_to_check = ! empty( $post ) ? $post : get_post( $post_id );
				Bew0pc::check_for_bew_opc( $post_to_check );
			}
			// Compare IDs
			if ( $post_id == Bew0pc::$shortcode_page_id || ( 'yes' == get_post_meta( $post_id, '_bewopc', true ) ) ) {
				$is_opc = true;
			} else {
				$is_opc = false;
			}

		}
		//echo "hola" . $is_opc;	
		return apply_filters( 'is_bewopc_checkout', $is_opc );
}

class Bew0pc{
	private static $_instance = null;
	
	static $shortcode_page_id = 0;
	static $add_scripts = false;
	static $needs_payment_changed = false;
	static $guest_checkout_option_changed = false;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }
	
	public function __construct() {
		
		// Filter is_checkout() on OPC posts/pages
		add_filter( 'woocommerce_is_checkout', array(  $this, 'bew_is_checkout_filter' ) );

		// Because there is no reliable way to filter is_checkout(), we need to do a page ID hack
		add_filter( 'woocommerce_get_checkout_page_id', array(  $this, 'bew_is_checkout_hack' ) );
		
		// Checks if a queried page contains the one page checkout shortcode, needs to happen after the "template_redirect"
		add_action( 'the_posts', array( $this,  'bew_ensure_shortcode_page_id_is_set' ), 10, 2 );
		
		// Allow empty cart when we're doing a request from a OPC page.
		add_action( 'wp_ajax_woocommerce_update_order_review', array( $this, 'bew_allow_expired_session' ), 9 );
		add_action( 'wp_ajax_nopriv_woocommerce_update_order_review', array( $this, 'bew_allow_expired_session' ), 9 );
		add_action( 'wc_ajax_update_order_review', array( $this, 'bew_allow_expired_session' ), 9 );
				
		// Display order review template even when cart is empty in WC 2.3+
		add_action( 'woocommerce_update_order_review_fragments', array( $this, 'bew_update_order_review_fragments' ), 9 );
		
		// Modify OPC empty cart error
		add_filter( 'woocommerce_add_error', array( $this, 'bew_improve_empty_cart_error' ) );
		
		// Ensure we have a session when loading OPC pages
		add_action( 'template_redirect', array( $this, 'bew_set_session' ), 1 );
		
		// Hook in just before (and after) 'woocommerce_checkout_payment' to adjust needs_payment() to ensure the payment methods are displayed on page load
		add_action( 'woocommerce_checkout_order_review', array( $this, 'bew_toggle_cart_needs_payment'), 19 );
		add_action( 'woocommerce_checkout_order_review', array( $this, 'bew_toggle_cart_needs_payment'), 21 );
		
		if( get_option('wo_fields') == 1 ) { 
			// Filter remove fields
			add_filter( 'woocommerce_checkout_fields', array( $this, 'bewwoo_remove_fields' ) , 99, 1  );
			// Filter requried country
			add_filter( 'woocommerce_billing_fields', array( $this, 'bew_optional_billing_fields') , 10, 1 );
			// Filter re-order email
			add_filter( 'woocommerce_checkout_fields', array( $this, 'bewwoo_reorder_fields' ) , 99, 1   );	
				
		}
		
	}
	
	function bewwoo_remove_fields( $woo_checkout_fields_array ) {
		 
		// Wanted me to leave these fields in checkout
		// unset( $woo_checkout_fields_array['billing']['billing_first_name'] );
		// unset( $woo_checkout_fields_array['billing']['billing_last_name'] );
		// unset( $woo_checkout_fields_array['billing']['billing_phone'] );
		// unset( $woo_checkout_fields_array['billing']['billing_email'] );
		// unset( $woo_checkout_fields_array['order']['order_comments'] ); // remove order notes
		 
		// and to remove the billing fields below
		if ( is_bewopc_checkout() ) {
			unset( $woo_checkout_fields_array['billing']['billing_company'] ); // remove company field
			unset( $woo_checkout_fields_array['billing']['billing_address_1'] );
			unset( $woo_checkout_fields_array['billing']['billing_address_2'] );
			unset( $woo_checkout_fields_array['billing']['billing_city'] );
			unset( $woo_checkout_fields_array['billing']['billing_state'] ); // remove state field
			unset( $woo_checkout_fields_array['billing']['billing_postcode'] ); // remove zip code field
		}
		return $woo_checkout_fields_array;
	}
		
	function bew_optional_billing_fields( $address_fields ) {
		if ( is_bewopc_checkout() ) {
			$address_fields['billing_address_1']['required'] = false;
			$address_fields['billing_address_2']['required'] = false;
			$address_fields['billing_country']['required'] = false;
		}
		return $address_fields;
	}	
 
	function bewwoo_reorder_fields( $checkout_fields ) {
		if ( is_bewopc_checkout() ) {
			$checkout_fields['billing']['billing_email']['priority'] = 4;
		}
		return $checkout_fields;
	}
		
	public static function bew_is_checkout_filter( $return = false ) {

		if ( is_bewopc_checkout() ) {
			$return = true;
		}

		return $return;
	}
		
	public static function bew_ensure_shortcode_page_id_is_set( $posts, $query ) {

		// Return straight away if there are no posts or if its a secondary query
		if ( empty( $posts ) || ! $query->is_main_query() ) {
			return $posts;
		}

		if ( 0 == self::$shortcode_page_id ) {
			foreach ( $posts as $post ) {
				if ( ( false !== stripos( $post->post_content, 'bew-opc' ) ) || ( 'yes' == get_post_meta( $post->ID, '_bewopc', true ) ) ) {
					self::$add_scripts = true;
					self::$shortcode_page_id = $post->ID;
					break;
				}
			}
		}

		return $posts;
	}
	
	public static function bew_is_checkout_hack( $page_id ) {
		global $wp;
					
		if ( 0 != self::$shortcode_page_id ) {
			
			$backtrace = debug_backtrace( false ); // Warned you it was a hack

			$functions_to_ignore = apply_filters( 'bewopc_is_checkout_override_function_names', array( 'wc_template_redirect', 'get_checkout_url', 'get_checkout_payment_url', 'get_checkout_order_received_url', 'get_cancel_order_url', 'get_cancel_order_url_raw', 'is_checkout' ) );

			// An array of backtrace indexes to look for functions to ignore
			$backtrace_indexes = apply_filters( 'bewopc_is_checkout_override_function_backtrace_indexes', array( 3, 4, 5, 6 ) );

			// making sure we have arrays
			if ( is_array( $functions_to_ignore ) && is_array( $backtrace_indexes ) ) {
				// An array of function names in the backtrace at the ignored indexes
				$backtrace_functions = array_intersect_key( wp_list_pluck( $backtrace, 'function' ), array_flip( $backtrace_indexes ) );

				// If we don't find any functions which we ignore
				if ( 0 == count( array_intersect( $backtrace_functions, $functions_to_ignore ) ) ) {
					$page_id = self::$shortcode_page_id;
				}
			}
		}
		
		return $page_id;

	}
		
	public static function check_for_bew_opc( $post_to_check ) {

		if ( false !== stripos( $post_to_check->post_content, 'bew-opc' ) ) {
			self::$add_scripts = true;
			self::$shortcode_page_id = $post_to_check->ID;
			$contains_shortcode = true;
		} else {
			$contains_shortcode = false;
		}

		return $contains_shortcode;
	}
	
	public static function bew_allow_expired_session() {
		if ( WC()->cart->is_empty() ) {
			add_filter( 'woocommerce_checkout_update_order_review_expired', '__return_false' );
		}
	}
	
	public static function bew_update_order_review_fragments( $fragments ) {

		// If the cart is empty
		if ( 0 == sizeof( WC()->cart->get_cart() ) ) {

			// Remove the "session has expired" notice
			if ( isset( $fragments['form.woocommerce-checkout'] ) ) {
				unset( $fragments['form.woocommerce-checkout'] );
			}

			$checkout = WC()->checkout();

			// To have control over when the create account fields are displayed - we'll display them all the time and hide/show with js
			if ( ! is_user_logged_in() ) {
				if ( false === $checkout->enable_guest_checkout ) {
					$checkout->enable_guest_checkout = true;
					self::$guest_checkout_option_changed = true;
				}
			}

			// Add non-blocked order review fragment
			ob_start();
			woocommerce_order_review();
			$fragments['.woocommerce-checkout-review-order-table'] = ob_get_clean();

			// Reset guest checkout option
			if ( true === self::$guest_checkout_option_changed ) {
				$checkout->enable_guest_checkout = false;
				self::$guest_checkout_option_changed = false;
			}

			// Add non-blocked checkout payment fragement
			ob_start();
			woocommerce_checkout_payment();
			$fragments['.woocommerce-checkout-payment'] = ob_get_clean();
		}

		return $fragments;
	}
	
	public static function bew_set_session() {
		if ( is_bewopc_checkout() && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}
	
	public static function bew_improve_empty_cart_error( $error ) {

		if ( defined( 'WOOCOMMERCE_CHECKOUT' ) && $error == sprintf( __( 'Sorry, your session has expired. <a href="%s">Return to homepage &rarr;</a>', 'wcopc' ), home_url() ) ) {
			$error = __( 'You must select a product.', 'wcopc' );
		}

		return $error;
	}
		
	public static function bew_toggle_cart_needs_payment() {

		if ( false === WC()->cart->needs_payment() && false === self::$needs_payment_changed ) {
			add_filter( 'woocommerce_cart_needs_payment', '__return_true' );
			self::$needs_payment_changed = true;
		} elseif ( true === self::$needs_payment_changed ) {
			remove_filter( 'woocommerce_cart_needs_payment', '__return_true' );
			self::$needs_payment_changed = false;
		}
	}
	
}
Bew0pc::instance();