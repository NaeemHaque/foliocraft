<?php
/**
 * Experience meta box: company, date range, current-role flag.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Meta;

defined( 'ABSPATH' ) || exit;

class Experience_Meta {

	public static function init() {
		add_action( 'init', array( __CLASS__, 'register_meta' ) );
		add_action( 'add_meta_boxes', array( __CLASS__, 'add_box' ) );
		add_action( 'save_post_experience', array( __CLASS__, 'save' ) );
	}

	public static function register_meta() {
		$auth = function () {
			return current_user_can( 'edit_posts' );
		};
		register_post_meta( 'experience', '_naeem_company', array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => $auth ) );
		register_post_meta( 'experience', '_naeem_date_range', array( 'type' => 'string', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => 'sanitize_text_field', 'auth_callback' => $auth ) );
		register_post_meta( 'experience', '_naeem_is_current', array( 'type' => 'boolean', 'single' => true, 'show_in_rest' => true, 'sanitize_callback' => function ( $v ) { return $v ? 1 : 0; }, 'auth_callback' => $auth ) );
	}

	public static function add_box() {
		add_meta_box( 'naeem_experience_details', __( 'Experience Details', 'naeem-portfolio' ), array( __CLASS__, 'box' ), 'experience', 'side' );
	}

	public static function box( $post ) {
		wp_nonce_field( 'naeem_experience_save', 'naeem_experience_nonce' );
		$company = get_post_meta( $post->ID, '_naeem_company', true );
		$range   = get_post_meta( $post->ID, '_naeem_date_range', true );
		$current = get_post_meta( $post->ID, '_naeem_is_current', true );
		printf(
			'<p><label for="_naeem_company" style="display:block;font-weight:600;margin-bottom:4px;">%1$s</label><input type="text" id="_naeem_company" name="_naeem_company" value="%2$s" style="width:100%%;" /></p>',
			esc_html__( 'Company', 'naeem-portfolio' ),
			esc_attr( $company )
		);
		printf(
			'<p><label for="_naeem_date_range" style="display:block;font-weight:600;margin-bottom:4px;">%1$s</label><input type="text" id="_naeem_date_range" name="_naeem_date_range" value="%2$s" placeholder="2022 — Present" style="width:100%%;" /></p>',
			esc_html__( 'Date range', 'naeem-portfolio' ),
			esc_attr( $range )
		);
		printf(
			'<p><label><input type="checkbox" name="_naeem_is_current" value="1" %1$s /> %2$s</label></p>',
			checked( (bool) $current, true, false ),
			esc_html__( 'Current role (featured)', 'naeem-portfolio' )
		);
	}

	public static function save( $post_id ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}
		if ( ! isset( $_POST['naeem_experience_nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['naeem_experience_nonce'] ) ), 'naeem_experience_save' ) ) {
			return;
		}
		if ( ! current_user_can( 'edit_post', $post_id ) ) {
			return;
		}
		update_post_meta( $post_id, '_naeem_company', sanitize_text_field( wp_unslash( isset( $_POST['_naeem_company'] ) ? $_POST['_naeem_company'] : '' ) ) );
		update_post_meta( $post_id, '_naeem_date_range', sanitize_text_field( wp_unslash( isset( $_POST['_naeem_date_range'] ) ? $_POST['_naeem_date_range'] : '' ) ) );
		update_post_meta( $post_id, '_naeem_is_current', isset( $_POST['_naeem_is_current'] ) ? 1 : 0 );
	}
}
