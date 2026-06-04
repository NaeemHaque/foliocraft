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

		// --- Hero ---
		$wp_customize->add_setting( 'naeem_hero_status', array(
			'default'           => 'Open to interesting open-source & product work',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_hero_status', array(
			'label'   => __( 'Status pill text', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_hero_name1', array(
			'default'           => 'Golam Sarwer',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_hero_name1', array(
			'label'   => __( 'Name line 1', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_hero_name2', array(
			'default'           => 'Naeem',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_hero_name2', array(
			'label'   => __( 'Name line 2', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_hero_roles', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'naeem_hero_roles', array(
			'label'       => __( 'Typed roles (one role per line)', 'naeem-portfolio' ),
			'section'     => 'naeem_hero',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_hero_lead', array(
			'default'           => 'I build scalable products, developer tools, and web apps — and contribute to the open-source projects that power the web. Currently engineering WordPress products at <strong style="color:var(--text)">WPManageNinja</strong>, used by millions worldwide.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'naeem_hero_lead', array(
			'label'   => __( 'Lead paragraph', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_hero_cta_label', array(
			'default'           => 'View Projects',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_hero_cta_label', array(
			'label'   => __( 'Primary CTA label', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_hero_cta_url', array(
			'default'           => '#work',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_hero_cta_url', array(
			'label'   => __( 'Primary CTA URL', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_hero_resume_label', array(
			'default'           => 'Download Résumé',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_hero_resume_label', array(
			'label'   => __( 'Résumé button label', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_stat1_num', array(
			'default'           => '5+',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_stat1_num', array(
			'label'   => __( 'Stat 1 number', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_stat1_label', array(
			'default'           => 'Years building',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_stat1_label', array(
			'label'   => __( 'Stat 1 label', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_stat2_num', array(
			'default'           => '6',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_stat2_num', array(
			'label'   => __( 'Stat 2 number', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_stat2_label', array(
			'default'           => 'WP focus areas',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_stat2_label', array(
			'label'   => __( 'Stat 2 label', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_stat3_num', array(
			'default'           => 'M+',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_stat3_num', array(
			'label'   => __( 'Stat 3 number', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_stat3_label', array(
			'default'           => 'Users reached',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_stat3_label', array(
			'label'   => __( 'Stat 3 label', 'naeem-portfolio' ),
			'section' => 'naeem_hero',
			'type'    => 'text',
		) );

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

		// --- About ---
		$wp_customize->add_setting( 'naeem_about_heading', array(
			'default'           => 'Engineering for scale, contributing in the open.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_about_heading', array(
			'label'   => __( 'Heading', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_about_p1', array(
			'default'           => "I'm a software engineer and open-source contributor focused on building <strong>scalable products, developer tools, and web apps</strong>. At WPManageNinja, I work on WordPress product engineering, shipping software trusted by millions of users and businesses worldwide.",
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'naeem_about_p1', array(
			'label'   => __( 'Paragraph 1', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_about_p2', array(
			'default'           => 'I contribute to the <strong>WordPress Open Source Project</strong> (Core, Plugins, Meta, Polyglots, Photos) and <strong>EmDash CMS</strong>. AI is now a core part of my workflow for research, planning, and debugging; the rest of my time goes to backend systems with a focus on architecture and clean code. I studied CSE at <strong>Sylhet International University</strong>, where programming contests shaped how I think and solve problems.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'naeem_about_p2', array(
			'label'   => __( 'Paragraph 2', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_pillar1_title', array(
			'default'           => 'Clean architecture',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_pillar1_title', array(
			'label'   => __( 'Pillar 1 title', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_pillar1_desc', array(
			'default'           => 'Maintainable, idiomatic code with structure that scales with the team and the product.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_pillar1_desc', array(
			'label'   => __( 'Pillar 1 description', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_pillar2_title', array(
			'default'           => 'Open by default',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_pillar2_title', array(
			'label'   => __( 'Pillar 2 title', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_pillar2_desc', array(
			'default'           => 'Contributing upstream — issues, patches, translations — to the tools the web runs on.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_pillar2_desc', array(
			'label'   => __( 'Pillar 2 description', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_pillar3_title', array(
			'default'           => 'Contest-grade detail',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_pillar3_title', array(
			'label'   => __( 'Pillar 3 title', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_pillar3_desc', array(
			'default'           => 'A competitive-programming background — fast under pressure, precise on the edge cases.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_pillar3_desc', array(
			'label'   => __( 'Pillar 3 description', 'naeem-portfolio' ),
			'section' => 'naeem_about',
			'type'    => 'text',
		) );

		// --- Skills ---
		$wp_customize->add_setting( 'naeem_skillgroup1_label', array(
			'default'           => 'Backend',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup1_label', array(
			'label'   => __( 'Group 1 label', 'naeem-portfolio' ),
			'section' => 'naeem_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup1_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup1_pills', array(
			'label'       => __( 'Group 1 pills (one per line)', 'naeem-portfolio' ),
			'section'     => 'naeem_skills',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup2_label', array(
			'default'           => 'Frontend',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup2_label', array(
			'label'   => __( 'Group 2 label', 'naeem-portfolio' ),
			'section' => 'naeem_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup2_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup2_pills', array(
			'label'       => __( 'Group 2 pills (one per line)', 'naeem-portfolio' ),
			'section'     => 'naeem_skills',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup3_label', array(
			'default'           => 'Databases',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup3_label', array(
			'label'   => __( 'Group 3 label', 'naeem-portfolio' ),
			'section' => 'naeem_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup3_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup3_pills', array(
			'label'       => __( 'Group 3 pills (one per line)', 'naeem-portfolio' ),
			'section'     => 'naeem_skills',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup4_label', array(
			'default'           => 'Tools & AI',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup4_label', array(
			'label'   => __( 'Group 4 label', 'naeem-portfolio' ),
			'section' => 'naeem_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'naeem_skillgroup4_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'naeem_skillgroup4_pills', array(
			'label'       => __( 'Group 4 pills (one per line)', 'naeem-portfolio' ),
			'section'     => 'naeem_skills',
			'type'        => 'textarea',
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
