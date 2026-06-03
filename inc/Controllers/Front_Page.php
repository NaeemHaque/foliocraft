<?php
/**
 * Front page controller — assembles section data and renders the views.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Front_Page {

	public function render() {
		get_header();

		$data = array( 'blog_url' => home_url( '/' ) );

		View::render( 'front/hero', $data );
		View::render( 'front/about', $data );
		View::render( 'front/experience', array( 'items' => $this->experience() ) );
		View::render( 'front/projects', array( 'projects' => $this->projects() ) );
		View::render( 'front/opensource', $data );
		View::render( 'front/skills', $data );
		View::render( 'front/blog', array( 'posts' => $this->posts(), 'blog_url' => home_url( '/' ) ) );
		View::render( 'front/contact', $data );

		get_footer();
	}

	/** Sample experience entries (replaced by the experience CPT in Phase 2). */
	private function experience() {
		return array(
			array(
				'role'    => __( 'Software Engineer', 'naeem-portfolio' ),
				'company' => 'WPManageNinja',
				'badge'   => __( 'WordPress products', 'naeem-portfolio' ),
				'when'    => __( '2022 — Present', 'naeem-portfolio' ),
				'current' => true,
				'desc'    => __( 'Product engineering on WordPress software used by millions of users and businesses worldwide. Building scalable features, developer-facing tooling, and the architecture behind it — from data models to REST APIs to front-end with Vue.', 'naeem-portfolio' ),
				'tags'    => array( 'PHP', 'WordPress', 'Vue.js', 'REST APIs', 'MySQL' ),
			),
			array(
				'role'    => __( 'Full-Stack Developer', 'naeem-portfolio' ),
				'company' => __( 'Product & web app work', 'naeem-portfolio' ),
				'badge'   => '',
				'when'    => __( '2020 — 2022', 'naeem-portfolio' ),
				'current' => false,
				'desc'    => __( 'Built and maintained scalable web apps and backend systems with PHP, Laravel, and MySQL — designing clean data models and REST APIs, and pairing them with Vue.js front-ends.', 'naeem-portfolio' ),
				'tags'    => array( 'Laravel', 'MySQL', 'Vue.js', 'REST APIs' ),
			),
			array(
				'role'    => __( 'Open Source Contributor', 'naeem-portfolio' ),
				'company' => 'WordPress Project · EmDash CMS',
				'badge'   => '',
				'when'    => __( '2019 — Present', 'naeem-portfolio' ),
				'current' => false,
				'desc'    => __( 'Ongoing contributions across WordPress Core, Plugins, Meta, Polyglots, and Photos, plus EmDash CMS — fixing issues, reviewing, and translating with the global community.', 'naeem-portfolio' ),
				'tags'    => array( 'Core', 'Polyglots', 'Meta', 'Photos' ),
			),
			array(
				'role'    => __( 'Computer Science & Engineering', 'naeem-portfolio' ),
				'company' => 'Sylhet International University',
				'badge'   => '',
				'when'    => __( 'B.Sc. CSE', 'naeem-portfolio' ),
				'current' => false,
				'desc'    => __( 'Studied CSE and competed heavily in programming contests — the foundation for detail-oriented, fast problem-solving that still shapes how I engineer today.', 'naeem-portfolio' ),
				'tags'    => array( 'Algorithms', 'Data Structures', 'Competitive Programming' ),
			),
		);
	}

	/** Sample projects (replaced by the project CPT in Phase 2). Mirrors the prototype data. */
	private function projects() {
		return array(
			array( 'name' => 'DevPulse', 'icon' => 'dash', 'desc' => __( 'A team productivity dashboard surfacing PRs, deploys, and CI health in real time. Laravel API, Vue front-end, MySQL.', 'naeem-portfolio' ), 'tags' => array( 'Laravel', 'Vue', 'MySQL', 'REST API' ), 'filters' => array( 'laravel', 'vue', 'mysql', 'php' ), 'stars' => '312', 'lang' => 'PHP' ),
			array( 'name' => 'WP Schema Pilot', 'icon' => 'plug', 'desc' => __( 'WordPress plugin that auto-generates schema.org structured data — Yoast/Rank Math friendly, zero config.', 'naeem-portfolio' ), 'tags' => array( 'WordPress', 'PHP', 'JavaScript' ), 'filters' => array( 'wordpress', 'php' ), 'stars' => '1.2k', 'lang' => 'PHP' ),
			array( 'name' => 'QueryLens', 'icon' => 'db', 'desc' => __( 'Slow-query analyzer for MySQL with a web UI — visualizes EXPLAIN plans and suggests indexes.', 'naeem-portfolio' ), 'tags' => array( 'PHP', 'MySQL', 'REST API' ), 'filters' => array( 'php', 'mysql' ), 'stars' => '486', 'lang' => 'PHP' ),
			array( 'name' => 'Fluent Blocks Kit', 'icon' => 'blocks', 'desc' => __( 'A collection of reusable Vue-powered blocks and components for WordPress and EmDash CMS projects.', 'naeem-portfolio' ), 'tags' => array( 'Vue', 'WordPress', 'Tailwind' ), 'filters' => array( 'vue', 'wordpress' ), 'stars' => '740', 'lang' => 'Vue' ),
			array( 'name' => 'LaravelKit Starter', 'icon' => 'rocket', 'desc' => __( 'Opinionated Laravel starter with auth, queues, and a Vue + Tailwind front-end wired for clean architecture.', 'naeem-portfolio' ), 'tags' => array( 'Laravel', 'Vue', 'MySQL' ), 'filters' => array( 'laravel', 'vue', 'mysql', 'php' ), 'stars' => '928', 'lang' => 'PHP' ),
			array( 'name' => 'Polyglot Helper', 'icon' => 'globe', 'desc' => __( 'A contributor tool for WordPress Polyglots — speeds up string review and translation suggestions.', 'naeem-portfolio' ), 'tags' => array( 'WordPress', 'Vue', 'i18n' ), 'filters' => array( 'wordpress', 'vue', 'php' ), 'stars' => '203', 'lang' => 'JavaScript' ),
		);
	}

	/** Sample latest posts (replaced by a WP_Query in Phase 2/4). */
	private function posts() {
		return array(
			array( 'cat' => __( 'WordPress', 'naeem-portfolio' ), 'read' => __( '8 min read', 'naeem-portfolio' ), 'title' => __( 'Building a CPT-driven portfolio the WordPress way', 'naeem-portfolio' ), 'excerpt' => __( 'Why custom post types and taxonomies beat hardcoded HTML, and how to model projects so anyone can manage them from wp-admin.', 'naeem-portfolio' ) ),
			array( 'cat' => __( 'Workflow', 'naeem-portfolio' ), 'read' => __( '6 min read', 'naeem-portfolio' ), 'title' => __( 'How I fold AI into my daily dev workflow', 'naeem-portfolio' ), 'excerpt' => __( 'From research and feature planning to debugging — a practical look at where AI actually saves time, and where it doesn\'t.', 'naeem-portfolio' ) ),
			array( 'cat' => __( 'Career', 'naeem-portfolio' ), 'read' => __( '5 min read', 'naeem-portfolio' ), 'title' => __( 'What competitive programming taught me about shipping', 'naeem-portfolio' ), 'excerpt' => __( 'Contest habits — reading edge cases first, thinking on your feet — that quietly made me a better product engineer.', 'naeem-portfolio' ) ),
		);
	}
}
