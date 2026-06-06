<?php
/**
 * Theme header.
 *
 * @package FolioCraft
 */

defined( 'ABSPATH' ) || exit;
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<?php wp_head(); ?>
<?php $foliocraft_profile = \FolioCraft\Models\Profile::all(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="skip-link screen-reader-text" href="#top"><?php esc_html_e( 'Skip to content', 'foliocraft' ); ?></a>
<?php if ( has_header_image() ) : ?>
<div class="site-header-image"><img src="<?php echo esc_url( get_header_image() ); ?>" alt="" /></div>
<?php endif; ?>
<?php
\FolioCraft\Core\View::render( 'layout/progress' );
\FolioCraft\Core\View::render( 'layout/nav', array( 'profile' => $foliocraft_profile ) );
\FolioCraft\Core\View::render( 'layout/mobile-menu' );
?>
<main id="top">
