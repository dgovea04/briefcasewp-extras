<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_11 {

	const ID = 11;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'image',
				'bg_image' => bew_get_img_uri( 'bg-laptop.jpg' ),
				'overlay' => 7,
				'padding_top' => 0,
				'padding_bottom' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'COMING SOON', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 88, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website.', 'briefcasewp-extras' ) ] );

    $widget->add_control(
      't'. $id . '_counter_header',
      Bew_Controls::option_text( esc_html__( 'Counter header', 'briefcasewp-extras' ), [], [
        'default' => esc_html__( 'We will be ready in', 'briefcasewp-extras' ),
      ] )
    );

    $widget->add_control(
      't'. $id . '_counter_deadline',
      Bew_Controls::option_text( esc_html__( 'Counter deadline', 'briefcasewp-extras' ), [], [
        'default' => '2019/12/25',
        'placeholder' => 'YYYY/MM/DD',
      ] )
    );

		Bew_Controls::end_section( $widget );

    $widget->panel( 'flash_down', [
      'inverse' => true,
    ] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t11_fullscreen'] ) {
			$full_row = 'h-full';
		}
		
		?>
		<?php $widget->html('header_tag'); ?>

      <div class="bew-row <?php echo $full_row; ?> align-items-center coming-soon-styles">
        <div class="bew-col-12 bew-col-lg-8 offset-lg-2">

          <h1 class="display-2 ls-3"><?php echo $settings['t11_header_text']; ?></h1>
          <br><br>
          <p class="lead opacity-80"><?php echo $settings['t11_text']; ?></p>

          <br><br>

          <h3><?php echo $settings['t11_counter_header']; ?></h3>
          <br>
          <div class="countdown countdown-sm countdown-outline countdown-inverse countdown-uppercase" data-countdown="<?php echo esc_attr( $settings['t11_counter_deadline'] ) ?>"></div>

        </div>

        <div class="bew-col-12 align-self-end text-center">
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
		if ( 'yes' == settings.t11_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag'); ?>

      <div class="bew-row {{ full_row }} align-items-center coming-soon-styles">
        <div class="bew-col-12 bew-col-lg-8 offset-lg-2">

          <h1 class="display-2 text-uppercase ls-3">{{{ settings.t11_header_text }}}</h1>
          <br><br>
          <p class="lead opacity-80">{{{ settings.t11_text }}}</p>

          <br><br>

          <h3>{{{ settings.t11_counter_header }}}</h3>
          <br>
          <div class="countdown countdown-sm countdown-outline countdown-inverse countdown-uppercase" data-countdown="{{ settings.t11_counter_deadline }}"></div>

        </div>

        <div class="bew-col-12 align-self-end text-center">
          <?php $widget->js('flash_down'); ?>
        </div>
      </div>

		</div></header>
		<?php
	}

}
