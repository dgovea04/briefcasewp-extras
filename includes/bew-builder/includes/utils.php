<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    BriefcaseWP
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Bew_Builder_Utils' ) ) {

	/**
	 * Define Bew_Builder_Utils class
	 */
	class Bew_Builder_Utils {

		/**
		 * [is_license_exist description]
		 * @return boolean [description]
		 */
		public static function get_bew_builder_license() {
			
			if ( Bew_Builder_Utils::is_account_check() ) {
				return true;
			}
			
		}

		/**
		 * [active_license_link description]
		 * @return [type] [description]
		 */
		public static function active_license_link() {

			if ( Bew_Builder_Utils::is_account_check() == false ) {
				return add_query_arg(
					array(
						'page' => 'bew-templates',
						//'tab'  => 'license',
					),
					esc_url( admin_url( 'admin.php' ) )
				);
			}
		}

		/**
		 * [is_account_check description]
		 * @return boolean [description]
		 */
		public static function is_account_check() {

			// Get Credentials for update the options.
			$options = get_site_option('briefcase-elementor-widgets_updater_options');
			$email= isset($options['email']) ? $options['email'] : '';		
			$check_password = !empty(isset($options['password'])) ? "yes" : "no";
			
			if ($check_password == "no"){
				
				//Check if is password is set.
				$option_bew_extras = get_site_option('bew_extras_options');
				$check_password_confirm = !empty(isset($option_bew_extras['confirm_password'])) ? "yes" : "no";
				
			}
			
			if (($email != '' && $check_password == "yes") || $check_password_confirm == "yes"  ){ 
				return true;
			}

			return false;
		}

		/**
		 * Get post types options list
		 *
		 * @return array
		 */
		public static function get_post_types() {

			$post_types = get_post_types( array( 'public' => true ), 'objects' );

			$deprecated = apply_filters(
				'bew-builder/post-types-list/deprecated',
				array(
					'attachment',
					'elementor_library',
					bew_builder()->templates->post_type,
				)
			);

			$result = array();

			if ( empty( $post_types ) ) {
				return $result;
			}

			foreach ( $post_types as $slug => $post_type ) {

				if ( in_array( $slug, $deprecated ) ) {
					continue;
				}

				$result[ $slug ] = $post_type->label;

			}

			return $result;

		}

	}

}
