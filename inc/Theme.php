<?php
/**
 * Theme setup: supports, menus, image sizes, text domain.
 *
 * @package FolioCraft
 */

namespace FolioCraft;

defined( 'ABSPATH' ) || exit;

class Theme {

	public static function init() {
		add_action( 'after_setup_theme', array( __CLASS__, 'setup' ) );
	}

	public static function setup() {
		load_theme_textdomain( 'foliocraft', FOLIOCRAFT_DIR . '/languages' );

		add_theme_support( 'title-tag' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'custom-logo' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support(
			'html5',
			array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' )
		);

		register_nav_menus(
			array( 'primary' => __( 'Primary Menu', 'foliocraft' ) )
		);

		add_image_size( 'foliocraft-project', 800, 600, true );
		add_image_size( 'foliocraft-post', 720, 480, true );

		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = 800;
		}
	}
}
