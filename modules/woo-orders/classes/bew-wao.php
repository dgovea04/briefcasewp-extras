<?php
namespace BriefcasewpExtras;

function is_bewwao_checkout( $post_id = null ) {

		// If no post_id specified try getting the post_id
		if ( empty( $post_id ) ) {
			global $post;

			if ( is_object( $post ) ) {
				$post_id = $post->ID;
			} else {
				// Try to get the post ID from the URL in case this function is called before init
				$schema = is_ssl() ? 'https://' : 'http://';
				$url = explode('?', $schema . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"] );
				$post_id = url_to_postid( $url[0] );						
								
				// Try to get the post ID from elementor archive product template
				//if ( $post_id == '0') {
				   				
				//	$id =  get_option( '_bew_woo_order_shop_id');					
				//	$template_type = get_post_meta($id, '_elementor_template_type', true);
					
				//	if ($template_type == 'product-archive' ) {						
				//		$post_id = $id;					
				//	}
				// }					
				
			}
		}
						
		// If still no post_id return straight away
		if ( empty( $post_id ) ) {

			$is_wao = false;

		} else {

			if ( 0 == BewWao::$shortcode_page_id ) {
				$post_to_check = ! empty( $post ) ? $post : get_post( $post_id );
				//echo "post_id" . $post_id;
				//echo "post_to_check" . var_dump($post_to_check);
				BewWao::check_for_bew_wao( $post_to_check );
			}

			// Compare IDs
			if ( $post_id == BewWao::$shortcode_page_id || ( 'yes' == get_post_meta( $post_id, '_bewwao', true ) ) ) {
				$is_wao = true;
			} else {
				$is_wao = false;
			}

		}
						
		return apply_filters( 'is_bewwao_checkout', $is_wao );
}

class BewWao{
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
		
		if ( is_bewopc_checkout() ) {
			add_filter( 'is_bew_woo_checkout', function( $is_bwco ) {
				return false;
			} );
		}
				
		// Checks if a queried page contains the whatsapp checkout shortcode, needs to happen after the "template_redirect"
		add_action( 'the_posts', array( $this,  'bew_ensure_wao_shortcode_page_id_is_set' ), 10, 2 );
		
		$this->add_actions();		
	}
	
	public static function bew_ensure_wao_shortcode_page_id_is_set( $posts, $query ) {

		// Return straight away if there are no posts or if its a secondary query
		if ( empty( $posts ) || ! $query->is_main_query() ) {
			return $posts;
		}

		if ( 0 == self::$shortcode_page_id ) {
			foreach ( $posts as $post ) {
				
				
				if ( ( false !== stripos( $post->post_content, 'bew-wao' ) ) || ( 'yes' == get_post_meta( $post->ID, '_bewwao', true ) ) ) {
					self::$add_scripts = true;
					self::$shortcode_page_id = $post->ID;
					break;
				}
			}
		}
		
		return $posts;
	}
		
	public static function check_for_bew_wao( $post_to_check ) {
		
		if ( false !== stripos( $post_to_check->post_content, 'bew-wao' ) ) {
			self::$add_scripts = true;
			self::$shortcode_page_id = $post_to_check->ID;
			$contains_shortcode = true;			
		} else {
			$contains_shortcode = false;			
		}
	
		//echo "check" . $post_to_check->post_content;
		return $contains_shortcode;
	}
		 
	function bewwoo_remove_fields( $woo_checkout_fields_array ) {
		 
		// Wanted me to leave these fields in checkout
		// unset( $woo_checkout_fields_array['billing']['billing_first_name'] );
		// unset( $woo_checkout_fields_array['billing']['billing_last_name'] );
		// unset( $woo_checkout_fields_array['billing']['billing_phone'] );
		// unset( $woo_checkout_fields_array['billing']['billing_email'] );
		// unset( $woo_checkout_fields_array['order']['order_comments'] ); // remove order notes
		 
		// and to remove the billing fields below
		unset( $woo_checkout_fields_array['billing']['billing_company'] ); // remove company field
		unset( $woo_checkout_fields_array['billing']['billing_address_1'] );
		unset( $woo_checkout_fields_array['billing']['billing_address_2'] );
		unset( $woo_checkout_fields_array['billing']['billing_city'] );
		unset( $woo_checkout_fields_array['billing']['billing_state'] ); // remove state field
		unset( $woo_checkout_fields_array['billing']['billing_postcode'] ); // remove zip code field
		 
		return $woo_checkout_fields_array;
	}
		
	function bew_optional_billing_fields( $address_fields ) {
		$address_fields['billing_address_1']['required'] = false;
		$address_fields['billing_address_2']['required'] = false;
		$address_fields['billing_country']['required'] = false;
		return $address_fields;
	}	
 
	function bewwoo_reorder_fields( $checkout_fields ) {
		$checkout_fields['billing']['billing_email']['priority'] = 4;
		return $checkout_fields;
	}
	
	private function add_actions() {
				
		if ( is_bewwao_checkout() ) {
			add_filter( 'is_bew_woo_checkout', function( $is_bwco ) {
				return false;
			} );		
		}
		
				
		if( (get_option('wo_fields') == 1) && (is_bewwao_checkout()) ) { 
			// Filter remove fields
			add_filter( 'woocommerce_checkout_fields', array( $this, 'bewwoo_remove_fields' ) , 99, 1  );
			// Filter requried country
			add_filter( 'woocommerce_billing_fields', array( $this, 'bew_optional_billing_fields') , 10, 1 );
			// Filter re-order email
			add_filter( 'woocommerce_checkout_fields', array( $this, 'bewwoo_reorder_fields' ) , 99, 1   );	
			
		}
	}

}
BewWao::instance();