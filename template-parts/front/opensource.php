<?php
defined( 'ABSPATH' ) || exit;
// Phase 3: Customizer-driven.
$os = isset( $profile['opensource'] ) ? $profile['opensource'] : \FolioCraft\Models\Profile::all()['opensource'];
?>

<section class="section os" id="opensource">
	<div class="wrap">
		<?php foliocraft_eyebrow( 'opensource' ); ?>
		<div class="os-grid">
			<div data-reveal data-delay="1">
				<?php if ( ! foliocraft_blank( $os['heading'] ) ) : ?>
				<h2 class="section-title"><?php echo esc_html( $os['heading'] ); ?></h2>
				<?php endif; ?>
				<?php if ( ! foliocraft_blank( $os['lead'] ) ) : ?>
				<p class="section-lead"><?php echo esc_html( $os['lead'] ); ?></p>
				<?php endif; ?>
				<?php if ( ! empty( $os['areas'] ) ) : ?>
				<div class="os-areas">
					<?php foreach ( $os['areas'] as $area ) : ?>
						<span class="os-area"><span class="tick">✓</span> <?php echo esc_html( $area ); ?></span>
					<?php endforeach; ?>
				</div>
				<?php endif; ?>
			</div>
			<div class="os-cards" data-reveal data-delay="2">
				<?php
				$os_glyphs = array(
					'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9.5"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.8 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.8-3.8-9S9.5 5.5 12 3z"/></svg>',
					'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M3 7l3-3h12l3 3M9 11h6"/></svg>',
					'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m21 16-5-5-7 7"/></svg>',
					'<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>',
				);
				foreach ( $os['cards'] as $i => $card ) :
					if ( foliocraft_blank( $card['title'] ) && foliocraft_blank( $card['role'] ) && foliocraft_blank( $card['blurb'] ) ) {
						continue;
					}
					?>
					<div class="os-card">
						<div class="head">
							<div class="glyph"><?php echo $os_glyphs[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG ?></div>
							<div class="ot"><?php if ( ! foliocraft_blank( $card['title'] ) ) : ?><h4><?php echo esc_html( $card['title'] ); ?></h4><?php endif; ?><?php if ( ! foliocraft_blank( $card['role'] ) ) : ?><div class="role"><?php echo esc_html( $card['role'] ); ?></div><?php endif; ?></div>
						</div>
						<?php if ( ! foliocraft_blank( $card['blurb'] ) ) : ?><p><?php echo esc_html( $card['blurb'] ); ?></p><?php endif; ?>
					</div>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
</section>
