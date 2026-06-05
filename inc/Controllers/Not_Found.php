<?php
/**
 * 404 controller.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Controllers;

use FolioCraft\Core\View;

defined( 'ABSPATH' ) || exit;

class Not_Found {

	public function render() {
		get_header();
		View::render( '404' );
		get_footer();
	}
}
