<?php
/**
 * Customizer registration for the Portfolio panel.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Customizer;

defined( 'ABSPATH' ) || exit;

class Customizer {

	public static function init() {
		add_action( 'customize_register', array( __CLASS__, 'register' ) );
	}

	public static function register( $wp_customize ) {
		$wp_customize->add_panel(
			'naeem_portfolio',
			array(
				'title'    => __( 'Portfolio', 'naeem-portfolio' ),
				'priority' => 30,
			)
		);
		$sections = array(
			'naeem_identity'   => __( 'Identity', 'naeem-portfolio' ),
			'naeem_hero'       => __( 'Hero', 'naeem-portfolio' ),
			'naeem_about'      => __( 'About', 'naeem-portfolio' ),
			'naeem_skills'     => __( 'Skills', 'naeem-portfolio' ),
			'naeem_opensource' => __( 'Open Source', 'naeem-portfolio' ),
			'naeem_social'     => __( 'Social Links', 'naeem-portfolio' ),
			'naeem_contact'    => __( 'Contact', 'naeem-portfolio' ),
			'naeem_resume'     => __( 'Résumé', 'naeem-portfolio' ),
			'naeem_footer'     => __( 'Footer', 'naeem-portfolio' ),
		);
		foreach ( $sections as $id => $title ) {
			$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'naeem_portfolio' ) );
		}
	}

	public static function sanitize_text( $v ) { return sanitize_text_field( $v ); }
	public static function sanitize_html( $v ) { return wp_kses_post( $v ); }
	public static function sanitize_url( $v ) { return esc_url_raw( $v ); }
	public static function sanitize_email_field( $v ) { return sanitize_email( $v ); }
	public static function sanitize_lines( $v ) { return sanitize_textarea_field( $v ); }
	public static function sanitize_hex( $v ) { return sanitize_hex_color( $v ); }
}
