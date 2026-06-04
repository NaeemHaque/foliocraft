<?php
/**
 * 404 controller.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Not_Found {

	public function render() {
		get_header();
		View::render( '404' );
		get_footer();
	}
}
