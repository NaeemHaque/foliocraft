<?php
/**
 * Project data model — reads the project list from the Customizer repeater
 * (a single JSON theme_mod), falling back to the bundled demo set.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Models;

defined( 'ABSPATH' ) || exit;

class Project {

	/** @return array<int,array<string,mixed>> */
	public static function all() {
		return array_map( array( __CLASS__, 'shape' ), self::items() );
	}

	/** Unique tech terms across all projects, for the filter pills. @return array<int,array<string,string>> */
	public static function tech_terms() {
		$seen = array();
		$out  = array();
		foreach ( self::items() as $it ) {
			foreach ( self::split_tech( $it ) as $name ) {
				$slug = sanitize_title( $name );
				if ( '' === $slug || isset( $seen[ $slug ] ) ) {
					continue;
				}
				$seen[ $slug ] = true;
				$out[]         = array( 'slug' => $slug, 'name' => $name );
			}
		}
		return $out;
	}

	/**
	 * Saved project rows (memoized). Falls back to the demo set ONLY when the mod
	 * was never set; once saved, a cleared repeater yields no rows so the section
	 * is hidden rather than re-showing demo data.
	 *
	 * @return array<int,array<string,mixed>>
	 */
	private static function items() {
		static $cache = null;
		if ( null !== $cache ) {
			return $cache;
		}
		$raw = get_theme_mod( 'foliocraft_projects', null );
		if ( null === $raw ) {
			$cache = self::demo();
		} else {
			$decoded = json_decode( (string) $raw, true );
			$cache   = is_array( $decoded ) ? $decoded : array();
		}
		return $cache;
	}

	/** @return array<int,string> */
	private static function split_tech( $it ) {
		$raw = isset( $it['tech'] ) ? (string) $it['tech'] : '';
		return array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) );
	}

	/** Map a stored row to the shape the card template expects. */
	private static function shape( $it ) {
		$tech = self::split_tech( $it );
		return array(
			'name'       => isset( $it['title'] ) ? (string) $it['title'] : '',
			'desc'       => isset( $it['desc'] ) ? (string) $it['desc'] : '',
			'live_url'   => isset( $it['live_url'] ) ? (string) $it['live_url'] : '',
			'github_url' => isset( $it['github_url'] ) ? (string) $it['github_url'] : '',
			'stars'      => isset( $it['stars'] ) ? (string) $it['stars'] : '',
			'lang'       => isset( $it['lang'] ) ? (string) $it['lang'] : '',
			'tags'       => $tech,
			'filters'    => array_map( 'sanitize_title', $tech ),
			'thumb_id'   => isset( $it['image'] ) ? (int) $it['image'] : 0,
		);
	}

	/** Demo projects shown until the user adds their own; also the Customizer default. @return array<int,array<string,mixed>> */
	public static function demo() {
		return array(
			array( 'title' => 'Open Dashboard', 'desc' => 'A team productivity dashboard surfacing PRs, deploys, and CI health in real time. API back-end, Vue front-end, MySQL.', 'image' => 0, 'tech' => 'Laravel, Vue, MySQL, PHP', 'live_url' => '', 'github_url' => 'https://github.com/yourusername/open-dashboard', 'stars' => '312', 'lang' => 'PHP' ),
			array( 'title' => 'Schema Helper', 'desc' => 'WordPress plugin that auto-generates schema.org structured data — Yoast/Rank Math friendly, zero config.', 'image' => 0, 'tech' => 'WordPress, PHP', 'live_url' => '', 'github_url' => 'https://github.com/yourusername/schema-helper', 'stars' => '1.2k', 'lang' => 'PHP' ),
			array( 'title' => 'Query Inspector', 'desc' => 'Slow-query analyzer for MySQL with a web UI — visualizes EXPLAIN plans and suggests indexes.', 'image' => 0, 'tech' => 'PHP, MySQL', 'live_url' => '', 'github_url' => 'https://github.com/yourusername/query-inspector', 'stars' => '486', 'lang' => 'PHP' ),
			array( 'title' => 'Block Starter Kit', 'desc' => 'A collection of reusable Vue-powered blocks and components for WordPress projects.', 'image' => 0, 'tech' => 'Vue, WordPress', 'live_url' => '', 'github_url' => 'https://github.com/yourusername/block-starter-kit', 'stars' => '740', 'lang' => 'Vue' ),
			array( 'title' => 'App Starter Kit', 'desc' => 'Opinionated Laravel starter with auth, queues, and a Vue + Tailwind front-end wired for clean architecture.', 'image' => 0, 'tech' => 'Laravel, Vue, MySQL, PHP', 'live_url' => '', 'github_url' => 'https://github.com/yourusername/app-starter-kit', 'stars' => '928', 'lang' => 'PHP' ),
			array( 'title' => 'Translation Tool', 'desc' => 'A contributor tool for software translators — speeds up string review and translation suggestions.', 'image' => 0, 'tech' => 'WordPress, Vue, PHP', 'live_url' => '', 'github_url' => 'https://github.com/yourusername/translation-tool', 'stars' => '203', 'lang' => 'JavaScript' ),
		);
	}
}
