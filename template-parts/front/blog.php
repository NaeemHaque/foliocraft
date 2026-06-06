<?php defined( 'ABSPATH' ) || exit; ?>

<section class="section section--tight" id="writing">
	<div class="wrap">
		<div class="proj-head">
			<div>
				<?php foliocraft_eyebrow( 'writing' ); ?>
				<?php if ( ! foliocraft_blank( $profile['titles']['writing']['heading'] ) ) : ?><h2 class="section-title" data-reveal data-delay="1" style="margin:0;"><?php echo esc_html( $profile['titles']['writing']['heading'] ); ?></h2><?php endif; ?>
			</div>
			<a href="<?php echo esc_url( $blog_url ); ?>" class="btn btn--ghost" data-reveal data-delay="2">
				<?php esc_html_e( 'All posts', 'foliocraft' ); ?>
				<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
			</a>
		</div>
		<div class="blog-grid">
			<?php foreach ( $posts as $post ) { \FolioCraft\Core\View::render( 'cards/post-card', $post ); } ?>
		</div>
	</div>
</section>
