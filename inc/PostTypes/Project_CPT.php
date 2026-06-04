<?php
namespace Naeem\PostTypes;

defined( 'ABSPATH' ) || exit;

class Project_CPT {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	public static function register() {
		register_post_type(
			'project',
			array(
				'labels'       => array(
					'name'          => __( 'Projects', 'naeem-portfolio' ),
					'singular_name' => __( 'Project', 'naeem-portfolio' ),
					'add_new_item'  => __( 'Add New Project', 'naeem-portfolio' ),
				),
				'public'            => true,
				'show_in_menu'      => false,
				'show_in_admin_bar' => true,
				'menu_icon'         => 'dashicons-portfolio',
				'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
				'has_archive'  => false,
				'show_in_rest' => true,
				'rewrite'      => array( 'slug' => 'work' ),
			)
		);
	}
}
