<?php
/**
 * Résumé page controller.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;

defined( 'ABSPATH' ) || exit;

class Resume {

	public function render() {
		get_header();
		View::render(
			'resume',
			array(
				'profile'    => \Naeem\Models\Profile::all(),
				'experience' => \Naeem\Models\Experience::all(),
			)
		);
		get_footer();
	}
}
