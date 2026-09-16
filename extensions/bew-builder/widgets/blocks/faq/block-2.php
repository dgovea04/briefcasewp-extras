<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Faq_Block_2 {

	const ID = 2;

	public function controls( $widget ) {
		$widget->set_id( self::ID );

		$widget->panel( 'section', [
			'includes' => [ 'bg_gray' ],
		] );

		$widget->panel( 'header_content', [
			'small'  => esc_html__( 'FAQ', 'briefcasewp-extras' ),
			'header' => esc_html__( 'General', 'briefcasewp-extras' ),
			'lead'   => esc_html__( 'Send an email if you couldn\'t find an answer to your question here.', 'briefcasewp-extras' ),
		] );

		$widget->panel( 'faq', [ 'no_columns' => true ] );

	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$id = uniqid();
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

          <?php
			echo '<div class="accordion" id="accordion-'. $id .'">';

			$counter = 1;
			foreach ( $settings['t2_faqs'] as $faq ) :
				?>
		        <div class="card">
		          <h5 class="card-title">
		            <a data-toggle="collapse" data-parent="#accordion-<?php echo $id; ?>" href="#collapse-<?php echo $id; ?>-<?php echo $counter; ?>" class="collapsed" ><?php echo $faq['question']; ?></a>
		          </h5>

		          <div id="collapse-<?php echo $id; ?>-<?php echo $counter; ?>" class="collapse">
		            <div class="card-block">
		              <?php echo $faq['answer']; ?>
		            </div>
		          </div>
		        </div>
				<?php
				$counter++;
			endforeach;

			echo '</div>';
			?>

		</div></section>
		<?php
	}



	public function javascript( $widget ) {
		$widget->set_id( self::ID );
		?>
		<?php $widget->js('section_tag'); ?>
			<?php $widget->js('section_header'); ?>

			<div class="accordion" id="accordion-{{ settings.t2_uniqid }}">
				<#
				if ( settings.t2_faqs ) {
					var counter = 1;
					_.each( settings.t2_faqs, function( faq ) { #>
			          <div class="card">
			            <h5 class="card-title">
			              <a data-toggle="collapse" data-parent="#accordion-{{ settings.t2_uniqid }}" href="#collapse-{{ settings.t2_uniqid }}-{{ counter }} " class="collapsed" >{{{ faq.question }}}</a>
			            </h5>

			            <div id="collapse-{{ settings.t2_uniqid }}-{{ counter }}" class="collapse">
			              <div class="card-block">
			                {{{ faq.answer }}}
			              </div>
			            </div>
			          </div>
					<#
						counter++;
					} );
				} #>
			</div>

		</div></section>
		<?php
	}

}
