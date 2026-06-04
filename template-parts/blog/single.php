<?php
defined( 'ABSPATH' ) || exit;
$posts_page = get_permalink( get_option( 'page_for_posts' ) );
$cats       = get_the_category();
$cat        = ! empty( $cats ) ? $cats[0]->name : __( 'Post', 'naeem-portfolio' );
$words      = str_word_count( wp_strip_all_tags( get_the_content() ) );
$mins       = max( 1, (int) ceil( $words / 200 ) );
?>
<article class="post-single wrap">
	<?php if ( $posts_page ) : ?>
		<a class="back" href="<?php echo esc_url( $posts_page ); ?>"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 12H5M11 18l-6-6 6-6"/></svg> <?php esc_html_e( 'All posts', 'naeem-portfolio' ); ?></a>
	<?php endif; ?>
	<div class="pmeta"><span class="cat"><?php echo esc_html( $cat ); ?></span><span>·</span><span><?php echo esc_html( get_the_date() ); ?></span><span>·</span><span><?php /* translators: %d minutes */ echo esc_html( sprintf( __( '%d min read', 'naeem-portfolio' ), $mins ) ); ?></span></div>
	<h1 class="post-single-title"><?php the_title(); ?></h1>
	<?php if ( has_post_thumbnail() ) { the_post_thumbnail( 'large', array( 'class' => 'post-single-cover' ) ); } ?>
	<div class="post-content"><?php the_content(); ?></div>
	<?php the_post_navigation( array( 'prev_text' => '&larr; %title', 'next_text' => '%title &rarr;' ) ); ?>
</article>
