<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_15 {

	const ID = 15;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_color', 'switch_sides', 'section_inverse' ],
			'bg_color' => '#4f8cf2',
			'inverse' => true,
		] );


		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Give a Fresh Design to Your SaaS', 'bew-extras' ) ] );
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Interactively productize worldwide potentialities before long-term high-impact initiatives. Completely disintermediate excellent leadership skills with client-centric content.', 'bew-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('bg-laptop.jpg') ] );
		Bew_Controls::add_btn_color( $widget, $id, [
			'default' => 'btn-danger',
			'label'   => 'Button color',
		] );
		Bew_Controls::add_btn_custom_color_icon( $widget, $id );
		Bew_Controls::add_btn_outline( $widget, $id );
		Bew_Controls::add_video_link( $widget, $id, [ 'default' => 'https://www.youtube.com/watch?v=0id3Cclx1iw' ] );
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = $settings['t15_image']['url'];
		?>
		<?php $widget->html('section_tag', [ 'class' => 'p-0', 'wide_container' => true ]); ?>

      <div class="bew-row no-gap">

				<?php if ( 'yes' !== $settings['t15_switch_sides'] ) : ?>

	        <div class="bew-col-12 offset-md-1 bew-col-md-6 bg-img order-md-last video-btn-wrapper" style="background-image: url(<?php echo esc_url( $image ); ?>); min-height: 300px;" data-overlay="4">
	          <a class="btn btn-lg btn-video <?php echo esc_attr( $settings['t15_btn_color'] ); ?> btn-circular <?php echo esc_attr( $settings['t15_btn_outline'] ); ?>" href="<?php echo esc_url( $settings['t15_video_link'] ); ?>" data-provide="lightbox"><i class="fa fa-play"></i></a>
	        </div>

	        <div class="offset-1 bew-col-10 bew-col-md-4 py-90 order-md-first">
	          <h5><?php echo $settings['t15_header_text']; ?></h5>
	          <p><?php echo $settings['t15_text']; ?></p>
	        </div>

				<?php else: ?>

	        <div class="bew-col-12 bew-col-md-6 bg-img video-btn-wrapper" style="background-image: url(<?php echo esc_url( $image ); ?>); min-height: 300px;" data-overlay="4">
	          <a class="btn btn-lg btn-video <?php echo esc_attr( $settings['t15_btn_color'] ); ?> btn-circular <?php echo esc_attr( $settings['t15_btn_outline'] ); ?>" href="<?php echo esc_url( $settings['t15_video_link'] ); ?>" data-provide="lightbox"><i class="fa fa-play"></i></a>
	        </div>

	        <div class="offset-1 bew-col-10 bew-col-md-4 py-90">
	          <h5><?php echo $settings['t15_header_text']; ?></h5>
	          <p><?php echo $settings['t15_text']; ?></p>
	        </div>

				<?php endif; ?>

      </div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'p-0', 'wide_container' => true ]); ?>

			<div class="bew-row">

				<# if ( 'yes' !== settings.t15_switch_sides ) { #>

	        <div class="bew-col-12 offset-md-1 bew-col-md-6 bg-img order-md-last video-btn-wrapper" style="background-image: url({{ settings.t15_image.url }}); min-height: 300px;" data-overlay="4">
	          <a class="btn btn-lg btn-video {{ settings.t15_btn_color }} btn-circular {{ settings.t15_btn_outline }}" href="{{ settings.t15_video_link }}" data-provide="lightbox"><i class="fa fa-play"></i></a>
	        </div>

	        <div class="offset-1 bew-col-10 bew-col-md-4 py-90 order-md-first">
	          <h5>{{{ settings.t15_header_text }}}</h5>
	          <p>{{{ settings.t15_text }}}</p>
	        </div>

				<# } else { #>

	        <div class="bew-col-12 bew-col-md-6 bg-img video-btn-wrapper" style="background-image: url({{ settings.t15_image.url }}); min-height: 300px;" data-overlay="4">
	          <a class="btn btn-lg btn-video {{ settings.t15_btn_color }} btn-circular {{ settings.t15_btn_outline }}" href="{{ settings.t15_video_link }}" data-provide="lightbox"><i class="fa fa-play"></i></a>
	        </div>

	        <div class="offset-1 bew-col-10 bew-col-md-4 py-90">
	          <h5>{{{ settings.t15_header_text }}}</h5>
	          <p>{{{ settings.t15_text }}}</p>
	        </div>

				<# } #>

			</div>

		</div></section>
		<?php
	}

}
