<?php defined( 'ABSPATH' ) || exit; ?>
<?php // Phase 3: this copy becomes Customizer-driven. ?>

<!-- ============ ABOUT ============ -->
<section class="section" id="about">
  <div class="wrap">
    <p class="eyebrow" data-reveal><span class="num">01 /</span> <?php esc_html_e( 'About', 'naeem-portfolio' ); ?></p>
    <div class="about-grid">
      <div class="about-body" data-reveal data-delay="1">
        <h2 class="section-title"><?php esc_html_e( 'Engineering for scale, contributing in the open.', 'naeem-portfolio' ); ?></h2>
        <p><?php echo wp_kses_post( __( "I'm a software engineer and open-source contributor focused on building <strong>scalable products, developer tools, and web apps</strong>. At WPManageNinja, I work on WordPress product engineering, shipping software trusted by millions of users and businesses worldwide.", 'naeem-portfolio' ) ); ?></p>
        <p><?php echo wp_kses_post( __( 'I contribute to the <strong>WordPress Open Source Project</strong> (Core, Plugins, Meta, Polyglots, Photos) and <strong>EmDash CMS</strong>. AI is now a core part of my workflow for research, planning, and debugging; the rest of my time goes to backend systems with a focus on architecture and clean code. I studied CSE at <strong>Sylhet International University</strong>, where programming contests shaped how I think and solve problems.', 'naeem-portfolio' ) ); ?></p>
      </div>
      <div class="about-aside" data-reveal data-delay="2">
        <div class="pillar">
          <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m18 16 4-4-4-4M6 8l-4 4 4 4M14.5 4l-5 16"/></svg></div>
          <div class="pillar-body">
            <h3><?php esc_html_e( 'Clean architecture', 'naeem-portfolio' ); ?></h3>
            <p><?php esc_html_e( 'Maintainable, idiomatic code with structure that scales with the team and the product.', 'naeem-portfolio' ); ?></p>
          </div>
        </div>
        <div class="pillar">
          <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2 2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg></div>
          <div class="pillar-body">
            <h3><?php esc_html_e( 'Open by default', 'naeem-portfolio' ); ?></h3>
            <p><?php esc_html_e( 'Contributing upstream — issues, patches, translations — to the tools the web runs on.', 'naeem-portfolio' ); ?></p>
          </div>
        </div>
        <div class="pillar">
          <div class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 8V4m0 0H8m4 0h4M5 12a7 7 0 1 0 14 0 7 7 0 0 0-14 0z"/><path d="m12 12 3-2"/></svg></div>
          <div class="pillar-body">
            <h3><?php esc_html_e( 'Contest-grade detail', 'naeem-portfolio' ); ?></h3>
            <p><?php esc_html_e( 'A competitive-programming background — fast under pressure, precise on the edge cases.', 'naeem-portfolio' ); ?></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
