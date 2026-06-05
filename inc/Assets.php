<?php
/**
 * Front-end asset enqueueing.
 *
 * @package FolioCraft
 */

namespace FolioCraft;

defined( 'ABSPATH' ) || exit;

class Assets {

	public static function init() {
		add_action( 'wp_enqueue_scripts', array( __CLASS__, 'enqueue' ) );
	}

	/** Cache-busting version from a theme-relative file's mtime (so CSS/JS changes propagate without a hard refresh). */
	private static function ver( $rel ) {
		$path = FOLIOCRAFT_DIR . '/' . $rel;
		return file_exists( $path ) ? (string) filemtime( $path ) : FOLIOCRAFT_VERSION;
	}

	public static function enqueue() {
		wp_enqueue_style(
			'foliocraft-fonts',
			'https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;600;700&family=Sora:wght@400;500;600;700&family=IBM+Plex+Sans:wght@400;500;600&family=IBM+Plex+Mono:wght@400;500;600&display=swap',
			array(),
			null
		);

		wp_enqueue_style(
			'foliocraft-app',
			FOLIOCRAFT_URI . '/assets/css/app.css',
			array( 'foliocraft-fonts' ),
			self::ver( 'assets/css/app.css' )
		);

		wp_enqueue_script(
			'foliocraft-main',
			FOLIOCRAFT_URI . '/assets/js/main.js',
			array(),
			self::ver( 'assets/js/main.js' ),
			true
		);
		wp_localize_script(
			'foliocraft-main',
			'FolioCraftData',
			array( 'roles' => \FolioCraft\Models\Profile::all()['hero']['roles'] )
		);

		if ( is_singular() && comments_open() && (int) get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
}
