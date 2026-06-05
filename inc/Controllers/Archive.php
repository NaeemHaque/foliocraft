<?php
/**
 * Archive (category/tag/date) controller.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Controllers;

use FolioCraft\Core\View;

defined( 'ABSPATH' ) || exit;

class Archive {

	public function render() {
		get_header();
		$desc = get_the_archive_description();
		View::render(
			'blog/listing',
			array(
				'num'     => '06 /',
				'eyebrow' => __( 'Writing', 'foliocraft' ),
				'title'   => wp_strip_all_tags( get_the_archive_title() ),
				'lead'    => $desc ? wp_strip_all_tags( $desc ) : __( 'Posts in this archive.', 'foliocraft' ),
			)
		);
		get_footer();
	}
}
