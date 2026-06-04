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
use Naeem\Models\Profile;

defined( 'ABSPATH' ) || exit;

class Front_Page {

	public function render() {
		get_header();

		$profile  = Profile::all();
		$blog_url = get_permalink( get_option( 'page_for_posts' ) );
		if ( ! $blog_url ) {
			$blog_url = home_url( '/' );
		}

		$data = array( 'blog_url' => $blog_url, 'profile' => $profile );

		View::render( 'front/hero', $data );
		View::render( 'front/about', $data );
		View::render( 'front/experience', array( 'items' => Experience::all(), 'profile' => $profile ) );
		View::render( 'front/projects', array( 'projects' => Project::all(), 'terms' => Project::tech_terms(), 'profile' => $profile ) );
		View::render( 'front/opensource', $data );
		View::render( 'front/skills', $data );
		View::render( 'front/blog', array( 'posts' => Post::latest( 3 ), 'blog_url' => $blog_url, 'profile' => $profile ) );
		View::render( 'front/contact', $data );

		get_footer();
	}
}
