<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_7 {

	const ID = 7;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray', 'switch_sides', 'wide_container' ],
			'wide_container' => true,
		] );


		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_small_text( $widget, $id, [ 'default' => esc_html__( 'Marketing', 'bew-extras' ) ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Increase Your Sells', 'bew-extras' ) ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Interactively productize worldwide potentialities before long-term high-impact initiatives. Completely disintermediate excellent leadership skills with client-centric content.', 'bew-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('placeholder-bg.jpg') ] );
		Bew_Controls::end_section( $widget );


		$widget->panel( 'button', [
			'text' => esc_html__( 'Read more', 'bew-extras' ),
			'round' => true,
			'color' => 'btn-primary',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = esc_url( $settings['t7_image']['url'] );
		$swap = $settings['t7_switch_sides'];
		?>
		<?php $widget->html('section_tag', [ 'class' => 'p-0' ]); ?>

			<div class="bew-row no-gap">

				<?php if ( 'yes' !== $swap ) : ?>
				<div class="bew-col-12 bew-col-md-6 bg-img" style="background-image: url(<?php echo $image; ?>); min-height: 200px;"></div>

				<div class="offset-1 bew-col-10 bew-col-md-4 py-90">
					<p><small><?php echo $settings['t7_small_text']; ?></small></p>
					<h5><?php echo $settings['t7_header_text']; ?></h5>
					<p><?php echo $settings['t7_text']; ?></p>
					<br>
					<?php $widget->html('button'); ?>
				</div>

				<?php else: ?>

				<div class="offset-1 bew-col-10 bew-col-md-4 py-90">
					<p><small><?php echo $settings['t7_small_text']; ?></small></p>
					<h5><?php echo $settings['t7_header_text']; ?></h5>
					<p><?php echo $settings['t7_text']; ?></p>
					<br>
					<?php $widget->html('button'); ?>
				</div>

				<div class="bew-col-12 offset-md-1 bew-col-md-6 bg-img" style="background-image: url(<?php echo $image; ?>); min-height: 200px;"></div>
				<?php endif; ?>

			</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'p-0' ]); ?>

			<div class="bew-row no-gap">

				<# if ( 'yes' !== settings.t7_switch_sides ) { #>
				<div class="bew-col-12 bew-col-md-6 bg-img" style="background-image: url({{ settings.t7_image.url }}); min-height: 200px;"></div>

				<div class="offset-1 bew-col-10 bew-col-md-4 py-90">
					<p><small>{{{ settings.t7_small_text }}}</small></p>
					<h5>{{{ settings.t7_header_text }}}</h5>
					<p>{{{ settings.t7_text }}}</p>
					<br>
					<?php $widget->js('button'); ?>
				</div>

				<# } else { #>

				<div class="offset-1 bew-col-10 bew-col-md-4 py-90">
					<p><small>{{{ settings.t7_small_text }}}</small></p>
					<h5>{{{ settings.t7_header_text }}}</h5>
					<p>{{{ settings.t7_text }}}</p>
					<br>
					<?php $widget->js('button'); ?>
				</div>

				<div class="bew-col-12 offset-md-1 bew-col-md-6 bg-img" style="background-image: url({{ settings.t7_image.url }}); min-height: 200px;"></div>
				<# } #>

			</div>

		</div></section>
		<?php
	}

}
