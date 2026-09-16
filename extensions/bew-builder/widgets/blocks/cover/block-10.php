<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_10 {

	const ID = 10;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
		        'background' => 'image',
		        'bg_image' => bew_get_img_uri( 'bew-blur.png' ),
				'overlay' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri( 'cr.png' ) ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'BriefcaseWP', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website.', 'briefcasewp-extras' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Purchase now - $49', 'briefcasewp-extras' ),
			'size' => 'btn-xl',
			'outline' => true,
			'round' => true,
			'color' => 'btn-white',
		] );

		$widget->panel( 'info_text', [
			'text' => esc_html__( 'or view demo', 'briefcasewp-extras' ),
			'link' => '#',
		] );

		$widget->panel( 'flash_down', [
			'inverse' => true,
		] );
	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t10_fullscreen'] ) {
			$full_row = 'h-full';
		}

		$image = $settings['t10_image']['url'];
		?>
		<?php $widget->html('header_tag'); ?>

      <div class="bew-row <?php echo $full_row; ?>">
        <div class="bew-col-12 bew-col-lg-8 offset-lg-2 align-self-center">

        	<?php if ( ! empty( $image ) ) : ?>
          <p><img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t10_header_text'] ); ?>"></p>         
        	<?php endif; ?>
          <h1 class="display-4"><?php echo $settings['t10_header_text']; ?></h1>
          <br>
          <p class="fs-20"><?php echo $settings['t10_text']; ?></p>

          <hr class="w-80">

          <p>
          	<?php $widget->html('button'); ?>
            	<br>
            	<?php $widget->html('info'); ?>
          </p>

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
		if ( 'yes' == settings.t10_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag'); ?>

      <div class="bew-row {{ full_row }}">
        <div class="bew-col-12 bew-col-lg-8 offset-lg-2 align-self-center">

          <# if ( '' !== settings.t10_image.url ) { #>
          <p><img src="{{ settings.t10_image.url }}" alt="{{ settings.t10_header_text }}"></p>          
          <# } #>
          <h1 class="display-4">{{{ settings.t10_header_text }}}</h1>
          <br>
          <p class="fs-20">{{{ settings.t10_text }}}</p>

          <hr class="w-80">

          <p>
          	<?php $widget->js('button'); ?>
            	<br>
            	<?php $widget->js('info'); ?>
          </p>

        </div>

				<div class="bew-col-12 align-self-end text-center">
					<?php $widget->js('flash_down'); ?>
				</div>
      </div>

		</div></header>
		<?php
	}

}
