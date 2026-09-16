<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_16 {

	const ID = 16;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray', 'padding' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Video', 'bew-extras' ),
			'header' => esc_html__( 'Explore It', 'bew-extras' ),
			'lead'   => esc_html__( 'Explore the best Woocommerce Addons for Elementor in a short 1-minute video.', 'bew-extras' ),
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('bg-laptop.jpg') ] );
		Bew_Controls::add_image_shadow_2( $widget, $id, );
		Bew_Controls::add_fade_up( $widget, $id, );
		Bew_Controls::add_btn_color( $widget, $id, [
			'default' => 'btn-danger',
			'label'   => 'Button color',
		] );
		Bew_Controls::add_btn_custom_color_icon( $widget, $id );
		Bew_Controls::add_btn_outline( $widget, $id );
		Bew_Controls::add_video_link( $widget, $id, [ 'default' => 'https://www.youtube.com/watch?v=0id3Cclx1iw' ] );
		Bew_Controls::end_section( $widget );
		
		Bew_Controls::start_section_style( $widget, 'Header', $id );
		Bew_Controls::add_heading_typo( $widget, $id, ['name' => 'header','selectors' => 'h2' ] );		
		Bew_Controls::add_color( $widget, $id, ['name' => 'header', 'selectors' => 'h2' ] );
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = $settings['t16_image']['url'];

		?>
		<?php $widget->html('section_tag', [ 'class' => 'overflow-hidden' ]); ?>
			<?php $widget->html('section_header'); ?>

			<div class="bew-row">
        <div class="offset-md-2 bew-col-md-8">

          <div class="video-btn-wrapper" data-aos="<?php echo esc_attr( $settings['t16_fade_up'] ); ?>">
            <img class="<?php echo esc_attr( $settings['t16_image_shadow_2'] ); ?> rounded" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t16_header_text'] ); ?>">
            <a class="btn btn-lg btn-video <?php echo esc_attr( $settings['t16_btn_color'] ); ?> btn-circular <?php echo esc_attr( $settings['t16_btn_outline'] ); ?>" href="<?php echo esc_url( $settings['t16_video_link'] ); ?>" data-provide="lightbox"><i class="fa fa-play"></i></a>
          </div>

        </div>
			</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>

			<div class="bew-row">
        <div class="offset-md-2 bew-col-md-8">

          <div class="video-btn-wrapper" data-aos="{{ settings.t16_fade_up }}">
            <img class="{{ settings.t16_image_shadow_2 }} rounded" src="{{ settings.t16_image.url }}" alt="{{ settings.t16_header_text }}">
            <a class="btn btn-lg btn-video {{ settings.t16_btn_color }}  btn-circular {{ settings.t16_btn_outline }}" href="{{ settings.t16_video_link }}" data-provide="lightbox"><i class="fa fa-play"></i></a>
          </div>

        </div>
			</div>

		</div></section>
		<?php
	}

}
