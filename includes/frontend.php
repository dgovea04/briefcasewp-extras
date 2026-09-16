<?php
namespace BriefcasewpExtras;

class Frontend{

    private static $_instance = null;
   

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    private function __construct() {	 
	
    }
				
	function bew_woocommerce_quantity_input( $args = array(), $product = null, $echo = true ) {
		if ( is_null( $product ) ) {
			$product = $GLOBALS['product'];
		}

		$defaults = array(
			'input_id'     => uniqid( 'quantity_' ),
			'input_name'   => 'quantity',
			'input_value'  => '1',
			'classes'      => apply_filters( 'woocommerce_quantity_input_classes', array( 'input-text', 'qty', 'text' ), $product ),
			'max_value'    => apply_filters( 'woocommerce_quantity_input_max', -1, $product ),
			'min_value'    => apply_filters( 'woocommerce_quantity_input_min', 0, $product ),
			'step'         => apply_filters( 'woocommerce_quantity_input_step', 1, $product ),
			'pattern'      => apply_filters( 'woocommerce_quantity_input_pattern', has_filter( 'woocommerce_stock_amount', 'intval' ) ? '[0-9]*' : '' ),
			'inputmode'    => apply_filters( 'woocommerce_quantity_input_inputmode', has_filter( 'woocommerce_stock_amount', 'intval' ) ? 'numeric' : '' ),
			'product_name' => $product ? $product->get_title() : '',
			'placeholder'  => apply_filters( 'woocommerce_quantity_input_placeholder', '', $product ),
		);

		$args = apply_filters( 'woocommerce_quantity_input_args', wp_parse_args( $args, $defaults ), $product );

		// Apply sanity to min/max args - min cannot be lower than 0.
		$args['min_value'] = max( $args['min_value'], 0 );
		$args['max_value'] = 0 < $args['max_value'] ? $args['max_value'] : '';

		// Max cannot be lower than min if defined.
		if ( '' !== $args['max_value'] && $args['max_value'] < $args['min_value'] ) {
			$args['max_value'] = $args['min_value'];
		}

		ob_start();

		// The template name. 
		$template_name = 'global/bew-quantity-input.php'; 
		
		// default template
		$template_path = ''; // use default which is usually "woocommerce"
		
		// default path (look in plugin file!)
		$default_path = BEW_EXTRAS_PATH . '/woocommerce/';
						
		//wc_get_template( 'global/bew-quantity-input.php' ); 
		wc_get_template($template_name, $args, $template_path, $default_path);

		if ( $echo ) {
			// phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
			echo ob_get_clean();
		} else {
			return ob_get_clean();
		}
	}
	
	public static function extend_post_allowed_html() {
		$allow_html = wp_kses_allowed_html( 'post' );
		foreach ( $allow_html as $key => $value ) {
			if ( in_array( $key, array( 'div', 'span', 'a', 'input', 'form', 'select', 'option', 'table' ) ) ) {
				$allow_html[ $key ]['data-*'] = 1;
			}
		}

		return array_merge( $allow_html, array(
				'input' => array(
					'type'         => 1,
					'id'           => 1,
					'name'         => 1,
					'class'        => 1,
					'placeholder'  => 1,
					'autocomplete' => 1,
					'style'        => 1,
					'value'        => 1,
					'data-*'       => 1,
					'size'         => 1,
					'max'          => 1,
					'min'          => 1,
					'step'         => 1,
				),
				'style'  => array(
					'id'     => 1,
					'class'  => 1,
					'type'  => 1,
				),
			)
		);
	}
		    
}

Frontend::instance();