<?php
/**
 * Single post controller.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Controllers;

use FolioCraft\Core\View;

defined( 'ABSPATH' ) || exit;

class Single_Post {

	public function render() {
		get_header();
		while ( have_posts() ) {
			the_post();
			View::render( 'blog/single' );
		}
		get_footer();
	}
}
