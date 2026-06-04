<?php
defined( 'ABSPATH' ) || exit;
$cats  = get_the_category();
$cat   = ! empty( $cats ) ? $cats[0]->name : __( 'Post', 'naeem-portfolio' );
$words = str_word_count( wp_strip_all_tags( get_the_content() ) );
$mins  = max( 1, (int) ceil( $words / 200 ) );
?>
<a class="post" href="<?php the_permalink(); ?>">
	<div class="pmeta"><span class="cat"><?php echo esc_html( $cat ); ?></span><span>·</span><span><?php /* translators: %d minutes */ echo esc_html( sprintf( __( '%d min read', 'naeem-portfolio' ), $mins ) ); ?></span></div>
	<h3><?php the_title(); ?></h3>
	<p><?php echo esc_html( get_the_excerpt() ); ?></p>
	<div class="post-meta-foot"><span><?php echo esc_html( get_the_date() ); ?></span></div>
	<span class="more"><?php esc_html_e( 'Read post', 'naeem-portfolio' ); ?> <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14M13 6l6 6-6 6"/></svg></span>
</a>
