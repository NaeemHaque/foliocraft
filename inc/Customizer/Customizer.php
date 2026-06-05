<?php
/**
 * Customizer registration for the Portfolio panel.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Customizer;

defined( 'ABSPATH' ) || exit;

class Customizer {

	public static function init() {
		add_action( 'customize_register', array( __CLASS__, 'register' ) );
	}

	public static function register( $wp_customize ) {
		$wp_customize->add_panel(
			'foliocraft',
			array(
				'title'    => __( 'Portfolio', 'foliocraft' ),
				'priority' => 30,
			)
		);
		$sections = array(
			'foliocraft_identity'   => __( 'Identity', 'foliocraft' ),
			'foliocraft_hero'       => __( 'Hero', 'foliocraft' ),
			'foliocraft_about'      => __( 'About', 'foliocraft' ),
			'foliocraft_skills'     => __( 'Skills', 'foliocraft' ),
			'foliocraft_opensource' => __( 'Open Source', 'foliocraft' ),
			'foliocraft_social'     => __( 'Social Links', 'foliocraft' ),
			'foliocraft_contact'    => __( 'Contact', 'foliocraft' ),
			'foliocraft_resume'     => __( 'Résumé', 'foliocraft' ),
			'foliocraft_footer'     => __( 'Footer', 'foliocraft' ),
		);
		foreach ( $sections as $id => $title ) {
			$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'foliocraft' ) );
		}

		// --- Hero ---
		$wp_customize->add_setting( 'foliocraft_hero_status', array(
			'default'           => 'Open to interesting open-source & product work',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_status', array(
			'label'   => __( 'Status pill text', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_name1', array(
			'default'           => 'Your',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_name1', array(
			'label'   => __( 'Name line 1', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_name2', array(
			'default'           => 'Name',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_name2', array(
			'label'   => __( 'Name line 2', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_roles', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_roles', array(
			'label'       => __( 'Typed roles (one role per line)', 'foliocraft' ),
			'section'     => 'foliocraft_hero',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_lead', array(
			'default'           => 'I build scalable products, developer tools, and web apps — and contribute to the open-source projects that power the web. Currently engineering WordPress products at <strong style="color:var(--text)">Acme Inc.</strong>, used by teams worldwide.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_lead', array(
			'label'   => __( 'Lead paragraph', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_cta_label', array(
			'default'           => 'View Projects',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_cta_label', array(
			'label'   => __( 'Primary CTA label', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_cta_url', array(
			'default'           => '#work',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_cta_url', array(
			'label'   => __( 'Primary CTA URL', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_hero_resume_label', array(
			'default'           => 'Download Résumé',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_resume_label', array(
			'label'   => __( 'Résumé button label', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_stat1_num', array(
			'default'           => '5+',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_stat1_num', array(
			'label'   => __( 'Stat 1 number', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_stat1_label', array(
			'default'           => 'Years building',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_stat1_label', array(
			'label'   => __( 'Stat 1 label', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_stat2_num', array(
			'default'           => '6',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_stat2_num', array(
			'label'   => __( 'Stat 2 number', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_stat2_label', array(
			'default'           => 'WP focus areas',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_stat2_label', array(
			'label'   => __( 'Stat 2 label', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_stat3_num', array(
			'default'           => 'M+',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_stat3_num', array(
			'label'   => __( 'Stat 3 number', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_stat3_label', array(
			'default'           => 'Users reached',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_stat3_label', array(
			'label'   => __( 'Stat 3 label', 'foliocraft' ),
			'section' => 'foliocraft_hero',
			'type'    => 'text',
		) );

		// --- Identity ---
		$wp_customize->add_setting( 'foliocraft_name', array(
			'default'           => 'Your Name',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_name', array(
			'label'   => __( 'Full name', 'foliocraft' ),
			'section' => 'foliocraft_identity',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_brand', array(
			'default'           => 'foliocraft',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_brand', array(
			'label'   => __( 'Brand slug', 'foliocraft' ),
			'section' => 'foliocraft_identity',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_accent', array(
			'default'           => '#e6926b',
			'sanitize_callback' => array( __CLASS__, 'sanitize_hex' ),
		) );
		$wp_customize->add_control( new \WP_Customize_Color_Control( $wp_customize, 'foliocraft_accent', array(
			'label'   => __( 'Accent color', 'foliocraft' ),
			'section' => 'foliocraft_identity',
		) ) );

		// --- Social ---
		$wp_customize->add_setting( 'foliocraft_social_github', array(
			'default'           => 'https://github.com/yourusername',
			'sanitize_callback' => array( __CLASS__, 'sanitize_url' ),
		) );
		$wp_customize->add_control( 'foliocraft_social_github', array(
			'label'   => __( 'GitHub URL', 'foliocraft' ),
			'section' => 'foliocraft_social',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( 'foliocraft_social_linkedin', array(
			'default'           => 'https://linkedin.com/in/yourusername',
			'sanitize_callback' => array( __CLASS__, 'sanitize_url' ),
		) );
		$wp_customize->add_control( 'foliocraft_social_linkedin', array(
			'label'   => __( 'LinkedIn URL', 'foliocraft' ),
			'section' => 'foliocraft_social',
			'type'    => 'url',
		) );

		$wp_customize->add_setting( 'foliocraft_social_x', array(
				'default'           => 'https://x.com/yourusername',
				'sanitize_callback' => array( __CLASS__, 'sanitize_url' ),
			) );
			$wp_customize->add_control( 'foliocraft_social_x', array(
				'label'   => __( 'X (Twitter) URL', 'foliocraft' ),
				'section' => 'foliocraft_social',
				'type'    => 'url',
			) );

			$wp_customize->add_setting( 'foliocraft_social_email', array(
			'default'           => 'hello@example.com',
			'sanitize_callback' => array( __CLASS__, 'sanitize_email_field' ),
		) );
		$wp_customize->add_control( 'foliocraft_social_email', array(
			'label'   => __( 'Contact email', 'foliocraft' ),
			'section' => 'foliocraft_social',
			'type'    => 'email',
		) );

		// --- About ---
		$wp_customize->add_setting( 'foliocraft_about_heading', array(
			'default'           => 'Engineering for scale, contributing in the open.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_about_heading', array(
			'label'   => __( 'Heading', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_about_p1', array(
			'default'           => "I'm a software engineer and open-source contributor focused on building <strong>scalable products, developer tools, and web apps</strong>. At Acme Inc., I work on WordPress product engineering, shipping software trusted by teams and businesses worldwide.",
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'foliocraft_about_p1', array(
			'label'   => __( 'Paragraph 1', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_about_p2', array(
			'default'           => 'I contribute to the <strong>WordPress Open Source Project</strong> (Core, Plugins, Meta, Polyglots, Photos) and <strong>Acme CMS</strong>. AI is now a core part of my workflow for research, planning, and debugging; the rest of my time goes to backend systems with a focus on architecture and clean code. I studied CSE at <strong>Example University</strong>, where programming contests shaped how I think and solve problems.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'foliocraft_about_p2', array(
			'label'   => __( 'Paragraph 2', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_pillar1_title', array(
			'default'           => 'Clean architecture',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_pillar1_title', array(
			'label'   => __( 'Pillar 1 title', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_pillar1_desc', array(
			'default'           => 'Maintainable, idiomatic code with structure that scales with the team and the product.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_pillar1_desc', array(
			'label'   => __( 'Pillar 1 description', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_pillar2_title', array(
			'default'           => 'Open by default',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_pillar2_title', array(
			'label'   => __( 'Pillar 2 title', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_pillar2_desc', array(
			'default'           => 'Contributing upstream — issues, patches, translations — to the tools the web runs on.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_pillar2_desc', array(
			'label'   => __( 'Pillar 2 description', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_pillar3_title', array(
			'default'           => 'Contest-grade detail',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_pillar3_title', array(
			'label'   => __( 'Pillar 3 title', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_pillar3_desc', array(
			'default'           => 'A competitive-programming background — fast under pressure, precise on the edge cases.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_pillar3_desc', array(
			'label'   => __( 'Pillar 3 description', 'foliocraft' ),
			'section' => 'foliocraft_about',
			'type'    => 'text',
		) );

		// --- Skills ---
		$wp_customize->add_setting( 'foliocraft_skillgroup1_label', array(
			'default'           => 'Backend',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup1_label', array(
			'label'   => __( 'Group 1 label', 'foliocraft' ),
			'section' => 'foliocraft_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup1_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup1_pills', array(
			'label'       => __( 'Group 1 pills (one per line)', 'foliocraft' ),
			'section'     => 'foliocraft_skills',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup2_label', array(
			'default'           => 'Frontend',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup2_label', array(
			'label'   => __( 'Group 2 label', 'foliocraft' ),
			'section' => 'foliocraft_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup2_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup2_pills', array(
			'label'       => __( 'Group 2 pills (one per line)', 'foliocraft' ),
			'section'     => 'foliocraft_skills',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup3_label', array(
			'default'           => 'Databases',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup3_label', array(
			'label'   => __( 'Group 3 label', 'foliocraft' ),
			'section' => 'foliocraft_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup3_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup3_pills', array(
			'label'       => __( 'Group 3 pills (one per line)', 'foliocraft' ),
			'section'     => 'foliocraft_skills',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup4_label', array(
			'default'           => 'Tools & AI',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup4_label', array(
			'label'   => __( 'Group 4 label', 'foliocraft' ),
			'section' => 'foliocraft_skills',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_skillgroup4_pills', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'foliocraft_skillgroup4_pills', array(
			'label'       => __( 'Group 4 pills (one per line)', 'foliocraft' ),
			'section'     => 'foliocraft_skills',
			'type'        => 'textarea',
		) );

		// --- Open Source ---
		$wp_customize->add_setting( 'foliocraft_os_heading', array(
			'default'           => 'Building the web in the open.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_os_heading', array(
			'label'   => __( 'Heading', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_os_lead', array(
			'default'           => 'Contributing to the WordPress Open Source Project across five focus areas, plus Acme CMS — fixing issues, shipping patches, and translating for a global community.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'foliocraft_os_lead', array(
			'label'   => __( 'Lead paragraph', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_os_areas', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_lines' ),
		) );
		$wp_customize->add_control( 'foliocraft_os_areas', array(
			'label'       => __( 'Areas (one per line)', 'foliocraft' ),
			'section'     => 'foliocraft_opensource',
			'type'        => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard1_title', array(
			'default'           => 'WP Core',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard1_title', array(
			'label'   => __( 'Card 1 title', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard1_role', array(
			'default'           => 'Patches & triage',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard1_role', array(
			'label'   => __( 'Card 1 role', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard1_blurb', array(
			'default'           => 'Bug fixes and issue triage on the platform that powers a huge share of the web.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard1_blurb', array(
			'label'   => __( 'Card 1 blurb', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard2_title', array(
			'default'           => 'Polyglots',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard2_title', array(
			'label'   => __( 'Card 2 title', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard2_role', array(
			'default'           => 'Translation',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard2_role', array(
			'label'   => __( 'Card 2 role', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard2_blurb', array(
			'default'           => 'Translating WordPress so it speaks more languages — i18n done the right way.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard2_blurb', array(
			'label'   => __( 'Card 2 blurb', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard3_title', array(
			'default'           => 'Photos & Meta',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard3_title', array(
			'label'   => __( 'Card 3 title', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard3_role', array(
			'default'           => 'Community',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard3_role', array(
			'label'   => __( 'Card 3 role', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard3_blurb', array(
			'default'           => 'Contributing to the Photo Directory and Meta tooling that keeps the project running.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard3_blurb', array(
			'label'   => __( 'Card 3 blurb', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard4_title', array(
			'default'           => 'Acme CMS',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard4_title', array(
			'label'   => __( 'Card 4 title', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard4_role', array(
			'default'           => 'Contributor',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard4_role', array(
			'label'   => __( 'Card 4 role', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_oscard4_blurb', array(
			'default'           => 'Helping build a modern, developer-friendly CMS out in the open.',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_oscard4_blurb', array(
			'label'   => __( 'Card 4 blurb', 'foliocraft' ),
			'section' => 'foliocraft_opensource',
			'type'    => 'text',
		) );

		// --- Contact ---
		$wp_customize->add_setting( 'foliocraft_contact_heading', array(
			'default'           => "Let's build something.",
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_contact_heading', array(
			'label'   => __( 'Heading', 'foliocraft' ),
			'section' => 'foliocraft_contact',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_contact_lead', array(
			'default'           => 'Open to interesting open-source collaborations and product engineering work. The fastest way to reach me is the form — or any of these:',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'foliocraft_contact_lead', array(
			'label'   => __( 'Lead paragraph', 'foliocraft' ),
			'section' => 'foliocraft_contact',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_contact_fluent', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_contact_fluent', array(
			'label'   => __( 'Fluent Forms shortcode (optional)', 'foliocraft' ),
			'section' => 'foliocraft_contact',
			'type'    => 'text',
		) );

		// --- Résumé ---
		$wp_customize->add_setting( 'foliocraft_resume_label', array(
			'default'           => 'Download Résumé',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_resume_label', array(
			'label'   => __( 'Résumé button label', 'foliocraft' ),
			'section' => 'foliocraft_resume',
			'type'    => 'text',
		) );

		$wp_customize->add_setting( 'foliocraft_resume_pdf', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( new \WP_Customize_Media_Control( $wp_customize, 'foliocraft_resume_pdf', array(
			'label'     => __( 'Résumé PDF', 'foliocraft' ),
			'section'   => 'foliocraft_resume',
			'mime_type' => 'application/pdf',
		) ) );

		// --- Identity: headshot ---
		$wp_customize->add_setting( 'foliocraft_headshot', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( new \WP_Customize_Media_Control( $wp_customize, 'foliocraft_headshot', array(
			'label'     => __( 'Headshot image', 'foliocraft' ),
			'section'   => 'foliocraft_identity',
			'mime_type' => 'image',
		) ) );

		// --- Footer ---
		$wp_customize->add_setting( 'foliocraft_footer_copy', array(
			'default'           => 'built with <a class="accent" href="https://github.com/NaeemHaque/foliocraft" target="_blank" rel="noopener">FolioCraft</a> — love this theme? own it <a class="accent" href="https://github.com/NaeemHaque/foliocraft" target="_blank" rel="noopener">here →</a>',
			'sanitize_callback' => array( __CLASS__, 'sanitize_html' ),
		) );
		$wp_customize->add_control( 'foliocraft_footer_copy', array(
			'label'   => __( 'Footer copy', 'foliocraft' ),
			'section' => 'foliocraft_footer',
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
