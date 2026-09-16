<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Subscribe_Block_1 {

	const ID = 1;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_image', 'overlay' ],
			'bg_image' => bew_get_img_uri('placeholder-bg.jpg'),
			'overlay'  => 9,
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'News and Updates', 'briefcasewp-extras' ),
		] );
		Bew_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Subscribe to our newsletter and receive the latest news from BriefcaseWP.', 'briefcasewp-extras' ),
		] );
		Bew_Controls::add_mailchimp_form_link( $widget, $id, ['default' => '#'] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Subscribe', 'briefcasewp-extras' ),
			'size' => 'btn-lg',
			'color' => 'btn-primary',
			'no_link' => true,
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'section-inverse text-center' ]); ?>
					<h3><?php echo $settings['t1_header_text'] ?></h3>
					<br>
					<p class="lead"><?php echo $settings['t1_text'] ?></p>

					<br><br>

					<form class="form-inline form-glass justify-content-center" action="<?php echo esc_url( $settings['t1_mailchimp_form_link'] ) ?>" method="post" target="_blank">

						<div class="form-group mr-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								<input type="email" name="EMAIL" class="form-control" placeholder="Email Address">
							</div>
						</div>

						<?php $widget->html('button', [ 'tag' => 'button' ]) ?>

					</form>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'section-inverse text-center' ]); ?>
					<h3>{{{ settings.t1_header_text }}}</h3>
					<br>
					<p class="lead">{{{ settings.t1_text }}}</p>

					<br><br>

					<form class="form-inline form-glass justify-content-center" action="{{ settings.t1_mailchimp_form_link }}" method="post" target="_blank">

						<div class="form-group mr-12">
							<div class="input-group">
								<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
								<input type="email" name="EMAIL" class="form-control" placeholder="Email Address">
							</div>
						</div>

						<?php $widget->js('button', [ 'tag' => 'button' ]) ?>

					</form>

		</div></section>
		<?php
	}

}
