<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Textual_Feature_Block_6 {

	const ID = 6;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'wide_container' ],
			'wide_container' => true,
		] );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_extended( $widget, $id, [
			'default' => [
				[
					'icon' => 'icon-mobile',
					'color' => '#ffbe00',
					'title' => esc_html__( 'Responsive Design', 'bew-extras' ),
					'text' => esc_html__( 'Interactively productize worldwide potentialities before long-term high-impact initiatives. Completely disintermediate excellent leadership skills with client-centric content.', 'bew-extras' ),
					'gray_bg' => 'bg-gray',
				],
				[
					'icon' => 'icon-recycle',
					'color' => '#ffbe00',
					'title' => esc_html__( 'Reusable Code', 'bew-extras' ),
					'text' => esc_html__( 'Interactively productize worldwide potentialities before long-term high-impact initiatives. Completely disintermediate excellent leadership skills with client-centric content.', 'bew-extras' ),
				],
			],
		] );
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'p-0' ]); ?>

			<div class="bew-row no-gap text-center">

				<?php foreach ( $settings['t6_features'] as $feature ) : ?>
				<div class="bew-col-12 bew-col-md-6 <?php echo esc_attr( $feature['gray_bg'] ); ?> feature-padding">
					<p><i class="<?php echo esc_attr( $feature['icon'] ); ?> fs-50" style="color: <?php echo esc_attr( $feature['color'] ); ?>"></i></p>
					<h5><?php echo $feature['title']; ?></h5>
					<br>
					<p><?php echo $feature['text']; ?></p>
					<br>
					<a href="<?php echo esc_url( $feature['more_link'] ); ?>"><?php echo $feature['more_text']; ?></a>
				</div>
				<?php endforeach; ?>

			</div>

		</div></section>
		<?php
	}

	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'p-0' ]); ?>

			<div class="bew-row no-gap text-center">

				<# _.each( settings.t6_features, function( feature ) { #>
				<div class="bew-col-12 bew-col-md-6 {{ feature.gray_bg }} feature-padding">
					<p><i class="{{ feature.icon }} fs-50" style="color: {{ feature.color }}"></i></p>
					<h5>{{{ feature.title }}}</h5>
					<br>
					<p>{{{ feature.text }}}</p>
					<br>
					<a href="{{ feature.more_link }}">{{{ feature.more_text }}}</a>
				</div>
				<# } ); #>

			</div>

		</div></section>
		<?php
	}

}
