<?php
/**
 * Output a single payment method
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/payment-method.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
		$payment_methods_paypal_layout	= get_option( '_payment_order_methods_paypal_layout');
		$payment_methods_stripe_layout	 = get_option( '_payment_order_methods_stripe_layout');
?>
	<div class="bew-components-tabs bew-components-checkout-payment-methods">
		<div class="text-center">
			<ul class="bew-components-tabs__list bew-nav">
			<?php 
				if ( ! empty( $available_gateways ) ) {
					$counter = 0;
					foreach ( $available_gateways as $gateway ) { 
					
						$get_title = $gateway->get_title();						
						if( ($gateway->id == 'paypal') && ($payment_methods_paypal_layout == 'img' ) ) {
						$get_title = '<img src="' . BEW_EXTRAS_ASSETS_URL . 'img/paypal.png" alt="PayPal" >';	
						}
						if( ($gateway->id == 'stripe') && ($payment_methods_stripe_layout == 'img' ) ) {
						$get_title = '<img src="' . BEW_EXTRAS_ASSETS_URL . 'img/visa-mastercard.png" alt="Stripe" >';	
						}
						
						?>	
						<li class="bew-components-tabs__item bew-nav-item tab-switcher" data-tab-index="<?php echo $counter;?>" tabindex="<?php echo $counter;?>">
							<div class="bew-components-tabs__item-content payment_method_<?php echo esc_attr( $gateway->id );?> bew-nav-link <?php if ( $gateway->chosen ) : ?>active<?php endif; ?>" data-toggle="tab" href="#tab-<?php echo sanitize_title($gateway->get_title()); ?>"><?php echo $get_title ?></div>
							<input id="payment_method_<?php echo esc_attr( $gateway->id ); ?>" type="radio" class="input-radio" name="payment_method" value="<?php echo esc_attr( $gateway->id ); ?>" <?php checked( $gateway->chosen, true ); ?> data-order_button_text="<?php echo esc_attr( $gateway->order_button_text ); ?>" />
							
						</li>						
					<?php $counter++;	
					}
				} ?>
			</ul>
		</div>

		<div class="tab-content text-left" id="allTabsContainer">
			<?php 
			$counter = 0;
			foreach ( $available_gateways as $gateway  ) { ?>
				<?php if ( $gateway->has_fields() || $gateway->get_description() ) { ?>
					<div class="bew-components-tabs__content tab-pane tab-container <?php if ( $gateway->chosen ) : /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>show active<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>" data-tab-index="<?php echo $counter;?>" id="tab-<?php echo sanitize_title($gateway->get_title()); ?>">
						<?php $gateway->payment_fields();
						$get_title = $gateway->get_title();						
						if( $gateway->id == 'stripe' ) { ?>  
							<div class="bew-components-payment-method-icons bew-components-payment-method-icons--align-left">
								<?php								
								echo '<img class="bew-components-payment-method-icon bew-components-payment-method-icon--visa" src="' . BEW_EXTRAS_ASSETS_URL . 'img/payment-methods/visa.svg" alt="Visa">';
								echo '<img class="bew-components-payment-method-icon bew-components-payment-method-icon--amex" src="' . BEW_EXTRAS_ASSETS_URL . 'img/payment-methods/amex.svg" alt="American Express">';
								echo '<img class="bew-components-payment-method-icon bew-components-payment-method-icon--mastercard" src=" ' . BEW_EXTRAS_ASSETS_URL .'img/payment-methods/mastercard.svg" alt="Mastercard">';
								echo '<img class="bew-components-payment-method-icon bew-components-payment-method-icon--discover" src="' . BEW_EXTRAS_ASSETS_URL .'img/payment-methods/discover.svg" alt="Discover">';
								echo '<img class="bew-components-payment-method-icon bew-components-payment-method-icon--jcb" src="' . BEW_EXTRAS_ASSETS_URL . 'img/payment-methods/jcb.svg" alt="JCB">';
								echo '<img class="bew-components-payment-method-icon bew-components-payment-method-icon--diners" src="' . BEW_EXTRAS_ASSETS_URL . 'img/payment-methods/diners.svg" alt="Diners Club">';
								?>
							</div>
						<?php
						}
						?>
					</div>
				<?php } else { ?>
					<div class="bew-components-tabs__content tab-pane tab-container <?php if ( $gateway->chosen ) : ?>show active<?php endif; /* phpcs:ignore Squiz.ControlStructures.ControlSignature.NewlineAfterOpenBrace */ ?>" data-tab-index="<?php echo $counter;?>" id="tab-<?php echo sanitize_title($gateway->get_title()); ?>">
						<p></p>
					</div>
				<?php	
				}
				$counter++;
			 }			 
			 ?>
		</div>
	</div>

