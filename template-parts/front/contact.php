<?php defined( 'ABSPATH' ) || exit; ?>
<?php // Contact copy + channels are Customizer-driven. The form slot renders a user-supplied form-plugin shortcode (e.g. Fluent Forms), falling back to an email CTA. ?>
<?php
$contact     = isset( $profile['contact'] ) ? $profile['contact'] : \FolioCraft\Models\Profile::all()['contact'];
$social      = isset( $profile['social'] ) ? $profile['social'] : \FolioCraft\Models\Profile::all()['social'];
$fc_channels = ! foliocraft_blank( $social['email'] ) || ! foliocraft_blank( $social['github'] ) || ! foliocraft_blank( $social['linkedin'] ) || ! foliocraft_blank( $social['x'] );
?>
<!-- ============ CONTACT ============ -->
<section class="section contact" id="contact">
  <div class="wrap">
    <?php foliocraft_eyebrow( 'contact' ); ?>
    <div class="contact-grid">
      <div class="contact-left" data-reveal data-delay="1">
        <?php if ( ! foliocraft_blank( $contact['heading'] ) ) : ?>
        <h2 class="section-title"><?php echo esc_html( $contact['heading'] ); ?></h2>
        <?php endif; ?>
        <?php if ( ! foliocraft_blank( $contact['lead'] ) ) : ?>
        <p class="section-lead"><?php echo esc_html( $contact['lead'] ); ?></p>
        <?php endif; ?>
        <?php if ( $fc_channels ) : ?>
        <div class="contact-channels">
          <span class="ch-label"><?php esc_html_e( 'Direct lines', 'foliocraft' ); ?></span>
          <?php if ( ! foliocraft_blank( $social['email'] ) ) : ?>
          <a class="channel" href="<?php echo esc_url( 'mailto:' . $social['email'] ); ?>">
            <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'Email', 'foliocraft' ); ?></span><span class="v"><?php echo esc_html( $social['email'] ); ?></span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <?php endif; ?>
          <?php if ( ! foliocraft_blank( $social['github'] ) ) : ?>
          <a class="channel" href="<?php echo esc_url( $social['github'] ); ?>" target="_blank" rel="noopener">
            <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.58 2 12.25c0 4.53 2.87 8.37 6.84 9.73.5.1.68-.22.68-.49v-1.7c-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.5-1.11-1.5-.91-.64.07-.62.07-.62 1 .07 1.53 1.06 1.53 1.06.89 1.57 2.34 1.12 2.91.86.09-.66.35-1.12.63-1.38-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.3.1-2.71 0 0 .84-.28 2.75 1.05a9.3 9.3 0 0 1 5 0c1.91-1.33 2.75-1.05 2.75-1.05.55 1.41.2 2.45.1 2.71.64.72 1.03 1.63 1.03 2.75 0 3.94-2.34 4.81-4.57 5.06.36.32.68.94.68 1.9v2.82c0 .27.18.6.69.49A10.26 10.26 0 0 0 22 12.25C22 6.58 17.52 2 12 2z"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'GitHub', 'foliocraft' ); ?></span><span class="v"><?php echo esc_html( foliocraft_url_label( $social['github'] ) ); ?></span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <?php endif; ?>
          <?php if ( ! foliocraft_blank( $social['linkedin'] ) ) : ?>
          <a class="channel" href="<?php echo esc_url( $social['linkedin'] ); ?>" target="_blank" rel="noopener">
            <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'LinkedIn', 'foliocraft' ); ?></span><span class="v"><?php echo esc_html( foliocraft_url_label( $social['linkedin'] ) ); ?></span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <?php endif; ?>
          <?php if ( ! foliocraft_blank( $social['x'] ) ) : ?>
          <a class="channel" href="<?php echo esc_url( $social['x'] ); ?>" target="_blank" rel="noopener">
            <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'X', 'foliocraft' ); ?></span><span class="v"><?php echo esc_html( foliocraft_url_label( $social['x'] ) ); ?></span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <?php endif; ?>
        </div>
        <?php endif; ?>
      </div>

      <?php if ( ! empty( $contact['fluent_shortcode'] ) ) : ?>
      <div class="form" data-reveal data-delay="2"><?php echo do_shortcode( $contact['fluent_shortcode'] ); ?></div>
      <?php else : ?>
      <div class="form contact-fallback" data-reveal data-delay="2">
        <div class="form-head">
          <h3><?php esc_html_e( 'Send a message', 'foliocraft' ); ?></h3>
          <p><?php esc_html_e( '// usually replies within a day or two', 'foliocraft' ); ?></p>
        </div>
        <p class="contact-fallback-lead"><?php esc_html_e( "The fastest way to reach me is email — drop me a line and I'll get back to you soon.", 'foliocraft' ); ?></p>
        <?php if ( ! foliocraft_blank( $social['email'] ) ) : ?>
        <a class="btn btn--primary" href="<?php echo esc_url( 'mailto:' . $social['email'] ); ?>">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg>
          <?php esc_html_e( 'Email me', 'foliocraft' ); ?>
        </a>
        <?php endif; ?>
        <?php if ( is_customize_preview() ) : ?>
        <p class="contact-form-hint"><?php esc_html_e( 'Tip: paste a form-plugin shortcode in Customizer → Portfolio → Contact → “Contact form shortcode” (e.g. [fluentform id="1"]) to replace this email button with a real form.', 'foliocraft' ); ?></p>
        <?php endif; ?>
      </div>
      <?php endif; ?>
    </div>
  </div>
</section>
