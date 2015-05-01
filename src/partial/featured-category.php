<?php
$categories = andromeda_get_featured_categories();

foreach ( $categories as $category ) {
	$cat_query = new WP_Query( array(
		'cat' => $category,
		'posts_per_page' => 6,
	) );
	if ( $cat_query->have_posts() ) : ?>
	<div class="<?php andromeda_featured_cats_classes( $cat_query ); ?>">
		<header class="section-header">
			<h1 class="section-title"><?php andromeda_single_term_title( $category, 'category' ); ?></h1>
		</header><!-- .page-header -->

		<?php while ( $cat_query->have_posts() ) {
			$cat_query->the_post();
			if ( $cat_query->post_count == 3 ) {
				get_template_part( 'partial/content', 'tiny' );
			} elseif ( ( $cat_query->post_count == 4 ) || ( $cat_query->current_post < 2 ) ) {
				get_template_part( 'partial/content', 'short' );
			} else {
				get_template_part( 'partial/content', 'tiny' );
			}
		} ?>

		<nav class="navigation posts-navigation" role="navigation">
			<div class="nav-links">
				<?php if ( get_next_posts_link() ) : ?>
				<div class="nav-previous"><?php next_posts_link( sprintf( __( 'See all %s', 'andromeda' ), andromeda_single_term_title( $category, 'category', false ) ) ); ?></div>
				<?php endif; ?>
			</div>
		</nav>
	</div>
	<?php endif; wp_reset_postdata();
}
