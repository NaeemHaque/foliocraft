<?php defined( 'ABSPATH' ) || exit;
$hero     = isset( $profile['hero'] ) ? $profile['hero'] : \FolioCraft\Models\Profile::all()['hero'];
$resume   = isset( $profile['resume'] ) ? $profile['resume'] : \FolioCraft\Models\Profile::all()['resume'];
$headshot = isset( $profile['headshot'] ) ? $profile['headshot'] : \FolioCraft\Models\Profile::all()['headshot'];
?>

<!-- ============ HERO ============ -->
<section class="hero wrap" id="hero">
  <div class="hero-grid">
    <div class="hero-text">
      <?php if ( ! foliocraft_blank( $hero['status'] ) ) : ?>
      <span class="hero-status" data-reveal><span class="dot"></span> <?php echo esc_html( $hero['status'] ); ?></span>
      <?php endif; ?>
      <?php if ( ! foliocraft_blank( $hero['name_line1'] ) || ! foliocraft_blank( $hero['name_line2'] ) ) : ?>
      <h1 data-reveal data-delay="1"><?php
      echo esc_html( $hero['name_line1'] );
      if ( ! foliocraft_blank( $hero['name_line2'] ) ) :
        ?><br /><span class="accent"><?php echo esc_html( $hero['name_line2'] ); ?></span><?php
      endif; ?></h1>
      <?php endif; ?>
      <?php if ( ! empty( $hero['roles'] ) ) : ?>
      <p class="hero-role" data-reveal data-delay="2"><span id="typed"></span><span class="cursor"></span></p>
      <?php endif; ?>
      <?php if ( ! foliocraft_blank( $hero['lead'] ) ) : ?>
      <p class="hero-lead" data-reveal data-delay="3"><?php echo wp_kses_post( $hero['lead'] ); ?></p>
      <?php endif; ?>
      <?php
      $fc_primary = ! foliocraft_blank( $hero['cta_primary_label'] );
      $fc_resume  = ! empty( $resume['url'] );
      if ( $fc_primary || $fc_resume ) :
      ?>
      <div class="hero-cta" data-reveal data-delay="4">
        <?php if ( $fc_primary ) : ?>
        <a href="<?php echo esc_url( $hero['cta_primary_url'] ); ?>" class="btn btn--primary">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 7h16M4 12h16M4 17h10"/></svg>
          <?php echo esc_html( $hero['cta_primary_label'] ); ?>
        </a>
        <?php endif; ?>
        <?php if ( $fc_resume ) : ?>
        <a href="<?php echo esc_url( $resume['url'] ); ?>" class="btn" target="_blank" rel="noopener">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
          <?php echo esc_html( $hero['cta_resume_label'] ); ?>
        </a>
        <?php endif; ?>
      </div>
      <?php endif; ?>
      <?php
      $fc_stats = array_filter(
        $hero['stats'],
        static function ( $s ) {
          return ! foliocraft_blank( $s['num'] ) || ! foliocraft_blank( $s['label'] );
        }
      );
      if ( $fc_stats ) :
      ?>
      <div class="hero-meta" data-reveal data-delay="4">
        <?php
        foreach ( $fc_stats as $stat ) :
          $fc_digits = preg_replace( '/[^0-9]/', '', (string) $stat['num'] );
          ?>
        <div class="stat">
          <div class="n"<?php echo '' !== $fc_digits ? ' data-count="' . esc_attr( $fc_digits ) . '"' : ''; ?>><?php echo esc_html( $stat['num'] ); ?></div>
          <?php if ( ! foliocraft_blank( $stat['label'] ) ) : ?><div class="l"><?php echo esc_html( $stat['label'] ); ?></div><?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php endif; ?>
    </div>

    <div class="hero-aside" data-reveal data-delay="2" style="position:relative;">
      <div class="terminal" aria-hidden="true">
        <div class="terminal-bar">
          <span class="dot"></span><span class="dot"></span><span class="dot"></span>
          <span class="file">~/foliocraft/profile.php</span>
        </div>
        <div class="terminal-body"><span class="t-comment">// who am i</span>
<span class="t-key">class</span> <span class="t-fn">Engineer</span> {
  <span class="t-key">public</span> <span class="t-prop">$role</span>      = <span class="t-str">'Software Engineer'</span>;
  <span class="t-key">public</span> <span class="t-prop">$company</span>   = <span class="t-str">'Acme Inc.'</span>;
  <span class="t-key">public</span> <span class="t-prop">$stack</span>     = [<span class="t-str">'PHP'</span>, <span class="t-str">'Laravel'</span>, <span class="t-str">'Vue'</span>, <span class="t-str">'WP'</span>];
  <span class="t-key">public</span> <span class="t-prop">$openSource</span> = <span class="t-key">true</span>;

  <span class="t-key">public function</span> <span class="t-fn">build</span>() {
    <span class="t-key">return</span> <span class="t-str">'clean architecture + ship'</span>;
  }
}</div>
      </div>
      <?php if ( ! empty( $headshot['url'] ) ) : ?>
      <img class="headshot-slot" src="<?php echo esc_url( $headshot['url'] ); ?>" alt="<?php echo esc_attr( $profile['identity']['name'] ); ?>" loading="lazy" decoding="async" />
      <?php endif; ?>
    </div>
  </div>
  <div class="scroll-cue" aria-hidden="true"><span>scroll</span><span class="line"></span></div>
</section>
