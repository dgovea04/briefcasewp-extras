<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_17 {

	const ID = 17;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_image', 'overlay', 'section_inverse' ],
			'bg_image' => bew_get_img_uri('bg-laptop.jpg'),
			'overlay'  => 6,
			'inverse'  => true,
		] );

		$widget->panel( 'header_content', [
			'small'  => '',
			'header' => esc_html__( 'Watch Video', 'bew-extras' ),
			'lead'   => esc_html__( 'Explore the best SaaS template in the market in a short 1-minute video.', 'bew-extras' ),
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('bg-laptop.jpg') ] );
		Bew_Controls::add_btn_color( $widget, $id, [
			'default' => 'btn-white',
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
		$image = $settings['t17_image']['url'];

		?>
		<?php $widget->html('section_tag', [ 'class' => 'bg-img' ]); ?>
			<?php $widget->html('section_header'); ?>

      <div class="text-center mb-60">
        <a class="btn btn-lg btn-video <?php echo esc_attr( $settings['t17_btn_color'] ); ?> btn-circular <?php echo esc_attr( $settings['t17_btn_outline'] ); ?>" href="<?php echo esc_url( $settings['t17_video_link'] ); ?>" data-provide="lightbox"><i class="fa fa-play"></i></a>
      </div>

		</div></section>
		<?php
	}

	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'bg-img' ]); ?>
			<?php $widget->js('section_header'); ?>

      <div class="text-center mb-60">
        <a class="btn btn-lg btn-video {{ settings.t17_btn_color }} btn-circular {{ settings.t17_btn_outline }}" href="{{ settings.t17_video_link }}" data-provide="lightbox"><i class="fa fa-play"></i></a>
      </div>

		</div></section>
		<?php
	}

}
