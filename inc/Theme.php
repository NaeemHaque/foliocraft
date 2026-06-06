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
		add_action( 'widgets_init', array( __CLASS__, 'widgets' ) );
		add_action( 'wp_head', array( __CLASS__, 'no_fouc_script' ), 1 );
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
		add_theme_support( 'align-wide' );
		add_theme_support( 'wp-block-styles' );
		add_theme_support( 'editor-styles' );
		add_editor_style( 'assets/css/editor.css' );
		add_theme_support(
			'custom-header',
			array(
				'width'       => 1600,
				'height'      => 400,
				'flex-width'  => true,
				'flex-height' => true,
				'header-text' => false,
			)
		);
		add_theme_support( 'custom-background', array( 'default-color' => '' ) );

		register_nav_menus(
			array( 'primary' => __( 'Primary Menu', 'foliocraft' ) )
		);

		add_image_size( 'foliocraft-project', 800, 600, true );
		add_image_size( 'foliocraft-post', 720, 480, true );

		if ( ! isset( $GLOBALS['content_width'] ) ) {
			$GLOBALS['content_width'] = 800;
		}
	}

	/** Register the footer widget area. */
	public static function widgets() {
		register_sidebar(
			array(
				'name'          => __( 'Footer', 'foliocraft' ),
				'id'            => 'foliocraft-footer',
				'description'   => __( 'Widgets added here appear in the site footer.', 'foliocraft' ),
				'before_widget' => '<section id="%1$s" class="footer-widget %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h2 class="footer-widget-title">',
				'after_title'   => '</h2>',
			)
		);
	}

	/**
	 * Print the pre-paint theme + reveal bootstrap inline in <head>.
	 *
	 * Hooked early on wp_head (so it runs before stylesheets, avoiding a flash)
	 * via an enqueue-style hook rather than a raw <script> tag in header.php.
	 */
	public static function no_fouc_script() {
		$js = '(function(){try{var t=localStorage.getItem("foliocraft-theme")||"dark";document.documentElement.setAttribute("data-theme",t);}catch(e){document.documentElement.setAttribute("data-theme","dark");}try{if(!window.matchMedia("(prefers-reduced-motion: reduce)").matches){document.documentElement.classList.add("reveal-on");}}catch(e){}})();';
		if ( function_exists( 'wp_print_inline_script_tag' ) ) {
			wp_print_inline_script_tag( $js );
		} else {
			echo '<script>' . $js . '</script>'; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript, WordPress.Security.EscapeOutput.OutputNotEscaped -- inline no-FOUC bootstrap.
		}
	}
}
