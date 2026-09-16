<?php

if ( ! class_exists( 'Bew_Builder_Structure_Block' ) ) {

	/**
	 * Define Bew_Builder_Structure_Section class
	 */
	class Bew_Builder_Structure_Block extends Bew_Builder_Structure_Base {

		public function get_id() {
			return 'bew_block';
		}

		public function get_single_label() {
			return esc_html__( 'Block', 'bew-extras' );
		}

		public function get_plural_label() {
			return esc_html__( 'Blocks', 'bew-extras' );
		}

		public function get_sources() {
			return array( 'bew-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Bew_Block_Document',				
				'file'  => BEW_EXTRAS_PATH . 'includes/bew-builder/includes/document-types/block.php',
			);
		}

		/**
		 * Library settings for current structure
		 *
		 * @return void
		 */
		public function library_settings() {

			return array(
				'show_title'    => false,
				'show_keywords' => true,
			);

		}

	}

}
