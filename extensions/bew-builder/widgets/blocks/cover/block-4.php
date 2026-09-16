<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Cover_Block_4 {

	const ID = 4;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'header_style', [
			'options' => [
				'background' => 'video',
				'padding_top' => 0,
				'padding_bottom' => 0,
			],
		]);

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_width( $widget, $id, [ 'default' => 1140, 'default_unit' => 'px' , 'selectors' => '.widget-container' ] );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Creating Awesome Product Page', 'briefcasewp-extras' ) ] );
		Bew_Controls::add_heading_size( $widget, $id, [ 'default' => 58, 'default_unit' => 'px' , 'selectors' => 'h1' ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Custom Woocommerce blocks for Elementor.', 'briefcasewp-extras' ) ] );
		$widget->add_control(
			't'. $id .'_btn_link',
			[
				'label' => esc_html__( 'Video button link', 'briefcasewp-extras' ),
				'type' => Controls_Manager::URL,
				'placeholder' => 'http://',
				'description' => 'Youtube or vimeo URL',
				'default' => [
					'url' => 'https://www.youtube.com/watch?v=1lVjIYXq-48',
				],
				'show_external' => false,
			]
		);
		Bew_Controls::end_section( $widget );
		
	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$full_row = '';
		if ( 'yes' == $settings['t4_fullscreen'] ) {
			$full_row = 'h-full';
		}

		$btn_link = $settings['t4_btn_link']['url'];
		?>
				
		<?php $widget->html('header_tag'); ?>

			<div class="bew-row <?php echo $full_row; ?> align-items-end pd-80">
				<div class="bew-col-12 bew-col-md-8 text-center text-md-left lh-11">
					<h1><?php echo $settings['t4_header_text']; ?></h1>
					<p class="lead"><?php echo $settings['t4_text']; ?></p>										
				</div>


				<div class="bew-col-12 offset-md-1 bew-col-md-3 text-center mb-20">
					<br>
					<p><a class="btn btn-lg btn-circular btn-outline btn-white btn-play" href="<?php echo esc_url( $btn_link ); ?>" data-provide="lightbox"><i class="fa fa-play"></i></a></p>
					<br>									
				</div>
			</div>

		</div></header>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<#
		var full_row = '';
		if ( 'yes' == settings.t4_fullscreen ) {
			full_row = 'h-full';
		}
		#>
		<?php $widget->js('header_tag'); ?>

			<div class="bew-row {{ full_row }} align-items-end  pd-80">
				<div class="bew-col-12 bew-col-md-8 text-center text-md-left lh-11">
					<h1>{{{ settings.t4_header_text }}}</h1>
					<p class="lead">{{{ settings.t4_text }}}</p>										
				</div>


				<div class="bew-col-12 offset-md-1 bew-col-md-3 text-center mb-20">
					<br>
					<p><a class="btn btn-lg btn-circular btn-outline btn-white btn-play" href="{{ settings.t4_btn_link }}" data-provide="lightbox"><i class="fa fa-play"></i></a></p>
					<br>									
				</div>

			</div>

		</div></header>
		<?php
	}

}
