<?php
/**
 * Review order table
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/review-order.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>
<div class="bew-checkout-review-shipping-table udpate">

	<?php 
	if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) { ?> 
		<table class="shop_table">
			<?php 
			do_action( 'woocommerce_review_order_before_shipping' );		
			wc_cart_totals_shipping_html();
			do_action( 'woocommerce_review_order_after_shipping' );
			?>
		</table>
		<?php 
	} else { ?>
		<div class="bew-shipping-options-enter-address">
			<p class="enter-address-message"> <?php esc_html_e( 'Shipping options will be displayed here after entering your full shipping address.', 'bew-extras' ); ?></p>
		</div>
	<?php 
	} ?>

</div>
