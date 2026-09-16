<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Feature_Block_1 {

	const ID = 1;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Features', 'bew-extras' ),
			'header' => esc_html__( 'Great Combination', 'bew-extras' ),
			'lead'   => esc_html__( 'There are many variations of passages of Lorem Ipsum available, but the majority have.', 'bew-extras' ),
		] );


		Bew_Controls::start_section( $widget, 'image', $id );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('feature-bew.png') ] );
		Bew_Controls::add_image_shadow( $widget, $id );
		Bew_Controls::end_section( $widget );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_columns( $widget, $id, [
			'min' => 2,
			'max' => 4,
			'default' => 3,
		]);
		Bew_Controls::add_feature_full( $widget, $id, [
			'default' => [
				[
					'icon' => 'fa fa-tv',
					'color' => '#e4e7ea',
					'title' => esc_html__( 'Responsive', 'bew-extras' ),
					'text' => esc_html__( 'Your landing page displays smoothly on any device: desktop, tablet or mobile.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-wrench',
					'color' => '#e4e7ea',
					'title' => esc_html__( 'Customizable', 'bew-extras' ),
					'text' => esc_html__( 'You can easily read, edit, and write your own code, or change everything.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-cubes',
					'color' => '#e4e7ea',
					'title' => esc_html__( 'UI Elements', 'bew-extras' ),
					'text' => esc_html__( 'There is a bunch of useful and necessary elements for developing your website.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-code',
					'color' => '#e4e7ea',
					'title' => esc_html__( 'Clean Code', 'bew-extras' ),
					'text' => esc_html__( 'You can find our code well organized, commented and readable.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-file-alt',
					'color' => '#e4e7ea',
					'title' => esc_html__( 'Documented', 'bew-extras' ),
					'text' => esc_html__( 'As you can see in the source code, we provided a comprehensive documentation.', 'bew-extras' ),
				],
				[
					'icon' => 'fa fa-download',
					'color' => '#e4e7ea',
					'title' => esc_html__( 'Free Updates', 'bew-extras' ),
					'text' => esc_html__( 'When you purchase this template, you\'ll freely receive future updates.', 'bew-extras' ),
				],
			],
		] );
		Bew_Controls::end_section( $widget );

		$widget->panel( 'button', [
			'text' => esc_html__( 'See more features', 'bew-extras' ),
			'size' => 'btn-lg',
			'color' => 'btn-primary',
		] );
		
		Bew_Controls::start_section_style( $widget, 'Header', $id );
		Bew_Controls::add_heading_typo( $widget, $id, ['name' => 'header' , 'selectors' => 'h2' ] );
		Bew_Controls::add_color( $widget, $id, ['name' => 'header' , 'selectors' => 'h2' ] );
		Bew_Controls::end_section( $widget );
		
		Bew_Controls::start_section_style( $widget, 'Features', $id );
		Bew_Controls::add_heading_typo( $widget, $id, ['name' => 'feature_title' , 'selectors' => 'h5' ] );
		Bew_Controls::add_color( $widget, $id, ['name' => 'feature_title' , 'selectors' => 'h5' ] );
		Bew_Controls::end_section( $widget );
	}
	
	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = $settings['t1_image']['url'];
		$col_size = $settings['t1_columns']['size'];

		$col_class = 'bew-col-12 bew-col-md-6 bew-col-xl-4';
		switch ( $col_size ) {
			case 2:
				$col_class = 'bew-col-12 bew-col-md-6';
				break;

			case 4:
				$col_class = 'bew-col-12 bew-col-md-6 bew-col-xl-3';
				break;
		}
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

			<div class="bew-row gap-y">

				<?php if ( ! empty( $image ) ) : ?>
				<div class="bew-col-12 offset-md-2 bew-col-md-8 mb-30">
					<img class="<?php echo esc_attr( $settings['t1_image_shadow'] ); ?>" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t1_header_text'] ); ?>" data-aos="fade-up" data-aos-duration="2000">
				</div>
				<?php endif; ?>


				<?php foreach ( $settings['t1_features'] as $feature ) : ?>
				<div class="<?php echo $col_class; ?>">
					<div class="flexbox gap-items-4">
						<div style="width: 35px;">
							<i class="<?php echo esc_attr( $feature['icon'] ); ?> fs-25 pt-4" style="color: <?php echo esc_attr( $feature['color'] ); ?>"></i>
						</div>

						<div class="flex-grow">
							<h5><?php echo $feature['title']; ?></h5>
							<p><?php echo $feature['text']; ?></p>
						</div>
					</div>
				</div>
				<?php endforeach; ?>


				<?php if ( ! empty( $settings['t1_btn_text'] ) ) : ?>
				<div class="bew-col-12 text-center">
					<br><br>
					<?php $widget->html('button'); ?>
				</div>
				<?php endif; ?>

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
				var col_size = settings.t1_columns.size;
				var col_class = 'bew-col-12 bew-col-md-6 bew-col-xl-4';
				switch ( col_size ) {
					case 2:
						col_class = 'bew-col-12 bew-col-md-6';
						break;

					case 4:
						col_class = 'bew-col-12 col-md-6 bew-col-xl-3';
						break;
				}
			#>

			<div class="bew-row gap-y">

				<# if ( '' !== settings.t1_image.url ) { #>
				<div class="bew-col-12 offset-md-2 bew-col-md-8 mb-30">
					<img class="{{ settings.t1_image_shadow }}" src="{{ settings.t1_image.url }}" alt="{{ settings.t1_header_text }}" data-aos="fade-up" data-aos-duration="2000">
				</div>
				<# } #>

				<# _.each( settings.t1_features, function( feature ) { #>
				<div class="{{ col_class }}">
					<div class="flexbox gap-items-4">
						<div style="width: 35px;">
							<i class="{{ feature.icon }} fs-25 pt-4" style="color: {{ feature.color }}"></i>
						</div>

						<div class="flex-grow">
							<h5>{{{ feature.title }}}</h5>
							<p>{{{ feature.text }}}</p>
						</div>
					</div>
				</div>
				<# } ); #>

				<# if ( '' !== settings.t1_btn_text ) { #>
				<div class="bew-col-12 text-center">
					<br><br>
					<?php $widget->js('button'); ?>
				</div>
				<# } #>

			</div>

		</div></section>
		<?php
	}

}
