<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
//if ( ! $shortcode_html || ! $rule_id ) {
//	return;
//}
$div_class = array(
	'bew-checkout-ob-shortcode',
	'bew-checkout-ob-shortcode-' . $rule_id,
);
$div_class = trim( implode( ' ', $div_class ) );

$product_id = get_option( 'ob_product_id');	
//echo $product_id;
//$frontend = Frontend::instance();
?>
<div class="bew-checkout-ob-container">
	<div class="<?php echo esc_attr( $div_class ) ?>">
		<div class="bew-loading-wrap bew-disable">
			<div class="bew-loading">
				<div></div>
				<div></div>
				<div></div>
				<div></div>
			</div>
		</div>
		<?php
		echo do_shortcode( '[bew_checkout_order_bump id="' . $rule_id . '" product_id="' . $product_id . '" ]' );
		?>
	</div>
</div>