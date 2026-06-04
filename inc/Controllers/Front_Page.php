<?php
/**
 * Front page controller — assembles section data and renders the views.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Controllers;

use Naeem\Core\View;
use Naeem\Models\Project;
use Naeem\Models\Experience;
use Naeem\Models\Post;

defined( 'ABSPATH' ) || exit;

class Front_Page {

	public function render() {
		get_header();

		$data = array( 'blog_url' => home_url( '/' ) );

		View::render( 'front/hero', $data );
		View::render( 'front/about', $data );
		View::render( 'front/experience', array( 'items' => Experience::all() ) );
		View::render( 'front/projects', array( 'projects' => Project::all(), 'terms' => Project::tech_terms() ) );
		View::render( 'front/opensource', $data );
		View::render( 'front/skills', $data );
		View::render( 'front/blog', array( 'posts' => Post::latest( 3 ), 'blog_url' => home_url( '/' ) ) );
		View::render( 'front/contact', $data );

		get_footer();
	}
}
