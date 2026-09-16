<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_3 {

	const ID = 3;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_image( $widget, $id, [
			'default' => thesaas_get_img_uri( 'placeholder-icon.png' ),
		] );
		The_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Become an Outstanding Startup', 'thesaas' ),
		] );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Try it yourself 30 days free. No credit card required! A good news is that you have 15 days money back!', 'thesaas' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Start now', 'thesaas' ),
			'size' => 'btn-lg',
			'round' => true,
			'color' => 'btn-success',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'text-center' ]); ?>
			<div class="row">
				<div class="col-12 offset-md-3 col-md-6">
					<p><img src="<?php echo esc_url( $setting['t3_image']['url'] ) ?>" alt="<?php echo esc_attr( $setting['t3_header_text'] ); ?>"></p>
					<br>
					<h3 class="fw-900 mb-20"><?php echo $setting['t3_header_text'] ?></h3>
					<p class="lead text-muted"><?php echo $setting['t3_text'] ?></p>
					<br>
					<?php $widget->html('button'); ?>
				</div>
			</div>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'text-center' ]); ?>
			<div class="row">
				<div class="col-12 offset-md-3 col-md-6">
					<p><img src="{{ settings.t3_image.url }}" alt="{{ settings.t3_header_text }}"></p>
					<br>
					<h3 class="fw-900 mb-20">{{{ settings.t3_header_text }}}</h3>
					<p class="lead text-muted">{{{ settings.t3_text }}}</p>
					<br>
					<?php $widget->js('button'); ?>
				</div>
			</div>
		</div></section>
		<?php
	}

}
