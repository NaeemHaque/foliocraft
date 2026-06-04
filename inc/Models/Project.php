<?php
/**
 * Project data model.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Models;

defined( 'ABSPATH' ) || exit;

class Project {

	/** @return array<int,array<string,mixed>> */
	public static function all() {
		$q   = new \WP_Query(
			array(
				'post_type'      => 'project',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'orderby'        => array( 'menu_order' => 'ASC', 'date' => 'DESC' ),
			)
		);
		$out = array();
		foreach ( $q->posts as $p ) {
			$names = wp_get_post_terms( $p->ID, 'tech', array( 'fields' => 'names' ) );
			$slugs = wp_get_post_terms( $p->ID, 'tech', array( 'fields' => 'slugs' ) );
			$out[] = array(
				'name'       => get_the_title( $p ),
				'desc'       => get_the_excerpt( $p ),
				'live_url'   => (string) get_post_meta( $p->ID, '_naeem_live_url', true ),
				'github_url' => (string) get_post_meta( $p->ID, '_naeem_github_url', true ),
				'stars'      => (string) get_post_meta( $p->ID, '_naeem_stars', true ),
				'lang'       => (string) get_post_meta( $p->ID, '_naeem_lang', true ),
				'tags'       => is_wp_error( $names ) ? array() : $names,
				'filters'    => is_wp_error( $slugs ) ? array() : $slugs,
				'thumb_id'   => (int) get_post_thumbnail_id( $p->ID ),
			);
		}
		wp_reset_postdata();
		return $out;
	}

	/** Tech terms attached to published projects, for the filter pills. @return array<int,array<string,string>> */
	public static function tech_terms() {
		$ids = get_posts(
			array(
				'post_type'      => 'project',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'fields'         => 'ids',
			)
		);
		if ( empty( $ids ) ) {
			return array();
		}
		$terms = wp_get_object_terms( $ids, 'tech' );
		$out   = array();
		$seen  = array();
		if ( ! is_wp_error( $terms ) ) {
			foreach ( $terms as $t ) {
				if ( isset( $seen[ $t->slug ] ) ) {
					continue;
				}
				$seen[ $t->slug ] = true;
				$out[]            = array( 'slug' => $t->slug, 'name' => $t->name );
			}
		}
		return $out;
	}
}
