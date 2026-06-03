<?php
/**
 * PSR-4-style autoloader mapping the Naeem\ namespace to inc/.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem;

defined( 'ABSPATH' ) || exit;

class Autoloader {

	/** Register the autoloader with SPL. */
	public static function register() {
		spl_autoload_register( array( __CLASS__, 'load' ) );
	}

	/**
	 * Load a class file for the Naeem\ namespace.
	 *
	 * Naeem\Core\View -> inc/Core/View.php
	 *
	 * @param string $class Fully-qualified class name.
	 */
	public static function load( $class ) {
		$prefix = 'Naeem\\';
		if ( 0 !== strpos( $class, $prefix ) ) {
			return;
		}
		$relative = substr( $class, strlen( $prefix ) );
		$path     = NAEEM_DIR . '/inc/' . str_replace( '\\', '/', $relative ) . '.php';
		if ( is_readable( $path ) ) {
			require_once $path;
		}
	}
}
