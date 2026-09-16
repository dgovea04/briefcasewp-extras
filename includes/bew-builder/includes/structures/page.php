<?php

if ( ! class_exists( 'Bew_Builder_Structure_Page' ) ) {

	/**
	 * Define Bew_Builder_Structure_Page class
	 */
	class Bew_Builder_Structure_Page extends Bew_Builder_Structure_Base {

		public function get_id() {
			return 'bew_page';
		}

		public function get_single_label() {
			return esc_html__( 'Page', 'bew-extras' );
		}

		public function get_plural_label() {
			return esc_html__( 'Pages', 'bew-extras' );
		}

		public function get_sources() {
			return array( 'bew-api' );
		}

		public function get_document_type() {
			return array(
				'class' => 'Bew_Page_Document',
				'file'  => BEW_EXTRAS_PATH . 'includes/bew-builder/includes/document-types/page.php',
			);
		}

	}

}
