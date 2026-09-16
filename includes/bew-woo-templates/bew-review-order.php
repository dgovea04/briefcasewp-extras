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

$order_review_coupon_label = !empty(get_option( '_order_review_coupon_label' )) ?  get_option( '_order_review_coupon_label' ) : '';
$order_review_coupon_title_text = !empty(get_option( '_order_review_coupon_title_text' )) ?  get_option( '_order_review_coupon_title_text' ) : 'Coupon code';
$order_review_coupon_button_text = !empty(get_option( '_order_review_coupon_button_text' ) ) ? get_option( '_order_review_coupon_button_text' ) : 'Apply'  ;
$order_review_coupon_label_text = !empty(get_option( '_order_review_coupon_label_text' ) ) ?  get_option( '_order_review_coupon_label_text' ) : 'Enter Code' ;
$order_review_shipping_label_text = !empty(get_option( '_order_review_shipping_label_text' ) ) ?  get_option( '_order_review_shipping_label_text' ) : 'Shipping' ;
$order_review_shipping_options = !empty(get_option( '_order_review_shipping_options' ) ) ?  get_option( '_order_review_shipping_options' ) : '' ;

$freeproduct = '';
$cart_total = (float) WC()->cart->get_total( 'edit' ); // [web:8]

if ( $cart_total == 0 ) {
   $freeproduct = 'free-product';
}

?>	
<div class="shop_table bew-woocommerce-checkout-review-order-table">
	
	<?php
	do_action( 'woocommerce_review_order_before_cart_contents' );
	?>
	<div class="bew-components-order-summary bew-components-panel">
		
		<div class="bew-components-panel__content">
			<div class="bew-components-order-summary__content">	
			
				<?php
				foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
					$_product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );

					if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_checkout_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
						
						$quantity  = apply_filters( 'woocommerce_checkout_cart_item_quantity', $cart_item['quantity']	, $cart_item, $cart_item_key );
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );				
						$name = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
						$permalink  = apply_filters( 'filter_woocommerce_cart_item_permalink', $_product->get_permalink(), $cart_item, $cart_item_key );
						$price = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, intval($cart_item['quantity']) ), $cart_item, $cart_item_key ); 
						
						
						$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );
						
						$free = __( 'Free!', 'woocommerce' );
						
						if ( WC()->cart->get_cart_shipping_total() == $free ) {
							$totals =  wc_price(0);
						} else {
							$totals = WC()->cart->get_cart_shipping_total();
						}
												
						?>
						<div class="bew-components-order-summary-item">
								
							<div class="bew-components-order-summary-item__image">
								<div class="bew-components-order-summary-item__quantity">
									<span aria-hidden="true"><?php echo ($quantity); ?></span>
									<span class="screen-reader-text"><?php echo ($quantity); ?> items</span>
								</div>
								<?php echo ( $thumbnail ); ?>												
							</div>
										
							<div class="bew-components-order-summary-item__description">
								<div class="bew-components-order-summary-item__header">
									<a class="bew-components-product-name" href="<?php echo ( $permalink ); ?>" tabindex="0"><?php echo ($name); ?></a>
									<span class="price bew-components-product-price">
										<span class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-product-price__value bew-components-order-summary-item__total-price"><?php echo ($price); ?></span>
									</span>
											
									<?php											
									echo '<div class="bew-product-remove product-remove">';
										echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
											'<a href="%s" class="bew-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span class="ti-trash"></span><span class="remove-link">Remove Item</span></a>',
											esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
											esc_html__( 'Remove this item', 'woocommerce' ),
											esc_attr( $product_id ),
											esc_attr( $_product->get_sku() )
										), $cart_item_key );
									echo '</div>';											
									?>	
											
								</div>
							</div>
										
						</div>	
						<?php
					}
				}
				?>
				
			</div>		
		</div>
	</div>
		
	<?php
	do_action( 'woocommerce_review_order_after_cart_contents' );
	?>
					
	<div class="bew-components-totals-item subtotal bew-components-order-subtotal">
		<span class="bew-components-totals-item__label"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span>
		<span class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value"><?php wc_cart_totals_subtotal_html(); ?></span>
		<div class="bew-components-totals-item__description"></div>
	</div>
	
	
	<?php
        $discount_excl_tax_total = WC()->cart->get_cart_discount_total();
        $discount_tax_total = WC()->cart->get_cart_discount_tax_total();
        $discount_total = $discount_excl_tax_total + $discount_tax_total;
		
	if ($discount_total){ ?>		
		<div class="bew-components-totals-item bew-components-totals-discount">
			<span class="bew-components-totals-item__label"><?php esc_html_e( 'Discount', 'bew-extras' );?></span>
			<span class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value">
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
	
	
	
	<?php 
	if ( WC()->cart->needs_shipping() && WC()->cart->show_shipping() ) : ?>	
	<div class="bew-components-totals-shipping">
		<div class="bew-components-totals-item">
			<span class="bew-components-totals-item__label"><?php esc_html_e( $order_review_shipping_label_text, 'woocommerce' ); ?></span>
			<span class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value"><?php echo $totals; ?></span>
			<div class="bew-components-totals-item__description">
				<span class="bew-components-shipping-address"><?php echo esc_attr( do_action( 'bew_review_order_shipping' ), 'bew-extras' );  ?></span> 
			</div>
			
			<?php
			if($order_review_shipping_options == 'yes'){
				
				if ( Elementor\Plugin::instance()->editor->is_edit_mode() ) { 
					// Calc totals.
					WC()->cart->calculate_totals();					
				}
				?>
				<table class="shop_table bew-checkout-review-shipping-table">

				<?php do_action( 'woocommerce_review_order_before_shipping' ); ?>

				<?php wc_cart_totals_shipping_html(); ?>

				<?php do_action( 'woocommerce_review_order_after_shipping' ); ?>

				</table>  
			<?php
			}
			 ?>
			
		</div>
	</div>
	<?php endif; ?>		
	
	<div class="bew-components-totals-coupon bew-components-panel has-border">
		<h2 class="bew-order-review-coupon">
			<div id= "bew-coupon" class="bew-components-panel__button">
				<span aria-hidden="true">	
				<?php if(!empty($order_review_coupon_title_text)){
						 printf( esc_html__( $order_review_coupon_title_text, 'woocommerce' ) );?>
			    </span> 
				<?php }else {
					     printf( esc_html__( 'Coupon code', 'woocommerce' ) . '?');?>
				</span>
				<?php } ?>
				<span class="screen-reader-text"><?php esc_attr_e( 'Enter Coupon code', 'bew-extras' ); ?></span>
			</div>
		</h2>
		<div class="bew-components-panel__content" >
			<div class="bew-components-totals-coupon__content bew-checkout-step-container" style="display:none;">
				<form class="bew-checkout_coupon woocommerce-form-coupon" method="post" >					
					<p class="form-row bew-form-row-first form-row-first label-inside-<?php echo $order_review_coupon_label; ?>">
						<label for="coupon_code_review" class=""><?php esc_html_e( $order_review_coupon_label_text, 'bew-extras' ); ?></label>						
						<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code_review" value="" />				
					</p>
					<p class="form-row bew-form-row-last form-row-last">						
						<button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( $order_review_coupon_button_text , 'woocommerce' ); ?>">
						<span class="btn__content visually-hidden-on-mobile" aria-hidden="true">
						<?php esc_html_e( $order_review_coupon_button_text, 'woocommerce' ); ?>
					    </span>
						<span class="ti-arrow-right"></span>
						</button>						
					</p>
					<div class="clear"></div>
				</form>
			</div>
		</div>
	</div>
	
	
	<?php foreach ( WC()->cart->get_fees() as $fee ) : ?>
		<tr class="fee">
			<th><?php echo esc_html( $fee->name ); ?></th>
			<td><?php wc_cart_totals_fee_html( $fee ); ?></td>
		</tr>
	<?php endforeach; ?>

	<?php if ( wc_tax_enabled() && ! WC()->cart->display_prices_including_tax() ) : ?>
		<?php if ( 'itemized' === get_option( 'woocommerce_tax_total_display' ) ) : ?>
			<?php foreach ( WC()->cart->get_tax_totals() as $code => $tax ) : // phpcs:ignore WordPress.WP.GlobalVariablesOverride.OverrideProhibited ?>
				<tr class="tax-rate tax-rate-<?php echo esc_attr( sanitize_title( $code ) ); ?>">
					<th><?php echo esc_html( $tax->label ); ?></th>
					<td><?php echo wp_kses_post( $tax->formatted_amount ); ?></td>
				</tr>
			<?php endforeach; ?>
		<?php else : ?>
			<tr class="tax-total">
				<th><?php echo esc_html( WC()->countries->tax_or_vat() ); ?></th>
				<td><?php wc_cart_totals_taxes_total_html(); ?></td>
			</tr>
		<?php endif; ?>
	<?php endif; ?>

	<?php do_action( 'woocommerce_review_order_before_order_total' ); ?>
		
	<div class="bew-components-totals-item bew-components-totals-footer-item <?php echo  $freeproduct ?>">
		<span class="bew-components-totals-item__label"><?php esc_html_e( 'Total', 'woocommerce' ); ?></span>
		<span class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value"><?php wc_cart_totals_order_total_html(); ?></span>
		<div class="bew-components-totals-item__description"></div>
	</div>

	<?php do_action( 'woocommerce_review_order_after_order_total' ); ?>

</div>
