<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package Andromeda
 */

get_header(); ?>

	<?php
	if ( is_front_page() && andromeda_has_featured_posts() ) {
		// Include the featured content template.
		get_template_part( 'partial/featured-post' );
	}
	?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">

		<header class="page-header">
			<h2 class="page-title"><?php _e( 'Recent Posts', 'andromeda' ); ?></h2>
		</header><!-- .page-header -->

		<div class="recent-posts">

		<?php if ( have_posts() ) : ?>

			<?php /* Start the Loop */ ?>
			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					get_template_part( 'partial/content', get_post_format() );
				?>

			<?php endwhile; ?>

			<nav class="navigation posts-navigation" role="navigation">
				<h2 class="screen-reader-text">Posts navigation</h2>
				<div class="nav-links">
					<?php if ( get_next_posts_link() ) : ?>
					<div class="nav-previous"><?php next_posts_link( __( 'See all recent posts', 'andromeda' ) ); ?></div>
					<?php endif; ?>
				</div>
			</nav>

		<?php else : ?>

			<?php get_template_part( 'partial/content', 'none' ); ?>

		<?php endif; ?>

		</div><!-- /.recent-posts -->

		<?php
			// Include the category sections
			get_template_part( 'partial/featured-category' );
		?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php get_sidebar(); ?>
<?php get_footer(); ?>
