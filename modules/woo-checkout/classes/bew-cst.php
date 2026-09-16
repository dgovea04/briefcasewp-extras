<?php
namespace BriefcasewpExtras;

function is_bewcst_checkout( $post_id = null ) {
		
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
					update_option( '_bew_checkout_cst_id', $post_id );
				}					
					
			} else{
						
				$current_page = get_page_by_path($_SERVER['REQUEST_URI']);            
				if(isset($current_page->ID)) {
					$post_id = $current_page->ID;
				}					
											
			}				
		}
		//echo "final" . $post_id;
		//$post_id = 818;
		
		// If still no post_id return straight away
		if ( empty( $post_id )) {

			$is_cst = false;
			
		} else {
			// DIsable it hasta que verifique
			// Check if Split Test is active/ 
			//$post_split = get_post( $post_id );	
			//Split test check
			$bew_split_test = $post_split->bew_split_test ?? null;
			//echo var_dump( $post->bew_split_test );
			//if($bew_split_test == 'yes'){	
				if ( 0 == BewCst::$shortcode_page_id ) {
					//echo "dc";
					$post_to_check = ! empty( $post ) ? $post : get_post( $post_id );
					//echo var_dump($post_to_check);
					BewCst::check_for_bew_cst( $post_to_check );
					//echo var_dump(BewCst::check_for_bew_cst( $post_to_check ));
				}
				// Compare IDs
				//echo "hola" . BewCst::$shortcode_page_id;
				if ( $post_id == BewCst::$shortcode_page_id || ( 'yes' == get_post_meta( $post_id, '_bewcst', true ) ) ) {
					$is_cst = true;
				} else {
					$is_cst = false;
				}
			//} else {
			//	$is_cst = false;
			//}
		}
		//$is_cst = true;
		//echo "hola" . $post_id;	
		//echo "hola" . $is_cst;	
		
		//$is_cst = false;
		
		return apply_filters( 'is_bewcst_checkout', $is_cst );
}

class BewCst{
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
		if ( is_bewcst_checkout() ) {
			// Filter is_checkout() on CST posts/pages
			add_filter( 'woocommerce_is_checkout', array(  $this, 'bew_is_checkout_filter' ) );

			// Because there is no reliable way to filter is_checkout(), we need to do a page ID hack
			add_filter( 'woocommerce_get_checkout_page_id', array(  $this, 'bew_is_checkout_hack' ) );
			
			// Checks if a queried page contains the one page checkout shortcode, needs to happen after the "template_redirect"
			add_action( 'the_posts', array( $this,  'bew_ensure_shortcode_page_id_is_set' ), 10, 2 );
			
			// Allow empty cart when we're doing a request from a OPC page.
			add_action( 'wp_ajax_woocommerce_update_order_review', array( $this, 'bew_allow_expired_session' ), 9 );
			add_action( 'wp_ajax_nopriv_woocommerce_update_order_review', array( $this, 'bew_allow_expired_session' ), 9 );
			add_action( 'wc_ajax_update_order_review', array( $this, 'bew_allow_expired_session' ), 9 );
			
			// Modify OPC empty cart error
			add_filter( 'woocommerce_add_error', array( $this, 'bew_improve_empty_cart_error' ) );
			
			// Ensure we have a session when loading OPC pages
			add_action( 'template_redirect', array( $this, 'bew_set_session' ), 1 );
			
			// Ensure redirect to empty cart on OPC pages
			add_action( 'template_redirect', array( $this, 'bew_set_redirect' ), 1 );
		}

	}
		
	public static function bew_is_checkout_filter( $return = false ) {

		if ( is_bewcst_checkout() ) {
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
				if ( ( false !== stripos( $post->post_content, 'bew-cst' ) ) || ( 'yes' == get_post_meta( $post->ID, '_bewcst', true ) ) ) {
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

			$functions_to_ignore = apply_filters( 'bewcst_is_checkout_override_function_names', array( 'wc_template_redirect', 'get_checkout_url', 'get_checkout_payment_url', 'get_checkout_order_received_url', 'get_cancel_order_url', 'get_cancel_order_url_raw', 'is_checkout' ) );

			// An array of backtrace indexes to look for functions to ignore
			$backtrace_indexes = apply_filters( 'bewcst_is_checkout_override_function_backtrace_indexes', array( 3, 4, 5, 6 ) );

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
		
	public static function check_for_bew_cst( $post_to_check ) {
		//echo var_dump(stripos( $post_to_check->post_content, 'bew-cst' ));
		//echo var_dump($post_to_check->post_content);
		if ( false !== stripos( $post_to_check->post_content, 'bew-cst' ) ) {
			//echo "gg";
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
	
	public static function bew_set_session() {
		if ( is_bewcst_checkout() && ! WC()->session->has_session() ) {
			WC()->session->set_customer_session_cookie( true );
		}
	}

	public static function bew_set_redirect() {
		
		if ( is_bewcst_checkout() ) {
			
			global $post;
			$post_id = $post->ID;
			
			// Check if Split Test is active
			if($post->bew_split_test == 'yes'){	
				// When on the checkout with an empty cart, redirect to cart page.
				if ( is_page( $post_id ) && $post_id !== wc_get_page_id( 'cart' ) && WC()->cart->is_empty() && empty( $wp->query_vars['order-pay'] ) && ! isset( $wp->query_vars['order-received'] ) && ! is_customize_preview() &&
				apply_filters( 'woocommerce_checkout_redirect_empty_cart', true ) ) {
					
					wp_safe_redirect( wc_get_cart_url() );
					exit;

				}
			}
			
		}
	}
	
	public static function bew_improve_empty_cart_error( $error ) {

		if ( defined( 'WOOCOMMERCE_CHECKOUT' ) && $error == sprintf( __( 'Sorry, your session has expired. <a href="%s">Return to homepage &rarr;</a>', 'wcopc' ), home_url() ) ) {
			$error = __( 'You must select a product.', 'wcopc' );
		}

		return $error;
	}
	
}
BewCst::instance();