<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_5 {

	const ID = 5;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'gradient',
				'overlay' => 0,
				'padding_bottom' => 0,				
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'BriefcaseWP', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 60, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website.', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri( 'phones-banner.png' ) ] );
		Bew_Controls::end_section( $widget );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t5_fullscreen'] ) {
			$full_row = 'h-full';
		}

		$image = $settings['t5_image']['url'];
		?>
		<?php $widget->html('header_tag', [ 'overflow' => 'overflow-visible' ]); ?>

			<div class="bew-row <?php echo $full_row; ?>">
				<div class="bew-col-12 bew-col-lg-8 offset-lg-2 align-self-center pt-0">

					<h1><?php echo $settings['t5_header_text']; ?></h1>
					<br>
					<p class="lead fs-20 fs-15"><?php echo $settings['t5_text']; ?></p>

				</div>

				<div class="bew-col-12 align-self-end text-center img-mn-300 img-mb-20">
					<img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t5_header_text'] ); ?>" data-aos="fade-up" data-aos-offset="0">
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
		if ( 'yes' == settings.t5_fullscreen ) {
			full_row = 'h-full';
		}
		#>		
		<?php $widget->js('header_tag', [ 'overflow' => 'overflow-visible' ]); ?>

			<div class="bew-row {{ full_row }}">
				<div class="bew-col-12 bew-col-lg-8 offset-lg-2 align-self-center pt-0">

					<h1>{{{ settings.t5_header_text }}}</h1>
					<br>
					<p class="lead fs-20 fs-15">{{{ settings.t5_text }}}</p>

				</div>

				<div class="bew-col-12 align-self-end text-center img-mn-300 img-mb-20">
					<img src="{{ settings.t5_image.url }}" alt="{{ settings.t5_header_text }}" data-aos="fade-up" data-aos-offset="0">
				</div>

			</div>

		</div></header>
		<?php
	}

}
