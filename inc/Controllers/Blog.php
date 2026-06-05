<?php
/**
 * Blog listing controller.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Controllers;

use FolioCraft\Core\View;

defined( 'ABSPATH' ) || exit;

class Blog {

	public function render() {
		get_header();
		View::render(
			'blog/listing',
			array(
				'num'     => '06 /',
				'eyebrow' => __( 'Writing', 'foliocraft' ),
				'title'   => __( 'Notes from the editor.', 'foliocraft' ),
				'lead'    => __( 'Things I learn building WordPress products, contributing upstream, and keeping software clean — written down so I (and maybe you) remember them.', 'foliocraft' ),
			)
		);
		get_footer();
	}
}
