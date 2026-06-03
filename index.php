<?php
/**
 * Fallback template.
 *
 * @package Naeem_Portfolio
 */

defined( 'ABSPATH' ) || exit;

get_header();
?>
<div class="wrap section">
	<h1 class="section-title"><?php esc_html_e( 'Naeem Portfolio', 'naeem-portfolio' ); ?></h1>
	<p class="section-lead"><?php esc_html_e( 'Theme scaffold active. Front-page sections arrive in Phase 1.', 'naeem-portfolio' ); ?></p>
</div>
<?php
get_footer();
