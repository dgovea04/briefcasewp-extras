<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Textual_Feature_Block_1 {

	const ID = 1;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Features', 'bew-extras' ),
			'header' => esc_html__( 'So Intuitive, So Easy', 'bew-extras' ),
			'lead'   => esc_html__( 'We are so excited and proud of our product. It\'s really easy to create a landing page for your awesome product.', 'bew-extras' ),
		] );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_feature_full( $widget, $id);
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

			<div class="bew-row gap-y">

				<?php foreach ( $settings['t1_features'] as $feature ) : ?>
					<div class="bew-col-12 bew-col-md-6 bew-col-xl-4 feature-1">
						<p class="feature-icon" style="color: <?php echo esc_attr( $feature['color'] ); ?>"><i class="<?php echo esc_attr( $feature['icon'] ); ?>"></i></p>
						<h5><?php echo $feature['title']; ?></h5>
						<p><?php echo $feature['text']; ?></p>
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

				<# _.each( settings.t1_features, function( feature ) { #>
					<div class="bew-col-12 bew-col-md-6 bew-col-xl-4 feature-1">
						<p class="feature-icon" style="color: {{ feature.color }}"><i class="{{ feature.icon }}"></i></p>
						<h5>{{{ feature.title }}}</h5>
						<p>{{{ feature.text }}}</p>
					</div>
				<# } ); #>

			</div>

		</div></section>
		<?php
	}

}
