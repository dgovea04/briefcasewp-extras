<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Feature_Block_4 {

	const ID = 4;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_color', 'section_inverse', 'width_container' ],
			'bg_color' => '#f64d62',
			'inverse' => true,
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Endless Features', 'bew-extras' ) ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Interactively maintain vertical portals through competitive strategic theme areas. Collaboratively reintermediate cross-platform data rather than state of the art value.', 'bew-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('placeholder-phone-2.png') ] );
		Bew_Controls::add_image_shadow( $widget, $id );
		Bew_Controls::end_section( $widget );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_full( $widget, $id, [
			'default' => [
				[
					'icon' => 'fa fa-cab',
					'color' => '#fff',
					'title' => esc_html__( 'Fast & easy work', 'bew-extras' ),
					'text' => esc_html__( 'Rapidiously evisculate business markets and cooperative results. Globally leverage other high.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-pie-chart',
					'color' => '#fff',
					'title' => esc_html__( 'Create result', 'bew-extras' ),
					'text' => esc_html__( 'Rapidiously evisculate business markets and cooperative results. Globally leverage other high.', 'bew-extras' ),
				],
			],
		] );
		Bew_Controls::end_section( $widget );
	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = $settings['t4_image']['url'];
		?>
		<?php $widget->html('section_tag', [ 'class' => 'pb-0 overflow-hidden' ]); ?>

			<div class="bew-row align-items-center">
				<div class="bew-col-12 bew-col-md-6 pb-70">
					<h2><?php echo $settings['t4_header_text'] ?></h2>
					<p><?php echo $settings['t4_text'] ?></p>

					<hr class="w-50 ml-0">

					<div class="flexbox flex-grow-all">
						<?php foreach ( $settings['t4_features'] as $feature ) : ?>
						<div>
							<p class="fs-30"><i class="<?php echo esc_attr( $feature['icon'] ); ?>" style="color: <?php echo esc_attr( $feature['color'] ); ?>"></i></p>
							<h6 class="text-uppercase fw-600"><?php echo $feature['title']; ?></h6>
							<p><?php echo $feature['text']; ?></p>
						</div>
						<?php endforeach; ?>
					</div>
				</div>

				<div class="bew-col-12 bew-col-md-6 align-self-end text-center">
					<img class="<?php echo esc_attr( $settings['t4_image_shadow'] ); ?>" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t4_header_text'] ); ?>" data-aos="fade-up">
				</div>
			</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'pb-0 overflow-hidden' ]); ?>

			<div class="bew-row align-items-center">
				<div class="bew-col-12 bew-col-md-6 pb-70">
					<h2>{{{ settings.t4_header_text }}}</h2>
					<p>{{{ settings.t4_text }}}</p>

					<hr class="w-50 ml-0">

					<div class="flexbox flex-grow-all">
						<# _.each( settings.t4_features, function( feature ) { #>
						<div>
							<p class="fs-30"><i class="{{ feature.icon }}" style="color: {{ feature.color }}"></i></p>
							<h6 class="text-uppercase fw-600">{{{ feature.title }}}</h6>
							<p>{{{ feature.text }}}</p>
						</div>
						<# } ); #>
					</div>
				</div>

				<div class="bew-col-12 bew-col-md-6 align-self-end text-center">
					<img class="{{ settings.t4_image_shadow }}" src="{{ settings.t4_image.url }}" alt="{{ settings.t4_header_text }}" data-aos="fade-up">
				</div>
			</div>

		</div></section>
		<?php
	}

}
