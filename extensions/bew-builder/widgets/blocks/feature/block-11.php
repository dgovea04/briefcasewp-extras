<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Feature_Block_11 {

	const ID = 11;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray', 'padding'  ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Features', 'bew-extras' ),
			'header' => esc_html__( 'More to Discover', 'bew-extras' ),
			'lead'   => esc_html__( 'We waited until we could do it right. Bewn we did! Instead of creating a carbon copy.', 'bew-extras' ),
		] );
				
		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_feature_img( $widget, $id, [
			'default' => [
				[
					'image' => [ 'url' => bew_get_img_uri( 'header-bew-1.png' ) ],
					'title' => esc_html__( '5 Ready Samples', 'bew-extras' ),
					'text' => esc_html__( 'Monotonectally leverage existing standards compliant ideas with distributed data. Efficiently simplify cross-unit systems whereas adaptive testing. Monotonectally leverage existing standards compliant ideas with distributed data. Efficiently simplify cross-unit systems whereas adaptive testing.', 'bew-extras' ),
				],
				[
					'image' => [ 'url' => bew_get_img_uri( 'header-bew-3.png' ) ],
					'title' => esc_html__( '6 Header Types', 'bew-extras' ),
					'text' => esc_html__( 'Monotonectally leverage existing standards compliant ideas with distributed data. Efficiently simplify cross-unit systems whereas adaptive testing. Monotonectally leverage existing standards compliant ideas with distributed data. Efficiently simplify cross-unit systems whereas adaptive testing.', 'bew-extras' ),
				],
			],
		] );
		
		Bew_Controls::add_image_large( $widget, $id );
		Bew_Controls::add_image_shadow( $widget, $id );
		
		Bew_Controls::end_section( $widget );		
		
		Bew_Controls::start_section_style( $widget, 'Header', $id );
		Bew_Controls::add_heading_typo( $widget, $id, ['name' => 'header','selectors' => 'h2' ] );		
		Bew_Controls::add_color( $widget, $id, ['name' => 'header', 'selectors' => 'h2' ] );
		Bew_Controls::end_section( $widget );
		
		Bew_Controls::start_section_style( $widget, 'Content', $id );
		Bew_Controls::add_heading( $widget, 'Titles', $id );
		Bew_Controls::add_heading_typo_content( $widget, $id, ['name' => 'content', 'selectors' => 'h4' ] );
		Bew_Controls::add_color_titles( $widget, $id, ['selectors' => 'h4' ] );
		Bew_Controls::add_heading( $widget, 'Description', $id );
		Bew_Controls::add_heading_typo_content_p( $widget, $id, ['selectors' => '.description p' ] );
		Bew_Controls::add_color_p( $widget, $id, ['selectors' => 'p' ] );	
		Bew_Controls::end_section( $widget );
	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$counter = 0;
		$max = count( $settings['t11_features'] );
		$image_large = $settings['t11_image_large'];

		$col_img_class = 'bew-col-12 bew-col-md-5';
		$col_des_class = 'bew-col-12 bew-col-md-7';
		switch ( $image_large ) {
			case 'large':
				$col_img_class = 'bew-col-12 bew-col-md-8';
				$col_des_class = 'bew-col-12 bew-col-md-4';
				break;
		}
		
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

			<?php foreach ( $settings['t11_features'] as $feature ) : $counter++; ?>
				<?php if ( 1 == $counter % 2 ) : ?>

					<div class="bew-row gap-y align-items-center">
						<div class="<?php echo $col_des_class; ?> order-md-last bew-fw-h4-600 description">
							<h4><?php echo $feature['title']; ?></h4>
							<p><?php echo $feature['text']; ?></p>
						</div>


						<div class="<?php echo $col_img_class; ?> order-md-first">
							<img class="rounded <?php echo esc_attr( $settings['t11_image_shadow'] ); ?>" src="<?php echo esc_url( $feature['image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['t11_header_text'] ); ?>">
						</div>
					</div>

				<?php else : ?>

					<div class="bew-row gap-y align-items-center">
						<div class="<?php echo $col_des_class; ?> bew-fw-h4-600 description">
							<h4><?php echo $feature['title']; ?></h4>
							<p><?php echo $feature['text']; ?></p>
						</div>

						<div class="<?php echo $col_img_class; ?> ">
							<img class="rounded <?php echo esc_attr( $settings['t1_image_shadow'] ); ?>" src="<?php echo esc_url( $feature['image']['url'] ); ?>" alt="<?php echo esc_attr( $settings['t11_header_text'] ); ?>">
						</div>
					</div>

				<?php endif; ?>

				<?php if ( $counter < $max ) : ?>
				<br>
				<hr>
				<br>
				<?php endif; ?>

			<?php endforeach; ?>

		</div></section>
		<?php
	}

	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>

			<#
				var counter = 0;
				var max = settings.t11_features.length;
				var image_large = settings.t11_image_large;
				var col_img_class = 'bew-col-12 bew-col-md-5';
				var col_des_class = 'bew-col-12 bew-col-md-7';
				switch ( image_large ) {
					case 'large':
						col_img_class = 'bew-col-12 bew-col-md-8';
						col_des_class = 'bew-col-12 bew-col-md-4';
						break;
				}
			#>

			<# _.each( settings.t11_features, function( feature ) { counter++; #>
				<# if ( 1 == counter % 2 ) { #>

					<div class="bew-row gap-y align-items-center">
						<div class="{{ col_des_class }} order-md-last bew-fw-h4-600 description">
							<h4>{{{ feature.title }}}</h4>
							<p>{{{ feature.text }}}</p>
						</div>

						<div class="{{ col_img_class }} order-md-first">
							<img class="rounded {{ settings.t11_image_shadow }}" src="{{ feature.image.url }}" alt="{{ settings.t11_header_text }}">
						</div>
					</div>

				<# } else { #>

					<div class="bew-row gap-y align-items-center">
						<div class="{{ col_des_class }} bew-fw-h4-600 description">
							<h4>{{{ feature.title }}}</h4>
							<p>{{{ feature.text }}}</p>
						</div>

						<div class="{{ col_img_class }}">
							<img class="rounded {{ settings.t11_image_shadow }}" src="{{ feature.image.url }}" alt="{{ settings.t11_header_text }}">
						</div>
					</div>

				<# } #>

				<# if ( counter < max ) { #>
				<br>
				<hr>
				<br>
				<# } #>

			<# } ); #>

		</div></section>
		<?php
	}

}