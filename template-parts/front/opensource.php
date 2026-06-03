<?php defined( 'ABSPATH' ) || exit;
// Phase 3: Customizer-driven.
?>

<section class="section os" id="opensource">
	<div class="wrap">
		<p class="eyebrow" data-reveal><span class="num">04 /</span> <?php esc_html_e( 'Open Source', 'naeem-portfolio' ); ?></p>
		<div class="os-grid">
			<div data-reveal data-delay="1">
				<h2 class="section-title"><?php esc_html_e( 'Building the web in the open.', 'naeem-portfolio' ); ?></h2>
				<p class="section-lead"><?php esc_html_e( 'Contributing to the WordPress Open Source Project across five focus areas, plus EmDash CMS — fixing issues, shipping patches, and translating for a global community.', 'naeem-portfolio' ); ?></p>
				<div class="os-areas">
					<span class="os-area"><span class="tick">✓</span> <?php esc_html_e( 'Core', 'naeem-portfolio' ); ?></span>
					<span class="os-area"><span class="tick">✓</span> <?php esc_html_e( 'Plugins', 'naeem-portfolio' ); ?></span>
					<span class="os-area"><span class="tick">✓</span> <?php esc_html_e( 'Meta', 'naeem-portfolio' ); ?></span>
					<span class="os-area"><span class="tick">✓</span> <?php esc_html_e( 'Polyglots', 'naeem-portfolio' ); ?></span>
					<span class="os-area"><span class="tick">✓</span> <?php esc_html_e( 'Photos', 'naeem-portfolio' ); ?></span>
					<span class="os-area"><span class="tick">✓</span> <?php esc_html_e( 'EmDash CMS', 'naeem-portfolio' ); ?></span>
				</div>
			</div>
			<div class="os-cards" data-reveal data-delay="2">
				<?php
				$os_cards = array(
					array(
						'glyph' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9.5"/><path d="M3 12h18M12 3c2.5 2.5 3.8 5.8 3.8 9s-1.3 6.5-3.8 9c-2.5-2.5-3.8-5.8-3.8-9S9.5 5.5 12 3z"/></svg>',
						'title' => __( 'WP Core', 'naeem-portfolio' ),
						'role'  => __( 'Patches & triage', 'naeem-portfolio' ),
						'blurb' => __( 'Bug fixes and issue triage on the platform that powers a huge share of the web.', 'naeem-portfolio' ),
					),
					array(
						'glyph' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 7h18v10a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><path d="M3 7l3-3h12l3 3M9 11h6"/></svg>',
						'title' => __( 'Polyglots', 'naeem-portfolio' ),
						'role'  => __( 'Translation', 'naeem-portfolio' ),
						'blurb' => __( 'Translating WordPress so it speaks more languages — i18n done the right way.', 'naeem-portfolio' ),
					),
					array(
						'glyph' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="14" rx="2"/><circle cx="9" cy="10" r="2"/><path d="m21 16-5-5-7 7"/></svg>',
						'title' => __( 'Photos & Meta', 'naeem-portfolio' ),
						'role'  => __( 'Community', 'naeem-portfolio' ),
						'blurb' => __( 'Contributing to the Photo Directory and Meta tooling that keeps the project running.', 'naeem-portfolio' ),
					),
					array(
						'glyph' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h7v7H4zM13 4h7v7h-7zM4 13h7v7H4zM13 13h7v7h-7z"/></svg>',
						'title' => __( 'EmDash CMS', 'naeem-portfolio' ),
						'role'  => __( 'Contributor', 'naeem-portfolio' ),
						'blurb' => __( 'Helping build a modern, developer-friendly CMS out in the open.', 'naeem-portfolio' ),
					),
				);
				foreach ( $os_cards as $os_card ) {
					\Naeem\Core\View::render( 'cards/os-card', $os_card );
				}
				?>
			</div>
		</div>
	</div>
</section>
