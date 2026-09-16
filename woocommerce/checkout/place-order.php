<?php
/**
 * Output a place order button alone
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/place-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates
 * @version     3.5.0
 */

defined( 'ABSPATH' ) || exit;

?>
<div id="bew-place-order" class="bew-woocommerce-checkout-place-order">
	<div class="bew-checkout__actions place-order">	
		<?php		
		$cart_link = WC()->cart->get_cart_url();
						
		if($return_cart_show == 'yes'){
			?>
			<a href="<?php echo $cart_link ?>" class="bew-components-checkout-return-to-cart-button">
			<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="currentColor" role="img" aria-hidden="true" focusable="false"><path d="M20 11H7.8l5.6-5.6L12 4l-8 8 8 8 1.4-1.4L7.8 13H20v-2z"></path></svg>
			<?php echo esc_html($return_cart_text)?>
			</a>
			<?php
		}
		
		do_action( 'woocommerce_review_order_before_submit' );
		
		if($place_order_button_show == 'yes'){ ?>
			<?php echo apply_filters( 'woocommerce_order_button_html', '<button type="submit" class="button alt components-button bew-components-button bew-components-checkout-place-order-button" name="woocommerce_checkout_place_order" id="place_order" value="' . esc_attr( $place_order_button_text ) . '" data-value="' . esc_attr( $place_order_button_text ) . '">' . esc_html( $place_order_button_text ) . '</button>' ); // @codingStandardsIgnoreLine 
		} ?>
		
		<?php do_action( 'woocommerce_review_order_after_submit' ); ?>
		<?php wp_nonce_field( 'woocommerce-process_checkout', 'woocommerce-process-checkout-nonce' ); ?>
	</div>
</div>


