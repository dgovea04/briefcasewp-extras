<?php
namespace BriefcasewpExtras;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Extension autoloader.
 *
 * Autoloader handler class is responsible for loading the different
 * classes needed to run the extension.
 *
 * @since 1.0.0
 */
class Autoloader {


	/**
	 * Classes map.
	 *
	 * Maps extension classes to file names.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array Classes used by elementor.
	 */
	private static $classes_map = [
		'Admin' => 'includes/admin.php',
		'Importer' => 'includes/importer.php',		
	];


	/**
	 * Run autoloader.
	 *
	 * Register a function as `__autoload()` implementation.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function run() {
		spl_autoload_register( [ __CLASS__, 'autoload' ] );
	}


	/**
	 * Load class.
	 *
	 * For a given class name, require the class file.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @param string $relative_class_name Class name.
	 */
	private static function load_class( $relative_class_name ) {
		if ( isset( self::$classes_map[ $relative_class_name ] ) ) {
			$filename = BEW_THEMER_PATH . '/extensions/bew-extensions' . self::$classes_map[ $relative_class_name ];

			if ( is_readable( $filename ) ) {
				/** @noinspection PhpIncludeInspection */
				require $filename;
			}
		}
	}

	/**
	 * Autoload.
	 *
	 * For a given class, check if it exist and load it.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @param string $class Class name.
	 */
	private static function autoload( $class ) {
		//if requested class doesn't have our namespace, we don't interact with it
		if ( 0 !== strpos( $class, __NAMESPACE__ . '\\' ) ) {
			return;
		}

		// why \\\ ? first \ escapes in string, second \ escapes in regexp
		$relative_class_name = preg_replace( '/^' . __NAMESPACE__ . '\\\/', '', $class );

		$final_class_name = __NAMESPACE__ . '\\' . $relative_class_name;

		if ( ! class_exists( $final_class_name ) ) {
			self::load_class( $relative_class_name );
		}
	}
}
