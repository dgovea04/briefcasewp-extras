'use strict';
var bewcheckout;

(
	function() {

		bewcheckout = (
		function() {

				return {

					init: function() { 
						this.checkout();
						this.cart();
						this.account();
					}
				}
			}()
		);
		
	}
)( jQuery );

jQuery( document ).ready( function() {
	if (!jQuery( 'body' ).hasClass("elementor-editor-active")) {
		bewcheckout.init();
	}
} );

// Make sure you run this code under Elementor.
jQuery( window ).on( 'elementor/frontend/init', function() {
		elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function() {
		if (jQuery( 'body' ).hasClass("elementor-editor-active")) {
			bewcheckout.init();
		}
	});
} );

//Checkout pages
(function( $ ) {

	var $window   = $( window ),
		$document = $( document ),
		$body     = $( 'body' ),
		w         = $window.width();		

	bewcheckout.checkout = function() {
		
		if(!$body.hasClass("woocommerce-checkout")){
			return;
		}

		//Check if is a mobile view
		function viewport() {
			var e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}
		
		function is_mobile() {
			var is_mobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			return is_mobile;
		}
		
		var CheckoutPage = function() {
			
			CheckoutInputLabels();
			
			setTimeout(function(){
				CheckoutPageloader();
			},500);
			
			console.log("is mobile " + is_mobile());
					
			if ( ( viewport().width  <= 767 ) && (is_mobile()) ) {				
								
				$('select').each( function () {	
					if ($(this).hasClass("select2-hidden-accessible")) {
						$(this).selectWoo("destroy");
					}
				});	
				
			} else {
				
				//Initiate Selectwoo	
				$('select#billing_country').selectWoo();
				$('select#billing_state').selectWoo();
				$('select#shipping_country').selectWoo();
				$('select#shipping_state').selectWoo();
				
				$('select:not(.flatpickr-monthDropdown-months)').each( function () {		
					$(this).selectWoo();
				});
			
			}
						
			//Check if autocomplete address is running
			$(document).ajaxComplete(function(event,xhr,options){
			   
				var str = options.url
				if (str.includes('brasilapi.com.br')) {					 
					 //console.log(str);
					 CheckoutInputLabels();
				}; 
			});
			
			//Disabled Enter on Checkout
			$(document).ready(function() {
				$("form").keypress(function(e) {
				  //Enter key
				  if (e.which == 13) {
					return false;
				  }
				});
			});
			
        };

		var CheckoutPageloader = function() {
									
			if ($(".woocommerce-checkout").length > 0 ) {
			  $(document).ready(function() {			
										
					$(".bew-skeleton").addClass("hidde-bew-skeleton");
					$(".woocommerce-checkout").addClass("show-bew-checkout");	
					
			  });
			}
		
        };
		
		var CheckoutInputLabels = function() {
						
			setTimeout(function(){
				$('.label-inside-yes input, .label-inside-yes select').each( function () {
					
					var $this = $(this);
					
					if ( this.value != '' ) $this.parents('div.form-row.label-inside-yes').addClass('is-active');
					if ( this.value != '' ) $this.parents('p.form-row.label-inside-yes').addClass('is-active');				
					//$this.attr("placeholder", "");
					
				});
			
				$('input, select').each( function () {	
				
					var $this = $(this);
					
					if ( this.value != '' ) $this.parents('div.form-row').addClass('has-value');
					if ( this.value != '' ) $this.parents('p.form-row').addClass('has-value');
					
				});
						
			},200);
			
		};
		
		var CheckoutInput = function() {
			
			$( '#billing_country, #billing_state, #billing_city, #billing_postcode, #shipping_country' ).on( 'change', function() {
				$('.label-inside-yes input, .label-inside-yes select').each( function () {
					var $this = $(this);	
					//$this.attr("placeholder", "");
				});
			} )
			
			$('.bew-checkout').on('focus input','input, select',  function(){
								
				if($(this).val() != '' ) {
				}else{					
					$(this).parents('div.form-row.label-inside-yes').addClass('is-active');
					$(this).parents('p.form-row.label-inside-yes').addClass('is-active');					
					//$(this).parents('.bew-payment-methods.label-inside-yes div.form-row').addClass('is-active');					
				} 
				 
			})
			
			$('.bew-checkout').on('keyup','input, select',  function(){
				var self = $( this );

				if ( self.val() != '' ) {
					$(this).parents('div.form-row').addClass('has-value');
					$(this).parents('p.form-row').addClass('has-value');
				} else {
					$(this).parents('div.form-row').removeClass('has-value');
					$(this).parents('p.form-row').removeClass('has-value');
				}
			})
			
			$('.bew-checkout').on('blur','input, select',  function(){
								
				if($(this).val() != '' ) {
				}else{					
					$(this).parents('div.form-row.label-inside-yes').removeClass('is-active');
					$(this).parents('p.form-row.label-inside-yes').removeClass('is-active');
					
					//$(this).parents('.bew-payment-methods.label-inside-yes div.form-row').removeClass('is-active');
				} 
				 
			})
					
			if($('#shipping_state').is("input")){
				
				var address_text = $(".bew-components-shipping-address span").text(),
					state = $('#shipping_state').val(),
					text_split = address_text.split(','),						
					newtext = address_text.replace(text_split[0], state );					

				setTimeout(function(){
					$(".bew-components-shipping-address span").text(newtext);
				},1000);
			}
			
			$('.bew-checkout').on('keyup change','#shipping_state',  function(){
											
					var address_text = $(".bew-components-shipping-address span").text(),
						state_select = $(this).find('option:selected').text(),
						state_input = $(this).val();
												
					if($(this).is("input")){
						var state = state_input; 
					 }else {
						var state = state_select; 
					 }
					
					var text_split = address_text.split(','),						
						newtext = address_text.replace(text_split[0], state );						
											
						$(".bew-components-shipping-address span").text(newtext);
						
			});
			
			// Remove active float label on password account			
			$('p.create-account').on('click',function () {
				if ($('p.create-account .input-checkbox').is(':checked')) {						
					setTimeout(function(){
						$('div.create-account .form-row.label-inside-yes').removeClass('woocommerce-invalid woocommerce-invalid-required-field');	
					},100);	
				}else{						               	        	
					$('div.create-account .form-row.label-inside-yes').removeClass('is-active has-value woocommerce-invalid woocommerce-invalid-required-field');						
				}
			});	
			
			// Get settings layout if shipping country change
			$('.bew-checkout').on('keyup change','#shipping_country_field',  function(){
				
				$('.form-row').each( function () {
					var $this = $(this);
					
					$this.removeClass(function (index, css) {
						 return (css.match (/\bform-row\S+/g) || []).join(' '); // removes anything that starts with "form-row"
					});
										
					setTimeout(function(){
						var old_class = $this.attr("data-row");
						
						if(typeof old_class != 'undefined' ){
							$this.addClass(old_class);	
						}
					},200);
					
					if($('#shipping_state').is("input")){
						setTimeout(function(){
							if($('#shipping_state').val() == '' ) {
								$('#shipping_state_field').removeClass('is-active');
							}
						},200);	
					}
					
					// Make sure select2 doesnt enabled on mobile on country change
					if ( ( viewport().width  <= 767 ) && (is_mobile()) ) {					
						$('select').each( function () {	
							if ($(this).hasClass("select2-hidden-accessible")) {
								$(this).selectWoo("destroy");
							}
						});
					}

					if($('#billing_state').is("select")){
						setTimeout(function(){
							if ($('.shipping-checkbox-input-b').is(':checked')) {
								if ( ( viewport().width  <= 767 ) && (is_mobile()) ) {	
								
								} else {
									$('#billing_state').selectWoo();
								}								
							}
						},100);	
					}
					
				});							
			});
			
			// Get settings layout if billing country change
			$('.bew-checkout').on('keyup change','#billing_country_field',  function(){
				$('.form-row').each( function () {
					var $this = $(this);
					
					$this.removeClass(function (index, css) {
						 return (css.match (/\bform-row\S+/g) || []).join(' '); // removes anything that starts with "form-row"
					});
					
					setTimeout(function(){
						var old_class = $this.attr("data-row");
						
						if(typeof old_class != 'undefined' ){													
							$this.addClass(old_class);	
						}
					},100);
					
					if($('#billing_state').is("input")){
						setTimeout(function(){
							if($('#billing_state').val() == '' ) {
								$('#billing_state_field').removeClass('is-active');
							}
						},100);
					}

					// Make sure select2 doesnt enabled on mobile on country change
					if ( ( viewport().width  <= 767 ) && (is_mobile()) ) {
						$('select').each( function () {	
							if ($(this).hasClass("select2-hidden-accessible")) {
								$(this).selectWoo("destroy");
							}
						});
					}
					
				});						
			});
			
			if ($(".bew-payment-methods").hasClass("label-inside-yes")){
				
				$('.bew-checkout').on('focus input','#wc-stripe-cc-form input',  function(){	
					
					// check if stripe stripe-card-element is focused
					var $div1 = $("#stripe-card-element");
					var observer = new MutationObserver(function(mutations) {
					  mutations.forEach(function(mutation) {
						if (mutation.attributeName === "class") {
						  var attributeValue = $(mutation.target).prop(mutation.attributeName),					      
							  checker = attributeValue.includes( 'focused' );						  
						  if(checker == true){
							$div1.closest('div.form-row').addClass('is-active');						
						  }else{						  
							var checker2 = attributeValue.includes( 'empty' );						  
							if(checker2 == true){	
							  $div1.closest('div.form-row').removeClass('is-active');
							}						
						  }
						}
					  });
					});
					
					observer.observe($div1[0], {
					  attributes: true
					});
					
					// check if stripe stripe-exp-element is focused
					var $div2 = $("#stripe-exp-element");
					var observer = new MutationObserver(function(mutations) {
					  mutations.forEach(function(mutation) {
						if (mutation.attributeName === "class") {
						  var attributeValue = $(mutation.target).prop(mutation.attributeName),
							  checker = attributeValue.includes( 'focused' );
						  if(checker == true){
							$div2.closest('div.form-row').addClass('is-active');						
						  }else{						  
							var checker2 = attributeValue.includes( 'empty' );						  
							if(checker2 == true){	
							  $div2.closest('div.form-row').removeClass('is-active');
							}
						  }
						}
					  });
					});
					
					observer.observe($div2[0], {
					  attributes: true
					});
					
					// check if stripe stripe-cvc-element is focused
					var $div3 = $("#stripe-cvc-element");
					var observer = new MutationObserver(function(mutations) {
					  mutations.forEach(function(mutation) {
						if (mutation.attributeName === "class") {
						  var attributeValue = $(mutation.target).prop(mutation.attributeName),
							  checker = attributeValue.includes( 'focused' );
						  if(checker == true){
							$div3.closest('div.form-row').addClass('is-active');						
						  }else{						  
							var checker2 = attributeValue.includes( 'empty' );						  
							if(checker2 == true){	
							  $div3.closest('div.form-row').removeClass('is-active');
							}						  
						  }
						}
					  });
					});
					
					observer.observe($div3[0], {
					  attributes: true
					});
					
				} );
			}
			
			// Margin botton on hiden inputs
			if($('.input-hide-yes')){
				
				$('.input-hide-yes').prev('div.form-row-wide').addClass('bew-last-child');
				
				var frl = $('.input-hide-yes').prev('div.form-row-last');
				
					frl.addClass('bew-last-child');
					frl.prev('div.form-row-first').addClass('bew-last-child');
			}
																
        };
		
		var CheckoutInputShipping = function() {
						
			$('.bew-checkout .bew-shipping select').each( function () {
				
				var id = $(this).attr('id'),
					id_split = id.split('_'),
					newID = id.replace(id_split[0], "billing");
							
					$("#" + newID  ).val($(this).val());
				
			});	
			
			if($('#shipping_state').is("input")){		
				$("#billing_state").val($("#shipping_state").val());
			}
			
			$('.bew-checkout').on('keyup change','.bew-information input, .bew-shipping input',  function(){
				
				if ($('.shipping-checkbox-input-b').is(':checked')) {
				
					var id = $(this).attr('id'),
						id_split = id.split('_'),
						newID = id.replace(id_split[0], "billing");						
							
						$("#" + newID  ).val($(this).val());
						$("#" + newID  ).parents('div.form-row.label-inside-yes').addClass('is-active');
				}		
				
			});
			
			$('.bew-checkout').on('change','.bew-shipping select',  function(){
				var id = $(this).attr('id'),
					id_split = id.split('_'),
					newID = id.replace(id_split[0], "billing");	
					
					$("#" + newID).val($(this).val()).change();					
			});
					
			$('.shipping-checkbox-input-b').on('click',function () {				
                if ($('.shipping-checkbox-input-b').is(':checked')) {
					setTimeout(function(){
						$('.billing-checkbox-fields .bew-billing .bew-components-checkout-step__content input:not([type=radio]), .billing-checkbox-fields .bew-billing .bew-components-checkout-step__content select').each( function () {
							
							var id = $(this).attr('id'),
							id_split = id.split('_'),
							newID = id.replace(id_split[0], "shipping");
							
							var sv =  $("#" + newID  ).val();						
							$(this).val(sv);
							
							if($(this).val().length){
								$(this).parents('div.form-row.label-inside-yes').addClass('is-active');
								//$(this).parents('p.form-row.label-inside-yes').addClass('is-active');
							}
							//Remove invalid class
							$(this).parents('div.form-row').removeClass('woocommerce-invalid');
						});
					},500);
                	        	
                }else{
                   	$('.billing-checkbox-fields .bew-billing .form-row:not(.field-conditional-yes) input:not([type=radio])').each( function () {
						var $this = $(this);
						$this.parents('p.form-row.label-inside-yes').removeClass('is-active');
						$this.parents('div.form-row.label-inside-yes').removeClass('is-active');
						$this.val("");						
						
                	});
				}
			});
		
		};
		
		var CheckoutUpdateBilling = function() {
						
			$('.bew-checkout .bew-shipping select').each( function () {
				
				var id = $(this).attr('id'),
					id_split = id.split('_'),
					newID = id.replace(id_split[0], "billing");
							
					$("#" + newID  ).val($(this).val());
				
			});	
			
			if($('#shipping_state').is("input")){		
				$("#billing_state").val($("#shipping_state").val());
			}
			
			$('.bew-information input, .bew-shipping input').each( function () {
				
				if ($('.shipping-checkbox-input-b').is(':checked')) {
				
					var id = $(this).attr('id');
						if(typeof id  != 'undefined') {
							var id_split = id.split('_'),
							    newID = id.replace(id_split[0], "billing");
								$("#" + newID  ).val($(this).val());
								//$("#" + newID  ).parents('div.form-row.label-inside-yes').addClass('is-active');
						}
				}		
				
			});
		
		};
		
		var CheckoutShipping = function() {				
			
			if( typeof checkoutShipping != 'undefined' && checkoutShipping) {	
				var default_checked  = checkoutShipping.default_checked,
					default_checkedb = checkoutShipping.default_checkedb;				
			}
		
			if ( 'yes' != default_checked ) {
				$(window).load(function() {					
					$('.bew-shipping').hide();
				});						
			}
			
			if ( 'yes' == default_checked ) {				
				$( '#ship-to-different-address' ).find( 'input' ).prop('checked', true);
				$( '#ship-to-different-address' ).hide();
			}
                	
			$('.shipping-checkbox-area').on('click',function () {
                if ($('.shipping-checkbox-input').is(':checked')) {
                   	$( '#ship-to-different-address' ).find( 'input' ).prop('checked', true); 
					$('.bew-shipping').slideDown();
                }else{
                   	$( '#ship-to-different-address' ).find( 'input' ).prop('checked', false); 
					$('.bew-shipping').slideUp();				    
                }
            });
						
			// Use-address-for-billing
			if ( 'yes' == default_checkedb ) {				
				$(".woocommerce-checkout").addClass('use-address-for-billing-yes');	
				setTimeout(function(){
					//console.log("run");
					$('.elementor-widget-woo-checkout-form-shipping').next('.elementor-widget-woo-checkout-form-billing').addClass('billing-checkbox-fields').hide();
					$('.bew-checkout .bew-checkout-step-title').addClass('counter-update');
					$(".bew-checkout .woocommerce-checkout").addClass('reset-initial');	
				},600);					
			}
                	
			$('.shipping-checkbox-input-b').on('click',function () {
				
                if ($('.shipping-checkbox-input-b').is(':checked')) {
					
					setTimeout(function(){
						
							$('.elementor-widget-woo-checkout-form-shipping').next('.elementor-widget-woo-checkout-form-billing').slideUp();							
							setTimeout(function(){								
								$('.bew-checkout .bew-checkout-step-title').addClass('counter-update-2');
							},450);							
	
					},300);							
                }else{
					setTimeout(function(){                	        	
						$('.elementor-widget-woo-checkout-form-shipping').next('.elementor-widget-woo-checkout-form-billing').slideDown();
						$('.bew-checkout .bew-checkout-step-title').removeClass('counter-update-2');						
					},300);
                }
				
            });			
		
		
        };
		
		var CheckoutShippingOptions = function() {	
			
			if( $('.bew-shipping-options-totals').length != 0 ) {
				
				$('.bew-checkout').on('update_order_review', function () {					
					//console.log("hola");											
				});					
			}		
		
        };
		
		var CheckoutOrderReview = function() {	
						
			$('.bew-checkout').on('updated_checkout', function(){

				// Update price on title
				var total_price = $('.bew-components-totals-footer-item .amount').html();
					if(total_price){
						$('.total-title .amount').html(total_price);
					}	
				// Update floating labels
				CheckoutInputLabels();
				
				// Update Billings fields
				CheckoutUpdateBilling();
			});	
		
        };
		
		var CheckoutPayment = function() {	
			
			$('.bew-checkout').on('click', '.bew-nav-link', function(){					
				$(this).parent().find('input').click();
			});
											
        };

		var CheckoutPaymentTab = function() {
					
			var previousActiveTabIndex = $(".bew-nav-link.active").parent().data("tab-index");
						
			$(".bew-checkout").on('click keypress', '.tab-switcher', function (event) {
				// event.which === 13 means the "Enter" key is pressed
				
				if ((event.type === "keypress" && event.which === 13) || event.type === "click") {
					
					var tabClicked = $(this).data("tab-index"),
						tabClickedElement = $(this);
										
					if(tabClicked != previousActiveTabIndex) {
						$("#allTabsContainer .tab-container").each(function () {
							if($(this).data("tab-index") == tabClicked) {
								$(".tab-container").hide();								
								$(this).show();
								
								$(".bew-nav-link").removeClass('active');
								$(".bew-nav-link", tabClickedElement).addClass('active');
								
								previousActiveTabIndex = $(this).data("tab-index");
								return;
							}
						});
					}
				}
			});


		};
		
		var Bew_checkout_coupons = {
			init: function() {
				
				$( document.body ).on( 'click', 'form.bew-checkout_coupon .button', this.apply_coupon  );
				$( document.body ).on( 'click', '.bew-remove-coupon', this.remove_coupon );
				
			},		
			apply_coupon: function( e ) {				
				e.preventDefault();
				
				var $form = $( '.bew-checkout_coupon');

				if ( $form.is( '.processing' ) ) {
					return false;
				}

				$form.addClass( 'processing' ).block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});

				var data = {
					security:		wc_checkout_params.apply_coupon_nonce,
					coupon_code:	$form.find( 'input[name="coupon_code"]' ).val()
				};

				$.ajax({
					type:		'POST',
					url:		wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'apply_coupon' ),
					data:		data,
					success:	function( code ) {
						$( '.woocommerce-error, .woocommerce-message' ).remove();
						$form.removeClass( 'processing' ).unblock();
						
						if ( code ) {
							//$( 'form.woocommerce-checkout').before( code );
							$ ('.woocommerce-checkout-review-order').removeClass('show-coupon');

							$( document.body ).trigger( 'applied_coupon_in_checkout', [ data.coupon_code ] );
							$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );							
							
							var content_holder = code.replace(/<(?:.|\n)*?>/gm, '');
							
							setTimeout(function(){
								Snackbar.show({ showAction: false, 
											pos: 'bottom-left' , 
											text: content_holder });
							},2000);
						}
					},
					dataType: 'html'
				});

				return false;
			},
			remove_coupon: function( e ) {
				e.preventDefault();

				var container = $( this ).parents(  '.bew-components-totals-discount__coupon-list li' ),
					coupon    = $( this ).data( 'coupon' );

				container.addClass( 'processing' ).block({
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				});

				var data = {
					security: wc_checkout_params.remove_coupon_nonce,
					coupon:   coupon
				};

				$.ajax({
					type:    'POST',
					url:     wc_checkout_params.wc_ajax_url.toString().replace( '%%endpoint%%', 'remove_coupon' ),
					data:    data,
					success: function( code ) {
						$( '.woocommerce-error, .woocommerce-message' ).remove();
						//container.removeClass( 'processing' ).unblock();

						if ( code ) {
							//$( 'form.woocommerce-checkout' ).before( code );

							$( document.body ).trigger( 'removed_coupon_in_checkout', [ data.coupon_code ] );
							$( document.body ).trigger( 'update_checkout', { update_shipping_method: false } );

							// Remove coupon code from coupon field
							$( 'form.checkout_coupon' ).find( 'input[name="coupon_code"]' ).val( '' );
							
							var content_holder = code.replace(/<(?:.|\n)*?>/gm, '');
														
							setTimeout(function(){
								Snackbar.show({ showAction: false, 
											pos: 'bottom-left' , 
											text: content_holder });
							},2000);
						}
					},
					error: function ( jqXHR ) {
						if ( wc_checkout_params.debug_mode ) {
							/* jshint devel: true */
							console.log( jqXHR.responseText );
						}
					},
					dataType: 'html'
				});
			}
		};
		
		var BewCouponsWidget = function() {
						
			$(".bew-checkout").on('click', '.bew-coupon-input button', function (e) {
				e.preventDefault();
				//console.log("hola");
				
				var $i = $(".bew-coupon-input").find( 'input[name="bew_coupon_code"]' ).val();
				
				$('.bew-checkout_coupon').find( 'input[name="coupon_code"]' ).val($i);				
				$('.bew-checkout_coupon button').click();
	
			});

		};
				
		var MultiStepCheckout = function() {	
			"use strict"
			
			$('.elementor-widget-woo-checkout-express-request').addClass('step step-information');
			$('.elementor-widget-woo-checkout-form-information').addClass('step step-information');
				
            if ( ($('.shipping-checkbox-area-b').length > 0) || $('.elementor-widget-woo-checkout-form-shipping').hasClass('dont-need-shipping-yes' ) ) {			
				$('.elementor-widget-woo-checkout-form-shipping').addClass('step step-information');
			} else {
				$('.elementor-widget-woo-checkout-form-shipping').addClass('step step-shipping-option');
			}
			
			$('.elementor-widget-woo-checkout-form-billing').addClass('step  step-information');
			
			$('#shipping-checkbox-b').on('click',function () {
                if (!$('.shipping-checkbox-input-b').is(':checked')) {			
					$('.elementor-widget-woo-checkout-form-billing').addClass('step step-information');
				}
			});
			
			$('.elementor-widget-woo-checkout-shipping-options').addClass('step step-shipping-option');
			$('.elementor-widget-woo-checkout-payment').addClass('step step-payment');
			$('.elementor-widget-woo-checkout-place-order').addClass('step step-payment');
			
			if($('.elementor-widget-woo-checkout-form-additional').hasClass('step-default')) {
				$('.elementor-widget-woo-checkout-form-additional').addClass('step-shipping-option');
			}

			var body                = $( 'body' ),
				login               = $( '#checkout_login' ),
				billing             = $( '.elementor-widget-woo-checkout-form-billing' ),
				information         = $( '.step-information' ),
				shipping            = $( '.elementor-widget-woo-checkout-form-shipping' ),
				shipping_option     = $( '.step-shipping-option' ),
				order               = $( '#order_review' ),
				payment             = $( '.step-payment' ),
				form_actions        = $( '#form_actions' ),
				prev                = form_actions.find( '.button.prev' ),
				next                = form_actions.find( '.button.next' ),
				coupon              = $( '#checkout_coupon' ),				
				payment_method      = function () {

					$( '#place_order' ).on( 'click', function () {
						var $this               = $( '#order_checkout_payment' ).find( 'input[name=payment_method]:checked' ),
							current_gateway     = $this.val(),
							order_button_text   = $this.data( 'order_button_text' );

						order.find( 'input[name="payment_method"]' ).val( current_gateway ).data( 'order_button_text', order_button_text ).attr( 'checked', 'checked' );
					} );

				};
				
				if(information.hasClass('dont-need-shipping-yes')){
					var steps = new Array( login, information, payment );
				}else {
					var steps = new Array( login, information, shipping_option, payment );
				}

				var ns = steps.length;			

			body.on( 'updated_checkout', function( e ) {
								
				steps[ns] = $( '#order_checkout_payment' );
				if ( e.type == 'updated_checkout' ) {
					steps[ns] = $( '#order_checkout_payment' );
				}

				$( '#order_checkout_payment' ).find( 'input[name=payment_method]' ).on( 'click', function() {

					if ( $( '.payment_methods input.input-radio' ).length > 1 ) {
						var target_payment_box = $( 'div.payment_box.' + $( this ).attr( 'ID' ) );

						if ( $( this ).is( ':checked' ) && ! target_payment_box.is( ':visible' ) ) {
							$( 'div.payment_box' ).filter( ':visible' ).slideUp( 250 );

							if ( $( this ).is( ':checked' ) ) {
								$( 'div.payment_box.' + $( this ).attr( 'ID' )).slideDown( 250 );
							}
						}
					} else {
						$( 'div.payment_box' ).show();
					}

					if ( $( this ).data( 'order_button_text' )) {
						$( '#place_order' ).val( $( this ).data( 'order_button_text' ) );
					} else {
						$( '#place_order' ).val( $( '#place_order' ).data( 'value' ) );
					}
				} );

			} );

			form_actions.find( '.button.prev' ).add( '.button.next' ).on( 'click', function( e ) {
				
				e.preventDefault();
				
				var $this           = $( this ),
					timeline        = $( '#bew-checkout-timeline' ),
					action          = $this.data( 'action' ),
					current_step    = form_actions.data( 'step' ),
					next_step       = parseInt(current_step) + 1,
					prev_step       = parseInt(current_step) - 1,					
					checkout_form   = $( 'form.woocommerce-checkout' ),
					is_logged_in    = checkouTimeline.is_logged_in,
					button_title,
					valid           = false,
					current_step_item = steps[current_step],
					selector        = current_step_item,
					posted_data     = {},
					type            = '',
					$offset         = 0,
					$adminBar       = $( '#wpadminbar' ),
					$stickyTopBar   = $( '#top-bar-wrap' ),
					$stickyHeader   = $( '#site-header' );
					
					$offset = $offset + $adminBar.height() + $stickyTopBar.height() + $stickyHeader.height();

				$( 'form.woocommerce-checkout .woocommerce-NoticeGroup.woocommerce-NoticeGroup-checkout' ).remove();
				
				//type = ($( selector ).hasClass( 'elementor-widget-woo-checkout-form-billing' ) ) ? 'billing' : type;
				//type = ($( selector ).hasClass( 'elementor-widget-woo-checkout-form-shipping' )) ? 'shipping' : type;				
				type = ($( selector ).hasClass( 'step-information' )) ? 'information' : type;
				
				//console.log(type);
				if("1" == "1"){
											
					if(selector.hasClass( 'step-information' )){
						//console.log("info");
						validate_fields("information") && after_validation(timeline,form_actions,checkout_form,steps,action,next_step,current_step,prev_step,prev,next,$offset,is_logged_in,coupon);
					}else if((selector.hasClass( 'step-shipping-option' )) && (selector.find( '.validate-required' ).length > 0) && (action == 'next') ){		
						
						//console.log("shipping-option");
						validate_fields("shipping-option") && after_validation(timeline,form_actions,checkout_form,steps,action,next_step,current_step,prev_step,prev,next,$offset,is_logged_in,coupon); 
					} else{
						//console.log("no-validation");
						after_validation(timeline,form_actions,checkout_form,steps,action,next_step,current_step,prev_step,prev,next,$offset,is_logged_in,coupon);
					}
					
				}else {				
					if ( type == 'billing' || type == 'shipping' || type == 'information') {
						
						$( selector ).find( '.validate-required input' ).each( function() {
							posted_data[ $( this ).attr( 'name' ) ] = $( this ).val();
						} );

						$( selector ).find( '.validate-required select' ).each( function() {
							posted_data[ $( this ).attr( 'name' ) ] = $( this ).val();
						} );

						$( selector ).find( '.input-checkbox' ).each( function() {
							if ( $( this ).is( ':checked' ) ) {
								posted_data[ $( this ).attr( 'name' ) ] = $( this ).val();
							}
						} );
						
						if(type == 'information'){
							posted_data[ 'type_group' ] = 'information';
							posted_data[ 'ship_to_different_address' ] = true;
						   
						   if ($('.shipping-checkbox-input-b').is(':checked')) {	
							posted_data[ 'use_address_for_billing' ] = true;
						   }
						}

						var data = {
							action: 'bew_validate_checkout',
							type: type,
							posted_data: posted_data
						};

						$.ajax( {
							type: 'POST',
							url: checkouTimeline.ajax_url,
							data: data,
							success: function( response ) {
								valid = response.valid;

								if ( ! response.valid ) {
									$( 'form.woocommerce-checkout' ).prepend( response.html );
									$( 'html, body' ).animate( {
										scrollTop: $( 'form.woocommerce-checkout' ).offset().top - $offset },
									'slow' );
								}
								else{
									after_validation(timeline,form_actions,checkout_form,steps,action,next_step,current_step,prev_step,prev,next,$offset,is_logged_in,coupon);                    }
							},
							complete: function() {}
						} );

					} else {
						valid = true;
						after_validation(timeline,form_actions,checkout_form,steps,action,next_step,current_step,prev_step,prev,next,$offset,is_logged_in,coupon);
						console.log("direct");
					}
				}

			} );
		
			// Click on review shipping and payment block		
			$( '.bew-formReview-action a' ).on( 'click', function(e) {
				
				//$('.button.prev').click();
				e.preventDefault();	
				
				var $this           = $( this ),
					timeline        = $( '#bew-checkout-timeline' ),
					current_step    = form_actions.data( 'step' ),
					prev_step       = current_step - 1,																		
					$offset         = 0,
					s				= '',
					t 				= $this.attr("data-target");					
										
					go_to_step(timeline,form_actions,steps,current_step,next,prev,$offset,t,s);
					
			} );
			
			// Click on timeline
			if ($("#form_actions .rc-layout").length != 0){
				
				$(".bew-multistep-timeline").addClass("is-clickable");				
				
				$( '.timeline:not(".cart")' ).on( 'click', function(e) {
					
					e.preventDefault();	
					
					var $this           = $( this ),
						timeline        = $( '#bew-checkout-timeline' ),
						current_step    = form_actions.data( 'step' ),																	
						$offset         = 0,
						t               = '',
						s 				= $this.attr("data-step");
						
						
						if("error"==""){	
							if(current_step != 1) {
								go_to_step(timeline,form_actions,steps,current_step,$offset,t,s);						
							}						
							if( (current_step == 1) && (s == 2) ) {						
								validate_fields("information") && $('.button.next').click();						
							}
						}
						
						validate_fields("information") && go_to_step(timeline,form_actions,steps,current_step,next,prev,$offset,t,s);
						
				} );
				
				$( '.timeline.cart' ).on( 'click', function(e) {
					window.location = $('a', this).attr('href');
					return false;
						
				} );
			}
			
			// Change Dynamic text on actions buttons for cr_layout	
			if ($("#form_actions .rc-layout").length != 0){
				//console.log()
				var rc_current_step = form_actions.data( 'step' ),
					rc_next_step = parseInt(rc_current_step) + 1,
					rc_prev_step = parseInt(rc_current_step) - 1,
					prefix_next = $("#form_actions .next").attr('data-text-cr'),
					prefix_prev = $("#form_actions .prev").attr('data-text-cr'),
					step_name_next = $("#timeline-" + rc_next_step).attr('data-step-name'),
					step_name_prev = $("#timeline-" + rc_prev_step).attr('data-step-name');
							
				// Next title
				if ( rc_current_step == 1 ) {
					next.find('span').text( prefix_next + " " + step_name_next );
				}			
			}

		};
		
		var validate_email_fields = function () {
			 		
			var hasError = false;
			var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,6})?$/;
			 
			var emailaddressVal = $("#billing_email").val();
			
			// For Information
			var error_email_validation = checkoutInformation.error_email_validation;			
					
			if(!emailReg.test(emailaddressVal)) {
				
				if($("._invalid-error.email-verification").length == 0 ){				
					$('<span class="_invalid-error email-verification">' + error_email_validation + '</span>').insertAfter($("#billing_email_field .woocommerce-input-wrapper"));
				}
				hasError = true;
			} 
			 
			if(hasError == true) { return false; } else { return true; }

        };
		
		var validate_fields = function (e) {
            var t = [];
			
			if ($('.shipping-checkbox-input').is(':checked')) {
								
				if ($('.shipping-checkbox-input-b').is(':checked')) {
					
				   var	i = $(".step.step-" + e + ":not('.elementor-widget-woo-checkout-form-billing')" + " .validate-required");
				   
				} else {
					
				   var	i = $(".step.step-" + e + " .validate-required");	
				   
				}
				
			}else{							
				
				var	i = $(".step.step-" + e + ":not('.elementor-widget-woo-checkout-form-shipping')" + " .validate-required");						
			}
            //console.log(i.length);    
            $("._invalid-error").remove();
						
			if (i.length != 0){
				for (var o = 0; o < i.length; o++) {
					var n = i[o],
						s = $("input, textarea, select", n),
						sId =  s.attr('id').split('_'),
						sIdp =  sId[0];
												
						//console.log(s);					
					if (("" == s.val()) || ("default" == s.val()) || ((s.attr('type') == 'checkbox') && (!s.is(':checked')) )  ) {										
						
						var c = !0;
						
						//Account Validate
						if (s.closest(".create-account").hasClass('is-reg-req')) {							
							var a = !0;							
						} else {							
							var a = !1;
						}
						
						//Custom error text
						if (s.attr('id') == "billing_email" ) {
							// For Information
							var error_required = ( typeof checkoutInformation != 'undefined' ? checkoutInformation.error_information_required : checkoutBilling.error_billing_required );
							
						} else if (s.attr('id') == "account_password") {
							//For Account Password on Information
							var error_required = ( typeof checkoutInformation != 'undefined' ? checkoutInformation.error_information_required : checkoutBilling.error_billing_required );
						
						} else if (sIdp == "shipping") {
							//For Shipping
							var error_required = checkoutShipping.error_shipping_required;
						
						} else if(sIdp == "billing") {
							//For Billing
							var error_required = checkoutBilling.error_billing_required;
						
						} else {
							var error_required = checkoutBilling.error_billing_required;	
						}
						
						//Validate Step
						$("input#createaccount").prop("checked") || (s.closest(".create-account").length && (c = a)),
							c && ($(n).addClass("woocommerce-invalid-required-field bew-woocommerce-invalid woocommerce-invalid"), $('<span class="_invalid-error">' + error_required + '</span>').insertAfter($(".woocommerce-input-wrapper", n)), t.push("1"));

					} else if ((s.attr('type') == 'email')) {
												
						if (validate_email_fields() == false){							
							validate_email_fields(), t.push("1");
						}						
					}
					
					if (i.length - 1 === o) return !t.length;
				}
			} else{
			 //console.log("pass");	
			 return true;
			}			
        };
		
		var go_to_step = function (timeline,form_actions,steps,current_step,next,prev,$offset,t,s) {
			
			timeline.find( '.active' ).removeClass( 'active' );
						
			//Complete contact and ship to information
			update_form_reviews();
						
			//console.log(t);
			//console.log(current_step );		
			
			if (t != ''){
				var position = 0;
				$.each(steps, function(i) {
				if ($(this).hasClass(t) == true) { position = i; }
				});
			}
			
			if (s != ''){ 
				var position = s;
			}
			
			//console.log (position);			
			form_actions.data( 'step', position );
						
            steps[current_step].fadeOut( 0, function() {
				//console.log(current_step);	

				if ($('.shipping-checkbox-input-b').is(':checked')) {	
					steps[position].not('.billing-checkbox-fields').fadeIn( 120 );
				} else {
					steps[position].fadeIn( 120 );	
				}
					
				steps[position].not('.billing-checkbox-fields').fadeIn( 120 );		
											
				// Continue & Return layout
				if( $('.rc-layout').length ){
					update_cr_actions(form_actions,next,prev,steps);
				} else{
					// Information step
					if( position == 1 ){
						form_actions.find( '.button.prev' ).fadeOut( 0 );
						form_actions.find( '.button.next' ).fadeIn( 0 );
					} else {
						form_actions.find( '.button.next' ).fadeIn( 0 );
						form_actions.find( '.button.prev' ).fadeIn( 0 );
					}
					
					// Last step			
					if( position == 3 ){
						form_actions.find( '.button.next' ).fadeOut( 0 );
					} 					
				}
								
            } );
			
            $( '#timeline-' + position ).toggleClass( 'active' );
            //$( 'html, body' ).animate( {
            //    scrollTop: $offset },
            // 'slow' );			
			
        };
		
		var after_validation = function( timeline,form_actions,checkout_form,steps,action,next_step,current_step,prev_step,prev,next,$offset,is_logged_in,coupon ) { 
               timeline.find( '.active' ).removeClass( 'active' );
			console.log("cu-a" + current_step);
            if ( action == 'next' ) {
				//console.log(next_step),
                form_actions.data( 'step', next_step );
                steps[current_step].fadeOut( 0, function() {
                    steps[next_step].fadeIn( 120 );
                } );

                $( '#timeline-' + next_step ).toggleClass( 'active' );
				
				//Complete contact and ship to information
				update_form_reviews();
				//console.log($( '#bew-checkout-timeline' ).offset().top);
                $( 'html, body' ).animate( {
                    //scrollTop: $( '#bew-checkout-timeline' ).offset().top - $offset },
					scrollTop: 0 },
                'slow' );
            } else if ( action == 'prev' ) {
                form_actions.data( 'step', prev_step );
                steps[current_step].fadeOut( 0, function() {
					
					if ($('.shipping-checkbox-input-b').is(':checked')) {						
						steps[prev_step].not('.billing-checkbox-fields').fadeIn( 120 );
					} else {
						steps[prev_step].fadeIn( 120 );
					}
					
                } );

                $( '#timeline-' + prev_step ).toggleClass( 'active' );
                //$( 'html, body' ).animate( {
                //    scrollTop: $offset },
                //'slow' );
            }

            current_step = form_actions.data( 'step' );
						
            if ( ( current_step == 1
                    && is_logged_in == true ) ||
                ( is_logged_in == false
                    && ( ( current_step == 0
                        && checkouTimeline.login_reminder_enabled == 1 )
                    ||  ( current_step == 1
                        && checkouTimeline.login_reminder_enabled == 0 ) ) ) ) {
                prev.fadeOut( 0 );
            } else {
                prev.fadeIn( 0 );
            }
				
			// Continue & Return layout	
			if( $('.rc-layout').length ){
				update_cr_actions(form_actions,next,prev,steps);
			}			
		}
					
		var update_cr_actions = function (form_actions,next,prev,steps) {
					
			var rc_current_step = form_actions.data( 'step' ),
				rc_next_step = parseInt(rc_current_step) + 1,
				rc_prev_step = parseInt(rc_current_step) - 1,
				prefix_next = $("#form_actions .next").attr('data-text-cr'),
			    prefix_prev = $("#form_actions .prev").attr('data-text-cr'),
				step_name_next = $("#timeline-" + rc_next_step).attr('data-step-name'),
			    step_name_prev = $("#timeline-" + rc_prev_step).attr('data-step-name');
			
			// Next title
			if ( rc_current_step != (steps.length-2) ) {
				if ( rc_current_step == 1 ) {
					next.find('span').text( prefix_next + " " + step_name_next );
					next.fadeIn( 0 );
					$(".back-to-cart").show();
				} else {								
					next.find('span').text( prefix_next + " " + step_name_next );
					next.fadeIn( 0 );
					$(".back-to-cart").hide();
				} 
			} else {
				next.fadeOut( 0 );
				$(".back-to-cart").hide();			
			}
				
			// Prev title
			if ( rc_current_step != (steps.length-2) ) {
				if ( rc_current_step == 1 ) {
					prev.fadeOut( 0 );
				} else {
					prev.find('span').text( prefix_prev + " " + step_name_prev );
					prev.fadeIn( 0 );
				} 
			} else {
				prev.fadeOut( 0 );
			}
		};
		 
		var update_form_reviews = function () {
            $(".bew-formReview-block").each(function (e, t) {
                var i = $(t),
                    o = $(".bew-formReview-content", i),
                    n = o.attr("data-fill");
                if ("email" === n) {
                    var s = $("#billing_email");
                    s.length && o.text(s.val());
                } else if ("address_ship" === n) {
                    					
					if ($('.shipping-checkbox-input').is(':checked')) {
						var c = [];
						$.each(["shipping_address_1", "shipping_city", "shipping_state", "shipping_postcode", "shipping_country"], function (e, t) {
							var i = $("#" + t);
							i.length && c.push(i.val());
						}),
							o.text(c.join(", "));
					} else {
						var a = [];
						$.each(["billing_address_1", "billing_city", "billing_state", "billing_postcode", "billing_country"], function (e, t) {
							var i = $("#" + t);
							i.length && a.push(i.val());
						}),
							o.text(a.join(", "));
					}
                } else if ("address_bill" === n) {
                    var a = [];
                    $.each(["billing_address_1", "billing_city", "billing_state", "billing_postcode", "billing_country"], function (e, t) {
                        var i = $("#" + t);
                        i.length && a.push(i.val());
                    }),
                        o.text(a.join(", "));
                } else if ("method" === n) {
                    var r = $("#shipping_method input[type='radio']:checked");
                    r.length || (r = $("#shipping_method input[type='hidden']")), r.length && o.html(r.next("label").html());
                }
            });
        };
		
		var wc_checkout_login_form = {
			init: function() {
				$( document.body ).on( 'click', 'a.showlogin', this.show_login_form );
				
			},
			show_login_form: function() {
				
				$( '.layout-collapse' ).removeClass("initial-hide");
				$( 'form.login, form.woocommerce-form--login' ).slideToggle();
				return false;
			}
		};
		
		var CheckoutConditional  = function () {
			
			$('.bew-checkout .field-conditional-yes').each( function () {
				
				var $this = $(this),
					conditional = $('input, select, textarea, div', this).attr('conditional'),
					superior_field = $('input, select, textarea, div', this).attr('superior_field'),
					sf_option = $('input, select, textarea, div', this).attr('superior_field_option'),
					sf_field_selector = '#' + superior_field + '_field input',
					sf_option_selector = '#' + superior_field + '_' + sf_option;
															
					if( typeof conditional != 'undefined' && conditional) {	
												
						if($this.length && $( '#' + superior_field + '_field ' + sf_option_selector).is(':checked')) {							
							$this.addClass("field-conditional-show");
							$this.find('input:not([type=hidden])').val("");
						} else {
							$this.find('input:not([type=hidden])').val("Not applicable");
						}
						
						$('#shipping-checkbox-b').on('click',function () {
							if ($('.shipping-checkbox-input-b').is(':checked')) {
								setTimeout(function(){
									$this.find('input:not([type=hidden])').val("Not applicable");
								},500);														
							}else{							
								$this.find('input:not([type=hidden])').val("");							
							}
						});	
												
						$( '.bew-checkout' ).on( 'change',  sf_field_selector,  function() {
							
							if ($( '#' + superior_field + '_field ' + sf_option_selector).is(':checked')) {								
								$this.addClass("field-conditional-show");
								$this.find('input:not([type=hidden])').val("");
								$this.removeClass('is-active');								
							}else {
								$this.removeClass("field-conditional-show");
								$this.find('input:not([type=hidden])').val("Not applicable");
							}
					
						});						
						
					}
									
			});	

		};
		
		var Bew_validate_fields = function () {
			
			$(document.body).on('checkout_error', function () {
				
				var t = [],
                i = $(".form-row.validate-required");
				
				$("._invalid-error").remove();
				//$(".woocommerce-error").remove();
				
				// Check required validation				
				for (var o = 0; o < i.length; o++) {
					var n = i[o],
						s = $("input, textarea, select", n);
						console.log(s);
					var	sId =  s.attr('id').split('_'),
						sIdp =  sId[0];
										
					if (s.attr('id') == "billing_email" ) {
						// For Information
						var error_required = ( typeof checkoutInformation != 'undefined' ? checkoutInformation.error_information_required : checkoutBilling.error_billing_required );
							
					} else if (s.attr('id') == "account_password") {
						//For Account Password on Information
						var error_required = ( typeof checkoutInformation != 'undefined' ? checkoutInformation.error_information_required : checkoutBilling.error_billing_required );
						
					}  else if (sIdp == "shipping") {
						//For Shipping
						var error_required = checkoutShipping.error_shipping_required;
					
					} else if(sIdp == "billing") {
						//For Billing
						var error_required = checkoutBilling.error_billing_required;
					
					} else {
						var error_required = checkoutBilling.error_billing_required;	
					}
					
					if (("" == s.val()) || ("default" == s.val()) ) {
						//console.log(s.val());
						var c = !0;
						$("input#createaccount").prop("checked") || (s.closest(".create-account").length && (c = !1)),
							c && ($(n).addClass("woocommerce-invalid-required-field bew-woocommerce-invalid woocommerce-invalid"), $('<span class="_invalid-error bew_error_required">' + error_required + "</span>").insertAfter($(".woocommerce-input-wrapper", n)), t.push("1"));
					}
										
					//if (i.length - 1 === o) return !t.length;
				};
				
				// check validation	for invalid format					
				$('.woocommerce-error li').each(function() {
					
					var data_id = $(this).attr('data-id');
					
					if( typeof data_id != 'undefined' && data_id) {	
					
						var data_id  = $(this).attr('data-id') + "_field",
							data_id_name = $(this).attr('data-id').split('_').pop(),
							data_text = '<span class="_invalid-error bew_error_required">' + checkoutBilling.error_billing_validation + " " + data_id_name + "</span>";
											
						if( $("#" + data_id + " .bew_error_required").length == 0 ){		
							$("#" + data_id).append( data_text );
						}
						
					} else{
						
						var data_text          = $(this).text().replace('billing','contact'),
							data_text_span     = '<span class="_invalid-error bew_error_required">' + data_text + '</span>', 
							error_email 	   = ( typeof checkoutInformation != 'undefined' ? checkoutInformation.error_information_required : checkoutBilling.error_billing_mail_validation );
						var	data_text_span_std = '<span class="_invalid-error bew_error_required">' + error_email + '</span>';
							
						// check if email is valid on contact information
						if(data_text.includes("email")){
							if( $("#billing_email_field .bew_error_required").length == 0 ){
								$("#billing_email_field").addClass("woocommerce-invalid-email bew-woocommerce-invalid-email woocommerce-invalid");
								$("#billing_email_field").append( data_text_span );
							}
						} else {
							if( $("#billing_email_field .bew_error_required").length == 0 ){		
								$("#billing_email_field").addClass("woocommerce-invalid-email bew-woocommerce-invalid-email woocommerce-invalid");
								$("#billing_email_field").append( data_text_span_std );
							}
						}
						
					}
					
				});
				
				//$(".woocommerce-error").remove();				
						
			});
            
        };
		
		var BewexpressCheckout = function () { 
		
			if ( $('.bew-checkout-yes').hasClass( 'bew-checkout-fast-yes' ) ) {	
				
				$body.addClass("bew-fast-checkout");
			}	
						
			// Express Checkout
			if ($(".express-checkout-name-yes").length > 0){
				//Get label first name			
				$('#billing_first_name').each(function() { 
				   var $this = $(this),
					   label = $('label[for="'+ $this.attr('id') +'"]'),
					   label_text = $('.bew-billing').attr("data-bill-name"); 
					   
				   if ((label.length > 0) && ($this.val() == '' ) ) {				   
					   label.text(label_text);
				   }
				});
				
				//Bew Fast Checkout if focus first name
				$('.bew-checkout').on('focus','#billing_first_name',  function(){
									
					$(this).parents('div.form-row').addClass('is-focused');
					$(this).parents('p.form-row').addClass('is-focused');
									
					var label = $("label[for='" + $(this).attr('id') + "']"),
						label_text = $('.bew-billing').attr("data-bill-first-name"); 
						
						if ((label.length > 0) && ($(this).val() == '' ) ) {				   
							label.text(label_text);
						}								
				});
			
			}

			//Bew Fast Checkout if focus on card number
			$('.bew-checkout').on('focus','#wc-stripe-cc-form .form-row-wide',  function(){								
				$(this).addClass('is-focused');
			});
			
			if ($("#wc-stripe-express-checkout-element").length > 0){
			// check if express checkout buttons is displayed
			var currentStyle = $("#wc-stripe-express-checkout-element").css("display");
			console.log("Initial display: ", currentStyle);			

				var $div = $("#wc-stripe-express-checkout-element");
				var observer = new MutationObserver(function(mutations) {
				  mutations.forEach(function(mutation) {
					if (mutation.attributeName === "style") {
					  var attributeValue = $(mutation.target).prop(mutation.attributeName);						
						if (attributeValue.display != currentStyle) {
							  console.log("Display changed to: ", $("#wc-stripe-payment-request-wrapper").css("display"));
							  setTimeout(function(){
								  $(".bew-checkout-express-buttons").removeClass("bew-checkout-express-loading");
								  $(".alternative-payment-separator").removeClass("bew-checkout-express-loading");
							  },300);
						}
					}
				  });
				});
				
				observer.observe($div[0], {
				  attributes: true
				});
			}
				
		};

		var bew_order_bump = {
			wc_ajax_url: '',
			i18n_unavailable_text: '',
			i18n_make_a_selection_text: '',
			$wrap: '',
			init: function () {
				let self = this;
				//console.log(self);
				if (self.$wrap.find('.bew-ob-product-wrap').length) {
					self.design();
				}
				jQuery(document.body).on('updated_checkout', function (e, data) {
					self.$wrap = jQuery('.bew-checkout-ob-container');
					if (self.$wrap.find('.bew-ob-product-wrap').length) {
						self.$wrap.off('click');
						self.design();
					}
				});
			},
			init_events: function () {
				let self = bew_order_bump;
				self.$wrap.find('.bew-ob-checkbox-loading').removeClass('bew-ob-checkbox-loading');								
				self.$wrap.on('click', '.bew-ob-product-desc-read', function () {
					let current = jQuery(this).closest('.bew-ob-product-desc');
					if (jQuery(this).hasClass('bew-ob-product-desc-read-more')) {
						current.find('.bew-ob-product-desc-short').addClass('bew-disable');
						current.find('.bew-ob-product-desc-full').removeClass('bew-disable');
					}else {
						current.find('.bew-ob-product-desc-short').removeClass('bew-disable');
						current.find('.bew-ob-product-desc-full').addClass('bew-disable');
					}
					bew_order_bump.design_form_atc(jQuery(this));
				});
				self.$wrap.on('click', '.bew-ob-title', function () {
					console.log("hola4");
					jQuery(this).parent().find('.bew-ob-checkbox').trigger('click');
				});
				self.$wrap.on('click', '.bew-ob-checkbox:not(.bew-ob-checkbox-loading)', function () {
					console.log("hola5");
					let button = jQuery(this);
					let product = button.closest('.bew-ob-product-wrap');
					let form = product.find('.bew-ob-cart-form');
					if (!form.length || (!form.find('[name="quantity"]').length && !form.find('.qty').length)){
						self.show_message(self.i18n_unavailable_text);
						return false;
					}
					if (button.hasClass('bew-ob-checkbox-checked')) {
						button.addClass('bew-ob-checkbox-loading');
						self.remove_form_cart(button, product);
						return false;
					}
					let check_attribute = true;
					form.find('.bew-attribute-options').each(function (k, item) {
						if (!jQuery(item).val()) {
							check_attribute = false;
							return false;
						}
					});
					if (!check_attribute) {
						self.show_message(self.i18n_make_a_selection_text.replace('{product_name}', form.data('product_name')));
						return false;
					}
					if (button.hasClass('bew-ob-checkbox-swatches-disable')) {
						self.show_message(self.i18n_unavailable_text);
						return false;
					}
					button.addClass('bew-ob-checkbox-loading');
					self.add_to_cart(button, form, product);
				});
			},
			add_to_cart: function (button, form, product) {
				if (typeof bew_atc ==='undefined') {
					var bew_atc = [];
				}
				button = jQuery(button);
				form = jQuery(form);
				product = jQuery(product);
				button.addClass('bew-ob-checkbox-checked');
				form.find('.bew-add-to-cart, .bew-product_id').val(product.data('product_id') || 0);
				let data = form.find('select, input').serialize();
				if (typeof bew_atc ==='undefined') {
					let bew_atc = [];
				}
				bew_atc.push({
					type: 'post',
					url: bew_order_bump.wc_ajax_url.toString().replace('%%endpoint%%', 'bew_add_to_cart'),
					data: data,
					beforeSend: function () {
						product.closest('.bew-checkout-ob-shortcode').find('.bew-loading-wrap').removeClass('bew-disable');
					},
					success: function (response) {
						if (!response || response.error) {
							if (response.message) {
								bew_order_bump.show_message(response.message);
							}
							bew_atc = [];
							return false;
						} else {
							jQuery(document.body).trigger('added_to_cart', [response.fragments, response.cart_hash, button]);
							jQuery(document.body).trigger('bew_added_to_cart', [response.fragments, response.cart_hash, button]);
							bew_atc.shift();
							if (bew_atc.length > 0) {
								jQuery.ajax(bew_atc[0]);
							} else {
								jQuery(document.body).trigger('update_checkout', {update_shipping_method: false});
							}
						}
					},
					complete: function (response) {
						form.find('.bew-add-to-cart, .bew-product_id').val('');
						product.closest('.bew-checkout-ob-shortcode').find('.bew-loading-wrap').addClass('bew-disable');
					},
				});
				if (bew_atc.length === 1) {
					jQuery.ajax(bew_atc[0]);
				}
			},
			remove_form_cart: function (button, product) {
				if (typeof bew_remove_cart_item ==='undefined') {
					var bew_remove_cart_item = [];
				}
				button = jQuery(button);
				console.log(button);
				product = jQuery(product);
				button.removeClass('bew-ob-checkbox-checked');
				bew_remove_cart_item.push({
					type: 'post',
					url: bew_order_bump.wc_ajax_url.toString().replace('%%endpoint%%', 'bew_remove_form_cart'),
					data: {
						product_id: product.data('product_id'),
						product_type: 'bew_ob_product',
						cart_item_key: product.data('cart_item_key')
					},
					beforeSend: function () {
						product.closest('.bew-checkout-ob-shortcode').find('.bew-loading-wrap').removeClass('bew-disable');
					},
					success: function (response) {
						console.log(response);
						if (!response || response.error) {
							if (response.message) {
								bew_order_bump.show_message(response.message);
							}
							bew_remove_cart_item = [];
							return false;
						} else {
							jQuery(document.body).trigger('removed_from_cart', [response.fragments, response.cart_hash, button]);
							jQuery(document.body).trigger('bew_removed_from_cart', [response.fragments, response.cart_hash, button]);
							bew_remove_cart_item.shift();
							if (bew_remove_cart_item.length > 0) {
								jQuery.ajax(bew_remove_cart_item[0]);
							} else {
								console.log("update");
								jQuery(document.body).trigger('update_checkout', {update_shipping_method: false});
							}
						}
					},
					complete: function () {
						product.closest('.bew-checkout-ob-shortcode').find('.bew-loading-wrap').addClass('bew-disable');
					},
				});
				if (bew_remove_cart_item.length === 1) {
					jQuery.ajax(bew_remove_cart_item[0]);
				}
			},
			design: function () {
				bew_order_bump.$wrap.find('.bew-ob-product-wrap-checked').each(function (k, v) {
					jQuery(v).find('.bew-ob-checkbox').addClass('bew-ob-checkbox-checked');
					jQuery(v).find('.bew-cart-form-swatches select').each(function () {
						let attr_name = jQuery(this).attr('name');
						let attr_value = jQuery(v).data(attr_name);
						jQuery(this).val(attr_value).trigger('change');
					});
				});
				if (bew_order_bump.$wrap.outerWidth() < 400) {
					bew_order_bump.$wrap.find('select').addClass('bew-ob-full-width');
				}
				bew_order_bump.$wrap.find('.bew-ob-cart-form').each(function () {
					jQuery(this).removeClass('bew-ob-full-width');
					bew_order_bump.design_form_atc(jQuery(this));
				});
				bew_order_bump.$wrap.find('.bew-cart-form-swatches:not(.bew-cart-form-swatches-init)').each(function () {
					jQuery(this).addClass('bew-cart-form-swatches-init vi_wpvs_variation_form').bew_get_variations({wc_ajax_url: bew_order_bump.wc_ajax_url});
					// Babystreet theme of theAlThemist
					if (jQuery(this).find('.babystreet-wcs-swatches').length) {
						jQuery(this).babystreet_wcs_variation_swatches_form();
					}
				});
				// WooCommerce Product Variations Swatches plugin of VillaTheme
				jQuery(document.body).trigger('vi_wpvs_variation_form');
				bew_order_bump.$wrap.find('.bew-ob-product-wrap-checked .vi-wpvs-variation-wrap-wrap').each(function () {
					let attr_value = jQuery(this).find('select').val().toString();
					jQuery(this).find('.vi-wpvs-option-wrap').each(function () {
						if (jQuery(this).data('attribute_value') === attr_value){
							jQuery(this).removeClass('vi-wpvs-option-wrap-default').addClass('vi-wpvs-option-wrap-selected');
						}else {
							jQuery(this).removeClass('vi-wpvs-option-wrap-selected vi-wpvs-option-wrap-default').addClass('vi-wpvs-option-wrap-disable');
						}
					});
				});
				bew_order_bump.$wrap.find('.bew-ob-product-wrap:not(.bew-ob-product-wrap-checked) .vi-wpvs-variation-wrap-wrap').each(function (k, v) {
					jQuery(v).find('select').each(function () {
						jQuery(this).val('').trigger('change');
					});
				});
				bew_order_bump.init_events();
			},
			design_form_atc: function (item) {
				let wrap = jQuery(item).closest('.bew-product');
				if (!wrap.find('.bew-ob-product-image').length){
					return false;
				}
				if (wrap.find('.bew-ob-product-image').height() < wrap.find('.bew-ob-product-desc').height()){
					wrap.find('.bew-ob-cart-form').addClass('bew-ob-full-width');
				}else {
					wrap.find('.bew-ob-cart-form').removeClass('bew-ob-full-width')
				}
			},
			show_message: function (message) {
				if (!jQuery('.bew-warning-wrap').length) {
					jQuery('body').append('<div class="bew-warning-wrap bew-warning-wrap-open">' + message + '</div>');
				} else {
					jQuery('.bew-warning-wrap').removeClass('bew-warning-wrap-close').addClass('bew-warning-wrap-open').html(message);
				}
				setTimeout(function () {
					jQuery('.bew-warning-wrap').addClass('bew-warning-wrap-close').removeClass('bew-warning-wrap-open');
				}, 3000);
			},
		};		

		var CheckoutDelivery  = function () {
					
			if( typeof checkoutShipping != 'undefined' && checkoutShipping) {	
				var bsconnect		  = checkoutShipping.bsconnect,
					store			  = checkoutShipping.store,
					delivery          = checkoutShipping.delivery,
					sm_store    	  = checkoutShipping.fopt,
					sm_delivery 	  = checkoutShipping.sopt,
					sm_delivery_free  = checkoutShipping.topt;				
			}
				
			if(bsconnect == "yes"){
				//Hide shipping options widtget
				$(".elementor-widget-woo-checkout-shipping-options").hide();
				
				// check default shipping method selection
				setTimeout(function () {
					var myform 		 	= $(".bew-shipping .bew-input-radio"),
					    shipping_method = $("#shipping_method"),
					    sm_checkedValue = shipping_method.find("input[type=radio]:checked").val();
												
						if(typeof sm_checkedValue != 'undefined') {
							var parts 		= sm_checkedValue.split(/[ :]+/),						
							sm_checkedValue = parts[0],
							SelectdValue 	= sm_checkedValue == sm_store ? store : delivery;
							
							//console.log(sm_checkedValue);
							//console.log(SelectdValue);
							
							//Change checked if onloading is not the first shipping method checked
							myform.find('input.input-radio').attr('checked',false);
							myform.find('input.input-radio[value^="' + SelectdValue + '"]').attr('checked',true);
							CheckoutConditional();
						}					
				}, 1000);
								
				var myform 		 = $(".bew-input-radio"),
					myinput 	 = myform.find("input.input-radio"),
				    checkedValue = myform.find("input.input-radio:checked").val();
					
					//console.log(checkedValue);				
					myinput.on('change', function() {
					  checkedValue = myform.find("input.input-radio:checked").val();
					  //console.log(checkedValue);
					});

			
				var shipping_method = $("#shipping_method"),
					name 			= 'shipping_method',
					SelectdValue 	= checkedValue == store ? sm_store : sm_delivery; 
					
					myinput.on('change', function() {
						SelectdValue 	= checkedValue == store ? sm_store : sm_delivery; 
						//console.log(SelectdValue);						
						//set right shipping method
						$('input[name^="' + name + '"]').attr('checked',false);
						$('input[name^="' + name + '"][value^="' + SelectdValue + '"]').attr('checked',true);
						$(':input[name^=shipping_method]').trigger('change');
					});
									
			}

		};	
		
		
		var HideonFreeProduct= function() {
			
			// check if is a free prodcut			
			if ($('.bew-components-totals-footer-item').hasClass('free-product')) {				
				$('.elementor-widget-woo-checkout-payment').hide();
			}
							
        };

		var BewIntlPhone = {	
		
		  iti_open: false,
		  init: function () {
			jQuery(function () {
			  BewIntlPhone.initialiseFields();

			  // Remove default event listener added by intlTelInput.
			  if (document.querySelector('.iti__selected-flag')) {
				document.querySelector('.iti__selected-flag').removeEventListener('click', intlTelInputGlobals.instances[0]._handleClickSelectedFlag, {}, false);
			  }
			  BewIntlPhone.toggleDropdown();
			  BewIntlPhone.handleClickOutside();
			 // jQuery(document.body).on('flux_step_change', BewIntlPhone.onStepChange); 
			  
			  //If country change
			  $('.bew-checkout').on('keyup change','#shipping_country_field',  function(){
				var country_value = $(this).find('select').val().toLowerCase();				
				BewIntlPhone.onCountryChange(country_value);				
			  });
			  
			  $('.bew-checkout').on('keyup change','#billing_country_field',  function(){
				var country_value = $(this).find('select').val().toLowerCase();				
				BewIntlPhone.onCountryChange(country_value);		
			  });
			  
			});
		  },
		  initialiseFields: function () {
			
			jQuery('.intl-phone-yes input[type=tel], intl-phone-yes input[type=text]').each(function () {
			  var args = {
				'hiddenInput': jQuery(this).attr('name') + '_full_number',
				'onlyCountries': bew_frontend_ob_params.allowed_countries,
				'preferredCountries': [],
				'nationalMode': true,
				'autoPlaceholder': 'polite',
				'utilsScript': bew_frontend_ob_params.intl_util_path,
				'separateDialCode': true
			  };
			  if (bew_frontend_ob_params?.base_country) {
				args.initialCountry = bew_frontend_ob_params?.base_country;
				args.preferredCountries = [bew_frontend_ob_params?.base_country];
			  }
						  			  
			  var input = jQuery('.intl-phone-yes input[type=tel], .intl-phone-yes input[type=text]');
			  args = wp.hooks.applyFilters('bew_checkout_intl_phone_args', args);
			  
			  input.intlTelInput(args);
			  
			  //Update the hidden field when field is changed.
			  //jQuery(this).on('countrychange', BewIntlPhone.updateHiddenField);
			  //jQuery(this).on('change', BewIntlPhone.updateHiddenField);
			  jQuery(this).closest('.form-row').addClass('bew-intl-phone--init');			  
			  
			});
		  },

		  onCountryChange: function (country_value) {
			var input = jQuery('.intl-phone-yes input[type=tel], .intl-phone-yes input[type=text]');
            var SetCountry = input.intlTelInput("setCountry", country_value);
		  },

		  //Update hidden field.
		  updateHiddenField: function () {
			var hidden_name = jQuery(this).attr('name') + '_full_number';
			var $hidden_field = jQuery(`[name=${hidden_name}]`);
			if ($hidden_field.length) {
			  $hidden_field.val(jQuery(this).intlTelInput("getNumber"));
			}
		  },
		
		  //Update hidden field on step change.
		  onStepChange: function () {
			jQuery('.intl-phone-yes input[type=tel], .intl-phone-yes input[type=text]').each(function () {
			  BewIntlPhone.updateHiddenField.apply(this);
			});
		  },

		  //Manually toggle dropdown opening and closing since it doesn't automatically work perfectly.
		  toggleDropdown: function () {
			jQuery('.iti__selected-flag').click(function () {
			  if (jQuery('.iti__country-list').hasClass('iti__hide')) {
				intlTelInputGlobals.instances[0]._showDropdown();
			  } else {
				intlTelInputGlobals.instances[0]._closeDropdown();
			  }
			});
		  },

		  //Close dropdown when clicked outside.
		  handleClickOutside: function () {
			document.addEventListener('click', function (e) {
			  if (intlTelInputGlobals && !e.target.closest('.iti__selected-flag')) {
				intlTelInputGlobals.instances[0]._closeDropdown();
			  }
			});
		  }
		};
		
		var events = function() {
			CheckoutPage();
			CheckoutInput();
			//CheckoutSummary();
			//CheckoutCoupon();
			CheckoutInputShipping();
			CheckoutShipping();
			CheckoutShippingOptions();
			CheckoutOrderReview();
			CheckoutPayment();
			CheckoutPaymentTab();
			BewCouponsWidget();
			MultiStepCheckout();
			CheckoutConditional();
			Bew_validate_fields();	
			BewexpressCheckout();
			CheckoutDelivery();	
			HideonFreeProduct();
			//Bew_toggle_create_account();
			
			//wc_checkout_login_form.init();
		};
		
		events();
		Bew_checkout_coupons.init();
		
		if( $('.intl-phone-yes').length) {
			BewIntlPhone.init();
		}
		
		let order_bump = bew_order_bump;
		order_bump.wc_ajax_url = bew_frontend_ob_params.wc_ajax_url;
		order_bump.i18n_unavailable_text = bew_frontend_ob_params.i18n_unavailable_text || 'Sorry, this product is unavailable. Please choose a different combination.';
		order_bump.i18n_make_a_selection_text = bew_frontend_ob_params.i18n_make_a_selection_text || 'Please select some product options before adding {product_name} to your cart.';
		order_bump.$wrap = jQuery('.bew-checkout-ob-container');
		order_bump.init();
	};
})( jQuery );
		
				
var CheckoutCoupon = function() {						
				
	var _toggle = jQuery( '.woocommerce-checkout-review-order' );
					
	jQuery( '.bew-checkout' ).on( 'click',  '#bew-coupon',  function( e ) {
		e.preventDefault();
		
		if ( _toggle.hasClass( 'show-coupon' ) ) {
			_toggle.removeClass( 'show-coupon' );						
		} else {
			_toggle.addClass( 'show-coupon' );
			jQuery(".bew-checkout_coupon .label-inside-yes input").attr("placeholder", "");			
		}
	} );			
				
};

var CheckoutSummary = function() {
				
	var _toggles = jQuery( '.woocommerce-checkout-review-order' ),
		collapse_mobile = jQuery( '.show-summary-mobile' );
	
	if ( _toggles.hasClass( 'bew-order-review-table' ) ) {
		var _toggles_content = jQuery( '.woocommerce-checkout-review-order .bew-review-order-content .bew-components-order-summary' );
	} else {
		var _toggles_content = jQuery( '.woocommerce-checkout-review-order .bew-review-order-content' );
	}
									
	if ( jQuery( '.bew-order-review-table' ).length > 0 ) {
		//console.log("hide");
				
		if ( (jQuery(window).width() <= 767) && (collapse_mobile.length == 0)  ) {
			_toggles.removeClass( 'show-summary' );	
		}
	}
	
	if ( jQuery( '.bew-checkout-fast-yes' ).length > 0 ) {
		//console.log("hide");		
		_toggles.addClass( 'closed-initial' );	
		_toggles.removeClass( 'show-summary' );	
		
	}
	
	if ( (jQuery(window).width() <= 767) && (jQuery( '.order-review-mobile-closed' ).length > 0 ) ) {
		//console.log("hide");		
		_toggles.addClass( 'closed-initial' );	
		_toggles.removeClass( 'show-summary' );	
		
	}
	
	CheckoutCouponWidget();	
									
	jQuery( '.bew-checkout' ).on( 'click',  '.woocommerce-checkout-review-order .bew-review-order-heading',  function( e ) {
		
		e.preventDefault();
		
		_toggles.removeClass( 'closed-initial' );	
							
		if ( _toggles.hasClass( 'show-summary' ) ) {			
			_toggles.removeClass( 'show-summary' );
			_toggles_content.slideUp();
			CheckoutCouponWidget();	
		} else {			
			_toggles.addClass( 'show-summary' );
			_toggles_content.show();
			CheckoutCouponWidget();				
		}
	} );
			
};

var CheckoutAccount = function () {
			
	if (jQuery( 'body' ).hasClass("elementor-editor-active")) {				
		jQuery( 'div.create-account' ).hide();				
		jQuery('p.create-account').on('click',function () {
			if (jQuery('p.create-account .input-checkbox').is(':checked')) {						
				jQuery('div.create-account').slideDown();						
			}else{						               	        	
				jQuery('div.create-account').hide();						
			}
		});								
	}
			
};

var CheckoutAccountInformation = function () {
			
	if (jQuery( 'body' ).hasClass("elementor-editor-active")) {				
		jQuery( 'div.create-account' ).hide();				
		jQuery('p.create-account').on('click',function () {
			if (jQuery('p.create-account .input-checkbox').is(':checked')) {						
				jQuery('div.create-account').slideDown();						
			}else{						               	        	
				jQuery('div.create-account').hide();						
			}
		});								
	}
			
};

		
var CheckoutCouponWidget = function () {
			
	if(jQuery(".bew-checkout-coupon").length == 0 ){
		return;
	}
						
	if ( jQuery(".bew-order-review-collapse").hasClass( "show-summary" ) ) {		
		jQuery(".bew-checkout-coupon").addClass("bew-hide");
	} else {		
		jQuery(".bew-checkout-coupon").removeClass("bew-hide");
	}
				            
};
		
/*
* Run this code under Elementor.
*/
jQuery(window).on('elementor/frontend/init', function () {

	elementorFrontend.hooks.addAction( 'frontend/element_ready/woo-checkout-review-order.default', CheckoutCoupon);
	elementorFrontend.hooks.addAction( 'frontend/element_ready/woo-checkout-review-order.default', CheckoutSummary);
	elementorFrontend.hooks.addAction( 'frontend/element_ready/woo-checkout-form-billing.default', CheckoutAccount);
	elementorFrontend.hooks.addAction( 'frontend/element_ready/woo-checkout-form-information.default', CheckoutAccountInformation);
});


// Bew Cart
(function( $ ) {

	var $window   = $( window ),
		$document = $( document ),
		$body     = $( 'body' ),
		w         = $window.width();		

	bewcheckout.cart = function() {
		
		if(!$body.hasClass("woocommerce-cart")){
				return;
		}
		
		//Check if is a mobile view
		function viewport() {
			var e = window, a = 'inner';
			if (!('innerWidth' in window )) {
				a = 'client';
				e = document.documentElement || document.body;
			}
			return { width : e[ a+'Width' ] , height : e[ a+'Height' ] };
		}
		
		function is_mobile() {
			var is_mobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
			return is_mobile;
		}
		
		var CartPage = function() {			
					
			if ($(".woocommerce-cart").length > 0 ) {
			  $(document).ready(function() {			
					
					setTimeout(function(){
						$(".bew-skeleton").addClass("hidde-bew-skeleton");
						$(".bew-cart-yes").addClass("show-bew-cart");
						$(".bew-cart-empty").addClass("show-bew-cart-empty");
						console.log("show bew cart");	
					},200);							
			  });
			}
						
			if($(".bew-cart-empty").length > 0){	
				
				//New Empty settings remove bew-cart-yes
				$('.bew-cart-is-empty .bew-cart-yes').remove();
				
			}
			
			//Set image height for mobile.
			if ( ( viewport().width  <= 767 ) && (is_mobile()) ) {	
				setTimeout(function(){
				var newheight = $('.product-thumbnail img').height();				
					$('head').append('<style type="text/css"> @media (max-width: 767px) {.bew-cart .elementor-widget-woo-cart-table table.cart .product-thumbnail img { height:' + newheight + 'px !important} }</style>');			
				},1000);
			}
        };
		
		var CartInput = function() {
			
			$('.shipping-calculator-form input, .shipping-calculator-form select').each( function () {
				var $this = $(this);
				if ( this.value != '' ) $this.parents('div.form-row').addClass('is-active');
				if ( this.value != '' ) $this.parents('p.form-row').addClass('is-active');
				$this.attr("placeholder", "");
			});
			
			
			$('.bew-cart').on('focus','input, select',  function(){
								
				if($(this).val() != '' ) {					
				    $(this).parents('div.form-row').addClass('is-active');
				    $(this).parents('p.form-row').addClass('is-active');
				}else{					
					$(this).parents('div.form-row').addClass('is-active');
					$(this).parents('p.form-row').addClass('is-active');
				} 
				 
			})
			
			$('.bew-cart').on('blur','input, select',  function(){
								
				if($(this).val() != '' ) {					
				    $(this).parents('div.form-row').addClass('is-active');
				    $(this).parents('p.form-row').addClass('is-active');
				}else{					
					$(this).parents('div.form-row').removeClass('is-active');
					$(this).parents('p.form-row').removeClass('is-active');
				} 
				 
			})
			
			$('.bew-cart').on('click', '.shipping-calculator-button', function(){
				$('input, select').each( function () {
					var $this = $(this);
					if ( this.value != '' ) $this.parents('div.form-row').addClass('is-active');
					if ( this.value != '' ) $this.parents('p.form-row').addClass('is-active');
					
				});
			});
																	
        };
		
		var CartEmpty= function() {			
			
			function cartEmptied(e) {
				//The below 2 elements can be changed according to the classes you use in your custom cart template
				var cartForm = $('.woocommerce-cart-form'),
				    cartCollaterals = $('.bew-cart-totals');
				
				if (cartForm.length > 0) {
					cartForm.remove();
				}

				if (cartCollaterals.length > 0) {
					cartCollaterals.remove();
				}
				
				$('body.bew-cart').addClass('bew-cart-is-empty');
				$('body.bew-cart .bew-cart-empty').addClass('show-bew-cart-empty');				
				
				//New Empty settings remove bew-cart-yes
				$('.bew-cart-is-empty .bew-cart-yes').remove();
								
				var woogrid_id = $('.bew-woo-grid').attr("data-id");
				
				if (woogrid_id){					
					// send id to create grid css
					var data = {
							action: 'empty_cart_woo_grid',
							woo_grid_id: woogrid_id
						};

					$.ajax( {
						type: 'POST',
						url: bewcart_vars.ajax_url,
						data: data,
						success: function( response ) {		
							
							$('.bew-woo-grid').prepend(response);
							//console.log(response);					
						},
						complete: function() {
							
						}
					} );
									
					$('.bew-woo-grid').addClass('show-bew-woo-grid'); 	
				}
				
			}
			$(document.body).on('wc_cart_emptied', cartEmptied);

        };
		
		var is_blocked = function( $node ) {
			return $node.is( '.processing' ) || $node.parents( '.processing' ).length;
		};
		
		var block = function( $node ) {
			if ( ! is_blocked( $node ) ) {
				$node.addClass( 'processing' ).block( {
					message: null,
					overlayCSS: {
						background: '#fff',
						opacity: 0.6
					}
				} );
			}
		};

		var unblock = function( $node ) {
			$node.removeClass( 'processing' ).unblock();
		};
		
		var get_url = function( endpoint ) {
			return wc_cart_params.wc_ajax_url.toString().replace(
				'%%endpoint%%',
				endpoint
			);
		};

		var bewWooqtyCart = function( $quantitySelector ) {
				
			if(!$body.hasClass("woocommerce-cart")){
				return;
			}
			
			//if(!$body.hasClass("elementor-editor-active")){
			//	return;
			//}
				
			if($(".elementor-widget-woo-cart-table").length == 0 ){ 
				return;
			}
				
			$body.addClass('bew-cart');	
								
			var $quantityBoxes,
				$quantityBoxes_theme,
				$cart = $( '.woocommerce-cart form.woocommerce-cart-form' );

			if ( ! $quantitySelector ) {
				$quantitySelector = '.qty';
			}

			$quantityBoxes = $( '.product-quantity div.bew-quantity:not(.buttons_added), .product-quantity .bew-quantity:not(.buttons_added)' ).find( $quantitySelector );
			$quantityBoxes_theme = $( 'div.bew-quantity a.minus, div.bew-quantity a.plus' );
				
			if ( $quantityBoxes && 'date' !== $quantityBoxes.prop( 'type' ) && 'hidden' !== $quantityBoxes.prop( 'type' ) && $('.bs-quantity').length == 0 && $quantityBoxes_theme.length == 0 ) {
					
				// Add plus and minus icons
				$quantityBoxes.parent().addClass( 'buttons_added' ).prepend('<a href="javascript:void(0)" class="minus">-</a>');
				$quantityBoxes.after('<a href="javascript:void(0)" class="plus">+</a>');

				// Target quantity inputs on cart pages
				$( 'input' + $quantitySelector + ':not(.product-quantity input' + $quantitySelector + ')' ).each( function() {
					var $min = parseFloat( $( this ).attr( 'min' ) );

					if ( $min && $min > 0 && parseFloat( $( this ).val() ) < $min ) {
						$( this ).val( $min );
					}
				});

				$( '.plus, .minus' ).unbind( 'click' );

				$( 'form.woocommerce-cart-form .plus, form.woocommerce-cart-form .minus' ).on( 'click', function() {

					// Quantity
					var $quantityBox;

					// If floating bar is enabled
					if ( $( 'body' ).hasClass( 'woocommerce-cart' )
						&& ! $cart.hasClass( 'grouped_form' )
						&& ! $cart.hasClass( 'cart_group' ) ) {						
						$quantityBox = $( this ).closest( '.bew-quantity' ).find( $quantitySelector );
					}

					// Get values
					var $currentQuantity = parseFloat( $quantityBox.val() ),
						$maxQuantity     = parseFloat( $quantityBox.attr( 'max' ) ),
						$minQuantity     = parseFloat( $quantityBox.attr( 'min' ) ),
						$step            = $quantityBox.attr( 'step' );

					// Fallback default values
					if ( ! $currentQuantity || '' === $currentQuantity  || 'NaN' === $currentQuantity ) {
						$currentQuantity = 0;
					}
					if ( '' === $maxQuantity || 'NaN' === $maxQuantity ) {
						$maxQuantity = '';
					}
					if ( '' === $minQuantity || 'NaN' === $minQuantity ) {
						$minQuantity = 0;
					}
					if ( 'any' === $step || '' === $step  || undefined === $step || 'NaN' === parseFloat( $step )  ) {
						$step = 1;
					}

					// Change the value
					if ( $( this ).is( '.plus' ) ) {

						if ( $maxQuantity && ( $maxQuantity == $currentQuantity || $currentQuantity > $maxQuantity ) ) {
							$quantityBox.val( $maxQuantity );
						} else {
							$quantityBox.val( $currentQuantity + parseFloat( $step ) );
						}

					} else {

						if ( $minQuantity && ( $minQuantity == $currentQuantity || $currentQuantity < $minQuantity ) ) {
							$quantityBox.val( $minQuantity );
						} else if ( $currentQuantity > 0 ) {
							$quantityBox.val( $currentQuantity - parseFloat( $step ) );
						}

					}					
					// Trigger change event
					$quantityBox.trigger( 'change' );
						
				} );
			}			

		};		
		
		var update_wc_div = function( html_str, preserve_notices ) {
			var $html       = $.parseHTML( html_str );
			var $new_form   = $( '.woocommerce-cart-form', $html );
			var $new_totals = $( '.cart_totals', $html );
			var $notices    = $( '.woocommerce-error, .woocommerce-message, .woocommerce-info', $html );

			// No form, cannot do this.
			if ( $( '.woocommerce-cart-form' ).length === 0 ) {
				window.location.reload();
				return;
			}

			// Remove errors
			if ( ! preserve_notices ) {
				$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();
			}

			if ( $new_form.length === 0 ) {
				// If the checkout is also displayed on this page, trigger reload instead.
				if ( $( '.woocommerce-checkout' ).length ) {
					window.location.reload();
					return;
				}

				// No items to display now! Replace all cart content.
				var $cart_html = $( '.cart-empty', $html ).closest( '.woocommerce' );
				$( '.woocommerce-cart-form__contents' ).closest( '.woocommerce' ).replaceWith( $cart_html );

				// Display errors
				if ( $notices.length > 0 ) {
					show_notice( $notices );
				}

				// Notify plugins that the cart was emptied.
				$( document.body ).trigger( 'wc_cart_emptied' );
			} else {
				// If the checkout is also displayed on this page, trigger update event.
				if ( $( '.woocommerce-checkout' ).length ) {
					$( document.body ).trigger( 'update_checkout' );
				}

				$( '.woocommerce-cart-form' ).replaceWith( $new_form );
				$( '.woocommerce-cart-form' ).find( ':input[name="update_cart"]' ).prop( 'disabled', true ).attr( 'aria-disabled', true );

				if ( $notices.length > 0 ) {
					show_notice( $notices );
				}

				update_cart_totals_div( $new_totals );
			}
			bewWooqtyCart();
			$( document.body ).trigger( 'updated_wc_div' );
		};
		
		var update_cart_totals_div = function( html_str ) {
			$( '.cart_totals' ).replaceWith( html_str );
			$( document.body ).trigger( 'updated_cart_totals' );
		};
						
		var show_notice = function( html_element, $target ) {
			if ( ! $target ) {
				$target = $( '.woocommerce-notices-wrapper:first' ) ||
					$( '.cart-empty' ).closest( '.woocommerce' ) ||
					$( '.woocommerce-cart-form' );
			}
			$target.prepend( html_element );
		};
		
		/**
		 * Object to handle AJAX calls for cart shipping changes.
		 */
		var bewcart_shipping = {

			init: function( cart ) {
				this.cart                       = cart;
				this.shipping_calculator_submit = this.shipping_calculator_submit.bind( this );

				$( document ).on(
					'submit',
					'form.bew-woocommerce-shipping-calculator',
					this.shipping_calculator_submit
				);

			},

			shipping_calculator_submit: function( evt ) {
				evt.preventDefault();

				var $form = $( evt.currentTarget );

				block( $( 'div.cart_totals' ) );
				block( $form );

				// Provide the submit button value because wc-form-handler expects it.
				$( '<input />' ).attr( 'type', 'hidden' )
								.attr( 'name', 'calc_shipping' )
								.attr( 'value', 'x' )
								.appendTo( $form );

				// Make call to actual form post URL.
				$.ajax( {
					type:     $form.attr( 'method' ),
					url:      $form.attr( 'action' ),
					data:     $form.serialize(),
					dataType: 'html',
					success:  function( response ) {
						bewWooqtyCart();
						update_wc_div( response );
						CartInput();
						var $notices    = $( '.woocommerce-error, .woocommerce-message, .woocommerce-info', response ),						
						    message = $notices.text(),
						    content_holder = message.replace(/<(?:.|\n)*?>/gm, '');
						
							setTimeout(function(){
								Snackbar.show({ showAction: false, 
											pos: 'bottom-left' , 
											text: content_holder });
							},500);
					},
					complete: function() {
						unblock( $form );
						unblock( $( 'div.cart_totals' ) );
					}
				} );
			}
		};

			
		/**
		 * Object to handle bew cart UI.
		 */
		var bewcart = {
			/**
			 * Initialize bew cart UI events.
			 */
			init: function() {
				
				this.apply_coupon          		= this.apply_coupon.bind( this );
				this.remove_coupon_clicked 		= this.remove_coupon_clicked.bind( this );
				this.cart_submit           		= this.cart_submit.bind( this );
				this.submit_click          		= this.submit_click.bind( this );
				this.item_remove_clicked   		= this.item_remove_clicked.bind( this );
				this.update_cart           		= this.update_cart.bind( this );
				this.shipping_method_selected   = this.shipping_method_selected.bind( this );
			
				$( document ).on(
					'click',
					'.bew-cart_coupon :input[type=submit]',
					this.submit_click );
				$( document ).on(
					'submit',
					'.bew-cart_coupon',
					this.cart_submit );
				$( document ).on(
					'click',
					'a.bew-remove-coupon',
					this.remove_coupon_clicked );
				$( document ).on(
					'click',
					'.woocommerce-cart-form .bew-product-remove > a',
					this.item_remove_clicked );	
				$( document ).on(
					'change',
					'select.shipping_method, :input[name^=shipping_method]',
					this.shipping_method_selected
				);
						
			},

			/**
			 * Update entire cart via ajax.
			 */
			update_cart: function( preserve_notices ) {
				var $form = $( '.woocommerce-cart-form' );
				
				block( $form );
				//block( $( 'div.cart_totals' ) );
				
				// Make call to actual form post URL.
				$.ajax( {
					type:     $form.attr( 'method' ),
					url:      $form.attr( 'action' ),
					data:     $form.serialize(),
					dataType: 'html',
					success:  function( response ) {
						update_wc_div( response, preserve_notices );
					},
					complete: function() {
						//unblock( $form );
						//unblock( $( 'div.cart_totals' ) );
						//$.scroll_to_notices( $( '[role="alert"]' ) );
					}
				} );
			},			
			cart_submit: function( evt ) {
							
				var $submit  = $( document.activeElement ),
					$clicked = $( ':input[type=submit][clicked=true]' ),
					$form    = $( evt.currentTarget );

				// For submit events, currentTarget is form.
				// For keypress events, currentTarget is input.
				if ( ! $form.is( 'form' ) ) {
					$form = $( evt.currentTarget ).parents( 'form' );
				}
				
				if ( is_blocked( $form ) ) {
					return false;
				}

				if ( $clicked.is( ':input[name="apply_coupon"]' ) || $submit.is( '#coupon_code_total' ) ) {				
					evt.preventDefault();
					this.apply_coupon( $form );
				}
			},
			submit_click: function( evt ) {
				$( ':input[type=submit]', $( evt.target ).parents( 'form' ) ).removeAttr( 'clicked' );
				$( evt.target ).attr( 'clicked', 'true' );
			},
			apply_coupon: function( $form ) {
				block( $form );
				
				var cart = this;
				var $text_field = $( '#coupon_code_total' );
				var coupon_code = $text_field.val();
				
				var data = {
					security: wc_cart_params.apply_coupon_nonce,
					coupon_code: coupon_code
				};

				$.ajax( {
					type:     'POST',
					url:      get_url( 'apply_coupon' ),
					data:     data,
					dataType: 'html',
					success: function( response ) {
						$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();
						//show_notice( response );
						$ ('.bew-cart-totals').removeClass('show-coupon');
						$( document.body ).trigger( 'applied_coupon', [ coupon_code ] );
						
						var content_holder = response.replace(/<(?:.|\n)*?>/gm, '');
														
							setTimeout(function(){
								Snackbar.show({ showAction: false, 
											pos: 'bottom-left' , 
											text: content_holder });
							},2000);
					},
					complete: function() {
						unblock( $form );
						$text_field.val( '' );
						cart.update_cart( true );
					}
				} );
			},
			remove_coupon_clicked: function( evt ) {
				evt.preventDefault();

				var cart     = this;
				var $wrapper = $( evt.currentTarget ).closest( '.bew-components-totals-discount__coupon-list li' );
				var coupon   = $( evt.currentTarget ).attr( 'data-coupon' );

				block( $wrapper );

				var data = {
					security: wc_cart_params.remove_coupon_nonce,
					coupon: coupon
				};

				$.ajax( {
					type:    'POST',
					url:      get_url( 'remove_coupon' ),
					data:     data,
					dataType: 'html',
					success: function( response ) {
						$( '.woocommerce-error, .woocommerce-message, .woocommerce-info' ).remove();
						//show_notice( response );
						$( document.body ).trigger( 'removed_coupon', [ coupon ] );
						//unblock( $wrapper );
						
						var content_holder = response.replace(/<(?:.|\n)*?>/gm, '');
						
							setTimeout(function(){
								Snackbar.show({ showAction: false, 
											pos: 'bottom-left' , 
											text: content_holder });
							},2000);
					},
					complete: function() {
						cart.update_cart( true );
					}
				} );
			},
			item_remove_clicked: function( evt ) {
				evt.preventDefault();

				var $a = $( evt.currentTarget );
				var $form = $a.parents( 'form' );
				var $item = $a.parents( '.woocommerce-cart-form__cart-item');
				
				block( $item );				

				$.ajax( {
					type:     'GET',
					url:      $a.attr( 'href' ),
					dataType: 'html',
					success:  function( response ) {
						update_wc_div( response );
												
						var $notices    = $( '.woocommerce-error, .woocommerce-message, .woocommerce-info', response ),						
						    message = $notices.text(),							
							text_split = message.split('.'),						
							newtext = text_split[0],	
							content_holder = newtext.replace(/<(?:.|\n)*?>/gm, '') + ".";
														
							setTimeout(function(){
								Snackbar.show({ showAction: false, 
											pos: 'bottom-left' , 
											text: content_holder });
							},500);
							
					},
					complete: function() {
						unblock( $form );				
						
					}
				} );
			},

		/**
		 * Handles when a shipping method is selected.
		 */
		shipping_method_selected: function() {
						
			//Add loading class
			$('.bew-cart-loader-active').addClass('loading');			
			//block( $( 'div.bew-cart-items' ) );
			
			function cartTotalUpdateShipping() {				
				$('.bew-cart-loader-active').removeClass('loading');
				//unblock( $( 'div.bew-cart-items' ) );
			}
			
			$(document.body).on('updated_shipping_method', cartTotalUpdateShipping);
		},
		
		};
		
		var CartUpdate= function() {			
			
			function cartUpdateEvent() {
				bewWooqtyCart();
				NotesCartUpdate();
				CartInput();
				VerticalLayout();
			}
			
			$(document.body).on('updated_wc_div', cartUpdateEvent);

        };

		var CartTotalUpdate= function() {			
			
			function cartTotalUpdateEvent() {				
				CartInput();
			}
			
			$(document.body).on('updated_shipping_method', cartTotalUpdateEvent);

        };
		
		var QtyCartUpdate= function() {	
		
			var timeout;
			var progress = null;
			
			jQuery("div.woocommerce").on("change", "input.qty, select.qty", function(evt){ // keyup and mouseup for Firefox support
				if (timeout != undefined) clearTimeout(timeout); //cancel previously scheduled event				
				timeout = setTimeout(function() {
					
					var $form   = $( evt.currentTarget );
					var $item 	= $form .parents( '.woocommerce-cart-form__cart-item');
					var $totals = $( '.cart_totals');
										
					block( $item );
					block( $totals );	

					// For submit events, currentTarget is form.
					// For keypress events, currentTarget is input.
					if ( ! $form.is( 'form' ) ) {
						$form = $( evt.currentTarget ).parents( 'form' );
					}

					if ( 0 === $form.find( '.woocommerce-cart-form__contents' ).length ) {
						return;
					}

					evt.preventDefault();					
					
					// Provide the submit button value because wc-form-handler expects it.
					$( '<input />' ).attr( 'type', 'hidden' )
									.attr( 'name', 'update_cart' )
									.attr( 'value', 'Update Cart' )
									.appendTo( $form );

					// Make call to actual form post URL.
					progress = $.ajax( {
						type:     $form.attr( 'method' ),
						url:      $form.attr( 'action' ),
						data:     $form.serialize(),
						dataType: 'html',
						beforeSend : function() {
							//checking progress status and aborting pending request if any							
							if(progress != null) {
								progress.abort();								
							}
							
							//Add loading class
							$('.bew-cart-loader-active').addClass('loading');
						},
						success:  function( response ) {
							update_wc_div( response );						
							var $notices    = $( '.woocommerce-error, .woocommerce-message, .woocommerce-info', response ),						
								message = $notices.first().text();
								console.log(message);						
								if (message === ''){									
									var content_holder = bewcart_vars.cart_update;
								}else {
									var content_holder = message.replace(/<(?:.|\n)*?>/gm, '');
								}								
								setTimeout(function(){
									Snackbar.show({ showAction: false, 
												pos: 'bottom-left' , 
												text: content_holder });
								},500);
						},
						complete: function() {
							// after ajax complets progress set to null
							progress = null;	
							unblock( $item );
							unblock( $totals );	
						}
					} );
					
				}, bewcart_vars.qty_delay ); // schedule update cart event with delay in miliseconds specified in plugin settings
			});

        };

		var NotesCartUpdate= function() {
			
			var timeout = null;
			var minlength = 2;
			
			$('body #order_comments').on('keyup paste change',function(){
			  
			  // clear last timeout check
			  clearTimeout(timeout);

			  var that = this;
			  var value = $(this).val();

			  if (value.length >= minlength) {

				// run ajax call 0.5 second after user has stopped typing
				timeout = setTimeout(function() {
				  $.ajax({
					type: 'POST',
					url: bewcart_vars.ajax_url,
					data: {
						 action: 'bew_update_cart_notes',
						 notes: $('#order_comments').val(),

					},
					success: function(response) {
						//$( document.body ).trigger( 'updated_wc_div' );
					}
				  });
				}, 500);
			  };
			});

        };
		
		var VerticalLayout= function() {
			
			//console.log("render vertical");
			if ( ( viewport().width  >= 1025 ) ) {				
				$('.bew-woo-cart-table .cart_item').each( function () {	

					$('.vertical-layout .desktop_vertical', this ).unwrap();
					$('.desktop_vertical', this ).wrapAll('<div class="vertical-layout"></div>');
										
				});
			}

			if ( ( viewport().width  > 767 && viewport().width  < 1025 ) ) {				
				$('.bew-woo-cart-table .cart_item').each( function () {
					
					$('.vertical-layout .tablet_vertical', this ).unwrap();
					$('.tablet_vertical', this ).wrapAll('<div class="vertical-layout"></div>');
					
				});
			}
			
			if ( ( viewport().width  <= 767 ) ) {				
				$('.bew-woo-cart-table .cart_item').each( function () {	
				
					$('.vertical-layout .mobile_vertical', this ).unwrap();
					$('.mobile_vertical', this ).wrapAll('<div class="vertical-layout"></div>');	
					
				});
			}

        };

		var VerticalLayoutRender= function() {

			// check if body data-elementor-device-mode changed
			var currentDevice = $("body").attr("data-elementor-device-mode");
			//console.log("Initial: ", currentDevice);			

				var $div = $("body");
				var observer = new MutationObserver(function(mutations) {
				  mutations.forEach(function(mutation) {
					if (mutation.attributeName === "data-elementor-device-mode") {
					  var attributeValue = $(mutation.target).prop(mutation.attributeName);						
						if (attributeValue != currentDevice) {
							  //console.log("Device changed to: ", $("body").attr("data-elementor-device-mode"));
							  setTimeout(function(){								  
								VerticalLayout();								 
							  },300);
						}
					}
				  });
				});
				
				observer.observe($div[0], {
				  attributes: true
				});
				
        };

		
		var events = function() {
			CartPage();
			CartInput();
			//CartCoupon();
			CartEmpty();
			CartUpdate();
			CartTotalUpdate();
			bewWooqtyCart();
			QtyCartUpdate();
			NotesCartUpdate();
			VerticalLayout();
			VerticalLayoutRender();
		};
		
		events();
		bewcart_shipping.init( bewcart );
		bewcart.init();
		
	};
})( jQuery );
				
var CartCoupon = function() {
			
	var _toggle = jQuery( '.bew-cart-totals' );			
			
	jQuery( '.bew-cart' ).on( 'click',  '#bew-coupon',  function( e ) {
		e.preventDefault();
				
		if ( _toggle.hasClass( 'show-coupon' ) ) {
			_toggle.removeClass( 'show-coupon' );					
		} else {
			_toggle.addClass( 'show-coupon' );					
		}
	} );
			
};

/*
* Run this code under Elementor.
*/
jQuery(window).on('elementor/frontend/init', function () {

	elementorFrontend.hooks.addAction( 'frontend/element_ready/woo-cart-totals.default', CartCoupon);

});

// Bew Account
(function( $ ) {

	var $window   = $( window ),
		$document = $( document ),
		$body     = $( 'body' ),
		w         = $window.width();		

	bewcheckout.account = function() {
		
		if($body.hasClass("elementor-editor-active") && $body.hasClass("bew-woocommerce-builder") ){
			
		}else {
			if(!$body.hasClass("woocommerce-account")){
				return;
			}			
		}
		
		var BewAccount  = function () {			
			if ($(".bew-woocommerce-account").length > 0 ) {
			  $(document).ready(function() {							  
				  $(".bew-account .bew-account-yes").addClass("show-bew-account");				
			  });
			}
		};

		var BewLogin  = function () {			
			if ($(".bew-account-login").length > 0 ) {
			  $(document).ready(function() {			
					$(".bew-account-login").addClass("show-bew-account-login");				
			  });
			}
		};		

		var AccountInput = function() {
			
			setTimeout(function(){
				$('.label-inside-yes .woocommerce-form-row input').each( function () {
					
					var $this = $(this);									
					if ( this.value != '' ) $this.parents('div.woocommerce-form-row').addClass('is-active');
					if ( this.value != '' ) $this.parents('p.woocommerce-form-row').addClass('is-active');				
				});
			
				$('.woocommerce-form-row input').each( function () {	
				
					var $this = $(this);
					
					if ( this.value != '' ) $this.parents('div.woocommerce-form-row').addClass('has-value');
					if ( this.value != '' ) $this.parents('p.woocommerce-form-row').addClass('has-value');
					
				});
						
			},200);

			$('.bew-account').on('focus','input',  function(){			
				    $(this).parents('div.fwoocommerce-form-row').addClass('is-active');
				    $(this).parents('p.woocommerce-form-row').addClass('is-active');				 
			})
			
			$('.bew-account').on('blur','input',  function(){
								
				if($(this).val() != '' ) {					
				    $(this).parents('div.fwoocommerce-form-row').addClass('is-active');
				    $(this).parents('p.woocommerce-form-row').addClass('is-active');
				}else{					
					$(this).parents('div.woocommerce-form-row').removeClass('is-active');
					$(this).parents('p.woocommerce-form-row').removeClass('is-active');
				} 
				 
			})
																	
        };		
		
		var AccountShowPassword = function() {
			
			if ($('.show-eye-icon-yes').length > 0 ) {				
				// Show password visiblity hover icon on woocommerce forms
				//$( '.woocommerce form .woocommerce-Input[type="password"]' ).wrap( '<span class="password-input"></span>' );
				// Add 'password-input' class to the password wrapper in checkout page.
				$( '.woocommerce form input' ).filter(':password').parent('span').addClass('password-input');
				
				if ($('.show-password-input').length == 0 ) {
					$( '.bew-woocommerce-Input[type="password"] + label' ).after( '<span class="show-password-input"></span>' );
				}
				$( '.show-password-input' ).on( 'click',
					function() {
												
						if ( $( this ).hasClass( 'display-password' ) ) {
							$( this ).removeClass( 'display-password' );
						} else {
							$( this ).addClass( 'display-password' );
						}
						if ( $( this ).hasClass( 'display-password' ) ) {
							$( this ).siblings( ['input[type="password"]'] ).prop( 'type', 'text' );
						} else {
							$( this ).siblings( 'input[type="text"]' ).prop( 'type', 'password' );
						}
					}
				);
			
			}

        };

		var AccountOnepage = function() {
			
			$('.elementor-widget-woo-account-register .bew-account-form-register').hide();
			$('#login').removeClass('active').hide();
			$('.elementor-widget-woo-account-lost-password .bew-account-form-lost-password').hide();
			
			$('.register_btn, #register a').on('click', function ( e ) {
				
			   e.preventDefault();
			   $('.elementor-widget-woo-account-login .bew-account-form-login').removeClass('active').hide();
			   $('#register').removeClass('active').hide();
			   $('.elementor-widget-woo-account-register .bew-account-form-register').addClass('active').fadeIn();
			   $('#login').addClass('active').fadeIn();
			   $('.elementor-widget-woo-account-lost-password .bew-account-form-lost-password').removeClass('active').hide();
			   
			});
			
			$('.login_btn, #login a').on('click', function ( e ) {
			   
			    e.preventDefault();
			   $('.elementor-widget-woo-account-register .bew-account-form-register').removeClass('active').hide();
			   $('#login').removeClass('active').hide();
			   $('.elementor-widget-woo-account-login .bew-account-form-login').addClass('active').fadeIn();
			   $('#register').addClass('active').fadeIn();
			   $('.elementor-widget-woo-account-lost-password .bew-account-form-lost-password').removeClass('active').hide();
			});
			
			$('.lost_password_btn a, #lost_password a').on('click', function ( e ) {
			   
			   e.preventDefault();
			   $('.elementor-widget-woo-account-login .bew-account-form-login').removeClass('active').hide();
			   $('#register').removeClass('active').hide();
			   $('.elementor-widget-woo-account-lost-password .bew-account-form-lost-password').addClass('active').fadeIn();			   
			});
		
        };		

		var events = function() {			
			BewAccount();
			BewLogin();
			AccountInput();
			AccountShowPassword();
			AccountOnepage();
		};
		
		events();
		
	};
})( jQuery );
