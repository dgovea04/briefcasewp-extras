<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_3 {

	const ID = 3;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'image',
				'padding_bottom' => 80,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Modern BriefcaseWP Blocks', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 56, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Custom pre-made Woocommerce blocks for Elementor. Create your E-commerce website now!', 'briefcasewp-extras' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'primary_button', [
			'text' => esc_html__( 'Blocks', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'round' => true,
			'color' => 'btn-info',
			'width' => 200,
		] );

		$widget->panel( 'secondary_button', [
			'text' => esc_html__( 'Features', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'outline' => true,
			'round' => true,
			'color' => 'btn-white',
			'width' => 200,
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
		if ( 'yes' == $settings['t3_fullscreen'] ) {
			$full_row = 'h-full';
		}

		?>
		<?php $widget->html('header_tag'); ?>

			<div class="bew-row <?php echo $full_row; ?>">
				<div class="bew-col-12 bew-col-lg-8 offset-lg-2 align-self-center">

					<h1 class="display-4"><?php echo $settings['t3_header_text']; ?></h1>					
					<br>
					<p class="lead text-white fs-15 fs-20"><?php echo $settings['t3_text']; ?></p>					

					<br><br><br>

					<?php $widget->html('button', [ 'class' => 'mx-1 mb-3' ]); ?>
					<?php $widget->html('button2', [ 'class' => 'hidden-sm-down1 mx-1 mb-3' ]); ?>
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
		if ( 'yes' == settings.t3_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag'); ?>

			<div class="bew-row {{ full_row }}">
				<div class="bew-col-12 bew-col-lg-8 offset-lg-2 align-self-center">

					<h1 class="display-4">{{{ settings.t3_header_text }}}</h1>					
					<br>
					<p class="lead text-white fs-15 fs-20">{{{ settings.t3_text }}}</p>

					<br><br><br>

					<?php $widget->js('button', [ 'class' => 'mx-1 mb-3' ]); ?>
					<?php $widget->js('button2', [ 'class' => 'hidden-sm-down1 mx-1 mb-3' ]); ?>
				</div>


				<div class="bew-col-12 align-self-end text-center">
					<?php $widget->js('flash_down'); ?>
				</div>

			</div>

		</div></header>
		<?php
	}

}
