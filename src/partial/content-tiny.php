<?php
/**
 * @package Andromeda
 */
?>

<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
	<div class="entry-image">
		<?php printf( '<a href="%s" rel="bookmark">', esc_url( get_permalink() ) ); ?>
		<?php the_post_thumbnail( 'small' ); ?>
		<?php printf( '</a>' ); ?>
	</div>

	<header class="entry-header">
		<?php if ( 'post' == get_post_type() ) : ?>
		<div class="entry-meta">
			<?php andromeda_posted_on(); ?>
		</div><!-- .entry-meta -->
		<?php endif; ?>

		<?php the_title( sprintf( '<h2 class="entry-title"><a href="%s" rel="bookmark">', esc_url( get_permalink() ) ), '</a></h2>' ); ?>
	</header><!-- .entry-header -->
</article><!-- #post-## -->