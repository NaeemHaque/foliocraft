<?php defined( 'ABSPATH' ) || exit;
// Phase 3: Customizer-driven.
?>
<?php $skills = isset( $profile['skills'] ) ? $profile['skills'] : \FolioCraft\Models\Profile::all()['skills']; ?>

<section class="section" id="stack">
	<div class="wrap">
		<?php foliocraft_eyebrow( 'stack' ); ?>
		<?php if ( ! foliocraft_blank( $profile['titles']['stack']['heading'] ) ) : ?><h2 class="section-title" data-reveal data-delay="1"><?php echo esc_html( $profile['titles']['stack']['heading'] ); ?></h2><?php endif; ?>
		<div class="skill-grid">
			<?php
			$group_icons = array(
				'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="6" rx="8" ry="3"/><path d="M4 6v6c0 1.7 3.6 3 8 3s8-1.3 8-3V6M4 12v6c0 1.7 3.6 3 8 3s8-1.3 8-3v-6"/></svg>',
				'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><path d="M3 9h18M8 21h8"/></svg>',
				'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="8" ry="3"/><path d="M4 5v14c0 1.7 3.6 3 8 3s8-1.3 8-3V5"/></svg>',
				'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2v3M12 19v3M2 12h3M19 12h3M5 5l2 2M17 17l2 2M19 5l-2 2M7 17l-2 2"/><circle cx="12" cy="12" r="3.5"/></svg>',
			);
			foreach ( $skills['groups'] as $i => $group ) :
				if ( foliocraft_blank( $group['label'] ) && empty( $group['pills'] ) ) {
					continue;
				}
				?>
			<div class="skill-group" data-reveal data-delay="<?php echo (int) ( $i + 1 ); ?>">
				<div class="gh"><div class="ico"><?php echo $group_icons[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG ?></div><?php if ( ! foliocraft_blank( $group['label'] ) ) : ?><h3><?php echo esc_html( $group['label'] ); ?></h3><?php endif; ?></div>
				<?php if ( ! empty( $group['pills'] ) ) : ?><div class="skill-list"><?php foreach ( $group['pills'] as $pill ) : ?><span class="skill-pill"><?php echo esc_html( $pill ); ?></span><?php endforeach; ?></div><?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</div>
</section>
