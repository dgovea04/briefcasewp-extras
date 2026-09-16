<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Feature_Block_12 {

	const ID = 12;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'See it in action', 'bew-extras' ),
			'header' => esc_html__( 'Screenshots', 'bew-extras' ),
			'lead'   => esc_html__( 'We waited until we could do it right. Bewn we did! Instead of creating a carbon copy.', 'bew-extras' ),
		] );


		Bew_Controls::start_section( $widget, 'screenshots', $id );
		Bew_Controls::add_columns( $widget, $id, [
			'min' => 2,
			'max' => 5,
			'default' => 3,
		]);
		Bew_Controls::add_columns_space( $widget, $id, [
			'min' => 0,
			'max' => 100,
			'default' => 50,
		]);
		Bew_Controls::add_gallery( $widget, $id, [ 'default' => [
				bew_get_img_uri( 'demo/shot-1.jpg' ),
				bew_get_img_uri( 'demo/shot-2.jpg' ),
				bew_get_img_uri( 'demo/shot-3.jpg' ),
				bew_get_img_uri( 'demo/shot-4.jpg' ),
				bew_get_img_uri( 'demo/shot-5.jpg' ),
				bew_get_img_uri( 'demo/shot-6.jpg' ),
				bew_get_img_uri( 'demo/shot-7.jpg' ),
				bew_get_img_uri( 'demo/shot-8.jpg' ),
				bew_get_img_uri( 'demo/shot-9.jpg' ),
			],
		] );
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$col_size = $settings['t12_columns']['size'];
		$col_space = $settings['t12_columns_space']['size'];
		$gallery = $settings['t12_gallery'];
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

          <div class="swiper-container swiper-button-circular" data-slides-per-view="<?php echo $col_size; ?>" data-space-between="<?php echo $col_space; ?>" data-centered-slides="true">
            <div class="swiper-wrapper pb-0">
				<?php
				if (is_array($gallery)) {
					foreach ($gallery as $image) {
						echo '<div class="swiper-slide shadow-2 pt-0 pb-0 mt-10 mb-10"><img src="' . esc_url( $image['url'] ) . '" alt="'. esc_attr( $settings['t12_header_text'] ) .'"></div>';
					}
				}
				?>
            </div>

            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
          </div>

		</div></section>		
		<?php
	}

	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>
			
			<#
				var col_size = settings.t12_columns.size;
				var col_space = settings.t12_columns_space.size;
			#>

	          <div class="swiper-container swiper-button-circular" data-slides-per-view="{{ col_size }}" data-space-between="{{ col_space }}" data-centered-slides="true">
	            <div class="swiper-wrapper pb-0">
				<# _.each( settings.t12_gallery, function( image ) { #>
					<div class="swiper-slide shadow-2 pt-0 pb-0 mt-10 mb-10"><img src="{{ image.url }}" alt="{{ settings.t12_header_text }}"></div>
				<# } ); #>
	            </div>

	            <div class="swiper-button-prev"></div>
	            <div class="swiper-button-next"></div>
	          </div>

		</div></section>
		<?php
	}

}
