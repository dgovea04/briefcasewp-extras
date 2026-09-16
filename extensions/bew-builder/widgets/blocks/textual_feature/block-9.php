<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;
use Elementor\Repeater;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class Bew_Textual_Feature_Block_9 {

	const ID = 9;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content' );

		Bew_Controls::start_section( $widget, 'features', $id );
		Bew_Controls::add_columns_responsive( $widget, $id );
		Bew_Controls::add_columns_responsive_space( $widget, $id );
		Bew_Controls::add_columns_responsive_space_v( $widget, $id );
		
		$repeater = new Repeater();
		
		$repeater->add_control(
			'icon', [
				'label' => __( 'Icon class', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,			
			]
		);				
		$repeater->add_control(
			'color', [
				'label' => esc_html__( 'Icon color', 'bew-extras' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#b5b9bf',		
			]
		);	
		$repeater->add_control(
			'title', [
				'label' => __( 'Title', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'label_block' => true,			
			]
		);		
		$repeater->add_control(
			'link', [
				'label' => esc_html__( 'Link', 'bew-extras' ),
				'type' => Controls_Manager::TEXT,
				'placeholder' => 'http://',
				'default' => '#',
				'label_block' => true,		
			]
		);
	
		$widget->add_control(
			't'. $id .'_features',
			[
				'label' => esc_html__( 'Features', 'bew-extras' ),
				'type' => Controls_Manager::REPEATER,
				'default' => [
					[
						'icon' => 'icon-mobile',
						'title' => esc_html__( 'Responsive', 'bew-extras' ),
					],
					[
						'icon' => 'icon-gears',
						'title' => esc_html__( 'Customizable', 'bew-extras' ),
					],
					[
						'icon' => 'icon-tools',
						'title' => esc_html__( 'UI Elements', 'bew-extras' ),
					],
					[
						'icon' => 'icon-recycle',
						'title' => esc_html__( 'Clean Code', 'bew-extras' ),
					],
					[
						'icon' => 'icon-browser',
						'title' => esc_html__( 'Browser Support', 'bew-extras' ),
					],
					[
						'icon' => 'icon-paintbrush',
						'title' => esc_html__( 'Color Pallet', 'bew-extras' ),
					],
					[
						'icon' => 'icon-puzzle',
						'title' => esc_html__( 'Page Builder', 'bew-extras' ),
					],
					[
						'icon' => 'icon-newspaper',
						'title' => esc_html__( 'Documentation', 'bew-extras' ),
					],
				],
				'fields' => $repeater->get_controls(),
				'title_field' => '{{{ title }}}',
			]
		);
		Bew_Controls::end_section( $widget );

	}

	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();		
			
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

			<div class="bew-row gap-y">

				<?php foreach ( $settings['t9_features'] as $feature ) : ?>
				<div class="card-content">
					<div class="card card-bordered card-hover-shadow text-center">
						<a class="card-block" href="<?php echo esc_url( $feature['link'] ); ?>">
							<p><i class="<?php echo esc_attr( $feature['icon'] ); ?> fs-50" style="color: <?php echo esc_attr( $feature['color'] ); ?>"></i></p>
							<h4 class="card-title"><?php echo $feature['title']; ?></h4>
						</a>
					</div>
				</div>
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
			
			<div class="bew-row gap-y">

				<# _.each( settings.t9_features, function( feature ) { #>
				<div class="card-content">
					<div class="card card-bordered card-hover-shadow text-center">
						<a class="card-block" href="{{ feature.link }}">
							<p><i class="{{ feature.icon }} fs-50" style="color: {{ feature.color }}"></i></p>
							<h4 class="card-title">{{{ feature.title }}}</h4>
						</a>
					</div>
				</div>
				<# } ); #>

			</div>

		</div></section>
		<?php
	}

}