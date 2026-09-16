<?php
namespace BriefcasewpExtras;

use Elementor;
use Elementor\Plugin;

function is_bew_woo_checkout( $post_id = null ) {
	
		static $foo_called = false;
		if ($foo_called) return;

		$foo_called = true;

		// If no post_id specified try getting the post_id
		if ( empty( $post_id ) ) {
			global $post;

			if ( is_object( $post ) ) {
				$post_id = $post->ID;
			} else {
				
				// Try to get the post ID from the URL in case this function is called before init
				$schema = is_ssl() ? 'https://' : 'http://';
				$url = explode('?', $schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );
				
				if(! empty($url[1])){
					$url_check = $url[0] . "checkout";
				}else {
					$url_check = $url[0];					
				}
						
				$post_id = url_to_postid( $url_check  );	
												
			}
			
		}
							
		// If still no post_id return straight away
		if ( empty( $post_id )) {
			
			$is_bwco = false;

		} else {

			if ( 0 == BewWco::$shortcode_page_id ) {
				$post_to_check = ! empty( $post ) ? $post : get_post( $post_id );				
				
				BewWco::check_for_bew_woo_checkout( $post_to_check );
				
			}
			
			// Compare IDs
			if ( $post_id == BewWco::$shortcode_page_id || ( 'yes' == get_post_meta( $post_id, '_bewwco', true ) ) ) {
				$is_bwco = true;					
			} else {
				$is_bwco = false;				
			}	
			
		}
											
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
		if ( isset( $wp->query_vars['order-received'] ) ) {

			self::order_received( $wp->query_vars['order-received'] );
		}
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
		$address_fields['address_2']['placeholder'] = '';
		
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
						
		if( empty($_checkout_fields) ) return $fields;
								
		foreach ( $_checkout_fields as $section => $_fields ) {
			
			if( count( $_fields ) > 0 ) {

				foreach ( $_fields as $_field ) {
					
					$bew_fields[] = $_field["{$section}_input_name"] ;
										
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
		
		$wc_fields = $this->bew_wc_fields();
		
		//echo var_dump($_checkout_fields );
		//echo var_dump($fields );			
		//echo var_dump($bew_fields);		
		//echo var_dump($wc_fields);
		
		if ( !Elementor\Plugin::instance()->editor->is_edit_mode() ) {
			
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
		echo '<p><strong>'.__('Phone From Checkout Form').':</strong> ' . get_post_meta( $order->get_id(), '_shipping_phone', true ) . '</p>';
	}
		
	function bew_review_order_shipping_address() {
		
		$state_code = WC()->customer->get_shipping_state();		
		$country_code = WC()->customer->get_shipping_country();
		$state_name   = WC()->countries->get_states($country_code)[$state_code];	
		$country_name   = WC()->countries->countries[ $country_code ];
				
		$shipping_address = $state_name . ", " . $country_name; 
		
		printf( _n( 'Showing the shipping address', 'Shipping to <span>%s</span>', $shipping_address , 'briefcase-elementor-widgets' ), $shipping_address );
		
	}
		
	function is_bewwco($is_bew_woo_checkout) {
		$is_bewwco = "yes";
		echo $is_bewwco;
		return $is_bewwco;
	}
					
	private function add_actions() {
					
		if( is_bew_woo_checkout()) { 
				
			add_filter( 'woocommerce_form_field', [ $this, 'my_woocommerce_form_field' ] );
			add_filter( 'woocommerce_checkout_fields', [ $this, 'bew_regenerate_fields' ]);	
			add_action( 'woocommerce_checkout_create_order', [ $this, 'bew_save_additional_fields' ], 10, 2);	
			add_action( 'woocommerce_admin_order_data_after_shipping_address', [ $this, 'bew_checkout_field_display_admin_order_meta'], 10, 1 );
			
			//add_filter( 'woocommerce_default_address_fields' , [ $this, 'override_default_address_checkout_fields' ], 20, 1 );
			
			// hook into the fragments in AJAX and add our new table to the group
			add_filter('woocommerce_update_order_review_fragments', [ $this, 'bew_order_fragments_split_shipping' ], 10, 1);
			
			// hook into the fragments in AJAX and add our new review order
			add_filter('woocommerce_update_order_review_fragments', [ $this, 'bew_order_fragments_review_order' ], 10, 1);

			//Wp hook for templates for each page
			//add_action( 'template_redirect', [ $this, 'bew_redirect_wc' ] );	
			
			add_action( 'bew_order_received', array( $this, 'bew_redirect_wc'), 21 );
			
			//Use customer shipping address 
			add_action( 'bew_review_order_shipping', [ $this, 'bew_review_order_shipping_address' ] );		
		
			add_action( 'is_bew_woo_checkout', array( $this, 'is_bewwco'), 10, 2 );
				
		}
	
	}

}
BewWco::instance();