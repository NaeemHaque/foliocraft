<?php
defined( 'ABSPATH' ) || exit;
$blog = get_permalink( get_option( 'page_for_posts' ) );
?>
<section class="notfound wrap">
	<div>
		<p class="code">404</p>
		<h1><?php esc_html_e( 'Page not found.', 'naeem-portfolio' ); ?></h1>
		<p class="section-lead" style="margin-inline:auto;"><?php esc_html_e( "That page slipped through the cracks — let's get you back on track.", 'naeem-portfolio' ); ?></p>
		<div class="cta">
			<a class="btn btn--primary" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'Back home', 'naeem-portfolio' ); ?></a>
			<?php if ( $blog ) : ?><a class="btn" href="<?php echo esc_url( $blog ); ?>"><?php esc_html_e( 'All posts', 'naeem-portfolio' ); ?></a><?php endif; ?>
		</div>
		<div style="margin-top:28px;max-width:420px;margin-inline:auto;"><?php get_search_form(); ?></div>
	</div>
</section>
