<?php
/**
 * Mobile menu.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;
?>
<div class="mobile-menu" id="mobileMenu">
  <button class="icon-btn close" id="menuClose" aria-label="Close menu">
    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="6" y1="6" x2="18" y2="18"/><line x1="18" y1="6" x2="6" y2="18"/></svg>
  </button>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#about"><span class="hash">#</span>about</a>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#experience"><span class="hash">#</span>experience</a>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#work"><span class="hash">#</span>work</a>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#opensource"><span class="hash">#</span>open-source</a>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#stack"><span class="hash">#</span>stack</a>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#writing"><span class="hash">#</span>writing</a>
  <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#contact"><span class="hash">#</span>contact</a>
</div>
