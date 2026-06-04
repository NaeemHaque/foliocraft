<?php
/**
 * Blog post data model.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Models;

defined( 'ABSPATH' ) || exit;

class Post {

	/** @param int $n How many latest posts. @return array<int,array<string,mixed>> */
	public static function latest( $n = 3 ) {
		$q   = new \WP_Query(
			array(
				'post_type'           => 'post',
				'post_status'         => 'publish',
				'posts_per_page'      => (int) $n,
				'ignore_sticky_posts' => true,
			)
		);
		$out = array();
		foreach ( $q->posts as $p ) {
			$cats  = get_the_category( $p->ID );
			$words = str_word_count( wp_strip_all_tags( $p->post_content ) );
			$mins  = max( 1, (int) ceil( $words / 200 ) );
			$out[] = array(
				'cat'     => ! empty( $cats ) ? $cats[0]->name : __( 'Post', 'naeem-portfolio' ),
				/* translators: %d: estimated reading time in minutes. */
				'read'    => sprintf( __( '%d min read', 'naeem-portfolio' ), $mins ),
				'title'   => get_the_title( $p ),
				'excerpt' => get_the_excerpt( $p ),
				'url'     => get_permalink( $p ),
			);
		}
		wp_reset_postdata();
		return $out;
	}
}
