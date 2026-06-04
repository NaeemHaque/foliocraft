<?php
/**
 * Naeem Portfolio theme bootstrap.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

define( 'NAEEM_VERSION', '0.1.0' );
define( 'NAEEM_DIR', get_template_directory() );
define( 'NAEEM_URI', get_template_directory_uri() );

require_once NAEEM_DIR . '/inc/Autoloader.php';
\Naeem\Autoloader::register();

\Naeem\Theme::init();
\Naeem\Assets::init();
\Naeem\PostTypes\Taxonomies::init();
\Naeem\PostTypes\Project_CPT::init();
\Naeem\PostTypes\Experience_CPT::init();
\Naeem\Meta\Project_Meta::init();
\Naeem\Meta\Experience_Meta::init();
\Naeem\Customizer\Customizer::init();
\Naeem\Forms\Contact::init();

if ( is_admin() ) {
	\Naeem\Admin\Menu::init();
}
