<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Textual_Feature_Block_10 {

	const ID = 10;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content' );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_full( $widget, $id, [
			'default' => [
				[
					'icon' => 'icon-mobile',
					'title' => esc_html__( 'Responsive Design', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'icon-recycle',
					'title' => esc_html__( 'Reusable Code', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'icon-layers',
					'title' => esc_html__( 'Legi Base', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'icon-gears',
					'title' => esc_html__( 'Easy Customization', 'bew-extras' ),
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

				<?php foreach ( $settings['t10_features'] as $feature ) : ?>
				<div class="bew-col-12 bew-col-md-6 feature-3">
					<div class="feature-icon"><i class="<?php echo esc_attr( $feature['icon'] ); ?>" style="color: <?php echo esc_attr( $feature['color'] ); ?>; width: 55px;"></i></div>

					<div class="feature-description">
						<h6><?php echo $feature['title']; ?></h6>
						<p><?php echo $feature['text']; ?></p>
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

				<# _.each( settings.t10_features, function( feature ) { #>
				<div class="bew-col-12 bew-col-md-6 feature-3">
					<div class="feature-icon"><i class="{{ feature.icon }}" style="color: {{ feature.color }}; width: 55px;"></i></div>

					<div class="feature-description">
						<h6>{{{ feature.title }}}</h6>
						<p>{{{ feature.text }}}</p>
					</div>
				</div>
				<# } ); #>

			</div>

		</div></section>
		<?php
	}

}