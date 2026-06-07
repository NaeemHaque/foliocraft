<?php
/**
 * Site footer.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;
$profile = isset( $profile ) ? $profile : \FolioCraft\Models\Profile::all();
?>
<footer class="footer">
  <div class="wrap footer-in">
    <span class="copy">© <span id="year"><?php echo esc_html( gmdate( 'Y' ) ); ?></span> <?php echo esc_html( $profile['identity']['name'] ); ?> · <?php echo wp_kses_post( $profile['footer']['copy'] ); ?></span>
    <div class="footer-links">
      <?php if ( ! foliocraft_blank( $profile['social']['github'] ) ) : ?>
      <a href="<?php echo esc_url( $profile['social']['github'] ); ?>" target="_blank" rel="noopener" aria-label="GitHub"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M12 2C6.48 2 2 6.58 2 12.25c0 4.53 2.87 8.37 6.84 9.73.5.1.68-.22.68-.49v-1.7c-2.78.62-3.37-1.22-3.37-1.22-.45-1.18-1.11-1.5-1.11-1.5-.91-.64.07-.62.07-.62 1 .07 1.53 1.06 1.53 1.06.89 1.57 2.34 1.12 2.91.86.09-.66.35-1.12.63-1.38-2.22-.26-4.56-1.14-4.56-5.07 0-1.12.39-2.03 1.03-2.75-.1-.26-.45-1.3.1-2.71 0 0 .84-.28 2.75 1.05a9.3 9.3 0 0 1 5 0c1.91-1.33 2.75-1.05 2.75-1.05.55 1.41.2 2.45.1 2.71.64.72 1.03 1.63 1.03 2.75 0 3.94-2.34 4.81-4.57 5.06.36.32.68.94.68 1.9v2.82c0 .27.18.6.69.49A10.26 10.26 0 0 0 22 12.25C22 6.58 17.52 2 12 2z"/></svg><?php foliocraft_new_tab(); ?></a>
      <?php endif; ?>
      <?php if ( ! foliocraft_blank( $profile['social']['linkedin'] ) ) : ?>
      <a href="<?php echo esc_url( $profile['social']['linkedin'] ); ?>" target="_blank" rel="noopener" aria-label="LinkedIn"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M20.45 20.45h-3.56v-5.57c0-1.33-.02-3.04-1.85-3.04-1.85 0-2.14 1.45-2.14 2.94v5.67H9.35V9h3.42v1.56h.05c.48-.9 1.64-1.85 3.37-1.85 3.6 0 4.27 2.37 4.27 5.46v6.28zM5.34 7.43a2.06 2.06 0 1 1 0-4.13 2.06 2.06 0 0 1 0 4.13zM7.12 20.45H3.56V9h3.56v11.45zM22.22 0H1.77C.79 0 0 .77 0 1.72v20.56C0 23.23.79 24 1.77 24h20.45c.98 0 1.78-.77 1.78-1.72V1.72C24 .77 23.2 0 22.22 0z"/></svg><?php foliocraft_new_tab(); ?></a>
      <?php endif; ?>
      <?php if ( ! foliocraft_blank( $profile['social']['x'] ) ) : ?>
      <a href="<?php echo esc_url( $profile['social']['x'] ); ?>" target="_blank" rel="noopener" aria-label="X"><svg viewBox="0 0 24 24" fill="currentColor"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg><?php foliocraft_new_tab(); ?></a>
      <?php endif; ?>
      <a href="#top" aria-label="<?php esc_attr_e( 'Back to top', 'foliocraft' ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 19V5M5 12l7-7 7 7"/></svg></a>
    </div>
  </div>
</footer>
