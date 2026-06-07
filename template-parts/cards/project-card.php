<?php defined( 'ABSPATH' ) || exit;

$icon_blocks = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG
$icon_ext    ='<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg>';
$icon_github = '<svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.58 2 12.25c0 4.53 2.87 8.37 6.84 9.73.5.1.68-.22.68-.49v-1.7c-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.5-1.11-1.5-.91-.64.07-.62.07-.62 1 .07 1.53 1.06 1.53 1.06.89 1.57 2.34 1.12 2.91.86.09-.66.35-1.12.63-1.38-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.3.1-2.71 0 0 .84-.28 2.75 1.05a9.3 9.3 0 0 1 5 0c1.91-1.33 2.75-1.05 2.75-1.05.55 1.41.2 2.45.1 2.71.64.72 1.03 1.63 1.03 2.75 0 3.94-2.34 4.81-4.57 5.06.36.32.68.94.68 1.9v2.82c0 .27.18.6.69.49A10.26 10.26 0 0 0 22 12.25C22 6.58 17.52 2 12 2z"/></svg>';
$icon_star   = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 3 2.7 5.6 6.1.9-4.4 4.3 1 6.1L12 17.8 6.6 20l1-6.1L3.2 9.5l6.1-.9z"/></svg>';
$icon_dot    = '<svg viewBox="0 0 24 24" fill="currentColor"><circle cx="12" cy="12" r="6"/></svg>';
?>
<article <?php post_class( 'proj-card' ); ?> data-filters="<?php echo esc_attr( implode( ' ', $filters ) ); ?>">
	<div class="proj-top">
		<div class="proj-icon"><?php
			if ( ! empty( $thumb_id ) ) {
				echo wp_get_attachment_image( $thumb_id, 'foliocraft-project', false, array( 'alt' => '' ) );
			} else {
				echo $icon_blocks; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG
			}
		?></div>
		<div class="proj-links">
			<a href="<?php echo ! empty( $live_url ) ? esc_url( $live_url ) : '#'; ?>"<?php echo ! empty( $live_url ) ? ' target="_blank" rel="noopener"' : ''; ?> aria-label="<?php esc_attr_e( 'Live site', 'foliocraft' ); ?>" title="<?php esc_attr_e( 'Live site', 'foliocraft' ); ?>"><?php echo $icon_ext; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG ?></a>
			<a href="<?php echo ! empty( $github_url ) ? esc_url( $github_url ) : '#'; ?>"<?php echo ! empty( $github_url ) ? ' target="_blank" rel="noopener"' : ''; ?> aria-label="<?php esc_attr_e( 'GitHub repo', 'foliocraft' ); ?>" title="<?php esc_attr_e( 'GitHub', 'foliocraft' ); ?>"><?php echo $icon_github; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG ?></a>
		</div>
	</div>
	<h3><?php echo esc_html( $name ); ?></h3>
	<p class="desc"><?php echo esc_html( $desc ); ?></p>
	<div class="proj-tags"><?php foreach ( $tags as $t ) : ?><span class="chip"><?php echo esc_html( $t ); ?></span><?php endforeach; ?></div>
	<?php if ( ! empty( $stars ) || ! empty( $lang ) ) : ?>
	<div class="proj-meta">
		<?php if ( ! empty( $stars ) ) : ?><span><?php echo $icon_star; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $stars ); ?></span><?php endif; ?>
		<?php if ( ! empty( $lang ) ) : ?><span><?php echo $icon_dot; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?><?php echo esc_html( $lang ); ?></span><?php endif; ?>
	</div>
	<?php endif; ?>
</article>
