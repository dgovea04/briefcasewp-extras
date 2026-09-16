<?php
/**
 * Class description
 *
 * @package   package_name
 * @author    BriefcaseWp
 * @license   GPL-2.0+
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! class_exists( 'Bew_Builder_Config' ) ) {

	/**
	 * Define Bew_Builder_Config class
	 */
	class Bew_Builder_Config {

		/**
		 * A reference to an instance of this class.
		 *
		 * @since 1.0.0
		 * @var   object
		 */
		private static $instance = null;

		/**
		 * Config holder
		 *
		 * @var array
		 */
		private $config = array();

		/**
		 * Constructor for the class
		 */
		public function __construct() {

			// register default config
			$this->config =  array(				
				'editor' => array(
					'template_before' => '',
					'template_after'  => '',
				),
				'library_button' => '',
				'library' => array(
					'version' => '1.0.0',
					'tabs'    => array(
						'bew_page'  => '',
						'bew_block'  => '',
					),
					'keywords' => array(),
				),
				'api' => array(
					'enabled'   => true,
					'base'      => 'https://account.briefcasewp.com/',
					'path'      => 'wp-json/briefcasewp/v1',
					'id'        => 1,
					'endpoints' => array(
						'templates'  => '/templates/',
						'keywords'   => '/keywords/',
						'categories' => '/categories/',
						'info'       => '/info/',
						'template'   => '/template/',
						'plugins'    => '/plugins/',
						'plugin'     => '/plugin/',
					),
				),
			);

			/**
			 * Register custom config on this hook
			 */
			do_action( 'bew-builder/register-config', $this );
		}

		/**
		 * Register custom config from theme or plugin
		 *
		 * @param  array $config Config to register
		 * @return void
		 */
		public function register_config( $config ) {

			foreach ( $config as $key => $data ) {

				if ( ! empty( $this->config[ $key ] ) ) {
					if ( is_array( $this->config[ $key ] ) ) {
						$this->config[ $key ] = array_merge( $this->config[ $key ], $data );
					} else {
						$this->config[ $key ] = $data;
					}
				} else {
					$this->config[ $key ] = $data;
				}

			}

		}

		/**
		 * Returns config value by key
		 *
		 * @param  string $key Key to get.
		 * @return mixed
		 */
		public function get( $key = '' ) {
			return isset( $this->config[ $key ] ) ? $this->config[ $key ] : false;
		}

		/**
		 * Returns the instance.
		 *
		 * @since  1.0.0
		 * @return object
		 */
		public static function get_instance() {

			// If the single instance hasn't been set, set it now.
			if ( null == self::$instance ) {
				self::$instance = new self;
			}
			return self::$instance;
		}
	}

}
