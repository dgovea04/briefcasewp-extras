<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_2 {

	const ID = 2;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'color',
				'overlay' => 0,
				'padding_bottom' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'BriefcaseWP', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 60, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website.', 'briefcasewp-extras' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'See Blocks', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'round' => true,
			'color' => 'btn-white',
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
		if ( 'yes' == $settings['t2_fullscreen'] ) {
			$full_row = 'h-full';
		}
		
		$custom_position = '';
		$custom_position_content = '';
		$hr_align  = '';
		if ( 'yes' == $settings['t2_custom_position'] ) {
			$custom_position = 'custom-position';
			$custom_position_content = 'custom-position-content';
			$hr_align = 'hr-align-' .$settings['t2_text_align']; 
		}
		
		
		
		?>
		<?php $widget->html('header_tag'); ?>

			<div class="bew-row <?php echo $full_row; ?> <?php echo $custom_position; ?> ">
				<div class="<?php echo $custom_position_content; ?> bew-col-12 align-self-center">

					<h1 class="display-1"><?php echo $settings['t2_header_text']; ?></h1>					
					<br>
					<p class="fs-16 fs-20 w-full w-600 mx-auto"><?php echo $settings['t2_text']; ?></p>					

					<hr class="w-80 <?php echo $hr_align; ?> ">
					<br>

					<?php $widget->html('button'); ?>
				</div>
				
				<?php if ( 'yes' == $settings['t2_flash_down'] ) { ?>
				<div class="bew-col-12 align-self-end text-center pb-50">
					<?php $widget->html('flash_down'); ?>
				</div>
				<?php } ?>

			</div>

		</div></header>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<#
		var full_row = '';
		if ( 'yes' == settings.t2_fullscreen ) {			
			full_row = 'h-full';
			
		}
		
		var custom_position = '';
		var custom_position_content = '';
		var hr_align = '';
		if ( 'yes' == settings.t2_custom_position ) {
			custom_position = 'custom-position';
			custom_position_content = 'custom-position-content';
			hr_align = 'hr-align-' + settings.t2_text_align; 
		}
						
		#>
		<?php $widget->js('header_tag'); ?>

			<div class="bew-row {{ full_row }} {{ custom_position }}">
				<div class="{{ custom_position_content }} bew-col-12 align-self-center">

					<h1 class="display-1">{{{ settings.t2_header_text }}}</h1>
					
					<br>
					<p class="fs-16 fs-20 w-full w-600 mx-auto">{{{ settings.t2_text }}}</p>					

					<hr class="w-80 {{ hr_align }}">
					<br>

					<?php $widget->js('button'); ?>
				</div>
				
				
				<# if ( 'yes' == settings.t2_flash_down ) { #>
				<div class="bew-col-12 align-self-end text-center pb-50">
					<?php $widget->js('flash_down'); ?>
				</div>
				<# } #>
			</div>

		</div></header>
		<?php
	}

}
