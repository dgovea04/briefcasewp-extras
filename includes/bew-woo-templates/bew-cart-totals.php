<?php
/**
 * Cart totals
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart-totals.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 2.3.6
 */

defined( 'ABSPATH' ) || exit;

use BriefcasewpExtras\Helper;

$cart_total_text = get_option( '_cart_total_text');
$cart_totals_loader = get_option( '_cart_totals_loader');
$loader_active = '';

if ($cart_totals_loader == "yes" ) {
	$loader_active = "bew-cart-loader-active";
}

?>
<div class="cart_totals <?php echo ( WC()->customer->has_calculated_shipping() ) ? 'calculated_shipping' : ''; ?>">

	<?php do_action( 'woocommerce_before_cart_totals' ); ?>

	<h2 class="bew-components-title bew-cart__totals-title"><?php esc_html_e( $cart_total_text, 'woocommerce' ); ?></h2>

	<div class="shop_table shop_table_responsive">

		<div class="cart-subtotal bew-components-totals-item">
			<div class="bew-components-totals-item__label"><span class="<?php echo $loader_active; ?>"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span></div>
			<div class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value <?php echo $loader_active; ?>"><?php wc_cart_totals_subtotal_html(); ?></div>
			<div class="bew-components-totals-item__description <?php echo $loader_active; ?>"></div>
		</div>
		
		<?php
            $discount_excl_tax_total = WC()->cart->get_cart_discount_total();
            $discount_tax_total = WC()->cart->get_cart_discount_tax_total();
            $discount_total = $discount_excl_tax_total + $discount_tax_total;			
		
		if ($discount_total){ ?>		
			<div class="bew-components-totals-item bew-components-totals-discount">
				<span class="bew-components-totals-item__label"><?php esc_html_e( 'Discount', 'bew-extras' );?></span>
				<span class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value <?php echo $loader_active; ?>">
					<?php if( ! empty($discount_total) ): ?>					
						<?php echo wc_price(-$discount_total) ?>				
					<?php endif; ?>
				</span>
				<div class="bew-components-totals-item__description">
					<ul class="bew-components-totals-discount__coupon-list">
						<?php foreach ( WC()->cart->get_coupons() as $code => $coupon ) : ?>
							<li class="bew-components-totals-discount__coupon-list-item is-removable bew-components-chip bew-components-chip--radius-large">
							<span aria-hidden="true" class="bew-components-chip__text"><?php echo esc_attr( sanitize_title( $code ) ); ?></span>
							<div data-title="<?php echo esc_attr( wc_cart_totals_coupon_label( $coupon, false ) ); ?>"><?php wc_cart_totals_coupon_html( $coupon ); ?></div>
							<span class="screen-reader-text">Coupon: <?php echo esc_attr( sanitize_title( $code ) ); ?></span>
							</li>
						<?php endforeach; ?>
					</ul>
				</div>
			</div>			
		<?php } ?>
		
		<?php if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>

			<?php do_action( 'woocommerce_cart_totals_before_shipping' ); ?>
			
			<div class="bew-components-totals-shipping">			
				<?php 
				if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) { 
				// Calc totals.
				WC()->cart->calculate_totals();
				}
				do_action( 'bew_cart_totals_shipping' ); ?>
			</div>
			<?php do_action( 'woocommerce_cart_totals_after_shipping' ); ?>

		<?php elseif ( WC()->cart->needs_shipping() && 'yes' === get_option( 'woocommerce_enable_shipping_calc' ) ) : ?>

			<div class="bew-components-totals-shipping shipping">
				<div><?php esc_html_e( 'Shipping', 'woocommerce' ); ?></div>
				<div data-title="<?php esc_attr_e( 'Shipping', 'woocommerce' ); ?>"><?php woocommerce_shipping_calculator(); ?></div>
			</div>

		<?php endif; ?>

		<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
			<div class="fee">
				<div><?php echo esc_html( $fee->name ); ?></div>
				<div data-title="<?php echo esc_attr( $fee->name ); ?>"><?php wc_cart_totals_fee_html( $fee ); ?></div>
			</div>
		<?php endforeach; ?>

		<?php
		if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) {
			$taxable_address = WC()->customer->get_taxable_address();
			$estimated_text  = '';

			if ( WC()->customer->is_customer_outside_base() && ! WC()->customer->has_calculated_shipping() ) {
				/* translators: %s location. */
				$estimated_text = sprintf( ' <small>' . esc_html__( '(estimated for %s)', 'woocommerce' ) . '</small>', WC()->countries->estimated_for_prefix( $taxable_address[0] ) . WC()->countries->countries[ $taxable_address[0] ] );
			}

			if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) {
				foreach ( WC()->cart->get_tax_totals() as $code => $tax ) { // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited
					?>
					<div class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
						<div><?php echo esc_html( $tax->label ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
						<div data-title="<?php echo esc_attr( $tax->label ); ?>"><?php echo wp_kses_post( $tax->formatted_amount ); ?></div>
					</div>
					<?php
				}
			} else {
				?>
				<div class="tax-total">
					<div><?php echo esc_html( WC()->countries->tax_or_vat() ) . $estimated_text; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></div>
					<div data-title="<?php echo esc_attr( WC()->countries->tax_or_vat() ); ?>"><?php wc_cart_totals_taxes_total_html(); ?></div>
				</div>
				<?php
			}
		}
		?>

		<?php do_action( 'woocommerce_cart_totals_before_order_total' ); ?>
		
		<?php if ( wc_coupons_enabled() ) { ?>

					<div class="bew-components-totals-coupon bew-components-panel has-border">
						<h2 class="bew-order-review-coupon">
							<div id= "bew-coupon" class="bew-components-panel__button">
								<span aria-hidden="true">Coupon Code?</span>
								<span class="screen-reader-text">Introduce Coupon Code</span>
							</div>
						</h2>
						<div class="bew-components-panel__content" >
							<div class="bew-components-totals-coupon__content ">
								<form class="bew-cart_coupon woocommerce-form-coupon" method="post" >					
									<p class="form-row form-row-first">
										<label for="coupon_code_total" class=""><?php esc_html_e( 'Enter Code', 'bew-extras' ); ?></label>
										<input type="text" name="coupon_code" class="input-text" placeholder="" id="coupon_code_total" value="" />
									</p>
									<p class="form-row form-row-last">
										<button type="submit" class="button apply-coupon-button" name="apply_coupon" value="<?php esc_attr_e( 'Apply', 'woocommerce' ); ?>"><?php esc_html_e( 'Apply', 'woocommerce' ); ?></button>
									</p>
									<div class="clear"></div>
								</form>				
							</div>
						</div>
					</div>
					<?php do_action( 'woocommerce_cart_coupon' ); ?>

			<?php } ?>

		<div class="order-total bew-components-totals-item bew-components-totals-footer-item">
			<div class="bew-components-totals-item__label" ><span class="<?php echo $loader_active; ?>"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span></div>
			<div class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value <?php echo $loader_active; ?>"><?php wc_cart_totals_order_total_html(); ?></div>
		</div>

		<?php do_action( 'woocommerce_cart_totals_after_order_total' );
		
		if ($cart_totals_order_notes == "yes") {
			$helper = new Helper();
			
			$fields = WC()->checkout->get_checkout_fields( 'order' );		
			$cart_items = WC()->cart->get_cart();
			
			foreach ($cart_items as $cart_item_key => $cart_item) {
				$aa  = ($cart_item['order_comments'] ?? null );
			}
			
			?>
			<div class="bew-form-additional bew-components-totals-item" id="order-notes">	
				<div class="bew-checkout-step-container bew-components-checkout-step__container">
					<?php

					foreach ( $fields as $key => $field ) {
								
						if (!empty($aa)){
							$value = $aa;
						} else {
							$value = WC()->checkout->get_value( $key );
						}
						$helper->bew_woocommerce_form_field( $key, $field, $value );
					}

					?>
				</div>
			</div>
		<?php } ?>

	</div>

	<div class="wc-proceed-to-checkout bew-cart__submit-container">
		<?php do_action( 'woocommerce_proceed_to_checkout' ); ?>
	</div>
	
	<?php do_action( 'woocommerce_after_cart_totals' ); ?>

</div>
	