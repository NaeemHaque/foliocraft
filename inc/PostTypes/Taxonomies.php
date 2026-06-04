<?php
namespace Naeem\PostTypes;

defined( 'ABSPATH' ) || exit;

class Taxonomies {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	public static function register() {
		register_taxonomy(
			'tech',
			array( 'project', 'experience' ),
			array(
				'labels'            => array(
					'name'          => __( 'Tech', 'naeem-portfolio' ),
					'singular_name' => __( 'Tech', 'naeem-portfolio' ),
				),
				'public'            => true,
				'hierarchical'      => false,
				'show_admin_column' => true,
				'show_in_rest'      => true,
			)
		);
		register_taxonomy(
			'contribution_area',
			array( 'project' ),
			array(
				'labels'            => array(
					'name'          => __( 'Contribution Areas', 'naeem-portfolio' ),
					'singular_name' => __( 'Contribution Area', 'naeem-portfolio' ),
				),
				'public'            => true,
				'hierarchical'      => false,
				'show_admin_column' => true,
				'show_in_rest'      => true,
			)
		);
	}
}
