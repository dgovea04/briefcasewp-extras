<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Subscribe_Block_5 {

	const ID = 5;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_image', 'overlay' ],
			'bg_image' => bew_get_img_uri('placeholder-bg.jpg'),
			'overlay'  => 4,
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Latest news direct to your inbox', 'briefcasewp-extras' ),
		] );
		Bew_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Subscribe Now', 'briefcasewp-extras' ),
		] );
		Bew_Controls::add_mailchimp_form_link( $widget, $id, ['default' => '#'] );
		Bew_Controls::add_content_bg_color( $widget, $id, ['default' => '#ff9900'] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Subscribe', 'briefcasewp-extras' ),
			'outline' => true,
			'color' => 'btn-white',
			'no_link' => true,
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'section-inverse py-120' ]); ?>
					<div class="section-dialog section-dialog-sm bg-custom text-left">
						<h4 class="fw-600"><?php echo $settings['t5_header_text'] ?></h4>
						<br><br>
						<p class="text-right text-white fs-12"><?php echo $settings['t5_text'] ?></p>
						<form class="form-inline form-glass form-round" action="<?php echo esc_url( $settings['t5_mailchimp_form_link'] ) ?>" method="post" target="_blank">
							<div class="input-group w-full">
								<input type="text" name="EMAIL" class="form-control" placeholder="Enter Email Address">
								<span class="input-group-btn">
									<?php $widget->html('button', [ 'tag' => 'button' ]) ?>
								</span>
							</div>
						</form>
					</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'section-inverse py-120' ]); ?>
					<div class="section-dialog section-dialog-sm bg-custom text-left">
						<h4 class="fw-600">{{{ settings.t5_header_text }}}</h4>
						<br><br>
						<p class="text-right text-white fs-12">{{{ settings.t5_text }}}</p>
						<form class="form-inline form-glass form-round" action="{{ settings.t5_mailchimp_form_link }}" method="post" target="_blank">
							<div class="input-group w-full">
								<input type="text" name="EMAIL" class="form-control" placeholder="Enter Email Address">
								<span class="input-group-btn">
									<?php $widget->js('button', [ 'tag' => 'button' ]) ?>
								</span>
							</div>
						</form>
					</div>

		</div></section>
		<?php
	}

}
