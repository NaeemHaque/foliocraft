<?php
namespace Naeem\PostTypes;

defined( 'ABSPATH' ) || exit;

class Experience_CPT {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register' ) );
	}

	public static function register() {
		register_post_type(
			'experience',
			array(
				'labels'       => array(
					'name'          => __( 'Experience', 'naeem-portfolio' ),
					'singular_name' => __( 'Experience', 'naeem-portfolio' ),
					'add_new_item'  => __( 'Add New Entry', 'naeem-portfolio' ),
				),
				'public'            => false,
				'show_ui'           => true,
				'show_in_menu'      => false,
				'show_in_admin_bar' => true,
				'menu_icon'         => 'dashicons-businessperson',
				'supports'     => array( 'title', 'editor', 'page-attributes' ),
				'show_in_rest' => true,
			)
		);
	}
}
