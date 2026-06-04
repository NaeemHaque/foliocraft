<?php
/**
 * Native contact form handler (admin-post), nonce + sanitize + wp_mail.
 *
 * @package Naeem_Portfolio
 */

namespace Naeem\Forms;

defined( 'ABSPATH' ) || exit;

class Contact {

	const ACTION = 'naeem_contact';

	public static function init() {
		add_action( 'admin_post_' . self::ACTION, array( __CLASS__, 'handle' ) );
		add_action( 'admin_post_nopriv_' . self::ACTION, array( __CLASS__, 'handle' ) );
	}

	public static function handle() {
		$ajax  = ! empty( $_POST['naeem_ajax'] );
		$nonce = isset( $_POST['naeem_contact_nonce'] ) ? sanitize_text_field( wp_unslash( $_POST['naeem_contact_nonce'] ) ) : '';

		if ( ! wp_verify_nonce( $nonce, self::ACTION ) ) {
			self::respond( $ajax, false, __( 'Security check failed — please refresh and try again.', 'naeem-portfolio' ) );
		}

		// Honeypot: a filled hidden field means a bot. Pretend success, send nothing.
		if ( ! empty( $_POST['naeem_website'] ) ) {
			self::respond( $ajax, true, __( 'Thanks — your message is on its way.', 'naeem-portfolio' ) );
		}

		$name    = isset( $_POST['name'] ) ? sanitize_text_field( wp_unslash( $_POST['name'] ) ) : '';
		$email   = isset( $_POST['email'] ) ? sanitize_email( wp_unslash( $_POST['email'] ) ) : '';
		$message = isset( $_POST['message'] ) ? sanitize_textarea_field( wp_unslash( $_POST['message'] ) ) : '';

		if ( '' === $name || ! is_email( $email ) || strlen( $message ) < 10 ) {
			self::respond( $ajax, false, __( 'Please add your name, a valid email, and a longer message.', 'naeem-portfolio' ) );
		}

		$to = get_option( 'admin_email' );
		/* translators: %s: sender name. */
		$subject = sprintf( __( 'Portfolio contact from %s', 'naeem-portfolio' ), $name );
		$body    = sprintf( "Name: %s\nEmail: %s\n\n%s", $name, $email, $message );
		$headers = array( 'Reply-To: ' . $name . ' <' . $email . '>' );
		$sent    = wp_mail( $to, $subject, $body, $headers );

		self::respond(
			$ajax,
			(bool) $sent,
			$sent
				? __( "Thanks — your message is on its way. I'll get back to you soon.", 'naeem-portfolio' )
				: __( 'Sorry, something went wrong sending your message. Email me directly instead.', 'naeem-portfolio' )
		);
	}

	private static function respond( $ajax, $ok, $message ) {
		if ( $ajax ) {
			if ( $ok ) {
				wp_send_json_success( array( 'message' => $message ) );
			}
			wp_send_json_error( array( 'message' => $message ) );
		}
		$base = wp_get_referer() ? wp_get_referer() : home_url( '/' );
		wp_safe_redirect( add_query_arg( 'contact', $ok ? 'sent' : 'error', $base ) . '#contact' );
		exit;
	}
}
