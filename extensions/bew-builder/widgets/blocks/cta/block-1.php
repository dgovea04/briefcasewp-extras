<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_1 {

	const ID = 1;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		The_Controls::start_section( $widget, 'content', $id );
		The_Controls::add_header_text( $widget, $id, [
			'default' => esc_html__( 'Join over 1,000 companies that trust us.', 'thesaas' ),
		] );
		The_Controls::add_text( $widget, $id, [
			'default' => esc_html__( 'Try it yourself 30 days free. No credit card required!', 'thesaas' ),
		] );
		The_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Design your site now', 'thesaas' ),
			'size' => 'btn-lg',
			'round' => true,
			'color' => 'btn-info',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'text-center' ]); ?>
          <h2 class="mb-20"><?php echo $setting['t1_header_text'] ?></h2>
          <p class="lead text-muted"><?php echo $setting['t1_text'] ?></p>
          <hr class="w-50">
          <?php $widget->html('button'); ?>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'text-center' ]); ?>
          <h2 class="mb-20">{{{ settings.t1_header_text }}}</h2>
          <p class="lead text-muted">{{{ settings.t1_text }}}</p>
          <hr class="w-50">
          <?php $widget->js('button'); ?>
		</div></section>
		<?php
	}

}
