<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_8 {

	const ID = 8;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );


		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'More Than You Think', 'bew-extras' ) ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Dramatically reintermediate effective applications after high-payoff core competencies. Authoritatively optimize collaborative benefits for error-free communities. Dramatically reintermediate effective applications after high-payoff core competencies. Authoritatively optimize collaborative benefits for error-free communities.', 'bew-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('placeholder-phone.png') ] );
		Bew_Controls::add_image_shadow( $widget, $id );
		Bew_Controls::end_section( $widget );


		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_full( $widget, $id, [
			'default' => [
				[
					'icon' => 'fa fa-tv',
					'color' => '#ffbe00',
					'title' => esc_html__( 'Responsive Design', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-code',
					'color' => '#ff4954',
					'title' => esc_html__( 'Clean Code', 'bew-extras' ),
					'text' => esc_html__( 'You can easily read, edit, and write your own code, or change everything.', 'bew-extras' ),
				],
			],
		] );
		Bew_Controls::end_section( $widget );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = $settings['t8_image']['url'];
		?>
		<?php $widget->html('section_tag', [ 'class' => 'pb-0' ]); ?>

			<div class="bew-row">

				<div class="bew-col-12 bew-col-md-6 text-center align-self-center">
					<img class="<?php echo esc_attr( $settings['t8_image_shadow'] ); ?> mr-40" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t8_header_text'] ); ?>">
				</div>

				<div class="bew-col-12 bew-col-md-6 align-self-center pb-70">
					<h3><?php echo $settings['t8_header_text'] ?></h3>
					<p><?php echo $settings['t8_text'] ?></p>

					<br><br>

					<div class="flexbox flex-grow-all gap-items-3">
						<?php foreach ( $settings['t8_features'] as $feature ) : ?>
						<div>
							<p><i class="<?php echo esc_attr( $feature['icon'] ); ?> fs-30" style="color: <?php echo esc_attr( $feature['color'] ); ?>"></i></p>
							<h5><?php echo $feature['title']; ?></h5>
							<p><?php echo $feature['text']; ?></p>
						</div>
						<?php endforeach; ?>
					</div>

				</div>
			</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'pb-0' ]); ?>

			<div class="bew-row">

				<div class="bew-col-12 bew-col-md-6 text-center align-self-center">
					<img class="{{ settings.t8_image_shadow }} mr-40" src="{{ settings.t8_image.url }}" alt="{{ settings.t8_header_text }}">
				</div>

				<div class="bew-col-12 bew-col-md-6 align-self-center pb-70">
					<h3>{{{ settings.t8_header_text }}}</h3>
					<p>{{{ settings.t8_text }}}</p>

					<br><br>

					<div class="flexbox flex-grow-all gap-items-3">
						<# _.each( settings.t8_features, function( feature ) { #>
						<div>
							<p><i class="{{ feature.icon }} fs-30" style="color: {{ feature.color }}"></i></p>
							<h5>{{{ feature.title }}}</h5>
							<p>{{{ feature.text }}}</p>
						</div>
						<# } ); #>
					</div>

				</div>
			</div>

		</div></section>
		<?php
	}

}
