<?php
/**
 * Presentation-only view renderer.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Core;

defined( 'ABSPATH' ) || exit;

class View {

	/**
	 * Render a template part with an injected, escaped-at-output data context.
	 *
	 * @param string $template Path under template-parts/ without extension, e.g. 'front/hero'.
	 * @param array  $data     Variables extracted into the template scope.
	 */
	public static function render( $template, array $data = array() ) {
		// Template names are internal, relative slugs (e.g. 'front/hero').
		// Reject empty, absolute, traversal, or null-byte values defensively.
		if ( '' === $template
			|| '/' === $template[0]
			|| false !== strpos( $template, '..' )
			|| false !== strpos( $template, "\0" )
		) {
			return;
		}

		$path = get_theme_file_path( 'template-parts/' . $template . '.php' );
		if ( ! is_readable( $path ) ) {
			return;
		}

		// Isolate the include scope so only the data context is exposed to the template.
		( static function ( $__foliocraft_path, $__foliocraft_data ) {
			// phpcs:ignore WordPress.PHP.DontExtract.extract_extract -- controlled view context.
			extract( $__foliocraft_data, EXTR_SKIP );
			require $__foliocraft_path;
		} )( $path, $data );
	}
}
