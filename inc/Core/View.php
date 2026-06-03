<?php
/**
 * Presentation-only view renderer.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Core;

defined( 'ABSPATH' ) || exit;

class View {

	/**
	 * Render a template part with an injected, escaped-at-output data context.
	 *
	 * @param string $template Path under template-parts/ without extension, e.g. 'front/hero'.
	 * @param array  $data     Variables extracted into the template scope.
	 */
	public static function render( $template, array $data = array() ) {
		$path = get_theme_file_path( 'template-parts/' . $template . '.php' );
		if ( ! is_readable( $path ) ) {
			return;
		}
		// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- controlled view context.
		extract( $data, EXTR_SKIP );
		require $path;
	}

	/**
	 * Render a template part and return it as a string.
	 *
	 * @param string $template Path under template-parts/ without extension.
	 * @param array  $data     Variables extracted into the template scope.
	 * @return string
	 */
	public static function capture( $template, array $data = array() ) {
		ob_start();
		self::render( $template, $data );
		return (string) ob_get_clean();
	}
}
