<?php defined( 'ABSPATH' ) || exit; ?>

<div class="tl-item<?php echo $current ? ' tl-featured' : ''; ?>" data-reveal<?php echo $current ? ' data-delay="1"' : ''; ?>>
	<div class="tl-when">
		<?php if ( $current ) : ?>
			<span class="now">&#x25CF; <?php echo esc_html( $when ); ?></span>
		<?php else : ?>
			<?php echo esc_html( $when ); ?>
		<?php endif; ?>
	</div>
	<div class="tl-main">
		<h3 class="tl-role"><?php echo esc_html( $role ); ?></h3>
		<p class="tl-co"><?php echo esc_html( $company ); ?></p>
		<p><?php echo esc_html( $desc ); ?></p>
		<div class="tl-tags">
			<?php foreach ( $tags as $tag ) : ?>
				<span class="chip<?php echo $current ? ' chip--accent' : ''; ?>"><?php echo esc_html( $tag ); ?></span>
			<?php endforeach; ?>
		</div>
	</div>
</div>
