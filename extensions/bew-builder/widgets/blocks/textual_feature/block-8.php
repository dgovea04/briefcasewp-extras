<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Textual_Feature_Block_8 {

	const ID = 8;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content' );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_extended( $widget, $id, [
			'default' => [
				[
					'icon' => 'icon-layers',
					'title' => esc_html__( 'Lego Base', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'icon-puzzle',
					'title' => esc_html__( 'Page Builder', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'icon-mobile',
					'title' => esc_html__( 'Responsive Design', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
			],
		] );
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

			<div class="bew-row gap-y">

				<?php foreach ( $settings['t8_features'] as $feature ) : ?>
				<div class="bew-col-12 bew-col-lg-4">
					<div class="card card-bordered text-center">
						<div class="card-block  <?php echo esc_attr( $feature['gray_bg'] ); ?>">
							<p><i class="<?php echo esc_attr( $feature['icon'] ); ?> fs-50" style="color: <?php echo esc_attr( $feature['color'] ); ?>"></i></p>
							<h4 class="card-title"><?php echo $feature['title']; ?></h4>
							<p class="card-text"><?php echo $feature['text']; ?></p>
							<a class="fw-600 fs-12" href="<?php echo esc_url( $feature['more_link'] ); ?>"><?php echo $feature['more_text']; ?> <i class="fa fa-chevron-right fs-9 pl-5"></i></a>
						</div>
					</div>
				</div>
				<?php endforeach; ?>

			</div>

		</div></section>
		<?php
	}

	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>

			<div class="bew-row gap-y">

				<# _.each( settings.t8_features, function( feature ) { #>
					<div class="bew-col-12 bew-col-lg-4">
						<div class="card card-bordered text-center">
							<div class="card-block  {{ feature.gray_bg }}">
								<p><i class="{{ feature.icon }} fs-50" style="color: {{ feature.color }}"></i></p>
								<h4 class="card-title">{{{ feature.title }}}</h4>
								<p class="card-text">{{{ feature.text }}}</p>
								<a class="fw-600 fs-12" href="{{ feature.more_link }}">{{{ feature.more_text }}} <i class="fa fa-chevron-right fs-9 pl-5"></i></a>
							</div>
						</div>
					</div>
				<# } ); #>

			</div>

		</div></section>
		<?php
	}

}