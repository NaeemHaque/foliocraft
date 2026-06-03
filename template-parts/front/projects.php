<?php defined( 'ABSPATH' ) || exit; ?>

<section class="section" id="work">
	<div class="wrap">
		<p class="eyebrow" data-reveal><span class="num">03 /</span> <?php esc_html_e( 'Selected Work', 'naeem-portfolio' ); ?></p>
		<div class="proj-head">
			<h2 class="section-title" data-reveal data-delay="1" style="margin:0;"><?php esc_html_e( "Things I've built.", 'naeem-portfolio' ); ?></h2>
			<div class="filter-bar" data-reveal data-delay="2" id="filterBar" role="tablist" aria-label="Filter projects by technology">
				<button class="filter-btn active" data-filter="all">all</button>
				<button class="filter-btn" data-filter="wordpress">WordPress</button>
				<button class="filter-btn" data-filter="laravel">Laravel</button>
				<button class="filter-btn" data-filter="vue">Vue</button>
				<button class="filter-btn" data-filter="php">PHP</button>
				<button class="filter-btn" data-filter="mysql">MySQL</button>
			</div>
		</div>
		<div class="proj-grid" id="projGrid">
			<?php foreach ( $projects as $project ) { \Naeem\Core\View::render( 'cards/project-card', $project ); } ?>
		</div>
	</div>
</section>
