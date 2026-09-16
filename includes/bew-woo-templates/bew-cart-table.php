<?php
namespace BriefcasewpExtras;

use Elementor\Icons_Manager;  
/**
 * Cart Page
 *
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;	

do_action( 'woocommerce_before_cart' ); 

$cart_count  =  wc()->cart->get_cart_contents_count();
$frontend = Frontend::instance();	
$vertical_count = 0;
$last = '';
$i = 0;
$loader_active = '';

if ($cart_table_loader == "yes" ) {
	$loader_active = "bew-cart-loader-active";
}

?>

<div class="bew-block-components-main bew-cart__main">

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>
	
	<h2 class="bew-components-title">
		<?php 
		if ( ! empty( $cart_title_text_icon['value'] ) ) {
			Icons_Manager::render_icon( $cart_title_text_icon, [ 'aria-hidden' => 'true' ] );	
		} ?>
		<?php printf( _n( '%1$s <span>%2$s item </span>', '%1$s <span>%2$s items </span>', $cart_count , 'bew-extras' ), esc_html_e( $cart_title_text_before , 'bew-extras' ) , $cart_count );
			  printf( ' %1$s', esc_html_e( $cart_title_text_after , 'bew-extras' )) ?>
	</h2>

	<div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents bew-cart-items" cellspacing="0">

		<div class="bew-cart-items-titles">			
				<?php
					foreach ( $cartitem as $itemvalue ) {
												
						if( $itemvalue['table_items'] == 'customadd' ){
							?>
							<div class="product-element product-<?php echo esc_attr( uniqid('bewcustomitem_') ); ?> elementor-repeater-item-<?php echo $itemvalue['_id']; ?>"  colspan="<?php echo $itemvalue['colspan']; ?>"> <?php echo esc_html_e( $itemvalue['table_heading_title'] ?? null , 'woocommerce' ); ?> </div>								
							<?php
						}else{							
							if($itemvalue['table_items'] == 'name'){
								?>
								<div class="product-element product-<?php echo esc_attr( $itemvalue['table_items'] ); ?> elementor-repeater-item-<?php echo $itemvalue['_id']; ?>" colspan="<?php echo $itemvalue['colspan']; ?>"> <?php echo esc_html_e( $itemvalue['table_heading_title'] ?? null , 'woocommerce' ); ?> </div>								
								<?php								
							}else{
								if($itemvalue['table_items'] == 'remove' || $itemvalue['table_items'] == 'thumbnail' ){
									
								} else{
								?>
								<div class="product-element product-<?php echo esc_attr( $itemvalue['table_items'] ); ?> elementor-repeater-item-<?php echo $itemvalue['_id']; ?> " colspan="<?php echo $itemvalue['colspan']; ?>"> <?php echo esc_html_e( $itemvalue['table_heading_title'] ?? null , 'woocommerce' ); ?> </div>								
								<?php
								}
							}
							
						}
					}
				?>			
		</div>
		
		<div class="bew-cart-items-content">
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					
					?>
					<div class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">
						
						<?php
						
							foreach ( $cartitem as $key => $itemvalue ) {
								
								if( $itemvalue['table_cell_layout'] == ''){								
									if( ($itemvalue['table_cell_layout'] == '') && ('name' == $itemvalue['table_items'])){ 
										echo '<div class="default-layout">';
									}
								}
																	
								switch ( $itemvalue['table_items'] ) {
									
									case 'remove':
										echo '<div class="product-element product-remove elementor-repeater-item-' . $itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' remove-layout-' . ($itemvalue['remove_layout'] ?? null) . ' hide-element-mobile-' . ($itemvalue['hide_element_mobile'] ?? null) . ' ' . $loader_active . '">';
											echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
												'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><div class="product-remove-wrap"><span class="ti-close"></span><span class="remove-link">%s</span></div></a>',
												esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
												esc_html__( 'Remove this item', 'woocommerce' ),
												esc_attr( $product_id ),
												esc_attr( $_product->get_sku() ),
												$itemvalue['remove_layout'] == "icon" ? "" : $itemvalue['table_heading_title'],
											), $cart_item_key );
										echo '</div>';
										break;

									case 'thumbnail':										
										echo '<div class="product-element product-thumbnail elementor-repeater-item-'.$itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' ' . $loader_active . '">';
											$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );
											if ( ! $product_permalink ) {
												echo ( $thumbnail ); // PHPCS: XSS ok.
											} else {
												printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
											}
										echo '</div>';										
										break;

									case 'name':
										echo '<div class="product-element product-name elementor-repeater-item-'.$itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' ' . $loader_active . '" data-title=" ' . ($itemvalue['table_heading_title'] ?? null) .' ">';
											if ( ! $product_permalink ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
											} else {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
											}

											do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

											// Meta data.
											echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

											// Backorder notification.
											if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
												echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'briefcase-extras' ) . '</p>', $product_id ) );
											}
										echo '</div>';
										break;

									case 'price':
										echo '<div class="product-element product-price elementor-repeater-item-' . $itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' hide-element-mobile-' . ($itemvalue['hide_element_mobile'] ?? null) . ' ' . $loader_active . '" data-title=" ' . ($itemvalue['table_heading_title'] ?? null) . ' ">';
											echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key );
										echo '</div>';
										break;

									case 'quantity':										
										echo '<div class="product-element product-quantity show-remove-' . ( $itemvalue['show_remove_qty'] ?? null ) . ' show-remove-tablet-' . ($itemvalue['show_remove_qty_tablet'] ?? null) . ' show-remove-mobile-' . ($itemvalue['show_remove_qty_mobile'] ?? null) .' remove-mobile-' . ($itemvalue['remove_layout_qty'] ?? null) . ' absolute-qty-' . ($itemvalue['absolute_qty'] ?? null) . ' float-right-qty-' . ($itemvalue['float_right_qty'] ?? null) . ' elementor-repeater-item-'.$itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' ' . $loader_active . '" data-title=" ' . ($itemvalue['table_heading_title'] ?? null) .' ">';
										echo '<div class="product-quantity-content">';	
											if ( $_product->is_sold_individually() ) {
												$product_quantity = sprintf( '<div class="label">' .($itemvalue['text_before_qty'] ?? null) .'</div>' .'<div class="value">1' .($itemvalue['text_after_qty'] ?? null) .'</div><input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
											} else {
												$product_quantity = $frontend->bew_woocommerce_quantity_input( array(
													'input_name'  => "cart[{$cart_item_key}][qty]",
													'input_value' => $cart_item['quantity'],
													'max_value'   => $_product->get_max_purchase_quantity(),
													'min_value'   => '0',
												), $_product, false );
											}

											echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item );
																						
												echo '<div class="bew-product-remove product-remove-qty">';
													echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
														'<a href="%s" class="bew-remove" aria-label="%s" data-product_id="%s" data-product_sku="%s"><span class="ti-trash"></span><span class="remove-link">%s</span></a>',
														esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
														esc_html__( 'Remove this item', 'woocommerce' ),
														esc_attr( $product_id ),
														esc_attr( $_product->get_sku() ),
														esc_html__( 'Remove Item', 'bew-extras' )
													), $cart_item_key );
												echo '</div>';
											
										echo '</div>';	
										echo '</div>';									
										break;

									case 'subtotal':
										echo '<div class="product-element product-subtotal elementor-repeater-item-' . $itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' hide-element-mobile-' . ($itemvalue['hide_element_mobile'] ?? null) . ' ' . $loader_active . '" data-title=" ' . ($itemvalue['table_heading_title'] ?? null) .' ">';
											echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
										echo '</div>';
										break;

									case 'customadd':
										echo '<div class="product-element product-customdata elementor-repeater-item-'.$itemvalue['_id']. ' desktop_' . ($itemvalue['table_cell_layout'] ?? null) . ' tablet_' . ($itemvalue['table_cell_layout_tablet'] ?? null) . ' mobile_' . ($itemvalue['table_cell_layout_mobile'] ?? null) . ' ' . $loader_active . '">';
											
											//$cart_custom_data = get_post_meta( $product_id, 'bew_cart_custom_content', true );											
											//echo $cart_custom_data;
											
											$product = wc_get_product( $product_id );
											
											if( $cart_custom_data == 'woocommerce-product-description'){
												
												$cart_custom_data_p = $product->get_description();
												
											} elseif($cart_custom_data == 'woocommerce-product-short-description') {
												
												//echo "hola";
												$cart_custom_data_p = $product->get_short_description();
												
											} else {
												$cart_custom_data = '';
											}
																																
											//echo "hola" . $cart_custom_data;
											
											if($cart_custom_data == ""){

												echo '<div class="bew-product-custom-content max-md:line-clamp-1">';													
													echo ($itemvalue['table_custom_content'] ?? null );													
												echo '</div>';												

											} else {																								
												
												$limit = 25;
												$text = isset( $cart_custom_data_p ) ? $cart_custom_data_p : '';
												if (str_word_count($text, 0) > $limit) {
													$arr = str_word_count($text, 2);
													$pos = array_keys($arr);
													$text = substr($text, 0, $pos[$limit]) . '...';
													// $text = force_balance_tags($text); // may be you dont need this…
												}
												echo '<div class="bew-product-custom-content max-md:line-clamp-1">';
												echo $text;
												echo '</div>';
											}
											
										echo '</div>';
										break;

									default:
										break;									
								}
								
								if( $itemvalue['table_cell_layout'] == ''){	
									if( ($itemvalue['table_cell_layout'] == '') && ('quantity' == $itemvalue['table_items'])){								
										echo '</div>';									
									}
								}

							}
							$vertical_count = 0;
						?>

					</div>
					<?php
				}
				
				$i++;
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>
			<div class="cart-actions">
				<div colspan="<?php echo count( $cartitem ); ?>" class="actions">
					
					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon bew-coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> 
							<input type="text" name="coupon_code" class="input-text bew-coupon-field" id="coupon_code" value="" placeholder="<?php echo esc_html_e( esc_attr( $coupon_button_placeholder ), 'woocommerce' ); ?>" /> 
							<button type="submit" class="button bew-coupon-button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php echo esc_html_e( $coupon_button_name , 'woocommerce' ); ?>
																		
							</button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>
					
					<button type="submit" class="button bew-update-cart-button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php echo esc_html_e( $update_cart_button_name , 'woocommerce' ); ?></button>
					
					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart' ); ?>					
					
					<div class="bew-proceed-to-checkout">
						<a href="<?php echo get_permalink( wc_get_page_id( 'checkout' ) ); ?>" class="button checkout-button alt wc-forward">
							<?php echo esc_html_e( $checkout_button_name , 'woocommerce' ); ?></a>
					</div>
					
					<div class="cart-subtotal-table cart-subtotal bew-components-totals-item">
						<div class="bew-components-totals-item__label <?php echo $loader_active;?>"><span><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></span></div>
						<div class="bew-formatted-money-amount bew-components-formatted-money-amount bew-components-totals-item__value <?php echo $loader_active;?>"><?php wc_cart_totals_subtotal_html(); ?></div>
						<div class="bew-components-totals-item__description"></div>
					</div>
					
				</div>
			</div>
			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
			
		</div>
	</div>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>
</div>
<div class="bew-woo-cart">bew-woo-cart</div>

<?php do_action( 'woocommerce_after_cart' ); ?>