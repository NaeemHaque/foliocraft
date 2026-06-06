<?php
/**
 * Front page controller — assembles section data and renders the views.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Controllers;

use FolioCraft\Core\View;
use FolioCraft\Models\Project;
use FolioCraft\Models\Experience;
use FolioCraft\Models\Post;
use FolioCraft\Models\Profile;

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

		if ( foliocraft_section_visible( 'about' ) ) {
			View::render( 'front/about', $data );
		}

		$experience = Experience::all();
		if ( $experience ) {
			View::render( 'front/experience', array( 'items' => $experience, 'profile' => $profile ) );
		}

		$projects = Project::all();
		if ( $projects ) {
			View::render( 'front/projects', array( 'projects' => $projects, 'terms' => Project::tech_terms(), 'profile' => $profile ) );
		}

		if ( foliocraft_section_visible( 'opensource' ) ) {
			View::render( 'front/opensource', $data );
		}

		if ( foliocraft_section_visible( 'stack' ) ) {
			View::render( 'front/skills', $data );
		}

		$posts = Post::latest( 3 );
		if ( $posts ) {
			View::render( 'front/blog', array( 'posts' => $posts, 'blog_url' => $blog_url, 'profile' => $profile ) );
		}

		View::render( 'front/contact', $data );

		get_footer();
	}
}
