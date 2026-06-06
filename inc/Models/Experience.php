<?php
/**
 * Experience data model — reads the timeline from the Customizer repeater
 * (a single JSON theme_mod), falling back to the bundled demo set.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Models;

defined( 'ABSPATH' ) || exit;

class Experience {

	/** @return array<int,array<string,mixed>> */
	public static function all() {
		return array_map( array( __CLASS__, 'shape' ), self::items() );
	}

	/** Decoded rows from the theme_mod, or the demo set when unset/empty. @return array<int,array<string,mixed>> */
	private static function items() {
		$items = json_decode( (string) get_theme_mod( 'foliocraft_experience', '' ), true );
		if ( ! is_array( $items ) || empty( $items ) ) {
			$items = self::demo();
		}
		return $items;
	}

	/** @return array<int,string> */
	private static function split_tech( $it ) {
		$raw = isset( $it['tech'] ) ? (string) $it['tech'] : '';
		return array_values( array_filter( array_map( 'trim', explode( ',', $raw ) ) ) );
	}

	/** Map a stored row to the shape the timeline template expects. */
	private static function shape( $it ) {
		return array(
			'role'    => isset( $it['title'] ) ? (string) $it['title'] : '',
			'company' => isset( $it['company'] ) ? (string) $it['company'] : '',
			'when'    => isset( $it['date_range'] ) ? (string) $it['date_range'] : '',
			'current' => ! empty( $it['is_current'] ),
			'desc'    => isset( $it['desc'] ) ? (string) $it['desc'] : '',
			'tags'    => self::split_tech( $it ),
		);
	}

	/** Demo timeline shown until the user adds their own; also the Customizer default. @return array<int,array<string,mixed>> */
	public static function demo() {
		return array(
			array( 'title' => 'Software Engineer', 'company' => 'Acme Inc.', 'date_range' => '2022 — Present', 'is_current' => 1, 'tech' => 'PHP, WordPress, Vue.js, REST APIs, MySQL', 'desc' => 'Product engineering on software used by teams and businesses worldwide — building scalable features, developer tooling, and the architecture behind them, from data models to REST APIs to Vue front-ends.' ),
			array( 'title' => 'Full-Stack Developer', 'company' => 'Example Studio', 'date_range' => '2020 — 2022', 'is_current' => 0, 'tech' => 'Laravel, MySQL, Vue.js, REST APIs', 'desc' => 'Built and maintained scalable web apps and backend systems with PHP, Laravel, and MySQL — clean data models and REST APIs paired with Vue.js front-ends.' ),
			array( 'title' => 'Open Source Contributor', 'company' => 'Open Source Community', 'date_range' => '2019 — Present', 'is_current' => 0, 'tech' => 'Core, Docs, Tooling, Community', 'desc' => 'Ongoing contributions across several open-source projects — fixing issues, reviewing, and improving docs with the global community.' ),
			array( 'title' => 'Computer Science & Engineering', 'company' => 'Example University', 'date_range' => 'B.Sc.', 'is_current' => 0, 'tech' => 'Algorithms, Data Structures, Competitive Programming', 'desc' => 'Studied computer science and competed in programming contests — the foundation for detail-oriented, fast problem-solving.' ),
		);
	}
}
