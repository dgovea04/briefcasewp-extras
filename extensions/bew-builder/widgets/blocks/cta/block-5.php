<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_5 {

	const ID = 5;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_image', 'section_inverse', 'overlay' ],
			'bg_image' => thesaas_get_img_uri('placeholder-bg.jpg'),
			'inverse'  => true,
			'overlay'  => 7,
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_image( $widget, $id, [
			'default' => thesaas_get_img_uri( 'placeholder-logo-light.png' ),
		] );
		The_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Download now', 'thesaas' ),
		] );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Continually e-enable vertical schemas with top-line infomediaries. Energistically restore real-time core competencies and compelling leadership skills.', 'thesaas' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'rating', [
			'stars_text' => esc_html__( 'Based on 3,000+ reviews', 'thesaas' ),
		] );

		$widget->panel( 'store_links', [
			'apple' => '#',
			'google' => '#',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'bg-img py-150' ]); ?>
					<div class="section-dialog text-center">
						<p><img src="<?php echo esc_url( $setting['t5_image']['url'] ) ?>" alt="logo"></p>
						<br>
						<h2><?php echo $setting['t5_header_text'] ?></h2>
						<p><?php echo $setting['t5_text'] ?></p>

						<hr class="w-50">

						<div class="rating">
							<?php
								for ( $i=0; $i<5; $i++) {
									if ( $setting['t5_stars']['size'] > $i ) {
										echo '<label class="fa fa-star active"></label>';
									}
									else {
										echo '<label class="fa fa-star"></label>';
									}
								}
							?>
						</div>
						<p><small><?php echo $setting['t5_stars_text'] ?></small></p>

						<div class="text-center gap-multiline-items-2 my-30">
							<?php $widget->html( 'store', ['store' => 'apple'] ); ?>
							<?php $widget->html( 'store', ['store' => 'google'] ); ?>
						</div>

					</div>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'bg-img py-150' ]); ?>
					<div class="section-dialog text-center">
						<p><img src="{{ settings.t5_image.url }}" alt="logo"></p>
						<br>
						<h2>{{{ settings.t5_header_text }}}</h2>
						<p>{{{ settings.t5_text }}}</p>

						<hr class="w-50">

						<div class="rating">
			<#
			for ( var i = 0; i < 5; i++ ) {
				if ( settings.t5_stars.size > i ) {
					print( '<label class="fa fa-star active"></label>' );
				}
				else {
					print( '<label class="fa fa-star"></label>' );
				}
			}
			#>
						</div>
						<p><small>{{{ settings.t5_stars_text }}}</small></p>

						<div class="text-center gap-multiline-items-2 my-30">
							<?php $widget->js( 'store', ['store' => 'apple'] ); ?>
							<?php $widget->js( 'store', ['store' => 'google'] ); ?>
						</div>

					</div>
		</div></section>
		<?php
	}

}
