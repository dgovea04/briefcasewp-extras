<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_9 {

	const ID = 9;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'image',
				'bg_image' => bew_get_img_uri( 'bg-parallax.png' ),
				'bg_image_type' => 'parallax',
				'header_inverse' => '',
				'padding_top' => 0,
				'padding_bottom' => 0,
				'overlay' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'BriefcaseWP', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 56, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'The fastest and easiest way to create your E-commerce website. Why are you waiting?. Get started Now!', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri( 'templates-a.jpg' ) ] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'primary_button', [
			'text' => esc_html__( 'Download now', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'color' => 'btn-dark',
			'width' => 200,
		] );

		$widget->panel( 'secondary_button', [
			'text' => esc_html__( 'Features', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'outline' => true,
			'color' => 'btn-dark',
			'width' => 200,
		] );

	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t9_fullscreen'] ) {
			$full_row = 'h-full';
		}

		$image = $settings['t9_image']['url'];

		?>
		<?php $widget->html('header_tag'); ?>

	        <div class="bew-row <?php echo $full_row; ?>">
	          <div class="bew-col-12 bew-col-lg-6 align-self-center text-center text-lg-left">

	            <h1 class=""><?php echo $settings['t9_header_text']; ?></h1>
	            <br>
	            <p class="lead fs-20 fs-16"><?php echo $settings['t9_text']; ?></p>

	            <br><br>

	            <div>
	            	<?php $widget->html('button'); ?>
	            	<?php $widget->html('button2', [ 'class' => 'ml-8 hidden-sm-down' ]); ?>
	            </div>

	          </div>


	          <div class="bew-col-12 bew-col-lg-6 align-self-end text-right hidden-md-down">
	            <img src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t9_header_text'] ); ?>" data-aos="slide-up" data-aos-offset="0">
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
		if ( 'yes' == settings.t9_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag'); ?>

	        <div class="bew-row {{ full_row }}">
	          <div class="bew-col-12 bew-col-lg-6 align-self-center text-center text-lg-left">

	            <h1 class="display-4">{{{ settings.t9_header_text }}}</h1>
	            <br>
	            <p class="lead fs-20 fs16">{{{ settings.t9_text }}}</p>

	            <br><br>

	            <div>
	            	<?php $widget->js('button'); ?>
	            	<?php $widget->js('button2', [ 'class' => 'ml-8 hidden-sm-down' ]); ?>
	            </div>

	          </div>


	          <div class="bew-col-12 bew-col-lg-6 align-self-end text-right hidden-md-down">
	            <img src="{{ settings.t9_image.url }}" alt="{{ settings.t9_header_text }}" data-aos="slide-up" data-aos-offset="0">
	          </div>

	        </div>

		</div></header>
		<?php
	}

}
