<?php
/**
 * Mobile menu.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mobile-menu" id="mobileMenu">
  <button class="icon-btn close" id="menuClose" aria-label="<?php esc_attr_e( 'Close menu', 'foliocraft' ); ?>">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
  </button>
  <?php foliocraft_primary_menu( 'mobile-menu-list' ); ?>
  <a href="<?php echo esc_url( ( is_front_page() ? '' : home_url( '/' ) ) . '#contact' ); ?>"><span class="hash">#</span><?php echo esc_html_x( 'contact', 'nav item', 'foliocraft' ); ?></a>
</div>
