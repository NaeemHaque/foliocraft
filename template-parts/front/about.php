<?php defined( 'ABSPATH' ) || exit; ?>
<?php // Phase 3: this copy becomes Customizer-driven. ?>
<?php $about = isset( $profile['about'] ) ? $profile['about'] : \FolioCraft\Models\Profile::all()['about']; ?>

<!-- ============ ABOUT ============ -->
<section class="section" id="about">
  <div class="wrap">
    <p class="eyebrow" data-reveal><span class="num">01 /</span> <?php esc_html_e( 'About', 'foliocraft' ); ?></p>
    <div class="about-grid">
      <div class="about-body" data-reveal data-delay="1">
        <h2 class="section-title"><?php echo esc_html( $about['heading'] ); ?></h2>
        <p><?php echo wp_kses_post( $about['paragraphs'][0] ); ?></p>
        <p><?php echo wp_kses_post( $about['paragraphs'][1] ); ?></p>
      </div>
      <div class="about-aside" data-reveal data-delay="2">
        <?php
        $pillar_icons = array(
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4M6 8l-4 4 4 4M14.5 4l-5 16"/></svg>',
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>',
          '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4m0 0H8m4 0h4M5 12a7 7 0 1 0 14 0 7 7 0 0 0-14 0z"/><path d="m12 12 3-2"/></svg>',
        );
        foreach ( $about['pillars'] as $i => $pillar ) : ?>
        <div class="pillar">
          <div class="ico"><?php echo $pillar_icons[ $i ]; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- trusted static SVG ?></div>
          <div class="pillar-body">
            <h3><?php echo esc_html( $pillar['title'] ); ?></h3>
            <p><?php echo esc_html( $pillar['desc'] ); ?></p>
          </div>
        </div>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</section>
