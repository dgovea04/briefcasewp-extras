<?php
namespace BriefcasewpExtras;

use Elementor;
use Elementor\Plugin;
use WP_Query;

class Helper{
	

	function get_woo_thankyou_template(){	
	
            $args = array(
                'post_type' => 'elementor_library',
				'post_status' => 'publish',
                'meta_query' => array(
	    		'relation'    => 'AND',
                    array(
                        'key' => 'briefcase_template_layout',
                        'value'   => 'woo-thankyou',
                        'compare' => '='
                    ),				    
                )
            );
            $templates = new WP_Query($args);
				
            if($templates->found_posts){
                $templates->the_post();					
                $bew_tid = get_the_ID();
			
            }else{
                return false;
            }
            wp_reset_postdata();
            return $bew_tid;
				
    }

	function get_woo_account_template(){	
	
            $args = array(
                'post_type' => 'elementor_library',
				'post_status' => 'publish',
                'meta_query' => array(
	    		'relation'    => 'AND',
                    array(
                        'key' => 'briefcase_template_layout',
                        'value'   => 'woo-account',
                        'compare' => '='
                    ),				    
                )
            );
            $templates = new WP_Query($args);
				
            if($templates->found_posts){
                $templates->the_post();					
                $bew_tid = get_the_ID();
			
            }else{
                return false;
            }
            wp_reset_postdata();
            return $bew_tid;
				
    }

	function get_woo_login_template(){	
	
            $args = array(
                'post_type' => 'elementor_library',
				'post_status' => 'publish',
                'meta_query' => array(
	    		'relation'    => 'AND',
                    array(
                        'key' => 'briefcase_template_layout',
                        'value'   => 'woo-login',
                        'compare' => '='
                    ),				    
                )
            );
            $templates = new WP_Query($args);
				
            if($templates->found_posts){
                $templates->the_post();					
                $bew_tid = get_the_ID();
			
            }else{
                return false;
            }
            wp_reset_postdata();
            return $bew_tid;
				
    }
	
	function get_woo_cart_empty_template(){	
	
            $args = array(
                'post_type' => 'elementor_library',
				'post_status' => 'publish',
                'meta_query' => array(
	    		'relation'    => 'AND',
                    array(
                        'key' => 'briefcase_template_layout',
                        'value'   => 'woo-cart-empty',
                        'compare' => '='
                    ),				    
                )
            );
            $templates = new WP_Query($args);
					
            if($templates->found_posts){
                $templates->the_post();					
                $bew_tid = get_the_ID();
			
            }else{
                return false;
            }
            wp_reset_postdata();
            return $bew_tid;
				
    }
			
	/** Forms */

	/**
	 * Outputs a checkout/address form field.
	 *
	 * @param string $key Key.
	 * @param mixed  $args Arguments.
	 * @param string $value (default: null).
	 * @return string
	 */
	function bew_woocommerce_form_field( $key, $args, $value = null ) {
		$defaults = array(
			'type'              => 'text',
			'label'             => '',
			'description'       => '',
			'placeholder'       => '',
			'maxlength'         => false,
			'required'          => false,
			'autocomplete'      => false,
			'id'                => $key,
			'class'             => array(),
			'label_class'       => array(),
			'input_class'       => array(),
			'return'            => false,
			'options'           => array(),
			'custom_attributes' => array(),
			'validate'          => array(),
			'default'           => '',
			'autofocus'         => '',
			'priority'          => '',
		);

		$args = wp_parse_args( $args, $defaults );
		$args = apply_filters( 'woocommerce_form_field_args', $args, $key, $value );

		if ( $args['required'] ) {
			$args['class'][] = 'validate-required';
			$required        = '&nbsp;<abbr class="required" title="' . esc_attr__( 'required', 'woocommerce' ) . '">*</abbr>';
		} else {
			$required = '&nbsp;<span class="optional">(' . esc_html__( 'optional', 'woocommerce' ) . ')</span>';
		}

		if ( is_string( $args['label_class'] ) ) {
			$args['label_class'] = array( $args['label_class'] );
		}

		if ( is_null( $value ) ) {
			$value = $args['default'];
		}

		// Custom attribute handling.
		$custom_attributes         = array();
		$args['custom_attributes'] = array_filter( (array) $args['custom_attributes'], 'strlen' );

		if ( $args['maxlength'] ) {
			$args['custom_attributes']['maxlength'] = absint( $args['maxlength'] );
		}

		if ( ! empty( $args['autocomplete'] ) ) {
			$args['custom_attributes']['autocomplete'] = $args['autocomplete'];
		}

		if ( true === $args['autofocus'] ) {
			$args['custom_attributes']['autofocus'] = 'autofocus';
		}

		if ( $args['description'] ) {
			$args['custom_attributes']['aria-describedby'] = $args['id'] . '-description';
		}
			
		// add custom bew data attributes
		if ( ! empty( $args['show_in_email'] ) ) {
			$args['custom_attributes']['show_in_email'] = $args['show_in_email'];
		}
		
		if ( ! empty( $args['show_in_order'] ) ) {
			$args['custom_attributes']['show_in_order'] = $args['show_in_order'];
		}
		
		if ( ! empty( $args['conditional'] ) ) {
			$args['custom_attributes']['conditional'] = $args['conditional'];
		}
		
		if ( ! empty( $args['superior_field'] ) ) {
			$args['custom_attributes']['superior_field'] = $args['superior_field'];
		}
		
		if ( ! empty( $args['superior_field_option'] ) ) {
			$args['custom_attributes']['superior_field_option'] = $args['superior_field_option'];
		}

		if ( ! empty( $args['custom_attributes'] ) && is_array( $args['custom_attributes'] ) ) {
			foreach ( $args['custom_attributes'] as $attribute => $attribute_value ) {
				$custom_attributes[] = esc_attr( $attribute ) . '="' . esc_attr( $attribute_value ) . '"';
			}
		}

		if ( ! empty( $args['validate'] ) ) {
			foreach ( $args['validate'] as $validate ) {
				$args['class'][] = 'validate-' . $validate;
			}
		}
		
		$field           = '';
		$label_id        = $args['id'];
		$sort            = $args['priority'] ? $args['priority'] : '';
		$conditional     = '';
		$option_layout   = '';
		$option_type   	 = '';
		$bew_intl_phone  = '';
		$info_text    	 = '';
		$custom_class    = '';
		
		if (isset($args['conditional'])){
			$conditional     = $args['conditional'] ? "field-conditional-". $args['conditional'] : '';
		}
		if (isset($args['option_layout'])){
			$option_layout   = $args['option_layout'] ? "option-layout-". $args['option_layout'] : '';
		}
		if (isset($args['option_type'])){
			$option_type   = $args['option_type'] ? "option-type-". $args['option_type'] : '';
		}

		if (isset($args['info_text'])){
			$info_text   = $args['info_text'] ? $args['info_text'] : '';
		}
		
		$row_class       = array_values($args['class'])[0];
		if (isset($args['custom_class'])){
			$custom_class    = $args['custom_class'];
		}

		$field_container = '<div class="form-row ' . esc_attr( $option_layout ) . ' ' . esc_attr( $option_type ) . ' ' . esc_attr( $conditional ) . ' '. esc_attr( $custom_class ) . ' %1$s" id="%2$s" data-row = "' . esc_attr( $row_class ) . '" data-priority="' . esc_attr( $sort ) . '">%3$s</div>';

		//var_dump($args);
		switch ( $args['type'] ) {
			case 'country':
				$countries = 'shipping_country' === $key ? WC()->countries->get_shipping_countries() : WC()->countries->get_allowed_countries();

				if ( 1 === count( $countries ) ) {

					$field .= '<strong>' . current( array_values( $countries ) ) . '</strong>';

					$field .= '<input type="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . current( array_keys( $countries ) ) . '" ' . implode( ' ', $custom_attributes ) . ' class="country_to_state" readonly="readonly" />';

				} else {

					$field = '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="country_to_state country_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . '><option value="default">' . esc_html__( 'Select a country / region&hellip;', 'woocommerce' ) . '</option>';

					foreach ( $countries as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
					}

					$field .= '</select>';

					$field .= '<noscript><button type="submit" name="woocommerce_checkout_update_totals" value="' . esc_attr__( 'Update country / region', 'woocommerce' ) . '">' . esc_html__( 'Update country / region', 'woocommerce' ) . '</button></noscript>';

				}

				break;
			case 'state':
				/* Get country this state field is representing */
				$for_country = isset( $args['country'] ) ? $args['country'] : WC()->checkout->get_value( 'billing_state' === $key ? 'billing_country' : 'shipping_country' );
				$states      = WC()->countries->get_states( $for_country );

				if ( is_array( $states ) && empty( $states ) ) {

					$field_container = '<div class="form-row %1$s" id="%2$s" data-row = "' . esc_attr( $row_class ). '" style="display: none">%3$s</div>';

					$field .= '<input type="hidden" class="hidden" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="" ' . implode( ' ', $custom_attributes ) . ' placeholder="' . esc_attr( $args['placeholder'] ) . '" readonly="readonly" data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

				} elseif ( ! is_null( $for_country ) && is_array( $states ) ) {

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="state_select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ? $args['placeholder'] : esc_html__( 'Select an option&hellip;', 'woocommerce' ) ) . '"  data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '">
						<option value="">' . esc_html__( 'Select an option&hellip;', 'woocommerce' ) . '</option>';

					foreach ( $states as $ckey => $cvalue ) {
						$field .= '<option value="' . esc_attr( $ckey ) . '" ' . selected( $value, $ckey, false ) . '>' . esc_html( $cvalue ) . '</option>';
					}

					$field .= '</select>';

				} else {

					$field .= '<input type="text" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $value ) . '"  placeholder="' . esc_attr( $args['placeholder'] ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" ' . implode( ' ', $custom_attributes ) . ' data-input-classes="' . esc_attr( implode( ' ', $args['input_class'] ) ) . '"/>';

				}

				break;
			case 'textarea':
				$field .= '<textarea name="' . esc_attr( $key ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '" ' . ( empty( $args['custom_attributes']['rows'] ) ? ' rows="2"' : '' ) . ( empty( $args['custom_attributes']['cols'] ) ? ' cols="5"' : '' ) . implode( ' ', $custom_attributes ) . '>' . esc_textarea( $value ) . '</textarea>';

				break;
			case 'checkbox':
				$field = '<label class="checkbox ' . implode( ' ', $args['label_class'] ) . '" ' . implode( ' ', $custom_attributes ) . '>
						<input type="' . esc_attr( $args['type'] ) . '" class="input-checkbox ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="1" ' . checked( $value, 1, false ) . ' /> ' . $args['label'] . $required . '</label>';

				break;
			case 'text':
			case 'password':
			case 'datetime':
			case 'datetime-local':
			case 'date':
			case 'month':
			case 'time':
			case 'week':
			case 'number':
			case 'email':
			case 'url':
			case 'tel':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-text ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" placeholder="' . esc_attr( $args['placeholder'] ) . '"  value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

				break;
			case 'hidden':
				$field .= '<input type="' . esc_attr( $args['type'] ) . '" class="input-hidden ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" value="' . esc_attr( $value ) . '" ' . implode( ' ', $custom_attributes ) . ' />';

				break;
			case 'select':
				$field   = '';
				$options = '';

				if ( ! empty( $args['options'] ) ) {
					foreach ( $args['options'] as $option_key => $option_text ) {
						if ( '' === $option_key ) {
							// If we have a blank option, select2 needs a placeholder.
							if ( empty( $args['placeholder'] ) ) {
								$args['placeholder'] = $option_text ? $option_text : __( 'Choose an option', 'woocommerce' );
							}
							$custom_attributes[] = 'data-allow_clear="true"';
						}
						$options .= '<option value="' . esc_attr( $option_key ) . '" ' . selected( $value, $option_key, false ) . '>' . esc_html( $option_text ) . '</option>';
					}

					$field .= '<select name="' . esc_attr( $key ) . '" id="' . esc_attr( $args['id'] ) . '" class="select ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" ' . implode( ' ', $custom_attributes ) . ' data-placeholder="' . esc_attr( $args['placeholder'] ) . '">
							' . $options . '
						</select>';
				}

				break;
			case 'radio':
				$label_id .= '_' . current( array_keys( $args['options'] ) );
											
				if ( ! empty( $args['options'] ) ) {
					$field .= '<div class="bew-input-radio">';
					foreach ( $args['options'] as $option_key => $option_text ) {						
						$field .= '<input type="radio" class="input-radio ' . esc_attr( implode( ' ', $args['input_class'] ) ) . '" value="' . esc_attr( $option_key ) . '" name="' . esc_attr( $key ) . '" ' . implode( ' ', $custom_attributes ) . ' id="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '"' . checked( $value, $option_key, false ) . ' />';
						$field .= '<label for="' . esc_attr( $args['id'] ) . '_' . esc_attr( $option_key ) . '" class="radio ' . implode( ' ', $args['label_class'] ) . esc_attr( $option_key ) . '">' . esc_html( $option_text ) . '</label>';
					}
					$field .= '</div>';
				}

				break;

			case 'info-text':
				$field .= '<div class="info-text" id="' . esc_attr( $args['id'] ) . '-info-text" aria-hidden="true" ' . implode( ' ', $custom_attributes ). '>' . wp_kses_post( $info_text ) . '</div>';

				break;
		}

		if ( ! empty( $field ) ) {
			$field_html = '';

			$field_html .= '<span class="woocommerce-input-wrapper">' . $field;

			if ( $args['description'] ) {
				$field_html .= '<span class="description" id="' . esc_attr( $args['id'] ) . '-description" aria-hidden="true">' . wp_kses_post( $args['description'] ) . '</span>';
			}
			
			if ( $args['label'] && 'checkbox' !== $args['type'] ) {
				$field_html .= '<label for="' . esc_attr( $label_id ) . '" class="' . esc_attr( implode( ' ', $args['label_class'] ) ) . '">' . wp_kses_post( $args['label'] ) . $required . '</label>';
			}
			$field_html .= '</span>';

			$container_class = esc_attr( implode( ' ', $args['class'] ) );
			$container_id    = esc_attr( $args['id'] ) . '_field';
			$field           = sprintf( $field_container, $container_class, $container_id, $field_html );
		}

		/**
		 * Filter by type.
		 */
		$field = apply_filters( 'bew_woocommerce_form_field_' . $args['type'], $field, $key, $args, $value );

		/**
		 * General filter on form fields.
		 *
		 * @since 3.4.0
		 */
		$field = apply_filters( 'bew_woocommerce_form_field', $field, $key, $args, $value );

		if ( $args['return'] ) {
			return $field;
		} else {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo $field;
		}
	}

	

}

