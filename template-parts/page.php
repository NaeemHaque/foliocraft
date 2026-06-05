<?php defined( 'ABSPATH' ) || exit; ?>
<article <?php post_class( 'page-body wrap' ); ?>>
	<h1 class="post-single-title"><?php the_title(); ?></h1>
	<div class="post-content"><?php the_content(); ?></div>
	<?php wp_link_pages( array( 'before' => '<nav class="page-links" aria-label="' . esc_attr__( 'Page sections', 'foliocraft' ) . '">' . esc_html__( 'Pages:', 'foliocraft' ) . ' ', 'after' => '</nav>' ) ); ?>
</article>
