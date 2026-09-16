<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Textual_Feature_Block_5 {

	const ID = 5;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'wide_container' ],
			'wide_container' => true,
		] );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_extended( $widget, $id );
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'p-0' ]); ?>

			<div class="bew-row no-gap text-center">

				<?php foreach ( $settings['t5_features'] as $feature ) : ?>
					<?php if ( 'yes' == $feature['gray_bg'] ) : ?>
						<div class="bew-col-12 bew-col-md-6 bg-gray feature-padding">
							<p class="iconbox iconbox-xxl bg-white">
					<?php else: ?>
						<div class="bew-col-12 bew-col-md-6 feature-padding">
							<p class="iconbox iconbox-xxl">
					<?php endif; ?>

						<i class="<?php echo esc_attr( $feature['icon'] ); ?> fs-40"></i>
					</p>
					<br><br>
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

				<# _.each( settings.t5_features, function( feature ) { #>
					<# if ( 'yes' == feature.gray_bg ) { #>
						<div class="bew-col-12 bew-col-md-6 bg-gray feature-padding">
							<p class="iconbox iconbox-xxl bg-white">
					<# } else { #>
						<div class="bew-col-12 bew-col-md-6 feature-padding">
							<p class="iconbox iconbox-xxl">
					<# } #>

						<i class="{{ feature.icon }} fs-40"></i>
					</p>
					<br><br>
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