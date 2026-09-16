<?php
namespace BriefcasewpExtras\Modules\WooCheckout;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Controls_Stack;
use WC_Shortcode_Checkout;
use WC_AJAX;
use BriefcasewpExtras\Base\Module_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Module extends Module_Base {
	
	public static function is_active() {
		return class_exists( 'woocommerce' );
	}

	public function get_name() {
		return 'woo-checkout';
	}

	public function get_widgets() {
		$widgets = [
			'Woo_Checkout_Form_Information',
			'Woo_Checkout_Form_Billing',
			'Woo_Checkout_Form_Shipping',
			'Woo_Checkout_Form_Additional',
			'Woo_Checkout_Form_Login',
			'Woo_Checkout_Coupon_Form',
			'Woo_Checkout_Review_Order',
			'Woo_Checkout_Payment',
			'Woo_Checkout_Shipping_Options',
			'Woo_Checkout_Place_Order',
			'Woo_Checkout_Multistep_Timeline',
			'Woo_Checkout_Multistep_Actions',
			'Woo_Checkout_Express_Request',
			'Woo_Checkout_Order_Bump',
			'Woo_Checkout_Delivery',
		];

		if ( $this->bewia_is_smart_upsells_active() ) {
			$widgets[] = 'BEWIA_BCWP_Elementor_AI_Upsell';
		}

		return $widgets;
	}

	private function bewia_is_smart_upsells_active() {
		$active_extensions = get_option( 'briefcasewp_active_extensions', array() );

		return in_array( 'Bew_AI_Smart_Upsells', $active_extensions, true );
	}
	
	public function register_controls( Controls_Stack $element ) {
		
			$post_id  = '';
			global $post;
			if(isset($post)){
				$post_id = $post->ID;
			}			
			$checkout_id = wc_get_page_id( 'checkout' );
						
			//if($post_id != $checkout_id ){			
			//return;
			//}
			
			$element->start_controls_section(
				'section_bew_checkout',
				[
					'label' => __( 'Bew Checkout', 'bew-extras' ),
					'tab' => Controls_Manager::TAB_LAYOUT,				
				]
			);

			$element->add_control(
				'bew_checkout',
				[
					'label'	=> __( 'Enable Checkout Form', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'prefix_class' => 'bew-checkout-',
					'description'	=> __( 'Enable Bew Checkout Form on this section', 'bew-extras' )
				]
			);
			
			$element->add_control(
				'bew_checkout_loading',
				[
					'label'	=> __( 'Enable Loading', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => 'yes',
					'frontend_available' => true,
					'description'	=> __( 'Enable Bew Checkout loading effect.', 'bew-extras' ),
					'condition' => [
						'bew_checkout' => 'yes',
					],
				]
			);

			$element->add_control(
				'bew_checkout_split_test',
				[
					'label'	=> __( 'Enable Split Test Checkout', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'prefix_class' => 'bew-checkout-',
					'description'	=> __( 'Enable Split Test Checkout on this section', 'bew-extras' )
				]
			);
			
            $element->add_responsive_control(
                'bew_checkout_skeleton_padding',
                [
                    'label' => __( 'Loading Padding', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.bew-checkout.bew-skeleton' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; ',
                    ],
					'condition' => [
						'bew_checkout' => 'yes',
						'bew_checkout_loading' => 'yes',
					],
                ]
            );

            $element->add_responsive_control(
                'bew_checkout_skeleton_margin',
                [
                    'label' => __( 'Loading Margin', 'elementor' ),
                    'type' => Controls_Manager::DIMENSIONS,
                    'size_units' => [ 'px', 'em', '%' ],
                    'selectors' => [
                        '.bew-checkout.bew-skeleton' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    ],
					'condition' => [
						'bew_checkout' => 'yes',
						'bew_checkout_loading' => 'yes',
					],
                ]
            );
			
			$element->add_control(
				'bew_checkout_multistep',
				[
					'label'	=> __( 'Enable Checkout Multistep', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'prefix_class' => 'bew-checkout-multistep-',
					'description'	=> __( 'Enable Bew Checkout Multistep on this section', 'bew-extras' )
				]
			);

		// Add Fast Checkout control 		
		$active_extensions = get_option( 'briefcasewp_active_extensions', array() );

		// Check If Bew Fast Checkout is active.
		if (in_array("Bew_Fast_Checkout", $active_extensions)) {
		
			$element->add_control(
				'bew_checkout_fast',
				[
					'label'	=> __( 'Enable Bew Fast Checkout', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'prefix_class' => 'bew-checkout-fast-',
					'description'	=> __( 'Enable Bew Fast Checkout on this section', 'bew-extras' )
				]
			);
			
		}	

			$element->add_control(
				'bew_checkout_notice',
				[
					'label'	=> __( 'Enable Woocommerce Notices', 'bew-extras' ),
					'type' => Controls_Manager::SWITCHER,
					'label_on' => __( 'On', 'bew-extras' ),
					'label_off' => __( 'Off', 'bew-extras' ),
					'return_value' => 'yes',
					'default' => '',
					'frontend_available' => true,
					'prefix_class' => 'bew-checkout-notices-',
					'description'	=> __( 'Enable Woocommerce Notices on this section', 'bew-extras' )
				]
			);
						
			$element->end_controls_section();
	}

	
	/**
	 * Show the bew checkout.
	 */
	private static function bew_checkout($fast_checkout, $checkout_notices) {
		// Show non-cart errors.
		do_action( 'woocommerce_before_checkout_form_cart_notices' );

		// Check cart has contents.
		if ( WC()->cart->is_empty() && ! is_customize_preview() && apply_filters( 'woocommerce_checkout_redirect_empty_cart', true ) ) {			
			return;
		}
		
		// Check cart contents for errors.
		do_action( 'woocommerce_check_cart_items' );

		// Calc totals.
		WC()->cart->calculate_totals();

		// Get checkout object.
		$checkout = WC()->checkout();

		if ( empty( $_POST ) && wc_notice_count( 'error' ) > 0 ) { // WPCS: input var ok, CSRF ok.

			wc_get_template( 'checkout/cart-errors.php', array( 'checkout' => $checkout ) );
			wc_clear_notices();

		} else {

			$non_js_checkout = ! empty( $_POST['woocommerce_checkout_update_totals'] ); // WPCS: input var ok, CSRF ok.

			if ( wc_notice_count( 'error' ) === 0 && $non_js_checkout ) {
				wc_add_notice( __( 'The order totals have been updated. Please confirm your order by pressing the "Place order" button at the bottom of the page.', 'woocommerce' ) );
			}

			//wc_get_template( 'checkout/form-checkout.php', array( 'checkout' => $checkout ) );
			
			// Add star form from form-checkout template, replace form-checkout.php
			
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
			remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );			
			do_action( 'woocommerce_before_checkout_form', $checkout );					
			
			// If checkout registration is disabled and not logged in, the user cannot checkout.
			if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
				echo '<div class="bew-must-be-logged woocommerce-info">';
				echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'woocommerce' ) ) );
				echo '</div>';
				echo '<div class="bew-checkout-force-login">';	
				return;
			}
						
			echo apply_filters( 'bew-checkout-form-tag', '<form name="checkout" method="post" class="checkout woocommerce-checkout woocommerce-notices-' .$checkout_notices . '" action="" enctype="multipart/form-data" novalidate="novalidate" style="opacity:0;">' );
			
		}
	}
	
	public function bew_form_start ( $element ) {
		$settings = $element->get_settings_for_display();
		$fast_checkout = '';
		$checkout_notices = '';
		$bew_checkout_loading = $settings['bew_checkout_loading'] ?? null;
		$post_id = '';
		
		if(isset($settings['bew_checkout_fast'])){
			$fast_checkout = $settings['bew_checkout_fast'];
		}

		if(isset($settings['bew_checkout_notice'])){
			$checkout_notices = $settings['bew_checkout_notice'];
		}
		
		//Bec Checkout Output		
		global $wp;

		// Check cart class is loaded or abort.
		if ( is_null( WC()->cart ) ) {
			return;
		}
		
		//Save data for Split Test
		global $post;
		if(isset($post)){
			$post_id = $post->ID;
		}			
		$meta_key = 'bew_split_test';
			
		if( $settings['bew_checkout_split_test'] == 'yes' ) {			
			update_post_meta( $post_id, $meta_key, 'yes');		
		} else {
			update_post_meta( $post_id, $meta_key, '');			
		}
		
		// Handle checkout actions.	
		if(isset($settings['bew_checkout'])){
			if( $settings['bew_checkout'] == 'yes' ) {
				
				// Handle checkout actions.
				if ( ! empty( $wp->query_vars['order-pay'] ) ) {
					
					?><section class="woocommerce bew-woocommerce-order-pay"> <?php 
						do_action( 'bew_order_received' );
					?></section><?php

				} elseif ( isset( $wp->query_vars['order-received'] ) ) {
					
					?><section class="woocommerce bew-woocommerce-order"> <?php 
						do_action( 'bew_order_received' );
					?></section><?php

				} else {
					
					if( $bew_checkout_loading == 'yes' ) {
						echo $this->bew_checkout_skeleton();
					}
					
					$this-> bew_checkout($fast_checkout, $checkout_notices);
					
				}
			
			}
		}
	}

	public function bew_form_close( $element ) {
		$settings = $element->get_settings_for_display();
		
		// Get checkout object.
		$checkout = WC()->checkout();
		
		if(isset($settings['bew_checkout'])){
			if( $settings['bew_checkout'] == 'yes' ) {
				global $wp;
				
				// Handle checkout actions.
				if ( ! empty( $wp->query_vars['order-pay'] ) ) {
					
					//add_action( 'wp_footer', [ $this, 'bew_send_order_pay_js'] );

				} elseif ( isset( $wp->query_vars['order-received'] ) ) {
					
					add_action( 'wp_footer', [ $this, 'bew_send_thankyou_js'] );

				} else {
					
					// If checkout registration is disabled and not logged in, the user cannot checkout.
					if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
						echo '</div>';	
						return;
					}
			
					echo '</form>';	
					
					do_action( 'woocommerce_after_checkout_form', $checkout );

				}
							
			}
		}
	}
	
	function bew_send_thankyou_js(){
				 
		// exit if we are not on the Thank You page
		if( !is_wc_endpoint_url( 'order-received' ) ) return;
				 
			echo "<script>
			jQuery( function( $ ) {
				$('.bew-checkout-yes').remove();
				$('.bew-woocommerce-order').siblings('.elementor-section').remove();
				});
			</script>";		 
	}
	
	function bew_send_order_pay_js(){
				 
		// exit if we are not on the Thank You page
		if( !is_wc_endpoint_url( 'order-pay' ) ) return;
				 
			echo "<script>
			jQuery( function( $ ) {
				$('.bew-checkout-yes').remove();
				$('.bew-woocommerce-order-pay').siblings('.elementor-section').remove();
				});
			</script>";		 
	}
			
	function bew_checkout_skeleton(){
		 ?>
		<section class="elementor-element bew-checkout-yes elementor-section-boxed elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section bew-skeleton-section" data-element_type="section" data-settings="{&quot;bew_checkout&quot;:&quot;yes&quot;}">
			<div class="elementor-container elementor-column-gap-default">
					
				<div class="bew-skeleton bew-components-sidebar-layout bew-checkout bew-checkout--is-loading bew-checkout--skeleton" aria-hidden="true">
					<div class="bew-components-main bew-checkout__main">
						<div class="bew-components-express-checkout"></div>
						<div class="bew-components-express-checkout-continue-rule"><span></span></div>
						<form class="bew-components-checkout-form">
							<fieldset class="bew-checkout__contact-fields bew-components-checkout-step">
								<div class="bew-components-checkout-step__heading">
									<div class="bew-components-checkout-step__title"></div>
								</div>
								<div class="bew-components-checkout-step__container">
									<div class="bew-components-checkout-step__content">
										<span></span>
									</div>
								</div>
							</fieldset>
							<fieldset class="bew-checkout__contact-fields bew-components-checkout-step">
								<div class="bew-components-checkout-step__heading">
									<div class="bew-components-checkout-step__title"></div>
								</div>
								<div class="bew-components-checkout-step__container">
									<div class="bew-components-checkout-step__content">
										<span></span>
									</div>
								</div>
							</fieldset>
							<fieldset class="bew-checkout__contact-fields bew-components-checkout-step">
								<div class="bew-components-checkout-step__heading">
									<div class="bew-components-checkout-step__title"></div>
								</div>
								<div class="bew-components-checkout-step__container">
									<div class="bew-components-checkout-step__content">
										<span></span>
									</div>
								</div>
							</fieldset>
							<fieldset class="bew-checkout__contact-fields bew-components-checkout-step">
								<div class="bew-components-checkout-step__heading">
									<div class="bew-components-checkout-step__title"></div>
								</div>
								<div class="bew-components-checkout-step__container">
									<div class="bew-components-checkout-step__content">
										<span></span>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="bew-components-sidebar bew-checkout__sidebar">
						<div class="components-card"></div>
					</div>
					<div class="bew-components-main bew-checkout__main-totals">
						<div class="bew-checkout__actions">
							<button class="components-button button bew-button bew-components-checkout-place-order-button">&nbsp;</button>
						</div>
					</div>
				</div>
			</div>
		</section>
		<?php
	}
	
	public function body_class_checkout( $classes ){
        if ( ( is_checkout() && !is_wc_endpoint_url('order-received') ) ) {
			$classes[] = 'bew-checkout';
			$classes[] = 'bew-woocommerce-builder';			
		} else {
			 if (is_wc_endpoint_url('order-received') ) {
				$classes[] = 'bew-checkout-order-received';
				$classes[] = 'bew-woocommerce-builder';	
			 }
		}			
		
        return $classes;
    }

	public function body_class_checkout_or( $classes ){

		if (is_wc_endpoint_url('order-received') ) {
			$classes[] = 'bew-checkout-order-received';
			$classes[] = 'bew-woocommerce-builder';	
		 }
				
        return $classes;
    }	

	// define the woocommerce_review_order_before_cart_contents callback 
	//function action_woocommerce_review_order_before_cart_contents(  ) { 
	//	echo do_shortcode( '[viwcuf_checkout_order_bump]' );
	//}

	private function add_actions() {
	
		// Add Bew Checkout on section
		add_action( 'elementor/element/section/section_structure/after_section_end', [ $this, 'register_controls' ], 10, 3  );	
				
		// Add Bew Checkout on container
		add_action( 'elementor/element/container/section_layout_additional_options/after_section_end', [ $this, 'register_controls' ], 10, 3  );	
		
		// Add Form on section
		add_action( 'elementor/frontend/section/before_render', [ $this, 'bew_form_start'] );
		add_action( 'elementor/frontend/section/after_render', [ $this, 'bew_form_close'] );
		
		// Add Form on container
		add_action( 'elementor/frontend/container/before_render', [ $this, 'bew_form_start'] );
		add_action( 'elementor/frontend/container/after_render', [ $this, 'bew_form_close'] );
		
		//add_filter( 'body_class', [ $this, 'body_class_checkout_or'] );
		add_filter( 'body_class', [ $this, 'body_class_checkout_or'] );
		add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ] );		
		
		// add custom action on review order 
		//add_action( 'woocommerce_review_order_before_cart_contents', [ $this,  'action_woocommerce_review_order_before_cart_contents'], 10, 0 );
		
	}

	public function enqueue_scripts() {
		$international_phone = '';

		wp_register_script(
			'bcwp-ai-upsell',
			BEW_EXTRAS_MODULES_URL . 'woo-checkout/assets/js/bcwp-ai-upsell.js',
			array( 'jquery' ),
			BEW_EXTRAS_VERSION,
			true
		);

		wp_register_style(
			'bcwp-ai-upsell',
			BEW_EXTRAS_MODULES_URL . 'woo-checkout/assets/css/bcwp-ai-upsell.css',
			array(),
			BEW_EXTRAS_VERSION
		);

		$args = array(
			'wc_ajax_url'                => WC_AJAX::get_endpoint( "%%endpoint%%" ),
			'i18n_unavailable_text'      => apply_filters( 'bew-i18n_unavailable_text', __( 'Sorry, this product is unavailable. Please choose a different combination.', 'bew-extraso' ) ),
			'i18n_make_a_selection_text' => apply_filters( 'bew-i18n_make_a_selection_text', __( 'Please select some product options before adding {product_name} to your cart.', 'bew-extras' ) ),
			'international_phone'       => $international_phone,
			'allowed_countries'         => array_map( 'strtolower', array_keys( WC()->countries->get_allowed_countries() ) ),
			'base_country'              => WC()->countries->get_base_country(),
			'intl_util_path'            => plugins_url( 'assets-vendor/intl-tel-input/js/utils.js', BEW_EXTRAS__FILE__ ),
		);
		
		if ( wp_script_is( 'bew-checkout', 'registered' ) || wp_script_is( 'bew-checkout', 'enqueued' ) ) {
			wp_localize_script( 'bew-checkout', 'bew_frontend_ob_params', $args );
		}

		if ( wp_script_is( 'bcwp-ai-upsell', 'registered' ) ) {
			wp_localize_script(
				'bcwp-ai-upsell',
				'BEWIAUpsell',
				array(
					'ajax_url'     => admin_url( 'admin-ajax.php' ),
					'nonce'        => wp_create_nonce( 'bewia_ai_smart_upsells' ),
					'is_checkout'  => ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) ? 1 : 0,
					'currencySymbol' => get_woocommerce_currency_symbol(),
					'loadingText'  => __( 'Loading recommendation...', 'bew-extras' ),
					'addingText'   => __( 'Adding offer...', 'bew-extras' ),
					'dismissingText' => __( 'Updating offer...', 'bew-extras' ),
					'successText'  => __( 'Offer added to cart.', 'bew-extras' ),
					'skeletonText' => __( 'Loading recommendation...', 'bew-extras' ),
					'emptyText'    => __( 'No recommendation available right now.', 'bew-extras' ),
					'errorText'    => __( 'Something went wrong. Please try again.', 'bew-extras' ),
				)
			);
		}

		if ( is_checkout() && ! is_wc_endpoint_url( 'order-received' ) ) {
			wp_enqueue_script( 'bcwp-ai-upsell' );
			wp_enqueue_style( 'bcwp-ai-upsell' );
		}

	}
			
	public function __construct() {
		parent::__construct();
		
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/classes/bew-woo-checkout.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/classes/bew-cst.php';				
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/interface-bcwp-ai-upsell-provider.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ai-provider.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-copy-generator.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-hybrid-provider.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-rule-provider.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-engine.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ajax.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php';
		require_once BEW_EXTRAS_PATH . 'modules/woo-checkout/widgets/class-bcwp-elementor-ai-upsell.php';
			
		$this->add_actions();
		add_shortcode( 'bewia_ai_upsell', array( $this, 'render_ai_upsell_shortcode' ) );
	
	
	}

	public function render_ai_upsell_shortcode( $atts = array() ) {
		if ( ! class_exists( 'WooCommerce' ) || ! is_checkout() || is_wc_endpoint_url( 'order-received' ) ) {
			return '';
		}

		$saved_settings = array();

		if ( class_exists( '\BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Analytics' ) ) {
			$saved_settings = \BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Analytics::get_saved_settings();
		}

		$atts = shortcode_atts(
			array(
				'enable_ai'                   => ! empty( $saved_settings['enable_ai'] ) ? $saved_settings['enable_ai'] : 'yes',
				'mode'                        => ! empty( $saved_settings['mode'] ) ? $saved_settings['mode'] : 'rules',
				'layout'                      => ! empty( $saved_settings['layout'] ) ? $saved_settings['layout'] : 'card',
				'candidate_products'          => ! empty( $saved_settings['candidate_product_ids'] ) ? implode( ',', $saved_settings['candidate_product_ids'] ) : '',
				'required_cart_categories'    => ! empty( $saved_settings['required_cart_category_ids'] ) ? implode( ',', $saved_settings['required_cart_category_ids'] ) : '',
				'exclude_cart_products'       => isset( $saved_settings['exclude_cart_products'] ) ? $saved_settings['exclude_cart_products'] : 'yes',
				'minimum_cart_total'          => isset( $saved_settings['minimum_cart_total'] ) ? $saved_settings['minimum_cart_total'] : 0,
				'maximum_cart_total'          => isset( $saved_settings['maximum_cart_total'] ) ? $saved_settings['maximum_cart_total'] : 0,
				'customer_status'             => ! empty( $saved_settings['customer_status'] ) ? $saved_settings['customer_status'] : 'any',
				'device_type'                 => ! empty( $saved_settings['device_type'] ) ? $saved_settings['device_type'] : 'any',
				'maximum_product_price_ratio' => isset( $saved_settings['maximum_product_price_ratio'] ) ? $saved_settings['maximum_product_price_ratio'] : 1,
				'priority_scores'             => ! empty( $saved_settings['priority_scores'] ) ? $this->bewia_format_shortcode_priority_scores( $saved_settings['priority_scores'] ) : '',
				'show_dismiss'                => ! empty( $saved_settings['show_dismiss'] ) ? $saved_settings['show_dismiss'] : 'yes',
				'success_message'             => ! empty( $saved_settings['success_message'] ) ? $saved_settings['success_message'] : __( 'Offer added to cart.', 'bew-extras' ),
				'collapse_delay_ms'           => isset( $saved_settings['collapse_delay_ms'] ) ? $saved_settings['collapse_delay_ms'] : 1200,
				'fallback_title'              => __( 'Recommended for your order', 'bew-extras' ),
				'fallback_description'        => __( 'A personalized offer will appear here during checkout.', 'bew-extras' ),
				'button_text'                 => __( 'Add this offer', 'bew-extras' ),
			),
			$atts,
			'bewia_ai_upsell'
		);

		$priority_scores = $this->bewia_parse_shortcode_priority_scores( $atts['priority_scores'] );
		$candidate_ids   = array_values( array_filter( array_map( 'absint', preg_split( '/[\s,]+/', (string) $atts['candidate_products'] ) ) ) );

		if ( ! empty( $priority_scores ) ) {
			$candidate_ids = array_values( array_unique( array_merge( $candidate_ids, array_map( 'absint', array_keys( $priority_scores ) ) ) ) );
		}

		$widget_settings = array(
			'enable_ai'                   => 'yes' === $atts['enable_ai'] ? 'yes' : '',
			'mode'                        => sanitize_key( $atts['mode'] ),
			'layout'                      => sanitize_key( $atts['layout'] ),
			'candidate_product_ids'       => $candidate_ids,
			'required_cart_category_ids'  => array_values( array_filter( array_map( 'absint', preg_split( '/[\s,]+/', (string) $atts['required_cart_categories'] ) ) ) ),
			'exclude_cart_products'       => 'yes' === $atts['exclude_cart_products'] ? 'yes' : '',
			'minimum_cart_total'          => (float) $atts['minimum_cart_total'],
			'maximum_cart_total'          => (float) $atts['maximum_cart_total'],
			'customer_status'             => sanitize_key( $atts['customer_status'] ),
			'device_type'                 => sanitize_key( $atts['device_type'] ),
			'maximum_product_price_ratio' => (float) $atts['maximum_product_price_ratio'],
			'priority_scores'             => $priority_scores,
			'show_dismiss'                => 'yes' === $atts['show_dismiss'] ? 'yes' : '',
			'success_message'             => sanitize_text_field( $atts['success_message'] ),
			'collapse_delay_ms'           => absint( $atts['collapse_delay_ms'] ),
			'fallback_title'              => sanitize_text_field( $atts['fallback_title'] ),
			'fallback_description'        => sanitize_textarea_field( $atts['fallback_description'] ),
			'button_text'                 => sanitize_text_field( $atts['button_text'] ),
		);
		$widget_settings = \BriefcasewpExtras\Modules\WooCheckout\AIUpsells\BEWIA_AI_Upsell_Engine::normalize_settings( $widget_settings );

		ob_start();
		?>
		<div
			class="bewia-ai-smart-upsells bewia-ai-smart-wrapper bewia-ai-upsell-wrapper is-loading"
			data-widget="bewia-ai-smart-upsell"
			data-layout="<?php echo esc_attr( $widget_settings['layout'] ); ?>"
			data-mode="<?php echo esc_attr( $widget_settings['mode'] ); ?>"
			data-settings="<?php echo esc_attr( wp_json_encode( $widget_settings ) ); ?>"
		>
			<div class="bewia-ai-smart-upsells__status" aria-live="polite"></div>
			<div class="bewia-ai-upsell-content">
				<h3 class="bewia-ai-upsell-title"><?php echo esc_html( $widget_settings['fallback_title'] ); ?></h3>
				<div class="bewia-ai-upsell-description"><?php echo esc_html( $widget_settings['fallback_description'] ); ?></div>
			</div>
			<div class="bewia-ai-smart-upsells__list"></div>
			<div class="bewia-ai-smart-upsells__actions">
				<button type="button" class="bewia-ai-smart-upsells__button bewia-ai-upsell-button button" disabled="disabled">
					<?php echo esc_html( $widget_settings['button_text'] ); ?>
				</button>
				<button type="button" class="bewia-ai-smart-upsells__dismiss button button-link" style="display:none;">
					<?php echo esc_html__( 'No thanks', 'bew-extras' ); ?>
				</button>
			</div>
		</div>
		<?php
		return ob_get_clean();
	}

	private function bewia_parse_shortcode_priority_scores( $raw_value ) {
		$priority_scores = array();
		$pairs           = preg_split( '/[\r\n,]+/', (string) $raw_value );

		if ( empty( $pairs ) || ! is_array( $pairs ) ) {
			return $priority_scores;
		}

		foreach ( $pairs as $pair ) {
			$pair = trim( $pair );

			if ( '' === $pair || false === strpos( $pair, ':' ) ) {
				continue;
			}

			list( $product_id, $score ) = array_map( 'trim', explode( ':', $pair, 2 ) );
			$product_id = absint( $product_id );

			if ( $product_id <= 0 ) {
				continue;
			}

			$priority_scores[ $product_id ] = (float) $score;
		}

		return $priority_scores;
	}

	private function bewia_format_shortcode_priority_scores( $priority_scores ) {
		$lines = array();

		if ( ! is_array( $priority_scores ) ) {
			return '';
		}

		foreach ( $priority_scores as $product_id => $score ) {
			$product_id = absint( $product_id );

			if ( $product_id <= 0 ) {
				continue;
			}

			$lines[] = $product_id . ':' . (float) $score;
		}

		return implode( ',', $lines );
	}
	
}
