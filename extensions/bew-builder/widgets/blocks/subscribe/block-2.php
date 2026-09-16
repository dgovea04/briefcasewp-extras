<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Subscribe_Block_2 {

	const ID = 2;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_color', 'overlay' ],
			'bg_color' => '#3ec1d3',
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_image( $widget, $id, [
			'default' => bew_get_img_uri( 'bew-phone.png' ),
		] );
		Bew_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Stay Updated', 'briefcasewp-extras' ),
		] );
		Bew_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Subscribe to our newsletter to be always aware of our new updates. We build the most powerful and flexible templates for Woocommerce.', 'briefcasewp-extras' ),
		] );
		Bew_Controls::add_mailchimp_form_link( $widget, $id, ['default' => '#'] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Sign up', 'briefcasewp-extras' ),
			'outline' => true,
			'color' => 'btn-white',
			'no_link' => true,
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'section-inverse pb-0 overflow-hidden' ]); ?>
					<div class="bew-row align-items-center">

						<div class="bew-col-12 bew-col-md-8 pb-70">
							<h2><?php echo $settings['t2_header_text'] ?></h2>
							<p class="lead"><?php echo $settings['t2_text'] ?></p>
							<br>
							<form class="form-inline form-glass" action="<?php echo esc_url( $settings['t2_mailchimp_form_link'] ) ?>" method="post" target="_blank">
								<div class="input-group">
									<input type="text" name="EMAIL" class="form-control" placeholder="Enter Email Address">
									<span class="input-group-btn">
										<?php $widget->html('button', [ 'tag' => 'button' ]) ?>
									</span>
								</div>
							</form>
						</div>


						<div class="bew-col-12 bew-col-md-4">
							<img src="<?php echo esc_url( $settings['t2_image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['t2_header_text'] ); ?>" data-aos="slide-up">
						</div>

					</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'section-inverse pb-0 overflow-hidden' ]); ?>
					<div class="bew-row align-items-center">

						<div class="bew-col-12 bew-col-md-8 pb-70">
							<h2>{{{ settings.t2_header_text }}}</h2>
							<p class="lead">{{{ settings.t2_text }}}</p>
							<br>
							<form class="form-inline form-glass" action="{{ settings.t2_mailchimp_form_link }}" method="post" target="_blank">
								<div class="input-group">
									<input type="text" name="EMAIL" class="form-control" placeholder="Enter Email Address">
									<span class="input-group-btn">
										<?php $widget->js('button', [ 'tag' => 'button' ]) ?>
									</span>
								</div>
							</form>
						</div>


						<div class="bew-col-12 bew-col-md-4">
							<img src="{{ settings.t2_image.url }}" alt="{{ settings.t2_header_text }}" data-aos="slide-up">
						</div>

					</div>

		</div></section>
		<?php
	}

}
