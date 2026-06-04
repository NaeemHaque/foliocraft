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

		// --- Identity ---
		$wp_customize->add_setting( 'naeem_name', array(
			'default'           => 'Golam Sarwer Naeem',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_name', array(
			'label'   => __( 'Full name', 'naeem-portfolio' ),
			'section' => 'naeem_identity',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_brand', array(
			'default'           => 'naeem',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_brand', array(
			'label'   => __( 'Brand slug', 'naeem-portfolio' ),
			'section' => 'naeem_identity',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_accent', array(
			'default'           => '#e6926b',
			'sanitize_callback' => array( __CLASS__, 'sanitize_hex' ),
		) );
		$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'naeem_accent', array(
			'label'   => __( 'Accent color', 'naeem-portfolio' ),
			'section' => 'naeem_identity',
		) ) );

		// --- Social ---
		$wp_customize->add_setting( 'naeem_social_github', array(
			'default'           => 'https://github.com/naeemHaque',
			'sanitize_callback' => array( __CLASS__, 'sanitize_url' ),
		) );
		$wp_customize->add_control( 'naeem_social_github', array(
			'label'   => __( 'GitHub URL', 'naeem-portfolio' ),
			'section' => 'naeem_social',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( 'naeem_social_linkedin', array(
			'default'           => 'https://linkedin.com/in/golam-sarwer-8626101a3',
			'sanitize_callback' => array( __CLASS__, 'sanitize_url' ),
		) );
		$wp_customize->add_control( 'naeem_social_linkedin', array(
			'label'   => __( 'LinkedIn URL', 'naeem-portfolio' ),
			'section' => 'naeem_social',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( 'naeem_social_email', array(
			'default'           => 'hello@naeem.dev',
			'sanitize_callback' => array( __CLASS__, 'sanitize_email_field' ),
		) );
		$wp_customize->add_control( 'naeem_social_email', array(
			'label'   => __( 'Contact email', 'naeem-portfolio' ),
			'section' => 'naeem_social',
			'type'    => 'email',
		) );

		// --- Footer ---
		$wp_customize->add_setting( 'naeem_footer_copy', array(
			'default'           => 'built with <span class="accent">clean code</span> & open source',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'naeem_footer_copy', array(
			'label'   => __( 'Footer copy', 'naeem-portfolio' ),
			'section' => 'naeem_footer',
			'type'    => 'textarea',
		) );
	}

	public static function sanitize_text( $v ) { return sanitize_text_field( $v ); }
	public static function sanitize_html( $v ) { return wp_kses_post( $v ); }
	public static function sanitize_url( $v ) { return esc_url_raw( $v ); }
	public static function sanitize_email_field( $v ) { return sanitize_email( $v ); }
	public static function sanitize_lines( $v ) { return sanitize_textarea_field( $v ); }
	public static function sanitize_hex( $v ) { return sanitize_hex_color( $v ); }
}
