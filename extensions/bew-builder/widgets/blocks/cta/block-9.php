<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Cta_Block_9 {

	const ID = 9;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'gradient_start', 'gradient_end', 'section_inverse' ],
			'gradient_start' => '#e0c3fc',
			'gradient_end' => '#8ec5fc',
			'inverse' => true,
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Own it', 'thesaas' ),
			'header' => esc_html__( 'Get it now', 'thesaas' ),
			'lead'   => esc_html__( 'If you have visited the other pages and you have made your decision to purchase this template, go on and press the following button and get a license in less than a minute.', 'thesaas' ),
		] );

		$widget->panel( 'button', [
			'text' => esc_html__( 'Purchase for $49', 'thesaas' ),
			'size' => 'btn-xl',
			'color' => 'btn-primary',
		] );

		$widget->panel( 'info_text', [
			'text' => esc_html__( 'or purchase an Extended License', 'thesaas' ),
			'link' => '#',
		] );
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$setting = $widget->get_settings();
		?>
		<?php $widget->html('section_tag', [ 'class' => 'section-inverse py-120' ]); ?>
					<?php $widget->html('section_header', [ 'class' => 'fs-45' ] ); ?>
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
		<?php $widget->js('section_tag', [ 'class' => 'section-inverse py-120' ]); ?>
					<?php $widget->js('section_header', [ 'class' => 'fs-45' ] ); ?>
					<p class="text-center">
						<?php $widget->js('button'); ?>
						<br>
						<?php $widget->js('info'); ?>
					</p>
		</div></section>
		<?php
	}

}
