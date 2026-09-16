<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_4 {

	const ID = 4;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_image', 'section_inverse', 'overlay' ],
			'bg_image' => thesaas_get_img_uri('placeholder-bg.jpg'),
			'inverse'  => true,
			'overlay'  => 5,
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( '20,000+ companies are using our templates every day!', 'thesaas' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Get Started', 'thesaas' ),
			'size' => 'btn-lg',
			'round' => true,
			'color' => 'btn-success',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'bg-img' ]); ?>
			<div class="row gap-y align-items-center">
				<div class="col-12 col-md-8 text-center text-md-left">
					<h4 class="mb-0"><?php echo $setting['t4_text'] ?></h4>
				</div>

				<div class="col-12 col-md-4 text-center text-md-right">
					<?php $widget->html('button'); ?>
				</div>
			</div>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'bg-img' ]); ?>
			<div class="row gap-y align-items-center">
				<div class="col-12 col-md-8 text-center text-md-left">
					<h4 class="mb-0">{{{ settings.t4_text }}}</h4>
				</div>

				<div class="col-12 col-md-4 text-center text-md-right">
					<?php $widget->js('button'); ?>
				</div>
			</div>
		</div></section>
		<?php
	}

}
