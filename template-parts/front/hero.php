<?php defined( 'ABSPATH' ) || exit; ?>

<!-- ============ HERO ============ -->
<section class="hero wrap" id="hero">
  <div class="hero-grid">
    <div class="hero-text">
      <span class="hero-status" data-reveal><span class="dot"></span> <?php esc_html_e( 'Open to interesting open-source & product work', 'naeem-portfolio' ); ?></span>
      <h1 data-reveal data-delay="1">Golam Sarwer<br /><span class="accent">Naeem</span></h1>
      <p class="hero-role" data-reveal data-delay="2"><span id="typed"></span><span class="cursor"></span></p>
      <p class="hero-lead" data-reveal data-delay="3"><?php echo wp_kses_post( __( 'I build scalable products, developer tools, and web apps — and contribute to the open-source projects that power the web. Currently engineering WordPress products at <strong style="color:var(--text)">WPManageNinja</strong>, used by millions worldwide.', 'naeem-portfolio' ) ); ?></p>
      <div class="hero-cta" data-reveal data-delay="4">
        <a href="#work" class="btn btn--primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
          <?php esc_html_e( 'View Projects', 'naeem-portfolio' ); ?>
        </a>
        <a href="#" class="btn" id="resumeHero">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          <?php esc_html_e( 'Download Résumé', 'naeem-portfolio' ); ?>
        </a>
      </div>
      <div class="hero-meta" data-reveal data-delay="4">
        <div class="stat"><div class="n" data-count="5">5+</div><div class="l"><?php esc_html_e( 'Years building', 'naeem-portfolio' ); ?></div></div>
        <div class="stat"><div class="n" data-count="6">6</div><div class="l"><?php esc_html_e( 'WP focus areas', 'naeem-portfolio' ); ?></div></div>
        <div class="stat"><div class="n">M+</div><div class="l"><?php esc_html_e( 'Users reached', 'naeem-portfolio' ); ?></div></div>
      </div>
    </div>

    <div class="hero-aside" data-reveal data-delay="2" style="position:relative;">
      <div class="terminal" aria-hidden="true">
        <div class="terminal-bar">
          <span class="dot"></span><span class="dot"></span><span class="dot"></span>
          <span class="file">~/naeem/profile.php</span>
        </div>
        <div class="terminal-body"><span class="t-comment">// who am i</span>
<span class="t-key">class</span> <span class="t-fn">Engineer</span> {
  <span class="t-key">public</span> <span class="t-prop">$role</span>      = <span class="t-str">'Software Engineer'</span>;
  <span class="t-key">public</span> <span class="t-prop">$company</span>   = <span class="t-str">'WPManageNinja'</span>;
  <span class="t-key">public</span> <span class="t-prop">$stack</span>     = [<span class="t-str">'PHP'</span>, <span class="t-str">'Laravel'</span>, <span class="t-str">'Vue'</span>, <span class="t-str">'WP'</span>];
  <span class="t-key">public</span> <span class="t-prop">$openSource</span> = <span class="t-key">true</span>;

  <span class="t-key">public function</span> <span class="t-fn">build</span>() {
    <span class="t-key">return</span> <span class="t-str">'clean architecture + ship'</span>;
  }
}</div>
      </div>
      <div class="headshot-slot" aria-hidden="true"></div>
    </div>
  </div>
  <div class="scroll-cue" aria-hidden="true"><span>scroll</span><span class="line"></span></div>
</section>
