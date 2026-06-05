<?php
/**
 * Theme header.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?> data-motion="medium">
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<script>
  /* Set theme + enable reveal animations before paint (avoids FOUC). */
  (function () {
    try {
      var t = localStorage.getItem('foliocraft-theme') || 'dark';
      document.documentElement.setAttribute('data-theme', t);
    } catch (e) { document.documentElement.setAttribute('data-theme', 'dark'); }
    try {
      if (!window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
        document.documentElement.classList.add('reveal-on');
      }
    } catch (e) {}
  })();
</script>
<?php wp_head(); ?>
<?php
$foliocraft_profile = \FolioCraft\Models\Profile::all();
if ( ! empty( $foliocraft_profile['accent'] ) && strtolower( $foliocraft_profile['accent'] ) !== '#e6926b' ) {
	echo '<style>:root{--accent:' . esc_html( $foliocraft_profile['accent'] ) . ';}</style>';
}
?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#top"><?php esc_html_e( 'Skip to content', 'foliocraft' ); ?></a>
<?php
\FolioCraft\Core\View::render( 'layout/progress' );
\FolioCraft\Core\View::render( 'layout/nav', array( 'profile' => $foliocraft_profile ) );
\FolioCraft\Core\View::render( 'layout/mobile-menu' );
?>
<main id="top">
