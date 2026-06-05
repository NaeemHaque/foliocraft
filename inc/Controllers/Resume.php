<?php
/**
 * Résumé page controller.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Controllers;

use FolioCraft\Core\View;

defined( 'ABSPATH' ) || exit;

class Resume {

	public function render() {
		get_header();
		View::render(
			'resume',
			array(
				'profile'    => \FolioCraft\Models\Profile::all(),
				'experience' => \FolioCraft\Models\Experience::all(),
			)
		);
		get_footer();
	}
}
