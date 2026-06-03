<?php defined( 'ABSPATH' ) || exit; ?>
<div class="os-card">
	<div class="head">
		<div class="glyph"><?php echo $glyph; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG ?></div>
		<div class="ot"><h4><?php echo esc_html( $title ); ?></h4><div class="role"><?php echo esc_html( $role ); ?></div></div>
	</div>
	<p><?php echo esc_html( $blurb ); ?></p>
</div>
