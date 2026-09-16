<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Testimonial_Block_2 {

	const ID = 2;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content' );

		$widget->panel( 'testimonials', [ 'no_columns' => true ] );

	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$autoplay = 'false';
		if ( 'yes' == $settings['t2_swiper_autoplay'] ) {
			$autoplay = $settings['t2_swiper_delay']['size'];
		}
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

          	<div class="swiper-container" data-space-between="50" data-autoplay="<?php echo $autoplay; ?>">
	            <div class="swiper-wrapper pb-0">

					<?php foreach ( $settings['t2_testimonials'] as $item ) : ?>

		  			<div class="swiper-slide">
		          <blockquote class="blockquote">
		            <p class="lead"><?php echo $item['content']; ?></p>
		            <br>
		            <div><img class="avatar avatar-xl" src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>"></div>
		            <footer><?php echo $item['name']; ?></footer>
		          </blockquote>
		  			</div>

					<?php endforeach; ?>

				</div>

				<div class="swiper-pagination"></div>
          	</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>

	        <div class="swiper-container" data-space-between="50">
	            <div class="swiper-wrapper pb-0">

					<# _.each( settings.t2_testimonials, function( item ) { #>
			        <div class="swiper-slide">
			          <blockquote class="blockquote">
			            <p class="lead">{{{ item.content }}}</p>
			            <br>
			            <div><img class="avatar avatar-xl" src="{{ item.image.url }}" alt="{{ item.name }}"></div>
			            <footer>{{{ item.name }}}</footer>
			          </blockquote>
			        </div>
					<# } ); #>

				</div>

				<div class="swiper-pagination"></div>
	        </div>

		</div></section>
		<?php
	}

}
