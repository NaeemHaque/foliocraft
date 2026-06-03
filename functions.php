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

// Service classes are booted in Task 4:
// \Naeem\Theme::init();
// \Naeem\Assets::init();
