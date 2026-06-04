<?php
/**
 * Generic page controller.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Page {

	public function render() {
		get_header();
		while ( have_posts() ) {
			the_post();
			View::render( 'page' );
		}
		get_footer();
	}
}
