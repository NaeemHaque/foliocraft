<?php defined( 'ABSPATH' ) || exit; ?>

<section class="section section--tight" id="experience">
	<div class="wrap">
		<p class="eyebrow" data-reveal><span class="num">02 /</span> <?php esc_html_e( 'Experience', 'foliocraft' ); ?></p>
		<h2 class="section-title" data-reveal data-delay="1"><?php esc_html_e( "Where I've shipped.", 'foliocraft' ); ?></h2>
		<div class="timeline">
			<?php foreach ( $items as $item ) { \FolioCraft\Core\View::render( 'cards/timeline-item', $item ); } ?>
		</div>
	</div>
</section>
