<?php
defined( 'ABSPATH' ) || exit;
$p    = $profile;
$role = ! empty( $p['hero']['roles'] ) ? $p['hero']['roles'][0] : '';
?>
<article <?php post_class( 'resume wrap' ); ?>>
	<header class="resume-head">
		<div>
			<h1><?php echo esc_html( $p['identity']['name'] ); ?></h1>
			<div class="role"><?php echo esc_html( $role ); ?></div>
		</div>
		<?php if ( ! empty( $p['resume']['url'] ) ) : ?>
			<a class="btn btn--primary" href="<?php echo esc_url( $p['resume']['url'] ); ?>" target="_blank" rel="noopener"><?php echo esc_html( $p['resume']['label'] ); ?></a>
		<?php endif; ?>
	</header>

	<section class="resume-section">
		<h2><?php esc_html_e( 'Experience', 'foliocraft' ); ?></h2>
		<div class="timeline">
			<?php foreach ( $experience as $item ) { \FolioCraft\Core\View::render( 'cards/timeline-item', $item ); } ?>
		</div>
	</section>

	<section class="resume-section">
		<h2><?php esc_html_e( 'Tech Stack', 'foliocraft' ); ?></h2>
		<div class="skill-grid">
			<?php foreach ( $p['skills']['groups'] as $group ) : ?>
				<div class="skill-group">
					<div class="gh"><h3><?php echo esc_html( $group['label'] ); ?></h3></div>
					<div class="skill-list"><?php foreach ( $group['pills'] as $pill ) : ?><span class="skill-pill"><?php echo esc_html( $pill ); ?></span><?php endforeach; ?></div>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<section class="resume-section">
		<h2><?php esc_html_e( 'Contact', 'foliocraft' ); ?></h2>
		<p class="section-lead"><a href="<?php echo esc_url( 'mailto:' . $p['social']['email'] ); ?>"><?php echo esc_html( $p['social']['email'] ); ?></a> · <a href="<?php echo esc_url( $p['social']['github'] ); ?>" target="_blank" rel="noopener">GitHub</a> · <a href="<?php echo esc_url( $p['social']['linkedin'] ); ?>" target="_blank" rel="noopener">LinkedIn</a></p>
	</section>
</article>
