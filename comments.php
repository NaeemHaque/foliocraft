<?php
/**
 * Comments template.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;

// Don't load comments for password-protected posts until the password is entered.
if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="comments wrap">
	<?php if ( have_comments() ) : ?>
		<h2 class="comments-title">
			<?php
			$foliocraft_comment_count = get_comments_number();
			printf(
				/* translators: %s: comment count number. */
				esc_html( _n( '%s comment', '%s comments', $foliocraft_comment_count, 'foliocraft' ) ),
				esc_html( number_format_i18n( $foliocraft_comment_count ) )
			);
			?>
		</h2>

		<ol class="comment-list">
			<?php
			wp_list_comments(
				array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 40,
				)
			);
			?>
		</ol>

		<?php
		the_comments_navigation();

		if ( ! comments_open() ) :
			?>
			<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'foliocraft' ); ?></p>
			<?php
		endif;
	endif;

	comment_form();
	?>
</section>
