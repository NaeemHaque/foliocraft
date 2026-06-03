<?php defined( 'ABSPATH' ) || exit; ?>

<section class="section section--tight" id="writing">
	<div class="wrap">
		<div class="proj-head">
			<div>
				<p class="eyebrow" data-reveal><span class="num">06 /</span> <?php esc_html_e( 'Writing', 'naeem-portfolio' ); ?></p>
				<h2 class="section-title" data-reveal data-delay="1" style="margin:0;"><?php esc_html_e( 'From the blog.', 'naeem-portfolio' ); ?></h2>
			</div>
			<a href="<?php echo esc_url( $blog_url ); ?>" class="btn btn--ghost" data-reveal data-delay="2">
				<?php esc_html_e( 'All posts', 'naeem-portfolio' ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		</div>
		<div class="blog-grid">
			<?php foreach ( $posts as $post ) { \Naeem\Core\View::render( 'cards/post-card', array_merge( $post, array( 'blog_url' => $blog_url ) ) ); } ?>
		</div>
	</div>
</section>
