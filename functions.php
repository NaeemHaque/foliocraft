<?php
/**
 * FolioCraft theme bootstrap.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;

define( 'FOLIOCRAFT_VERSION', '0.1.0' );
define( 'FOLIOCRAFT_DIR', get_template_directory() );
define( 'FOLIOCRAFT_URI', get_template_directory_uri() );

require_once FOLIOCRAFT_DIR . '/inc/Autoloader.php';
\FolioCraft\Autoloader::register();

\FolioCraft\Theme::init();
\FolioCraft\Assets::init();
\FolioCraft\PostTypes\Taxonomies::init();
\FolioCraft\PostTypes\Project_CPT::init();
\FolioCraft\PostTypes\Experience_CPT::init();
\FolioCraft\Meta\Project_Meta::init();
\FolioCraft\Meta\Experience_Meta::init();
\FolioCraft\Customizer\Customizer::init();

if ( is_admin() ) {
	\FolioCraft\Admin\Menu::init();
}

/**
 * Fallback for the 'primary' nav location: the one-page anchor links rendered
 * when no menu is assigned. Passed to wp_nav_menu() as fallback_cb.
 */
function foliocraft_default_nav() {
	$base  = is_front_page() ? '' : home_url( '/' );
	$items = array(
		'#about'      => _x( 'about', 'nav item', 'foliocraft' ),
		'#experience' => _x( 'experience', 'nav item', 'foliocraft' ),
		'#work'       => _x( 'work', 'nav item', 'foliocraft' ),
		'#opensource' => _x( 'open-source', 'nav item', 'foliocraft' ),
		'#stack'      => _x( 'stack', 'nav item', 'foliocraft' ),
		'#writing'    => _x( 'writing', 'nav item', 'foliocraft' ),
	);
	foreach ( $items as $anchor => $label ) {
		echo '<a href="' . esc_url( $base . $anchor ) . '"><span class="hash">#</span>' . esc_html( $label ) . '</a>';
	}
}
