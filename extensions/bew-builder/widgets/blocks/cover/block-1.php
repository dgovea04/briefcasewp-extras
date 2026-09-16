<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_1 {

	const ID = 1;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'overlay' => 0,
				'fullscreen' => false,
				'bg_color' => '#ff9900',			
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_image( $widget, $id, [ 'default' => '' ] );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Header Title', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 40, 'default_unit' => 'px', 'selectors' => 'h1' ] );
		Bew_Controls::add_heading_typo( $widget, $id, ['name' => 'header' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Some description to put inside the header.', 'briefcasewp-extras' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'primary_button', [
			'text' => '',
			'round' => true,
			'color' => 'btn-white',
			'width' => 160,
		] );

		$widget->panel( 'secondary_button', [
			'text' => '',
			'outline' => true,
			'round' => true,
			'color' => 'btn-white',
			'width' => 160,
		] );

		$widget->panel( 'flash_down', [
			'inverse' => true,		
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t1_fullscreen'] ) {
			$full_row = 'h-full';
		}
		
		$custom_position = '';
		$custom_position_content = '';
		if ( 'yes' == $settings['t1_custom_position'] ) {
			$custom_position = 'custom-position';
			$custom_position_content = 'custom-position-content';
		}

		$image = $settings['t1_image']['url'];

		?>
		<?php $widget->html('header_tag'); ?>

      <div class="bew-row <?php echo $full_row; ?> <?php echo $custom_position; ?>">
        <div class="<?php echo $custom_position_content; ?> bew-col-12">
        	<?php if ( ! empty( $image ) ) : ?>
          <p><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t1_header_text'] ); ?>"></p>
          <br>
        	<?php endif; ?>
          <h1><?php echo $settings['t1_header_text']; ?></h1>
          <p class="fs-16 fs-20 opacity-70"><?php echo $settings['t1_text']; ?></p>

          <?php if ( ! empty( $settings['t1_btn_text'] ) || ! empty( $settings['t1_btn2_text'] ) ) : ?>
          	<br><br>
          <?php endif; ?>

					<?php $widget->html('button', [ 'class' => 'mr-16' ]); ?>
					<?php $widget->html('button2', [ 'class' => 'hidden-sm-down' ]); ?>
        </div>
				<?php if ( 'yes' == $settings['t1_flash_down'] ) { ?>
				<div class="bew-col-12 align-self-end text-center">
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
		if ( 'yes' == settings.t1_fullscreen ) {
			full_row = 'h-full';
		}
		
		var custom_position = '';
		var custom_position_content = '';
		if ( 'yes' == settings.t1_custom_position ) {
			custom_position = 'custom-position';
			custom_position_content = 'custom-position-content';
		}
		#>

		<?php $widget->js('header_tag'); ?>
      <div class="bew-row {{ full_row }} {{ custom_position }}">
        <div class="{{ custom_position_content }} bew-col-12">
          <# if ( '' !== settings.t1_image.url ) { #>
          <p><img src="{{ settings.t1_image.url }}" alt="{{ settings.t1_header_text }}"></p>
          <br>
          <# } #>
          <h1>{{{ settings.t1_header_text }}}</h1>
          <p class="fs-20 opacity-70">{{{ settings.t1_text }}}</p>

          <# if ( '' !== settings.t1_btn_text || '' !== settings.t1_btn2_text ) { #>
          <br><br>
          <# } #>
					<?php $widget->js('button', [ 'class' => 'mr-16' ]); ?>
					<?php $widget->js('button2', [ 'class' => 'hidden-sm-down' ]); ?>
        </div>
				<# if ( 'yes' == settings.t1_flash_down ) { #>
				<div class="bew-col-12 align-self-end text-center">
					<?php $widget->js('flash_down'); ?>
				</div>
				<# } #>
      </div>

		</div></header>
		<?php
	}

}
