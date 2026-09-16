<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_2 {

	const ID = 2;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_color', 'section_inverse' ],
			'bg_color' => '#8ea6e6',
			'inverse'  => true,
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Want a bite? You\'re in the right place!', 'thesaas' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Take a test drive', 'thesaas' ),
			'size' => 'btn-lg',
			'round' => true,
			'color' => 'btn-white',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'py-40' ]); ?>
					<div class="row gap-y align-items-center text-center text-md-left">
						<div class="col-12 col-md-9">
							<h4 class="fw-300 mb-0"><?php echo $setting['t2_text'] ?></h4>
						</div>

						<div class="col-12 col-md-3 text-center text-md-right">
							<?php $widget->html('button'); ?>
						</div>
					</div>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'py-40' ]); ?>
					<div class="row gap-y align-items-center text-center text-md-left">
						<div class="col-12 col-md-9">
							<h4 class="fw-300 mb-0">{{{ settings.t2_text }}}</h4>
						</div>

						<div class="col-12 col-md-3 text-center text-md-right">
							<?php $widget->js('button'); ?>
						</div>
					</div>
		</div></section>
		<?php
	}

}
