<?php defined( 'ABSPATH' ) || exit; ?>
<main class="blog-page">
	<div class="wrap blog-header">
		<p class="eyebrow"><span class="num"><?php echo esc_html( $num ); ?></span> <?php echo esc_html( $eyebrow ); ?></p>
		<h1 class="section-title"><?php echo esc_html( $title ); ?></h1>
		<p class="section-lead"><?php echo esc_html( $lead ); ?></p>
		<div class="blog-filter" role="tablist" aria-label="<?php esc_attr_e( 'Filter posts by topic', 'foliocraft' ); ?>">
			<?php $posts_page = (int) get_option( 'page_for_posts' ); ?>
			<a class="filter-btn<?php echo ( ! is_category() && ! is_tag() ) ? ' active' : ''; ?>" href="<?php echo esc_url( get_permalink( $posts_page ) ); ?>"><?php esc_html_e( 'all', 'foliocraft' ); ?></a>
			<?php foreach ( get_categories( array( 'hide_empty' => true ) ) as $cat ) : ?>
				<a class="filter-btn<?php echo is_category( $cat->term_id ) ? ' active' : ''; ?>" href="<?php echo esc_url( get_category_link( $cat ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
			<?php endforeach; ?>
		</div>
	</div>
	<div class="wrap">
		<div class="blog-grid">
			<?php if ( have_posts() ) : ?>
				<?php while ( have_posts() ) : the_post(); ?>
					<?php \FolioCraft\Core\View::render( 'cards/post-card-full' ); ?>
				<?php endwhile; ?>
			<?php else : ?>
				<p class="section-lead"><?php esc_html_e( 'No posts yet.', 'foliocraft' ); ?></p>
			<?php endif; ?>
		</div>
		<?php
		the_posts_pagination(
			array(
				'mid_size'  => 1,
				'prev_text' => __( '← Prev', 'foliocraft' ),
				'next_text' => __( 'Next →', 'foliocraft' ),
			)
		);
		?>
		<div class="blog-foot"><p class="note"><?php esc_html_e( "// you've reached the end of the feed — more shipping soon.", 'foliocraft' ); ?></p></div>
	</div>
</main>
