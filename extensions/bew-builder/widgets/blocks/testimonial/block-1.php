<?php
namespace TheThemeio\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class The_Testimonial_Block_1 {

	const ID = 1;

	public function controls( $widget ) {
		$widget->set_id( self::ID );
		$id = self::ID;

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'Reviews', 'thesaas' ),
			'header' => esc_html__( 'Happy Customers', 'thesaas' ),
			'lead'   => esc_html__( 'Join thousands of satisfied customers using our template globally.', 'thesaas' ),
		] );

		$widget->panel( 'testimonials' );

	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();

		$cols = $settings['t1_columns']['size'];
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

          	<div class="row gap-y text-center">

				<?php
				foreach ( $settings['t1_testimonials'] as $item ) :

					if ( 1 == $cols ) :
					?>
					<div class="col-12">
			          <blockquote class="blockquote">
			            <p class="lead"><?php echo $item['content']; ?></p>
			            <br>
			            <div><img class="avatar avatar-xl" src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>"></div>
			            <footer><?php echo $item['name']; ?></footer>
			          </blockquote>
		        	</div>
					<?php
					elseif ( 2 == $cols ) :
					?>
			        <div class="col-12 col-md-6">
			          <blockquote class="blockquote mx-0">
			            <div><img class="avatar avatar-xl" src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>"></div>
			            <br>
			            <p><?php echo $item['content']; ?></p>
			            <footer><?php echo $item['name']; ?></footer>
			          </blockquote>
			        </div>
					<?php
					elseif ( 3 == $cols ) :
					?>
			        <div class="col-12 col-lg-4">
			          <blockquote class="blockquote mx-0">
			            <div><img class="avatar avatar-xl" src="<?php echo esc_url( $item['image']['url'] ); ?>" alt="<?php echo esc_attr( $item['name'] ); ?>"></div>
			            <br>
			            <p class="small"><?php echo $item['content']; ?></p>
			            <footer><?php echo $item['name']; ?></footer>
			          </blockquote>
			        </div>
					<?php
					endif;
				endforeach; ?>

          	</div>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>

          	<div class="row gap-y text-center">

				<#
				var cols = settings.t1_columns.size;
				_.each( settings.t1_testimonials, function( item ) {

					if ( 1 == cols ) {
					#>
					<div class="col-12">
			          <blockquote class="blockquote">
			            <p class="lead">{{{ item.content }}}</p>
			            <br>
			            <div><img class="avatar avatar-xl" src="{{ item.image.url }}" alt="{{ item.name }}"></div>
			            <footer>{{{ item.name }}}</footer>
			          </blockquote>
            		</div>
					<#
					} else if ( 2 == cols ) {
					#>
		            <div class="col-12 col-md-6">
		              <blockquote class="blockquote mx-0">
		                <div><img class="avatar avatar-xl" src="{{ item.image.url }}" alt="{{ item.name }}"></div>
		                <br>
		                <p>{{{ item.content }}}</p>
		                <footer>{{{ item.name }}}</footer>
		              </blockquote>
		            </div>
					<#
					} else if ( 3 == cols ) {
					#>
		            <div class="col-12 col-lg-4">
		              <blockquote class="blockquote mx-0">
		                <div><img class="avatar avatar-xl" src="{{ item.image.url }}" alt="{{ item.name }}"></div>
		                <br>
		                <p class="small">{{{ item.content }}}</p>
		                <footer>{{{ item.name }}}</footer>
		              </blockquote>
		            </div>
					<#
					};
				} ); #>

          	</div>

		</div></section>
		<?php
	}

}
