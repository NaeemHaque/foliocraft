<?php defined( 'ABSPATH' ) || exit; ?>
<?php // Phase 3: contact copy + channels become Customizer-driven; Phase 5 wires the real form handler. ?>
<?php
$contact = isset( $profile['contact'] ) ? $profile['contact'] : \FolioCraft\Models\Profile::all()['contact'];
$social  = isset( $profile['social'] ) ? $profile['social'] : \FolioCraft\Models\Profile::all()['social'];
?>
<!-- ============ CONTACT ============ -->
<section class="section contact" id="contact">
  <div class="wrap">
    <p class="eyebrow" data-reveal><span class="num">07 /</span> <?php esc_html_e( 'Contact', 'foliocraft' ); ?></p>
    <div class="contact-grid">
      <div class="contact-left" data-reveal data-delay="1">
        <h2 class="section-title"><?php echo esc_html( $contact['heading'] ); ?></h2>
        <p class="section-lead"><?php echo esc_html( $contact['lead'] ); ?></p>
        <div class="contact-channels">
          <span class="ch-label"><?php esc_html_e( 'Direct lines', 'foliocraft' ); ?></span>
          <a class="channel" href="<?php echo esc_url( 'mailto:' . $social['email'] ); ?>">
            <span class="ico"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="5" width="18" height="14" rx="2"/><path d="m3 7 9 6 9-6"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'Email', 'foliocraft' ); ?></span><span class="v"><?php echo esc_html( $social['email'] ); ?></span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <a class="channel" href="<?php echo esc_url( $social['github'] ); ?>" target="_blank" rel="noopener">
            <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.58 2 12.25c0 4.53 2.87 8.37 6.84 9.73.5.1.68-.22.68-.49v-1.7c-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.5-1.11-1.5-.91-.64.07-.62.07-.62 1 .07 1.53 1.06 1.53 1.06.89 1.57 2.34 1.12 2.91.86.09-.66.35-1.12.63-1.38-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.3.1-2.71 0 0 .84-.28 2.75 1.05a9.3 9.3 0 0 1 5 0c1.91-1.33 2.75-1.05 2.75-1.05.55 1.41.2 2.45.1 2.71.64.72 1.03 1.63 1.03 2.75 0 3.94-2.34 4.81-4.57 5.06.36.32.68.94.68 1.9v2.82c0 .27.18.6.69.49A10.26 10.26 0 0 0 22 12.25C22 6.58 17.52 2 12 2z"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'GitHub', 'foliocraft' ); ?></span><span class="v">github.com/yourusername</span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <a class="channel" href="<?php echo esc_url( $social['linkedin'] ); ?>" target="_blank" rel="noopener">
            <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'LinkedIn', 'foliocraft' ); ?></span><span class="v">in/yourusername</span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
          <a class="channel" href="<?php echo esc_url( $social['x'] ); ?>" target="_blank" rel="noopener">
            <span class="ico"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg></span>
            <span class="ct"><span class="l"><?php esc_html_e( 'X', 'foliocraft' ); ?></span><span class="v">@yourusername</span></span>
            <span class="arrow"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M7 17 17 7M9 7h8v8"/></svg></span>
          </a>
        </div>
      </div>

      <?php if ( empty( $contact['fluent_shortcode'] ) ) : ?>
      <form class="form" id="contactForm" method="post" action="<?php echo esc_url( admin_url( 'admin-post.php' ) ); ?>" novalidate data-reveal data-delay="2">
        <input type="hidden" name="action" value="foliocraft_contact" />
        <?php wp_nonce_field( 'foliocraft_contact', 'foliocraft_contact_nonce' ); ?>
        <p class="foliocraft-hp" aria-hidden="true"><label>Website <input type="text" name="foliocraft_website" tabindex="-1" autocomplete="off" /></label></p>
        <div class="form-head">
          <h3><?php esc_html_e( 'Send a message', 'foliocraft' ); ?></h3>
          <p><?php esc_html_e( '// usually replies within a day or two', 'foliocraft' ); ?></p>
        </div>
        <div class="field" id="f-name">
          <label for="name"><?php esc_html_e( 'Name', 'foliocraft' ); ?> <span class="req">*</span></label>
          <input type="text" id="name" name="name" placeholder="<?php esc_attr_e( 'Your name', 'foliocraft' ); ?>" autocomplete="name" />
          <span class="err"><?php esc_html_e( 'Please enter your name.', 'foliocraft' ); ?></span>
        </div>
        <div class="field" id="f-email">
          <label for="email"><?php esc_html_e( 'Email', 'foliocraft' ); ?> <span class="req">*</span></label>
          <input type="email" id="email" name="email" placeholder="<?php esc_attr_e( 'you@company.com', 'foliocraft' ); ?>" autocomplete="email" />
          <span class="err"><?php esc_html_e( 'Please enter a valid email.', 'foliocraft' ); ?></span>
        </div>
        <div class="field" id="f-message">
          <label for="message"><?php esc_html_e( 'Message', 'foliocraft' ); ?> <span class="req">*</span></label>
          <textarea id="message" name="message" placeholder="<?php esc_attr_e( 'What are you building?', 'foliocraft' ); ?>"></textarea>
          <span class="err"><?php esc_html_e( 'A little more detail, please (10+ characters).', 'foliocraft' ); ?></span>
        </div>
        <div class="form-foot">
          <button type="submit" class="btn btn--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 2 11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
            <?php esc_html_e( 'Send message', 'foliocraft' ); ?>
          </button>
          <span class="form-note"><?php esc_html_e( 'Your message comes straight to my inbox.', 'foliocraft' ); ?></span>
        </div>
        <div class="form-ok<?php echo ( isset( $_GET['contact'] ) && 'sent' === $_GET['contact'] ) ? ' show' : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended -- display-only flag ?>" id="formOk">
          <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
          <span><?php esc_html_e( 'Thanks — your message is on its way. I\'ll get back to you soon.', 'foliocraft' ); ?></span>
        </div>
        <?php if ( isset( $_GET['contact'] ) && 'error' === $_GET['contact'] ) : // phpcs:ignore WordPress.Security.NonceVerification.Recommended ?>
        <div class="form-err"><?php esc_html_e( 'Something went wrong — please try again or email me directly.', 'foliocraft' ); ?></div>
        <?php endif; ?>
      </form>
      <?php else : ?>
      <div class="form"><?php echo do_shortcode( $contact['fluent_shortcode'] ); ?></div>
      <?php endif; ?>
    </div>
  </div>
</section>
