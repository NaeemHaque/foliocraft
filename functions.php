<?php
/**
 * FolioCraft theme bootstrap.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;

define( 'FOLIOCRAFT_VERSION', '1.0.0' );
define( 'FOLIOCRAFT_DIR', get_template_directory() );
define( 'FOLIOCRAFT_URI', get_template_directory_uri() );

require_once FOLIOCRAFT_DIR . '/inc/Autoloader.php';
\FolioCraft\Autoloader::register();

\FolioCraft\Theme::init();
\FolioCraft\Assets::init();
\FolioCraft\Customizer\Customizer::init();
\FolioCraft\Blocks::init();

// Projects and Experience are managed via Customizer repeaters (the Portfolio
// panel, read by the Project/Experience models) — no custom post types and no
// companion plugin, so the theme is fully self-contained.

/**
 * Fallback for the 'primary' nav location: the one-page anchor links rendered
 * when no menu is assigned. Passed to wp_nav_menu() as fallback_cb.
 */
function foliocraft_default_nav() {
	$base  = is_front_page() ? '' : home_url( '/' );
	$items = array(
		'about'      => array( '#about', _x( 'about', 'nav item', 'foliocraft' ) ),
		'experience' => array( '#experience', _x( 'experience', 'nav item', 'foliocraft' ) ),
		'work'       => array( '#work', _x( 'work', 'nav item', 'foliocraft' ) ),
		'opensource' => array( '#opensource', _x( 'open-source', 'nav item', 'foliocraft' ) ),
		'stack'      => array( '#stack', _x( 'stack', 'nav item', 'foliocraft' ) ),
		'writing'    => array( '#writing', _x( 'writing', 'nav item', 'foliocraft' ) ),
	);
	foreach ( $items as $id => $item ) {
		if ( ! foliocraft_section_visible( $id ) ) {
			continue;
		}
		echo '<a href="' . esc_url( $base . $item[0] ) . '"><span class="hash">#</span>' . esc_html( $item[1] ) . '</a>';
	}
}

/**
 * Whether a front-page section has any content to show.
 *
 * Lets empty sections — and their nav links — be hidden when every Customizer
 * field that feeds the section is blank (or, for the repeaters, has no rows).
 *
 * @param string $id Section id: about|experience|work|opensource|stack|writing.
 * @return bool
 */
function foliocraft_section_visible( $id ) {
	$p = \FolioCraft\Models\Profile::all();
	switch ( $id ) {
		case 'about':
			if ( ! foliocraft_blank( $p['about']['heading'] ) ) {
				return true;
			}
			foreach ( $p['about']['paragraphs'] as $para ) {
				if ( ! foliocraft_blank( $para ) ) {
					return true;
				}
			}
			foreach ( $p['about']['pillars'] as $pillar ) {
				if ( ! foliocraft_blank( $pillar['title'] ) || ! foliocraft_blank( $pillar['desc'] ) ) {
					return true;
				}
			}
			return false;
		case 'experience':
			return (bool) \FolioCraft\Models\Experience::all();
		case 'work':
			return (bool) \FolioCraft\Models\Project::all();
		case 'opensource':
			if ( ! foliocraft_blank( $p['opensource']['heading'] ) || ! foliocraft_blank( $p['opensource']['lead'] ) || ! empty( $p['opensource']['areas'] ) ) {
				return true;
			}
			foreach ( $p['opensource']['cards'] as $card ) {
				if ( ! foliocraft_blank( $card['title'] ) || ! foliocraft_blank( $card['role'] ) || ! foliocraft_blank( $card['blurb'] ) ) {
					return true;
				}
			}
			return false;
		case 'stack':
			foreach ( $p['skills']['groups'] as $group ) {
				if ( ! foliocraft_blank( $group['label'] ) || ! empty( $group['pills'] ) ) {
					return true;
				}
			}
			return false;
		case 'writing':
			return (bool) \FolioCraft\Models\Post::latest( 1 );
	}
	return true;
}

/**
 * Whether a Customizer-driven value is visually empty.
 *
 * Used across the front-page templates so that an element is hidden when its
 * Customizer field is removed/blank, instead of rendering an empty tag.
 *
 * @param string $value Raw (possibly HTML) value.
 * @return bool True when there is no visible content.
 */
function foliocraft_blank( $value ) {
	return '' === trim( wp_strip_all_tags( (string) $value ) );
}

/**
 * Compact display label for a profile URL — drops the scheme, www, and trailing slash
 * (e.g. https://github.com/jane/ -> github.com/jane).
 *
 * @param string $url URL.
 * @return string
 */
function foliocraft_url_label( $url ) {
	return rtrim( preg_replace( '#^https?://(www\.)?#i', '', (string) $url ), '/' );
}
