<?php
/**
 * Single post controller.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

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
