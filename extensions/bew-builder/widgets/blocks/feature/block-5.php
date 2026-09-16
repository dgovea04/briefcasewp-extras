<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Feature_Block_5 {

	const ID = 5;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Features', 'bew-extras' ),
			'header' => esc_html__( 'Header Varieties', 'bew-extras' ),
			'lead'   => esc_html__( 'We waited until we could do it right. Bew we did! Instead of creating a carbon copy.', 'bew-extras' ),
		] );


		Bew_Controls::start_section( $widget, 'tabs', $id );
		
		$repeater = new Repeater();

		$repeater->add_control(
			'title', [
				'label' => esc_html__( 'Title', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Tab Title', 'bew-extras' ),
				'placeholder' => esc_html__( 'Tab Title', 'bew-extras' ),
				'label_block' => true,
			]
		);	
		$repeater->add_control(
			'content', [
				'label' => esc_html__( 'Content', 'bew-extras' ),
				'default' => esc_html__( 'Tab Content', 'bew-extras' ),
				'placeholder' => esc_html__( 'Tab Content', 'bew-extras' ),
				'type' => Controls_Manager::WYSIWYG,
				'show_label' => false,
			]
		);	

		$widget->add_control(
			't'. $id .'_tabs',
			[
				'label' => esc_html__( 'Tabs Items', 'bew-extras' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'title' => esc_html__( 'Men', 'bew-extras' ),
						'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-1.png' ) ) .'" alt="header bew 1">',
					],
					[
						'title' => esc_html__( 'Women', 'bew-extras' ),
						'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-3.png' ) ) .'" alt="header bew 2">',
					],
					[
						'title' => esc_html__( 'Kid', 'bew-extras' ),
						'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-4.png' ) ) .'" alt="header bew 4">',
					],
					[
						'title' => esc_html__( 'Accesories', 'bew-extras' ),
						'content' => '<img src="'. esc_url( bew_get_img_uri( 'header-bew-5.png' ) ) .'" alt="header bew 5">',
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);

		Bew_Controls::add_uniqid( $widget, $id );

		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$id = $settings['t5_uniqid'];
		$counter = 0;
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

			<div class="text-center">
				<ul class="bew-nav bew-nav-outline bew-nav-round">
				<?php foreach ( $settings['t5_tabs'] as $item ) : $counter++; ?>

					<?php if ( 1 == $counter ) : ?>

						<li class="bew-nav-item w-140">
							<a class="bew-nav-link active" data-toggle="tab" href="#tab-<?php echo $id; ?>-<?php echo $counter; ?>"><?php echo $item['title']; ?></a>
						</li>

					<?php else : ?>

						<li class="bew-nav-item w-140">
							<a class="bew-nav-link" data-toggle="tab" href="#tab-<?php echo $id; ?>-<?php echo $counter; ?>"><?php echo $item['title']; ?></a>
						</li>

					<?php endif; ?>

				<?php endforeach; $counter = 0; ?>
				</ul>
			</div>


			<br><br>


			<div class="tab-content text-center" data-aos="fade-in">
				<?php foreach ( $settings['t5_tabs'] as $item ) : $counter++; ?>

					<?php if ( 1 == $counter ) : ?>

						<div class="tab-pane fade show active" id="tab-<?php echo $id; ?>-<?php echo $counter; ?>">
							<?php echo $item['content']; ?>
						</div>

					<?php else : ?>

						<div class="tab-pane fade" id="tab-<?php echo $id; ?>-<?php echo $counter; ?>">
							<?php echo $item['content']; ?>
						</div>

					<?php endif; ?>

				<?php endforeach; ?>
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
				var counter = 0;
				var id = settings.t5_uniqid;
			#>
			<div class="text-center">
				<ul class="bew-nav bew-nav-outline bew-nav-round">
				<# _.each( settings.t5_tabs, function( item ) { counter++; #>

					<# if ( 1 == counter ) { #>

						<li class="bew-nav-item w-140">
							<a class="bew-nav-link active" data-toggle="tab" href="#tab-{{ id }}-{{ counter }}">{{{ item.title }}}</a>
						</li>

					<# } else { #>

						<li class="bew-nav-item w-140">
							<a class="bew-nav-link" data-toggle="tab" href="#tab-{{ id }}-{{ counter }}">{{{ item.title }}}</a>
						</li>

					<# } #>

				<# } ); counter = 0; #>
				</ul>
			</div>


			<br><br>


			<div class="tab-content text-center" data-aos="fade-in">
				<# _.each( settings.t5_tabs, function( item ) { counter++; #>

					<# if ( 1 == counter ) { #>

						<div class="tab-pane fade show active" id="tab-{{ id }}-{{ counter }}">
							{{{ item.content }}}
						</div>

					<# } else { #>

						<div class="tab-pane fade" id="tab-{{ id }}-{{ counter }}">
							{{{ item.content }}}
						</div>

					<# } #>

				<# } ); #>
			</div>

		</div></section>
		<?php
	}

}
