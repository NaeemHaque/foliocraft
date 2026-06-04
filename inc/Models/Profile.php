<?php
/**
 * Profile model — returns every editable piece of front-page content.
 *
 * Each value is read from get_theme_mod( 'naeem_<key>', <DEFAULT> ), where the
 * default is the exact string currently rendered by the views. With no mods set,
 * the site renders identically.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Models;

defined( 'ABSPATH' ) || exit;

class Profile {

	/**
	 * Assemble the full profile content tree.
	 *
	 * @return array
	 */
	public static function all() {
		return array(
			'identity'   => array(
				'name'  => get_theme_mod( 'naeem_name', 'Golam Sarwer Naeem' ),
				'brand' => get_theme_mod( 'naeem_brand', 'naeem' ),
			),
			'hero'       => array(
				'status'            => get_theme_mod( 'naeem_hero_status', 'Open to interesting open-source & product work' ),
				'name_line1'        => get_theme_mod( 'naeem_hero_name1', 'Golam Sarwer' ),
				'name_line2'        => get_theme_mod( 'naeem_hero_name2', 'Naeem' ),
				'roles'             => self::lines(
					'naeem_hero_roles',
					array(
						'Software Engineer',
						'Open Source Contributor',
						'WordPress Product Engineer',
						'Laravel + Vue developer',
						'Clean-architecture advocate',
					)
				),
				'lead'              => get_theme_mod( 'naeem_hero_lead', 'I build scalable products, developer tools, and web apps — and contribute to the open-source projects that power the web. Currently engineering WordPress products at <strong style="color:var(--text)">WPManageNinja</strong>, used by millions worldwide.' ),
				'cta_primary_label' => get_theme_mod( 'naeem_hero_cta_label', 'View Projects' ),
				'cta_primary_url'   => get_theme_mod( 'naeem_hero_cta_url', '#work' ),
				'cta_resume_label'  => get_theme_mod( 'naeem_hero_resume_label', 'Download Résumé' ),
				'stats'             => array(
					array(
						'num'   => get_theme_mod( 'naeem_stat1_num', '5+' ),
						'label' => get_theme_mod( 'naeem_stat1_label', 'Years building' ),
					),
					array(
						'num'   => get_theme_mod( 'naeem_stat2_num', '6' ),
						'label' => get_theme_mod( 'naeem_stat2_label', 'WP focus areas' ),
					),
					array(
						'num'   => get_theme_mod( 'naeem_stat3_num', 'M+' ),
						'label' => get_theme_mod( 'naeem_stat3_label', 'Users reached' ),
					),
				),
			),
			'about'      => array(
				'heading'    => get_theme_mod( 'naeem_about_heading', 'Engineering for scale, contributing in the open.' ),
				'paragraphs' => array(
					get_theme_mod( 'naeem_about_p1', "I'm a software engineer and open-source contributor focused on building <strong>scalable products, developer tools, and web apps</strong>. At WPManageNinja, I work on WordPress product engineering, shipping software trusted by millions of users and businesses worldwide." ),
					get_theme_mod( 'naeem_about_p2', 'I contribute to the <strong>WordPress Open Source Project</strong> (Core, Plugins, Meta, Polyglots, Photos) and <strong>EmDash CMS</strong>. AI is now a core part of my workflow for research, planning, and debugging; the rest of my time goes to backend systems with a focus on architecture and clean code. I studied CSE at <strong>Sylhet International University</strong>, where programming contests shaped how I think and solve problems.' ),
				),
				'pillars'    => array(
					array(
						'title' => get_theme_mod( 'naeem_pillar1_title', 'Clean architecture' ),
						'desc'  => get_theme_mod( 'naeem_pillar1_desc', 'Maintainable, idiomatic code with structure that scales with the team and the product.' ),
					),
					array(
						'title' => get_theme_mod( 'naeem_pillar2_title', 'Open by default' ),
						'desc'  => get_theme_mod( 'naeem_pillar2_desc', 'Contributing upstream — issues, patches, translations — to the tools the web runs on.' ),
					),
					array(
						'title' => get_theme_mod( 'naeem_pillar3_title', 'Contest-grade detail' ),
						'desc'  => get_theme_mod( 'naeem_pillar3_desc', 'A competitive-programming background — fast under pressure, precise on the edge cases.' ),
					),
				),
			),
			'skills'     => array(
				'groups' => array(
					array(
						'label' => get_theme_mod( 'naeem_skillgroup1_label', 'Backend' ),
						'pills' => self::lines(
							'naeem_skillgroup1_pills',
							array( 'PHP', 'Laravel', 'WordPress', 'REST APIs' )
						),
					),
					array(
						'label' => get_theme_mod( 'naeem_skillgroup2_label', 'Frontend' ),
						'pills' => self::lines(
							'naeem_skillgroup2_pills',
							array( 'Vue.js', 'Tailwind CSS', 'JavaScript', 'HTML/CSS' )
						),
					),
					array(
						'label' => get_theme_mod( 'naeem_skillgroup3_label', 'Databases' ),
						'pills' => self::lines(
							'naeem_skillgroup3_pills',
							array( 'MySQL', 'Schema design', 'Query tuning' )
						),
					),
					array(
						'label' => get_theme_mod( 'naeem_skillgroup4_label', 'Tools & AI' ),
						'pills' => self::lines(
							'naeem_skillgroup4_pills',
							array( 'Git', 'AI workflow', 'Composer', 'Vite', 'CLI tooling' )
						),
					),
				),
			),
			'opensource' => array(
				'heading' => get_theme_mod( 'naeem_os_heading', 'Building the web in the open.' ),
				'lead'    => get_theme_mod( 'naeem_os_lead', 'Contributing to the WordPress Open Source Project across five focus areas, plus EmDash CMS — fixing issues, shipping patches, and translating for a global community.' ),
				'areas'   => self::lines(
					'naeem_os_areas',
					array( 'Core', 'Plugins', 'Meta', 'Polyglots', 'Photos', 'EmDash CMS' )
				),
				'cards'   => array(
					array(
						'title' => get_theme_mod( 'naeem_oscard1_title', 'WP Core' ),
						'role'  => get_theme_mod( 'naeem_oscard1_role', 'Patches & triage' ),
						'blurb' => get_theme_mod( 'naeem_oscard1_blurb', 'Bug fixes and issue triage on the platform that powers a huge share of the web.' ),
					),
					array(
						'title' => get_theme_mod( 'naeem_oscard2_title', 'Polyglots' ),
						'role'  => get_theme_mod( 'naeem_oscard2_role', 'Translation' ),
						'blurb' => get_theme_mod( 'naeem_oscard2_blurb', 'Translating WordPress so it speaks more languages — i18n done the right way.' ),
					),
					array(
						'title' => get_theme_mod( 'naeem_oscard3_title', 'Photos & Meta' ),
						'role'  => get_theme_mod( 'naeem_oscard3_role', 'Community' ),
						'blurb' => get_theme_mod( 'naeem_oscard3_blurb', 'Contributing to the Photo Directory and Meta tooling that keeps the project running.' ),
					),
					array(
						'title' => get_theme_mod( 'naeem_oscard4_title', 'EmDash CMS' ),
						'role'  => get_theme_mod( 'naeem_oscard4_role', 'Contributor' ),
						'blurb' => get_theme_mod( 'naeem_oscard4_blurb', 'Helping build a modern, developer-friendly CMS out in the open.' ),
					),
				),
			),
			'social'     => array(
				'github'   => get_theme_mod( 'naeem_social_github', 'https://github.com/naeemHaque' ),
				'linkedin' => get_theme_mod( 'naeem_social_linkedin', 'https://linkedin.com/in/golam-sarwer-8626101a3' ),
				'email'    => get_theme_mod( 'naeem_social_email', 'hello@naeem.dev' ),
			),
			'contact'    => array(
				'heading'          => get_theme_mod( 'naeem_contact_heading', "Let's build something." ),
				'lead'             => get_theme_mod( 'naeem_contact_lead', 'Open to interesting open-source collaborations and product engineering work. The fastest way to reach me is the form — or any of these:' ),
				'fluent_shortcode' => get_theme_mod( 'naeem_contact_fluent', '' ),
			),
			'resume'     => self::resume_data(),
			'headshot'   => self::headshot_data(),
			'accent'     => get_theme_mod( 'naeem_accent', '#e6926b' ),
			'footer'     => array( 'copy' => get_theme_mod( 'naeem_footer_copy', 'built with <span class="accent">clean code</span> & open source' ) ),
		);
	}

	/**
	 * Build the résumé data array, resolving the media attachment ID to a URL.
	 *
	 * @return array
	 */
	private static function resume_data() {
		$rid = get_theme_mod( 'naeem_resume_pdf', 0 );
		return array(
			'url'   => $rid ? (string) wp_get_attachment_url( $rid ) : '',
			'label' => get_theme_mod( 'naeem_resume_label', 'Download Résumé' ),
		);
	}

	/**
	 * Build the headshot data array, resolving the media attachment ID to a URL.
	 *
	 * @return array
	 */
	private static function headshot_data() {
		$hid = get_theme_mod( 'naeem_headshot', 0 );
		return array( 'url' => $hid ? (string) wp_get_attachment_url( $hid ) : '' );
	}

	/**
	 * Resolve a newline-separated textarea mod into a clean array of lines,
	 * falling back to the supplied default when unset or empty.
	 *
	 * @param string $key     Theme mod key.
	 * @param array  $default Default list of lines.
	 * @return array
	 */
	private static function lines( $key, array $default ) {
		$raw = (string) get_theme_mod( $key, '' );
		if ( '' === trim( $raw ) ) {
			return $default;
		}
		$lines = array_values( array_filter( array_map( 'trim', explode( "\n", $raw ) ) ) );
		return ! empty( $lines ) ? $lines : $default;
	}
}
