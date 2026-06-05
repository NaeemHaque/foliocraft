<?php defined( 'ABSPATH' ) || exit; ?>
<a href="<?php echo esc_url( $url ); ?>" class="post" data-reveal>
	<div class="pmeta"><span class="cat"><?php echo esc_html( $cat ); ?></span><span>·</span><span><?php echo esc_html( $read ); ?></span></div>
	<h3><?php echo esc_html( $title ); ?></h3>
	<p><?php echo esc_html( $excerpt ); ?></p>
	<span class="more"><?php esc_html_e( 'Read post', 'foliocraft' ); ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
</a>
