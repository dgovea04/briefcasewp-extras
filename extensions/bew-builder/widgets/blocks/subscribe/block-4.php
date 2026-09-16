<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Subscribe_Block_4 {

	const ID = 4;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Subscribe to our newsletter and receive the latest news.', 'bew-extras' ),
		] );
		Bew_Controls::add_mailchimp_form_link( $widget, $id, ['default' => '#'] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Subscribe', 'bew-extras' ),
			'color' => 'btn-info',
			'no_link' => true,
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'py-50' ]); ?>
					<div class="bew-row gap-y align-items-center">
						<div class="bew-col-12 bew-col-md-6">
							<p class="lead mb-0 text-center text-md-left"><?php echo $settings['t4_header_text'] ?></p>
						</div>

						<div class="bew-col-12 bew-col-md-6">
							<form class="form-inline form-round justify-content-center justify-content-lg-end" action="<?php echo esc_url( $settings['t4_mailchimp_form_link'] ) ?>" method="post" target="_blank">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="text" name="EMAIL" class="form-control" placeholder="Email Address">
									<span class="input-group-btn">
										<?php $widget->html('button', [ 'tag' => 'button' ]) ?>
									</span>
								</div>
							</form>
						</div>
					</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'py-50' ]); ?>
					<div class="bew-row gap-y align-items-center">
						<div class="bew-col-12 bew-col-md-6">
							<p class="lead mb-0 text-center text-md-left">{{{ settings.t4_header_text }}}</p>
						</div>

						<div class="bew-col-12 bew-col-md-6">
							<form class="form-inline form-round justify-content-center justify-content-lg-end" action="{{ settings.t4_mailchimp_form_link }}" method="post" target="_blank">
								<div class="input-group">
									<span class="input-group-addon"><i class="fa fa-envelope"></i></span>
									<input type="text" name="EMAIL" class="form-control" placeholder="Email Address">
									<span class="input-group-btn">
										<?php $widget->js('button', [ 'tag' => 'button' ]) ?>
									</span>
								</div>
							</form>
						</div>
					</div>

		</div></section>
		<?php
	}

}
