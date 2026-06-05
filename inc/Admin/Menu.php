<?php
/**
 * Consolidated "Portfolio" admin menu.
 *
 * The project/experience CPTs and their taxonomies register with
 * show_in_menu => false (no auto top-level menus); this gathers them — plus
 * Add-New links and the Customizer panel — under a single "Portfolio" menu.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Admin;

defined( 'ABSPATH' ) || exit;

class Menu {

	const SLUG = 'foliocraft';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register' ) );
		add_filter( 'parent_file', array( __CLASS__, 'highlight_parent' ) );
	}

	public static function register() {
		add_menu_page(
			__( 'Portfolio', 'foliocraft' ),
			__( 'Portfolio', 'foliocraft' ),
			'edit_posts',
			self::SLUG,
			array( __CLASS__, 'landing' ),
			'dashicons-portfolio',
			58
		);

		// label, capability, target page — order here is the submenu order.
		$items = array(
			array( __( 'Projects', 'foliocraft' ), 'edit_posts', 'edit.php?post_type=project' ),
			array( __( 'Add Project', 'foliocraft' ), 'edit_posts', 'post-new.php?post_type=project' ),
			array( __( 'Experience', 'foliocraft' ), 'edit_posts', 'edit.php?post_type=experience' ),
			array( __( 'Add Experience', 'foliocraft' ), 'edit_posts', 'post-new.php?post_type=experience' ),
			array( __( 'Tech', 'foliocraft' ), 'manage_categories', 'edit-tags.php?taxonomy=tech&post_type=project' ),
			array( __( 'Contribution Areas', 'foliocraft' ), 'manage_categories', 'edit-tags.php?taxonomy=contribution_area&post_type=project' ),
			array( __( 'Customize', 'foliocraft' ), 'edit_theme_options', 'customize.php?autofocus[panel]=foliocraft' ),
		);
		foreach ( $items as $item ) {
			add_submenu_page( self::SLUG, $item[0], $item[0], $item[1], $item[2] );
		}

		// Drop the duplicate auto-submenu add_menu_page creates (slug === parent slug).
		remove_submenu_page( self::SLUG, self::SLUG );
	}

	/** The Portfolio top-level page itself forwards to the Projects list. */
	public static function landing() {
		wp_safe_redirect( admin_url( 'edit.php?post_type=project' ) );
		exit;
	}

	/** Keep the Portfolio menu highlighted on the CPT + taxonomy screens. */
	public static function highlight_parent( $parent_file ) {
		$screen = function_exists( 'get_current_screen' ) ? get_current_screen() : null;
		if ( ! $screen ) {
			return $parent_file;
		}
		if ( in_array( $screen->post_type, array( 'project', 'experience' ), true )
			|| in_array( $screen->taxonomy, array( 'tech', 'contribution_area' ), true ) ) {
			return self::SLUG;
		}
		return $parent_file;
	}
}
