<?php
/**
 * Archive (category/tag/date) controller.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Archive {

	public function render() {
		get_header();
		$desc = get_the_archive_description();
		View::render(
			'blog/listing',
			array(
				'num'     => '06 /',
				'eyebrow' => __( 'Writing', 'naeem-portfolio' ),
				'title'   => wp_strip_all_tags( get_the_archive_title() ),
				'lead'    => $desc ? wp_strip_all_tags( $desc ) : __( 'Posts in this archive.', 'naeem-portfolio' ),
			)
		);
		get_footer();
	}
}
