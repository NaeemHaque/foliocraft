<?php
/**
 * Experience data model.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Models;

defined( 'ABSPATH' ) || exit;

class Experience {

	/** @return array<int,array<string,mixed>> */
	public static function all() {
		$q   = new \WP_Query(
			array(
				'post_type'      => 'experience',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			)
		);
		$out = array();
		foreach ( $q->posts as $p ) {
			$names = wp_get_post_terms( $p->ID, 'tech', array( 'fields' => 'names' ) );
			$out[] = array(
				'role'    => get_the_title( $p ),
				'company' => (string) get_post_meta( $p->ID, '_naeem_company', true ),
				'when'    => (string) get_post_meta( $p->ID, '_naeem_date_range', true ),
				'current' => (bool) get_post_meta( $p->ID, '_naeem_is_current', true ),
				'badge'   => '',
				'desc'    => get_the_excerpt( $p ),
				'tags'    => is_wp_error( $names ) ? array() : $names,
			);
		}
		wp_reset_postdata();
		return $out;
	}
}
