<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_8 {

	const ID = 8;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'gradient',
				'color_1' => '#fdfbfb',
				'color_2' => '#eee',
				'header_inverse' => '',
				'overlay' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'BriefcaseWP', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 56, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website.', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri( 'bew-macbook.png' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Get started', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'color' => 'btn-warning',
		] );

		$widget->panel( 'info_text', [
			'text' => esc_html__( 'Already a member? Sign in', 'briefcasewp-extras' ),
			'link' => '#',
		] );

		$widget->panel( 'flash_down' );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t8_fullscreen'] ) {
			$full_row = 'h-full';
		}

		$image = $settings['t8_image']['url'];
		?>
		<?php $widget->html('header_tag', [ 'container' => '-wide' ]); ?>

      <div class="bew-row <?php echo $full_row; ?> align-items-center text-center text-md-left">

        <div class="bew-col-md-5 offset-md-1">
          <div class="px-30">
            <h1><?php echo $settings['t8_header_text']; ?></h1>            
            <p class="lead mx-auto"><?php echo $settings['t8_text']; ?></p>            
            <?php $widget->html('button'); ?>
            <p class="pt-8"><?php $widget->html('info'); ?></p>
          </div>
        </div>

        <div class="bew-col-md-6 img-outside-right hidden-sm-down py-20">
          <img class="" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t8_header_text'] ); ?>">
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
		if ( 'yes' == settings.t8_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag', [ 'container' => '-wide' ]); ?>

      <div class="bew-row {{ full_row }} align-items-center text-center text-md-left">

        <div class="bew-col-md-5 offset-md-1">
          <div class="px-30">
            <h1>{{{ settings.t8_header_text }}}</h1>            
            <p class="lead mx-auto">{{{ settings.t8_text }}}</p>           
            <?php $widget->js('button'); ?>
            <p class="pt-8"><?php $widget->js('info'); ?></p>
          </div>
        </div>

        <div class="bew-col-md-6 img-outside-right hidden-sm-down py-20">
          <img class="" src="{{ settings.t8_image.url }}" alt="{{ settings.t8_header_text }}">
        </div>

				<div class="bew-col-12 align-self-end text-center">
					<?php $widget->js('flash_down'); ?>
				</div>
      </div>

		</div></header>
		<?php
	}

}
