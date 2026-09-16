<?php
namespace BriefcasewpExtras\Widgets;

use Elementor\Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly


class Bew_Faq_Block_1 {

	const ID = 1;

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

		$widget->panel( 'faq' );

	}



	public function html( $widget ) {
		$widget->set_id( self::ID );
		$settings = $widget->get_settings();
		$cols = $settings['t1_columns']['size'];
		?>
		<?php $widget->html('section_tag'); ?>
			<?php $widget->html('section_header'); ?>

          <div class="bew-row gap-y gap-3">

			<?php
			foreach ( $settings['t1_faqs'] as $faq ) :

				if ( 1 == $cols ) :
				?>
				<div class="bew-col-12">
	              <h5><?php echo $faq['question']; ?></h5>
	              <?php echo $faq['answer']; ?>
	            </div>
				<?php
				elseif ( 2 == $cols ) :
				?>
				<div class="bew-col-12 bew-col-lg-6">
	              <h5><?php echo $faq['question']; ?></h5>
	              <?php echo $faq['answer']; ?>
	            </div>
				<?php
				elseif ( 3 == $cols ) :
				?>
	            <div class="bew-col-12 bew-col-md-6 bew-col-lg-4">
	              <h6 class="fw-400"><?php echo $faq['question']; ?></h6>
	              <?php echo $faq['answer']; ?>
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

          	<div class="bew-row gap-y gap-3">

			<#
			var cols = settings.t1_columns.size;
			_.each( settings.t1_faqs, function( faq ) {

				if ( 1 == cols ) {
				#>
				<div class="bew-col-12">
	              <h5>{{{ faq.question }}}</h5>
	              {{{ faq.answer }}}
	            </div>
				<#
				} else if ( 2 == cols ) {
				#>
				<div class="bew-col-12 bew-col-lg-6">
	              <h5>{{{ faq.question }}}</h5>
	              {{{ faq.answer }}}
	            </div>
				<#
				} else if ( 3 == cols ) {
				#>
	            <div class="bew-col-12 bew-col-md-6 bew-col-lg-4">
	              <h6 class="fw-400">{{{ faq.question }}}</h6>
	              {{{ faq.answer }}}
	            </div>
				<#
				};
			} ); #>

          	</div>

		</div></section>
		<?php
	}

}
