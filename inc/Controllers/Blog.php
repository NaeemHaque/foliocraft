<?php
/**
 * Blog listing controller.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Blog {

	public function render() {
		get_header();
		View::render(
			'blog/listing',
			array(
				'num'     => '06 /',
				'eyebrow' => __( 'Writing', 'naeem-portfolio' ),
				'title'   => __( 'Notes from the editor.', 'naeem-portfolio' ),
				'lead'    => __( 'Things I learn building WordPress products, contributing upstream, and keeping software clean — written down so I (and maybe you) remember them.', 'naeem-portfolio' ),
			)
		);
		get_footer();
	}
}
