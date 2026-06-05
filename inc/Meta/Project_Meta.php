<?php
/**
 * Project meta box: live URL, GitHub URL, stars, language.
 *
 * @package FolioCraft
 */

namespace FolioCraft\Meta;

defined( 'ABSPATH' ) || exit;

class Project_Meta {

	const FIELDS = array(
		'_foliocraft_live_url'   => 'url',
		'_foliocraft_github_url' => 'url',
		'_foliocraft_stars'      => 'text',
		'_foliocraft_lang'       => 'text',
	);

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_box' ) );
		add_action( 'save_post_project', array( __CLASS__, 'save' ) );
	}

	public static function register_meta() {
		foreach ( self::FIELDS as $key => $type ) {
			register_post_meta(
				'project',
				$key,
				array(
					'type'              => 'string',
					'single'            => true,
					'show_in_rest'      => true,
					'sanitize_callback' => 'url' === $type ? 'esc_url_raw' : 'sanitize_text_field',
					'auth_callback'     => function () {
						return current_user_can( 'edit_posts' );
					},
				)
			);
		}
	}

	public static function add_box() {
		add_meta_box(
			'foliocraft_project_details',
			__( 'Project Details', 'foliocraft' ),
			array( __CLASS__, 'box' ),
			'project',
			'side'
		);
	}

	public static function box( $post ) {
		wp_nonce_field( 'foliocraft_project_save', 'foliocraft_project_nonce' );
		$labels = array(
			'_foliocraft_live_url'   => array( __( 'Live URL', 'foliocraft' ), 'url' ),
			'_foliocraft_github_url' => array( __( 'GitHub URL', 'foliocraft' ), 'url' ),
			'_foliocraft_stars'      => array( __( 'Stars', 'foliocraft' ), 'text' ),
			'_foliocraft_lang'       => array( __( 'Language', 'foliocraft' ), 'text' ),
		);
		foreach ( $labels as $key => $meta ) {
			$val = get_post_meta( $post->ID, $key, true );
			printf(
				'<p><label for="%1$s" style="display:block;font-weight:600;margin-bottom:4px;">%2$s</label><input type="%3$s" id="%1$s" name="%1$s" value="%4$s" style="width:100%%;" /></p>',
				esc_attr( $key ),
				esc_html( $meta[0] ),
				esc_attr( $meta[1] ),
				esc_attr( $val )
			);
		}
	}

	public static function save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['foliocraft_project_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['foliocraft_project_nonce'] ) ), 'foliocraft_project_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		foreach ( self::FIELDS as $key => $type ) {
			if ( ! isset( $_POST[ $key ] ) ) {
				continue;
			}
			$raw = wp_unslash( $_POST[ $key ] );
			$val = 'url' === $type ? esc_url_raw( $raw ) : sanitize_text_field( $raw );
			update_post_meta( $post_id, $key, $val );
		}
	}
}
