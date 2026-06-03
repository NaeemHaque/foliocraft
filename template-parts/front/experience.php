<?php defined( 'ABSPATH' ) || exit; ?>

<section class="section section--tight" id="experience">
	<div class="wrap">
		<p class="eyebrow" data-reveal><span class="num">02 /</span> <?php esc_html_e( 'Experience', 'naeem-portfolio' ); ?></p>
		<h2 class="section-title" data-reveal data-delay="1"><?php esc_html_e( "Where I've shipped.", 'naeem-portfolio' ); ?></h2>
		<div class="timeline">
			<?php foreach ( $items as $item ) { \Naeem\Core\View::render( 'cards/timeline-item', $item ); } ?>
		</div>
	</div>
</section>
