<?php defined( 'ABSPATH' ) || exit; ?>

<section class="section section--tight" id="experience">
	<div class="wrap">
		<?php foliocraft_eyebrow( 'experience' ); ?>
		<?php if ( ! foliocraft_blank( $profile['titles']['experience']['heading'] ) ) : ?><h2 class="section-title" data-reveal data-delay="1"><?php echo esc_html( $profile['titles']['experience']['heading'] ); ?></h2><?php endif; ?>
		<div class="timeline">
			<?php foreach ( $items as $item ) { \FolioCraft\Core\View::render( 'cards/timeline-item', $item ); } ?>
		</div>
	</div>
</section>
