<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Feature_Block_10 {

	const ID = 10;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray', 'switch_sides', 'padding' ],
			'switch_sides' => true,
		] );

		Bew_Controls::start_section( $widget, 'content', $id );
		Bew_Controls::add_header_text( $widget, $id, [ 'default' => esc_html__( 'Unlimited Layout Option', 'bew-extras' ) ] );	
		Bew_Controls::add_text( $widget, $id, [ 'default' => esc_html__( 'Energistically transform pandemic manufactured products whereas premier solutions. Compellingly streamline an expanded array of web-readiness rather.', 'bew-extras' ) ] );
		Bew_Controls::add_image( $widget, $id, [ 'default' => bew_get_img_uri('header-bew-4.png') ] );
		Bew_Controls::add_image_large( $widget, $id );
		Bew_Controls::add_image_shadow( $widget, $id, [ 'default' => true ] );
		Bew_Controls::end_section( $widget );

		Bew_Controls::start_section( $widget, 'list', $id );
		
		$repeater = new Repeater();

		$repeater->add_control(
			'text', [
				'label' => esc_html__( 'Text', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,
			]
		);	
		
		$widget->add_control(
			't'. $id .'_list',
			[
				'label' => esc_html__( 'Feature list', 'bew-extras' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'text' => esc_html__( 'MailChimp integration included', 'bew-extras' ),
					],
					[
						'text' => esc_html__( 'Develop pages like lego', 'bew-extras' ),
					],
					[
						'text' => esc_html__( 'Dozen of colors for elements', 'bew-extras' ),
					],
					[
						'text' => esc_html__( 'Drag and drop page design', 'bew-extras' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ text }}}',
			]
		);
		Bew_Controls::end_section( $widget );
		
		Bew_Controls::start_section_style( $widget, 'Header', $id );
		Bew_Controls::add_heading_typo( $widget, $id, ['name' => 'header','selectors' => 'h2' ] );
		Bew_Controls::add_color( $widget, $id, ['name' => 'header', 'selectors' => 'h2' ] );
		Bew_Controls::end_section( $widget );
		
		Bew_Controls::start_section_style( $widget, 'Text', $id );
		Bew_Controls::add_typo_content_p( $widget, $id, ['selectors' => 'p' ] );
		Bew_Controls::add_color( $widget, $id, ['name' => 'text', 'selectors' => 'p' ] );
		Bew_Controls::end_section( $widget );
	
		Bew_Controls::start_section_style( $widget, 'Image', $id );
		Bew_Controls::add_size( $widget, $id, ['selectors' => 'img' ] );
		Bew_Controls::add_padding_style( $widget, $id, ['name' => 'image', 'selectors' => 'img' ] );
		Bew_Controls::add_margin_style( $widget, $id, ['name' => 'image', 'selectors' => 'img' ] );
		Bew_Controls::end_section( $widget );
		
		Bew_Controls::start_section_style( $widget, 'List', $id );
		Bew_Controls::add_typo_content_p( $widget, $id, ['selectors' => 'p span' ] );
		Bew_Controls::add_color_icon( $widget, $id, ['name' => 'list_icon', 'selectors' => 'p i' ] );		
		Bew_Controls::add_color_text( $widget, $id, ['name' => 'list_text', 'selectors' => 'p span' ] );
		Bew_Controls::end_section( $widget );
		
		
	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$image = $settings['t10_image']['url'];
		$image_large = $settings['t10_image_large'];
		
		if ( 'yes' == $settings['t10_switch_sides'] ) :
			$col_img_class = 'bew-col-md-5 offset-md-1';
			$col_des_class = 'bew-col-12 bew-col-md-6';
		else:
			$col_img_class = 'bew-col-md-5';
			$col_des_class = 'bew-col-12 bew-col-md-6 offset-md-1';
		endif;
		
		switch ( $image_large ) {
			case 'large':
				if ( 'yes' == $settings['t10_switch_sides'] ) :
					$col_img_class = 'bew-col-md-7';
					$col_des_class = 'bew-col-12 bew-col-md-5 pr-30';
				else:
					$col_img_class = 'bew-col-md-7';
					$col_des_class = 'bew-col-12 bew-col-md-5 pl-30';
				endif;
				break;
		}
		?>
		<?php $widget->html('section_tag', [ 'class' => 'overflow-hidden' ]); ?>

			<div class="bew-row gap-y align-items-center">

			<?php if ( 'yes' == $settings['t10_switch_sides'] ) : ?>

				<div class="<?php echo $col_des_class; ?>">
					<h2><?php echo $settings['t10_header_text'] ?></h2>
					<p class="lead"><?php echo $settings['t10_text'] ?></p>

					<br>

					<?php foreach ( $settings['t10_list'] as $item ) : ?>
					<p>
						<i class="ti-check text-success mr-8"></i>
						<span class="fs-14"><?php echo $item['text']; ?></span>
					</p>
					<?php endforeach; ?>
				</div>

				<div class="<?php echo $col_img_class; ?> align-self-center">
					<img class="<?php echo esc_attr( $settings['t10_image_shadow'] ); ?>" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t10_header_text'] ); ?>">
				</div>

			<?php else: ?>

				<div class="<?php echo $col_des_class; ?> order-md-last">
					<h2><?php echo $settings['t10_header_text'] ?></h2>
					<p class="lead"><?php echo $settings['t10_text'] ?></p>

					<br>

					<?php foreach ( $settings['t10_list'] as $item ) : ?>
					<p>
						<i class="ti-check text-success mr-8"></i>
						<span class="fs-14"><?php echo $item['text']; ?></span>
					</p>
					<?php endforeach; ?>
				</div>

				<div class="<?php echo $col_img_class; ?> align-self-center order-md-first">
					<img class="<?php echo esc_attr( $settings['t10_image_shadow'] ); ?>" src="<?php echo esc_url( $image ); ?>" alt="<?php echo esc_attr( $settings['t10_header_text'] ); ?>">
				</div>

			<?php endif; ?>

			</div>

		</div></section>
		<?php
	}

	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag', [ 'class' => 'overflow-hidden' ]); ?>
		
		<#			
			var image_large = settings.t10_image_large;
			
			if ( 'yes' == settings.t10_switch_sides ) {
				var col_img_class = 'bew-col-md-5 offset-md-1';
				var col_des_class = 'bew-col-12 bew-col-md-6';
			} else {
				var col_img_class = 'bew-col-md-5 ';
				var col_des_class = 'bew-col-12 bew-col-md-6 offset-md-1';				
			}
			
			switch ( image_large ) {
				case 'large':
					if ( 'yes' == settings.t10_switch_sides ) {
						col_img_class = 'bew-col-md-7';
						col_des_class = 'bew-col-12 bew-col-md-5 pr-30';
					} else {
						col_img_class = 'bew-col-md-7';
						col_des_class = 'bew-col-12 bew-col-md-5 pl-30';
					}
					break;
			}
		#>

			<div class="bew-row gap-y align-items-center">

			<# if ( 'yes' == settings.t10_switch_sides ) { #>

				<div class="{{ col_des_class }}">
					<h2>{{{ settings.t10_header_text }}}</h2>
					<p class="lead">{{{ settings.t10_text }}}</p>

					<br>

					<# _.each( settings.t10_list, function( item ) { #>
					<p>
						<i class="ti-check text-success mr-8"></i>
						<span class="fs-14">{{{ item.text }}}</span>
					</p>
					<# } ); #>
				</div>

				<div class="{{ col_img_class }} align-self-center">
					<img class="{{ settings.t10_image_shadow }}" src="{{ settings.t10_image.url }}" alt="{{ settings.t10_header_text }}">
				</div>

			<# } else { #>

				<div class="{{ col_des_class }} order-md-last">
					<h2>{{{ settings.t10_header_text }}}</h2>
					<p class="lead">{{{ settings.t10_text }}}</p>

					<br>

					<# _.each( settings.t10_list, function( item ) { #>
					<p>
						<i class="ti-check text-success mr-8"></i>
						<span class="fs-14">{{{ item.text }}}</span>
					</p>
					<# } ); #>
				</div>

				<div class="{{ col_img_class }} align-self-center order-md-first">
					<img class="{{ settings.t10_image_shadow }}" src="{{ settings.t10_image.url }}" alt="{{ settings.t10_header_text }}">
				</div>

			<# } #>

			</div>

		</div></section>
		<?php
	}

}
