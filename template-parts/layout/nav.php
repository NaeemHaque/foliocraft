<?php
/**
 * Primary navigation header.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;
$profile = isset( $profile ) ? $profile : \Naeem\Models\Profile::all();
?>
<header class="nav" id="nav">
  <a href="#top" class="brand" aria-label="Golam Sarwer Naeem — home">
    <span class="mark"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="m7 8 4 4-4 4"/><path d="M13 16h4"/></svg></span>
    <span><?php echo esc_html( $profile['identity']['brand'] ); ?><span class="dim">.dev</span></span>
  </a>
  <nav class="nav-links" id="navLinks" aria-label="Primary">
    <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#about"><span class="hash">#</span>about</a>
    <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#experience"><span class="hash">#</span>experience</a>
    <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#work"><span class="hash">#</span>work</a>
    <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#opensource"><span class="hash">#</span>open-source</a>
    <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#stack"><span class="hash">#</span>stack</a>
    <a href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#writing"><span class="hash">#</span>writing</a>
  </nav>
  <div class="nav-right">
    <a class="btn btn--ghost" href="<?php echo is_front_page() ? '' : esc_url( home_url( '/' ) ); ?>#contact" id="contactTop">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16v16H4z"/><path d="m4 6 8 6 8-6"/></svg>
      <?php esc_html_e( 'Contact', 'naeem-portfolio' ); ?>
    </a>
    <button class="icon-btn theme-toggle" id="themeToggle" aria-label="Toggle color theme">
      <svg class="sun" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4.5"/><path d="M12 2v2M12 20v2M4.9 4.9l1.4 1.4M17.7 17.7l1.4 1.4M2 12h2M20 12h2M4.9 19.1l1.4-1.4M17.7 6.3l1.4-1.4"/></svg>
      <svg class="moon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.8A9 9 0 1 1 11.2 3a7 7 0 0 0 9.8 9.8z"/></svg>
    </button>
    <button class="icon-btn menu-btn" id="menuBtn" aria-label="Open menu">
      <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"><line x1="3" y1="7" x2="21" y2="7"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="17" x2="21" y2="17"/></svg>
    </button>
  </div>
</header>
