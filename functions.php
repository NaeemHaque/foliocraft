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
\FolioCraft\Forms\Contact::init();

if ( is_admin() ) {
	\FolioCraft\Admin\Menu::init();
}
