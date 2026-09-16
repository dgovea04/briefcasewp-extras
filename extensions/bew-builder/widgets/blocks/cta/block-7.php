<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_7 {

	const ID = 7;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_image', 'section_inverse', 'overlay' ],
			'bg_image' => thesaas_get_img_uri('placeholder-bg.jpg'),
			'inverse'  => true,
			'overlay'  => 8,
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Start the supporting system that you always dreamed about', 'thesaas' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Login with envato', 'thesaas' ),
			'size' => 'btn-xl',
			'round' => true,
			'color' => 'btn-success',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'bg-img text-center py-150' ]); ?>
          <h5 class="fs-30 text-white fw-300 mb-90"><?php echo $setting['t7_text'] ?></h5>
          <?php $widget->html('button'); ?>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'bg-img text-center py-150' ]); ?>
          <h5 class="fs-30 text-white fw-300 mb-90">{{{ settings.t7_text }}}</h5>
          <?php $widget->js('button'); ?>
		</div></section>
		<?php
	}

}
