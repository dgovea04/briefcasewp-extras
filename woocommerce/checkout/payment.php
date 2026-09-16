<?php
/**
 * Checkout Payment Section
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.3
 */

defined( 'ABSPATH' ) || exit;

if ( ! wp_doing_ajax() ) {
	//do_action( 'woocommerce_review_order_before_payment' );
}

$payment_methods_layout =  get_option( '_payment_order_methods_layout');
$payment_methods_radio_layout =  get_option( '_payment_order_methods_radio_layout');
$save_output_order = "";
$save_output = "";

if ( is_admin() && Elementor\Plugin::instance()->editor->is_edit_mode()) {	
	global $post;
	$post_id = $post->ID;
	$_bew_checkout_id = get_option( '_bew_checkout_id' );	
		
	if ( ($post_id == $_bew_checkout_id) && ($payment_methods_layout == 'tabs' ) ) {	
		$save_output = "yes";
		$save_output_order = "yes";

	}else {
		$save_output = "";
		if ( ($payment_methods_layout == 'checkbox' ) || ($payment_methods_layout == 'radio' ) ) {	
			$save_output_order = "yes";
		}
	}
} else {
	global $post;
	$post_id = $post->post_title;
	
	//echo $post_id;
	
	ob_start(); // start capturing output.
	do_action('is_bew_woo_checkout');
	$save_output = ob_get_contents(); // the actions output will now be stored in the variable as a string!		
	ob_end_clean();

	if ( "yes" == $save_output ){
			
		if ( $payment_methods_layout == 'tabs'  ) {
			$save_output_order = "yes";
		}elseif ( ($payment_methods_layout == 'checkbox' ) || ($payment_methods_layout == 'radio' ) ){	
			$save_output = "";
			$save_output_order = "yes";
		}
		else {			
			$save_output = "";
			$save_output_order = "";
		}
	}
}

//echo "outorder" . $save_output_order;
//echo "out" . $save_output;

?>
<div id="payment" class="woocommerce-checkout-payment">
	<?php if ( WC()->cart->needs_payment() ) {
		
		if ( "yes" == $save_output ){
			
			// The template name. 
			$template_name = 'checkout/bew-payment-method.php'; 

			// default args
			$args =  array( 'available_gateways' => $available_gateways );
			
			// default template
			$template_path = ''; // use default which is usually "woocommerce"
			
			// default path (look in plugin file!)
			$default_path = BEW_EXTRAS_PATH . '/woocommerce/';
							
			//wc_get_template( 'checkout/bew-payment-method.php' ); 
			wc_get_template($template_name, $args, $template_path, $default_path);
			
		}else{
							
			$ag_count = count($available_gateways);			
			?>
			<ul class="wc_payment_methods payment_methods methods ag-count-<?php echo $ag_count ?>">
				<?php
					if ( ! empty( $available_gateways ) ) {
						foreach ( $available_gateways as $gateway ) {
							wc_get_template( 'checkout/payment-method.php', array( 'gateway' => $gateway, 'radio_layout' => $payment_methods_radio_layout, ) );
						}
					} else {
						echo '<li class="woocommerce-notice woocommerce-notice--info woocommerce-info">' . apply_filters( 'woocommerce_no_available_payment_methods_message', WC()->customer->get_billing_country() ? esc_html__( 'Sorry, it seems that there are no available payment methods for your state. Please contact us if you require assistance or wish to make alternate arrangements.', 'woocommerce' ) : esc_html__( 'Please fill in your details above to see available payment methods.', 'woocommerce' ) ) . '</li>'; // @codingStandardsIgnoreLine
					}			
				?>
			</ul><?php
			
		}
		
	}
		
    $payment_order_button =  get_option( '_payment_order_button');	
	$bew_order_button_text  =  get_option( '_payment_order_button_text');
	$policy_position =  get_option( '_payment_order_policy_position');

	//echo $save_output_order;
		
	if( (  "yes" == $save_output_order  ) ){ 
	
		if( "yes" == $payment_order_button ){ 
		?>	
			<div class="form-row place-order">
				<noscript>
					<?php
					/* translators: $1 and $2 opening and closing emphasis tags respectively */
					printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
					?>
					<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
				</noscript>

				<?php do_action( 'woocommerce_review_order_before_submit' );		
				
				if( "privacy-top" == $policy_position  ){ 
				wc_get_template( 'checkout/terms.php' ); 
				}
				?>
						
				<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $bew_order_button_text ) . '" data-value="' . esc_attr( $bew_order_button_text ) . '">' . esc_html( $bew_order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>
				
				<?php do_action( 'woocommerce_review_order_after_submit' );			
				wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' );
				
				if( "privacy-bottom" == $policy_position  ){ 
					wc_get_template( 'checkout/terms.php' ); 
				}
				?>
			</div>
		<?php
		}
		
	} else { ?>
		
		<div class="form-row place-order">
			<noscript>
				<?php
				/* translators: $1 and $2 opening and closing emphasis tags respectively */
				printf( esc_html__( 'Since your browser does not support JavaScript, or it is disabled, please ensure you click the %1$sUpdate Totals%2$s button before placing your order. You may be charged more than the amount stated above if you fail to do so.', 'woocommerce' ), '<em>', '</em>' );
				?>
				<br/><button type="submit" class="button alt" name="woocommerce_checkout_update_totals" value="<?php esc_attr_e( 'Update totals', 'woocommerce' ); ?>"><?php esc_html_e( 'Update totals', 'woocommerce' ); ?></button>
			</noscript>

			<?php wc_get_template( 'checkout/terms.php' ); ?>

			<?php do_action( 'woocommerce_review_order_before_submit' ); ?>

			<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $order_button_text ) . '" data-value="' . esc_attr( $order_button_text ) . '">' . esc_html( $order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine ?>

			<?php do_action( 'woocommerce_review_order_after_submit' ); ?>

			<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
		</div>
	<?php	
	
	} 
	?>
</div>
<?php
if ( ! wp_doing_ajax() ) {
	do_action( 'woocommerce_review_order_after_payment' );
}
