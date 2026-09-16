<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_8 {

	const ID = 8;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Meet TheSaaS', 'thesaas' ),
			'header' => esc_html__( '30 Days Free Trial', 'thesaas' ),
			'lead'   => esc_html__( 'We waited until we could do it right. Then we did! Instead of creating a carbon copy.', 'thesaas' ),
		] );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Try it now', 'thesaas' ),
			'size' => 'btn-lg',
			'color' => 'btn-success',
			'width' => 250,
		] );

		$widget->panel( 'info_text', [
			'text' => esc_html__( 'You\'ll login using your Envato account.', 'thesaas' ),
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'text-center py-150' ]); ?>
					<?php $widget->html('section_header', [ 'class' => 'fs-50' ] ); ?>
					<p class="text-center">
						<?php $widget->html('button'); ?>
						<br>
						<?php $widget->html('info'); ?>
					</p>
		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'text-center py-150' ]); ?>
					<?php $widget->js('section_header', [ 'class' => 'fs-50' ] ); ?>
					<p class="text-center">
						<?php $widget->js('button'); ?>
						<br>
						<?php $widget->js('info'); ?>
					</p>
		</div></section>
		<?php
	}

}
