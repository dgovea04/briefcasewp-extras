<?php
namespace BriefcasewpExtras\Modules\WooCheckout\Widgets;

use Elementor;
use Elementor\Plugin;
use Elementor\Controls_Manager;
use Elementor\Icons_Manager;  
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;   
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use Elementor\Scheme_Color;
use Elementor\Scheme_Typography;
use BriefcasewpExtras\Base\Base_Widget;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Woo_Checkout_Multistep_Actions extends Base_Widget {

	public function get_name() {
		return 'woo-checkout-multistep-actions';
	}

	public function get_title() {
		return __( 'Checkout Multistep Actions', 'bew-extras' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

	public function get_categories() {
		return [ 'bew-extras-checkout' ];
	}
	
	public function get_script_depends() {
		return [ 'woo-general' ];
	}
	
	public function is_reload_preview_required() {
		return true;
	}

	protected function _register_controls() {

	$this->start_controls_section(
		'woo_checkout_content_section',
		[
			'label' => __( 'Multistep Actions', 'bew-extras' ),
			'tab' => Controls_Manager::TAB_CONTENT,
		]
	);
	
	$this->add_control(
		'woo_checkout_multistep_layout',
		[
			'label' 	=> __( 'Layout', 'bew-extras' ),
			'type' 		=> Controls_Manager::SELECT,
			'default' 	=> 'np-layout',
			'options' 	=> [				
				'np-layout'  => __( 'Next/Prev', 'bew-extras' ),
				'rc-layout'  => __( 'Return/Continue', 'bew-extras' ),
			],            
		]
	);
	
	$this->add_control(
		'woo_checkout_multistep_back_show',
		[
			'label'         => __( 'Show/Hide Back to cart', 'bew-extras' ),
			'type'          => Controls_Manager::SWITCHER,
				'label_on'      => __( 'Show', 'bew-extras' ),
				'label_off'     => __( 'Hide', 'bew-extras' ),
			'return_value'  => 'yes',
			'default'       => 'yes',
		]
	);

	$this->add_control(
		'woo_checkout_multistep_back', [
			'label' => __( 'Back to Cart text', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Back to Cart' , 'bew-extras' ),
			'placeholder' => __( 'Custom text here' , 'bew-extras' ),
			'label_block' => true,	
			'condition' => [
			   'woo_checkout_multistep_back_show' => 'yes'
			],
		]
	);	
	
	$this->add_control(
		'woo_checkout_multistep_next', [
			'label' => __( 'Next text', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Next' , 'bew-extras' ),
			'placeholder' => __( 'Custom text here' , 'bew-extras' ),
			'label_block' => true,	
			'condition' => [
			   'woo_checkout_multistep_layout' => 'np-layout'
			],			
		]
	);

	$this->add_control(
		'woo_checkout_multistep_prev', [
			'label' => __( 'Prev text', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Prev' , 'bew-extras' ),
			'placeholder' => __( 'Custom text here' , 'bew-extras' ),
			'label_block' => true,
			'condition' => [
			   'woo_checkout_multistep_layout' => 'np-layout'
			],				
		]
	);

	$this->add_control(
		'woo_checkout_multistep_continue', [
			'label' => __( 'Continue To text', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Continue to' , 'bew-extras' ),
			'placeholder' => __( 'Custom text here' , 'bew-extras' ),
			'label_block' => true,
			'condition' => [
			   'woo_checkout_multistep_layout' => 'rc-layout'
			],			
		]
	);

	$this->add_control(
		'woo_checkout_multistep_return', [
			'label' => __( 'Return To text', 'bew-extras' ),
			'type' => Controls_Manager::TEXT,
			'default' => __( 'Return to' , 'bew-extras' ),
			'placeholder' => __( 'Custom text here' , 'bew-extras' ),
			'label_block' => true,
			'condition' => [
			   'woo_checkout_multistep_layout' => 'rc-layout'
			],			
		]
	);
	
	$this->end_controls_section();

	}
	
	protected function render() {
		if ( ( is_checkout() && !empty( is_wc_endpoint_url('order-received') ) ) ) return;
		
		$settings = $this->get_settings_for_display();
		
		$checkout_multistep_layout 	  = $settings['woo_checkout_multistep_layout'];
		$back_to_cart_show  = $settings['woo_checkout_multistep_back_show'];
		$back_to_cart   	= $settings['woo_checkout_multistep_back'];
		$next 	      		= $settings['woo_checkout_multistep_next'];
		$prev 	      		= $settings['woo_checkout_multistep_prev'];
		$continue 	  		= $settings['woo_checkout_multistep_continue'];
		$return 	  		= $settings['woo_checkout_multistep_return'];
		
		$show_login_step = '';
		
		?>
		<div id="form_actions" class= "bew-checkout-multistep" data-step="<?php echo $show_login_step ? 0 : 1; ?>">
			<?php
			if ($back_to_cart_show == 'yes'){
			?>
				<a class="back-to-cart" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><i class="ti-angle-left"></i><?php esc_html_e( $back_to_cart, 'bew-extras' ); ?></a>
			<?php
			}
			?>
			<div class="buttons <?php echo $checkout_multistep_layout; ?>">
					<a  class="button prev" href="#" name="checkout_prev_step" data-action="prev" data-text-cr="<?php echo esc_html__( $return, 'bew-extras' ); ?>"><i class="ti-angle-left"></i><span><?php echo esc_html_e( $prev, 'bew-extras' ); ?></span></a>
					<a  class="button next" href="#" name="checkout_next_step" data-action="next" data-text-cr=" <?php echo esc_html__( $continue, 'bew-extras' ); ?>"><span><?php echo esc_html_e( $next, 'bew-extras' ); ?><span></a>				
			</div>
		</div>
		<?php

		wp_localize_script( 'bew-checkout', 'checkoutActions', array(
				'next' 		 	  	=> esc_html__( $next, 'bew-extras' ),
			)
		);
		
	}

	protected function _content_template() {
		
	}
	
}

