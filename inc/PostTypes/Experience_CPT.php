<?php
namespace FolioCraft\PostTypes;

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
					'name'          => __( 'Experience', 'foliocraft' ),
					'singular_name' => __( 'Experience', 'foliocraft' ),
					'add_new_item'  => __( 'Add New Entry', 'foliocraft' ),
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
