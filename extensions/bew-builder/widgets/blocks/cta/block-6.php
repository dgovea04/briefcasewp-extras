<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_6 {

	const ID = 6;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Get it now', 'thesaas' ),
		] );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'We waited until we could do it right. Then we did! Instead of creating a carbon copy.', 'thesaas' ),
		] );
		The_Controls::add_image( $widget, $id, [
			'default' => thesaas_get_img_uri( 'placeholder-phone.png' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'store_links', [
			'apple' => '#',
			'google' => '#',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'text-center pb-0 overflow-hidden' ]); ?>
					<header class="section-header">
						<h2 class="display-4"><?php echo $setting['t6_header_text'] ?></h2>
						<br>
						<p class="lead"><?php echo $setting['t6_text'] ?></p>
					</header>

					<div class="text-center gap-multiline-items-2 my-50">
							<?php $widget->html( 'store', ['store' => 'apple'] ); ?>
							<?php $widget->html( 'store', ['store' => 'google'] ); ?>
					</div>

					<div class="text-center">
						<br><br>
						<img src="<?php echo esc_url( $setting['t6_image']['url'] ) ?>" alt="<?php echo esc_attr( $setting['t6_header_text'] ); ?>" data-aos="slide-up">
					</div>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'text-center pb-0 overflow-hidden' ]); ?>
					<header class="section-header">
						<h2 class="display-4">{{{ settings.t6_header_text }}}</h2>
						<br>
						<p class="lead">{{{ settings.t6_text }}}</p>
					</header>

					<div class="text-center gap-multiline-items-2 my-50">
							<?php $widget->js( 'store', ['store' => 'apple'] ); ?>
							<?php $widget->js( 'store', ['store' => 'google'] ); ?>
					</div>

					<div class="text-center">
						<br><br>
						<img src="{{ settings.t6_image.url }}" alt="{{ settings.t6_header_text }}" data-aos="slide-up">
					</div>
		</div></section>
		<?php
	}

}
