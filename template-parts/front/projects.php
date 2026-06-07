<?php defined( 'ABSPATH' ) || exit;
$terms    = isset( $terms ) ? $terms : array();
$projects = isset( $projects ) ? $projects : array();
?>

<section class="section" id="work">
	<div class="wrap">
		<?php foliocraft_eyebrow( 'work' ); ?>
		<div class="proj-head">
			<?php if ( ! foliocraft_blank( $profile['titles']['work']['heading'] ) ) : ?><h2 class="section-title" data-reveal data-delay="1" style="margin:0;"><?php echo esc_html( $profile['titles']['work']['heading'] ); ?></h2><?php endif; ?>
			<div class="filter-bar" data-reveal data-delay="2" id="filterBar" role="tablist" aria-label="<?php esc_attr_e( 'Filter projects by technology', 'foliocraft' ); ?>">
				<button class="filter-btn active" data-filter="all">all</button>
				<?php foreach ( $terms as $term ) : ?><button class="filter-btn" data-filter="<?php echo esc_attr( $term['slug'] ); ?>"><?php echo esc_html( $term['name'] ); ?></button><?php endforeach; ?>
			</div>
		</div>
		<div class="proj-grid" id="projGrid">
			<?php if ( empty( $projects ) ) : ?>
			  <p class="section-lead"><?php esc_html_e( 'Projects coming soon.', 'foliocraft' ); ?></p>
			<?php else : ?>
			  <?php foreach ( $projects as $project ) { \FolioCraft\Core\View::render( 'cards/project-card', $project ); } ?>
			<?php endif; ?>
		</div>
	</div>
</section>
