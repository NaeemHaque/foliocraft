<?php
/**
 * Consolidated "Portfolio" admin menu.
 *
 * The project/experience CPTs and their taxonomies register with
 * show_in_menu => false (no auto top-level menus); this gathers them — plus
 * Add-New links and the Customizer panel — under a single "Portfolio" menu.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Admin;

defined( 'ABSPATH' ) || exit;

class Menu {

	const SLUG = 'naeem-portfolio';

	public static function init() {
		add_action( 'admin_menu', array( __CLASS__, 'register' ) );
		add_filter( 'parent_file', array( __CLASS__, 'highlight_parent' ) );
	}

	public static function register() {
		add_menu_page(
			__( 'Portfolio', 'naeem-portfolio' ),
			__( 'Portfolio', 'naeem-portfolio' ),
			'edit_posts',
			self::SLUG,
			array( __CLASS__, 'landing' ),
			'dashicons-portfolio',
			58
		);

		// label, capability, target page — order here is the submenu order.
		$items = array(
			array( __( 'Projects', 'naeem-portfolio' ), 'edit_posts', 'edit.php?post_type=project' ),
			array( __( 'Add Project', 'naeem-portfolio' ), 'edit_posts', 'post-new.php?post_type=project' ),
			array( __( 'Experience', 'naeem-portfolio' ), 'edit_posts', 'edit.php?post_type=experience' ),
			array( __( 'Add Experience', 'naeem-portfolio' ), 'edit_posts', 'post-new.php?post_type=experience' ),
			array( __( 'Tech', 'naeem-portfolio' ), 'manage_categories', 'edit-tags.php?taxonomy=tech&post_type=project' ),
			array( __( 'Contribution Areas', 'naeem-portfolio' ), 'manage_categories', 'edit-tags.php?taxonomy=contribution_area&post_type=project' ),
			array( __( 'Customize', 'naeem-portfolio' ), 'edit_theme_options', 'customize.php?autofocus[panel]=naeem_portfolio' ),
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
