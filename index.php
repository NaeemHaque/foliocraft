<?php
/**
 * Fallback template (replaced with header/footer wiring in Task 5).
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<p>Theme active.</p>
<?php wp_footer(); ?>
