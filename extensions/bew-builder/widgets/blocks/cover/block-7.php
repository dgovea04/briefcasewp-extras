<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_7 {

	const ID = 7;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
		        'background' => 'color',
				'bg_color_img' => 'waves',
				'bg_color' => '#242424',
		        'overlay' => 0,
		        'padding_bottom' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		$widget->add_control(
			't'. $id .'_typing_text',
			[
				'label' => esc_html__( 'Typing Text', 'briefcasewp-extras' ),
				'type' => Controls_Manager::TEXTAREA,
				'placeholder' => esc_html__( 'Enter your words', 'briefcasewp-extras' ),
				'default' => esc_html__( 'Shop, Single, Cart', 'briefcasewp-extras' ),
				'description' => esc_html__( 'Comma separated words', 'briefcasewp-extras' ),
			]
		);

		$widget->add_control(
			't'. $id .'_typing_delay',
			[
        'label' => esc_html__( 'Backspace delay', 'briefcasewp-extras' ),
        'type' => Controls_Manager::SLIDER,
        'default' => [
          'size' => 800,
          'unit' => 'px',
        ],
        'size_units' => [ 'px' ],
        'range' => [
          'px' => [
            'min' => 0,
            'max' => 5000,
            'step' => 100,
          ],
        ],
			]
		);

		$widget->add_control(
			't'. $id .'_typing_color',
			[
				'label' => esc_html__( 'Typing color', 'briefcasewp-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#27EFA1',
			]
		);

		$widget->add_control(
			't'. $id .'_prefix',
			[
				'label' => esc_html__( 'Prefix Text', 'briefcasewp-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => __( 'Creating <br> Modern <br> Custom', 'briefcasewp-extras' ),
				'placeholder' => esc_html__( 'Text to display before typing texts.', 'briefcasewp-extras' ),
				'label_block' => true,
				'separator' => 'before',
			]
		);

		$widget->add_control(
			't'. $id .'_postfix',
			[
				'label' => esc_html__( 'Postfix Text', 'briefcasewp-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( '', 'briefcasewp-extras' ),
				'placeholder' => esc_html__( 'Text to display after typing texts.', 'briefcasewp-extras' ),
				'label_block' => true,
			]
		);
		
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 82, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website.', 'briefcasewp-extras' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Purchase Now - $49', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'color' => 'btn-white',
		] );

		$widget->panel( 'info_text', [
			'text' => esc_html__( 'or purchase an Extended License', 'briefcasewp-extras' ),
			'link' => '#',
		] );

		$widget->panel( 'flash_down', [
			'display' => true,
			'inverse' => true,
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t7_fullscreen'] ) {
			$full_row = 'h-full';
		}

    $typing_text = esc_attr( $settings['t7_typing_text'] );
    $typing_delay = intval( esc_attr( $settings['t7_typing_delay']['size'] ) );
    $typing_color = esc_attr( $settings['t7_typing_color'] );
		?>
		<?php $widget->html('header_tag'); ?>

      <div class="bew-row <?php echo $full_row; ?> black-block-7">
        <div class="bew-col-12 bew-col-md-12 offset-md-1 text-center text-md-left align-self-center">          
		  <h1 class="fw-600 lh-11"><?php echo $settings['t7_prefix']; ?> <span style="color: <?php echo $typing_color; ?>" data-typing="<?php echo $typing_text; ?>" data-delay="<?php echo $typing_delay; ?>"></span><?php echo $settings['t7_postfix']; ?></h1>
		  <p class="fs-16 fs-20 opacity-70"><?php echo $settings['t7_text']; ?></p>
          <?php $widget->html('button'); ?>
        </div>

        <div class="bew-col-12 align-self-end text-center pb-70">
          <?php $widget->html('flash_down'); ?>
        </div>
      </div>

		</div></header>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<#
		var full_row = '';
		if ( 'yes' == settings.t7_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag'); ?>

	    <div class="bew-row {{ full_row }}">
	      <div class="bew-col-12 bew-col-md-12 offset-md-1 text-center text-md-left align-self-center">
	        <h1 class="fw-600 lh-11">{{{ settings.t7_prefix }}} <span style="color: {{ settings.t7_typing_color }}" data-typing="{{ settings.t7_typing_text }}" data-delay="{{ settings.t7_typing_delay.size }}"></span>{{{ settings.t7_postfix }}}</h1>
			<p class="fs-16 fs-20 opacity-70">{{{ settings.t7_text }}}</p>
	        <?php $widget->js('button'); ?>	        
	      </div>

	      <div class="bew-col-12 align-self-end text-center pb-70">
	        <?php $widget->js('flash_down'); ?>
	      </div>
	    </div>

		</div></header>
		<?php
	}

}
