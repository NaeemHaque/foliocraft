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
		add_action( 'customize_controls_enqueue_scripts', array( __CLASS__, 'enqueue_controls' ) );
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
			'foliocraft_experience' => __( 'Experience', 'foliocraft' ),
			'foliocraft_projects'   => __( 'Projects', 'foliocraft' ),
			'foliocraft_skills'     => __( 'Skills', 'foliocraft' ),
			'foliocraft_opensource' => __( 'Open Source', 'foliocraft' ),
			'foliocraft_writing'    => __( 'Writing', 'foliocraft' ),
			'foliocraft_social'     => __( 'Social Links', 'foliocraft' ),
			'foliocraft_contact'    => __( 'Contact', 'foliocraft' ),
			'foliocraft_resume'     => __( 'Résumé', 'foliocraft' ),
			'foliocraft_footer'     => __( 'Footer', 'foliocraft' ),
		);
		foreach ( $sections as $id => $title ) {
			$wp_customize->add_section( $id, array( 'title' => $title, 'panel' => 'foliocraft' ) );
		}

		// --- Section labels (eyebrow) + the headings not already registered in their own block ---
		$fc_section_titles = array(
			'about'      => array( 'section' => 'foliocraft_about',      'eyebrow' => __( 'About', 'foliocraft' ),         'heading_key' => '',                              'heading' => '' ),
			'experience' => array( 'section' => 'foliocraft_experience', 'eyebrow' => __( 'Experience', 'foliocraft' ),    'heading_key' => 'foliocraft_experience_heading', 'heading' => "Where I've shipped." ),
			'work'       => array( 'section' => 'foliocraft_projects',   'eyebrow' => __( 'Selected Work', 'foliocraft' ), 'heading_key' => 'foliocraft_projects_heading',   'heading' => "Things I've built." ),
			'opensource' => array( 'section' => 'foliocraft_opensource', 'eyebrow' => __( 'Open Source', 'foliocraft' ),   'heading_key' => '',                              'heading' => '' ),
			'stack'      => array( 'section' => 'foliocraft_skills',     'eyebrow' => __( 'Tech Stack', 'foliocraft' ),    'heading_key' => 'foliocraft_skills_heading',     'heading' => 'Tools I reach for.' ),
			'writing'    => array( 'section' => 'foliocraft_writing',    'eyebrow' => __( 'Writing', 'foliocraft' ),       'heading_key' => 'foliocraft_blog_heading',       'heading' => 'From the blog.' ),
			'contact'    => array( 'section' => 'foliocraft_contact',    'eyebrow' => __( 'Contact', 'foliocraft' ),       'heading_key' => '',                              'heading' => '' ),
		);
		foreach ( $fc_section_titles as $sid => $cfg ) {
			$wp_customize->add_setting(
				'foliocraft_eyebrow_' . $sid,
				array( 'default' => $cfg['eyebrow'], 'sanitize_callback' => array( __CLASS__, 'sanitize_text' ) )
			);
			$wp_customize->add_control(
				'foliocraft_eyebrow_' . $sid,
				array( 'label' => __( 'Section label', 'foliocraft' ), 'section' => $cfg['section'], 'type' => 'text', 'priority' => 4 )
			);
			if ( '' !== $cfg['heading_key'] ) {
				$wp_customize->add_setting(
					$cfg['heading_key'],
					array( 'default' => $cfg['heading'], 'sanitize_callback' => array( __CLASS__, 'sanitize_text' ) )
				);
				$wp_customize->add_control(
					$cfg['heading_key'],
					array( 'label' => __( 'Heading', 'foliocraft' ), 'section' => $cfg['section'], 'type' => 'text', 'priority' => 5 )
				);
			}
		}

		// --- Hero aside (code terminal / image / hidden) ---
		$wp_customize->add_setting( 'foliocraft_hero_aside', array( 'default' => 'terminal', 'sanitize_callback' => array( __CLASS__, 'sanitize_aside_mode' ) ) );
		$wp_customize->add_control( 'foliocraft_hero_aside', array(
			'label'    => __( 'Hero visual', 'foliocraft' ),
			'section'  => 'foliocraft_hero',
			'type'     => 'radio',
			'choices'  => array( 'terminal' => __( 'Code terminal', 'foliocraft' ), 'image' => __( 'Image / GIF', 'foliocraft' ), 'none' => __( 'Hidden', 'foliocraft' ) ),
			'priority' => 30,
		) );
		$wp_customize->add_setting( 'foliocraft_hero_terminal_file', array( 'default' => '~/foliocraft/profile.php', 'sanitize_callback' => array( __CLASS__, 'sanitize_text' ) ) );
		$wp_customize->add_control( 'foliocraft_hero_terminal_file', array( 'label' => __( 'Terminal title bar', 'foliocraft' ), 'section' => 'foliocraft_hero', 'type' => 'text', 'priority' => 31 ) );
		$wp_customize->add_setting( 'foliocraft_hero_terminal_code', array( 'default' => \FolioCraft\Models\Profile::default_terminal_code(), 'sanitize_callback' => array( __CLASS__, 'sanitize_code' ) ) );
		$wp_customize->add_control( 'foliocraft_hero_terminal_code', array(
			'label'       => __( 'Terminal code', 'foliocraft' ),
			'description' => __( 'Plain text; lightly syntax-highlighted in the terminal. Clear it to hide the terminal.', 'foliocraft' ),
			'section'     => 'foliocraft_hero',
			'type'        => 'textarea',
			'priority'    => 32,
		) );
		$wp_customize->add_setting( 'foliocraft_hero_image', array( 'default' => 0, 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control( new \WP_Customize_Media_Control( $wp_customize, 'foliocraft_hero_image', array(
			'label'       => __( 'Hero image / GIF', 'foliocraft' ),
			'description' => __( 'Shown when "Image / GIF" is selected above. An animated GIF works too.', 'foliocraft' ),
			'section'     => 'foliocraft_hero',
			'mime_type'   => 'image',
			'priority'    => 33,
		) ) );

		// --- Projects (repeater) ---
		$wp_customize->add_setting(
			'foliocraft_projects',
			array(
				'default'           => wp_json_encode( \FolioCraft\Models\Project::demo() ),
				'sanitize_callback' => array( __CLASS__, 'sanitize_projects' ),
			)
		);
		$wp_customize->add_control(
			new Repeater_Control(
				$wp_customize,
				'foliocraft_projects',
				array(
					'label'        => __( 'Projects', 'foliocraft' ),
					'description'  => __( 'The cards in the Selected Work grid. Drag to reorder; the filter pills come from the Tech field.', 'foliocraft' ),
					'section'      => 'foliocraft_projects',
					'button_label' => __( 'Add project', 'foliocraft' ),
					'fields'       => array(
						array( 'key' => 'title', 'label' => __( 'Title', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'desc', 'label' => __( 'Description', 'foliocraft' ), 'type' => 'textarea' ),
						array( 'key' => 'image', 'label' => __( 'Image', 'foliocraft' ), 'type' => 'media' ),
						array( 'key' => 'tech', 'label' => __( 'Tech (comma-separated)', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'live_url', 'label' => __( 'Live URL', 'foliocraft' ), 'type' => 'url' ),
						array( 'key' => 'github_url', 'label' => __( 'GitHub URL', 'foliocraft' ), 'type' => 'url' ),
						array( 'key' => 'stars', 'label' => __( 'Stars', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'lang', 'label' => __( 'Language', 'foliocraft' ), 'type' => 'text' ),
					),
				)
			)
		);

		// --- Experience (repeater) ---
		$wp_customize->add_setting(
			'foliocraft_experience',
			array(
				'default'           => wp_json_encode( \FolioCraft\Models\Experience::demo() ),
				'sanitize_callback' => array( __CLASS__, 'sanitize_experience' ),
			)
		);
		$wp_customize->add_control(
			new Repeater_Control(
				$wp_customize,
				'foliocraft_experience',
				array(
					'label'        => __( 'Experience', 'foliocraft' ),
					'description'  => __( 'The timeline entries. Drag to reorder; mark one as current to highlight it.', 'foliocraft' ),
					'section'      => 'foliocraft_experience',
					'button_label' => __( 'Add entry', 'foliocraft' ),
					'fields'       => array(
						array( 'key' => 'title', 'label' => __( 'Role / Title', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'company', 'label' => __( 'Company', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'date_range', 'label' => __( 'Date range', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'is_current', 'label' => __( 'Current / highlighted', 'foliocraft' ), 'type' => 'checkbox' ),
						array( 'key' => 'tech', 'label' => __( 'Tech (comma-separated)', 'foliocraft' ), 'type' => 'text' ),
						array( 'key' => 'desc', 'label' => __( 'Description', 'foliocraft' ), 'type' => 'textarea' ),
					),
				)
			)
		);

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
			'sanitize_callback' => array( __CLASS__, 'sanitize_link' ),
		) );
		$wp_customize->add_control( 'foliocraft_hero_cta_url', array(
			'label'   => __( 'Primary CTA URL', 'foliocraft' ),
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
			'default'           => 'I contribute to the <strong>WordPress Open Source Project</strong> (Core, Plugins, Meta, Polyglots, Photos) and <strong>Acme CMS</strong>. AI is now a core part of my workflow for research, planning, and debugging. The rest of my time goes to backend systems with a focus on architecture and clean code. I studied CSE at <strong>Example University</strong>, where programming contests shaped how I think and solve problems.',
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
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
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
			'default'           => 'Open to interesting open-source collaborations and product engineering work. The fastest way to reach me is email — or any of these:',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_contact_lead', array(
			'label'   => __( 'Lead paragraph', 'foliocraft' ),
			'section' => 'foliocraft_contact',
			'type'    => 'textarea',
		) );

		$wp_customize->add_setting( 'foliocraft_contact_fluent', array(
			'default'           => '',
			'sanitize_callback' => array( __CLASS__, 'sanitize_shortcode' ),
		) );
		$wp_customize->add_control( 'foliocraft_contact_fluent', array(
			'label'       => __( 'Contact form shortcode', 'foliocraft' ),
			'description' => __( 'Optional — paste a form shortcode to replace the email button. Works with any form plugin, e.g. Fluent Forms: [fluentform id="1"].', 'foliocraft' ),
			'section'     => 'foliocraft_contact',
			'type'        => 'text',
		) );

		// --- Résumé ---
		$wp_customize->add_setting( 'foliocraft_resume_label', array(
			'default'           => 'Download Résumé',
			'sanitize_callback' => array( __CLASS__, 'sanitize_text' ),
		) );
		$wp_customize->add_control( 'foliocraft_resume_label', array(
			'label'       => __( 'Résumé button label', 'foliocraft' ),
			'description' => __( 'Labels both the hero “Download Résumé” button and the résumé page download button.', 'foliocraft' ),
			'section'     => 'foliocraft_resume',
			'type'        => 'text',
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
			'default'           => 'built with <a class="accent" href="https://github.com/naeemhaque/foliocraft" target="_blank" rel="noopener">FolioCraft</a>',
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
	public static function sanitize_aside_mode( $v ) { return in_array( $v, array( 'terminal', 'image', 'none' ), true ) ? $v : 'terminal'; }
	public static function sanitize_code( $v ) { return (string) wp_check_invalid_utf8( (string) $v, true ); }

	/**
	 * Restrict the contact "form shortcode" to a single well-formed shortcode
	 * tag (e.g. [fluentform id="1"]) or empty — no surrounding text/HTML, no
	 * multiple/nested shortcodes — so do_shortcode() can't be fed arbitrary input.
	 */
	public static function sanitize_shortcode( $v ) {
		$v = trim( sanitize_text_field( (string) $v ) );
		if ( '' === $v ) {
			return '';
		}
		return preg_match( '/\A\[[a-zA-Z0-9_-]+[^\[\]]*\]\z/', $v ) ? $v : '';
	}

	/** Sanitize a link that may be an in-page anchor (#work) or a full/relative URL. */
	public static function sanitize_link( $v ) {
		$v = trim( (string) $v );
		if ( '' === $v ) {
			return '';
		}
		if ( '#' === $v[0] ) {
			return '#' . preg_replace( '/[^A-Za-z0-9_-]/', '', substr( $v, 1 ) );
		}
		return esc_url_raw( $v );
	}

	/** Validate + re-encode the repeater JSON (projects). */
	public static function sanitize_projects( $value ) {
		$items = json_decode( (string) $value, true );
		if ( ! is_array( $items ) ) {
			return '';
		}
		$clean = array();
		foreach ( $items as $it ) {
			if ( ! is_array( $it ) ) {
				continue;
			}
			$clean[] = array(
				'title'      => sanitize_text_field( isset( $it['title'] ) ? $it['title'] : '' ),
				'desc'       => sanitize_textarea_field( isset( $it['desc'] ) ? $it['desc'] : '' ),
				'image'      => absint( isset( $it['image'] ) ? $it['image'] : 0 ),
				'tech'       => sanitize_text_field( isset( $it['tech'] ) ? $it['tech'] : '' ),
				'live_url'   => esc_url_raw( isset( $it['live_url'] ) ? $it['live_url'] : '' ),
				'github_url' => esc_url_raw( isset( $it['github_url'] ) ? $it['github_url'] : '' ),
				'stars'      => sanitize_text_field( isset( $it['stars'] ) ? $it['stars'] : '' ),
				'lang'       => sanitize_text_field( isset( $it['lang'] ) ? $it['lang'] : '' ),
			);
		}
		return wp_json_encode( $clean );
	}

	/** Validate + re-encode the repeater JSON (experience). */
	public static function sanitize_experience( $value ) {
		$items = json_decode( (string) $value, true );
		if ( ! is_array( $items ) ) {
			return '';
		}
		$clean = array();
		foreach ( $items as $it ) {
			if ( ! is_array( $it ) ) {
				continue;
			}
			$clean[] = array(
				'title'      => sanitize_text_field( isset( $it['title'] ) ? $it['title'] : '' ),
				'company'    => sanitize_text_field( isset( $it['company'] ) ? $it['company'] : '' ),
				'date_range' => sanitize_text_field( isset( $it['date_range'] ) ? $it['date_range'] : '' ),
				'is_current' => empty( $it['is_current'] ) ? 0 : 1,
				'tech'       => sanitize_text_field( isset( $it['tech'] ) ? $it['tech'] : '' ),
				'desc'       => sanitize_textarea_field( isset( $it['desc'] ) ? $it['desc'] : '' ),
			);
		}
		return wp_json_encode( $clean );
	}

	/** Enqueue the repeater control's script/style in the Customizer pane. */
	public static function enqueue_controls() {
		wp_enqueue_media();
		wp_enqueue_script(
			'foliocraft-customizer-repeater',
			FOLIOCRAFT_URI . '/assets/js/customizer-repeater.js',
			array( 'jquery', 'jquery-ui-sortable', 'customize-controls', 'wp-util' ),
			FOLIOCRAFT_VERSION,
			true
		);
		wp_localize_script(
			'foliocraft-customizer-repeater',
			'fcRep',
			array(
				'i18n' => array(
					'select'   => __( 'Select image', 'foliocraft' ),
					'change'   => __( 'Change image', 'foliocraft' ),
					'remove'   => __( 'Remove', 'foliocraft' ),
					'use'      => __( 'Use image', 'foliocraft' ),
					'untitled' => __( '(untitled)', 'foliocraft' ),
					'drag'     => __( 'Drag to reorder', 'foliocraft' ),
				),
			)
		);
		wp_enqueue_style(
			'foliocraft-customizer-repeater',
			FOLIOCRAFT_URI . '/assets/css/customizer-repeater.css',
			array(),
			FOLIOCRAFT_VERSION
		);
	}
}
